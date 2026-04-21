<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        // If a redirect_to query is present (e.g., coming from buy page), set intended URL
        $redirect = $this->normalizeIntendedUrl($request, $request->query('redirect_to'));
        if ($redirect) {
            $request->session()->put('url.intended', $redirect);
        }
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $request->authenticate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Attach a friendly error message and redirect back with old input
            return redirect()->back()->withInput($request->only('email', 'remember'))->withErrors($e->errors())->with('error', 'Login gagal. Periksa email dan password Anda.');
        }

        $request->session()->regenerate();

        $normalizedIntended = $this->normalizeIntendedUrl(
            $request,
            (string) $request->session()->get('url.intended', '')
        );
        if ($normalizedIntended) {
            $request->session()->put('url.intended', $normalizedIntended);
        } else {
            $request->session()->forget('url.intended');
        }

        $user = Auth::user();

        if ($user && (($user->is_admin ?? false) || ($user->is_superadmin ?? false))) {
            return redirect()->intended(url('/admin'));
        }

        if ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
            $shouldSendEmailVerification = Config::get('mail.default') !== 'log';
            $statusMessage = 'Akun belum diverifikasi. Silakan cek inbox email kamu untuk link verifikasi.';

            if ($shouldSendEmailVerification) {
                try {
                    $user->sendEmailVerificationNotification();
                } catch (\Throwable $e) {
                    Log::warning('Failed to resend verification email on login', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage(),
                    ]);
                    $statusMessage = 'Akun belum diverifikasi. Kami gagal mengirim email otomatis, silakan klik kirim ulang verifikasi.';
                }
            } else {
                $statusMessage = 'Akun belum diverifikasi. Mailer masih mode log, aktifkan SMTP lalu kirim ulang verifikasi.';
            }

            return redirect()->route('verification.notice')->with('status', $statusMessage);
        }

        // If the authenticated user's email looks like an admin account (ends with @admin),
        // redirect them to the admin dashboard immediately.
        if ($user && is_string($user->email) && str_ends_with(strtolower($user->email), '@admin')) {
            return redirect()->intended(url('/admin'));
        }

        // Non-admin flow: users without package should continue to purchase flow.
        if ($user && method_exists($user, 'hasLmsAccess') && $user->hasLmsAccess()) {
            $defaultRoute = route('lms.entry');
        } elseif ($user && method_exists($user, 'hasCoachingAccess') && $user->hasCoachingAccess()) {
            $defaultRoute = route('coaching.upcoming');
        } else {
            $defaultRoute = route('registerclass');
        }

        $intended = (string) $request->session()->get('url.intended', '');
        if ($intended !== '' && str_contains($intended, '/lms/access-pending')) {
            $request->session()->forget('url.intended');
        }

        return redirect()->intended($defaultRoute);
    }

    private function normalizeIntendedUrl(Request $request, ?string $url): ?string
    {
        if (! is_string($url)) {
            return null;
        }

        $url = trim($url);
        if ($url === '') {
            return null;
        }

        if (str_starts_with($url, '/')) {
            return $url;
        }

        $parsed = parse_url($url);
        if (! is_array($parsed)) {
            return null;
        }

        $host = strtolower((string) ($parsed['host'] ?? ''));
        $currentHost = strtolower($request->getHost());
        if ($host === '' || $host !== $currentHost) {
            return null;
        }

        $path = (string) ($parsed['path'] ?? '/');
        $query = isset($parsed['query']) ? '?' . $parsed['query'] : '';
        $fragment = isset($parsed['fragment']) ? '#' . $parsed['fragment'] : '';

        return $request->getSchemeAndHttpHost() . $path . $query . $fragment;
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

    $request->session()->regenerateToken();

    // after logout, redirect visitors to the public company profile at /ndeofficial
    // use the named route if available so URL generation follows app configuration
    if (function_exists('route')) {
        return redirect()->route('compro');
    }
    return redirect(url('/ndeofficial'));
    }
}
