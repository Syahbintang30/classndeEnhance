<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
    /**
     * Mark the user's email address as verified and log them in automatically.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $userId = $request->route('id');
        $user = Auth::user() ?: User::find($userId);

        if (! $user) {
            return redirect()->route('login')->with('error', 'Account not found. Please register or log in.');
        }

        // Validate hash parameter against sha1 of user's email
        if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            return redirect()->route('login')->with('error', 'Invalid email verification link.');
        }

        // Mark email as verified if not already verified
        if (! $user->hasVerifiedEmail()) {
            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
            }
        }

        // Automatically log the user in if not logged in yet
        if (! Auth::check()) {
            Auth::login($user, true);
            $request->session()->regenerate();
        }

        // Determine destination route
        $defaultRoute = route('registerclass');
        if (method_exists($user, 'hasLmsAccess') && $user->hasLmsAccess()) {
            $defaultRoute = route('lms.dashboard');
        } elseif (method_exists($user, 'hasCoachingAccess') && $user->hasCoachingAccess()) {
            $defaultRoute = route('coaching.upcoming');
        }

        $intended = (string) $request->session()->get('url.intended', '');
        if ($intended !== '' && str_contains($intended, '/verify-email')) {
            $request->session()->forget('url.intended');
        }

        return redirect()->intended($defaultRoute . '?verified=1')
            ->with('status', 'Your email address has been verified successfully! You are now logged in.');
    }
}
