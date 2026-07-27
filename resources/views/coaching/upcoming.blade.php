@extends('layouts.app')

@push('head')
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            important: true,
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            black: '#08080a',
                            card: 'rgba(18, 18, 24, 0.65)',
                            border: 'rgba(255, 255, 255, 0.08)',
                            accent: '#0066ff',
                            amber: '#f59e0b',
                            crimson: '#ef4444'
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        display: ['"Bebas Neue"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        .tw-dash {
            background-color: #08080a !important;
            color: #f3f4f6 !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
        }
        .tw-dash .font-display {
            font-family: 'Bebas Neue', cursive;
            letter-spacing: 1px;
        }
        .glass-panel {
            background: rgba(18, 18, 26, 0.55);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 1.5rem;
        }
        .glass-panel-glow:hover {
            border-color: rgba(59, 130, 246, 0.3);
            box-shadow: 0 0 30px rgba(59, 130, 246, 0.12);
        }
        body > nav { display: none !important; }
        .tw-dash ::-webkit-scrollbar { width: 6px; }
        .tw-dash ::-webkit-scrollbar-track { background: #08080a; }
        .tw-dash ::-webkit-scrollbar-thumb { background: #222232; border-radius: 3px; }
        .tw-dash a { text-decoration: none; }
        .tw-dash *:focus { outline: none !important; }
    </style>
@endpush

@section('content')
@php
    $isEn = (session('app_lang', request('lang', 'id')) === 'en');
@endphp
<div class="tw-dash min-h-screen flex flex-col antialiased bg-[#08080a] text-gray-200 relative overflow-hidden" x-data="{ mobileMenuOpen: false }">

    {{-- Ambient Mesh Background Glow --}}
    <div class="absolute -top-32 left-1/3 w-[500px] h-[500px] bg-blue-600/10 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute top-1/2 -right-32 w-[400px] h-[400px] bg-emerald-500/10 rounded-full blur-[120px] pointer-events-none"></div>

    {{-- ─── TOP NAVIGATION BAR ──────────────────────────────────────────── --}}
    @include('layouts.lms_header')

    <!-- MAIN COACHING DASHBOARD -->
    <main class="flex-1 max-w-7xl mx-auto w-full px-4 lg:px-8 py-10 space-y-10 relative z-10">
        <header class="mb-6">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-bold uppercase tracking-wider mb-3">
                <i class="fa-solid fa-user-ninja"></i> {{ $isEn ? 'Personal Coaching Hub' : 'Hub Coaching Privat' }}
            </div>
            <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl tracking-wide text-white mb-2 uppercase">
                Coaching <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-400 via-indigo-300 to-white">Dashboard</span>
            </h1>
            <p class="text-gray-400 text-sm max-w-xl">{{ $isEn ? 'Manage your active passes and upcoming live 1-on-1 video sessions to accelerate your playing.' : 'Kelola tiket aktif dan jadwal video call live 1-on-1 bersama mentor.' }}</p>
        </header>

        {{-- Ticket variables --}}
        @php
            $totalTickets = isset($tickets) && is_iterable($tickets) ? collect($tickets)->count() : 0;
            $availableTickets = 0;
            if (isset($tickets) && is_iterable($tickets)) {
                $availableTickets = collect($tickets)->where('is_used', false)->count();
            }
            
            $totalWarrantyTickets = isset($warrantyTickets) && is_iterable($warrantyTickets) ? collect($warrantyTickets)->count() : 0;
            $availableWarrantyTickets = 0;
            if (isset($warrantyTickets) && is_iterable($warrantyTickets)) {
                $availableWarrantyTickets = collect($warrantyTickets)->where('status', 'available')->count();
            }
        @endphp

        <!-- SECTION 1: YOUR PASSES -->
        <section>
            <h2 class="font-display text-2xl tracking-wide text-white mb-5 flex items-center gap-3">
                <i class="fa-solid fa-ticket text-blue-400"></i> {{ $isEn ? 'Your Passes' : 'Tiket & Akses Kamu' }}
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Primary Ticket Card -->
                <div class="glass-panel p-6 sm:p-7 glass-panel-glow flex flex-col sm:flex-row justify-between items-center gap-6 transition duration-300">
                    <div class="flex-1">
                        <div class="flex items-center gap-4 mb-3">
                            <div class="w-12 h-12 rounded-2xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center text-xl flex-shrink-0">
                                <i class="fa-solid fa-star"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg text-white">{{ $isEn ? 'Coaching Tickets' : 'Tiket Coaching' }}</h3>
                                <p class="text-xs text-gray-400">{{ $isEn ? 'Regular 1-on-1 Live Sessions' : 'Sesi Live 1-on-1 Privat' }}</p>
                            </div>
                        </div>
                        <div class="mt-4 flex items-baseline gap-2">
                            <span class="font-display text-5xl text-blue-400">{{ $availableTickets }}</span>
                            <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider">{{ $isEn ? 'available of '.$totalTickets.' total' : 'tersedia dari total '.$totalTickets }}</span>
                        </div>
                    </div>

                    <div class="w-full sm:w-auto flex-shrink-0">
                        @if($availableTickets > 0)
                            <a href="{{ route('coaching.index') }}" class="w-full sm:w-auto py-3.5 px-6 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-sm rounded-xl text-center transition shadow-lg hover:shadow-blue-600/30 flex items-center justify-center gap-2">
                                <span>{{ $isEn ? 'Book Session' : 'Jadwalkan Sesi' }}</span> <i class="fa-solid fa-arrow-right text-xs"></i>
                            </a>
                        @else
                            <a href="{{ route('coaching.checkout') }}" class="w-full sm:w-auto py-3.5 px-6 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-sm rounded-xl text-center transition shadow-lg hover:shadow-blue-600/30 flex items-center justify-center gap-2">
                                <span>{{ $isEn ? 'Buy Ticket' : 'Beli Tiket' }}</span> <i class="fa-solid fa-arrow-right text-xs"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Warranty Ticket Card -->
                <div class="glass-panel p-6 sm:p-7 glass-panel-glow flex flex-col sm:flex-row justify-between items-center gap-6 transition duration-300">
                    <div class="flex-1">
                        <div class="flex items-center gap-4 mb-3">
                            <div class="w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center text-xl flex-shrink-0">
                                <i class="fa-solid fa-shield-halved"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg text-white">Warranty Tickets</h3>
                                <p class="text-xs text-gray-400">Backup & Rescheduled Sessions</p>
                            </div>
                        </div>
                        <div class="mt-4 flex items-baseline gap-2">
                            <span class="font-display text-5xl text-amber-400">{{ $availableWarrantyTickets }}</span>
                            <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider">available</span>
                        </div>
                    </div>

                    <div class="w-full sm:w-auto flex-shrink-0">
                        @php
                            $firstAvailableWarranty = isset($warrantyTickets) && is_iterable($warrantyTickets)
                                ? collect($warrantyTickets)->firstWhere('status', 'available')
                                : null;
                        @endphp
                        @if($firstAvailableWarranty)
                            <a href="{{ route('coaching.index', ['warranty_ticket' => $firstAvailableWarranty->id]) }}" class="w-full sm:w-auto py-3.5 px-6 bg-amber-500/20 hover:bg-amber-500/30 border border-amber-500/30 text-amber-300 font-bold text-sm rounded-xl text-center transition flex items-center justify-center gap-2">
                                <span>Apply Ticket</span> <i class="fa-solid fa-check text-xs"></i>
                            </a>
                        @else
                            <div class="w-full sm:w-auto py-3 px-6 bg-zinc-950/60 border border-white/5 text-gray-500 font-semibold text-xs rounded-xl text-center">
                                {{ $isEn ? 'No Warranty Tickets' : 'Tidak Ada Tiket Garansi' }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION 2: UPCOMING APPOINTMENTS -->
        <section>
            <h2 class="font-display text-2xl tracking-wide text-white mb-5 flex items-center gap-3">
                <i class="fa-regular fa-calendar-check text-blue-400"></i> {{ $isEn ? 'Scheduled Appointments' : 'Jadwal Sesi Mendatang' }}
            </h2>

            @if($bookings->isEmpty() && (empty($caching) || $caching->isEmpty()))
                <div class="glass-panel p-12 text-center flex flex-col items-center justify-center">
                    <div class="w-20 h-20 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center text-3xl mb-5 shadow-[0_0_30px_rgba(59,130,246,0.15)]">
                        <i class="fa-regular fa-calendar-plus"></i>
                    </div>
                    <h3 class="font-display text-3xl text-white tracking-wide mb-2">{{ $isEn ? 'Ready to start your session?' : 'Siap untuk memulai sesi?' }}</h3>
                    <p class="text-gray-400 text-sm max-w-md mb-6">{{ $isEn ? 'You don\'t have any upcoming appointments scheduled. Book a session with your available tickets to level up your guitar skills.' : 'Kamu belum memiliki jadwal sesi mendatang. Pesan sesi dengan tiketmu untuk meningkatkan skill gitar secara cepat.' }}</p>
                    <a href="{{ $availableTickets > 0 ? route('coaching.index') : route('coaching.checkout') }}" class="py-3.5 px-8 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-sm rounded-full transition shadow-lg flex items-center gap-2">
                        <i class="fa-solid fa-calendar-day"></i> {{ $isEn ? 'Schedule Session Now' : 'Jadwalkan Sesi Sekarang' }}
                    </a>
                </div>
            @else
                <div class="space-y-4">
                    @php
                        $chronoBookings = $bookings->sortBy('booking_time')->values();
                        $sessionNumbers = [];
                        foreach ($chronoBookings as $cIdx => $cBook) {
                            $sessionNumbers[$cBook->id] = $cIdx + 1;
                        }
                        $sortedBookings = $bookings->sortByDesc('booking_time')->values();
                    @endphp
                    @foreach($sortedBookings as $index => $b)
                        @php
                            $dt = \Carbon\Carbon::parse($b->booking_time);
                            $now = \Carbon\Carbon::now();
                            $sessionLength = (int) ($b->session_duration_minutes ?? config('coaching.session_length_minutes', 60));
                            try {
                                $sessionEnd = $dt->copy()->addMinutes($sessionLength);
                                $isPast = $sessionEnd->lt($now);
                                $isLiveWindow = $now->gte($dt) && $now->lte($sessionEnd);
                            } catch (\Throwable $e) {
                                $isPast = false;
                                $isLiveWindow = false;
                            }
                            $dtLocal = $dt->format('Y-m-d H:i:s');
                            $sessionUrl = route('coaching.session', ['booking' => $b->id]);
                            $sessionNum = $sessionNumbers[$b->id] ?? ($index + 1);
                            $sessionLabel = 'Session ' . $sessionNum;
                            $isGoingOn = $isLiveWindow && !$isPast && in_array(strtolower((string) $b->status), ['accepted', 'scheduled'], true);
                        @endphp
                        
                        <div class="glass-panel p-5 sm:p-6 transition duration-300 relative overflow-hidden {{ $isGoingOn ? 'border-emerald-500/40 bg-emerald-950/20 shadow-[0_0_30px_rgba(16,185,129,0.15)]' : ($isPast ? 'opacity-60 bg-zinc-950/40' : '') }}">
                            @if($isGoingOn)
                                <div class="absolute inset-0 bg-emerald-500/5 animate-pulse pointer-events-none"></div>
                            @endif
                            
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-5 relative z-10">
                                <div class="space-y-2 flex-1">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <h3 class="font-bold text-lg text-white">
                                            {{ $sessionLabel }}@if(!empty($b->topic)) <span class="text-gray-500 font-normal mx-1">-</span> {{ $b->topic }}@endif
                                        </h3>
                                        
                                        @php
                                            $s = strtolower((string) $b->status);
                                            if ($isPast) {
                                                $badgeClass = 'bg-zinc-800 text-gray-400 border border-zinc-700';
                                                $badgeText = 'Meeting ended';
                                            } else {
                                                if ($s === 'rejected') {
                                                    $badgeClass = 'bg-red-500/10 text-red-400 border border-red-500/20';
                                                    $badgeText = 'Rejected';
                                                } else if ($s === 'pending') {
                                                    $badgeClass = 'bg-amber-500/10 text-amber-400 border border-amber-500/20';
                                                    $badgeText = 'Pending';
                                                } else if ($s === 'accepted' || $s === 'scheduled') {
                                                    if ($isLiveWindow) {
                                                        $badgeClass = 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 font-extrabold animate-pulse';
                                                        $badgeText = 'On Going';
                                                    } else {
                                                        $badgeClass = 'bg-blue-500/10 text-blue-400 border border-blue-500/20';
                                                        $badgeText = 'Scheduled';
                                                    }
                                                } else {
                                                    $badgeClass = 'bg-zinc-800 text-gray-400 border border-zinc-700';
                                                    $badgeText = 'Meeting ended';
                                                }
                                            }
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-[10px] uppercase tracking-wider font-bold {{ $badgeClass }}">{{ $badgeText }}</span>
                                    </div>

                                    <div class="text-xs text-gray-400 flex items-center gap-2">
                                        <i class="fa-regular fa-clock text-blue-400"></i>
                                        {{ $dt->format('F j, Y') }}, <span class="text-white font-semibold">{{ $dt->format('H:i') }} WIB</span>
                                    </div>


                                    @if($b->notes)
                                        @php
                                            $rawNotes = (string) $b->notes;
                                            $cleanLines = [];
                                            $parts = preg_split('/(?=\[\d{4}-\d{2}-\d{2})|\r?\n/', $rawNotes) ?: [$rawNotes];

                                            foreach ($parts as $part) {
                                                $part = trim($part);
                                                if ($part === '') continue;

                                                $cleanPart = preg_replace('/^\[\d{4}-\d{2}-\d{2}\s+\d{2}:\d{2}:\d{2}\]\s*/', '', $part);
                                                $cleanPart = trim($cleanPart);
                                                if ($cleanPart === '') continue;

                                                $lower = strtolower($cleanPart);
                                                if (
                                                    str_contains($lower, 'meeting selesai') ||
                                                    str_contains($lower, 'session_end') ||
                                                    str_contains($lower, 'session_ended') ||
                                                    str_contains($lower, 'connect_error') ||
                                                    str_contains($lower, 'notallowederror') ||
                                                    str_contains($lower, 'permission denied')
                                                ) {
                                                    continue;
                                                }

                                                $cleanLines[] = $cleanPart;
                                            }

                                            $displayNotes = trim(implode(' ', $cleanLines));
                                        @endphp
                                        @if($displayNotes !== '')
                                            <div class="mt-2.5 bg-zinc-950/70 rounded-xl p-3 border border-white/10 text-xs text-gray-300 shadow-inner flex items-start gap-2">
                                                <span class="font-bold text-gray-400 uppercase text-[10px] tracking-wider bg-white/5 px-2 py-0.5 rounded border border-white/5 shrink-0">Notes</span>
                                                <p class="leading-relaxed text-gray-300">{{ $displayNotes }}</p>
                                            </div>
                                        @endif
                                    @endif


                                    @if(strtolower($b->status) === 'rejected')
                                        <div class="mt-2 text-xs text-red-400 bg-red-500/10 p-3 rounded-xl border border-red-500/20">
                                            <i class="fa-solid fa-triangle-exclamation mr-1.5"></i>
                                            <span class="font-bold">Reason:</span> {{ $b->admin_note ?? 'The admin is unavailable, please reschedule.' }} — Your ticket has been returned.
                                        </div>
                                    @endif
                                </div>

                                <div class="flex items-center gap-3 min-w-[200px] justify-end">
                                    @php
                                        $statusLower = strtolower((string) $b->status);
                                    @endphp
                                    @if($statusLower === 'rejected')
                                        <button type="button" class="py-2.5 px-5 bg-zinc-800 hover:bg-zinc-700 text-white font-bold rounded-xl text-xs transition" onclick="window.location.href='{{ route('coaching.index') }}'">Reschedule</button>
                                    @elseif(in_array($statusLower, ['accepted', 'scheduled'], true))
                                         @php
                                             $gCalStart = $dt->copy()->setTimezone('UTC')->format('Ymd\THis\Z');
                                             $gCalEnd = $dt->copy()->addMinutes($sessionLength)->setTimezone('UTC')->format('Ymd\THis\Z');
                                             $gCalParams = [
                                                 'action' => 'TEMPLATE',
                                                 'text' => '1-on-1 Guitar Coaching Session with Nde',
                                                 'dates' => $gCalStart . '/' . $gCalEnd,
                                                 'details' => "1-on-1 Private Mentorship Session with Nde on Guitarclassbynde.\nStudent: " . (auth()->user()->name ?? 'Student') . "\nJoin Meeting: " . route('coaching.upcoming'),
                                                 'location' => 'Guitarclassbynde Portal / Online Video Session',
                                             ];
                                             $gCalItemUrl = 'https://calendar.google.com/calendar/render?' . http_build_query($gCalParams);
                                         @endphp
                                         <div class="flex items-center gap-2">
                                              @if(! $isPast && ! $isLiveWindow)
                                                  <a href="{{ $gCalItemUrl }}" target="_blank" rel="noopener noreferrer" class="gcal-btn py-2 px-3.5 rounded-xl bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 border border-emerald-500/25 font-bold text-[11px] transition flex items-center gap-2 cursor-pointer shadow-sm">
                                                      <i class="fa-brands fa-google text-xs text-emerald-400"></i>
                                                      <span>Add to Google Calendar</span>
                                                  </a>
                                                  
                                                  @php
                                                      $isWarrantySession = ($b->ticket && $b->ticket->source === 'warranty');
                                                  @endphp

                                                  @if($isWarrantySession)
                                                      <span title="Sessions booked via Warranty Ticket cannot be rescheduled." class="py-2 px-3.5 rounded-xl bg-amber-500/10 text-amber-400 border border-amber-500/25 font-bold text-[11px] flex items-center gap-1.5 cursor-not-allowed shadow-sm">
                                                          <i class="fa-solid fa-shield-halved text-xs text-amber-400"></i>
                                                          <span>Warranty Session (Non-reschedulable)</span>
                                                      </span>
                                                  @elseif(($b->rescheduled_count ?? 0) >= 1)
                                                      <span title="Each coaching session can only be rescheduled once." class="py-2 px-3.5 rounded-xl bg-white/5 text-gray-500 border border-white/10 text-[11px] font-semibold flex items-center gap-1.5 cursor-not-allowed">
                                                          <i class="fa-solid fa-lock text-xs"></i>
                                                          <span>Rescheduled (Max 1x)</span>
                                                      </span>
                                                  @else
                                                      <a href="{{ route('coaching.index', ['reschedule' => $b->id]) }}" class="py-2 px-3.5 rounded-xl bg-amber-500/10 hover:bg-amber-500/20 text-amber-400 border border-amber-500/25 font-bold text-[11px] transition flex items-center gap-1.5 cursor-pointer shadow-sm">
                                                          <i class="fa-solid fa-calendar-days text-xs"></i>
                                                          <span>Reschedule</span>
                                                      </a>
                                                  @endif
                                              @endif

                                              <div class="booking-timer-wrapper flex flex-col items-end gap-2 w-full sm:w-auto" data-booking-time="{{ $dtLocal }}" data-status="{{ $b->status }}" data-href="{{ $sessionUrl }}">
                                                  <span class="countdown px-4 py-2 rounded-xl font-mono text-xs bg-zinc-950/90 text-blue-400 border border-blue-500/20 tracking-widest text-center shadow-inner inline-block">--:--:--</span>
                                                  <a href="{{ $sessionUrl }}" target="_blank" class="start-btn w-full py-2.5 px-5 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-black font-bold rounded-xl text-xs transition shadow-[0_0_20px_rgba(16,185,129,0.3)] flex items-center justify-center gap-2 hidden cursor-pointer no-underline">
                                                      <i class="fa-solid fa-video text-sm animate-pulse"></i>
                                                      <span class="start-label">Join Session</span>
                                                  </a>
                                              </div>
                                         </div>
                                    @else
                                        <span class="px-3 py-1.5 rounded-lg text-xs font-bold text-gray-500 bg-white/5 border border-white/10 uppercase">{{ $b->status }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    </main>
</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function formatDelta(ms) {
                if (ms <= 0) return 'Live Now';
                const total = Math.floor(ms / 1000);
                const days = Math.floor(total / 86400);
                const hours = Math.floor((total % 86400) / 3600);
                const mins = Math.floor((total % 3600) / 60);
                const secs = Math.floor(total % 60);
                
                const dStr = days > 0 ? days + (days === 1 ? ' day ' : ' days ') : '';
                const hStr = String(hours).padStart(2, '0') + ':';
                const mStr = String(mins).padStart(2, '0') + ':';
                const sStr = String(secs).padStart(2, '0');
                
                return dStr + hStr + mStr + sStr;
            }

            function parseLocalDateTime(s) {
                if (!s) return null;
                const iso = s.replace(' ', 'T');
                const dt = new Date(iso);
                if (isNaN(dt.getTime())) return null;
                return dt;
            }

            const boundClicks = new WeakMap();

            function updateCountdowns() {
                const wrappers = document.querySelectorAll('.booking-timer-wrapper');
                const now = new Date();

                wrappers.forEach(wrap => {
                    const dtStr = wrap.dataset.bookingTime || '';
                    const dt = parseLocalDateTime(dtStr);
                    const href = wrap.dataset.href || '';
                    const cd = wrap.querySelector('.countdown');
                    const btn = wrap.querySelector('.start-btn');

                    if (!dt) return;

                    const startWindow = dt.getTime();
                    const endWindow = dt.getTime() + (60 * 60 * 1000); // 1 hour session window

                    if (now.getTime() >= startWindow && now.getTime() <= endWindow) {
                        const parentContainer = wrap.closest('.flex');
                        if (parentContainer) {
                            const gcalBtn = parentContainer.querySelector('.gcal-btn');
                            if (gcalBtn) gcalBtn.style.display = 'none';
                        }
                        // LIVE NOW!

                        if (cd) {
                            cd.textContent = 'LIVE NOW';
                            cd.className = 'countdown px-4 py-2 rounded-xl font-mono text-xs bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 tracking-widest text-center shadow-[0_0_15px_rgba(16,185,129,0.2)] inline-block font-bold animate-pulse';
                        }
                        if (btn) {
                            btn.classList.remove('hidden');
                            btn.classList.add('flex');
                            btn.style.opacity = '1';
                            btn.style.pointerEvents = 'auto';
                            if (href) {
                                btn.setAttribute('href', href);
                            }
                        }
                    } else if (now.getTime() > endWindow) {
                        // EXPIRED / ENDED
                        if (cd) {
                            cd.textContent = 'Session Completed';
                            cd.className = 'countdown px-3 py-1.5 rounded-lg text-xs font-semibold text-gray-500 bg-white/5 border border-white/10 inline-block';
                        }
                        if (btn) {
                            btn.classList.add('hidden');
                        }
                    } else {
                        // UPCOMING COUNTDOWN TICKING!
                        const msUntilStart = startWindow - now.getTime();
                        if (cd) {
                            cd.textContent = formatDelta(msUntilStart);
                            cd.className = 'countdown px-4 py-2 rounded-xl font-mono text-xs bg-zinc-950/80 text-blue-400 border border-blue-500/20 tracking-widest text-center shadow-inner inline-block';
                        }
                        if (btn) {
                            btn.classList.add('hidden');
                        }
                    }
                });
            }

            updateCountdowns();
            setInterval(updateCountdowns, 1000);
        });
    </script>
@endpush




