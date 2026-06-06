<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LessonController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\TopicController;
use App\Http\Controllers\BunnyController;
use App\Http\Controllers\CoachingCheckoutController;
use App\Http\Controllers\CoachingController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\LookupController;
use App\Http\Controllers\MediaStreamController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentRedirectController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SongTutorialController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\TopicProgressController;
use App\Http\Controllers\TwilioWebhookController;
use Illuminate\Support\Facades\Route;

// Route sitemap untuk mesin pencari dan akses publik.
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Halaman landing utama /ndeofficial: narik data promo, paket, dan FAQ untuk halaman marketing.
Route::get('/ndeofficial', [LandingController::class, 'index'])->name('compro');

// Root domain diarahkan ke landing page agar URL utama tetap konsisten.
Route::redirect('/', '/ndeofficial');

// Jalur checkout/lms lama tetap hidup supaya link lama dan bookmark user tidak putus.
Route::get('/checkout', [KelasController::class, 'index'])->middleware('rate.limit:default')->name('registerclass');
Route::redirect('/registerclass', '/checkout');
Route::redirect('/dashboard', '/checkout')->name('dashboard');
Route::get('/lms', [KelasController::class, 'lmsEntry'])->name('lms.entry');
Route::get('/lms/dashboard', [KelasController::class, 'customerDashboard'])->middleware(['auth', 'verified'])->name('lms.dashboard');
Route::view('/lms/access-pending', 'lms.access-pending')->middleware('auth')->name('lms.pending');
Route::get('/kelas', [KelasController::class, 'lmsEntry'])->name('kelas');
Route::get('/registerclass/{lesson}', [KelasController::class, 'show'])->middleware(['auth', 'verified'])->name('kelas.show');
Route::get('/registerclass/{lesson}/content', [KelasController::class, 'content'])->middleware(['auth', 'verified'])->name('kelas.content');

// URL friendly untuk navigasi SPA/manual refresh di halaman belajar.
// Ini mencegah 404 saat JS mengubah URL ke /kelas/{lesson} lalu user refresh browser.
Route::get('/kelas/{lesson}', [KelasController::class, 'show'])->middleware(['auth', 'verified']);
Route::get('/kelas/{lesson}/content', [KelasController::class, 'content'])->middleware(['auth', 'verified']);

// Jalur song tutorial dipertahankan sebagai entry point konten belajar yang sudah ada di sisi frontend.
Route::get('/song-tutorial/index', [SongTutorialController::class, 'index'])->name('song.tutorial.index');
Route::get('/song-tutorial', [SongTutorialController::class, 'index'])->name('song.tutorial');
Route::get('/song-tutorial/{lesson}', [SongTutorialController::class, 'show'])->name('song.tutorial.show');
Route::get('/song-tutorial/{lesson}/content', [SongTutorialController::class, 'content'])->name('song.tutorial.content');

