<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Package;
use App\Models\CoachingTicket;
use App\Models\CoachingBooking;
use App\Models\Transaction;
use App\Models\TopicProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\CoachingTicketService;
use Illuminate\Support\Facades\Log;

class KelasController extends Controller
{
    /**
     * Single entry point for LMS navigation.
     * - Guest: redirect to login
        * - Auth with access: redirect to first available lesson/topic
        * - Auth without access: redirect to access-pending status page
     */
    public function lmsEntry(Request $request)
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (($user->is_admin ?? false) || ($user->is_superadmin ?? false)) {
            $targetUrl = $this->resolveFirstCourseTargetUrl();
            if ($targetUrl) {
                return redirect()->to($targetUrl);
            }
            return redirect()->route('compro');
        }

        if (! $user->hasLmsAccess()) {
            return redirect()->route('lms.pending');
        }

        // Redirect to the first course lesson instead of dashboard
        $firstLesson = Lesson::where('type', 'course')
            ->orderBy('position')
            ->first();

        if ($firstLesson) {
            return redirect()->route('kelas.show', ['lesson' => $firstLesson->id]);
        }

        return redirect()->route('lms.dashboard');
    }

    /**
     * Premium customer dashboard with modules and learning progress.
     */
    public function customerDashboard(Request $request)
    {
        $user = $request->user();
        if (! $user) {
            return redirect()->route('login');
        }

        if (! (($user->is_admin ?? false) || ($user->is_superadmin ?? false) || $user->hasLmsAccess())) {
            return redirect()->route('lms.pending');
        }

        $courseLessons = Lesson::where('type', 'course')
            ->with(['topics' => function ($q) { $q->orderBy('position'); }])
            ->orderBy('position')
            ->get();

        $courseTopics = $courseLessons->flatMap(function ($lesson) {
            return $lesson->topics;
        })->values();

        $courseTopicIds = $courseTopics->pluck('id')->values();
        $totalTopics = $courseTopics->count();

        $completedTopicIds = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('topic_progresses')) {
            $progressRows = TopicProgress::query()
                ->where('user_id', $user->id)
                ->whereIn('topic_id', $courseTopicIds)
                ->get(['topic_id', 'completed', 'watched_seconds']);

            $completedTopicIds = $progressRows
                ->where('completed', true)
                ->pluck('topic_id')
                ->unique()
                ->values();
        }

        $completedTopics = $completedTopicIds->count();
        $progressPercent = $totalTopics > 0 ? (int) round(($completedTopics / $totalTopics) * 100) : 0;

        $nextTopic = $courseTopics->first(function ($topic) use ($completedTopicIds) {
            return ! $completedTopicIds->contains($topic->id);
        });

        $firstLesson = $courseLessons->first();
        $firstTopic = $firstLesson?->topics?->first();
        $resumeLesson = $nextTopic ? $nextTopic->lesson : $firstLesson;
        $resumeTopic = $nextTopic ?: $firstTopic;

        $coursesUrl = $resumeLesson
            ? route('kelas.show', ['lesson' => $resumeLesson->id]) . ($resumeTopic ? ('?topic=' . $resumeTopic->id) : '')
            : route('compro');

        $upcomingCoachingCount = CoachingBooking::query()
            ->where('user_id', $user->id)
            ->where('status', '!=', 'cancelled')
            ->where('booking_time', '>=', now())
            ->count();

        $availableTicketCount = CoachingTicket::query()
            ->where('user_id', $user->id)
            ->where('is_used', false)
            ->count();

        return view('lms.dashboard', [
            'progressPercent' => $progressPercent,
            'completedTopics' => $completedTopics,
            'totalTopics' => $totalTopics,
            'coursesUrl' => $coursesUrl,
            'hasSongTutorialAccess' => $user->hasIntermediateAccess(),
            'upcomingCoachingCount' => $upcomingCoachingCount,
            'availableTicketCount' => $availableTicketCount,
        ]);
    }

    public function index()
    {
    // Enforce new user flow: register/login first, then choose package + checkout.
    if (! Auth::check()) {
        return redirect()->route('register', ['redirect_to' => route('registerclass')]);
    }

    // Logged-in users without package should remain on package chooser (checkout flow).
    // Only users that already have access should go to LMS entry.
    $authUser = Auth::user();

    if (($authUser->is_admin ?? false) || ($authUser->is_superadmin ?? false)) {
        return redirect()->route('admin.dashboard');
    }

    if ($authUser->hasLmsAccess()) {
        return redirect()->route('lms.entry');
    }

    // Dashboard becomes the buy/home page showing package options
    // Prefer lessons of type 'course' on the buy page; gracefully fallback if none
    $lessons = Lesson::where('type', 'course')->with(['topics' => function($q){ $q->orderBy('position'); }])->orderBy('position')->get();
    if ($lessons->isEmpty()) {
        // Fallback: show any lessons ordered by position so $lesson is not null
        // but exclude song tutorial lessons from the main lesson listing
        $lessons = Lesson::where('type', '<>', 'song_tutorial')
            ->with(['topics' => function($q){ $q->orderBy('position'); }])
            ->orderBy('position')
            ->get();
    }
    /** @var \App\Models\User|null $user */
    $user = Auth::user();

    // coaching package slug and eligible public package slugs are configurable
    $coachingSlug = config('coaching.coaching_package_slug', 'coaching-ticket');
    $eligibleSlugs = config('coaching.eligible_packages', ['beginner','intermediate']);

    if ($user) {
        // If the user does not have any package yet, show beginner/intermediate packages
        if (empty($user->package_id)) {
            $packages = Package::whereIn('slug', $eligibleSlugs)->orderBy('price')->get();
        } else {
            // logged-in users who already have a package should normally only see the coaching-ticket package
            $packages = Package::where('slug', $coachingSlug)->orderBy('price')->get();
        }

        // If the user previously purchased the 'beginner' package, offer an
        // "Upgrade Intermediate" package priced at (intermediate - beginner).
        try {
            $hasBeginner = false;
            // check current package_id first
            if (! empty($user->package_id)) {
                $cur = Package::find($user->package_id);
                if ($cur && $cur->slug === 'beginner') $hasBeginner = true;
            }
            // fallback: check historical purchases via UserPackage
            if (! $hasBeginner) {
                $hasBeginner = \App\Models\UserPackage::where('user_id', $user->id)
                    ->whereHas('package', function($q){ $q->where('slug', 'beginner'); })
                    ->exists();
            }

            if ($hasBeginner) {
                $beginner = Package::where('slug', 'beginner')->first();
                $intermediate = Package::where('slug', 'intermediate')->first();
                if ($beginner && $intermediate) {
                    $diff = max(0, intval($intermediate->price) - intval($beginner->price));
                    if ($diff > 0) {
                        // create or update a special upgrade package record so it can be selected/validated
                        $upgrade = Package::where('slug', 'upgrade-intermediate')->first();
                        if (! $upgrade) {
                            $upgrade = Package::create([
                                'name' => 'Upgrade Intermediate',
                                'slug' => 'upgrade-intermediate',
                                'price' => $diff,
                                'description' => 'Upgrade from Beginner to Intermediate — bayar selisih harga saja.',
                                'benefits' => "Upgrade fee to move from Beginner to Intermediate.",
                                'image' => null,
                            ]);
                        } else {
                            // keep price in sync with current difference
                            if ((int)$upgrade->price !== $diff) {
                                $upgrade->price = $diff;
                                $upgrade->save();
                            }
                        }
                        // append upgrade package to the packages collection
                        $packages = $packages->concat(collect([$upgrade]));
                    }
                }
            }
        } catch (\Throwable $e) {
            // don't break the page if upgrade creation fails; just log and continue
            \Illuminate\Support\Facades\Log::warning('Failed to prepare upgrade package', ['err' => $e->getMessage(), 'user_id' => $user->id]);
        }
    } else {
        // guests see the eligible beginner/intermediate packages only
        $packages = Package::whereIn('slug', $eligibleSlugs)->orderBy('price')->get();
    }
    // pick a default lesson (first) if available so purchase route in the buy view has an id
    $lesson = $lessons->first();
    // show buy page with packages
    return view('kelas.buy', ['lessons' => $lessons, 'packages' => $packages, 'lesson' => $lesson]);
    }

    public function show(Lesson $lesson)
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        if (! (($user->is_admin ?? false) || ($user->is_superadmin ?? false) || $user->hasLmsAccess())) {
            return redirect()->route('lms.pending');
        }

        // load topics ordered by position
        $lesson->load(['topics' => function($q){ $q->orderBy('position'); }]);
        // also provide list of all lessons for sidebar navigation
        // only show lessons with type 'course' in the sidebar
        $lessons = Lesson::where('type', 'course')->orderBy('position')->get();
        // if the requested lesson is not a course, redirect to the first course lesson
        if ($lesson->type !== 'course') {
            $first = Lesson::where('type', 'course')->orderBy('position')->first();
            if ($first) {
                return redirect()->route('kelas.show', $first->id);
            }
        }
        return view('kelas', compact('lessons', 'lesson'));
    }

    /**
     * Return the lesson main content as a partial (AJAX)
     */
    public function content(Lesson $lesson)
    {
        $user = Auth::user();
        if (! $user) {
            abort(401);
        }

        if (! (($user->is_admin ?? false) || ($user->is_superadmin ?? false) || $user->hasLmsAccess())) {
            abort(403);
        }

        // Only return content for lessons of type 'course'
        if ($lesson->type !== 'course') {
            // return an empty partial so AJAX consumers gracefully handle it
            // Use safe approach instead of raw SQL for getting empty results
            return view('kelas._lesson_content', [
                'lesson' => $lesson->loadMissing(['topics' => function($q){ 
                    $q->where('id', '=', -1); // Safe way to get no results
                }])
            ]);
        }
        $lesson->load(['topics' => function($q){ $q->orderBy('position'); }]);
        return view('kelas._lesson_content', compact('lesson'));
    }

    /**
     * Show purchase page for a lesson (beli kelas).
     */
    public function buy(Lesson $lesson)
    {
    /** @var \App\Models\User|null $user */
    $user = Auth::user();
    $packages = Package::orderBy('price')->get();

        // determine package from request or user's existing package
        $packageId = request()->input('package_id') ?: ($user->package_id ?? null);
        $package = $packageId ? Package::find($packageId) : null;

        // package price is canonical; avoid misleading hardcoded fallback
        $price = (int) ($package->price ?? 0);
        // qty can be passed as query param (guests) or request; default 1
        $qty = (int) (request()->input('package_qty') ?: session('pre_register.package_qty') ?: 1);

        // prepare order and apply referral discount if present in session/request
        $rawAmount = $price * max(1, $qty);
        $appliedReferralPercent = 0;
        $referralCode = session('pre_register.referral') ?: request()->input('referral');
        // Coaching-ticket purchases: apply referrer discount based on invites, no code needed
        if ($package && $package->slug === config('coaching.coaching_package_slug', 'coaching-ticket')) {
            if ($user) {
                $appliedReferralPercent = \App\Services\ReferralService::referrerCoachingDiscountPercent($user);
            }
        } else if (! empty($referralCode)) {
            // Course packages: apply 5% when a valid referral code is used (typically for new/guest users)
            $appliedReferralPercent = \App\Services\ReferralService::guestCourseDiscountPercent($referralCode, $package);
        }

        $grossAmount = $rawAmount;
        if ($appliedReferralPercent > 0) {
            $grossAmount = (int) round($rawAmount * (100 - $appliedReferralPercent) / 100);
        }

        $order = [
            'order_id' => \App\Services\OrderIdGenerator::generate('nde'),
            'gross_amount' => $grossAmount,
            'original_amount' => $rawAmount,
            'applied_referral_percent' => $appliedReferralPercent,
            'referral_code' => $referralCode,
            // carry referral meta through cache/pending flow for webhook use
            'meta' => [
                'applied_referral_percent' => $appliedReferralPercent,
                'referral_code' => $referralCode,
            ],
            'item_details' => [
                ['id' => $package ? 'package-'.$package->id : 'lesson-'.$lesson->id, 'price' => (int) ($price * (100 - $appliedReferralPercent) / 100), 'quantity' => max(1, $qty), 'name' => $package ? $package->name : $lesson->title . ($appliedReferralPercent ? (' (Referral ' . $appliedReferralPercent . '%)') : '')],
            ],
            'customer_details' => [
                'first_name' => $user->name ?? '',
                'email' => $user->email ?? '',
                'phone' => $user->phone ?? '',
            ],
        ];

        // pass Midtrans client key to view
        $midtrans = config('services.midtrans');
    // load active payment methods
    $methods = \App\Models\PaymentMethod::where('is_active', true)->orderBy('name')->get();
    return view('kelas.payment', compact('lesson', 'order', 'midtrans', 'package', 'methods'));
    }

    /**
     * Show the payment UI for a specific lesson (requires auth route).
     */
    public function payment(Lesson $lesson)
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        $packages = Package::orderBy('price')->get();

        // Prefer restoring an existing in-flight order by order_id (pending/failed) so amounts are accurate
        $existingOrderId = request()->query('order_id') ?? request()->query('orderId');

        $package = null;
        $order = null;
        if ($existingOrderId) {
            // 1) If a Transaction already exists (e.g., capture/settlement recorded), use it
            $txn = \App\Models\Transaction::where('order_id', $existingOrderId)->latest()->first();
            if ($txn) {
                $package = $txn->package_id ? Package::find($txn->package_id) : null;
                $order = [
                    'order_id' => $existingOrderId,
                    'gross_amount' => (int) ($txn->amount ?? 0),
                    'original_amount' => (int) ($txn->original_amount ?? ($txn->amount ?? 0)),
                    'applied_referral_percent' => 0,
                    'referral_code' => null,
                    'item_details' => [
                        ['id' => $package ? 'package-'.$package->id : 'lesson-'.$lesson->id, 'price' => (int) ($txn->amount ?? 0), 'quantity' => 1, 'name' => $package ? $package->name : $lesson->title],
                    ],
                    'customer_details' => [
                        'first_name' => $user->name ?? '',
                        'email' => $user->email ?? '',
                        'phone' => $user->phone ?? '',
                    ],
                ];
            } else {
                // 2) Pending orders live only in cache; restore their payload for accurate display
                try {
                    $cached = \Illuminate\Support\Facades\Cache::get('pending_txn:' . $existingOrderId);
                } catch (\Throwable $e) { $cached = null; }
                if (is_array($cached)) {
                    $package = isset($cached['package_id']) && $cached['package_id'] ? Package::find($cached['package_id']) : null;
                    $order = [
                        'order_id' => $existingOrderId,
                        'gross_amount' => (int) ($cached['amount'] ?? 0),
                        'original_amount' => (int) ($cached['original_amount'] ?? ($cached['amount'] ?? 0)),
                        'applied_referral_percent' => (int) ($cached['applied_referral_percent'] ?? 0),
                        'referral_code' => $cached['referral_code'] ?? null,
                        'item_details' => [
                            ['id' => $package ? 'package-'.$package->id : 'lesson-'.$lesson->id, 'price' => (int) ($cached['amount'] ?? 0), 'quantity' => 1, 'name' => $package ? $package->name : $lesson->title],
                        ],
                        'customer_details' => [
                            'first_name' => $user->name ?? '',
                            'email' => $user->email ?? '',
                            'phone' => $user->phone ?? '',
                        ],
                    ];
                }
            }
        }

        // If no cached/existing order, compute from request/package selection as before (no arbitrary default price)
        if (! $order) {
            $packageId = request()->input('package_id') ?: ($user->package_id ?? null);
            $package = $packageId ? Package::find($packageId) : null;
            $qty = (int) (request()->input('package_qty') ?: session('pre_register.package_qty') ?: 1);
            $price = (int) ($package->price ?? 0); // avoid misleading hardcoded fallback

            $rawAmount = $price * max(1, $qty);
            $appliedReferralPercent = 0;
            $referralCode = session('pre_register.referral') ?: request()->input('referral');
            if (! empty($referralCode)) {
                $refUser = \App\Models\User::where('referral_code', $referralCode)->first();
                $dbVal = \App\Models\Setting::get('referral.discount_percent', null);
                $discountPercent = $dbVal !== null ? (int) $dbVal : (int) config('referral.discount_percent', 2);
                if ($refUser) { $appliedReferralPercent = (int) $discountPercent; }
            }

            $grossAmount = $appliedReferralPercent > 0 ? (int) round($rawAmount * (100 - $appliedReferralPercent) / 100) : (int) $rawAmount;

            $order = [
                'order_id' => \App\Services\OrderIdGenerator::generate('nde'),
                'gross_amount' => $grossAmount,
                'original_amount' => $rawAmount,
                'applied_referral_percent' => $appliedReferralPercent,
                'referral_code' => $referralCode,
                'item_details' => [
                    ['id' => $package ? 'package-'.$package->id : 'lesson-'.$lesson->id, 'price' => (int) ($price * (100 - $appliedReferralPercent) / 100), 'quantity' => max(1, $qty), 'name' => $package ? $package->name : $lesson->title . ($appliedReferralPercent ? (' (Referral ' . $appliedReferralPercent . '%)') : '')],
                ],
                'customer_details' => [
                    'first_name' => $user->name ?? '',
                    'email' => $user->email ?? '',
                    'phone' => $user->phone ?? '',
                ],
            ];
        }

    $midtrans = config('services.midtrans');
        $methods = \App\Models\PaymentMethod::where('is_active', true)->orderBy('name')->get();

        return view('kelas.payment', compact('lesson', 'order', 'midtrans', 'package', 'methods'));
    }

    /**
     * Handle purchase form submission (very small stub).
     */
    public function purchase(Request $request, Lesson $lesson)
    {
    /** @var \App\Models\User|null $user */
    $user = Auth::user();
        // Assign user's package if provided
    // Do not grant package or create tickets here — permission and DB inserts must
    // only happen once the payment reaches 'settlement'. Keep a lightweight
    // acknowledgement and redirect the user to the payment UI where the
    // settlement will be processed via webhook / client polling.
    return redirect()->route('kelas.payment', ['lesson' => $lesson->id, 'package_id' => $request->input('package_id')])->with('info', 'Silakan lanjutkan pembayaran untuk menyelesaikan pembelian. Akses paket akan diberikan setelah pembayaran terkonfirmasi.');
    }

    /**
     * Handle client/server notification after payment completes (simple handler).
     */
    public function paymentComplete(Request $request, Lesson $lesson)
    {
    /** @var \App\Models\User|null $user */
    $user = Auth::user();

    // In a production app you'd validate the notification from Midtrans signature
    // NOTE: Do NOT create user accounts or write DB records here for pending payments.
    // Guest account creation is postponed until we have a confirmed settlement so
    // we avoid creating accounts for incomplete/abandoned payments.

        if (! $user) {
            // cannot associate ticket without a user; redirect home
            return redirect()->route('dashboard')->with('error', 'User not found after payment. Please contact support.');
        }

    // We no longer create tickets or assign package here. Tickets and package
    // assignment will be created when we detect 'settlement' below (either
    // from client-reported midtrans_result or via webhook).
    $createdTickets = [];
    $firstTicketId = null;
    $pkgId = $request->input('package_id') ?: $user->package_id;
    $package = $pkgId ? \App\Models\Package::find($pkgId) : null;
    $beginnerSlugs = ['beginner', 'intermediate', 'upgrade-intermediate'];

        // Default: keep existing thank-you redirect (backwards compatible)
        // If midtrans_result is provided (snap client returned), try to persist a Transaction
        $midResRaw = $request->input('midtrans_result');
        if (! empty($midResRaw)) {
            $data = is_string($midResRaw) ? json_decode($midResRaw, true) : (array) $midResRaw;
            $orderId = $data['order_id'] ?? $data['orderId'] ?? $request->input('order_id') ?? null;
            $txnStatus = $data['transaction_status'] ?? $data['status'] ?? $data['status_code'] ?? null;
            try {
                if ($orderId) {
                    $existing = Transaction::where('order_id', $orderId)->latest()->first();
                    if (! $existing) {
                        // Normalize status to either 'pending' or 'settlement'
                        $normalized = 'pending';
                        $lower = strtolower((string) ($txnStatus ?? ''));
                        if (in_array($lower, ['settlement','capture','success'])) $normalized = 'settlement';

                        // Do NOT persist a Transaction record if it's still pending. Only
                        // create DB transaction when we already have settlement confirmed.
                        if ($normalized === 'settlement') {
                            // If guest flow provided pre_register, create/login user now before persisting
                            if (! $user && $request->session()->has('pre_register')) {
                                $pre = $request->session()->get('pre_register');
                                $exists = \App\Models\User::where('email', $pre['email'])->exists();
                                if ($exists) {
                                    $user = \App\Models\User::where('email', $pre['email'])->first();
                                    Auth::login($user);
                                } else {
                                    // SECURITY FIX: Generate random secure password instead of using stored password
                                    // This eliminates password storage vulnerability in session
                                    $plainPassword = str()->random(16); // Generate secure random password
                                    
                                    $user = \App\Models\User::create([
                                        'name' => $pre['name'] ?? 'User',
                                        'email' => $pre['email'],
                                        'password' => \Illuminate\Support\Facades\Hash::make($plainPassword),
                                        'phone' => $pre['phone'] ?? null,
                                        'package_id' => $pre['package_id'] ?? null,
                                        'referred_by' => null,
                                    ]);
                                    if (! empty($pre['referral'])) {
                                        $refCode = $pre['referral'];
                                        $referrer = \App\Models\User::where('referral_code', $refCode)->first();
                                        if ($referrer) {
                                            $user->referred_by = $referrer->id;
                                            $user->save();
                                        }
                                    }
                                    event(new \Illuminate\Auth\Events\Registered($user));
                                    
                                    // Send welcome email with password to new user
                                    try {
                                        $user->notify(new \App\Notifications\WelcomeWithPasswordNotification($plainPassword));
                                    } catch (\Throwable $e) {
                                        // Log error but don't fail the registration process
                                        \Illuminate\Support\Facades\Log::error('Failed to send welcome email: ' . $e->getMessage());
                                    }
                                    
                                    Auth::login($user);
                                }
                                $request->session()->forget('pre_register');
                            }

                            Transaction::create([
                                'order_id' => $orderId,
                                'user_id' => $user->id ?? null,
                                'package_id' => $pkgId ?? null,
                                'method' => isset($data['payment_type']) ? strtoupper($data['payment_type']) : ($request->input('payment_method') ?? null),
                                'amount' => $data['gross_amount'] ?? null,
                                'original_amount' => $data['gross_amount'] ?? null,
                                'status' => $normalized,
                                'midtrans_response' => $data,
                            ]);

                            // grant tickets & package immediately when the client already reports settlement
                            // IMPORTANT: Only create 'midtrans' coaching tickets if the purchased package is the coaching ticket itself
                            $coachingSlug = config('coaching.coaching_package_slug', 'coaching-ticket');
                            if ($package && ($package->slug ?? null) === $coachingSlug) {
                                $qty = (int) ($request->input('package_qty') ?: session('pre_register.package_qty') ?: 1);
                                for ($i = 0; $i < max(1, $qty); $i++) {
                                    $createdTickets[] = CoachingTicket::create([
                                        'user_id' => $user->id,
                                        'source' => 'midtrans',
                                        'is_used' => false,
                                    ]);
                                }
                            }
                            if ($request->input('package_id') && $user) {
                                $user->package_id = $request->input('package_id');
                                $user->save();
                            }
                            // Idempotent: top-up free_on_register tickets based on final package
                            if ($user) {
                                CoachingTicketService::grantFreeOnRegister($user);
                            }
                            // If purchasing coaching-ticket with referral discount applied, redeem units
                            if ($package && ($package->slug ?? null) === config('coaching.coaching_package_slug', 'coaching-ticket') && !empty($orderId)) {
                                $percentApplied = (int) ($data['applied_referral_percent'] ?? ($request->input('applied_referral_percent') ?? 0));
                                if ($percentApplied > 0 && $user) { \App\Services\ReferralService::redeemUnits($user, $percentApplied, (string) $orderId); }
                            }
                            $firstTicketId = !empty($createdTickets) && isset($createdTickets[0]) ? $createdTickets[0]->id : null;
                            if ($package && isset($package->slug) && in_array($package->slug, $beginnerSlugs)) {
                                return $this->redirectAfterSuccessfulPayment($lesson, $firstTicketId);
                            }
                        } else {
                            // pending: do not write DB transaction here. The webhook will
                            // create the DB transaction on settlement. Keep user on payment
                            // page / client polling will check transactionStatus endpoint.
                        }
                    } else {
                        $existingResponse = $existing->midtrans_response;
                        if (is_string($existingResponse)) {
                            $existingResponse = json_decode($existingResponse, true) ?: [];
                        }
                        if (! is_array($existingResponse)) {
                            $existingResponse = [];
                        }
                        $existing->midtrans_response = array_merge($existingResponse, $data ?: []);
                        if ($txnStatus) {
                            $lower = strtolower((string) $txnStatus);
                                $existing->status = in_array($lower, ['settlement','capture','success']) ? 'settlement' : 'pending';
                                // If this update moved the txn into settlement, grant tickets & package now.
                                if (in_array($lower, ['settlement','capture','success'])) {
                                    // If guest flow provided pre_register, create/login user now before granting
                                    if (! $user && $request->session()->has('pre_register')) {
                                        $pre = $request->session()->get('pre_register');
                                        $exists = \App\Models\User::where('email', $pre['email'])->exists();
                                        if ($exists) {
                                            $user = \App\Models\User::where('email', $pre['email'])->first();
                                            Auth::login($user);
                                        } else {
                                            $user = \App\Models\User::create([
                                                'name' => $pre['name'] ?? 'User',
                                                'email' => $pre['email'],
                                                'password' => \Illuminate\Support\Facades\Hash::make($pre['password'] ?? str()->random(12)),
                                                'phone' => $pre['phone'] ?? null,
                                                'package_id' => $pre['package_id'] ?? null,
                                                'referred_by' => null,
                                            ]);
                                            if (! empty($pre['referral'])) {
                                                $refCode = $pre['referral'];
                                                $referrer = \App\Models\User::where('referral_code', $refCode)->first();
                                                if ($referrer) {
                                                    $user->referred_by = $referrer->id;
                                                    $user->save();
                                                }
                                            }
                                            event(new \Illuminate\Auth\Events\Registered($user));
                                            Auth::login($user);
                                        }
                                        $request->session()->forget('pre_register');
                                    }

                                    // Only create 'midtrans' coaching tickets for coaching ticket package
                                    $coachingSlug = config('coaching.coaching_package_slug', 'coaching-ticket');
                                    if ($package && ($package->slug ?? null) === $coachingSlug) {
                                        $qty = (int) ($request->input('package_qty') ?: session('pre_register.package_qty') ?: 1);
                                        for ($i = 0; $i < max(1, $qty); $i++) {
                                            $createdTickets[] = CoachingTicket::create([
                                                'user_id' => $user->id,
                                                'source' => 'midtrans',
                                                'is_used' => false,
                                            ]);
                                        }
                                    }
                                    if ($request->input('package_id') && $user) {
                                        $user->package_id = $request->input('package_id');
                                        $user->save();
                                    }
                                    // Idempotent: top-up free_on_register tickets based on final package
                                    if ($user) {
                                        CoachingTicketService::grantFreeOnRegister($user);
                                    }
                                    // redeem referral units for coaching-ticket purchases
                                    if ($package && ($package->slug ?? null) === config('coaching.coaching_package_slug', 'coaching-ticket') && !empty($orderId)) {
                                        $percentApplied = (int) ($data['applied_referral_percent'] ?? ($request->input('applied_referral_percent') ?? 0));
                                        if ($percentApplied > 0 && $user) { \App\Services\ReferralService::redeemUnits($user, $percentApplied, (string) $orderId); }
                                    }
                                    $firstTicketId = !empty($createdTickets) && isset($createdTickets[0]) ? $createdTickets[0]->id : null;
                                    if ($package && isset($package->slug) && in_array($package->slug, $beginnerSlugs)) { 
                                        return $this->redirectAfterSuccessfulPayment($lesson, $firstTicketId);
                                    }
                                }
                        }
                        $existing->save();
                    }
                }
            } catch (\Throwable $e) {
                // don't break flow; webhook will still create DB txn later
                Log::warning('paymentComplete: failed to persist midtrans_result', ['err' => $e->getMessage(), 'order_id' => $orderId ?? null]);
            }

            // If the client-side reported settlement already, redirect to thankyou with order_id
            $lowerStat = strtolower((string) ($txnStatus ?? ''));
            if (in_array($lowerStat, ['settlement','capture','success'])) {
                // client already reported settlement; the grant logic above will have
                // executed. Redirect to centralized thankyou page.
                return $this->redirectAfterSuccessfulPayment($lesson, $firstTicketId, $orderId);
            }

            // If client reports pending/not-paid, keep the user on the payment page (do not redirect to thankyou)
            // Include order_id so client-side polling or waiting UI can pick it up.
            if (! in_array($lowerStat, ['settlement','capture','success'])) {
            return redirect()->route('kelas.payment', ['lesson' => $lesson->id, 'order_id' => $orderId])
                ->with('info', 'Pembayaran belum dikonfirmasi. Silakan selesaikan atau tunggu konfirmasi di halaman pembayaran.');
            }
        }

        return $this->redirectAfterSuccessfulPayment($lesson, $firstTicketId, $orderId ?? null);
    }

    /**
     * In local development, send the user straight into the LMS lesson page after payment.
     * Production can keep the legacy thank-you flow until that is ready to switch.
     */
    protected function redirectAfterSuccessfulPayment(Lesson $lesson, $ticketId = null, ?string $orderId = null)
    {
        $shouldOpenLms = app()->environment('local') || filter_var(env('PAYMENT_REDIRECT_TO_LMS', true), FILTER_VALIDATE_BOOLEAN);

        if ($shouldOpenLms) {
            $targetLessonId = (int) env('PAYMENT_LMS_LESSON_ID', 0);
            $targetTopicId = (int) env('PAYMENT_LMS_TOPIC_ID', 0);

            $targetLesson = null;
            if ($targetLessonId > 0) {
                $targetLesson = Lesson::with(['topics' => function ($query) {
                    $query->orderBy('position');
                }])->find($targetLessonId);
            }

            if (! $targetLesson) {
                $targetLesson = Lesson::where('type', 'course')->orderBy('position')->with(['topics' => function ($query) {
                    $query->orderBy('position');
                }])->first();
            }

            if ($targetLesson) {
                $targetTopic = null;
                if ($targetTopicId > 0) {
                    $targetTopic = $targetLesson->topics->firstWhere('id', $targetTopicId);
                }
                if (! $targetTopic) {
                    $targetTopic = $targetLesson->topics->first();
                }
                $targetUrl = url('/kelas/' . $targetLesson->id);
                if ($targetTopic) {
                    $targetUrl .= '?topic=' . $targetTopic->id;
                }
                if ($ticketId) {
                    return redirect()->to($targetUrl)->with(['ticket_id' => $ticketId]);
                }
                return redirect()->to($targetUrl);
            }
        }

        $payload = ['lesson' => $lesson->id];
        if ($orderId) {
            $payload['order_id'] = $orderId;
        }

        return redirect()->route('payments.thankyou', $payload)->with(['ticket_id' => $ticketId]);
    }

    /**
     * Resolve first course lesson/topic URL for LMS home.
     */
    protected function resolveFirstCourseTargetUrl(): ?string
    {
        $targetLessonId = (int) env('PAYMENT_LMS_LESSON_ID', 0);
        $targetTopicId = (int) env('PAYMENT_LMS_TOPIC_ID', 0);

        $targetLesson = null;
        if ($targetLessonId > 0) {
            $targetLesson = Lesson::with(['topics' => function ($query) {
                $query->orderBy('position');
            }])->find($targetLessonId);
        }

        if (! $targetLesson) {
            $targetLesson = Lesson::where('type', 'course')->orderBy('position')->with(['topics' => function ($query) {
                $query->orderBy('position');
            }])->first();
        }

        if (! $targetLesson) {
            return null;
        }

        $targetTopic = null;
        if ($targetTopicId > 0) {
            $targetTopic = $targetLesson->topics->firstWhere('id', $targetTopicId);
        }
        if (! $targetTopic) {
            $targetTopic = $targetLesson->topics->first();
        }

        $targetUrl = url('/kelas/' . $targetLesson->id);
        if ($targetTopic) {
            $targetUrl .= '?topic=' . $targetTopic->id;
        }

        return $targetUrl;
    }

    /**
     * Show final step / thank you page after purchase
     */
    public function thankyou(Lesson $lesson)
    {
        $user = Auth::user();
        // If an order_id query param exists, send user to the centralized payments.thankyou
        $orderId = request()->query('order_id') ?? request()->query('orderId') ?? null;
        if ($orderId) {
            return redirect()->route('payments.thankyou', ['order_id' => $orderId]);
        }

        if (! $user) return redirect()->route('dashboard');

        // Block access to thankyou page until we received settlement webhook from Midtrans
        $hasSettlement = Transaction::where('user_id', $user->id)
            ->whereIn('status', ['settlement','capture','success'])
            ->whereNotNull('midtrans_response')
            ->exists();

        if (! $hasSettlement) {
            // If we haven't recorded settlement yet, keep user on payment page
            return redirect()->route('kelas.payment', ['lesson' => $lesson->id])
                ->with('error', 'Pembayaran belum dikonfirmasi. Silakan selesaikan pembayaran di halaman pembayaran.');
        }

        $package = null;
        if ($user->package_id) {
            $package = Package::find($user->package_id);
        }

        // try to load ticket from flashed session (fallback to last ticket)
        $ticket = null;
        $ticketId = session('ticket_id');
        if ($ticketId) {
            $ticket = CoachingTicket::find($ticketId);
        }
        if (! $ticket) {
            $ticket = CoachingTicket::where('user_id', $user->id)->orderByDesc('id')->first();
        }

        return view('kelas.thankyou', compact('user', 'package', 'ticket', 'lesson'));
    }
}
