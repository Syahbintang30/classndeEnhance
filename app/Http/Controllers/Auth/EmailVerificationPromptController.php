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

        if (empty($user->google_id)) {
            return view('auth.verify-email');
        }

        if (! $user->hasVerifiedEmail()) {
            $user->forceFill(['email_verified_at' => now()])->save();
        }

        $defaultRoute = method_exists($user, 'hasLmsAccess') && $user->hasLmsAccess()
            ? route('lms.entry')
            : route('registerclass');

        return redirect()->intended($defaultRoute);
    }
}