// Semua route admin dikunci middleware admin + audit log supaya perubahan tercatat dan akses aman.
Route::prefix('admin')->name('admin.')->middleware([\App\Http\Middleware\EnsureAdminOrSuper::class, 'audit.log'])->group(function () {
    // Dashboard admin: ringkasan statistik, transaksi, dan audit terbaru.
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD konten inti LMS: lesson, topic, dan package.
    Route::resource('lessons', LessonController::class);
    Route::get('packages', [PackageController::class, 'index'])->name('packages.index');
    Route::get('packages/create', [PackageController::class, 'create'])->name('packages.create');
    Route::post('packages', [PackageController::class, 'store'])->middleware('file.upload.security')->name('packages.store');
    Route::get('packages/{package}/edit', [PackageController::class, 'edit'])->name('packages.edit');
    Route::put('packages/{package}', [PackageController::class, 'update'])->middleware('file.upload.security')->name('packages.update');
    Route::delete('packages/{package}', [PackageController::class, 'destroy'])->name('packages.destroy');

    // Upload helper Bunny dipisah supaya proses upload video tidak bercampur dengan logic UI.
    Route::post('bunny/upload-url', [BunnyController::class, 'createUploadUrl'])->name('bunny.upload-url');
    Route::post('bunny/upload-server', [BunnyController::class, 'uploadToBunny'])->middleware('file.upload.security')->name('bunny.upload-server');
    Route::get('bunny/video-status/{guid}', [BunnyController::class, 'videoStatus'])->name('bunny.video-status');

    // Manajemen topic per lesson untuk konten belajar.
    Route::get('lessons/{lesson}/topics/create', [TopicController::class, 'create'])->name('topics.create');
    Route::post('lessons/{lesson}/topics', [TopicController::class, 'store'])->name('topics.store');
    Route::get('lessons/{lesson}/topics/{topic}/edit', [TopicController::class, 'edit'])->name('topics.edit');
    Route::put('lessons/{lesson}/topics/{topic}', [TopicController::class, 'update'])->name('topics.update');
    Route::delete('lessons/{lesson}/topics/{topic}', [TopicController::class, 'destroy'])->name('topics.destroy');

    // Payment methods dan transaksi dipisah supaya admin finance punya area kerja sendiri.
    Route::get('payment-methods', [App\Http\Controllers\Admin\PaymentMethodController::class, 'index'])->name('payment-methods.index');
    Route::post('payment-methods/update', [App\Http\Controllers\Admin\PaymentMethodController::class, 'update'])->middleware('file.upload.security')->name('payment-methods.update');
    Route::post('payment-methods', [App\Http\Controllers\Admin\PaymentMethodController::class, 'store'])->middleware('file.upload.security')->name('payment-methods.store');
    Route::delete('payment-methods/{id}', [App\Http\Controllers\Admin\PaymentMethodController::class, 'destroy'])->name('payment-methods.destroy');
    Route::post('payment-methods/{id}/test', [App\Http\Controllers\Admin\PaymentMethodController::class, 'test'])->name('payment-methods.test');
    Route::get('transactions', [App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('transactions.index');

    // Operasional coaching: booking, feedback, slot kapasitas, dan warranty ticket.
    Route::get('coaching/bookings', [App\Http\Controllers\Admin\CoachingBookingController::class, 'index'])->middleware('can:admin')->name('coaching.bookings');
    Route::post('coaching/bookings/{booking}/accept', [App\Http\Controllers\Admin\CoachingBookingController::class, 'accept'])->middleware('can:admin');
    Route::post('coaching/bookings/{booking}/reject', [App\Http\Controllers\Admin\CoachingBookingController::class, 'reject'])->middleware('can:admin');
    Route::post('coaching/bookings/{booking}/create-room', [App\Http\Controllers\Admin\CoachingBookingController::class, 'createRoom'])->middleware('can:admin');
    Route::post('coaching/bookings/{booking}/end-room', [App\Http\Controllers\Admin\CoachingBookingController::class, 'endRoom'])->middleware('can:admin');
    Route::get('coaching/feedbacks', [App\Http\Controllers\Admin\AdminFeedbackController::class, 'index'])->middleware('can:admin')->name('coaching.feedbacks.index');
    Route::put('coaching/feedbacks/{feedback}', [App\Http\Controllers\Admin\AdminFeedbackController::class, 'update'])->middleware('can:admin')->name('coaching.feedback.update');
    Route::get('coaching/slot-capacities', [App\Http\Controllers\Admin\CoachingSlotCapacityController::class, 'index'])->name('coaching.slotcapacities');
    Route::post('coaching/slot-capacities', [App\Http\Controllers\Admin\CoachingSlotCapacityController::class, 'store']);
    Route::post('coaching/slot-capacities/delete', [App\Http\Controllers\Admin\CoachingSlotCapacityController::class, 'destroy']);
    Route::get('coaching/warranty-tickets', [App\Http\Controllers\Admin\CoachingWarrantyTicketController::class, 'index'])->name('coaching.warranty');

    // FAQ admin tetap di sini karena dipakai halaman marketing utama.
    Route::get('faq', [App\Http\Controllers\Admin\FaqController::class, 'index'])->name('faq.index');
    Route::post('faq', [App\Http\Controllers\Admin\FaqController::class, 'store'])->name('faq.store');
    Route::put('faq/{faqItem}', [App\Http\Controllers\Admin\FaqController::class, 'update'])->name('faq.update');
    Route::delete('faq/{faqItem}', [App\Http\Controllers\Admin\FaqController::class, 'destroy'])->name('faq.destroy');

    // Referral settings dan leaderboard dipakai untuk program promo dan akuisisi user.
    Route::get('settings/referral', [\App\Http\Controllers\Admin\SettingController::class, 'referralForm'])->name('referral.settings');
    Route::post('settings/referral', [\App\Http\Controllers\Admin\SettingController::class, 'referralSave'])->name('referral.save');
    Route::get('settings/referral/export', [\App\Http\Controllers\Admin\SettingController::class, 'exportReferralCsv'])->name('referral.export');
    Route::get('referral/settings', [\App\Http\Controllers\Admin\ReferralController::class, 'settingsForm'])->name('referral.settings.form');
    Route::post('referral/settings', [\App\Http\Controllers\Admin\ReferralController::class, 'saveSettings'])->name('referral.settings.save');
    Route::get('referral/leaderboard', [\App\Http\Controllers\Admin\ReferralController::class, 'leaderboard'])->name('referral.leaderboard');
    Route::get('referral/users/{referrer}', [\App\Http\Controllers\Admin\ReferralController::class, 'referredUsers'])->name('referral.users');

    // Voucher management mendukung diskon checkout dan campaign tertentu.
    Route::get('vouchers', [\App\Http\Controllers\Admin\VoucherController::class, 'index'])->name('vouchers.index');
    Route::get('vouchers/create', [\App\Http\Controllers\Admin\VoucherController::class, 'create'])->name('vouchers.create');
    Route::post('vouchers', [\App\Http\Controllers\Admin\VoucherController::class, 'store'])->name('vouchers.store');
    Route::get('vouchers/{voucher}/edit', [\App\Http\Controllers\Admin\VoucherController::class, 'edit'])->name('vouchers.edit');
    Route::put('vouchers/{voucher}', [\App\Http\Controllers\Admin\VoucherController::class, 'update'])->name('vouchers.update');
    Route::delete('vouchers/{voucher}', [\App\Http\Controllers\Admin\VoucherController::class, 'destroy'])->name('vouchers.destroy');
    
    // Audit trail khusus superadmin untuk jejak perubahan sistem.
    Route::get('audit', [\App\Http\Controllers\Admin\AuditTrailController::class, 'index'])->middleware(\App\Http\Middleware\EnsureSuperAdmin::class)->name('audit.index');
    // Alias legacy promo settings diarahkan ke Video Promo supaya link lama tetap jalan.
    Route::redirect('settings/promo', '/admin/videopromo')->name('settings.promo');
    Route::get('users/packages', [\App\Http\Controllers\Admin\ReferralController::class, 'userPackages'])->name('users.packages');
    Route::get('users/{user}/edit', [\App\Http\Controllers\Admin\ReferralController::class, 'editUser'])->name('users.edit');
    Route::post('users/{user}', [\App\Http\Controllers\Admin\ReferralController::class, 'updateUser'])->name('users.update');
    // Video Promo: konten promosi landing page, hanya superadmin yang boleh ubah.
    Route::get('videopromo', [\App\Http\Controllers\Admin\VideoPromoController::class, 'edit'])->middleware(\App\Http\Middleware\EnsureSuperAdmin::class)->name('videopromo');
    Route::post('videopromo', [\App\Http\Controllers\Admin\VideoPromoController::class, 'update'])->middleware(\App\Http\Middleware\EnsureSuperAdmin::class)->name('videopromo.update');
    
    // Pengaturan sistem khusus superadmin untuk mencegah risiko keamanan.
    Route::get('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->middleware(\App\Http\Middleware\EnsureSuperAdmin::class)->name('settings.index');
    Route::post('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->middleware(\App\Http\Middleware\EnsureSuperAdmin::class)->name('settings.update');
    Route::post('settings/reset', [\App\Http\Controllers\Admin\SettingsController::class, 'reset'])->middleware(\App\Http\Middleware\EnsureSuperAdmin::class)->name('settings.reset');
});

// Endpoint streaming video topic: dipakai player untuk ambil URL signed/CDN tanpa buka logic di frontend.
Route::get('/topics/{topic}/stream', [MediaStreamController::class, 'topicStream'])->name('topics.stream');

// Stream promo landing page dipisah supaya konten marketing tetap bisa berubah dari setting.
Route::get('/promo-stream', [MediaStreamController::class, 'promoStream']);

// Area ini hanya untuk user login dan terverifikasi karena menyentuh progress belajar dan coaching.
Route::middleware(['auth', 'verified'])->group(function () {
    // API progress dipakai player belajar untuk baca/tulis posisi tontonan.
    Route::get('/api/topics/{topic}/progress', [TopicProgressController::class, 'show'])->name('topics.progress.show');
    Route::post('/api/topics/{topic}/progress', [TopicProgressController::class, 'update'])->middleware('throttle:120,1')->name('topics.progress.update');

    // Coaching member flow: booking, jadwal, join session, dan catatan sesi.
    Route::get('/coaching', [CoachingController::class, 'index'])->name('coaching.index');
    Route::get('/coaching/availability', [CoachingController::class, 'availability'])->name('coaching.availability');
    Route::get('/coaching/availability-range', [CoachingController::class, 'availabilityRange'])->name('coaching.availability.range');
    Route::post('/coaching/book', [CoachingController::class, 'storeBooking'])->name('coaching.book');
    Route::get('/coaching/thankyou/{booking?}', [CoachingController::class, 'thankyou'])->name('coaching.thankyou');
    Route::get('/coaching/upcoming', [CoachingController::class, 'upcoming'])->name('coaching.upcoming');
    Route::post('/coaching/{booking}/note', [CoachingController::class, 'updateNote'])->name('coaching.note');
    Route::post('/coaching/caching/{caching}/note', [CoachingController::class, 'updateCachingNote'])->name('coaching.caching.note');
    Route::get('/coaching/checkout', [CoachingCheckoutController::class, 'checkoutForm'])->name('coaching.checkout');
    Route::post('/coaching/checkout/create-order', [CoachingCheckoutController::class, 'createOrder'])->name('coaching.checkout.create');
    Route::post('/coaching/checkout/finalize', [CoachingCheckoutController::class, 'finalizeOrder'])->name('coaching.checkout.finalize');
    Route::get('/coaching/session/{booking}', [CoachingController::class, 'joinSession'])->name('coaching.session');
    Route::get('/coaching/token/{booking}', [CoachingController::class, 'token'])->middleware('throttle:30,1')->name('coaching.token');
    Route::post('/coaching/{booking}/event', [CoachingController::class, 'logEvent'])->middleware('throttle:30,1')->name('coaching.event');

    // Checkout class/paket yang butuh akun aktif sebelum transaksi lanjut.
    Route::post('/registerclass/{lesson}/buy', [KelasController::class, 'purchase'])->name('kelas.purchase');
    Route::get('/registerclass/{lesson}/thankyou', [KelasController::class, 'thankyou'])->name('kelas.thankyou');
});

// Detail pembelian class tetap terbuka sebagai landing bagi user yang datang dari link lama.
Route::get('/registerclass/{lesson}/buy', [KelasController::class, 'buy'])->name('kelas.buy');

// Callback internal untuk menandai pembayaran class selesai.
Route::post('/registerclass/{lesson}/payment/complete', [KelasController::class, 'paymentComplete'])->middleware('rate.limit:payment')->name('kelas.payment.complete');

// Halaman payment hanya untuk user yang sudah login dan verified.
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/registerclass/{lesson}/payment', [KelasController::class, 'payment'])->name('kelas.payment');
});

// Midtrans Snap token dipakai untuk memulai pembayaran, baik mode akun maupun guest checkout.
// CSRF dan rate limit tetap aktif kecuali untuk webhook-notify di bawah.
Route::post('/api/midtrans/create', [MidtransController::class, 'createSnapToken'])
    ->middleware(['throttle:30,1']);


// Notifikasi server-to-server dari Midtrans: harus bebas session/CSRF karena dipanggil gateway eksternal.
Route::post('/payments/midtrans-notify', [PaymentController::class, 'midtransNotification'])
    // Lepaskan route ini dari stack web default supaya session/CSRF tidak ikut diproses.
    ->withoutMiddleware([
        'web', 'csrf',
        \App\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Cookie\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
    ])
    // Hanya jalankan middleware keamanan webhook milik kita.
    ->middleware('webhook.security:midtrans');

// Public status endpoint untuk mengecek transaksi, tapi tetap diberi rate limit.
Route::get('/api/transactions/status', [PaymentController::class, 'transactionStatus'])
    ->middleware('throttle:60,1'); // Publik, tapi tetap diberi rate limit.

// Redirect halaman pembayaran untuk sukses, gagal, status, dan auto login setelah payment.
Route::get('/payments/thankyou', [PaymentRedirectController::class, 'thankyou'])->name('payments.thankyou');
Route::get('/payments/error', [PaymentRedirectController::class, 'error'])->name('payments.error');
Route::get('/payments/status', [PaymentRedirectController::class, 'status'])->name('payments.status');
Route::get('/payments/autologin', [PaymentRedirectController::class, 'autoLogin'])->name('payments.autologin');
// Redirect finish Midtrans (Snap finish URL) ditambahkan supaya redirect eksternal dari gateway bekerja.
Route::get('/payments/finish', [PaymentRedirectController::class, 'finish'])->name('payments.finish');

// Webhook Twilio video juga harus bebas CSRF/session karena dipanggil dari service eksternal.
Route::post('/webhooks/twilio/video', [TwilioWebhookController::class, 'video'])
    // Lepaskan dari stack web default karena panggilan Twilio tidak boleh pakai session/CSRF.
    ->withoutMiddleware([
        'web', 'csrf',
        \App\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Cookie\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
    ])
    ->middleware('webhook.security:twilio'); // Jalankan middleware keamanan webhook Twilio.

require __DIR__ . '/auth.php';

// Validasi referral dan voucher dipakai AJAX checkout supaya user bisa cek diskon tanpa reload halaman.
Route::post('/referral/validate', [LookupController::class, 'referralValidate']);

Route::post('/vouchers/validate', [LookupController::class, 'voucherValidate']);

// Profil user hanya tersedia setelah login dan verifikasi email.
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->middleware('file.upload.security')->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/referrals', [ProfileController::class, 'referrals'])->name('profile.referrals');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');
});
