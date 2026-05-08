<?php

use App\Http\Controllers\Admin\LessonController;
use App\Http\Controllers\Admin\PackageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\Admin\TopicController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SitemapController;

// Sitemap route
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::get('/ndeofficial', function () {
    $promoGuid = \App\Models\Setting::get('nde.promo_bunny_guid', null);
    $promoTitle = \App\Models\Setting::get('nde.promo_title', null);
    $promoThumbnail = \App\Models\Setting::get('nde.promo_thumbnail', null);
    $packages = \App\Models\Package::orderBy('price')->get();
    return view('compro', [
        'promo_bunny_guid' => $promoGuid,
        'promo_title' => $promoTitle,
        'promo_thumbnail_url' => $promoThumbnail,
        'packages' => $packages,
    ]);
})->name('compro');

Route::get('/', function () {
    return redirect(route('compro'));
});

Route::get('/checkout', [KelasController::class, 'index'])->middleware('rate.limit:default')->name('registerclass');
Route::redirect('/registerclass', '/checkout');
Route::get('/dashboard', function () { return redirect(route('registerclass')); })->name('dashboard');
Route::get('/lms', [KelasController::class, 'lmsEntry'])->name('lms.entry');
Route::get('/lms/dashboard', [KelasController::class, 'customerDashboard'])->middleware(['auth', 'verified'])->name('lms.dashboard');
Route::get('/lms/access-pending', function () {
    return view('lms.access-pending');
})->middleware('auth')->name('lms.pending');
Route::get('/kelas', [KelasController::class, 'lmsEntry'])->name('kelas');
Route::get('/registerclass/{lesson}', [KelasController::class, 'show'])->middleware(['auth', 'verified'])->name('kelas.show');
Route::get('/registerclass/{lesson}/content', [KelasController::class, 'content'])->middleware(['auth', 'verified'])->name('kelas.content');

// Friendly URLs used by client-side navigation: support direct requests to /kelas/{lesson}
// so a browser refresh doesn't return 404 when the JS pushState uses /kelas/...
Route::get('/kelas/{lesson}', [KelasController::class, 'show'])->middleware(['auth', 'verified']);
Route::get('/kelas/{lesson}/content', [KelasController::class, 'content'])->middleware(['auth', 'verified']);

Route::get('/song-tutorial/index', [App\Http\Controllers\SongTutorialController::class, 'index'])->name('song.tutorial.index');
Route::get('/song-tutorial', [App\Http\Controllers\SongTutorialController::class, 'index'])->name('song.tutorial');
Route::get('/song-tutorial/{lesson}', [App\Http\Controllers\SongTutorialController::class, 'show'])->name('song.tutorial.show');
Route::get('/song-tutorial/{lesson}/content', [App\Http\Controllers\SongTutorialController::class, 'content'])->name('song.tutorial.content');

