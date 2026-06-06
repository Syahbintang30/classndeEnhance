<?php

namespace App\Http\Controllers;

use App\Models\CoachingBooking;
use App\Models\CoachingTicket;
use App\Models\CoachingWarrantyTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Services\TwilioService;

class CoachingController extends Controller
{
    protected $twilio;

    public function __construct(TwilioService $twilio)
    {
        $this->twilio = $twilio;
    }

    public function index()
    {
        // Halaman utama coaching ini menampilkan semua aset milik user: ticket, booking, dan warranty.
        $user = Auth::user();
        $tickets = $user ? CoachingTicket::where('user_id', $user->id)->get() : collect();
        $bookings = $user ? CoachingBooking::where('user_id', $user->id)->get() : collect();

        // Cek apakah user masih punya ticket aktif yang bisa dipakai booking.
        $hasAvailableTicket = false;
        if ($user) {
            $hasAvailableTicket = CoachingTicket::where('user_id', $user->id)->where('is_used', false)->exists();
        }

        // Warranty ticket dipisahkan karena dipakai sebagai kompensasi atau pengganti sesi tertentu.
        $warrantyTickets = $user
            ? \App\Models\CoachingWarrantyTicket::where('user_id', $user->id)->orderByDesc('id')->get()
            : collect();
        $hasWarrantyTicket = $user
            ? \App\Models\CoachingWarrantyTicket::where('user_id', $user->id)->where('status', 'available')->exists()
            : false;
        $selectedWarrantyTicket = null;
        if ($user && request()->filled('warranty_ticket')) {
            $selectedWarrantyTicket = \App\Models\CoachingWarrantyTicket::where('id', request()->input('warranty_ticket'))
                ->where('user_id', $user->id)
                ->where('status', 'available')
                ->first();
        }
        if (! $selectedWarrantyTicket && $hasWarrantyTicket && ! $hasAvailableTicket) {
            $selectedWarrantyTicket = $warrantyTickets->firstWhere('status', 'available');
        }

        // Package coaching diambil dari slug config supaya flow booking tetap fleksibel.
        $coachingPkg = \App\Models\Package::where('slug', config('coaching.coaching_package_slug'))->first();

        return view('coaching.index', compact('tickets', 'bookings', 'hasAvailableTicket', 'coachingPkg', 'warrantyTickets', 'hasWarrantyTicket', 'selectedWarrantyTicket'));
    }

    // Feedback sekarang disimpan bersama booking di storeBooking().

    public function thankyou(?CoachingBooking $booking = null)
    {
        // Halaman ini dipakai setelah booking dibuat untuk menampilkan konfirmasi ke user.
        return view('coaching.thankyou', compact('booking'));
    }

    public function upcoming()
    {
        // Halaman upcoming merangkum booking mendatang plus status ticket user.
        $user = Auth::user();
        if ($user) {
            $qb = CoachingBooking::where('user_id', $user->id)->where('status', '!=', 'cancelled')->orderBy('booking_time');
            if (Schema::hasTable('coaching_feedbacks')) {
                $qb = $qb->with('feedback');
            }
            $bookings = $qb->get();
        } else {
            $bookings = collect();
        }

        $hasTicket = $user ? CoachingTicket::where('user_id', $user->id)->where('is_used', false)->exists() : false;
        $tickets = $user ? CoachingTicket::where('user_id', $user->id)->orderByDesc('id')->get() : collect();
        $warrantyTickets = $user
            ? CoachingWarrantyTicket::where('user_id', $user->id)->orderByDesc('id')->get()
            : collect();
        $hasWarrantyTicket = $user
            ? CoachingWarrantyTicket::where('user_id', $user->id)->where('status', 'available')->exists()
            : false;

        return view('coaching.upcoming', compact('bookings', 'hasTicket', 'tickets', 'warrantyTickets', 'hasWarrantyTicket'));
    }

