@extends('layouts.admin')

@section('title', 'Riwayat Transaksi')

@section('content')
    @push('styles')
        <style>
            .transactions-page {
                font-family: 'Inter', system-ui, -apple-system, sans-serif;
                background-color: #F8FAFC;
            }

            .table-txns {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
            }

            .table-txns th {
                background: #F8FAFC;
                padding: 14px 20px;
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: #475569;
                border-bottom: 1px solid #E2E8F0;
            }

            .table-txns td {
                padding: 16px 20px;
                border-bottom: 1px solid #F1F5F9;
                vertical-align: middle;
                font-size: 13px;
                background: #FFFFFF;
            }

            .table-txns tr:last-child td {
                border-bottom: none;
            }

            .table-txns tr:hover td {
                background-color: #F8FAFC;
            }

            .badge-status-success {
                background: #ECFDF5;
                color: #047857;
                border: 1px solid #A7F3D0;
                border-radius: 6px;
                padding: 4px 10px;
                font-size: 0.75rem;
                font-weight: 700;
                display: inline-flex;
                align-items: center;
                gap: 6px;
            }

            .badge-status-pending {
                background: #FFFBEB;
                color: #B45309;
                border: 1px solid #FDE68A;
                border-radius: 6px;
                padding: 4px 10px;
                font-size: 0.75rem;
                font-weight: 700;
                display: inline-flex;
                align-items: center;
                gap: 6px;
            }

            .badge-status-failed {
                background: #FEF2F2;
                color: #DC2626;
                border: 1px solid #FCA5A5;
                border-radius: 6px;
                padding: 4px 10px;
                font-size: 0.75rem;
                font-weight: 700;
                display: inline-flex;
                align-items: center;
                gap: 6px;
            }

            .filter-popover {
                position: absolute;
                top: calc(100% + 6px);
                left: 0;
                background: #ffffff;
                border: 1px solid #E2E8F0;
                border-radius: 12px;
                box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.15);
                padding: 16px;
                z-index: 9999;
                min-width: 280px;
                display: none;
            }
            .filter-popover.show {
                display: block;
            }

            .btn-icon-text {
                display: inline-flex !important;
                align-items: center !important;
                gap: 8px !important;
            }
        </style>
    @endpush

    <script>
        window.togglePopover = function(e, id) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }
            const targetPopover = document.getElementById(id);
            if (!targetPopover) return;

            const isShown = targetPopover.classList.contains('show') || targetPopover.style.display === 'block';

            document.querySelectorAll('.filter-popover').forEach(function(p) {
                p.style.display = 'none';
                p.classList.remove('show');
            });

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
            } else if (preset === 'week') {
                const day = today.getDay();
                const diff = (day === 0) ? -6 : (1 - day);
                fromDate = new Date(today);
                fromDate.setDate(today.getDate() + diff);
                toDate = new Date(fromDate);
                toDate.setDate(fromDate.getDate() + 6);
            } else if (preset === 'month') {
                fromDate = new Date(today.getFullYear(), today.getMonth(), 1);
                toDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
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
            const hiddenFrom = document.getElementById('filterFromHidden');
            const hiddenTo = document.getElementById('filterToHidden');
            if (fromEl) fromEl.value = '';
            if (toEl) toEl.value = '';
            if (hiddenFrom) hiddenFrom.value = '';
            if (hiddenTo) hiddenTo.value = '';
            
            const form = document.getElementById('txnFilterForm');
            if (form) form.submit();
        };

        window.applyPopoverDates = function() {
            const fromEl = document.getElementById('popoverDateFrom');
            const toEl = document.getElementById('popoverDateTo');
            const hiddenFrom = document.getElementById('filterFromHidden');
            const hiddenTo = document.getElementById('filterToHidden');
            
            if (hiddenFrom && fromEl) hiddenFrom.value = fromEl.value;
            if (hiddenTo && toEl) hiddenTo.value = toEl.value;
            
            const form = document.getElementById('txnFilterForm');
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

    <div class="transactions-page min-h-screen p-3 md:p-6">
        <div class="max-w-7xl mx-auto space-y-6">

            <!-- Header Section -->
            <div class="d-flex flex-column flex-md-row md:items-start justify-content-between gap-3 mb-4">
                <div>
                    <div class="d-flex align-items-center gap-3 mb-1">
                        <h1 class="h4 fw-bold text-slate-900 tracking-tight mb-0" style="color: #0F172A; font-weight: 800; font-size: 1.5rem;">
                            Riwayat Transaksi & Pembayaran
                        </h1>
                        <span class="px-2.5 py-0.5 rounded-pill bg-blue-100 text-blue-700 fw-semibold" style="font-size: 0.75rem; background: #DBEAFE; color: #1D4ED8;">
                            Total {{ $txns->total() ?? $txns->count() }} Transaksi
                        </span>
                    </div>
                    <p class="text-slate-500 mb-0" style="color: #64748B; font-size: 0.875rem;">
                        Pantau status pembayaran Midtrans, order ID, paket LMS, dan tanggal transaksi murid.
                    </p>
                </div>
            </div>

            <!-- Controls & Filters Container (New Clean Popover Filter Model) -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 mb-4" style="border-radius: 12px; border: 1px solid #E2E8F0; overflow: visible;">
                <form id="txnFilterForm" method="GET" action="{{ route('admin.transactions.index') }}" class="m-0">
                    <input type="hidden" name="from" id="filterFromHidden" value="{{ request('from') }}" />
                    <input type="hidden" name="to" id="filterToHidden" value="{{ request('to') }}" />

                    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                        
                        <!-- Left Actions: Date Popover, Filter Popover, Refresh -->
                        <div class="d-flex flex-wrap align-items-center gap-2.5">
                            
                            <!-- Date Range Popover Button -->
                            <div class="position-relative">
                                <button type="button" onclick="window.togglePopover(event, 'txnDatePopover')" class="popover-trigger-btn btn-icon-text px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors shadow-sm" style="border-radius: 8px; border: 1px solid #CBD5E1; color: #334155; height: 38px; font-size: 0.875rem; cursor: pointer; gap: 8px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #64748B;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                    <span>
                                        @if(request('from') && request('to'))
                                            {{ \Carbon\Carbon::parse(request('from'))->format('d M Y') }} — {{ \Carbon\Carbon::parse(request('to'))->format('d M Y') }}
                                        @elseif(request('from'))
                                            Dari {{ \Carbon\Carbon::parse(request('from'))->format('d M Y') }}
                                        @elseif(request('to'))
                                            Sampai {{ \Carbon\Carbon::parse(request('to'))->format('d M Y') }}
                                        @else
                                            Semua Tanggal
                                        @endif
                                    </span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #94A3B8;"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                </button>

                                <!-- Date Range Popover Box -->
                                <div id="txnDatePopover" class="filter-popover" onclick="event.stopPropagation()">
                                    <div class="d-flex justify-content-between align-items-center pb-2 mb-2 border-bottom">
                                        <span class="fw-bold text-slate-900" style="font-size: 0.85rem; color: #0F172A;">Pilih Rentang Tanggal</span>
                                        <button type="button" onclick="window.togglePopover(event, 'txnDatePopover')" class="btn-close" style="font-size: 0.7rem;"></button>
                                    </div>
                                    <div>
                                        <div class="mb-2">
                                            <label class="d-block text-slate-500 font-semibold mb-1" style="font-size: 0.75rem; color: #64748B;">Dari Tanggal</label>
                                            <input type="date" id="popoverDateFrom" value="{{ request('from') }}" class="form-control form-control-sm" style="border-radius: 6px; background: #F8FAFC; font-size: 0.8rem;" />
                                        </div>
                                        <div class="mb-2">
                                            <label class="d-block text-slate-500 font-semibold mb-1" style="font-size: 0.75rem; color: #64748B;">Sampai Tanggal</label>
                                            <input type="date" id="popoverDateTo" value="{{ request('to') }}" class="form-control form-control-sm" style="border-radius: 6px; background: #F8FAFC; font-size: 0.8rem;" />
                                        </div>
                                        <div class="d-flex flex-wrap gap-1 mb-3 pt-1">
                                            <button type="button" onclick="window.setPresetDate('today')" class="btn btn-xs btn-outline-secondary" style="font-size: 0.7rem; padding: 2px 6px;">Hari Ini</button>
                                            <button type="button" onclick="window.setPresetDate('week')" class="btn btn-xs btn-outline-secondary" style="font-size: 0.7rem; padding: 2px 6px;">Minggu Ini</button>
                                            <button type="button" onclick="window.setPresetDate('month')" class="btn btn-xs btn-outline-secondary" style="font-size: 0.7rem; padding: 2px 6px;">Bulan Ini</button>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                            <button type="button" onclick="window.clearDates()" class="btn btn-sm btn-link text-danger p-0 text-decoration-none" style="font-size: 0.75rem;">Reset</button>
                                            <div class="d-flex gap-1.5">
                                                <button type="button" onclick="window.togglePopover(event, 'txnDatePopover')" class="btn btn-sm btn-light font-semibold" style="border-radius: 6px; font-size: 0.78rem;">Batal</button>
                                                <button type="button" onclick="window.applyPopoverDates()" class="btn btn-sm btn-primary font-semibold" style="border-radius: 6px; background: #2563EB; font-size: 0.78rem;">Terapkan</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Filter Dropdown Button & Popover -->
                            @php
                                $selectedStatus = request('status');
                                $activeFilters = 0;
                                if ($selectedStatus) $activeFilters++;
                                if (request('from') || request('to')) $activeFilters++;
                                if (request('q')) $activeFilters++;
                            @endphp
                            <div class="position-relative">
                                <button type="button" onclick="window.togglePopover(event, 'txnFilterPopover')" class="popover-trigger-btn btn-icon-text px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors shadow-sm" style="border-radius: 8px; border: 1px solid #CBD5E1; color: #334155; height: 38px; font-size: 0.875rem; cursor: pointer; gap: 8px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #64748B;"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                                    <span>Filter</span>
                                    @if($activeFilters > 0)
                                        <span class="d-flex align-items-center justify-content-center bg-blue-600 text-white rounded-circle ms-1" style="width: 20px; height: 20px; background: #2563EB; font-size: 0.72rem; font-weight: 700;">
                                            {{ $activeFilters }}
                                        </span>
                                    @endif
                                </button>

                                <!-- Filter Popover Box -->
                                <div id="txnFilterPopover" class="filter-popover" onclick="event.stopPropagation()">
                                    <span class="fw-bold text-slate-900 d-block mb-2" style="font-size: 0.85rem; color: #0F172A;">Filter Status Transaksi</span>
                                    <div class="mb-3">
                                        <select name="status" class="form-select form-select-sm" style="border-radius: 6px; font-size: 0.8rem; background: #F8FAFC;">
                                            <option value="">Semua Status</option>
                                            <option value="settlement" {{ ($selectedStatus==='settlement') ? 'selected' : '' }}>Settlement (Berhasil)</option>
                                            <option value="pending" {{ ($selectedStatus==='pending') ? 'selected' : '' }}>Pending</option>
                                            <option value="expire" {{ (in_array($selectedStatus, ['expire', 'cancel', 'failed'])) ? 'selected' : '' }}>Batal / Expire</option>
                                        </select>
                                    </div>
                                    <div class="d-flex justify-content-end gap-1.5 pt-2 border-top">
                                        <button type="button" onclick="window.togglePopover(event, 'txnFilterPopover')" class="btn btn-sm btn-light font-semibold" style="border-radius: 6px; font-size: 0.78rem;">Batal</button>
                                        <button type="submit" class="btn btn-sm btn-primary font-semibold" style="border-radius: 6px; background: #2563EB; font-size: 0.78rem;">Terapkan</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Refresh / Reset Button -->
                            <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center justify-content-center p-2 rounded-lg text-slate-700 bg-white border-slate-300" style="border-radius: 8px; border: 1px solid #CBD5E1; width: 38px; height: 38px; color: #475569;" title="Reset Filter">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
                            </a>

                        </div>

                        <!-- Search Order ID Input -->
                        <div class="d-flex align-items-center gap-2">
                            <div class="position-relative w-100" style="min-width: 240px;">
                                <div class="position-absolute h-100 d-flex align-items-center ps-3 pointer-events-none" style="left: 0; top: 0;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #94A3B8;"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                </div>
                                <input 
                                    type="text" 
                                    name="q" 
                                    value="{{ request('q') }}" 
                                    placeholder="Cari order ID..." 
                                    class="form-control form-control-sm shadow-none" 
                                    style="padding-left: 38px !important; border-radius: 8px; border: 1px solid #CBD5E1; height: 38px; font-size: 0.85rem;"
                                />
                            </div>
                            <button class="btn btn-sm btn-primary font-semibold px-3" type="submit" style="background: #2563EB; border-radius: 8px; height: 38px; font-size: 0.85rem;">
                                Filter
                            </button>
                        </div>

                    </div>
                </form>
            </div>

            <!-- List Section Card -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden" style="border-radius: 12px; border: 1px solid #E2E8F0;">
                <div class="table-responsive">
                    <table class="table-txns mb-0">
                        <thead>
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 25%;">ORDER ID</th>
                                <th style="width: 18%;">MURID / USER</th>
                                <th style="width: 18%;">PAKET</th>
                                <th style="width: 12%;">METODE</th>
                                <th style="width: 12%;">TOTAL</th>
                                <th style="width: 10%;">STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($txns as $txn)
                            @php
                                $st = strtolower((string) $txn->status);
                                $isSuccess = in_array($st, ['settlement', 'success', 'paid', 'capture', 'completed']);
                                $isPending = in_array($st, ['pending', 'challenge']);
                                $userName = optional($txn->user)->name ?? 'Guest User';
                                $packageName = optional($txn->package)->name ?? '-';
                            @endphp
                            <tr>
                                <td class="text-slate-400 font-semibold" style="color: #94A3B8; font-size: 0.8rem;">
                                    {{ $loop->iteration + ($txns->currentPage()-1)*$txns->perPage() }}
                                </td>
                                <td>
                                    <span class="px-2.5 py-1 rounded font-mono fw-bold text-slate-800 text-truncate d-inline-block" style="background: #F8FAFC; color: #1E293B; border: 1px solid #E2E8F0; border-radius: 6px; font-size: 0.78rem; max-width: 220px;" title="{{ $txn->order_id }}">
                                        {{ $txn->order_id }}
                                    </span>
                                    <div class="text-slate-400 mt-1" style="font-size: 0.72rem; color: #94A3B8;">
                                        {{ $txn->created_at ? $txn->created_at->format('d M Y, H:i') : '-' }} WIB
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-slate-900" style="color: #0F172A; font-size: 0.88rem;">
                                        {{ $userName }}
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-semibold text-slate-700" style="color: #334155; font-size: 0.85rem;">
                                        {{ $packageName }}
                                    </span>
                                </td>
                                <td>
                                    <span class="px-2 py-0.5 rounded text-uppercase font-semibold text-slate-600" style="background: #F1F5F9; color: #475569; border: 1px solid #CBD5E1; font-size: 0.72rem;">
                                        {{ $txn->method ?: 'Midtrans' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold text-slate-900" style="color: #0F172A; font-size: 0.9rem;">
                                        Rp {{ number_format($txn->amount, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td>
                                    @if($isSuccess)
                                        <span class="badge-status-success">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                            <span>SETTLEMENT</span>
                                        </span>
                                    @elseif($isPending)
                                        <span class="badge-status-pending">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                            <span>PENDING</span>
                                        </span>
                                    @else
                                        <span class="badge-status-failed">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                                            <span>{{ strtoupper($txn->status) }}</span>
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5" style="color: #64748B;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="d-block mx-auto mb-2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                                    Tidak ada data transaksi yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Footer of the Table -->
                <div class="px-4 py-3.5 bg-slate-50 border-top border-slate-200 text-sm text-slate-600 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2" style="background: #F8FAFC; border-top: 1px solid #E2E8F0; color: #475569; font-size: 0.85rem;">
                    <div>
                        Menampilkan <span class="fw-semibold text-slate-900" style="color: #0F172A; font-weight: 700;">{{ $txns->count() }}</span> dari {{ $txns->total() }} transaksi
                    </div>
                    <div>
                        {{ $txns->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
