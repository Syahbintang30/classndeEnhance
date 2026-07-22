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
        $redirect = $this->normalizeIntendedUrl($request, $request->query('redirect_to'));
        if ($redirect) {
            $request->session()->put('url.intended', $redirect);
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

        $selectedPkg = $request->input('selected_package') ?: $request->input('package_id');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone ?? null,
            'package_id' => null, // Will be set after payment settlement
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
                if ($refCodeSession) { $request->session()->forget('referral'); }
            } else if ($request->filled('referral')) {
                return redirect()->back()->withInput()->withErrors(['referral' => 'Referral code is not valid. Please check the code you entered.']);
            }
        }

        try {
            event(new Registered($user));
        } catch (\Throwable $e) {
            Log::error('Failed to send verification email after registration', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }

        Auth::login($user);

        $firstLesson = \App\Models\Lesson::orderBy('position')->first();
        $firstLessonId = $firstLesson ? $firstLesson->id : 1;

        if ($selectedPkg) {
            return redirect()->route('kelas.payment', ['lesson' => $firstLessonId, 'package_id' => $selectedPkg]);
        }

        return redirect()->route('kelas.buy', ['lesson' => $firstLessonId])
            ->with('status', 'Registration successful! Choose your package below to start learning.');
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
}