    public function storeBooking(Request $request)
    {
        $user = Auth::user();
        if (! $user) return redirect()->route('login');
        $keluhKesahMaxLength = config('constants.business_logic.keluh_kesah_max_length');
        
        // Validasi input dasar sebelum booking dihitung lebih jauh.
        $data = $request->validate([
            'booking_time' => 'required|string',
            'notes' => 'nullable|string|max:255',
            'keluh_kesah' => "nullable|string|max:{$keluhKesahMaxLength}",
            'want_to_learn' => 'nullable|string|max:255',
            'warranty_ticket_id' => 'nullable|integer',
            'use_warranty' => 'nullable|boolean',
        ]);

        logger()->info('CoachingController@storeBooking called', ['user_id' => $user->id ?? null, 'payload' => $data]);

        // Pastikan format waktu booking benar dan masih berada dalam jendela waktu yang diizinkan.
        try {
            $dt = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data['booking_time']);
        } catch (\Throwable $e) {
            if (request()->wantsJson() || request()->header('Accept') === 'application/json') {
                return response()->json(['ok' => false, 'errors' => ['booking_time' => ['Invalid datetime format, expected YYYY-MM-DD HH:MM:SS']]], 422);
            }
            return redirect()->route('coaching.index')->withErrors(['booking_time' => 'Invalid datetime format, expected YYYY-MM-DD HH:MM:SS'])->withInput();
        }
        // Booking masih boleh dibuat kalau sesi sudah dimulai, selama sesi belum selesai.
        // Contoh: slot 01:00, user booking jam 01:10, lalu tetap bisa join jika accepted.
        // Panjang sesi diambil dari coaching.session_length_minutes (default 60).
        $sessionLength = config('coaching.session_length_minutes', 60);
        $now = now();
        try {
            $endWindow = $dt->copy()->addMinutes($sessionLength);
            if ($now->gt($endWindow)) {
                if (request()->wantsJson() || request()->header('Accept') === 'application/json') {
                    return response()->json(['ok' => false, 'errors' => ['booking_time' => ['Booking time is in the past and cannot be booked']]], 422);
                }
                return redirect()->route('coaching.index')->withErrors(['booking_time' => 'Booking time is in the past and cannot be booked'])->withInput();
            }
        } catch (\Throwable $e) {
            // Kalau ada masalah saat membandingkan waktu, sistem menolak booking demi keamanan.
            if (request()->wantsJson() || request()->header('Accept') === 'application/json') {
                return response()->json(['ok' => false, 'errors' => ['booking_time' => ['Invalid booking time']]], 422);
            }
            return redirect()->route('coaching.index')->withErrors(['booking_time' => 'Invalid booking time'])->withInput();
        }
        if ($dt->gt(now()->addMonths(6))) {
            if (request()->wantsJson() || request()->header('Accept') === 'application/json') {
                return response()->json(['ok' => false, 'errors' => ['booking_time' => ['Booking time is too far in the future']]], 422);
            }
            return redirect()->route('coaching.index')->withErrors(['booking_time' => 'Booking time is too far in the future'])->withInput();
        }

        // Warranty ticket bisa dipakai bila user memilihnya, atau otomatis jika tidak punya standard ticket.
        $warrantyTicket = null;
        $warrantyTicketId = $data['warranty_ticket_id'] ?? $request->input('warranty_ticket');
        $useWarranty = ! empty($data['use_warranty']) || ! empty($warrantyTicketId);

        if ($useWarranty) {
            if (! empty($warrantyTicketId)) {
                $warrantyTicket = \App\Models\CoachingWarrantyTicket::where('id', (int) $warrantyTicketId)
                    ->where('user_id', $user->id)
                    ->where('status', 'available')
                    ->first();
            } else {
                $warrantyTicket = \App\Models\CoachingWarrantyTicket::where('user_id', $user->id)
                    ->where('status', 'available')
                    ->orderByDesc('id')
                    ->first();
            }

            if (! $warrantyTicket) {
                if (request()->wantsJson() || request()->header('Accept') === 'application/json') {
                    return response()->json(['ok' => false, 'errors' => ['warranty_ticket' => ['Invalid warranty ticket.']]], 422);
                }
                return redirect()->route('coaching.index')->withErrors(['warranty_ticket' => 'Invalid warranty ticket.'])->withInput();
            }
        }

