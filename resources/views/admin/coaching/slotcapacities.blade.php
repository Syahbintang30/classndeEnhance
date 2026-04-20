@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4 header">
                <div>
                    <h2 class="mb-0">Coaching Slot Capacities - {{ DateTime::createFromFormat('!m', $month)->format('F') }} {{ $year }}</h2>
                </div>
                <small style="color: #666">Showing current month</small>
            </div>

            <p class="m-0" style="color:#666; font-size:14px">Select dates in the calendar, then click "Edit hours" to pick hourly slots (1-hour increments). Each slot will be for 1 person (capacity = 1).</p>
            <p style="color:#666; font-size:14px">Click a date to pick hours, then click <strong>Add</strong> to queue the schedule in the sidebar.</p>

            <style>
                #calendar table { width:100%; border-collapse: collapse; border-radius:12px; overflow: hidden; box-shadow: 0 6px 24px rgba(0,0,0,0.35); background:#0b0b0b; }
                #calendar thead th { background:#0b0b0b; color:#e6e7e8; padding:12px 8px; text-align:center; border-bottom:1px solid rgba(255,255,255,0.03); }
                #calendar tbody td { background:#0d0d0e; color:#d6d8da; vertical-align: top; padding:14px; border-right:1px solid rgba(255,255,255,0.02); border-bottom:1px solid rgba(255,255,255,0.02); }
                #calendar tbody tr:last-child td { border-bottom: none; }
                #calendar tbody td .day-number { font-weight:600; display:block; margin-bottom:8px; color:#cfd3d6; }
                #calendar tbody td:not(.inactive):hover { background: #1a1a1b; cursor:pointer; }
                #calendar td.inactive { background:transparent; color:#6c757d; cursor:default; }
                #calendar td.past { opacity:0.55; }
                #calendar td.selected { background:#343a40 !important; color:#fff !important; }
                .slot-badge, #calendar .badge { background:#6c757d; color:#fff; border-radius:6px; padding:4px 6px; font-size:12px; }
                .badge.bg-success { background:#495057 !important; }

                #hoursGrid .slot-hour-past { opacity: .65; }
                #hoursGrid .slot-hour-booked { opacity: .9; }

                #pendingList .pending-item,
                #existingList .existing-card {
                    border: 1px solid rgba(255,255,255,0.08);
                    border-radius: 12px;
                    background: rgba(255,255,255,0.02);
                    padding: 12px;
                    margin-bottom: 10px;
                }

                #existingList .existing-grid {
                    display: grid;
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                    gap: 10px;
                }

                #existingList .existing-head {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 8px;
                    margin-bottom: 8px;
                }

                #existingList .existing-date {
                    font-weight: 700;
                    letter-spacing: 0.2px;
                    word-break: break-word;
                }

                #existingList .existing-times {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 6px;
                }

                #existingList .existing-chip {
                    display: inline-flex;
                    align-items: center;
                    gap: 5px;
                    background: #6c757d;
                    color: #fff;
                    border-radius: 999px;
                    font-size: 12px;
                    font-weight: 600;
                    padding: 4px 8px;
                    line-height: 1;
                }

                #existingList .existing-chip button {
                    border: 0;
                    background: transparent;
                    color: #f8d7da;
                    font-size: 12px;
                    line-height: 1;
                    padding: 0;
                    cursor: pointer;
                }

                .slot-toast {
                    position: fixed;
                    top: 18px;
                    right: 18px;
                    z-index: 2200;
                    min-width: 280px;
                    max-width: min(430px, 92vw);
                    border-radius: 12px;
                    border: 1px solid transparent;
                    padding: 10px 12px;
                    color: #fff;
                    box-shadow: 0 18px 45px rgba(0,0,0,.45);
                    opacity: 0;
                    transform: translateY(-10px);
                    pointer-events: none;
                    transition: opacity .2s ease, transform .2s ease;
                    font-size: 13px;
                    font-weight: 600;
                }

                .slot-toast.show { opacity: 1; transform: translateY(0); }
                .slot-toast.success { background: linear-gradient(180deg, #1c8f5b, #156f48); border-color: #24a469; }
                .slot-toast.error { background: linear-gradient(180deg, #9d2f2f, #7f2323); border-color: #c74a4a; }
                .slot-toast.info { background: linear-gradient(180deg, #2f4f80, #243e66); border-color: #4f78b3; }

                @media (max-width: 1200px){
                    #existingList .existing-grid { grid-template-columns: 1fr; }
                }

                @media (max-width: 768px){
                    #calendar tbody td { padding:10px; font-size:14px; }
                }
            </style>

            <div class="row">
                <div class="col-lg-8">
                    <div id="calendar" class="mb-3"></div>
                </div>
                <div class="col-lg-4">
                    <div class="card custom">
                        <div class="card-body">
                            <h6 class="card-title">Pending schedules</h6>
                            <p style="font-size: 14px">Schedules you added (not yet saved)</p>
                            <div id="pendingList" style="min-height:120px"></div>
                            <hr>
                            <h6 class="mb-2">Existing schedules (this month)</h6>
                            <div id="existingList" style="min-height:80px"></div>

                            <form id="saveForm" method="POST" action="{{ url('/admin/coaching/slot-capacities') }}">
                                @csrf
                                <input type="hidden" name="slots_json" id="slots_json">
                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <button id="saveAllBtn" class="btn btn-success btn-sm" type="submit" disabled>Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="hoursModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content custom">
                        <div class="modal-header">
                            <h5 class="modal-title">Pick hours for <span id="modalDateLabel"></span></h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="m-0">Choose hours (1-hour steps) for the selected date.</p>
                            <div class="mb-3" style="color:#888"><small>Past hours are disabled automatically. Click <strong>Add</strong> to queue this date + hours to the sidebar.</small></div>
                            <div id="hoursGrid" class="d-flex flex-wrap gap-2"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button id="addHoursBtn" type="button" class="btn btn-primary">Add</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div id="slotToast" class="slot-toast" role="status" aria-live="polite"></div>

@section('scripts')
<script>
    const year = {{ $year }};
    const month = {{ $month }};
    const existing = @json($slots);
    const booked = @json($booked ?? []);
    const sessionSuccess = @json(session('success'));

    let pendingEntries = {};
    let toastTimer = null;

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
        return slotAt.getTime() <= Date.now();
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
        table.className = 'table table-dark';
        table.style.background = 'transparent';

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
                    td.innerHTML = `<div class="small">${cell.day}</div>`;
                } else {
                    const iso = formatDateLocal(cell.date);
                    const has = existing[iso] && existing[iso].length > 0;
                    const hasBookings = booked[iso] && booked[iso].length > 0;
                    const now = new Date();
                    const isPast = cell.date < new Date(now.getFullYear(), now.getMonth(), now.getDate());

                    td.innerHTML = `<div class="d-flex justify-content-between align-items-center"><div><strong>${cell.day}</strong></div><div>${has ? '<span class="badge bg-success">' + existing[iso].length + '</span>' : ''}${hasBookings ? '<span title="Has bookings" style="display:inline-block;width:10px;height:10px;background:#dc3545;border-radius:50%;margin-left:8px"></span>' : ''}</div></div>`;
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
        btn.className = 'btn btn-outline-secondary btn-sm';
        btn.style.width = '70px';
        const label = ('0' + h).slice(-2) + ':00';
        btn.textContent = label;
        btn.dataset.hour = label;
        btn.addEventListener('click', () => {
            if (!btn.disabled) {
                btn.classList.toggle('btn-success');
                btn.classList.toggle('btn-outline-secondary');
            }
        });
        hoursGrid.appendChild(btn);
    }

    function openModalForDate(iso) {
        document.querySelectorAll('#hoursGrid button').forEach(b => {
            b.disabled = false;
            b.title = '';
            b.classList.remove('btn-danger', 'btn-dark', 'slot-hour-past', 'slot-hour-booked', 'btn-success');
            b.classList.add('btn-outline-secondary');
        });

        document.getElementById('modalDateLabel').textContent = iso;

        document.querySelectorAll('#hoursGrid button').forEach(b => {
            const hour = b.dataset.hour;
            const hourBooked = !!(booked[iso] && booked[iso].includes(hour));
            const hourPast = isPastHour(iso, hour);

            if (hourBooked) {
                b.disabled = true;
                b.classList.remove('btn-outline-secondary');
                b.classList.add('btn-danger', 'slot-hour-booked');
                b.title = 'Already booked';
            } else if (hourPast) {
                b.disabled = true;
                b.classList.remove('btn-outline-secondary');
                b.classList.add('btn-dark', 'slot-hour-past');
                b.title = 'This time has passed';
            }
        });

        if (existing[iso]) {
            existing[iso].forEach(h => {
                const b = Array.from(document.querySelectorAll('#hoursGrid button')).find(x => x.dataset.hour === h);
                if (b && !b.disabled) {
                    b.classList.remove('btn-outline-secondary');
                    b.classList.add('btn-success');
                }
            });
        }

        document.getElementById('hoursModal').dataset.currentDate = iso;
        const modal = new bootstrap.Modal(document.getElementById('hoursModal'));
        modal.show();
    }

    document.getElementById('addHoursBtn').addEventListener('click', function () {
        const iso = document.getElementById('hoursModal').dataset.currentDate;
        const hours = Array.from(document.querySelectorAll('#hoursGrid button.btn-success'))
            .map(b => b.dataset.hour)
            .filter(h => !isPastHour(iso, h));

        if (!iso || hours.length === 0) {
            showToast('Please pick at least one available future hour for the date', 'error');
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
            showToast('No pending schedules to save', 'error');
            return;
        }

        doAjaxSave(true);
    });

    function doAjaxSave(replace) {
        fetch('{{ url('/admin/coaching/slot-capacities') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ slots_json: pendingEntries, replace: replace })
        }).then(r => r.json()).then(data => {
            if (data && data.success) {
                Object.keys(data.updated || {}).forEach(d => { existing[d] = data.updated[d]; });
                Object.keys(pendingEntries).forEach(d => { delete pendingEntries[d]; });
                renderPending();
                buildCalendar(year, month);
                renderExisting();
                document.getElementById('saveAllBtn').disabled = true;
                showToast('Schedules saved successfully', 'success');
            } else {
                showToast('Save failed', 'error');
            }
        }).catch(err => {
            console.error(err);
            showToast('Network error saving schedules', 'error');
        });
    }

    function renderPending() {
        const list = document.getElementById('pendingList');
        list.innerHTML = '';

        const keys = Object.keys(pendingEntries).sort();
        if (keys.length === 0) {
            list.innerHTML = '<div>No pending schedules.</div>';
            return;
        }

        keys.forEach(d => {
            const div = document.createElement('div');
            div.className = 'pending-item';
            const hrs = pendingEntries[d].map(h => `<span class="badge bg-info text-white me-1">${h}</span>`).join('');
            div.innerHTML = `<div class="d-flex justify-content-between align-items-start"><div><strong>${d}</strong><div class="mt-1">${hrs}</div></div><div><button class="btn btn-sm btn-outline-danger">Remove</button></div></div>`;
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
            container.innerHTML = '<div style="font-size:14px">No saved schedules for this month.</div>';
            return;
        }

        const grid = document.createElement('div');
        grid.className = 'existing-grid';

        keys.forEach(d => {
            const div = document.createElement('div');
            div.className = 'existing-card';
            const hours = (existing[d] || []).slice().sort();
            const chips = hours.map(h => `<span class="existing-chip">${h} <button class="remove-hour" data-date="${d}" data-time="${h}" title="Delete ${h}">x</button></span>`).join('');

            div.innerHTML = `
                <div class="existing-head">
                    <div class="existing-date">${d}</div>
                    <button class="btn btn-sm btn-outline-danger delete-date" data-date="${d}">Delete</button>
                </div>
                <div class="existing-times">${chips || '<span class="text-muted">No slots</span>'}</div>
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
            if (!confirm('Delete ' + tm + ' on ' + dt + '?')) return;

            fetch('{{ url('/admin/coaching/slot-capacities/delete') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ date: dt, time: tm })
            }).then(r => r.json()).then(data => {
                if (data && data.success) {
                    existing[dt] = data.remaining || [];
                    if (existing[dt].length === 0) delete existing[dt];
                    renderExisting();
                    buildCalendar(year, month);
                    showToast('Deleted ' + tm + ' on ' + dt, 'info');
                } else {
                    showToast('Delete failed', 'error');
                }
            }).catch(err => {
                console.error(err);
                showToast('Network error deleting slot', 'error');
            });
            return;
        }

        if (removeDateBtn) {
            e.preventDefault();
            const date = removeDateBtn.dataset.date;
            if (!confirm('Delete all schedules for ' + date + '?')) return;

            fetch('{{ url('/admin/coaching/slot-capacities/delete') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ date: date })
            }).then(r => r.json()).then(data => {
                if (data && data.success) {
                    delete existing[date];
                    renderExisting();
                    buildCalendar(year, month);
                    showToast('Deleted all slots on ' + date, 'info');
                } else {
                    showToast('Delete failed', 'error');
                }
            }).catch(err => {
                console.error(err);
                showToast('Network error deleting slots', 'error');
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

@endsection
