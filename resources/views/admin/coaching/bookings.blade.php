@extends('layouts.admin')

@section('title', 'Booking Coaching')

@section('content')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('compro/css/admin-bookings.css') }}" />
        <style>
            .coaching-page {
                font-family: 'Inter', system-ui, -apple-system, sans-serif;
                background-color: #F8FAFC;
            }

            .animate-pulse-subtle {
                animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }
            @keyframes pulse {
                0%, 100% { opacity: 1; }
                50% { opacity: .5; }
            }

            .position-relative {
                position: relative !important;
            }

            /* Custom Popover with high Z-Index */
            .filter-popover {
                position: absolute;
                top: calc(100% + 8px);
                right: 0;
                background: #FFFFFF;
                border: 1px solid #CBD5E1;
                border-radius: 12px;
                padding: 18px;
                box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.18), 0 4px 6px -2px rgba(15, 23, 42, 0.05);
                z-index: 9999 !important;
                width: 300px;
                display: none;
            }

            .filter-popover.show {
                display: block !important;
            }

            /* Table Styling */
            .table-booking {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
            }

            .table-booking th {
                background: #F8FAFC;
                padding: 14px 20px;
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: #475569;
                border-bottom: 1px solid #E2E8F0;
            }

            .table-booking td {
                padding: 18px 20px;
                border-bottom: 1px solid #F1F5F9;
                vertical-align: middle;
                font-size: 13px;
                background: #FFFFFF;
            }

            .table-booking tr:last-child td {
                border-bottom: none;
            }

            .table-booking tr:hover td {
                background-color: #F8FAFC;
            }

            /* Time & Status Badges */
            .time-pill {
                background: #F1F5F9;
                color: #334155;
                border: 1px solid #CBD5E1;
                border-radius: 6px;
                padding: 3px 8px;
                font-size: 0.75rem;
                font-weight: 600;
                font-family: monospace;
            }

            /* Pending / Mendatang -> Kuning / Yellow Amber */
            .countdown-badge-pending {
                background: #FFFBEB !important;
                color: #B45309 !important;
                border: 1px solid #FDE68A !important;
                border-radius: 6px;
                padding: 5px 10px;
                font-size: 0.78rem;
                font-weight: 600;
                display: inline-flex !important;
                align-items: center !important;
                gap: 8px !important;
            }

            /* Berlangsung / Live Now -> Hijau / Emerald Green */
            .countdown-badge-live {
                background: #ECFDF5 !important;
                color: #047857 !important;
                border: 1px solid #A7F3D0 !important;
                border-radius: 6px;
                padding: 5px 10px;
                font-size: 0.78rem;
                font-weight: 700;
                display: inline-flex !important;
                align-items: center !important;
                gap: 8px !important;
            }

            /* Sesi Selesai -> Neutral Slate */
            .countdown-badge-done {
                background: #F8FAFC !important;
                color: #64748B !important;
                border: 1px solid #E2E8F0 !important;
                border-radius: 6px;
                padding: 5px 10px;
                font-size: 0.78rem;
                font-weight: 500;
                display: inline-flex !important;
                align-items: center !important;
                gap: 8px !important;
            }

            .badge-garansi {
                color: #4338CA;
                background-color: #EEF2FF;
                border: 1px solid #E0E7FF;
                border-radius: 6px;
                padding: 5px 10px;
                font-size: 0.78rem;
                font-weight: 600;
                display: inline-flex !important;
                align-items: center !important;
                gap: 8px !important;
            }

            .badge-bonus {
                color: #047857;
                background-color: #ECFDF5;
                border: 1px solid #D1FAE5;
                border-radius: 6px;
                padding: 5px 10px;
                font-size: 0.78rem;
                font-weight: 600;
                display: inline-flex !important;
                align-items: center !important;
                gap: 8px !important;
            }

            .badge-paid {
                color: #1E293B;
                background-color: #F8FAFC;
                border: 1px solid #E2E8F0;
                border-radius: 6px;
                padding: 5px 10px;
                font-size: 0.78rem;
                font-weight: 600;
                font-family: monospace;
                display: inline-flex !important;
                align-items: center !important;
                gap: 8px !important;
            }

            .badge-room {
                background: #F8FAFC;
                color: #334155;
                border: 1px solid #E2E8F0;
                border-radius: 6px;
                padding: 5px 12px;
                font-size: 0.78rem;
                font-weight: 600;
                display: inline-flex !important;
                align-items: center !important;
                gap: 8px !important;
            }

            .btn-session-pending {
                background: #F1F5F9;
                color: #64748B;
                border: 1px solid #CBD5E1;
                border-radius: 6px;
                padding: 5px 12px;
                font-size: 0.78rem;
                font-weight: 500;
                text-decoration: none;
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                gap: 8px !important;
            }

            .btn-icon-text {
                display: inline-flex !important;
                align-items: center !important;
                gap: 8px !important;
            }
        </style>
    @endpush

    <!-- Script loaded inline at top to guarantee handlers exist immediately -->
    <script>
        window.togglePopover = function(e, id) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }
            const targetPopover = document.getElementById(id);
            if (!targetPopover) return;

            const isShown = targetPopover.classList.contains('show') || targetPopover.style.display === 'block';

            // Close all popovers
            document.querySelectorAll('.filter-popover').forEach(function(p) {
                p.style.display = 'none';
                p.classList.remove('show');
            });

            // Toggle target popover
            if (!isShown) {
                targetPopover.style.display = 'block';
                targetPopover.classList.add('show');
            }
        };

        window.setPresetDate = function(preset) {
            const today = new Date();
            let fromDate = new Date();
            let toDate = new Date();
            
            if (preset === 'today') {
                fromDate = today;
                toDate = today;
            } else if (preset === '7days') {
                fromDate.setDate(today.getDate() - 7);
                toDate = today;
            } else if (preset === '30days') {
                fromDate.setDate(today.getDate() - 30);
                toDate = today;
            }

            const formatDate = function(d) {
                const year = d.getFullYear();
                const month = String(d.getMonth() + 1).padStart(2, '0');
                const day = String(d.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            };
            
            const fromEl = document.getElementById('popoverDateFrom');
            const toEl = document.getElementById('popoverDateTo');
            if (fromEl) fromEl.value = formatDate(fromDate);
            if (toEl) toEl.value = formatDate(toDate);
        };

        window.clearDates = function() {
            const fromEl = document.getElementById('popoverDateFrom');
            const toEl = document.getElementById('popoverDateTo');
            const hiddenFrom = document.getElementById('dateFromHidden');
            const hiddenTo = document.getElementById('dateToHidden');
            if (fromEl) fromEl.value = '';
            if (toEl) toEl.value = '';
            if (hiddenFrom) hiddenFrom.value = '';
            if (hiddenTo) hiddenTo.value = '';
            
            const form = document.getElementById('bookingFilterForm');
            if (form) form.submit();
        };

        window.applyPopoverDates = function() {
            const fromEl = document.getElementById('popoverDateFrom');
            const toEl = document.getElementById('popoverDateTo');
            const hiddenFrom = document.getElementById('dateFromHidden');
            const hiddenTo = document.getElementById('dateToHidden');
            
            if (hiddenFrom && fromEl) hiddenFrom.value = fromEl.value;
            if (hiddenTo && toEl) hiddenTo.value = toEl.value;
            
            const form = document.getElementById('bookingFilterForm');
            if (form) form.submit();
        };

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.filter-popover') && !e.target.closest('.popover-trigger-btn')) {
                document.querySelectorAll('.filter-popover').forEach(function(p) {
                    p.style.display = 'none';
                    p.classList.remove('show');
                });
            }
        });
    </script>

    <div class="coaching-page min-h-screen p-3 md:p-6">
        <div class="max-w-7xl mx-auto space-y-6">

            @include('admin.coaching._nav')

            <!-- Header Section -->
            <div class="d-flex flex-column flex-md-row md:items-start justify-content-between gap-3 mb-4">
                <div>
                    <div class="d-flex align-items-center gap-3 mb-1">
                        <h1 class="h4 fw-bold text-slate-900 tracking-tight mb-0" style="color: #0F172A; font-weight: 800; font-size: 1.5rem;">
                            Sesi Coaching Live 1-on-1
                        </h1>
                        <span class="px-2.5 py-0.5 rounded-pill bg-blue-100 text-blue-700 fw-semibold" style="font-size: 0.75rem; background: #DBEAFE; color: #1D4ED8;">
                            {{ $upcomingCount ?? 0 }} Active Sessions
                        </span>
                    </div>
                    <p class="text-slate-500 mb-0" style="color: #64748B; font-size: 0.875rem;">
                        Kelola pendaftaran, konfirmasi jam mengajar, dan link ruang video call murid.
                    </p>
                </div>
                <div>
                    <a href="{{ url('/admin/coaching/slot-capacities') }}" class="btn btn-outline-primary btn-icon-text justify-content-center px-4 py-2.5 rounded-lg fw-semibold shadow-xs" style="background: #EFF6FF; color: #1D4ED8; border: 1.5px solid #2563EB; font-size: 0.875rem; border-radius: 8px; gap: 8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        <span>Kelola Slot Jam</span>
                    </a>
                </div>
            </div>

            <!-- Controls & Filters Container -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 space-y-4 mb-4" style="border-radius: 12px; border: 1px solid #E2E8F0; overflow: visible;">
                <form method="GET" action="{{ url('/admin/coaching/bookings') }}" id="bookingFilterForm" class="m-0">
                    <input type="hidden" name="tab" value="{{ $tab }}" />
                    <input type="hidden" name="date_from" id="dateFromHidden" value="{{ request('date_from') }}" />
                    <input type="hidden" name="date_to" id="dateToHidden" value="{{ request('date_to') }}" />

                    <!-- Top Controls Row -->
                    <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">
                        
                        <!-- Tabs -->
                        <div class="d-flex p-1 bg-slate-100 rounded-lg w-fit border border-slate-200" style="background: #F1F5F9; border-radius: 8px; border: 1px solid #E2E8F0; width: fit-content; gap: 4px;">
                            <a href="{{ request()->fullUrlWithQuery(['tab' => 'upcoming']) }}" class="btn-icon-text px-3 py-2 rounded-md text-sm font-medium text-decoration-none transition-all {{ $tab === 'upcoming' ? 'bg-white shadow-sm text-blue-700' : 'text-slate-600 hover:text-slate-900' }}" style="gap: 8px; {{ $tab === 'upcoming' ? 'background: #FFFFFF; color: #1D4ED8; box-shadow: 0 1px 2px rgba(0,0,0,0.05); border-radius: 6px;' : 'color: #475569;' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                <span class="fw-semibold" style="font-size: 0.875rem;">Jadwal Aktif</span>
                                <span class="px-2 py-0.5 rounded-circle text-xs fw-bold {{ $tab === 'upcoming' ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-600' }}" style="{{ $tab === 'upcoming' ? 'background: #2563EB; color: #FFFFFF;' : 'background: #E2E8F0; color: #475569;' }} font-size: 0.72rem;">
                                    {{ $upcomingCount ?? 0 }}
                                </span>
                            </a>

                            <a href="{{ request()->fullUrlWithQuery(['tab' => 'history']) }}" class="btn-icon-text px-3 py-2 rounded-md text-sm font-medium text-decoration-none transition-all {{ $tab === 'history' ? 'bg-white shadow-sm text-blue-700' : 'text-slate-600 hover:text-slate-900' }}" style="gap: 8px; {{ $tab === 'history' ? 'background: #FFFFFF; color: #1D4ED8; box-shadow: 0 1px 2px rgba(0,0,0,0.05); border-radius: 6px;' : 'color: #475569;' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                <span class="fw-semibold" style="font-size: 0.875rem;">Riwayat Sesi</span>
                                <span class="px-2 py-0.5 rounded-circle text-xs fw-bold {{ $tab === 'history' ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-600' }}" style="{{ $tab === 'history' ? 'background: #2563EB; color: #FFFFFF;' : 'background: #E2E8F0; color: #475569;' }} font-size: 0.72rem;">
                                    {{ $historyCount ?? 0 }}
                                </span>
                            </a>
                        </div>

                        <!-- Actions Right -->
                        <div class="d-flex flex-wrap align-items-center gap-2.5">
                            
                            <!-- Date Picker Dropdown Button -->
                            <div class="position-relative">
                                <button type="button" onclick="window.togglePopover(event, 'datePopover')" class="popover-trigger-btn btn-icon-text px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors shadow-sm" style="border-radius: 8px; border: 1px solid #CBD5E1; color: #334155; height: 38px; font-size: 0.875rem; cursor: pointer; gap: 8px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #64748B;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                    <span id="dateRangeLabel">
                                        @if(request('date_from') && request('date_to'))
                                            {{ \Carbon\Carbon::parse(request('date_from'))->format('d M Y') }} — {{ \Carbon\Carbon::parse(request('date_to'))->format('d M Y') }}
                                        @elseif(request('date_from'))
                                            Dari {{ \Carbon\Carbon::parse(request('date_from'))->format('d M Y') }}
                                        @elseif(request('date_to'))
                                            Sampai {{ \Carbon\Carbon::parse(request('date_to'))->format('d M Y') }}
                                        @else
                                            22 Jul — 28 Jul 2026
                                        @endif
                                    </span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #94A3B8;"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                </button>

                                <!-- Date Range Popover -->
                                <div id="datePopover" class="filter-popover" onclick="event.stopPropagation()">
                                    <div class="d-flex justify-content-between align-items-center pb-2 mb-2 border-bottom">
                                        <span class="fw-bold text-slate-900" style="font-size: 0.85rem; color: #0F172A;">Pilih Rentang Tanggal</span>
                                        <button type="button" onclick="window.togglePopover(event, 'datePopover')" class="btn-close" style="font-size: 0.7rem;"></button>
                                    </div>
                                    <div>
                                        <div class="mb-2">
                                            <label class="d-block text-slate-500 font-semibold mb-1" style="font-size: 0.75rem; color: #64748B;">Tanggal Mulai</label>
                                            <input type="date" id="popoverDateFrom" value="{{ request('date_from') }}" class="form-control form-control-sm" style="border-radius: 6px; background: #F8FAFC; font-size: 0.8rem;" />
                                        </div>
                                        <div class="mb-2">
                                            <label class="d-block text-slate-500 font-semibold mb-1" style="font-size: 0.75rem; color: #64748B;">Tanggal Akhir</label>
                                            <input type="date" id="popoverDateTo" value="{{ request('date_to') }}" class="form-control form-control-sm" style="border-radius: 6px; background: #F8FAFC; font-size: 0.8rem;" />
                                        </div>
                                        <div class="d-flex flex-wrap gap-1 mb-3 pt-1">
                                            <button type="button" onclick="window.setPresetDate('today')" class="btn btn-xs btn-outline-secondary" style="font-size: 0.7rem; padding: 2px 6px;">Hari Ini</button>
                                            <button type="button" onclick="window.setPresetDate('7days')" class="btn btn-xs btn-outline-secondary" style="font-size: 0.7rem; padding: 2px 6px;">7 Hari Terakhir</button>
                                            <button type="button" onclick="window.setPresetDate('30days')" class="btn btn-xs btn-outline-secondary" style="font-size: 0.7rem; padding: 2px 6px;">30 Hari Terakhir</button>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                            <button type="button" onclick="window.clearDates()" class="btn btn-sm btn-link text-danger p-0 text-decoration-none" style="font-size: 0.75rem;">Reset Tanggal</button>
                                            <div class="d-flex gap-1.5">
                                                <button type="button" onclick="window.togglePopover(event, 'datePopover')" class="btn btn-sm btn-light font-semibold" style="border-radius: 6px; font-size: 0.78rem;">Batal</button>
                                                <button type="button" onclick="window.applyPopoverDates()" class="btn btn-sm btn-primary font-semibold" style="border-radius: 6px; background: #2563EB; font-size: 0.78rem;">Terapkan</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Filter Button & Popover -->
                            @php
                                $selectedTicketTypes = (array) request('ticket_types', []);
                                $hasTicketTypeFilter = count($selectedTicketTypes) > 0;
                                $selectedStatus = request('status');
                                
                                $activeFilterCount = 0;
                                if ($hasTicketTypeFilter) $activeFilterCount += count($selectedTicketTypes);
                                if ($selectedStatus) $activeFilterCount += 1;
                                if (request('date_from') || request('date_to')) $activeFilterCount += 1;
                                if (request('q')) $activeFilterCount += 1;
                            @endphp

                            <div class="position-relative">
                                <button type="button" onclick="window.togglePopover(event, 'filterPopover')" class="popover-trigger-btn btn-icon-text px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors shadow-sm" style="border-radius: 8px; border: 1px solid #CBD5E1; color: #334155; height: 38px; font-size: 0.875rem; cursor: pointer; gap: 8px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #64748B;"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                                    <span>Filter</span>
                                    @if($activeFilterCount > 0)
                                        <span class="d-flex align-items-center justify-content-center bg-blue-600 text-white rounded-circle ms-1" style="width: 20px; height: 20px; background: #2563EB; font-size: 0.72rem; font-weight: 700;">
                                            {{ $activeFilterCount }}
                                        </span>
                                    @endif
                                </button>

                                <!-- Filter Popover -->
                                <div id="filterPopover" class="filter-popover" onclick="event.stopPropagation()">
                                    <span class="fw-bold text-slate-900 d-block mb-2" style="font-size: 0.85rem; color: #0F172A;">Filter Tipe Pembayaran</span>
                                    <div class="space-y-2 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="ticket_types[]" value="warranty" id="fGaransi" {{ in_array('warranty', $selectedTicketTypes) ? 'checked' : '' }}>
                                            <label class="form-check-label text-slate-700" style="font-size: 0.8rem;" for="fGaransi">Garansi Coaching</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="ticket_types[]" value="free" id="fBonus" {{ in_array('free', $selectedTicketTypes) ? 'checked' : '' }}>
                                            <label class="form-check-label text-slate-700" style="font-size: 0.8rem;" for="fBonus">Bonus Registrasi</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="ticket_types[]" value="paid" id="fPaid" {{ in_array('paid', $selectedTicketTypes) ? 'checked' : '' }}>
                                            <label class="form-check-label text-slate-700" style="font-size: 0.8rem;" for="fPaid">Sesi Berbayar (Midtrans)</label>
                                        </div>
                                    </div>

                                    <span class="fw-bold text-slate-900 d-block mb-2 pt-2 border-top" style="font-size: 0.85rem; color: #0F172A;">Filter Status</span>
                                    <div class="mb-3">
                                        <select name="status" class="form-select form-select-sm" style="font-size: 0.8rem; border-radius: 6px;">
                                            <option value="">Semua Status</option>
                                            <option value="accepted" {{ request('status') === 'accepted' || request('status') === 'approved' ? 'selected' : '' }}>Disetujui (Accepted)</option>
                                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak (Rejected)</option>
                                        </select>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                        <a href="{{ url('/admin/coaching/bookings') }}?tab={{ $tab }}" class="text-slate-500 text-decoration-none font-bold" style="font-size: 0.78rem;">Reset All</a>
                                        <button type="submit" class="btn btn-sm btn-primary font-bold" style="border-radius: 6px; background: #2563EB; font-size: 0.78rem;">Simpan Filter</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Refresh Button -->
                            <a href="{{ url('/admin/coaching/bookings') }}?tab={{ $tab }}" class="d-flex align-items-center justify-content-center p-2 bg-white border border-slate-300 rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-50 transition-colors shadow-sm text-decoration-none" style="border-radius: 8px; border: 1px solid #CBD5E1; width: 38px; height: 38px; color: #64748B;" title="Reset Semua Filter">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.2"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Bottom Controls Row: Search & Active Filters -->
                    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 pt-3 border-top mt-3" style="border-top-color: #F1F5F9 !important;">
                        
                        <!-- Search Input -->
                        <div class="position-relative w-100" style="max-width: 380px;">
                            <div class="position-absolute h-100 d-flex align-items-center ps-3 pointer-events-none" style="left: 0; top: 0;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #94A3B8;"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            </div>
                            <input 
                                type="text" 
                                name="q"
                                value="{{ request('q') }}"
                                class="form-control w-100 shadow-none" 
                                style="padding-left: 42px !important; border-radius: 8px; border: 1px solid #CBD5E1; height: 38px; font-size: 0.85rem; background: #FFFFFF;"
                                placeholder="Cari nama murid, email, atau room ID..." 
                            />
                        </div>

                        <!-- Active Filters Display Bar -->
                        <div class="d-flex align-items-center gap-2 flex-wrap" style="font-size: 0.85rem;">
                            @if($activeFilterCount > 0)
                                <span class="text-slate-500 font-medium" style="color: #64748B; font-weight: 500;">Filter Aktif:</span>

                                @if(request('q'))
                                    <span class="btn-icon-text px-2.5 py-1 rounded-md bg-blue-50 text-blue-700 border border-blue-100 font-medium" style="background: #EFF6FF; color: #1D4ED8; border: 1px solid #DBEAFE; border-radius: 6px; font-size: 0.75rem; font-weight: 600; gap: 6px;">
                                        <span>Cari: "{{ request('q') }}"</span>
                                        <a href="{{ request()->fullUrlWithQuery(['q' => null]) }}" class="text-blue-700 hover:text-blue-900 ms-1 text-decoration-none">×</a>
                                    </span>
                                @endif

                                @if(request('status'))
                                    <span class="btn-icon-text px-2.5 py-1 rounded-md bg-blue-50 text-blue-700 border border-blue-100 font-medium" style="background: #EFF6FF; color: #1D4ED8; border: 1px solid #DBEAFE; border-radius: 6px; font-size: 0.75rem; font-weight: 600; gap: 6px;">
                                        <span>Status: {{ ucfirst(request('status')) }}</span>
                                        <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="text-blue-700 hover:text-blue-900 ms-1 text-decoration-none">×</a>
                                    </span>
                                @endif

                                @if(request('date_from') || request('date_to'))
                                    <span class="btn-icon-text px-2.5 py-1 rounded-md bg-slate-100 text-slate-700 border border-slate-200 font-medium" style="background: #F1F5F9; color: #334155; border: 1px solid #E2E8F0; border-radius: 6px; font-size: 0.75rem; font-weight: 600; gap: 6px;">
                                        <span>Tanggal: {{ request('date_from') ?: 'Awal' }} s/d {{ request('date_to') ?: 'Akhir' }}</span>
                                        <a href="{{ request()->fullUrlWithQuery(['date_from' => null, 'date_to' => null]) }}" class="text-slate-700 hover:text-slate-900 ms-1 text-decoration-none">×</a>
                                    </span>
                                @endif

                                @foreach($selectedTicketTypes as $tt)
                                    @php
                                        $ttName = $tt === 'warranty' ? 'Garansi Coaching' : ($tt === 'free' ? 'Bonus Registrasi' : 'Berbayar');
                                    @endphp
                                    <span class="btn-icon-text px-2.5 py-1 rounded-md bg-purple-50 text-purple-700 border border-purple-200 font-medium" style="background: #FAF5FF; color: #7E22CE; border: 1px solid #E9D5FF; border-radius: 6px; font-size: 0.75rem; font-weight: 600; gap: 6px;">
                                        <span>Tipe: {{ $ttName }}</span>
                                        <a href="{{ request()->fullUrlWithQuery(['ticket_types' => array_diff($selectedTicketTypes, [$tt])]) }}" class="text-purple-700 hover:text-purple-900 ms-1 text-decoration-none">×</a>
                                    </span>
                                @endforeach

                                <a href="{{ url('/admin/coaching/bookings') }}?tab={{ $tab }}" class="text-xs text-danger font-semibold ms-1 text-decoration-none" style="font-size: 0.75rem;">Hapus Semua Filter</a>
                            @else
                                <span class="text-slate-400 font-medium" style="color: #94A3B8; font-size: 0.78rem;">Menampilkan semua data</span>
                            @endif
                        </div>
                    </div>

                </form>
            </div>

            <!-- List Section -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden" style="border-radius: 12px; border: 1px solid #E2E8F0;">
                <div class="table-responsive">
                    <table class="table-booking mb-0">
                        <thead>
                            <tr>
                                <th style="width: 22%;">MURID / STUDENT</th>
                                <th style="width: 28%;">WAKTU SESI & COUNTDOWN</th>
                                <th style="width: 25%;">TIKET / GARANSI</th>
                                <th style="width: 25%;">RUANG VIDEO CALL</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($bookings as $b)
                            @php
                                $bt = \Carbon\Carbon::parse($b->booking_time);
                                $sessionLength = (int) ($b->session_duration_minutes ?? config('coaching.session_length_minutes', 60));
                                $sessionStart = $bt->copy();
                                $sessionEnd = $bt->copy()->addMinutes($sessionLength);
                                $isPastByTime = $sessionEnd->lt(now());
                                $hasMeetingFinished = in_array(strtolower((string) $b->status), ['ended', 'finished', 'completed'], true);
                                
                                $studentUser = optional($b->user);
                                $userName = $studentUser->name ?? 'Guest User';
                                $userEmail = $studentUser->email ?? '-';
                                $userInitial = strtoupper(substr($userName, 0, 1));
                                $studentPhoto = $studentUser->id ? $studentUser->photoUrl() : null;
                                
                                $sessionUrl = url('/coaching/session/'.$b->id);
                                $btLocal = $bt->format('Y-m-d H:i:s');
                                $btIso = $bt->toIso8601String();
                                
                                $rawSource = (string) (optional($b->ticket)->source ?? '');
                                $cleanSource = preg_replace('/^midtrans:/i', '', $rawSource);

                                $avatarBgs = ['#2563EB', '#4F46E5', '#0891B2', '#7C3AED', '#059669'];
                                $avatarBg = $avatarBgs[$loop->index % count($avatarBgs)];
                            @endphp
                            <tr>
                                <!-- Student Column -->
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        @if($studentPhoto)
                                            <img 
                                                src="{{ $studentPhoto }}" 
                                                alt="{{ $userName }}" 
                                                class="rounded-circle flex-shrink-0 object-fit-cover shadow-xs"
                                                style="width: 42px; height: 42px; object-fit: cover; border: 1px solid #E2E8F0;" 
                                            />
                                        @else
                                            <div class="rounded-circle text-white fw-bold d-flex align-items-center justify-content-center shadow-xs flex-shrink-0" style="width: 42px; height: 42px; background: {{ $avatarBg }}; font-size: 1rem;">
                                                {{ $userInitial }}
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <div class="fw-bold text-slate-900 text-truncate" style="color: #0F172A; font-size: 0.9rem;">
                                                {{ $userName }}
                                            </div>
                                            <div class="text-slate-500 text-truncate" style="color: #64748B; font-size: 0.8rem; margin-top: 2px;">
                                                {{ $userEmail }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Time Column -->
                                <td>
                                    <div class="d-flex flex-column gap-2">
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <span class="fw-bold text-slate-900" style="color: #0F172A; font-size: 0.9rem;">{{ $bt->translatedFormat('j F Y') }}</span>
                                            <span class="time-pill">
                                                {{ $bt->format('H:i') }} WIB
                                            </span>
                                        </div>
                                        @if(! $isPastByTime && ! $hasMeetingFinished)
                                            <div>
                                                <span class="countdown-badge-pending countdown-container">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="animate-pulse-subtle flex-shrink-0"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                                    <span class="countdown" data-time-iso="{{ $btIso }}">Menghitung...</span>
                                                </span>
                                            </div>
                                        @else
                                            <div>
                                                <span class="countdown-badge-done">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                                    <span>Sesi Selesai</span>
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Ticket Column -->
                                <td>
                                    <div class="d-flex flex-column gap-1.5">
                                        @if(strtolower($rawSource) === 'warranty' || str_contains(strtolower($rawSource), 'warranty'))
                                            <div>
                                                <span class="badge-garansi">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                                                    <span>Garansi Coaching</span>
                                                </span>
                                            </div>
                                            <div class="text-slate-500" style="font-size: 0.8rem; color: #64748B;">
                                                Klaim Garansi Bebas Biaya
                                            </div>
                                        @elseif(strtolower($rawSource) === 'free_on_register' || str_contains(strtolower($rawSource), 'free'))
                                            <div>
                                                <span class="badge-bonus">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0"><polyline points="20 12 20 22 4 22 4 12"></polyline><rect x="2" y="7" width="20" height="5"></rect><line x1="12" y1="22" x2="12" y2="7"></line><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"></path><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"></path></svg>
                                                    <span>Bonus Registrasi</span>
                                                </span>
                                            </div>
                                            <div class="text-slate-500" style="font-size: 0.8rem; color: #64748B;">
                                                Sesi Gratis Pendaftaran
                                            </div>
                                        @else
                                            <div>
                                                <span class="badge-paid text-truncate" style="max-width: 220px;" title="#{{ $cleanSource }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                                                    <span>#{{ $cleanSource }}</span>
                                                </span>
                                            </div>
                                            <div class="text-slate-500 d-flex align-items-center gap-1" style="font-size: 0.8rem;">
                                                <span class="fw-bold text-emerald-600" style="color: #059669;">Rp 50.000</span>
                                                <span style="color: #64748B;">• Paid</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Room Column -->
                                <td>
                                    <div class="d-flex flex-column gap-1.5" style="max-width: 200px;">
                                        @if($b->twilio_room_sid)
                                            <div class="badge-room justify-content-center mb-1" title="Room SID: {{ $b->twilio_room_sid }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>
                                                <span>Room #{{ substr($b->twilio_room_sid, 0, 8) }}</span>
                                            </div>
                                        @endif
                                        <a class="btn-session-pending open-session-btn w-100 justify-content-center d-flex align-items-center gap-2" data-booking-time="{{ $btLocal }}" data-href="{{ $sessionUrl }}" target="_blank" href="{{ $sessionUrl }}" style="font-size: 0.78rem; border-radius: 8px; padding: 7px 12px; font-weight: 600; text-decoration: none; transition: all 0.2s ease-in-out; pointer-events: none; opacity: 0.6; background: #F1F5F9; color: #64748B; border: 1px solid #CBD5E1;">
                                            <i class="fa-regular fa-clock text-xs"></i>
                                            <span>Belum Dimulai</span>
                                        </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5" style="color: #64748B;">
                                    <i class="fa-regular fa-calendar-xmark display-6 d-block mb-2" style="color: #94A3B8;"></i>
                                    Tidak ada data jadwal {{ $tab === 'upcoming' ? 'aktif / mendatang' : 'riwayat' }}.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Footer of the List -->
                <div class="px-4 py-3.5 bg-slate-50 border-top border-slate-200 text-sm text-slate-600 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2" style="background: #F8FAFC; border-top: 1px solid #E2E8F0; color: #475569; font-size: 0.85rem;">
                    <div>
                        Menampilkan <span class="fw-semibold text-slate-900" style="color: #0F172A; font-weight: 700;">{{ $bookings->count() }}</span> jadwal coaching
                    </div>
                    <div>
                        {{ $bookings->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Countdown & Room Status Script -->
    <script>
        (function () {
            function formatDelta(ms) {
                if (ms <= 0) return 'Live Sesi Sekarang';
                const total = Math.floor(ms / 1000);
                const days = Math.floor(total / 86400);
                const hours = Math.floor((total % 86400) / 3600);
                const mins = Math.floor((total % 3600) / 60);
                const parts = [];
                if (days > 0) parts.push(days + ' hr');
                if (hours > 0) parts.push(hours + ' jam');
                if (mins > 0 || parts.length === 0) parts.push(mins + ' min');
                return parts.join(' ') + ' lagi';
            }

            function updateCountdowns() {
                const now = Date.now();
                document.querySelectorAll('.countdown-container').forEach(function (container) {
                    const el = container.querySelector('.countdown');
                    if (!el) return;
                    const isoStr = el.getAttribute('data-time-iso');
                    if (!isoStr) return;
                    const targetMs = new Date(isoStr).getTime();
                    if (isNaN(targetMs)) return;

                    const delta = targetMs - now;

                    if (delta > 0) {
                        // Pending / Mendatang -> Kuning (Amber)
                        container.className = 'countdown-badge-pending countdown-container';
                        el.textContent = formatDelta(delta);
                    } else {
                        // Live / Sedang Berlangsung -> Hijau (Emerald Green)
                        container.className = 'countdown-badge-live countdown-container';
                        el.textContent = 'Live Sesi Sekarang';
                    }
                });
            }

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
                    const earlyAccessMs = startMs - (15 * 60 * 1000); // 15 menit sebelum sesi
                    const endWindow = startMs + (60 * 60 * 1000); // 1 jam durasi sesi

                    if (now >= earlyAccessMs && now <= endWindow) {
                        btn.innerHTML = '<i class="fa-solid fa-video text-xs"></i> <span>Masuk Sesi Video</span>';
                        btn.style.background = 'linear-gradient(135deg, #10B981, #059669)';
                        btn.style.color = '#FFFFFF';
                        btn.style.border = 'none';
                        btn.style.boxShadow = '0 4px 12px rgba(16, 185, 129, 0.3)';
                        btn.style.fontWeight = '700';
                        btn.style.gap = '8px';
                        btn.removeAttribute('aria-disabled');
                        if (href) btn.setAttribute('href', href);
                        btn.style.pointerEvents = 'auto';
                        btn.style.opacity = '1';
                        btn.classList.add('animate-pulse');
                    } else if (now < earlyAccessMs) {
                        btn.innerHTML = '<i class="fa-regular fa-clock text-xs"></i> <span>Belum Dimulai</span>';
                        btn.style.background = '#F1F5F9';
                        btn.style.color = '#64748B';
                        btn.style.border = '1px solid #CBD5E1';
                        btn.style.fontWeight = '600';
                        btn.style.gap = '8px';
                        btn.style.boxShadow = 'none';
                        btn.setAttribute('aria-disabled', 'true');
                        btn.removeAttribute('href');
                        btn.style.pointerEvents = 'none';
                        btn.style.opacity = '0.6';
                        btn.classList.remove('animate-pulse');
                    } else {
                        btn.innerHTML = '<i class="fa-solid fa-check text-xs"></i> <span>Sesi Selesai</span>';
                        btn.style.background = '#F8FAFC';
                        btn.style.color = '#94A3B8';
                        btn.style.border = '1px solid #E2E8F0';
                        btn.style.fontWeight = '600';
                        btn.style.gap = '8px';
                        btn.style.boxShadow = 'none';
                        btn.setAttribute('aria-disabled', 'true');
                        btn.removeAttribute('href');
                        btn.style.pointerEvents = 'none';
                        btn.style.opacity = '0.5';
                        btn.classList.remove('animate-pulse');
                    }
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                updateCountdowns();
                updateOpenSessionButtons();
            });

            // Immediate execution
            updateCountdowns();
            updateOpenSessionButtons();

            setInterval(function() {
                updateCountdowns();
                updateOpenSessionButtons();
            }, 1000);
        })();
    </script>
@endsection