        if (! $warrantyTicket) {
            $hasStandardTicket = CoachingTicket::where('user_id', $user->id)->where('is_used', false)->exists();
            if (! $hasStandardTicket) {
                $warrantyTicket = \App\Models\CoachingWarrantyTicket::where('user_id', $user->id)
                    ->where('status', 'available')
                    ->orderByDesc('id')
                    ->first();
            }
        }

        if ($warrantyTicket && $warrantyTicket->warranty_minutes) {
            $sessionLength = (int) $warrantyTicket->warranty_minutes;
        }

        // Kalau tidak pakai warranty, booking harus mengonsumsi ticket reguler yang masih available.
        $ticket = null;
        if (! $warrantyTicket) {
            $ticket = CoachingTicket::where('user_id', $user->id)->where('is_used', false)->first();

            if (! $ticket) {
                if (request()->wantsJson() || request()->header('Accept') === 'application/json') {
                    return response()->json(['ok' => false, 'errors' => ['ticket' => ['No available tickets. Please purchase one.']]], 422);
                }
                return redirect()->route('coaching.index')->withErrors(['ticket' => 'No available tickets. Please purchase one.'])->withInput();
            }
        }

        // Simpan booking secara atomik supaya ticket, slot, dan booking tidak setengah jadi kalau gagal.
        $booking = null;
        try {
            \Illuminate\Support\Facades\DB::transaction(function() use (&$booking, $data, $user, $ticket, $warrantyTicket, $sessionLength) {
                $dt = \Carbon\Carbon::parse($data['booking_time']);
                $date = $dt->toDateString();
                $time = $dt->format('H:i');

                // Setiap slot dianggap kapasitas 1 sesuai desain coaching saat ini.
                $capacity = 1;

                // Row locking dipakai hanya jika driver mendukung, supaya double booking bisa dicegah.
                $driver = null;
                try {
                    $driver = \Illuminate\Support\Facades\DB::getPdo() ? \Illuminate\Support\Facades\DB::getPdo()->getAttribute(\PDO::ATTR_DRIVER_NAME) : null;
                } catch (\Throwable $e) {
                    $driver = null;
                }

                // Kunci baris slot untuk mencegah double booking saat belum ada booking lain.
                $slotQuery = \App\Models\CoachingSlotCapacity::where('date', $date)
                    ->where('time', $time);
                if (in_array($driver, ['mysql', 'pgsql', 'pgsql'])) {
                    $slotQuery = $slotQuery->lockForUpdate();
                }
                $slotRow = $slotQuery->first();
                if (! $slotRow) {
                    throw new \RuntimeException('Slot not available');
                }

                // Hitung booking aktif (pending/accepted) pada slot ini.
                $qb = CoachingBooking::whereDate('booking_time', $date)
                    ->whereTime('booking_time', $time)
                    ->whereIn('status', ['pending','accepted']);

                if (in_array($driver, ['mysql', 'pgsql', 'pgsql'])) {
                    $qb = $qb->lockForUpdate();
                }

                $taken = $qb->count();

                if ($taken >= $capacity) {
                    logger()->info('Booking slot full', ['date' => $date, 'time' => $time, 'taken' => $taken]);
                    throw new \RuntimeException('Slot full');
                }

                if ($warrantyTicket) {
                    $ticket = CoachingTicket::create([
                        'user_id' => $user->id,
                        'is_used' => true,
                        'source' => 'warranty',
                    ]);
                }

                // Booking langsung dibuat dengan status accepted supaya user bisa join tanpa review admin.
                $booking = CoachingBooking::create([
                    'user_id' => $user->id,
                    'ticket_id' => $ticket->id,
                    'booking_time' => $data['booking_time'],
                    'status' => 'accepted',
                    'session_number' => 1,
                    'session_duration_minutes' => $sessionLength,
                    'notes' => isset($data['notes']) ? $data['notes'] : null,
                ]);

                // Gabungkan field feedback ke booking.notes supaya semua catatan tersimpan di satu tempat.
                try {
                    $parts = [];
                    if (!empty($data['keluh_kesah'])) $parts[] = "Keluhan: " . $data['keluh_kesah'];
                    if (!empty($data['want_to_learn'])) $parts[] = "Ingin belajar: " . $data['want_to_learn'];
                    if (!empty($parts)) {
                        $extra = implode("\n\n", $parts);
                        $booking->notes = trim(($booking->notes ? $booking->notes . "\n\n" : '') . $extra);
                        $booking->save();
                    }
                } catch (\Throwable $e) {
                    logger()->warning('Failed to merge feedback into booking notes', ['err' => $e->getMessage()]);
                }

                logger()->info('CoachingBooking created inside transaction', ['booking_id' => $booking->id, 'user_id' => $user->id, 'ticket_id' => $ticket->id]);

                // Catatan: tabel CachingBooking sudah deprecated; sumber data utama ada di coaching_bookings.

                // Tandai ticket sebagai terpakai, kecuali kalau sudah di-handle oleh warranty.
                if (! $warrantyTicket) {
                    $ticket->is_used = true;
                    $ticket->save();
                }

                if ($warrantyTicket) {
                    $warrantyTicket->status = 'used';
                    $warrantyTicket->used_at = now();
                    $warrantyTicket->ticket_id = $ticket->id;
                    $warrantyTicket->save();
                }
            });
        } catch (\Throwable $e) {
            logger()->error('Booking transaction failed', ['error' => $e->getMessage()]);
            if (request()->wantsJson() || request()->header('Accept') === 'application/json') {
                return response()->json(['ok' => false, 'error' => 'Selected slot is full or failed to create booking'], 422);
            }
            return redirect()->route('coaching.index')->withErrors(['booking_time' => 'Failed to create booking, please try again.']);
        }

