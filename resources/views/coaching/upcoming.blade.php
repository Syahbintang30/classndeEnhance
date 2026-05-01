@extends('layouts.app')

@push('styles')
    <style>
        /* Make the page container fill the viewport (minus likely header) and use flex layout */
        .upcoming { padding:40px 20px; color:rgba(255,255,255,0.95); display:flex; flex-direction:column; min-height: calc(100vh - 120px); box-sizing:border-box; }
    /* card-style slots */
    .slot { border-radius:10px; margin-bottom:18px; padding:0; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.04); }
    .slot .card-body { padding:18px; display:flex; justify-content:space-between; gap:12px; align-items:flex-start; flex-wrap:wrap; }
    .slot .meta { font-size:14px; opacity:0.9; margin-bottom:6px; }
    .slot .info { flex:1 1 60%; min-width:0 }
    .slot .topic { font-weight:800; font-size:16px; margin-bottom:6px; }
    .slot .label { color:rgba(255,255,255,0.75); font-size:13px; margin-right:6px; }
    .slot .muted { color:rgba(255,255,255,0.7); font-size:14px; }
    .slot hr { border:none; border-top:1px solid rgba(255,255,255,0.03); margin:10px 0 }
    .slot .btn { padding:8px 18px; border-radius:12px; background:#e5e7eb; color:#111827; font-weight:700; text-decoration:none; border: none; transition: transform .12s ease, box-shadow .12s ease, background .12s ease, color .12s ease; }
    .slot .btn:hover { transform: translateY(-3px); box-shadow: 0 8px 22px rgba(0,0,0,0.35); }
    /* countdown badge visible - simplified, professional black & white theme */
    .countdown {
        display: inline-block;
        padding: 8px 12px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 13px;
        letter-spacing: 0.6px;
        background: rgba(255,255,255,0.06);
        color: #ffffff;
        border: 1px solid rgba(255,255,255,0.07);
        min-width: 88px;
        text-align: center;
    }
    /* states keep to monochrome palette but increase contrast when relevant */
    .countdown.soon {
        background: #ffffff;
        color: #111111;
        border-color: rgba(0,0,0,0.06);
    }
    .countdown.live {
        background: #111111;
        color: #ffffff;
        border-color: rgba(255,255,255,0.06);
        box-shadow: 0 8px 20px rgba(0,0,0,0.55);
    }
        .no-appointments { opacity:0.85; }
        .make-another { position:fixed; right:36px; bottom:36px; z-index:60 }
        /* style override for the floating action button to be a blunt rounded rectangle with hover effect */
        .make-another .btn {
            padding:12px 22px; border-radius:12px; border:2px solid rgba(255,255,255,0.9); background:transparent; color:#fff; font-weight:800; text-decoration:none; display:inline-block; transition: transform .12s ease, box-shadow .12s ease, background .12s ease, color .12s ease;
        }
        .make-another .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.6);
            background: #ffffff;
            color: #111827;
        }
    .rejected-note { margin-top:6px; }
    .start-wrap { display:flex; flex-direction:column; align-items:flex-end; gap:8px; flex:0 0 160px; }
    .start-wrap .countdown { margin-left:0; }
    /* status badges - minimal monochrome pills */
    .status-badge {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 12px;
        margin-left: 8px;
        letter-spacing: 0.4px;
        border: 1px solid rgba(255,255,255,0.06);
        background: rgba(255,255,255,0.04);
        color: #ffffff;
    }
    /* Rejected: subtle dark outline with muted white text */
    .status-badge.rejected {
        background: transparent;
        color: rgba(255,255,255,0.9);
        border-color: rgba(255,255,255,0.12);
    }
    /* Pending: light, outlined pill */
    .status-badge.pending {
        background: rgba(255,255,255,0.06);
        color: #ffffff;
        border-style: dashed;
        border-color: rgba(255,255,255,0.08);
    }
    /* Scheduled: high-contrast white pill (on dark background) */
    .status-badge.scheduled {
        background: rgba(255,255,255,0.08);
        color: #f3f4f6;
        border-color: rgba(255,255,255,0.18);
    }
    .status-badge.on-going {
        background: #facc15;
        color: #111111;
        border-color: rgba(0,0,0,0.12);
        font-weight: 800;
    }
    /* Finished: red badge for clear ended state */
    .status-badge.finished {
        background: rgba(185, 28, 28, 0.2);
        color: #fecaca;
        border-color: rgba(248, 113, 113, 0.45);
        font-weight: 700;
    }
    /* history (past) booking box: darker, lower-contrast */
    .slot.history { background: rgba(0,0,0,0.45) !important; border-color: rgba(255,255,255,0.03) !important; }
    .slot.history .topic { color: rgba(255,255,255,0.95); }
    .slot.history .muted, .slot.history .meta { color: rgba(255,255,255,0.75); }
    .slot.history .status-badge { background: rgba(255,255,255,0.04); color: rgba(255,255,255,0.9); border-color: rgba(255,255,255,0.06); }
    .btn-reschedule { background:transparent;color:#fff;border:1px solid rgba(255,255,255,0.08);padding:8px 12px;border-radius:8px }

    .notes-wrap { margin-top: 6px; }
    .notes-label { color: rgba(255,255,255,0.82); font-weight: 700; margin-right: 6px; }
    .meeting-finished-box {
        display: inline-block;
        margin-top: 8px;
        background: rgba(185, 28, 28, 0.2);
        color: #fecaca;
        border: 1px solid rgba(248, 113, 113, 0.45);
        border-radius: 8px;
        padding: 6px 10px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.2px;
    }
    
        :root[data-theme="light"] .upcoming {
            color: #0f172a;
        }
    
        :root[data-theme="light"] .slot {
            background: #ffffff;
            border-color: rgba(15, 23, 42, 0.08) !important;
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.05);
        }
    
        :root[data-theme="light"] .slot .topic,
        :root[data-theme="light"] .slot .muted,
        :root[data-theme="light"] .slot .meta,
        :root[data-theme="light"] .slot .label,
        :root[data-theme="light"] .notes-label,
        :root[data-theme="light"] .no-appointments {
            color: #334155;
        }
    
        :root[data-theme="light"] .slot hr {
            border-top-color: rgba(15, 23, 42, 0.08);
        }
    
        :root[data-theme="light"] .slot .btn,
        :root[data-theme="light"] .btn-reschedule,
        :root[data-theme="light"] .make-another .btn {
            background: #ffffff;
            color: #0f172a;
            border-color: rgba(15, 23, 42, 0.12);
        }
    
        :root[data-theme="light"] .countdown {
            background: #ffffff;
            color: #0f172a;
            border-color: rgba(15, 23, 42, 0.12);
        }
    
        :root[data-theme="light"] .countdown.soon,
        :root[data-theme="light"] .countdown.live,
        :root[data-theme="light"] .status-badge,
        :root[data-theme="light"] .status-badge.pending,
        :root[data-theme="light"] .status-badge.scheduled,
        :root[data-theme="light"] .status-badge.rejected,
        :root[data-theme="light"] .status-badge.finished,
        :root[data-theme="light"] .meeting-finished-box,
        :root[data-theme="light"] .slot.history {
            background: #ffffff;
            color: #0f172a;
            border-color: rgba(15, 23, 42, 0.12) !important;
            box-shadow: none;
        }

    /* Responsive adjustments */
    @media (max-width: 992px) {
        .upcoming { padding:28px 14px; min-height: calc(100vh - 140px); }
        .slot .info { flex:1 1:100%; }
        .start-wrap { flex: 0 0 100%; align-items:flex-start; }
        .slot .card-body { gap:10px; }
        .make-another { right:18px; bottom:18px }
    }
    @media (max-width: 576px) {
        .slot .card-body { flex-direction:column; align-items:stretch; }
        .slot .info { width:100%; }
        .start-wrap { width:100%; display:flex; flex-direction:row; justify-content:space-between; align-items:center }
        .start-wrap .countdown { margin-top:0 }
        .slot .topic { font-size:15px }
    }
    </style>
