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
        $redirect = $request->query('redirect_to');
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

        $user = Auth::user();

        if ($user && (($user->is_admin ?? false) || ($user->is_superadmin ?? false))) {
            return redirect()->intended(url('/admin'));
        }

        if ($user && empty($user->google_id)) {
            $shouldSendEmailVerification = Config::get('mail.default') !== 'log';

            if ($shouldSendEmailVerification && $user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
                try {
                    $user->sendEmailVerificationNotification();
                } catch (\Throwable $e) {
                    Log::warning('Failed to resend verification email on login', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            return redirect()->route('verification.notice')->with('status', 'Lanjutkan verifikasi dengan Google memakai email yang sama untuk mengaktifkan akun.');
        }

        // Keep unverified users in signed-in state so they can access verification notice
        // and verify through the signed link flow.
        if ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
            $user->forceFill(['email_verified_at' => now()])->save();
        }

        // If the authenticated user's email looks like an admin account (ends with @admin),
        // redirect them to the admin dashboard immediately.
        if ($user && is_string($user->email) && str_ends_with(strtolower($user->email), '@admin')) {
            return redirect()->intended(url('/admin'));
        }

        // Non-admin flow: users without package should continue to purchase flow.
        $defaultRoute = $user && method_exists($user, 'hasLmsAccess') && $user->hasLmsAccess()
            ? route('lms.entry')
            : route('registerclass');

        $intended = (string) $request->session()->get('url.intended', '');
        if ($intended !== '' && str_contains($intended, '/lms/access-pending')) {
            $request->session()->forget('url.intended');
        }

        return redirect()->intended($defaultRoute);
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
