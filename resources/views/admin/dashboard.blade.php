@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
    @push('styles')
        <style>
            .dash-wrapper {
                font-family: 'Inter', system-ui, -apple-system, sans-serif;
                color: #0F172A;
                background-color: #F8FAFC;
            }
            
            .dash-kpi-card {
                background: #FFFFFF !important;
                border: 1px solid #E2E8F0 !important;
                box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.05) !important;
                border-radius: 14px !important;
                padding: 1.25rem !important;
                position: relative !important;
                overflow: hidden !important;
                transition: all 0.2s ease !important;
            }

            .dash-kpi-card:hover {
                border-color: #CBD5E1 !important;
                box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08) !important;
                transform: translateY(-2px) !important;
            }

            .dash-kpi-icon {
                width: 42px;
                height: 42px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.1rem;
                flex-shrink: 0;
            }

            .dash-chart-container {
                display: flex !important;
                align-items: flex-end !important;
                justify-content: space-between !important;
                gap: 12px !important;
                height: 230px !important;
                padding: 24px 16px 36px 16px !important;
                background: #F8FAFC !important;
                border: 1px solid #E2E8F0 !important;
                border-radius: 12px !important;
                position: relative !important;
                margin-top: 1rem !important;
                margin-bottom: 1rem !important;
            }

            .dash-chart-bar {
                flex: 1 !important;
                min-height: 8px !important;
                background: linear-gradient(180deg, #2563EB 0%, #1D4ED8 100%) !important;
                border-radius: 6px 6px 0 0 !important;
                position: relative !important;
                transition: all 0.2s ease !important;
                cursor: pointer !important;
            }

            .dash-chart-bar:hover {
                background: linear-gradient(180deg, #3B82F6 0%, #1E40AF 100%) !important;
                transform: translateY(-3px) !important;
                box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3) !important;
            }

            .dash-chart-bar::after {
                content: attr(data-m) !important;
                position: absolute !important;
                bottom: -28px !important;
                left: 50% !important;
                transform: translateX(-50%) !important;
                font-size: 0.72rem !important;
                font-weight: 700 !important;
                color: #64748B !important;
                white-space: nowrap !important;
            }

            .dash-chart-bar:hover::before {
                content: attr(title) !important;
                position: absolute !important;
                top: -34px !important;
                left: 50% !important;
                transform: translateX(-50%) !important;
                background: #0F172A !important;
                color: #FFFFFF !important;
                font-size: 0.72rem !important;
                font-weight: 700 !important;
                padding: 4px 10px !important;
                border-radius: 6px !important;
                white-space: nowrap !important;
                z-index: 20 !important;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
            }

            .badge-live-pulse {
                background-color: #ECFDF5 !important;
                color: #047857 !important;
                border: 1px solid #A7F3D0 !important;
                border-radius: 9999px;
                padding: 4px 10px;
                font-size: 0.75rem;
                font-weight: 700;
                display: inline-flex;
                align-items: center;
                gap: 6px;
            }
        </style>
    @endpush

    @php
        $dashboardMetrics = $dashboardMetrics ?? [];
        $bars = $dashboardMetrics['chart_bars'] ?? collect();
        $monthLabels = $dashboardMetrics['month_labels'] ?? ['current' => now()->translatedFormat('F Y'), 'previous' => now()->subMonthNoOverflow()->translatedFormat('F Y')];
        $monthOrders = $dashboardMetrics['month_orders'] ?? ['current' => 0, 'previous' => 0];
        $monthRevenue = $dashboardMetrics['month_revenue'] ?? ['current' => 0, 'previous' => 0];
        $target = $dashboardMetrics['target'] ?? ['value' => 1, 'percent' => 0];
        $kpiDeltas = $dashboardMetrics['kpi_deltas'] ?? ['users' => 0, 'orders' => 0, 'lessons' => 0];

        $deltaLabel = function ($value) {
            $prefix = $value > 0 ? '+' : '';
            return $prefix . number_format((float) $value, 1, ',', '.') . '%';
        };

        $targetPercent = (float) ($target['percent'] ?? 0);
        $pendingBookings = (int) ($stats['pending_bookings'] ?? 0);
    @endphp

    <div class="dash-wrapper min-h-screen p-3 md:p-6">
        <div class="max-w-7xl mx-auto space-y-6">
            
            <!-- Page Header Title Section -->
            <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
                <div>
                    <div class="d-flex align-items-center gap-3 mb-1">
                        <h1 class="h4 fw-bold text-slate-900 tracking-tight mb-0" style="color: #0F172A; font-weight: 800; font-size: 1.5rem;">
                            Dashboard {{ $isSuperadmin ? 'Super Admin' : 'Admin' }}
                        </h1>
                        <span class="badge-live-pulse">
                            <span class="position-relative d-inline-flex" style="width: 6px; height: 6px;">
                                <span class="position-absolute w-100 h-100 rounded-circle bg-emerald-400 opacity-75 animate-ping"></span>
                                <span class="position-relative w-100 h-100 rounded-circle bg-emerald-500"></span>
                            </span>
                            <span>Live Sync</span>
                        </span>
                    </div>
                    <p class="text-slate-500 mb-0" style="color: #64748B; font-size: 0.875rem;">
                        Ringkasan statistik realtime, pendapatan bulanan, dan aktivitas Guitarclassbynde.
                    </p>
                </div>
                <div>
                    <span class="badge bg-white border border-slate-200 text-slate-700 font-mono shadow-xs px-3 py-2 rounded-lg d-inline-flex align-items-center gap-2" style="font-size: 0.8rem; background: #FFFFFF !important; color: #334155 !important; border: 1px solid #CBD5E1 !important;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        <span>{{ now()->translatedFormat('F Y') }}</span>
                    </span>
                </div>
            </div>

            <!-- Top Metric Cards Grid (4 Columns) -->
            <div class="row g-4 mb-4">
                
                <!-- Card 1: Total Users -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="dash-kpi-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="fw-semibold mb-1" style="font-size: 0.78rem; color: #64748B;">Total Users</p>
                                <h3 class="h2 fw-extrabold mb-0 tracking-tight" style="color: #0F172A; font-weight: 800;">{{ number_format((int) $stats['users']) }}</h3>
                            </div>
                            <div class="dash-kpi-icon" style="background: #EFF6FF; border: 1px solid #BFDBFE; color: #2563EB;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            </div>
                        </div>
                        <div class="mt-3">
                            <span class="badge px-2.5 py-1 rounded-pill font-semibold" style="font-size: 0.72rem; background: #ECFDF5; color: #047857; border: 1px solid #A7F3D0;">
                                {{ $deltaLabel($kpiDeltas['users']) }} vs bulan lalu
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Orders This Month -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="dash-kpi-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="fw-semibold mb-1" style="font-size: 0.78rem; color: #64748B;">Orders Bulan Ini</p>
                                <h3 class="h2 fw-extrabold mb-0 tracking-tight" style="color: #0F172A; font-weight: 800;">{{ number_format((int) $monthOrders['current']) }}</h3>
                            </div>
                            <div class="dash-kpi-icon" style="background: #ECFEFF; border: 1px solid #A5F3FC; color: #0891B2;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                            </div>
                        </div>
                        <div class="mt-3">
                            <span class="badge px-2.5 py-1 rounded-pill font-semibold" style="font-size: 0.72rem; background: #ECFDF5; color: #047857; border: 1px solid #A7F3D0;">
                                {{ $deltaLabel($kpiDeltas['orders']) }} vs bulan lalu
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Total Lessons -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="dash-kpi-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="fw-semibold mb-1" style="font-size: 0.78rem; color: #64748B;">Total Lessons</p>
                                <h3 class="h2 fw-extrabold mb-0 tracking-tight" style="color: #0F172A; font-weight: 800;">{{ number_format((int) $stats['lessons']) }}</h3>
                            </div>
                            <div class="dash-kpi-icon" style="background: #EEF2FF; border: 1px solid #C7D2FE; color: #4F46E5;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                            </div>
                        </div>
                        <div class="mt-3">
                            <span class="badge px-2.5 py-1 rounded-pill font-semibold" style="font-size: 0.72rem; background: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE;">
                                Modul Pembelajaran Aktif
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Pending Coaching Bookings -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="dash-kpi-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="fw-semibold mb-1" style="font-size: 0.78rem; color: #64748B;">Coaching Pending</p>
                                <h3 class="h2 fw-extrabold mb-0 tracking-tight" style="color: #0F172A; font-weight: 800;">{{ number_format($pendingBookings) }}</h3>
                            </div>
                            <div class="dash-kpi-icon" style="background: #FFFBEB; border: 1px solid #FDE68A; color: #D97706;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            </div>
                        </div>
                        <div class="mt-3">
                            @if($pendingBookings > 0)
                                <span class="badge px-2.5 py-1 rounded-pill font-semibold" style="font-size: 0.72rem; background: #FFFBEB; color: #B45309; border: 1px solid #FDE68A;">
                                    Membutuhkan Konfirmasi
                                </span>
                            @else
                                <span class="badge px-2.5 py-1 rounded-pill font-semibold" style="font-size: 0.72rem; background: #F8FAFC; color: #64748B; border: 1px solid #E2E8F0;">
                                    Semua Terkonfirmasi
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            <!-- Revenue & Target Progress Row -->
            <div class="row g-4 mb-4">
                
                <!-- Monthly Revenue Chart Card (2 Columns Wide) -->
                <div class="col-12 col-lg-8">
                    <div class="dash-kpi-card h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <h3 class="h5 fw-bold mb-1 tracking-tight" style="color: #0F172A; font-weight: 800;">Monthly Revenue {{ now()->year }}</h3>
                                    <p class="small mb-0" style="color: #64748B; font-size: 0.85rem;">Grafik tren pendapatan dari transaksi lisensi kelas & booking coaching.</p>
                                </div>
                                <span class="badge px-3 py-1.5 rounded-lg font-semibold" style="background: #EFF6FF !important; color: #1D4ED8 !important; border: 1px solid #BFDBFE !important; font-size: 0.75rem;">
                                    {{ $monthLabels['current'] }}
                                </span>
                            </div>

                            <!-- Visual Revenue Bar Chart Container -->
                            <div class="dash-chart-container">
                                @foreach($bars as $bar)
                                    <div class="dash-chart-bar" data-m="{{ $bar['label'] }}" style="height: {{ max(10, (int) $bar['height']) }}%" title="Rp {{ number_format((int) $bar['value'], 0, ',', '.') }}"></div>
                                @endforeach
                            </div>
                        </div>
                        
                        <p class="small fst-italic mt-3 pt-2 border-top border-slate-200 d-flex align-items-center gap-1.5 mb-0" style="font-size: 0.78rem; color: #64748B;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                            <span>Dihitung berdasarkan transaksi sukses (settlement / capture / paid).</span>
                        </p>
                    </div>
                </div>

                <!-- Monthly Target Progress Card (1 Column Wide) -->
                <div class="col-12 col-lg-4">
                    <div class="dash-kpi-card h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h3 class="h5 fw-bold mb-0 tracking-tight" style="color: #0F172A; font-weight: 800;">Pencapaian Target</h3>
                                <span class="badge px-2.5 py-1 rounded-lg font-semibold" style="background: #EFF6FF !important; color: #1D4ED8 !important; border: 1px solid #BFDBFE !important; font-size: 0.75rem;">
                                    {{ number_format($targetPercent, 1, ',', '.') }}%
                                </span>
                            </div>

                            <div class="my-4">
                                <div class="h1 fw-extrabold mb-2 tracking-tight" style="color: #0F172A; font-size: 2.2rem; font-weight: 800;">{{ number_format($targetPercent, 1, ',', '.') }}%</div>
                                <div class="w-100 rounded-pill p-0.5" style="height: 12px; overflow: hidden; background: #F1F5F9; border: 1px solid #CBD5E1;">
                                    <div class="rounded-pill h-100" style="width: {{ max(0, min(100, $targetPercent)) }}%; background: linear-gradient(90deg, #2563eb, #4f46e5, #0891b2);"></div>
                                </div>
                            </div>

                            <p class="small leading-relaxed mb-4" style="font-size: 0.78rem; color: #64748B;">
                                Target dihitung berdasarkan perbandingan omzet bulan ini vs bulan sebelumnya.
                            </p>

                            <!-- Breakdown Numbers Grid -->
                            <div class="row g-2 text-center">
                                <div class="col-4">
                                    <div class="p-2 rounded-xl" style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px;">
                                        <span class="d-block fw-bold uppercase tracking-wider" style="font-size: 0.65rem; color: #64748B;">TARGET</span>
                                        <span class="d-block fw-extrabold small mt-1" style="color: #0F172A; font-weight: 700;">Rp {{ number_format((int) $target['value'], 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-2 rounded-xl" style="background: #ECFDF5; border: 1px solid #A7F3D0; border-radius: 8px;">
                                        <span class="d-block fw-bold uppercase tracking-wider" style="font-size: 0.65rem; color: #047857;">OMZET</span>
                                        <span class="d-block fw-extrabold small mt-1" style="color: #065F46; font-weight: 700;">Rp {{ number_format((int) $monthRevenue['current'], 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-2 rounded-xl" style="background: #FFFBEB; border: 1px solid #FDE68A; border-radius: 8px;">
                                        <span class="d-block fw-bold uppercase tracking-wider" style="font-size: 0.65rem; color: #B45309;">PENDING</span>
                                        <span class="d-block fw-extrabold small mt-1" style="color: #D97706; font-weight: 700;">{{ number_format($pendingBookings) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('admin.transactions.index') }}" class="btn w-100 mt-4 py-2 rounded-lg text-xs font-semibold d-flex align-items-center justify-content-center gap-2 text-decoration-none" style="background: #F1F5F9; border: 1px solid #CBD5E1; color: #334155; border-radius: 8px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                            <span>Lihat Riwayat Transaksi</span>
                        </a>
                    </div>
                </div>

            </div>

            <!-- Quick Action Shortcut Cards (3 Columns) -->
            <div class="row g-4">
                
                <div class="col-12 col-md-4">
                    <a class="dash-kpi-card text-decoration-none d-block h-100" href="{{ route('admin.lessons.create') }}">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="dash-kpi-icon" style="background: #EFF6FF; border: 1px solid #BFDBFE; color: #2563EB;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                            </div>
                            <h4 class="h6 font-bold mb-0" style="color: #0F172A; font-weight: 700;">Tambah Lesson Baru</h4>
                        </div>
                        <p class="small mb-3" style="color: #64748B; font-size: 0.82rem;">Buat modul pembelajaran, unggah materi lagu, atau video kelas baru.</p>
                        <div class="d-flex align-items-center font-semibold text-xs gap-1.5" style="color: #2563EB;">
                            <span>Buka Form Tambah</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-md-4">
                    <a class="dash-kpi-card text-decoration-none d-block h-100" href="{{ url('/admin/coaching/bookings') }}">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="dash-kpi-icon" style="background: #EEF2FF; border: 1px solid #C7D2FE; color: #4F46E5;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            </div>
                            <h4 class="h6 font-bold mb-0" style="color: #0F172A; font-weight: 700;">Kelola Booking Coaching</h4>
                        </div>
                        <p class="small mb-3" style="color: #64748B; font-size: 0.82rem;">Pantau sesi 1-on-1 live coaching murid, konfirmasi jadwal & link video call.</p>
                        <div class="d-flex align-items-center font-semibold text-xs gap-1.5" style="color: #4F46E5;">
                            <span>Lihat Sesi Coaching</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-md-4">
                    <a class="dash-kpi-card text-decoration-none d-block h-100" href="{{ route('admin.users.packages') }}">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="dash-kpi-icon" style="background: #ECFEFF; border: 1px solid #A5F3FC; color: #0891B2;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                            </div>
                            <h4 class="h6 font-bold mb-0" style="color: #0F172A; font-weight: 700;">Paket & Tiket Murid</h4>
                        </div>
                        <p class="small mb-3" style="color: #64748B; font-size: 0.82rem;">Lihat akses paket murid, sisa tiket coaching yang tersedia, dan kuota.</p>
                        <div class="d-flex align-items-center font-semibold text-xs gap-1.5" style="color: #0891B2;">
                            <span>Kelola Tiket User</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </div>
                    </a>
                </div>

            </div>

        </div>
    </div>
@endsection
