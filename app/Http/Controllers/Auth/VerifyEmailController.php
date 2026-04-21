<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $user = $request->user();

        $defaultRoute = route('registerclass');
        if ($user && method_exists($user, 'hasLmsAccess') && $user->hasLmsAccess()) {
            $defaultRoute = route('lms.dashboard');
        } elseif ($user && method_exists($user, 'hasCoachingAccess') && $user->hasCoachingAccess()) {
            $defaultRoute = route('coaching.upcoming');
        }

        $intended = (string) $request->session()->get('url.intended', '');
        if ($intended !== '' && str_contains($intended, '/verify-email')) {
            $request->session()->forget('url.intended');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended($defaultRoute . '?verified=1');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->intended($defaultRoute . '?verified=1');
    }
}
