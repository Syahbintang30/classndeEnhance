<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        $user = $request->user();

        if (($user->is_admin ?? false) || ($user->is_superadmin ?? false)) {
            return redirect()->intended(url('/admin'));
        }

        if (method_exists($user, 'hasVerifiedEmail') && ! $user->hasVerifiedEmail()) {
            return view('auth.verify-email');
        }

        if (method_exists($user, 'hasLmsAccess') && $user->hasLmsAccess()) {
            $defaultRoute = route('lms.entry');
        } elseif (method_exists($user, 'hasCoachingAccess') && $user->hasCoachingAccess()) {
            $defaultRoute = route('coaching.upcoming');
        } else {
            $defaultRoute = route('registerclass');
        }

        return redirect()->intended($defaultRoute);
    }
}
