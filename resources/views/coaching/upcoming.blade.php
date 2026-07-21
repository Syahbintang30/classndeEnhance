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
                <i class="fa-solid fa-user-ninja"></i> Personal Coaching Hub
            </div>
            <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl tracking-wide text-white mb-2 uppercase">
                Coaching <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-400 via-indigo-300 to-white">Dashboard</span>
            </h1>
            <p class="text-gray-400 text-sm max-w-xl">Manage your active passes and upcoming live 1-on-1 video sessions to accelerate your playing.</p>
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
                <i class="fa-solid fa-ticket text-blue-400"></i> Your Passes
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
                                <h3 class="font-bold text-lg text-white">Coaching Tickets</h3>
                                <p class="text-xs text-gray-400">Regular 1-on-1 Live Sessions</p>
                            </div>
                        </div>
                        <div class="mt-4 flex items-baseline gap-2">
                            <span class="font-display text-5xl text-blue-400">{{ $availableTickets }}</span>
                            <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider">available of {{ $totalTickets }} total</span>
                        </div>
                    </div>

                    <div class="w-full sm:w-auto flex-shrink-0">
                        @if($availableTickets > 0)
                            <a href="{{ route('coaching.index') }}" class="w-full sm:w-auto py-3.5 px-6 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-sm rounded-xl text-center transition shadow-lg hover:shadow-blue-600/30 flex items-center justify-center gap-2">
                                <span>Book Session</span> <i class="fa-solid fa-arrow-right text-xs"></i>
                            </a>
                        @else
                            <a href="{{ route('coaching.checkout') }}" class="w-full sm:w-auto py-3.5 px-6 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-sm rounded-xl text-center transition shadow-lg hover:shadow-blue-600/30 flex items-center justify-center gap-2">
                                <span>Buy Ticket</span> <i class="fa-solid fa-arrow-right text-xs"></i>
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
                                No Warranty Tickets
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION 2: UPCOMING APPOINTMENTS -->
        <section>
            <h2 class="font-display text-2xl tracking-wide text-white mb-5 flex items-center gap-3">
                <i class="fa-regular fa-calendar-check text-blue-400"></i> Scheduled Appointments
            </h2>

            @if($bookings->isEmpty() && (empty($caching) || $caching->isEmpty()))
                <div class="glass-panel p-12 text-center flex flex-col items-center justify-center">
                    <div class="w-20 h-20 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center text-3xl mb-5 shadow-[0_0_30px_rgba(59,130,246,0.15)]">
                        <i class="fa-regular fa-calendar-plus"></i>
                    </div>
                    <h3 class="font-display text-3xl text-white tracking-wide mb-2">Ready to start your session?</h3>
                    <p class="text-gray-400 text-sm max-w-md mb-6">You don't have any upcoming appointments scheduled. Book a session with your available tickets to level up your guitar skills.</p>
                    <a href="{{ $availableTickets > 0 ? route('coaching.index') : route('coaching.checkout') }}" class="py-3.5 px-8 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-sm rounded-full transition shadow-lg flex items-center gap-2">
                        <i class="fa-solid fa-calendar-day"></i> Schedule Session Now
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
                                        {{ $dt->translatedFormat('d F Y') }}, <span class="text-white font-semibold">{{ $dt->format('H:i') }} WIB</span>
                                    </div>

                                    @if($b->notes)
                                        @php
                                            $rawNotes = (string) $b->notes;
                                            $noteLines = preg_split('/\r?\n/', $rawNotes) ?: [];
                                            $cleanLines = [];
                                            $hasMeetingFinishedEvent = false;

                                            foreach ($noteLines as $line) {
                                                $line = trim($line);
                                                if ($line === '') continue;

                                                $lower = strtolower($line);
                                                if (str_contains($lower, 'session_end_clicked') || str_contains($lower, 'session_ended_by_admin')) {
                                                    $hasMeetingFinishedEvent = true;
                                                    continue;
                                                }
                                                if (str_contains($lower, 'connect_error') || str_contains($lower, 'notallowederror') || str_contains($lower, 'permission denied')) {
                                                    continue;
                                                }
                                                $cleanLines[] = $line;
                                            }

                                            $displayNotes = trim(implode(' ', $cleanLines));
                                            if ($hasMeetingFinishedEvent) {
                                                $notice = 'Meeting dipaksa selesai oleh admin karena kendala koneksi. Anda mendapatkan warranty tickets untuk melanjutkan sesi.';
                                                $displayNotes = trim($displayNotes === '' ? $notice : ($displayNotes . ' ' . $notice));
                                            }
                                        @endphp
                                        @if($displayNotes !== '' || $hasMeetingFinishedEvent)
                                            <div class="mt-2 bg-zinc-950/60 rounded-xl p-3 border border-white/5 text-xs text-gray-400">
                                                <span class="font-bold text-gray-500 mr-2">Notes:</span>{{ $displayNotes }}
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

                                <div class="flex items-center gap-3 min-w-[180px] justify-end">
                                    @php
                                        $statusLower = strtolower((string) $b->status);
                                        $isEndedStatus = in_array($statusLower, ['ended', 'finished', 'completed'], true);
                                    @endphp
                                    @if($statusLower === 'rejected')
                                        <button type="button" class="py-2.5 px-5 bg-zinc-800 hover:bg-zinc-700 text-white font-bold rounded-xl text-xs transition" onclick="window.location.href='{{ route('coaching.index') }}'">Reschedule</button>
                                    @else
                                        @if($isLiveWindow && !$isPast && !$isEndedStatus && in_array($statusLower, ['accepted', 'scheduled'], true))
                                            <button type="button" class="start-btn w-full py-3 px-6 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-black font-bold rounded-xl text-xs transition shadow-[0_0_20px_rgba(16,185,129,0.3)] flex items-center justify-center gap-2" disabled
                                                data-booking-time="{{ $dtLocal }}"
                                                data-status="{{ $b->status }}"
                                                data-href="{{ $sessionUrl }}"
                                                title="Join is available only when the session is live">
                                                <i class="fa-solid fa-video text-base animate-pulse"></i>
                                                <span class="start-label">Join Session</span>
                                            </button>
                                        @else
                                            <span class="countdown px-4 py-2 rounded-xl font-mono text-xs bg-zinc-950/80 text-gray-300 border border-white/5 tracking-widest text-center inline-block"></span>
                                        @endif
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
                
                const dStr = days > 0 ? String(days).padStart(2, '0') + 'd ' : '';
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

            function updateStartButtons() {
                const buttons = document.querySelectorAll('.start-btn');
                const now = new Date();
                buttons.forEach(btn => {
                    const status = (btn.dataset.status || '').toLowerCase();
                    const dtStr = btn.dataset.bookingTime || '';
                    const dt = parseLocalDateTime(dtStr);
                    let enabled = false;

                    let endWindow = null;
                    if ((status === 'accepted' || status === 'scheduled') && dt) {
                        const startWindow = new Date(dt.getTime());
                        endWindow = new Date(dt.getTime() + (60 * 60 * 1000));
                        if (now >= startWindow && now <= endWindow) enabled = true;
                    }

                    if (enabled) {
                        btn.disabled = false;
                        btn.setAttribute('aria-disabled', 'false');
                        btn.style.opacity = '';
                        if (!boundClicks.has(btn)) {
                            btn.addEventListener('click', startBtnClickHandler);
                            boundClicks.set(btn, true);
                        }
                    } else {
                        btn.disabled = true;
                        btn.setAttribute('aria-disabled', 'true');
                        btn.style.opacity = '0.6';
                    }

                    const slot = btn.closest('.glass-panel');
                    const cd = slot ? slot.querySelector('.countdown') : null;
                    if (cd && dt) {
                        if (endWindow && now > endWindow) {
                            cd.textContent = '00:00:00';
                        } else {
                            const delta = dt.getTime() - Date.now();
                            cd.textContent = formatDelta(delta);
                        }
                    }
                });
            }

            function startBtnClickHandler(e) {
                const btn = e.currentTarget;
                const href = btn.dataset.href;
                if (href) window.location.href = href;
            }

            updateStartButtons();
            setInterval(updateStartButtons, 1000);
        });
    </script>
@endpush
