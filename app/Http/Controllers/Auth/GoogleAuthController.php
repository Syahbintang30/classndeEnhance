<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle(Request $request): RedirectResponse
{
    $clientId = Config::get('services.google.client_id');
    $clientSecret = Config::get('services.google.client_secret');

    if (! $clientId || ! $clientSecret) {
        Log::warning('Google auth misconfigured', [
            'has_client_id' => (bool) $clientId,
            'has_client_secret' => (bool) $clientSecret,
        ]);

        return redirect()
            ->route('login')
            ->with('error', 'Google Login is not configured yet. Please set GOOGLE_CLIENT_ID and GOOGLE_CLIENT_SECRET.');
    }

    // Simpan package_id ke session jika ada
    if ($request->query('package_id')) {
        $request->session()->put('pre_register', [
            'package_id' => (int) $request->query('package_id'),
            'package_qty' => (int) $request->query('package_qty', 1),
        ]);
    }

    /** @var \Laravel\Socialite\Two\GoogleProvider $provider */
    $provider = Socialite::driver('google');

    return $provider
        ->redirectUrl($this->resolveGoogleRedirectUri($request))
        ->redirect();
}

    public function handleGoogleCallback(Request $request): RedirectResponse
    {
        try {
            /** @var \Laravel\Socialite\Two\GoogleProvider $provider */
            $provider = Socialite::driver('google');

            $googleUser = $provider
                ->redirectUrl($this->resolveGoogleRedirectUri($request))
                ->user();
        } catch (\Throwable $e) {
            Log::warning('Google auth callback failed', [
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('login')->with('error', 'Google login failed. Please try again.');
        }

        $email = strtolower(trim((string) $googleUser->getEmail()));
        if ($email === '') {
            return redirect()->route('login')->with('error', 'Your Google account does not have a valid email address.');
        }

        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $email)
            ->first();

        if (! $user) {
            $user = User::create([
                'name' => $googleUser->getName() ?: 'Google User',
                'email' => $email,
                'password' => Hash::make(Str::random(40)),
                'google_id' => $googleUser->getId(),
                'photo' => $googleUser->getAvatar() ?: null,
                'email_verified_at' => now(),
            ]);
        } else {
            $updates = [];

            if (empty($user->google_id)) {
                $updates['google_id'] = $googleUser->getId();
            }

            if (! $user->hasVerifiedEmail()) {
                $updates['email_verified_at'] = now();
            }

            if (empty($user->photo) && $googleUser->getAvatar()) {
                $updates['photo'] = $googleUser->getAvatar();
            }

            if (! empty($updates)) {
                $user->forceFill($updates)->save();
            }
        }

        Auth::login($user, true);
        session()->regenerate();

        // Cek apakah ada package yang dipilih sebelum login via Google
        $preRegister = $request->session()->get('pre_register');
        $packageId = $preRegister['package_id'] ?? null;

        if ($packageId && !$user->hasLmsAccess()) {
            $request->session()->forget('pre_register');
            $firstLesson = \App\Models\Lesson::where('type', 'course')->orderBy('position')->first();
            if ($firstLesson) {
                return redirect(route('kelas.buy', $firstLesson->id) . '?package_id=' . $packageId . '&package_qty=' . ($preRegister['package_qty'] ?? 1));
            }
        }

        if (is_string($user->email) && str_ends_with(strtolower($user->email), '@admin')) {
            return redirect()->intended(url('/admin'));
        }

        if (method_exists($user, 'hasLmsAccess') && $user->hasLmsAccess()) {
            $defaultRoute = route('lms.entry');
        } elseif (method_exists($user, 'hasCoachingAccess') && $user->hasCoachingAccess()) {
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

    private function resolveGoogleRedirectUri(Request $request): string
    {
        $configuredRedirect = Config::get('services.google.redirect');

        if (is_string($configuredRedirect) && trim($configuredRedirect) !== '') {
            return trim($configuredRedirect);
        }

        return $request->getSchemeAndHttpHost() . '/auth/google/callback';
    }
}
