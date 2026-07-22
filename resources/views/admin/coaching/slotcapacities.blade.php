@extends('layouts.admin')

@section('title', 'Slot Capacities Coaching')

@section('content')
    @push('styles')
        <style>
            .coaching-page {
                font-family: 'Inter', system-ui, -apple-system, sans-serif;
                background-color: #F8FAFC;
            }

            /* Light Modern Calendar Styling */
            #calendar table { 
                width: 100%; 
                border-collapse: separate; 
                border-spacing: 0; 
                border-radius: 12px; 
                overflow: hidden; 
                border: 1px solid #E2E8F0; 
                background: #FFFFFF; 
            }
            #calendar thead th { 
                background: #F8FAFC; 
                color: #475569; 
                padding: 12px 8px; 
                text-align: center; 
                border-bottom: 1px solid #E2E8F0; 
                font-size: 12px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }
            #calendar tbody td { 
                background: #FFFFFF; 
                color: #1E293B; 
                vertical-align: top; 
                padding: 12px; 
                border-right: 1px solid #F1F5F9; 
                border-bottom: 1px solid #F1F5F9; 
                transition: background 0.15s ease;
            }
            #calendar tbody tr:last-child td { border-bottom: none; }
            #calendar tbody td .day-number { font-weight: 700; display: block; margin-bottom: 6px; color: #0F172A; font-size: 0.9rem; }
            #calendar tbody td:not(.inactive):hover { background: #EFF6FF !important; cursor: pointer; }
            #calendar td.inactive { background: #F8FAFC !important; color: #94A3B8 !important; cursor: default; }
            #calendar td.past { opacity: 0.5; }
            
            .slot-badge-count { 
                background: #EFF6FF; 
                color: #1D4ED8; 
                border: 1px solid #BFDBFE;
                border-radius: 6px; 
                padding: 2px 7px; 
                font-size: 11px; 
                font-weight: 700;
            }

            /* Hours Modal Buttons Styling */
            .unselected-hour {
                background: #F8FAFC !important;
                color: #334155 !important;
                border: 1px solid #CBD5E1 !important;
                transition: all 0.15s ease;
            }
            .unselected-hour:hover {
                background: #EFF6FF !important;
                color: #2563EB !important;
                border-color: #2563EB !important;
            }
            .selected-hour {
                background: #2563EB !important;
                color: #FFFFFF !important;
                border: 1px solid #2563EB !important;
                font-weight: 700 !important;
                box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25);
            }
            .slot-hour-booked {
                background: #FEF2F2 !important;
                color: #DC2626 !important;
                border: 1px solid #FCA5A5 !important;
                opacity: 0.85;
                cursor: not-allowed !important;
            }
            .slot-hour-past {
                background: #F1F5F9 !important;
                color: #94A3B8 !important;
                border: 1px solid #E2E8F0 !important;
                opacity: 0.65;
                cursor: not-allowed !important;
            }

            /* Side Panel Cards & Chips */
            #pendingList .pending-item {
                border: 1px solid #FDE68A;
                border-radius: 10px;
                background: #FFFBEB;
                padding: 12px;
                margin-bottom: 10px;
            }

            #existingList .existing-card {
                border: 1px solid #E2E8F0;
                border-radius: 12px;
                background: #FFFFFF;
                padding: 14px;
                margin-bottom: 12px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            }

            #existingList .existing-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 12px;
            }

            #existingList .existing-head {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 8px;
                margin-bottom: 10px;
                padding-bottom: 8px;
                border-bottom: 1px solid #F1F5F9;
            }

            #existingList .existing-date {
                font-weight: 800;
                color: #0F172A;
                font-size: 0.88rem;
            }

            #existingList .existing-times {
                display: flex;
                flex-wrap: wrap;
                gap: 6px;
            }

            #existingList .existing-chip {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                background: #EFF6FF;
                color: #1D4ED8;
                border: 1px solid #BFDBFE;
                border-radius: 6px;
                font-size: 12px;
                font-weight: 700;
                padding: 4px 8px;
                font-family: monospace;
            }

            #existingList .existing-chip button {
                border: 0;
                background: transparent;
                color: #DC2626;
                font-size: 14px;
                line-height: 1;
                padding: 0 0 0 2px;
                cursor: pointer;
                font-weight: 800;
                transition: color 0.15s ease;
            }
            #existingList .existing-chip button:hover {
                color: #991B1B;
            }

            .slot-toast {
                position: fixed;
                top: 18px;
                right: 18px;
                z-index: 9999;
                min-width: 280px;
                max-width: min(430px, 92vw);
                border-radius: 10px;
                border: 1px solid transparent;
                padding: 10px 14px;
                color: #fff;
                box-shadow: 0 10px 25px -5px rgba(15,23,42,0.2);
                opacity: 0;
                transform: translateY(-10px);
                pointer-events: none;
                transition: opacity .2s ease, transform .2s ease;
                font-size: 13px;
                font-weight: 600;
            }

            .slot-toast.show { opacity: 1; transform: translateY(0); }
            .slot-toast.success { background: #059669; border-color: #047857; }
            .slot-toast.error { background: #DC2626; border-color: #B91C1C; }
            .slot-toast.info { background: #2563EB; border-color: #1D4ED8; }

            @media (max-width: 1200px){
                #existingList .existing-grid { grid-template-columns: 1fr; }
            }
        </style>
    @endpush

    <div class="coaching-page min-h-screen p-3 md:p-6">
        <div class="max-w-7xl mx-auto space-y-6">

            @include('admin.coaching._nav')

            <!-- Header Section -->
            <div class="d-flex flex-column flex-md-row md:items-start justify-content-between gap-3 mb-4">
                <div>
                    <div class="d-flex align-items-center gap-3 mb-1">
                        <h1 class="h4 fw-bold text-slate-900 tracking-tight mb-0" style="color: #0F172A; font-weight: 800; font-size: 1.5rem;">
                            Coaching Slot Capacities — {{ DateTime::createFromFormat('!m', $month)->format('F') }} {{ $year }}
                        </h1>
                        <span class="px-2.5 py-0.5 rounded-pill bg-blue-100 text-blue-700 fw-semibold" style="font-size: 0.75rem; background: #DBEAFE; color: #1D4ED8;">
                            Bulan Berjalan
                        </span>
                    </div>
                    <p class="text-slate-500 mb-0" style="color: #64748B; font-size: 0.875rem;">
                        Pilih tanggal di kalender untuk mengatur slot jam mengajar per hari (kapasitas 1 murid / slot).
                    </p>
                </div>
            </div>

            <div class="row g-4">
                <!-- Left: Calendar Box -->
                <div class="col-lg-8">
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4" style="border-radius: 12px; border: 1px solid #E2E8F0;">
                        <div id="calendar" class="mb-0"></div>
                    </div>
                </div>

                <!-- Right: Side Panel (Pending & Existing) -->
                <div class="col-lg-4">
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 space-y-4" style="border-radius: 12px; border: 1px solid #E2E8F0;">
                        <div>
                            <h6 class="fw-bold text-slate-900 mb-1" style="color: #0F172A; font-size: 0.95rem;">Jadwal Baru (Pending)</h6>
                            <p class="text-slate-500 mb-2" style="font-size: 0.78rem; color: #64748B;">Slot yang Anda pilih (belum disimpan ke database).</p>
                            <div id="pendingList" style="min-height: 60px;"></div>
                        </div>

                        <hr class="my-3" style="border-color: #E2E8F0;">

                        <div>
                            <h6 class="fw-bold text-slate-900 mb-2" style="color: #0F172A; font-size: 0.95rem;">Jadwal Aktif Bulan Ini</h6>
                            <div id="existingList" style="min-height: 80px;"></div>
                        </div>

                        <form id="saveForm" method="POST" action="{{ url('/admin/coaching/slot-capacities') }}" class="m-0 pt-2">
                            @csrf
                            <input type="hidden" name="slots_json" id="slots_json">
                            <button id="saveAllBtn" class="btn btn-primary w-100 font-semibold py-2" type="submit" disabled style="background: #2563EB; border-radius: 8px; font-size: 0.875rem;">
                                Simpan Perubahan Slot
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Pick Hours Modal -->
            <div class="modal fade" id="hoursModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                        <div class="modal-header border-bottom" style="border-bottom-color: #E2E8F0;">
                            <h5 class="modal-title fw-bold text-slate-900" style="font-size: 1rem; color: #0F172A;">
                                Pilih Slot Jam untuk <span id="modalDateLabel" class="text-blue-600" style="color: #2563EB;"></span>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <p class="text-slate-600 mb-3" style="font-size: 0.85rem;">Klik jam yang ingin dibuka untuk slot coaching (interval 1 jam):</p>
                            <div id="hoursGrid" class="d-flex flex-wrap gap-2 pt-1 mb-2"></div>
                            
                            <!-- Legend -->
                            <div class="d-flex flex-wrap align-items-center gap-3 pt-3 mt-3 border-top" style="border-top-color: #F1F5F9; font-size: 0.75rem;">
                                <div class="d-flex align-items-center gap-1.5">
                                    <span class="d-inline-block rounded-circle" style="width: 10px; height: 10px; background: #2563EB;"></span>
                                    <span class="text-slate-600">Dipilih</span>
                                </div>
                                <div class="d-flex align-items-center gap-1.5">
                                    <span class="d-inline-block rounded-circle" style="width: 10px; height: 10px; background: #F8FAFC; border: 1px solid #CBD5E1;"></span>
                                    <span class="text-slate-600">Tersedia</span>
                                </div>
                                <div class="d-flex align-items-center gap-1.5">
                                    <span class="d-inline-block rounded-circle" style="width: 10px; height: 10px; background: #FEF2F2; border: 1px solid #FCA5A5;"></span>
                                    <span class="text-slate-600">Sudah Dipesan</span>
                                </div>
                                <div class="d-flex align-items-center gap-1.5">
                                    <span class="d-inline-block rounded-circle" style="width: 10px; height: 10px; background: #F1F5F9; border: 1px solid #E2E8F0;"></span>
                                    <span class="text-slate-600">Waktu Lewat</span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-top" style="border-top-color: #E2E8F0;">
                            <button type="button" class="btn btn-light font-semibold" data-bs-dismiss="modal" style="border-radius: 8px; font-size: 0.85rem;">Batal</button>
                            <button id="addHoursBtn" type="button" class="btn btn-primary font-semibold" style="background: #2563EB; border-radius: 8px; font-size: 0.85rem;">Tambahkan ke Daftar</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div id="slotToast" class="slot-toast" role="status" aria-live="polite"></div>
@endsection

@section('scripts')
<script>
    const year = {{ $year }};
    const month = {{ $month }};
    const sessionLengthMinutes = {{ (int) config('coaching.session_length_minutes', 60) }};
    const existing = @json($slots);
    const booked = @json($booked ?? []);
    const sessionSuccess = @json(session('success'));

    let pendingEntries = {};
    let toastTimer = null;

    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    function showToast(message, type = 'success') {
        const toast = document.getElementById('slotToast');
        if (!toast || !message) return;
        toast.textContent = message;
        toast.classList.remove('success', 'error', 'info', 'show');
        toast.classList.add(type);
        void toast.offsetWidth;
        toast.classList.add('show');
        if (toastTimer) clearTimeout(toastTimer);
        toastTimer = setTimeout(() => toast.classList.remove('show'), 2400);
    }

    function isPastHour(isoDate, hourLabel) {
        const slotAt = new Date(`${isoDate}T${hourLabel}:00`);
        if (Number.isNaN(slotAt.getTime())) return false;
        const slotEnd = slotAt.getTime() + (sessionLengthMinutes * 60 * 1000);
        return slotEnd <= Date.now();
    }

    function buildCalendar(y, m) {
        function formatDateLocal(d) {
            const Y = d.getFullYear();
            const M = ('0' + (d.getMonth() + 1)).slice(-2);
            const D = ('0' + d.getDate()).slice(-2);
            return `${Y}-${M}-${D}`;
        }

        const first = new Date(y, m - 1, 1);
        const last = new Date(y, m, 0);
        const startWeekDay = first.getDay();
        const weeks = [];
        let day = 1 - startWeekDay;

        while (day <= last.getDate()) {
            const week = [];
            for (let i = 0; i < 7; i++) {
                const d = new Date(y, m - 1, day);
                const inMonth = d.getMonth() === (m - 1);
                week.push({ day: d.getDate(), date: new Date(d), inMonth });
                day++;
            }
            weeks.push(week);
        }

        const container = document.getElementById('calendar');
        container.innerHTML = '';

        const table = document.createElement('table');

        const thead = document.createElement('thead');
        thead.innerHTML = '<tr><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr>';
        table.appendChild(thead);

        const tbody = document.createElement('tbody');

        weeks.forEach(row => {
            const tr = document.createElement('tr');

            row.forEach(cell => {
                const td = document.createElement('td');
                td.style.width = '120px';
                td.style.height = '80px';
                td.className = 'align-top';

                if (!cell.inMonth) {
                    td.classList.add('inactive');
                    td.innerHTML = `<div class="small text-slate-400" style="color: #94A3B8;">${cell.day}</div>`;
                } else {
                    const iso = formatDateLocal(cell.date);
                    const has = existing[iso] && existing[iso].length > 0;
                    const hasBookings = booked[iso] && booked[iso].length > 0;
                    const now = new Date();
                    const isPast = cell.date < new Date(now.getFullYear(), now.getMonth(), now.getDate());

                    td.innerHTML = `<div class="d-flex justify-content-between align-items-center"><div class="day-number">${cell.day}</div><div>${has ? '<span class="slot-badge-count">' + existing[iso].length + '</span>' : ''}${hasBookings ? '<span title="Has bookings" style="display:inline-block;width:8px;height:8px;background:#EF4444;border-radius:50%;margin-left:6px"></span>' : ''}</div></div>`;
                    td.dataset.date = iso;

                    if (isPast) {
                        td.classList.add('inactive', 'past');
                    } else {
                        td.style.cursor = 'pointer';
                        td.addEventListener('click', () => openModalForDate(iso));
                    }
                }

                tr.appendChild(td);
            });

            tbody.appendChild(tr);
        });

        table.appendChild(tbody);
        container.appendChild(table);
    }

    const hoursGrid = document.getElementById('hoursGrid');
    for (let h = 0; h < 24; h++) {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn btn-sm font-mono unselected-hour';
        btn.style.width = '70px';
        btn.style.borderRadius = '6px';
        btn.style.fontSize = '0.78rem';
        const label = ('0' + h).slice(-2) + ':00';
        btn.textContent = label;
        btn.dataset.hour = label;
        btn.addEventListener('click', () => {
            if (!btn.disabled) {
                btn.classList.toggle('selected-hour');
                btn.classList.toggle('unselected-hour');
            }
        });
        hoursGrid.appendChild(btn);
    }

    function openModalForDate(iso) {
        document.querySelectorAll('#hoursGrid button').forEach(b => {
            b.disabled = false;
            b.title = '';
            b.className = 'btn btn-sm font-mono unselected-hour';
            b.style.width = '70px';
            b.style.borderRadius = '6px';
            b.style.fontSize = '0.78rem';
        });

        document.getElementById('modalDateLabel').textContent = iso;

        document.querySelectorAll('#hoursGrid button').forEach(b => {
            const hour = b.dataset.hour;
            const hourBooked = !!(booked[iso] && booked[iso].includes(hour));
            const hourPast = isPastHour(iso, hour);

            if (hourBooked) {
                b.disabled = true;
                b.className = 'btn btn-sm font-mono slot-hour-booked';
                b.style.width = '70px';
                b.style.borderRadius = '6px';
                b.style.fontSize = '0.78rem';
                b.title = 'Already booked';
            } else if (hourPast) {
                b.disabled = true;
                b.className = 'btn btn-sm font-mono slot-hour-past';
                b.style.width = '70px';
                b.style.borderRadius = '6px';
                b.style.fontSize = '0.78rem';
                b.title = 'This time has passed';
            }
        });

        const activeHours = pendingEntries[iso] || existing[iso] || [];
        activeHours.forEach(h => {
            const b = Array.from(document.querySelectorAll('#hoursGrid button')).find(x => x.dataset.hour === h);
            if (b && !b.disabled) {
                b.className = 'btn btn-sm font-mono selected-hour';
                b.style.width = '70px';
                b.style.borderRadius = '6px';
                b.style.fontSize = '0.78rem';
            }
        });

        document.getElementById('hoursModal').dataset.currentDate = iso;
        const modal = new bootstrap.Modal(document.getElementById('hoursModal'));
        modal.show();
    }

    document.getElementById('addHoursBtn').addEventListener('click', function () {
        const iso = document.getElementById('hoursModal').dataset.currentDate;
        const hours = Array.from(document.querySelectorAll('#hoursGrid button.selected-hour'))
            .map(b => b.dataset.hour)
            .filter(h => !isPastHour(iso, h));

        if (!iso || hours.length === 0) {
            showToast('Pilih setidaknya 1 slot jam yang tersedia', 'error');
            return;
        }

        pendingEntries[iso] = hours;
        renderPending();
        document.getElementById('saveAllBtn').disabled = false;
        bootstrap.Modal.getInstance(document.getElementById('hoursModal')).hide();
    });

    document.getElementById('saveForm').addEventListener('submit', function (e) {
        e.preventDefault();
        if (Object.keys(pendingEntries).length === 0) {
            showToast('Tidak ada jadwal pending untuk disimpan', 'error');
            return;
        }

        doAjaxSave(true);
    });

    function doAjaxSave(replace) {
        fetch('/admin/coaching/slot-capacities', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ slots_json: pendingEntries, replace: replace })
        }).then(async (r) => {
            const data = await r.json().catch(() => null);
            if (!r.ok) {
                const message = (data && (data.error || data.message)) ? (data.error || data.message) : `Gagal menyimpan (${r.status})`;
                throw new Error(message);
            }
            return data;
        }).then(data => {
            if (data && data.success) {
                Object.keys(data.updated || {}).forEach(d => { existing[d] = data.updated[d]; });
                Object.keys(pendingEntries).forEach(d => { delete pendingEntries[d]; });
                renderPending();
                buildCalendar(year, month);
                renderExisting();
                document.getElementById('saveAllBtn').disabled = true;
                showToast('Jadwal slot berhasil disimpan', 'success');
            } else {
                showToast((data && (data.error || data.message)) ? (data.error || data.message) : 'Gagal menyimpan slot', 'error');
            }
        }).catch(err => {
            console.error(err);
            showToast(err && err.message ? err.message : 'Terjadi kesalahan jaringan', 'error');
        });
    }

    function renderPending() {
        const list = document.getElementById('pendingList');
        list.innerHTML = '';

        const keys = Object.keys(pendingEntries).sort();
        if (keys.length === 0) {
            list.innerHTML = '<div class="text-slate-400" style="font-size: 0.8rem; color: #94A3B8;">Tidak ada slot pending.</div>';
            return;
        }

        keys.forEach(d => {
            const div = document.createElement('div');
            div.className = 'pending-item';
            const hrs = pendingEntries[d].map(h => `<span class="badge bg-amber-100 text-amber-800 me-1" style="background:#FEF3C7; color:#92400E; border:1px solid #FDE68A; border-radius:4px;">${h}</span>`).join('');
            div.innerHTML = `<div class="d-flex justify-content-between align-items-start"><div><strong class="text-slate-900" style="font-size:0.85rem">${d}</strong><div class="mt-1">${hrs}</div></div><div><button class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size:0.75rem; border-radius:4px;">Hapus</button></div></div>`;
            div.querySelector('button').addEventListener('click', () => {
                delete pendingEntries[d];
                renderPending();
                if (Object.keys(pendingEntries).length === 0) document.getElementById('saveAllBtn').disabled = true;
            });
            list.appendChild(div);
        });

        try {
            const hidden = document.getElementById('slots_json');
            if (hidden) hidden.value = JSON.stringify(pendingEntries);
        } catch (e) {}
    }

    function renderExisting() {
        const container = document.getElementById('existingList');
        container.innerHTML = '';

        const keys = Object.keys(existing).sort();
        if (keys.length === 0) {
            container.innerHTML = '<div class="text-slate-400" style="font-size:0.8rem; color: #94A3B8;">Belum ada jadwal slot bulan ini.</div>';
            return;
        }

        const grid = document.createElement('div');
        grid.className = 'existing-grid';

        keys.forEach(d => {
            const div = document.createElement('div');
            div.className = 'existing-card';
            const hours = (existing[d] || []).slice().sort();
            const chips = hours.map(h => `<span class="existing-chip">${h} <button class="remove-hour" data-date="${d}" data-time="${h}" title="Hapus ${h}">×</button></span>`).join('');

            div.innerHTML = `
                <div class="existing-head">
                    <div class="existing-date">${d}</div>
                    <button class="btn btn-sm btn-outline-danger delete-date px-2 py-0.5" style="font-size: 0.72rem; border-radius: 6px; background: #FEF2F2; color: #DC2626; border: 1px solid #FCA5A5;" data-date="${d}">Hapus Tanggal</button>
                </div>
                <div class="existing-times">${chips || '<span class="text-slate-400">Tidak ada slot</span>'}</div>
            `;

            grid.appendChild(div);
        });

        container.appendChild(grid);
    }

    document.getElementById('existingList').addEventListener('click', function (e) {
        const removeHourBtn = e.target.closest('.remove-hour');
        const removeDateBtn = e.target.closest('.delete-date');

        if (removeHourBtn) {
            e.preventDefault();
            const dt = removeHourBtn.dataset.date;
            const tm = removeHourBtn.dataset.time;
            if (!confirm('Hapus slot jam ' + tm + ' pada tanggal ' + dt + '?')) return;

            fetch('/admin/coaching/slot-capacities/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ date: dt, time: tm })
            }).then(async (r) => {
                const data = await r.json().catch(() => null);
                if (!r.ok) {
                    const message = (data && (data.error || data.message)) ? (data.error || data.message) : `Gagal menghapus (${r.status})`;
                    throw new Error(message);
                }
                return data;
            }).then(data => {
                if (data && data.success) {
                    existing[dt] = data.remaining || [];
                    if (existing[dt].length === 0) delete existing[dt];
                    renderExisting();
                    buildCalendar(year, month);
                    showToast('Slot ' + tm + ' pada ' + dt + ' berhasil dihapus', 'info');
                } else {
                    showToast((data && (data.error || data.message)) ? (data.error || data.message) : 'Gagal menghapus slot', 'error');
                }
            }).catch(err => {
                console.error(err);
                showToast(err && err.message ? err.message : 'Kesalahan jaringan saat menghapus slot', 'error');
            });
            return;
        }

        if (removeDateBtn) {
            e.preventDefault();
            const date = removeDateBtn.dataset.date;
            if (!confirm('Hapus seluruh slot pada tanggal ' + date + '?')) return;

            fetch('/admin/coaching/slot-capacities/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ date: date })
            }).then(async (r) => {
                const data = await r.json().catch(() => null);
                if (!r.ok) {
                    const message = (data && (data.error || data.message)) ? (data.error || data.message) : `Gagal menghapus (${r.status})`;
                    throw new Error(message);
                }
                return data;
            }).then(data => {
                if (data && data.success) {
                    delete existing[date];
                    renderExisting();
                    buildCalendar(year, month);
                    showToast('Semua slot pada ' + date + ' berhasil dihapus', 'info');
                } else {
                    showToast((data && (data.error || data.message)) ? (data.error || data.message) : 'Gagal menghapus slot', 'error');
                }
            }).catch(err => {
                console.error(err);
                showToast(err && err.message ? err.message : 'Kesalahan jaringan saat menghapus slot', 'error');
            });
        }
    });

    buildCalendar(year, month);
    renderExisting();

    if (sessionSuccess) {
        showToast(sessionSuccess, 'success');
    }
</script>
@endsection
