@extends('layouts.app')

@push('styles')
    <style>
        /* Make the page container fill the viewport (minus likely header) and use flex layout */
        .upcoming { padding:40px 20px; color:rgba(255,255,255,0.95); display:flex; flex-direction:column; min-height: calc(100vh - 120px); box-sizing:border-box; background: linear-gradient(180deg, #0b0b0b 0%, #050505 100%); }
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
        background: rgba(255,255,255,0.06);
        color: #ffffff;
        border-color: rgba(255,255,255,0.07);
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
    /* status badges - rebuilt for clear contrast in both dark & light mode */
    .status-badge {
        display: inline-block;
        padding: 6px 11px;
        border-radius: 999px;
        font-weight: 800;
        font-size: 11px;
        text-transform: uppercase;
        margin-left: 8px;
        letter-spacing: 0.35px;
        border: 1px solid transparent;
        vertical-align: middle;
        line-height: 1.1;
        white-space: nowrap;
    }
    .status-badge.pending {
        background: rgba(251, 191, 36, 0.14);
        color: #fbbf24;
        border-color: rgba(251, 191, 36, 0.45);
    }
    .status-badge.scheduled {
        background: rgba(96, 165, 250, 0.14);
        color: #93c5fd;
        border-color: rgba(96, 165, 250, 0.45);
    }
    .status-badge.on-going {
        background: rgba(52, 211, 153, 0.16);
        color: #6ee7b7;
        border-color: rgba(52, 211, 153, 0.48);
    }
    .status-badge.finished {
        background: rgba(148, 163, 184, 0.16);
        color: #cbd5e1;
        border-color: rgba(148, 163, 184, 0.45);
    }
    .status-badge.rejected {
        background: rgba(251, 113, 133, 0.14);
        color: #fda4af;
        border-color: rgba(251, 113, 133, 0.5);
    }
    .btn-reschedule { background:transparent;color:#fff;border:1px solid rgba(255,255,255,0.08);padding:8px 12px;border-radius:8px }

    .notes-wrap { margin-top: 6px; }
    .notes-label { color: rgba(255,255,255,0.82); font-weight: 700; margin-right: 6px; }
    .meeting-finished-box {
        display: inline-block;
        margin-top: 8px;
        background: #ef4444;
        color: #ffffff;
        border: 1px solid #ef4444;
        border-radius: 8px;
        padding: 6px 10px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.2px;
    }
    
        :root[data-theme="light"] .upcoming {
            color: #0f172a;
            background: #f6f7fb;
        }
    
        :root[data-theme="light"] .slot {
            background: #ffffff !important;
            border: 1px solid rgba(15, 23, 42, 0.06) !important;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
        }
    
        :root[data-theme="light"] .upcoming > div[style*="rgba(255,255,255,0.02)"] {
            background: #ffffff !important;
            border-color: rgba(15, 23, 42, 0.1) !important;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.06);
        }

        :root[data-theme="light"] .upcoming > div[style*="rgba(255,255,255,0.02)"] strong,
        :root[data-theme="light"] .upcoming > div[style*="rgba(255,255,255,0.02)"] div {
            color: #0f172a;
        }

        :root[data-theme="light"] .upcoming h2,
        :root[data-theme="light"] .upcoming strong,
        :root[data-theme="light"] .upcoming .topic,
        :root[data-theme="light"] .upcoming .countdown,
        :root[data-theme="light"] .upcoming .meeting-finished-box {
            color: #0f172a;
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
    
        :root[data-theme="light"] .slot .card-body,
        :root[data-theme="light"] .slot.history .card-body {
            background: #ffffff;
        }

        :root[data-theme="light"] .slot .btn,
        :root[data-theme="light"] .btn-reschedule,
        :root[data-theme="light"] .make-another .btn,
        :root[data-theme="light"] .upcoming .btn {
            background: #0f172a;
            color: #ffffff;
            border-color: #0f172a;
        }

        :root[data-theme="light"] .slot .btn:hover,
        :root[data-theme="light"] .btn-reschedule:hover,
        :root[data-theme="light"] .make-another .btn:hover,
        :root[data-theme="light"] .upcoming .btn:hover {
            background: #1e293b;
            color: #ffffff;
            box-shadow: 0 12px 26px rgba(15, 23, 42, 0.2);
        }
    
    
        :root[data-theme="light"] .countdown {
            background: #ffffff;
            color: #0f172a;
            border-color: rgba(15, 23, 42, 0.12);
        }
    
        :root[data-theme="light"] .countdown.soon,
        :root[data-theme="light"] .countdown.live {
            background: #ffffff !important;
            color: #0f172a;
            border-color: rgba(15, 23, 42, 0.06) !important;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04) !important;
        }

        :root[data-theme="light"] .make-another .btn {
            background: #0f172a;
            color: #ffffff;
            border-color: #0f172a;
        }

        :root[data-theme="light"] .make-another .btn:hover {
            background: #ffffff;
            color: #0f172a;
        }

        :root[data-theme="light"] .status-badge.on-going {
            background: #dcfce7 !important;
            color: #166534 !important;
            border-color: #86efac !important;
        }

        :root[data-theme="light"] .status-badge.finished {
            background: #e2e8f0 !important;
            color: #334155 !important;
            border-color: #cbd5e1 !important;
        }


        :root[data-theme="light"] .meeting-finished-box {
            background: #ef4444;
            color: #ffffff;
            border-color: #ef4444 !important;
        }

        :root[data-theme="light"] .status-badge.rejected,
        :root[data-theme="light"] .status-badge.pending,
        :root[data-theme="light"] .status-badge.scheduled {
            background: #f8fafc !important;
            color: #0f172a !important;
            border-color: #cbd5e1 !important;
        }

        :root[data-theme="light"] .status-badge.pending {
            background: #fef3c7 !important;
            color: #92400e !important;
            border-color: #fcd34d !important;
        }

        :root[data-theme="light"] .status-badge.scheduled {
            background: #dbeafe !important;
            color: #1e3a8a !important;
            border-color: #93c5fd !important;
        }

        :root[data-theme="light"] .status-badge.rejected {
            background: #ffe4e6 !important;
            color: #9f1239 !important;
            border-color: #fda4af !important;
        }

        :root[data-theme="light"] .countdown.soon {
            background: #ffffff;
            color: #0f172a;
            border-color: rgba(15, 23, 42, 0.12);
        }

        :root[data-theme="light"] .countdown.live {
            background: rgba(15, 23, 42, 0.06);
            color: #0f172a;
            border-color: rgba(15, 23, 42, 0.08);
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

    /* Hide global navbar and show custom LMS navbar */
    body > nav { display: none; }

    /* Custom LMS Navbar */
    .lms-navbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 80px;
        background: linear-gradient(180deg, #111 0%, #0a0a0a 100%);
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        padding: 0 20px;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .lms-navbar-left {
        display: flex;
        align-items: center;
        width: 280px;
    }

    .lms-home-link {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #e0e0e0;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .lms-home-link:hover {
        color: #fff;
    }

    .lms-navbar-right {
        display: flex;
        align-items: center;
        gap: 32px;
    }

    .lms-nav-link {
        color: #a0a0a0;
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .lms-nav-link:hover {
        color: #fff;
    }

    .lms-nav-link.active {
        color: #fff;
        font-weight: 600;
    }

    .lms-admin-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border-radius: 10px;
        background: #0f172a;
        color: #ffffff;
        text-decoration: none;
        font-weight: 700;
        font-size: 13px;
        border: 1px solid rgba(15, 23, 42, 0.2);
        transition: transform .12s ease, box-shadow .12s ease, background .12s ease, color .12s ease;
    }

    .lms-admin-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.18);
        background: #111827;
        color: #ffffff;
    }

    :root[data-theme="light"] .lms-navbar {
        background: linear-gradient(180deg, #ffffff 0%, #f4f5f7 100%);
        border-bottom-color: rgba(15, 23, 42, 0.08);
    }

    :root[data-theme="light"] .lms-home-link,
    :root[data-theme="light"] .lms-nav-link {
        color: #334155;
    }

    :root[data-theme="light"] .lms-home-link:hover,
    :root[data-theme="light"] .lms-nav-link:hover,
    :root[data-theme="light"] .lms-nav-link.active {
        color: #0f172a;
    }

    :root[data-theme="light"] .lms-admin-btn {
        background: #0f172a;
        color: #ffffff;
        border-color: rgba(15, 23, 42, 0.2);
    }

    :root[data-theme="light"] .lms-admin-btn:hover {
        background: #111827;
        color: #ffffff;
    }
    </style>
@endpush

@section('content')
<!-- Custom LMS Navbar -->
<nav class="lms-navbar">
    <div class="lms-navbar-left">
        <a href="{{ route('lms.dashboard') }}" class="lms-home-link">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            Home
        </a>
    </div>
    
    <div class="lms-navbar-right">
        <a href="{{ route('lms.entry') }}" class="lms-nav-link @if(request()->routeIs('kelas.show') || request()->routeIs('lms.entry')) active @endif">Lessons</a>
        <a href="{{ route('coaching.upcoming') }}" class="lms-nav-link @if(request()->routeIs('coaching.*')) active @endif">Coaching</a>
        @php
            $user = auth()->user();
            $isAdminPanel = false;
            try {
                $isAdminPanel = $user && (\Illuminate\Support\Facades\Gate::allows('admin') || ($user->is_superadmin ?? false));
            } catch (\Throwable $e) {
                $isAdminPanel = false;
            }
        @endphp
        @if($user && $user->hasLmsAccess() && $user->hasIntermediateAccess())
            <a href="{{ route('song.tutorial.index') }}" class="lms-nav-link @if(request()->routeIs('song.tutorial.*')) active @endif">Song Tutorial</a>
        @endif
        @if($isAdminPanel)
            <a href="{{ route('admin.dashboard') }}" class="lms-admin-btn">Admin Panel</a>
        @endif
    </div>
</nav>

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

        {{-- Warranty tickets summary --}}
        @php
            $totalWarrantyTickets = isset($warrantyTickets) && is_iterable($warrantyTickets) ? collect($warrantyTickets)->count() : 0;
            $availableWarrantyTickets = 0;
            if (isset($warrantyTickets) && is_iterable($warrantyTickets)) {
                $availableWarrantyTickets = collect($warrantyTickets)->where('status', 'available')->count();
            }
        @endphp
        <div style="margin:0 0 20px 0;padding:12px;border-radius:10px;background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.03);">
            <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap">
                <div>
                    <strong>Warranty Tickets</strong>
                    <div style="font-size:13px;opacity:0.9">{{ $availableWarrantyTickets }} available of {{ $totalWarrantyTickets }} total</div>
                </div>
                @php
                    $firstAvailableWarranty = isset($warrantyTickets) && is_iterable($warrantyTickets)
                        ? collect($warrantyTickets)->firstWhere('status', 'available')
                        : null;
                @endphp
                @if($firstAvailableWarranty)
                    <div style="font-size:13px;opacity:0.95">
                        <a href="{{ route('coaching.index', ['warranty_ticket' => $firstAvailableWarranty->id]) }}" class="btn" style="padding:8px 12px;border-radius:10px;">Apply Ticket</a>
                    </div>
                @endif
            </div>
            @if(isset($warrantyTickets) && is_iterable($warrantyTickets) && collect($warrantyTickets)->isNotEmpty())
                <div style="margin-top:10px;display:flex;flex-direction:column;gap:6px;">
                    @foreach(collect($warrantyTickets)->take(5) as $wt)
                        <div style="display:flex;justify-content:space-between;gap:12px;font-size:13px;opacity:0.9;">
                            <div>Warranty {{ $wt->warranty_minutes !== null ? $wt->warranty_minutes . ' min' : '-' }}</div>
                            <div>{{ $wt->issued_at ? $wt->issued_at->format('d M Y') : ($wt->created_at ? $wt->created_at->format('d M Y') : '-') }}</div>
                        </div>
                    @endforeach
                </div>
            @endif
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
                    $sessionLength = (int) ($b->session_duration_minutes ?? config('coaching.session_length_minutes', 60));
                    try {
                        $sessionEnd = $dt->copy()->addMinutes($sessionLength);
                        $isPast = $sessionEnd->lt($now);
                        $isLiveWindow = $now->gte($dt) && $now->lte($sessionEnd);
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
                                        $s = strtolower((string) $b->status);
                                        // Runtime-state label aligned with admin dashboard semantics.
                                        if ($isPast) {
                                            $badgeClass = 'finished';
                                            $badgeText = 'Meeting ended';
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
                                                } elseif ($now->lt($dt)) {
                                                    $badgeClass = 'pending';
                                                    $badgeText = 'Pending';
                                                } else {
                                                    $badgeClass = 'scheduled';
                                                    $badgeText = 'Scheduled';
                                                }
                                            } else {
                                                $badgeClass = 'finished';
                                                $badgeText = 'Meeting ended';
                                            }
                                        }
                                        // Safety fallback so UI never renders an empty badge.
                                        if (trim((string) $badgeText) === '') {
                                            $badgeClass = 'pending';
                                            $badgeText = 'Status unknown';
                                        }
                                    @endphp
                                    <span class="status-badge {{ $badgeClass }}">{{ $badgeText }}</span>
                            </div>

                            <div class="muted"><span class="label">Schedule:</span> {{ $dt->translatedFormat('d F Y') }}, {{ $dt->format('H:i') }} WIB</div>
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

                                    $displayNotes = trim(implode(' ', $cleanLines));
                                    if ($hasMeetingFinishedEvent) {
                                        $notice = 'Meeting dipaksa selesai oleh admin karena kendala koneksi. Anda mendapatkan warranty tickets untuk melanjutkan sesi dengan sisa waktu yang diberikan admin. Silakan cek di Warranty Tickets.';
                                        $displayNotes = trim($displayNotes === '' ? $notice : ($displayNotes . ' ' . $notice));
                                    }
                                @endphp
                                @if($displayNotes !== '' || $hasMeetingFinishedEvent)
                                    <div class="notes-wrap">
                                        @if($displayNotes !== '')
                                            <div class="meta"><span class="notes-label">Notes:</span>{{ $displayNotes }}</div>
                                        @endif
                                        @if($hasMeetingFinishedEvent)
                                            <div class="meeting-finished-box">Meeting ended</div>
                                        @endif
                                    </div>
                                @endif
                            @else
                                <div class="notes-wrap">
                                    <div class="meta"><span class="notes-label">Notes:</span>-</div>
                                </div>
                            @endif
                            {{-- feedback moved into booking->notes; notes displayed above --}}
                            @if(strtolower($b->status) === 'rejected')
                                <div class="muted" style="margin-top:8px">Reason: {{ $b->admin_note ?? 'The admin is unavailable, please reschedule.' }} — Your ticket has been returned.</div>
                            @endif
                        </div>

                        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:10px;min-width:160px">
                            @php
                                $statusLower = strtolower((string) $b->status);
                                $isEndedStatus = in_array($statusLower, ['ended', 'finished', 'completed'], true);
                            @endphp
                            @if($statusLower === 'rejected')
                                <form method="GET" action="{{ route('coaching.index') }}">
                                    <button type="button" class="btn-reschedule" onclick="window.location.href='{{ route('coaching.index') }}'">Reschedule</button>
                                </form>
                            @else
                                @if($isLiveWindow && !$isPast && !$isEndedStatus && in_array($statusLower, ['accepted', 'scheduled'], true))
                                    <div class="start-wrap">
                                        <button type="button" class="btn start-btn" disabled
                                            data-booking-time="{{ $dtLocal }}"
                                            data-status="{{ $b->status }}"
                                            data-href="{{ $sessionUrl }}"
                                            title="Join is available only when the session is live"
                                        >
                                            <span class="start-label">Join Session</span>
                                        </button>
                                        <span class="countdown"></span>
                                    </div>
                                @endif
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
                if (ms <= 0) return 'Live';
                const total = Math.floor(ms / 1000);
                const days = Math.floor(total / 86400);
                const hours = Math.floor((total % 86400) / 3600);
                const mins = Math.floor((total % 3600) / 60);
                const parts = [];
                if (days) parts.push(days + ' day' + (days === 1 ? '' : 's'));
                if (hours) parts.push(hours + ' hour' + (hours === 1 ? '' : 's'));
                parts.push(mins + ' min');
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

                    let endWindow = null;
                    if ((status === 'accepted' || status === 'scheduled') && dt) {
                        // enable only within window: from start until 60 minutes after start
                        const startWindow = new Date(dt.getTime());
                        endWindow = new Date(dt.getTime() + (60 * 60 * 1000));
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
                        if (endWindow && now > endWindow) {
                            cd.textContent = 'Ended';
                            cd.classList.remove('soon');
                            cd.classList.remove('live');
                        } else {
                            const delta = dt.getTime() - Date.now();
                            cd.textContent = formatDelta(delta);
                            // add small class when soon (less than 1 day) to highlight
                            if (delta <= (24 * 60 * 60 * 1000) && delta > 0) cd.classList.add('soon'); else cd.classList.remove('soon');
                            if (delta <= 0 && delta >= -(60 * 60 * 1000)) cd.classList.add('live'); else cd.classList.remove('live');
                        }
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
