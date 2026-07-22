    @extends('layouts.admin')

@section('title', 'Coaching Bookings')

@section('content')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('compro/css/admin-bookings.css') }}" />
    @endpush
    <div class="container-fluid py-4">
        @include('admin.coaching._nav')

    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-4 header">
            <div>
                <h2>Jadwal Live Coaching</h2>
                <p style="color:#888; font-size:14px">Kelola dan pantau seluruh jadwal sesi 1-on-1 live coaching murid, ruang video call, serta konfirmasi pendaftaran.</p>
            </div>

            <div style="min-width:320px;">
                <form method="GET" class="d-flex flex-wrap justify-content-end gap-2">
                    <input name="q" value="{{ request('q') }}" class="form-control form-control-sm" style="width:240px;" placeholder="Cari nama murid, email, order ID..." />

                    <select name="status" class="form-select form-select-sm text-white" style="width:160px; background-color: #1a1a1a; border:1px solid #333;">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                        <option value="accepted" {{ request('status')=='accepted' ? 'selected' : '' }}>Disetujui / Terjadwal</option>
                        <option value="rejected" {{ request('status')=='rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>

                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control form-control-sm text-white" style="width:140px; background-color:#1a1a1a; border:1px solid #333;" title="Tanggal Mulai" />
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control form-control-sm text-white" style="width:140px; background-color:#1a1a1a; border:1px solid #333;" title="Tanggal Akhir" />

                    <button class="btn btn-sm btn-primary">Filter</button>
                    @if(request()->hasAny(['q', 'status', 'date_from', 'date_to']))
                        <a href="{{ url('/admin/coaching/bookings') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                    @endif
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-3" style="background: #ffffff;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                            <tr>
                                <th style="min-width:180px; color:#475569; font-weight:700; font-size:0.8rem; text-transform:uppercase;">Murid / Siswa</th>
                                <th style="min-width:110px; color:#475569; font-weight:700; font-size:0.8rem; text-transform:uppercase;">Catatan</th>
                                <th style="min-width:170px; color:#475569; font-weight:700; font-size:0.8rem; text-transform:uppercase;">Waktu Sesi</th>
                                <th style="min-width:110px; color:#475569; font-weight:700; font-size:0.8rem; text-transform:uppercase;">Status</th>
                                <th style="min-width:180px; color:#475569; font-weight:700; font-size:0.8rem; text-transform:uppercase;">Pembayaran / Tiket</th>
                                <th style="min-width:160px; color:#475569; font-weight:700; font-size:0.8rem; text-transform:uppercase;">Ruang Call</th>
                                <th style="min-width:120px; color:#475569; font-weight:700; font-size:0.8rem; text-transform:uppercase;" class="text-end">Aksi Admin</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($bookings as $b)
                            <tr>
                                <td class="cb-col-user py-3">
                                    <div style="font-weight:700; color:#0f172a; font-size:0.92rem;">{{ optional($b->user)->name ?? 'Guest User' }}</div>
                                    <div style="color:#64748b; font-size:12px;" class="mt-0.5">
                                        {{ optional($b->user)->email }}
                                        @if(optional($b->user)->phone)
                                            <span class="ms-1">• HP: {{ $b->user->phone }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="cb-col-notes py-3">
                                    @php
                                        // hide noisy telemetry and render end-call events as a clean status badge
                                        $notes = $b->notes ?? '';
                                        $hasMeetingFinished = in_array(strtolower((string) $b->status), ['ended', 'finished', 'completed'], true);
                                        if ($notes) {
                                            $lines = preg_split('/\r?\n/', trim($notes));
                                            $filtered = array_filter($lines, function($l) {
                                                $low = strtolower($l);
                                                if (str_contains($low, 'connect_error') || str_contains($low, 'permission denied') || str_contains($low, 'notallowederror')) return false;
                                                return true;
                                            });

                                            $clean = [];
                                            foreach ($filtered as $line) {
                                                $low = strtolower((string) $line);
                                                if (str_contains($low, 'session_end_clicked') || str_contains($low, 'session_ended_by_admin')) {
                                                    $hasMeetingFinished = true;
                                                    continue;
                                                }
                                                $clean[] = $line;
                                            }

                                            $display = trim(implode("\n", array_slice($clean, 0, 5)));
                                            if (! $display) $display = '-';
                                        } else {
                                            $display = '-';
                                        }
                                    @endphp
                                    @if($display !== '-')
                                        <div style="color:#334155; font-size:12px;">{{ $display }}</div>
                                    @else
                                        <span style="color:#94a3b8; font-size:12px;">—</span>
                                    @endif
                                </td>
                                <td class="cb-col-time py-3">
                                    @php
                                        $bt = \Carbon\Carbon::parse($b->booking_time);
                                        $slotTime = $bt->format('H:i');
                                        $slotDate = $bt->toDateString();
                                        $key = $slotDate . ' ' . $slotTime;
                                        $taken = isset($slotCounts[$key]) ? $slotCounts[$key] : 0;
                                        $sessionLength = (int) ($b->session_duration_minutes ?? config('coaching.session_length_minutes', 60));
                                        $sessionStart = $bt->copy();
                                        $sessionEnd = $bt->copy()->addMinutes($sessionLength);
                                        $isPastByTime = $sessionEnd->lt(now());
                                        $isLiveWindow = now()->gte($sessionStart) && now()->lte($sessionEnd);
                                    @endphp
                                    <div style="font-weight:700; color:#0f172a; font-size:0.88rem;"><i class="fa-regular fa-calendar-check text-primary me-1"></i> {{ $bt->translatedFormat('j F Y') }}</div>
                                    <div style="color:#475569; font-size:12px;" class="mt-0.5"><i class="fa-regular fa-clock me-1"></i> {{ $bt->format('H:i') }} WIB <span class="badge bg-light text-secondary border ms-1" style="font-size:0.65rem;">Sesi #{{ $taken }}</span></div>
                                    <div style="margin-top:4px">
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-0.5" style="font-size:0.7rem;"><i class="fa-solid fa-hourglass-half me-1"></i> <span class="countdown" data-time-ms="{{ \Carbon\Carbon::parse($b->booking_time)->getTimestamp() * 1000 }}">-</span></span>
                                    </div>
                                </td>
                                <td class="cb-col-status py-3">
                                    @php $s = strtolower($b->status); @endphp
                                    @if($s === 'accepted' || $s === 'approved')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1 fw-bold" style="font-size:0.75rem;"><i class="fa-solid fa-circle-check me-1"></i> Disetujui</span>
                                    @elseif($s === 'rejected')
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2.5 py-1 fw-bold" style="font-size:0.75rem;"><i class="fa-solid fa-circle-xmark me-1"></i> Ditolak</span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-15 text-dark border border-warning border-opacity-50 px-2.5 py-1 fw-bold" style="font-size:0.75rem;"><i class="fa-solid fa-clock me-1"></i> Menunggu</span>
                                    @endif
                                </td>
                                <td class="cb-col-payment py-3">
                                    @php
                                        $paymentInfo = null;
                                        $rawSource = strtolower((string) (optional($b->ticket)->source ?? ''));

                                        if ($b->ticket && $b->ticket->source) {
                                            if (str_starts_with($b->ticket->source, 'midtrans:')) {
                                                $order = substr($b->ticket->source, strlen('midtrans:'));
                                                $txn = \App\Models\Transaction::where('order_id', $order)->latest()->first();
                                                if ($txn) {
                                                    $paymentInfo = ['order' => $order, 'amount' => $txn->amount, 'id' => $txn->id];
                                                } else {
                                                    $paymentInfo = ['order' => $order];
                                                }
                                            } else {
                                                $order = $b->ticket->source;
                                                $paymentInfo = ['order' => $order];
                                            }
                                        } else {
                                            $paymentInfo = ['info' => $b->ticket->source ?? null];
                                        }
                                    @endphp
                                    @if($rawSource === 'warranty' || str_contains($rawSource, 'warranty'))
                                        <span class="badge" style="background-color: #f3e8ff !important; color: #6b21a8 !important; border: 1px solid #d8b4fe !important; padding: 5px 8px; font-weight: 700; font-size:0.73rem;"><i class="fa-solid fa-shield-halved me-1"></i> Garansi Coaching</span>
                                        <div style="color:#64748b; font-size:11px;" class="mt-1">Klaim Garansi Bebas Biaya</div>
                                    @elseif($rawSource === 'free_on_register' || str_contains($rawSource, 'free'))
                                        <span class="badge" style="background-color: #e0f2fe !important; color: #0369a1 !important; border: 1px solid #7dd3fc !important; padding: 5px 8px; font-weight: 700; font-size:0.73rem;"><i class="fa-solid fa-gift me-1"></i> Bonus Registrasi</span>
                                        <div style="color:#64748b; font-size:11px;" class="mt-1">Sesi Gratis Pendaftaran</div>
                                    @elseif($paymentInfo)
                                        @if(isset($paymentInfo['id']))
                                            <div style="font-weight:700; color:#0f172a; font-family:monospace; font-size:12px;"><i class="fa-solid fa-receipt me-1 text-primary"></i> Order #{{ $paymentInfo['order'] }}</div>
                                            <div style="color:#059669; font-weight:700; font-size:12px;" class="mt-0.5">Rp {{ number_format($paymentInfo['amount'] ?? 0,0,',','.') }}</div>
                                            <div class="mt-1">
                                                <a class="btn btn-xs btn-outline-primary" style="font-size:0.72rem; padding: 2px 7px;" href="{{ route('admin.transactions.index') }}?q={{ $paymentInfo['order'] }}"><i class="fa-solid fa-magnifying-glass me-1"></i> Lihat Transaksi</a>
                                            </div>
                                        @elseif(isset($paymentInfo['order']))
                                            <div style="font-weight:700; color:#0f172a; font-family:monospace; font-size:12px;"><i class="fa-solid fa-receipt me-1 text-primary"></i> {{ $paymentInfo['order'] }}</div>
                                        @else
                                            <span style="color:#94a3b8; font-size:12px;">—</span>
                                        @endif
                                    @else
                                        <span style="color:#94a3b8; font-size:12px;">—</span>
                                    @endif
                                </td>
                            <td class="cb-col-twilio py-3">
                                @php $sessionUrl = url('/coaching/session/'.$b->id); $btLocal = \Carbon\Carbon::parse($b->booking_time)->format('Y-m-d H:i:s'); @endphp
                                @if($b->twilio_room_sid)
                                    <div class="d-flex flex-column gap-1">
                                        <div style="color:#64748b; font-family:monospace; font-size:0.7rem;" title="Room SID: {{ $b->twilio_room_sid }}">
                                            <i class="fa-solid fa-video me-1 text-primary"></i> Room #{{ substr($b->twilio_room_sid, 0, 8) }}...
                                        </div>
                                        <div>
                                            <a class="btn btn-sm btn-primary open-session-btn d-inline-flex align-items-center gap-1 shadow-sm" style="font-size:0.78rem; padding: 4px 10px;" data-booking-time="{{ $btLocal }}" data-href="{{ $sessionUrl }}" target="_blank" href="#">
                                                <i class="fa-solid fa-headset me-1"></i> <span>Masuk Ruang Call</span>
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <form method="POST" action="{{ url('/admin/coaching/bookings/'.$b->id.'/create-room') }}" style="display:inline">@csrf
                                        <button class="btn btn-sm btn-outline-secondary" style="font-size:0.75rem;"><i class="fa-solid fa-plus me-1"></i> Buat Room</button>
                                    </form>
                                @endif
                            </td>
                            <td class="text-end cb-col-actions py-3">
                                @if(strtolower($b->status) === 'pending')
                                    <div class="d-flex justify-content-end align-items-center gap-1.5">
                                        <form method="POST" action="{{ url('/admin/coaching/bookings/'.$b->id.'/accept') }}" style="display:inline">@csrf
                                            <button class="btn btn-sm btn-success px-2 py-1" style="font-size:0.75rem;" title="Setujui Booking"><i class="fa-solid fa-check me-1"></i> Setujui</button>
                                        </form>

                                        <button type="button" class="btn btn-sm btn-danger reject-open-btn px-2 py-1" style="font-size:0.75rem;" data-action="{{ url('/admin/coaching/bookings/'.$b->id.'/reject') }}" title="Tolak Booking"><i class="fa-solid fa-xmark me-1"></i> Tolak</button>
                                    </div>
                                @else
                                    @if($hasMeetingFinished || $isPastByTime)
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border px-2 py-1" style="font-size:0.72rem;"><i class="fa-solid fa-check-double me-1"></i> Sesi Selesai</span>
                                    @elseif(strtolower($b->status) === 'accepted' && $isLiveWindow)
                                        <span class="badge bg-warning text-dark fw-bold px-2 py-1" style="font-size:0.72rem;"><i class="fa-solid fa-tower-broadcast me-1"></i> Berlangsung</span>
                                    @elseif(strtolower($b->status) === 'accepted')
                                        <span class="badge bg-info bg-opacity-15 text-info border border-info border-opacity-25 fw-bold px-2 py-1" style="font-size:0.72rem;"><i class="fa-solid fa-calendar-days me-1"></i> Terjadwal</span>
                                    @else
                                        <span style="color:#94a3b8; font-size:12px;">—</span>
                                    @endif
                                @endif
                            </td>
                        </tr>


                    @empty
                        <tr style="pointer-events: none; background: transparent;">
                            <td colspan="7" class="text-center pt-5">No bookings found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                </div>
            </div>
        </div>

        <div class="mt-3">{{ $bookings->appends(request()->query())->links() }}</div>
    </div>
    <!-- Reject reason modal -->
    <div id="rejectModal" style="display:none;position:fixed;left:0;top:0;width:100%;height:100%;align-items:center;justify-content:center;z-index:2000;">
        <div class="modal-inner" style="padding:18px;border-radius:8px;max-width:520px;margin:40px auto;position:relative;">
            <button class="reject-modal-close" style="position:absolute;right:8px;top:8px;border:none;background:transparent;font-size:18px;">&times;</button>
            <h4 style="margin-top:0">Reason for rejection</h4>
            <form id="rejectModalForm">
                <div style="margin-bottom:8px;"><textarea name="reason" rows="4" class="form-control" placeholder="Short reason (visible to user)"></textarea></div>
                <div style="display:flex;gap:8px;justify-content:flex-end;">
                    <button type="button" class="btn btn-secondary reject-modal-close">Cancel</button>
                    <button type="submit" class="btn btn-danger">Submit Rejection</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        (function () {
            function formatDelta(ms) {
                if (ms <= 0) return 'Live';
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

            function updateCountdowns() {
                document.querySelectorAll('.countdown').forEach(function (el) {
                    const msAttr = el.getAttribute('data-time-ms');
                    if (!msAttr) return;
                    const target = parseInt(msAttr, 10);
                    if (isNaN(target)) return;
                    const now = Date.now();
                    const delta = target - now;
                    el.textContent = formatDelta(delta);
                });
            }

            // Enable Open Session buttons when within the session window (start until 60 minutes after)
            // Also show a dynamic remaining-time label: "Open Session (Xm)" where applicable
            function updateOpenSessionButtons() {
                const now = Date.now();
                document.querySelectorAll('.open-session-btn').forEach(function (btn) {
                    const dtStr = btn.getAttribute('data-booking-time');
                    const href = btn.getAttribute('data-href');
                    if (!dtStr) return;
                    const iso = dtStr.replace(' ', 'T');
                    const dt = new Date(iso);
                    if (isNaN(dt.getTime())) return;
                    const startMs = dt.getTime();
                    const startWindow = startMs;
                    const endWindow = startMs + (60 * 60 * 1000);

                    // enable only when within the open window (start time until end window)
                    if (now >= startWindow && now <= endWindow) {
                        btn.innerHTML = '<i class="fa-solid fa-headset me-1"></i> Masuk Ruang Call';
                        btn.classList.remove('disabled', 'btn-outline-secondary');
                        btn.classList.add('btn-primary');
                        btn.removeAttribute('aria-disabled');
                        if (href) btn.setAttribute('href', href);
                        btn.style.pointerEvents = '';
                        btn.style.opacity = '1';
                    } else {
                        btn.innerHTML = '<i class="fa-solid fa-lock me-1"></i> Belum Dimulai';
                        btn.classList.add('disabled', 'btn-outline-secondary');
                        btn.classList.remove('btn-primary');
                        btn.setAttribute('aria-disabled', 'true');
                        btn.removeAttribute('href');
                        btn.style.pointerEvents = 'none';
                        btn.style.opacity = '0.65';
                    }

                });
            }

            // Reject modal handling: open modal, collect reason, then submit
            let currentRejectAction = null;
            function openRejectModal(actionUrl) {
                currentRejectAction = actionUrl;
                const modal = document.getElementById('rejectModal');
                modal.style.display = 'block';
                modal.querySelector('textarea[name="reason"]').value = '';
                modal.querySelector('textarea[name="reason"]').focus();
            }

            function closeRejectModal() {
                const modal = document.getElementById('rejectModal');
                modal.style.display = 'none';
                currentRejectAction = null;
            }

            document.addEventListener('click', function (e) {
                if (e.target && e.target.classList && e.target.classList.contains('reject-open-btn')) {
                    const action = e.target.getAttribute('data-action');
                    openRejectModal(action);
                }
                if (e.target && e.target.classList && e.target.classList.contains('reject-modal-close')) {
                    closeRejectModal();
                }
            });

            document.getElementById && document.getElementById('rejectModalForm') && document.getElementById('rejectModalForm').addEventListener('submit', function (e) {
                e.preventDefault();
                const ta = this.querySelector('textarea[name="reason"]');
                if (!ta || !ta.value.trim()) {
                    alert('Please provide a short reason for rejection');
                    ta && ta.focus();
                    return;
                }
                if (!currentRejectAction) {
                    alert('Missing action URL');
                    return;
                }
                // create a form and submit to action URL
                const f = document.createElement('form');
                f.method = 'POST';
                f.action = currentRejectAction;
                f.style.display = 'none';
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const inputToken = document.createElement('input'); inputToken.type = 'hidden'; inputToken.name = '_token'; inputToken.value = token; f.appendChild(inputToken);
                const inputReason = document.createElement('input'); inputReason.type = 'hidden'; inputReason.name = 'reason'; inputReason.value = ta.value; f.appendChild(inputReason);
                document.body.appendChild(f);
                f.submit();
            });

            updateCountdowns();
            updateOpenSessionButtons();
            setInterval(function(){ updateCountdowns(); updateOpenSessionButtons(); }, 30 * 1000);
        })();
    </script>
@endsection
