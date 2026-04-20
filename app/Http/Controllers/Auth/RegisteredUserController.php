<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use App\Rules\NotDisposableEmail;
use App\Rules\AllowedEmailDomain;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {
        // Preserve redirect target (e.g., payment page) if provided from buy page
        if ($request->query('redirect_to')) {
            $request->session()->put('url.intended', $request->query('redirect_to'));
        }
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $messages = [
            'name.required' => 'Name is required.',
            'name.max' => 'Name is too long (max 255 characters).',
            'email.required' => 'Email is required.',
            'email.email' => 'Email format is invalid.',
            'email.max' => 'Email is too long (max 255 characters).',
            'email.unique' => 'Email is already registered. If this is yours, please log in or use forgot password.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password must be at least :min characters.',
            'phone.max' => 'Phone number is too long.',
            'selected_package.exists' => 'Selected package is invalid.',
        ];

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // enforce rfc + dns checks to ensure email domain exists (helps ensure real email addresses)
            // require allowed domain (public whitelist). Admin-reserved domain cannot be used for public registration.
            'email' => ['required', 'string', 'lowercase', 'email:rfc,dns', new NotDisposableEmail(), new AllowedEmailDomain(false), 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['nullable', 'string', 'max:30'],
            'referral' => ['nullable', 'string', 'max:64'],
            'selected_package' => ['nullable', 'integer', 'exists:packages,id'],
            'package_id' => ['nullable', 'integer', 'exists:packages,id'],
        ], $messages);

        // If the form included a selected package, don't create the user yet.
        // Store registration data in session temporarily and redirect to the purchase/payment page.
        if ($request->filled('selected_package') || $request->filled('package_id')) {
            $pkg = $request->input('selected_package') ?: $request->input('package_id');
            // Keep registration input in session until payment completes. We'll create the user after payment.
            // SECURITY FIX: Do NOT store password in session - generate random password during user creation instead
            $request->session()->put('pre_register', [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'password_provided' => $request->filled('password'), // Just flag if password was provided
                'referral' => $request->input('referral') ?: $request->session()->get('referral') ?: null,
                'package_id' => $pkg,
                'package_qty' => $request->input('package_qty') ? intval($request->input('package_qty')) : 1,
            ]);

            // redirect to the buy/payment page (use buy route which renders the payment view)
            $firstLesson = \App\Models\Lesson::orderBy('position')->first();
            return redirect(route('kelas.buy', ['lesson' => $firstLesson->id ?? null, 'package_id' => $pkg]));
        }

        // No package selected: proceed with normal immediate registration flow
    $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone ?? null,
            // lesson_id removed; use package_id only
            'package_id' => $request->input('selected_package') ?: $request->input('package_id') ?: null,
            'referred_by' => null,
        ]);

        // If referral code present (form or session), resolve it and set referred_by
        $refCodeInput = $request->input('referral');
        $refCodeSession = $request->session()->get('referral');
        $refCode = $refCodeInput ?: $refCodeSession;
        if (! empty($refCode)) {
            $referrer = User::where('referral_code', $refCode)->first();
            if ($referrer) {
                $user->referred_by = $referrer->id;
                $user->save();
                // Clear the session referral after applying
                if ($refCodeSession) { $request->session()->forget('referral'); }
            } else if ($request->filled('referral')) {
                // Only error when an explicit invalid code was typed into the form
                return redirect()->back()->withInput()->withErrors(['referral' => 'Referral code is not valid. Please check the code you entered.']);
            }
        }

    // Dispatch Registered event (this will queue the email verification notification)
    // Do not fail registration if mail/notification throws; log and continue
    try {
        event(new Registered($user));
    } catch (\Throwable $e) {
        Log::error('Failed to send verification email after registration', [
            'user_id' => $user->id,
            'error' => $e->getMessage(),
        ]);
    }

    // Keep user signed in and send them to verification notice directly.
    Auth::login($user);
    return redirect()->route('verification.notice')
        ->with('status', 'Registrasi berhasil. Lanjutkan verifikasi dengan Google menggunakan email yang sama untuk mengaktifkan akun.');
    }
}