        // Pastikan data booking terbaru dan ticket sudah benar-benar ter-reserve.
        if ($booking) {
            $booking = $booking->fresh();

            // Log detail koneksi DB untuk membantu melacak lokasi penyimpanan data.
            try {
                $conn = \Illuminate\Support\Facades\DB::getDefaultConnection();
                $pdo = \Illuminate\Support\Facades\DB::getPdo();
                $driver = $pdo ? $pdo->getAttribute(\PDO::ATTR_DRIVER_NAME) : null;
                logger()->info('Booking DB info', ['connection' => $conn, 'driver' => $driver, 'booking_id' => $booking->id]);
            } catch (\Throwable $e) {
                logger()->warning('Failed to log DB info for booking', ['err' => $e->getMessage()]);
            }

            // Bersihkan cache ketersediaan agar frontend langsung melihat booking baru.
            try {
                $dt = \Carbon\Carbon::parse($booking->booking_time);
                $key = 'coaching_avail_range:' . $dt->toDateString() . ':' . $dt->toDateString();
                \Illuminate\Support\Facades\Cache::forget($key);
                logger()->info('Cleared coaching availability cache', ['key' => $key, 'booking_id' => $booking->id]);
            } catch (\Throwable $e) {
                logger()->warning('Failed to clear availability cache', ['err' => $e->getMessage(), 'booking_id' => $booking->id]);
            }
        }

        // Coba buat room Twilio untuk booking auto-approved jika Twilio sudah dikonfigurasi.
        try {
            if ($booking && $booking->status === 'accepted' && $this->twilio->isConfigured()) {
                // Samakan nama room supaya seluruh aplikasi mengarah ke identitas room yang sama.
                $roomName = 'coaching-' . $booking->id;
                logger()->info('Creating Twilio room', ['room' => $roomName, 'booking_id' => $booking->id]);
                $room = $this->twilio->createOrFetchRoom($roomName);
                if ($room && isset($room->sid)) {
                    $booking->twilio_room_sid = $room->sid;
                    $booking->save();
                    logger()->info('Twilio room created and saved', ['booking_id' => $booking->id, 'room_sid' => $room->sid]);
                }
            } else {
                logger()->info('Skipping Twilio room creation (not accepted or not configured)', ['booking_id' => $booking->id ?? null]);
            }
        } catch (\Throwable $e) {
            logger()->warning('Failed to create Twilio room during booking', ['booking' => $booking->id ?? null, 'error' => $e->getMessage()]);
        }