Route::prefix('admin')->name('admin.')->middleware([\App\Http\Middleware\EnsureAdminOrSuper::class, 'audit.log'])->group(function () {
    Route::get('/', function(){
        $user = auth()->user();
        $isSuperadmin = (bool) ($user->is_superadmin ?? false);
        $successStatuses = ['settlement', 'capture', 'success', 'paid', 'settled'];

        $now = now();
        $monthStart = $now->copy()->startOfMonth();
        $prevMonthStart = $now->copy()->subMonthNoOverflow()->startOfMonth();
        $prevMonthEnd = $now->copy()->subMonthNoOverflow()->endOfMonth();

        $stats = [
            'lessons' => \App\Models\Lesson::count(),
            'topics' => \App\Models\Topic::count(),
            'packages' => \App\Models\Package::count(),
            'users' => \App\Models\User::count(),
            'pending_bookings' => \App\Models\CoachingBooking::where('status', 'pending')->count(),
            'today_transactions' => \App\Models\Transaction::whereDate('created_at', now()->toDateString())->count(),
            'today_revenue' => \App\Models\Transaction::whereDate('created_at', now()->toDateString())
                ->whereIn('status', $successStatuses)
                ->sum('amount'),
        ];

        $monthlyOrderCounts = \App\Models\Transaction::query()
            ->selectRaw('MONTH(created_at) as month_num, COUNT(*) as total')
            ->whereYear('created_at', $now->year)
            ->groupBy('month_num')
            ->pluck('total', 'month_num');

        $monthlyRevenue = \App\Models\Transaction::query()
            ->selectRaw('MONTH(created_at) as month_num, SUM(amount) as total')
            ->whereYear('created_at', $now->year)
            ->whereIn('status', $successStatuses)
            ->groupBy('month_num')
            ->pluck('total', 'month_num');

        $chartBars = collect(range(1, 12))->map(function ($m) use ($monthlyRevenue) {
            $monthValue = (int) ($monthlyRevenue[$m] ?? 0);
            return [
                'key' => $m,
                'label' => \Carbon\Carbon::create()->month($m)->locale('id')->translatedFormat('M'),
                'value' => $monthValue,
            ];
        });

        $maxBarValue = max(1, (int) $chartBars->max('value'));
        $chartBars = $chartBars->map(function ($bar) use ($maxBarValue) {
            $rawHeight = $bar['value'] > 0 ? (int) round(($bar['value'] / $maxBarValue) * 100) : 8;
            $bar['height'] = max(8, min(100, $rawHeight));
            return $bar;
        })->values();

        $currentMonthRevenue = (int) ($monthlyRevenue[$now->month] ?? 0);
        $prevMonthRevenue = (int) ($monthlyRevenue[$prevMonthStart->month] ?? 0);
        $monthlyTarget = $prevMonthRevenue > 0 ? $prevMonthRevenue : max(1, $currentMonthRevenue);
        $targetPercent = $monthlyTarget > 0 ? round(min(100, ($currentMonthRevenue / $monthlyTarget) * 100), 2) : 0;

        $kpiUsersCurrent = \App\Models\User::where('created_at', '>=', $monthStart)->count();
        $kpiUsersPrev = \App\Models\User::whereBetween('created_at', [$prevMonthStart, $prevMonthEnd])->count();
        $kpiOrdersCurrent = \App\Models\Transaction::where('created_at', '>=', $monthStart)->count();
        $kpiOrdersPrev = \App\Models\Transaction::whereBetween('created_at', [$prevMonthStart, $prevMonthEnd])->count();
        $kpiLessonsCurrent = \App\Models\Lesson::where('created_at', '>=', $monthStart)->count();
        $kpiLessonsPrev = \App\Models\Lesson::whereBetween('created_at', [$prevMonthStart, $prevMonthEnd])->count();

        $calcDelta = function (int $current, int $previous): float {
            if ($previous <= 0) {
                return $current > 0 ? 100.0 : 0.0;
            }
            return round((($current - $previous) / $previous) * 100, 1);
        };

        $kpiDeltas = [
            'users' => $calcDelta($kpiUsersCurrent, $kpiUsersPrev),
            'orders' => $calcDelta($kpiOrdersCurrent, $kpiOrdersPrev),
            'lessons' => $calcDelta($kpiLessonsCurrent, $kpiLessonsPrev),
        ];

        $dashboardMetrics = [
            'chart_bars' => $chartBars,
            'month_labels' => [
                'current' => $monthStart->translatedFormat('F Y'),
                'previous' => $prevMonthStart->translatedFormat('F Y'),
            ],
            'month_orders' => [
                'current' => (int) ($monthlyOrderCounts[$now->month] ?? 0),
                'previous' => (int) ($monthlyOrderCounts[$prevMonthStart->month] ?? 0),
            ],
            'month_revenue' => [
                'current' => $currentMonthRevenue,
                'previous' => $prevMonthRevenue,
            ],
            'target' => [
                'value' => $monthlyTarget,
                'percent' => $targetPercent,
            ],
            'kpi_deltas' => $kpiDeltas,
        ];

        $recentTransactions = \App\Models\Transaction::with(['user', 'package'])
            ->latest()
            ->limit(8)
            ->get();

        $recentAudits = collect();
        if ($isSuperadmin && \Illuminate\Support\Facades\Schema::hasTable('audit_trails')) {
            $recentAudits = \App\Models\AuditTrail::query()->latest()->limit(8)->get();
        }

        return view('admin.dashboard', [
            'isSuperadmin' => $isSuperadmin,
            'stats' => $stats,
            'dashboardMetrics' => $dashboardMetrics,
            'recentTransactions' => $recentTransactions,
            'recentAudits' => $recentAudits,
        ]);
    })->name('dashboard');
    Route::resource('lessons', LessonController::class);
    Route::get('packages', [PackageController::class, 'index'])->name('packages.index');
    Route::get('packages/create', [PackageController::class, 'create'])->name('packages.create');
    Route::post('packages', [PackageController::class, 'store'])->middleware('file.upload.security')->name('packages.store');
    Route::get('packages/{package}/edit', [PackageController::class, 'edit'])->name('packages.edit');
    Route::put('packages/{package}', [PackageController::class, 'update'])->middleware('file.upload.security')->name('packages.update');
    Route::delete('packages/{package}', [PackageController::class, 'destroy'])->name('packages.destroy');
    Route::post('bunny/upload-url', function (\Illuminate\Http\Request $request) {
        return response()->json([
            'success' => false,
            'message' => 'Direct browser upload endpoint temporarily disabled. Use the server upload endpoint at admin/bunny/upload-server.',
            'upload_url' => null,
        ], 501);
    })->name('bunny.upload-url');
    Route::post('bunny/upload-server', [App\Http\Controllers\BunnyController::class, 'uploadToBunny'])->middleware('file.upload.security')->name('bunny.upload-server');
    Route::get('bunny/video-status/{guid}', [App\Http\Controllers\BunnyController::class, 'videoStatus'])->name('bunny.video-status');
    Route::get('lessons/{lesson}/topics/create', [TopicController::class, 'create'])->name('topics.create');
    Route::post('lessons/{lesson}/topics', [TopicController::class, 'store'])->name('topics.store');
    Route::get('lessons/{lesson}/topics/{topic}/edit', [TopicController::class, 'edit'])->name('topics.edit');
    Route::put('lessons/{lesson}/topics/{topic}', [TopicController::class, 'update'])->name('topics.update');
    Route::delete('lessons/{lesson}/topics/{topic}', [TopicController::class, 'destroy'])->name('topics.destroy');
    Route::get('payment-methods', [App\Http\Controllers\Admin\PaymentMethodController::class, 'index'])->name('payment-methods.index');
    Route::post('payment-methods/update', [App\Http\Controllers\Admin\PaymentMethodController::class, 'update'])->middleware('file.upload.security')->name('payment-methods.update');
    Route::post('payment-methods', [App\Http\Controllers\Admin\PaymentMethodController::class, 'store'])->middleware('file.upload.security')->name('payment-methods.store');
    Route::delete('payment-methods/{id}', [App\Http\Controllers\Admin\PaymentMethodController::class, 'destroy'])->name('payment-methods.destroy');
    Route::post('payment-methods/{id}/test', [App\Http\Controllers\Admin\PaymentMethodController::class, 'test'])->name('payment-methods.test');
    Route::get('transactions', [App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('transactions.index');
    Route::get('coaching/bookings', [App\Http\Controllers\Admin\CoachingBookingController::class, 'index'])->middleware('can:admin')->name('admin.coaching.bookings');
    Route::post('coaching/bookings/{booking}/accept', [App\Http\Controllers\Admin\CoachingBookingController::class, 'accept'])->middleware('can:admin');
    Route::post('coaching/bookings/{booking}/reject', [App\Http\Controllers\Admin\CoachingBookingController::class, 'reject'])->middleware('can:admin');
    Route::post('coaching/bookings/{booking}/create-room', [App\Http\Controllers\Admin\CoachingBookingController::class, 'createRoom'])->middleware('can:admin');
    Route::post('coaching/bookings/{booking}/end-room', [App\Http\Controllers\Admin\CoachingBookingController::class, 'endRoom'])->middleware('can:admin');
    Route::get('coaching/feedbacks', [App\Http\Controllers\Admin\AdminFeedbackController::class, 'index'])->middleware('can:admin')->name('admin.coaching.feedbacks.index');
    Route::put('coaching/feedbacks/{feedback}', [App\Http\Controllers\Admin\AdminFeedbackController::class, 'update'])->middleware('can:admin')->name('admin.coaching.feedback.update');
    Route::get('coaching/slot-capacities', [App\Http\Controllers\Admin\CoachingSlotCapacityController::class, 'index'])->name('admin.coaching.slotcapacities');
    Route::post('coaching/slot-capacities', [App\Http\Controllers\Admin\CoachingSlotCapacityController::class, 'store']);
    Route::post('coaching/slot-capacities/delete', [App\Http\Controllers\Admin\CoachingSlotCapacityController::class, 'destroy']);
    Route::get('coaching/warranty-tickets', [App\Http\Controllers\Admin\CoachingWarrantyTicketController::class, 'index'])->name('admin.coaching.warranty');
    Route::get('settings/referral', [\App\Http\Controllers\Admin\SettingController::class, 'referralForm'])->name('referral.settings');
    Route::post('settings/referral', [\App\Http\Controllers\Admin\SettingController::class, 'referralSave'])->name('referral.save');
    Route::get('settings/referral/export', [\App\Http\Controllers\Admin\SettingController::class, 'exportReferralCsv'])->name('referral.export');
    Route::get('referral/settings', [\App\Http\Controllers\Admin\ReferralController::class, 'settingsForm'])->name('referral.settings.form');
    Route::post('referral/settings', [\App\Http\Controllers\Admin\ReferralController::class, 'saveSettings'])->name('referral.settings.save');
    Route::get('referral/leaderboard', [\App\Http\Controllers\Admin\ReferralController::class, 'leaderboard'])->name('referral.leaderboard');
    Route::get('referral/users/{referrer}', [\App\Http\Controllers\Admin\ReferralController::class, 'referredUsers'])->name('referral.users');
    Route::get('vouchers', [\App\Http\Controllers\Admin\VoucherController::class, 'index'])->name('vouchers.index');
    Route::get('vouchers/create', [\App\Http\Controllers\Admin\VoucherController::class, 'create'])->name('vouchers.create');
    Route::post('vouchers', [\App\Http\Controllers\Admin\VoucherController::class, 'store'])->name('vouchers.store');
    Route::get('vouchers/{voucher}/edit', [\App\Http\Controllers\Admin\VoucherController::class, 'edit'])->name('vouchers.edit');
    Route::put('vouchers/{voucher}', [\App\Http\Controllers\Admin\VoucherController::class, 'update'])->name('vouchers.update');
    Route::delete('vouchers/{voucher}', [\App\Http\Controllers\Admin\VoucherController::class, 'destroy'])->name('vouchers.destroy');
    
    // Audit trail (Superadmin)
    Route::get('audit', [\App\Http\Controllers\Admin\AuditTrailController::class, 'index'])->middleware(\App\Http\Middleware\EnsureSuperAdmin::class)->name('audit.index');
    Route::get('settings/promo', function () {
        return redirect(route('videopromo'));
    })->name('admin.settings.promo');
    Route::get('users/packages', [\App\Http\Controllers\Admin\ReferralController::class, 'userPackages'])->name('users.packages');
    Route::get('users/{user}/edit', [\App\Http\Controllers\Admin\ReferralController::class, 'editUser'])->name('users.edit');
    Route::post('users/{user}', [\App\Http\Controllers\Admin\ReferralController::class, 'updateUser'])->name('users.update');
    Route::get('videopromo', [\App\Http\Controllers\Admin\VideoPromoController::class, 'edit'])->middleware(\App\Http\Middleware\EnsureSuperAdmin::class)->name('videopromo');
    Route::post('videopromo', [\App\Http\Controllers\Admin\VideoPromoController::class, 'update'])->middleware(\App\Http\Middleware\EnsureSuperAdmin::class)->name('videopromo.update');
    
    // System Settings (Superadmin only to prevent security issues)
    Route::get('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->middleware(\App\Http\Middleware\EnsureSuperAdmin::class)->name('settings.index');
    Route::post('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->middleware(\App\Http\Middleware\EnsureSuperAdmin::class)->name('settings.update');
    Route::post('settings/reset', [\App\Http\Controllers\Admin\SettingsController::class, 'reset'])->middleware(\App\Http\Middleware\EnsureSuperAdmin::class)->name('settings.reset');
});

use App\Http\Controllers\BunnyController;
use App\Http\Controllers\CoachingController;
use App\Http\Controllers\CoachingCheckoutController;

Route::get('/topics/{topic}/stream', function ($topic) {
    // Avoid implicit model binding 404s: resolve manually and always return JSON 200
    $topicModel = \App\Models\Topic::find($topic);
    if (! $topicModel) {
        return response()->json(['url' => null]);
    }

    if ($topicModel->bunny_guid) {
        // If bunny_guid accidentally contains a full URL (some imports/store may do this),
        // return it directly instead of treating it as a GUID. This avoids producing
        // malformed URLs like https://video.b-cdn.net/https://.../playlist.m3u8
        if (preg_match('#^https?://#i', $topicModel->bunny_guid)) {
            return response()->json(['url' => $topicModel->bunny_guid]);
        }

        $signed = BunnyController::signUrl($topicModel->bunny_guid, 300);
        if ($signed) return response()->json(['url' => $signed]);
        return response()->json(['url' => BunnyController::cdnUrl($topicModel->bunny_guid)]);
    }

    $path = $topicModel->video_url ?? null;
    if (! $path) return response()->json(['url' => null]);
    if (preg_match('#^https?://#i', $path)) return response()->json(['url' => $path]);
    $signed = BunnyController::signUrl($path, 300);
    if ($signed) return response()->json(['url' => $signed]);
    return response()->json(['url' => BunnyController::cdnUrl($path)]);
})->name('topics.stream');

Route::get('/promo-stream', function () {
    $guid = \App\Models\Setting::get('nde.promo_bunny_guid', null);
    if (! $guid) return response()->json(['url' => null]);
    try {
        $signed = \App\Http\Controllers\BunnyController::signStreamUrl($guid, 300);
        if ($signed) return response()->json(['url' => $signed]);
        return response()->json(['url' => \App\Http\Controllers\BunnyController::cdnUrl($guid)]);
    } catch (\Throwable $e) {
        return response()->json(['url' => \App\Http\Controllers\BunnyController::cdnUrl($guid)]);
    }
});

Route::middleware(['auth', 'verified'])->group(function(){
    Route::get('/api/topics/{topic}/progress', function ($topic) {
        $topicModel = \App\Models\Topic::find($topic);
        if (! $topicModel) {
            return response()->json(['completed' => false, 'watched_seconds' => 0], 404);
        }

        if (! \Illuminate\Support\Facades\Schema::hasTable('topic_progresses')) {
            return response()->json(['completed' => false, 'watched_seconds' => 0]);
        }

        $progress = \App\Models\TopicProgress::where('user_id', auth()->id())
            ->where('topic_id', $topicModel->id)
            ->first();

        return response()->json([
            'completed' => (bool) ($progress->completed ?? false),
            'watched_seconds' => (int) ($progress->watched_seconds ?? 0),
        ]);
    })->name('topics.progress.show');

    Route::post('/api/topics/{topic}/progress', function (\Illuminate\Http\Request $request, $topic) {
        $topicModel = \App\Models\Topic::find($topic);
        if (! $topicModel) {
            return response()->json(['ok' => false, 'error' => 'topic_not_found'], 404);
        }

        if (! \Illuminate\Support\Facades\Schema::hasTable('topic_progresses')) {
            return response()->json(['ok' => true, 'completed' => false, 'watched_seconds' => 0]);
        }

        $data = $request->validate([
            'watched_seconds' => ['nullable', 'integer', 'min:0'],
            'duration_seconds' => ['nullable', 'integer', 'min:0'],
            'completed' => ['nullable', 'boolean'],
        ]);

        $incomingSeconds = (int) ($data['watched_seconds'] ?? 0);
        $durationSeconds = (int) ($data['duration_seconds'] ?? 0);
        $incomingCompleted = (bool) ($data['completed'] ?? false);

        // Mark complete automatically if playback is at least 95% of the known duration.
        if (! $incomingCompleted && $durationSeconds > 0) {
            $incomingCompleted = $incomingSeconds >= max(1, (int) floor($durationSeconds * 0.95));
        }

        $progress = \App\Models\TopicProgress::firstOrNew([
            'user_id' => auth()->id(),
            'topic_id' => $topicModel->id,
        ]);

        $currentSeconds = (int) ($progress->watched_seconds ?? 0);
        $progress->watched_seconds = max($currentSeconds, $incomingSeconds);
        $progress->completed = (bool) (($progress->completed ?? false) || $incomingCompleted);
        $progress->save();

        return response()->json([
            'ok' => true,
            'completed' => (bool) $progress->completed,
            'watched_seconds' => (int) $progress->watched_seconds,
        ]);
    })->middleware('throttle:120,1')->name('topics.progress.update');

    Route::get('/coaching', [CoachingController::class, 'index'])->name('coaching.index');
    Route::get('/coaching/availability', [CoachingController::class, 'availability'])->name('coaching.availability');
    Route::get('/coaching/availability-range', [CoachingController::class, 'availabilityRange'])->name('coaching.availability.range');
    Route::post('/coaching/book', [CoachingController::class, 'storeBooking'])->name('coaching.book');
    Route::get('/coaching/thankyou/{booking?}', function ($booking = null) { return view('coaching.thankyou', compact('booking')); })->name('coaching.thankyou');
    Route::get('/coaching/upcoming', function () {
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user) {
            $qb = \App\Models\CoachingBooking::where('user_id', $user->id)->where('status', '!=', 'cancelled')->orderBy('booking_time');
            if (\Illuminate\Support\Facades\Schema::hasTable('coaching_feedbacks')) {
                $qb = $qb->with('feedback');
            }
            $bookings = $qb->get();
        } else {
            $bookings = collect();
        }
        $hasTicket = $user ? \App\Models\CoachingTicket::where('user_id', $user->id)->where('is_used', false)->exists() : false;
        $tickets = $user ? \App\Models\CoachingTicket::where('user_id', $user->id)->orderByDesc('id')->get() : collect();
        $warrantyTickets = $user
            ? \App\Models\CoachingWarrantyTicket::where('user_id', $user->id)->orderByDesc('id')->get()
            : collect();
        $hasWarrantyTicket = $user
            ? \App\Models\CoachingWarrantyTicket::where('user_id', $user->id)->where('status', 'available')->exists()
            : false;
    return view('coaching.upcoming', compact('bookings', 'hasTicket', 'tickets', 'warrantyTickets', 'hasWarrantyTicket'));
    })->name('coaching.upcoming');
    Route::post('/coaching/{booking}/note', [\App\Http\Controllers\CoachingController::class, 'updateNote'])->name('coaching.note');
    Route::post('/coaching/caching/{caching}/note', [\App\Http\Controllers\CoachingController::class, 'updateCachingNote'])->name('coaching.caching.note');
    Route::get('/coaching/checkout', [CoachingCheckoutController::class, 'checkoutForm'])->name('coaching.checkout');
    Route::post('/coaching/checkout/create-order', [CoachingCheckoutController::class, 'createOrder'])->name('coaching.checkout.create');
    Route::post('/coaching/checkout/finalize', [CoachingCheckoutController::class, 'finalizeOrder'])->name('coaching.checkout.finalize');
    Route::get('/coaching/session/{booking}', [CoachingController::class, 'joinSession'])->name('coaching.session');
    Route::get('/coaching/token/{booking}', [CoachingController::class, 'token'])->middleware('throttle:30,1')->name('coaching.token');
    Route::post('/coaching/{booking}/event', [CoachingController::class, 'logEvent'])->middleware('throttle:30,1')->name('coaching.event');
    Route::post('/registerclass/{lesson}/buy', [App\Http\Controllers\KelasController::class, 'purchase'])->name('kelas.purchase');
    Route::get('/registerclass/{lesson}/thankyou', [App\Http\Controllers\KelasController::class, 'thankyou'])->name('kelas.thankyou');
});

Route::get('/registerclass/{lesson}/buy', [App\Http\Controllers\KelasController::class, 'buy'])->name('kelas.buy');

Route::post('/registerclass/{lesson}/payment/complete', [App\Http\Controllers\KelasController::class, 'paymentComplete'])->middleware('rate.limit:payment')->name('kelas.payment.complete');

Route::middleware(['auth', 'verified'])->group(function(){
    Route::get('/registerclass/{lesson}/payment', [App\Http\Controllers\KelasController::class, 'payment'])->name('kelas.payment');
});

// Require login to create Midtrans Snap token (account-first checkout)
// Allow guest to create Snap token (guest checkout). CSRF + rate-limit still apply.
Route::post('/api/midtrans/create', [App\Http\Controllers\MidtransController::class, 'createSnapToken'])
    ->middleware(['throttle:30,1']);


Route::post('/payments/midtrans-notify', [App\Http\Controllers\PaymentController::class, 'midtransNotification'])
    // Detach this route from the default 'web' stack so no session/CSRF is applied at all
    ->withoutMiddleware([
        'web', 'csrf',
        \App\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Cookie\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
    ])
    // Only run our webhook security middleware
    ->middleware('webhook.security:midtrans');

Route::get('/api/transactions/status', [App\Http\Controllers\PaymentController::class, 'transactionStatus'])
    ->middleware('throttle:60,1'); // Public, but rate-limited

Route::get('/payments/thankyou', [App\Http\Controllers\PaymentRedirectController::class, 'thankyou'])->name('payments.thankyou');
Route::get('/payments/error', [App\Http\Controllers\PaymentRedirectController::class, 'error'])->name('payments.error');
Route::get('/payments/status', [App\Http\Controllers\PaymentRedirectController::class, 'status'])->name('payments.status');
Route::get('/payments/autologin', [App\Http\Controllers\PaymentRedirectController::class, 'autoLogin'])->name('payments.autologin');
// Midtrans finish redirect (Snap finish URL). This was missing; add explicit route so external redirect works.
Route::get('/payments/finish', [App\Http\Controllers\PaymentRedirectController::class, 'finish'])->name('payments.finish');

Route::post('/webhooks/twilio/video', [App\Http\Controllers\TwilioWebhookController::class, 'video'])
    // Detach from default web stack: Twilio calls should not use session/CSRF
    ->withoutMiddleware([
        'web', 'csrf',
        \App\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Cookie\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
    ])
    ->middleware('webhook.security:twilio'); // Apply webhook security middleware

require __DIR__ . '/auth.php';

Route::post('/referral/validate', function (\Illuminate\Http\Request $request) {
    $code = $request->input('code');
    if (! $code) return response()->json(['valid' => false, 'message' => 'No code provided'], 200);
    $user = \App\Models\User::where('referral_code', $code)->first();
    if (! $user) return response()->json(['valid' => false, 'message' => 'Code not found'], 200);

    $dbVal = \App\Models\Setting::get('referral.discount_percent', null);
    $discount = $dbVal !== null ? (int) $dbVal : (int) config('referral.discount_percent', 2);

    return response()->json(['valid' => true, 'discount_percent' => $discount, 'referrer' => ['id' => $user->id, 'name' => $user->name]]);
});

Route::post('/vouchers/validate', function (\Illuminate\Http\Request $request) {
    $code = trim($request->input('code',''));
    if (! $code) return response()->json(['valid'=>false,'message'=>'No code provided']);
    $v = \App\Models\Voucher::where('code', $code)->first();
    if (! $v) return response()->json(['valid'=>false,'message'=>'Voucher not found']);
    if (! $v->isValid()) return response()->json(['valid'=>false,'message'=>'Voucher is not valid']);
    return response()->json(['valid'=>true,'discount_percent'=>$v->discount_percent,'voucher_id'=>$v->id]);
});

Route::middleware(['auth', 'verified'])->group(function(){
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->middleware('file.upload.security')->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/referrals', [ProfileController::class, 'referrals'])->name('profile.referrals');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');
});