@endpush

@section('content')
    <div class="upcoming">
        <h2>Upcoming Appointment</h2>

        {{-- User tickets summary --}}
        @php
            // $tickets is provided by controller (collection)
            $totalTickets = isset($tickets) && is_iterable($tickets) ? collect($tickets)->count() : 0;
            $availableTickets = 0;
            if (isset($tickets) && is_iterable($tickets)) {
                $availableTickets = collect($tickets)->where('is_used', false)->count();
            }
        @endphp
        <div style="margin:14px 0 20px 0;padding:12px;border-radius:10px;background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.03);">
            <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap">
                <div>
                    <strong>My Tickets</strong>
                    <div style="font-size:13px;opacity:0.9">{{ $availableTickets }} available of {{ $totalTickets }} total</div>
                </div>
                <div style="font-size:13px;opacity:0.95">
                    @if($availableTickets > 0)
                        <a href="{{ route('coaching.index') }}" class="btn" style="padding:8px 12px;border-radius:10px;">Book a Session</a>
                    @else
                        <a href="{{ route('coaching.checkout') }}" class="btn" style="padding:8px 12px;border-radius:10px;">Buy Ticket</a>
                    @endif
                </div>
            </div>

            {{-- ticket badges removed per design request; summary above retained --}}
        </div>

        @if($bookings->isEmpty() && (empty($caching) || $caching->isEmpty()))
            <p class="no-appointments">You have no upcoming appointments.</p>
        @else
            @php
                // Ensure bookings are ordered oldest -> newest so Session 1 is the earliest
                $sortedBookings = $bookings->sortBy('booking_time')->values();
            @endphp
            @foreach($sortedBookings as $index => $b)
                @php
                    $dt = \Carbon\Carbon::parse($b->booking_time);
                    $now = \Carbon\Carbon::now();
                    // Treat booking as finished only when the session end time has passed.
                    // Session length is configurable via coaching.session_length_minutes (default 60).
                    $sessionLength = config('coaching.session_length_minutes', 60);
                    try {
                        $sessionEnd = $dt->copy()->addMinutes($sessionLength);
                        $isPast = $sessionEnd->lt($now);
                        $isLiveWindow = $now->gte($dt->copy()->subMinutes(10)) && $now->lte($sessionEnd);
                    } catch (\Throwable $e) {
                        // Fallback: if any error, do not mark as past to avoid prematurely showing 'selesai'
                        $isPast = false;
                        $isLiveWindow = false;
                    }
                    $dtLocal = $dt->format('Y-m-d H:i:s');
                    $sessionUrl = route('coaching.session', ['booking' => $b->id]);
                @endphp
                <div class="slot{{ $isPast ? ' history' : '' }}">
                    <div class="card-body">
                        <div class="info">
                            {{-- show session number if available, otherwise use sequential index --}}
                            @php
                                // Label sessions sequentially by chronological order (oldest = Session 1)
                                $sessionLabel = 'Session ' . ($index + 1);
                            @endphp
                            <div class="topic">{{ $sessionLabel }}@if(!empty($b->topic)) - {{ $b->topic }}@endif
                                    @php
                                        $s = strtolower($b->status);
                                        // Runtime-state label aligned with admin dashboard semantics.
                                        if ($isPast) {
                                            $badgeClass = 'finished';
                                            $badgeText = 'Meeting selesai';
                                        } else {
                                            if ($s === 'rejected') {
                                                $badgeClass = 'rejected';
                                                $badgeText = 'Rejected';
                                            } else if ($s === 'pending') {
                                                $badgeClass = 'pending';
                                                $badgeText = 'Pending';
                                            } else if ($s === 'accepted' || $s === 'scheduled') {
                                                if ($isLiveWindow) {
                                                    $badgeClass = 'on-going';
                                                    $badgeText = 'On Going';
                                                } else {
                                                    $badgeClass = 'scheduled';
                                                    $badgeText = 'Scheduled';
                                                }
                                            } else {
                                                $badgeClass = 'finished';
                                                $badgeText = 'Meeting selesai';
                                            }
                                        }
                                    @endphp
                                    <span class="status-badge {{ $badgeClass }}">{{ $badgeText }}</span>
                            </div>

                            <div class="muted"><span class="label">Jadwal:</span> {{ $dt->translatedFormat('d F Y') }}, {{ $dt->format('H:i') }} WIB</div>
                            @if($b->notes)
                                @php
                                    $rawNotes = (string) $b->notes;
                                    $noteLines = preg_split('/\r?\n/', $rawNotes) ?: [];
                                    $cleanLines = [];
                                    $hasMeetingFinishedEvent = false;

                                    foreach ($noteLines as $line) {
                                        $line = trim($line);
                                        if ($line === '') {
                                            continue;
                                        }

                                        $lower = strtolower($line);
                                        if (str_contains($lower, 'session_end_clicked') || str_contains($lower, 'session_ended_by_admin')) {
                                            $hasMeetingFinishedEvent = true;
                                            continue;
                                        }

                                        // Hide connect-error telemetry noise from user-facing notes.
                                        if (str_contains($lower, 'connect_error') || str_contains($lower, 'notallowederror') || str_contains($lower, 'permission denied')) {
                                            continue;
                                        }

                                        $cleanLines[] = $line;
                                    }

                                    if ($hasMeetingFinishedEvent) {
                                        // Render this as a separate red status box below notes.
                                    }

                                    $displayNotes = trim(implode(' ', $cleanLines));
                                @endphp
                                @if($displayNotes !== '' || $hasMeetingFinishedEvent)
                                    <div class="notes-wrap">
                                        @if($displayNotes !== '')
                                            <div class="meta"><span class="notes-label">Notes:</span>{{ $displayNotes }}</div>
                                        @endif
                                        @if($hasMeetingFinishedEvent)
                                            <div class="meeting-finished-box">Meeting selesai</div>
                                        @endif
                                    </div>
                                @endif
                            @endif
                            {{-- feedback moved into booking->notes; notes displayed above --}}
                            @if(strtolower($b->status) === 'rejected')
                                <div class="muted" style="margin-top:8px">Alasan: {{ $b->admin_note ?? 'Admin sibuk, mohon reschedule' }} — Tiket Anda telah dikembalikan.</div>
                            @endif
                        </div>

                        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:10px;min-width:160px">
                            @if(strtolower($b->status) === 'rejected')
                                <form method="GET" action="{{ route('coaching.index') }}">
                                    <button type="button" class="btn-reschedule" onclick="window.location.href='{{ route('coaching.index') }}'">Reschedule</button>
                                </form>
                            @else
                                <div class="start-wrap">
                                    <button type="button" class="btn start-btn" disabled
                                        data-booking-time="{{ $dtLocal }}"
                                        data-status="{{ $b->status }}"
                                        data-href="{{ $sessionUrl }}"
                                        title="Tombol akan aktif 10 menit sebelum sesi dimulai"
                                    >
                                        <span class="start-label">Join Session</span>
                                    </button>
                                    <span class="countdown"></span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- No pending caching bookings UI; bookings are the single source of truth. --}}
        @endif

        <div class="make-another">
            @if($hasTicket)
                <a href="{{ route('coaching.index') }}" class="btn">MAKE ANOTHER APPOINTMENT</a>
            @else
                <a href="{{ route('coaching.checkout') }}" class="btn">MAKE ANOTHER APPOINTMENT</a>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            function formatDelta(ms) {
                // ms is milliseconds until target (can be negative)
                if (ms <= 0) return 'Dimulai';
                const total = Math.floor(ms / 1000);
                const days = Math.floor(total / 86400);
                const hours = Math.floor((total % 86400) / 3600);
                const mins = Math.floor((total % 3600) / 60);
                const parts = [];
                if (days) parts.push(days + ' hari');
                if (hours) parts.push(hours + ' jam');
                parts.push(mins + ' menit');
                return parts.join(' ');
            }

            function handleNoteForm(form) {
                form.addEventListener('submit', async function (e) {
                    e.preventDefault();
                    const submitBtn = form.querySelector('button[type="submit"]');
                    const input = form.querySelector('input[name="note"]');
                    if (!input) return;
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const url = form.getAttribute('action');
                    const payload = new URLSearchParams();
                    payload.append('note', input.value);
                    try {
                        submitBtn.disabled = true;
                        const resp = await fetch(url, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json', 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: payload.toString(),
                            credentials: 'same-origin'
                        });
                        if (resp.ok) {
                            const json = await resp.json().catch(() => null);
                            // show small saved indicator
                            let saved = form.querySelector('.saved-indicator');
                            if (!saved) {
                                saved = document.createElement('span');
                                saved.className = 'saved-indicator';
                                saved.style.marginLeft = '8px';
                                saved.style.opacity = '0.9';
                                saved.style.fontSize = '13px';
                                saved.textContent = 'Saved';
                                form.appendChild(saved);
                            }
                            saved.style.color = '#6EE7B7';
                            setTimeout(() => { if (saved) saved.remove(); }, 2500);
                        } else {
                            alert('Failed to save note');
                        }
                    } catch (err) {
                        console.error(err);
                        alert('Failed to save note');
                    } finally {
                        submitBtn.disabled = false;
                    }
                });
            }

            document.querySelectorAll('form[action*="/coaching/"]').forEach(f => {
                if (f.getAttribute('action').includes('/coaching/') && f.querySelector('input[name="note"]')) handleNoteForm(f);
            });

            // START button enablement + countdown: show "Opens in Xm Ys" and enable when admin accepted and current time is within window
            function parseLocalDateTime(s) {
                // Accept 'YYYY-MM-DD HH:mm:ss' and convert to 'YYYY-MM-DDTHH:mm:ss' which is parsed as local in modern browsers
                if (!s) return null;
                const iso = s.replace(' ', 'T');
                const dt = new Date(iso);
                if (isNaN(dt.getTime())) return null;
                return dt;
            }

            // Keep track of whether we've bound a click handler to avoid duplicates
            const boundClicks = new WeakMap();

            function updateStartButtons() {
                const buttons = document.querySelectorAll('.start-btn');
                const now = new Date();
                buttons.forEach(btn => {
                    const status = (btn.dataset.status || '').toLowerCase();
                    const dtStr = btn.dataset.bookingTime || '';
                    const dt = parseLocalDateTime(dtStr);
                    // default: disabled
                    let enabled = false;

                    if ((status === 'accepted' || status === 'scheduled') && dt) {
                        // enable only within tight window: from 10 minutes before start until 60 minutes after start
                        const startWindow = new Date(dt.getTime() - (10 * 60 * 1000));
                        const endWindow = new Date(dt.getTime() + (60 * 60 * 1000));
                        if (now >= startWindow && now <= endWindow) enabled = true;
                    }

                    // enable / disable join button
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

                    // update countdown text element next to the button
                    const slot = btn.closest('.slot');
                    const cd = slot ? slot.querySelector('.countdown') : null;
                    if (cd && dt) {
                        const delta = dt.getTime() - Date.now();
                        cd.textContent = formatDelta(delta);
                        // add small class when soon (less than 1 day) to highlight
                        if (delta <= (24 * 60 * 60 * 1000) && delta > 0) cd.classList.add('soon'); else cd.classList.remove('soon');
                        if (delta <= 0 && delta >= -(60 * 60 * 1000)) cd.classList.add('live'); else cd.classList.remove('live');
                    }

                    // if rejected, show admin_note (already printed in markup) and ensure reschedule button is visible (handled by server side markup)
                });
            }

            function startBtnClickHandler(e) {
                const btn = e.currentTarget;
                const href = btn.dataset.href;
                if (href) window.location.href = href;
            }

            // initial run + periodic polls:
            // - run the full check immediately
            // - run a light-weight per-second update to refresh countdown labels and enable when window opens
            // - keep a 15s poll to pick up admin status changes
            updateStartButtons();
            const secondTicker = setInterval(updateStartButtons, 1000);
            const pollTicker = setInterval(updateStartButtons, 15000);
        });
    </script>
@endpush