        // Beri notifikasi ke admin jika alamat email admin tersedia.
        try {
            $adminEmails = env('ADMIN_EMAILS', '');
            if ($adminEmails) {
                $list = array_map('trim', explode(',', $adminEmails));
                foreach ($list as $addr) {
                    \Illuminate\Support\Facades\Notification::route('mail', $addr)->notify(new \App\Notifications\AdminBookingCreated($booking));
                }
            }
        } catch (\Exception $e) {
            logger()->warning('Failed to notify admins about booking: ' . $e->getMessage());
        }

        // Jika request mengharapkan JSON, kembalikan booking id supaya frontend bisa redirect sendiri.
        if (request()->wantsJson() || request()->header('Accept') === 'application/json') {
            return response()->json(['ok' => true, 'booking' => $booking->id]);
        }

        // Untuk submit form biasa, arahkan ke halaman thank-you sederhana.
        return redirect()->route('coaching.thankyou', ['booking' => $booking->id])->with('success', 'Booking created successfully');
    }

    public function joinSession(CoachingBooking $booking)
    {
        // Endpoint ini membuka ruang video coaching untuk owner booking, coach, atau admin.
        $user = Auth::user();
        // Hak akses dasar: pemilik booking, coach yang ditugaskan, atau email coach yang dikonfigurasi.
        $isOwner = $user && $booking->user_id === $user->id;
        $isAssignedCoach = $user && $booking->coach_user_id && $booking->coach_user_id === $user->id;
        $isConfiguredCoach = false;
        if ($user && config('coaching.coaches')) {
            $isConfiguredCoach = in_array($user->email, config('coaching.coaches'));
        }

        // Admin juga boleh masuk dari panel admin untuk bantu monitoring sesi.
        $isAdmin = false;
        try {
            $isAdmin = $user && \Illuminate\Support\Facades\Gate::allows('admin');
        } catch (\Throwable $e) {
            $isAdmin = false;
        }
        if (! $user || (! $isOwner && ! $isAssignedCoach && ! $isConfiguredCoach && ! $isAdmin)) {
            abort(403);
        }
        if (! $this->twilio->isConfigured()) {
            abort(500, 'Twilio not configured');
        }

        $status = strtolower((string) $booking->status);
        if (in_array($status, ['ended', 'finished', 'completed'], true)) {
            abort(403, 'Session already ended');
        }
        if (! $isAdmin && ! in_array($status, ['accepted', 'scheduled'], true)) {
            abort(403, 'Session not available');
        }

        // Nama room dibuat konsisten supaya sesi yang sama selalu mengarah ke ruang video yang sama.
        $roomName = 'coaching-' . $booking->id;

        // Load relasi penting lebih awal supaya view tidak kena lazy-loading berulang.
        $booking->loadMissing(['user', 'ticket']);

        // Batas waktu akses untuk non-admin: mulai 10 menit sebelum sesi sampai durasi sesi habis.
        try {
            $start = \Carbon\Carbon::parse($booking->booking_time);
            $now = now();
            $duration = (int) ($booking->session_duration_minutes ?? config('coaching.session_length_minutes', 60));
            if (! $isAdmin && ($now->lt($start->copy()->subMinutes(10)) || $now->gt($start->copy()->addMinutes($duration)))) {
                abort(403, 'Session not available at this time');
            }
        } catch (\Throwable $e) {
            abort(400, 'Invalid booking time');
        }

        try {
            $room = $this->twilio->createOrFetchRoom($roomName);
        } catch (\Exception $e) {
            // Catat error dan tampilkan pesan yang ramah ke user.
            logger()->error('Twilio room error: ' . $e->getMessage(), ['booking' => $booking->id]);
            abort(500, 'Failed to prepare video room');
        }

        // Simpan twilio_room_sid kalau belum ada nilainya.
        if (! $booking->twilio_room_sid) {
            $booking->twilio_room_sid = $room->sid ?? null;
            $booking->save();
        }

        // Token akses dipakai Twilio supaya frontend bisa join room dengan identitas user yang benar.
        $identity = $this->twilio->generateIdentity($user);
        try {
            $accessToken = $this->twilio->createAccessToken($identity, $roomName);
        } catch (\Exception $e) {
            logger()->error('Twilio token error: ' . $e->getMessage(), ['booking' => $booking->id]);
            abort(500, 'Failed to generate access token');
        }

        // Flag admin dikirim ke view supaya UI bisa menampilkan kontrol khusus admin bila perlu.
        $sessionDurationMinutes = (int) ($booking->session_duration_minutes ?? config('coaching.session_length_minutes', 60));
        return view('coaching.session', compact('booking', 'accessToken', 'roomName', 'sessionDurationMinutes'))
            ->with('isAdmin', $isAdmin);
    }

    public function token(Request $request, CoachingBooking $booking)
    {
        // Endpoint ini mengeluarkan token JSON untuk frontend yang join session via AJAX.
        $user = Auth::user();
        $isOwner = $user && $booking->user_id === $user->id;
        $isAssignedCoach = $user && $booking->coach_user_id && $booking->coach_user_id === $user->id;
        $isConfiguredCoach = false;
        if ($user && config('coaching.coaches')) {
            $isConfiguredCoach = in_array($user->email, config('coaching.coaches'));
        }

        if (! $user || (! $isOwner && ! $isAssignedCoach && ! $isConfiguredCoach)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        if (! $this->twilio->isConfigured()) {
            return response()->json(['error' => 'Twilio not configured'], 500);
        }

        $status = strtolower((string) $booking->status);
        if (in_array($status, ['ended', 'finished', 'completed'], true)) {
            return response()->json(['error' => 'Session already ended'], 403);
        }
        if (! in_array($status, ['accepted', 'scheduled'], true)) {
            return response()->json(['error' => 'Session not available'], 403);
        }

        $identity = $this->twilio->generateIdentity($user);
        $roomName = 'coaching-' . $booking->id;

        // Token hanya boleh keluar di jendela waktu sesi yang sudah ditentukan.
        try {
            $start = \Carbon\Carbon::parse($booking->booking_time);
            $now = now();
            $duration = (int) ($booking->session_duration_minutes ?? config('coaching.session_length_minutes', 60));
            if ($now->lt($start->copy()->subMinutes(10)) || $now->gt($start->copy()->addMinutes($duration))) {
                return response()->json(['error' => 'Token not available at this time'], 403);
            }
        } catch (\Throwable $e) {
            // Kalau parsing gagal, tolak akses demi keamanan.
            return response()->json(['error' => 'Invalid booking time'], 400);
        }

        try {
            $token = $this->twilio->createAccessToken($identity, $roomName);
        } catch (\Exception $e) {
            logger()->error('Twilio token endpoint error: ' . $e->getMessage(), ['booking' => $booking->id]);
            return response()->json(['error' => 'Failed to generate token'], 500);
        }

        return response()->json([
            'token' => $token,
            'room' => $roomName,
        ]);
    }

    public function logEvent(Request $request, CoachingBooking $booking)
    {
        // Event log dipakai untuk mencatat momen penting selama sesi coaching berjalan.
        $user = Auth::user();
        $isOwner = $user && $booking->user_id === $user->id;
        $isAssignedCoach = $user && $booking->coach_user_id && $booking->coach_user_id === $user->id;
        $isConfiguredCoach = false;
        if ($user && config('coaching.coaches')) {
            $isConfiguredCoach = in_array($user->email, config('coaching.coaches'));
        }

        if (! $user || (! $isOwner && ! $isAssignedCoach && ! $isConfiguredCoach)) return response()->json(['error' => 'Unauthorized'], 403);

        $data = $request->validate([
            'event' => 'required|string',
            'meta' => 'nullable|array',
        ]);

        // Simpan event dalam bentuk timeline yang mudah dibaca, bukan telemetry mentah.
        try {
            $event = strtolower(trim((string) ($data['event'] ?? 'event')));
            $line = null;

            if ($event === 'session_end_clicked') {
                $line = '[' . now()->toDateTimeString() . '] Meeting selesai';
            } elseif ($event === 'session_ended_by_admin') {
                $line = '[' . now()->toDateTimeString() . '] Meeting selesai (diakhiri admin)';
            } elseif ($event === 'connect_error') {
                // Simpan error koneksi hanya di log server, bukan di catatan yang dilihat user.
                logger()->warning('Coaching connect_error event', ['booking_id' => $booking->id, 'meta' => $data['meta'] ?? null]);
            } else {
                $line = '[' . now()->toDateTimeString() . '] ' . ($data['event'] ?? 'event');
            }

            if ($line) {
                $booking->notes = trim(($booking->notes ? $booking->notes . "\n\n" : '') . $line);
                $booking->save();
            }
        } catch (\Throwable $e) {
            logger()->warning('Failed to append event to booking notes', ['err' => $e->getMessage()]);
        }

        return response()->json(['ok' => true]);
    }

    /**
    * Update note for a booking (user-editable).
     */
    public function updateNote(Request $request, CoachingBooking $booking)
    {
        $user = Auth::user();
        if (! $user || $booking->user_id !== $user->id) return redirect()->back()->with('error', 'Unauthorized');
        $data = $request->validate([ 'note' => 'required|string|max:255' ]);
        $booking->notes = $data['note'];
        $booking->save();
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['ok' => true, 'note' => $booking->notes]);
        }
        return redirect()->back()->with('success','Note saved');
    }

    /**
     * Update note on a pending caching booking (user-editable before admin acceptance)
     */
    public function updateCachingNote(Request $request, \App\Models\CachingBooking $caching)
    {
        $user = Auth::user();
        if (! $user || $caching->user_id !== $user->id) return redirect()->back()->with('error', 'Unauthorized');
        $data = $request->validate([ 'note' => 'required|string|max:255' ]);
        $meta = $caching->meta ?? [];
        $meta['note'] = $data['note'];
        $caching->meta = $meta;
        $caching->save();
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['ok' => true, 'note' => $meta['note']]);
        }
        return redirect()->back()->with('success','Note saved');
    }

    // Mengembalikan ketersediaan slot untuk tanggal tertentu.
    public function availability(Request $request)
    {
        $user = Auth::user();
        if (! $user) return response()->json(['error' => 'Unauthorized'], 403);

        $date = $request->query('date');
        if (! $date) return response()->json(['error' => 'date missing'], 400);

        // Ambil booking pada tanggal itu, dan hanya status aktif yang dihitung ke kapasitas.
        $booked = CoachingBooking::whereDate('booking_time', $date)
            ->whereIn('status', ['pending','accepted'])
            ->get();

        // Pre-compute jam booking milik user agar frontend bisa menandai "booking saya".
        $myBookedTimes = [];
        foreach ($booked as $b) {
            try {
                if ($b->user_id === ($user->id ?? null)) {
                    $myBookedTimes[] = \Carbon\Carbon::parse($b->booking_time)->format('H:i');
                }
            } catch (\Throwable $e) { /* abaikan masalah parsing */ }
        }

        // Muat kapasitas slot yang sudah ditetapkan admin untuk tanggal ini.
        $capacityRows = \App\Models\CoachingSlotCapacity::where('date', $date)->get();

        $sessionLength = (int) config('coaching.session_length_minutes', 60);
        $now = now();
        $result = [];
        if ($capacityRows->count() > 0) {
            // Bangun daftar slot dari waktu yang ditentukan admin (key HH:MM persis).
            foreach ($capacityRows as $r) {
                $time = $r->time;
                try {
                    $slotStart = \Carbon\Carbon::parse($date . ' ' . $time . ':00');
                    $slotEnd = $slotStart->copy()->addMinutes($sessionLength);
                    if ($now->gte($slotEnd)) {
                        continue;
                    }
                } catch (\Throwable $e) {
                    continue;
                }
                $cap = (int) ($r->capacity ?? 1);
                $result[$time] = ['capacity' => $cap, 'taken' => 0, 'remaining' => $cap];
            }

            // Hitung booking untuk waktu yang cocok persis (HH:MM).
            foreach ($booked as $b) {
                $t = \Carbon\Carbon::parse($b->booking_time)->format('H:i');
                if (isset($result[$t])) {
                    $result[$t]['taken']++;
                    $result[$t]['remaining'] = max(0, $result[$t]['capacity'] - $result[$t]['taken']);
                    if ($b->user_id === ($user->id ?? null)) {
                        $result[$t]['mine'] = true;
                    }
                }
            }
        } else {
            // Tidak ada slot buatan admin untuk tanggal ini, jadi frontend menyembunyikan availability.
            $result = [];
        }

        return response()->json(['slots' => $result]);
    }

    /**
     * Mengembalikan ringkasan ketersediaan untuk rentang tanggal (inklusif).
     * Format response: { days: { 'YYYY-MM-DD': remainingCount, ... } }
     */
    public function availabilityRange(Request $request)
    {
        $user = Auth::user();
        if (! $user) return response()->json(['error' => 'Unauthorized'], 403);

        $start = $request->query('start');
        $end = $request->query('end');
        if (! $start || ! $end) return response()->json(['error' => 'start/end missing'], 400);

        try {
            $startDt = \Carbon\Carbon::createFromFormat('Y-m-d', $start)->startOfDay();
            $endDt = \Carbon\Carbon::createFromFormat('Y-m-d', $end)->startOfDay();
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Invalid date format, expected YYYY-MM-DD'], 400);
        }

        if ($endDt->lt($startDt)) return response()->json(['error' => 'end must be >= start'], 400);

        // Validasi kecil supaya rentang tanggal tidak terlalu besar.
        if ($endDt->diffInDays($startDt) > 92) return response()->json(['error' => 'range too large'], 400);

        $sessionLength = (int) config('coaching.session_length_minutes', 60);
        $days = [];
        // Cache pendek opsional untuk mengurangi beban DB kalau banyak user membuka bulan yang sama.
        $cacheKey = 'coaching_avail_range:' . $startDt->toDateString() . ':' . $endDt->toDateString();
        $cached = \Illuminate\Support\Facades\Cache::get($cacheKey);
        if ($cached) return response()->json(['days' => $cached]);

        for ($d = $startDt->copy(); $d->lte($endDt); $d->addDay()) {
            $ds = $d->toDateString();
            $booked = CoachingBooking::whereDate('booking_time', $ds)
                ->whereIn('status', ['pending','accepted'])
                ->get();
            $capacityRows = \App\Models\CoachingSlotCapacity::where('date', $ds)->get();

            $remainingCount = 0;
            if ($capacityRows->count() > 0) {
                $map = [];
                foreach ($capacityRows as $r) {
                    $time = $r->time;
                    try {
                        $slotStart = \Carbon\Carbon::parse($ds . ' ' . $time . ':00');
                        $slotEnd = $slotStart->copy()->addMinutes($sessionLength);
                        if (now()->gte($slotEnd)) {
                            continue;
                        }
                    } catch (\Throwable $e) {
                        continue;
                    }
                    $cap = (int) ($r->capacity ?? 1);
                    $map[$time] = ['capacity' => $cap, 'taken' => 0, 'remaining' => $cap];
                }
                foreach ($booked as $b) {
                    $t = \Carbon\Carbon::parse($b->booking_time)->format('H:i');
                    if (isset($map[$t])) {
                        $map[$t]['taken']++;
                        $map[$t]['remaining'] = max(0, $map[$t]['capacity'] - $map[$t]['taken']);
                    }
                }
                foreach ($map as $time => $info) {
                    if (($info['remaining'] ?? 0) > 0) $remainingCount++;
                }
            } else {
                $remainingCount = 0;
            }
            $days[$ds] = $remainingCount;
        }

        // Cache sebentar (10 detik) untuk meredam lonjakan trafik.
        \Illuminate\Support\Facades\Cache::put($cacheKey, $days, 10);

        return response()->json(['days' => $days]);
    }
}
