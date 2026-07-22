@extends('layouts.admin')

@section('title', 'Dashboard')

@push('styles')
<style>
    .admin-dash {
        color: #f3f4f6;
    }

    .admin-dash .page-title {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .admin-dash .page-title h1 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        color: #ffffff;
    }

    .admin-dash .page-title p {
        margin: .35rem 0 0;
        color: #94a3b8;
        font-size: .9rem;
    }

    .admin-dash .chip {
        border-radius: 999px;
        border: 1px solid rgba(59, 130, 246, 0.3);
        color: #60a5fa;
        text-decoration: none;
        font-size: .78rem;
        font-weight: 700;
        padding: .32rem .7rem;
        background: rgba(59, 130, 246, 0.15);
    }

    .admin-dash .kpi-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: .9rem;
    }

    .admin-dash .kpi-card,
    .admin-dash .panel,
    .admin-dash .quick-action {
        background: rgba(18, 18, 26, 0.75);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
        color: #ffffff;
    }

    .admin-dash .kpi-card {
        padding: 1rem;
        position: relative;
        overflow: hidden;
    }

    .admin-dash .kpi-card .label {
        color: #94a3b8;
        font-size: .8rem;
        font-weight: 600;
    }

    .admin-dash .kpi-card .value {
        margin-top: .25rem;
        font-size: 1.6rem;
        font-weight: 800;
        letter-spacing: -.02em;
        color: #ffffff;
    }

    .admin-dash .kpi-icon {
        position: absolute;
        right: .9rem;
        top: .9rem;
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: rgba(59, 130, 246, 0.15);
        color: #60a5fa;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .admin-dash .trend {
        margin-top: .45rem;
        font-size: .76rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: .2rem;
        padding: .2rem .44rem;
        border-radius: 999px;
    }

    .admin-dash .trend.up {
        background: rgba(16, 185, 129, .2);
        color: #34d399;
    }

    .admin-dash .trend.down {
        background: rgba(239, 68, 68, .2);
        color: #f87171;
    }

    .admin-dash .trend.neutral {
        background: rgba(255, 255, 255, .1);
        color: #94a3b8;
    }

    .admin-dash .layout-grid {
        display: grid;
        grid-template-columns: 1.7fr 1fr;
        gap: .9rem;
        margin-top: .9rem;
    }

    .admin-dash .panel {
        padding: 1rem;
    }

    .admin-dash .panel-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: .8rem;
        gap: .6rem;
    }

    .admin-dash .panel-head h2 {
        margin: 0;
        font-size: 1rem;
        font-weight: 700;
        color: #ffffff;
    }


    .admin-dash .bar-chart {
        display: grid;
        grid-template-columns: repeat(12, minmax(0, 1fr));
        gap: .42rem;
        align-items: end;
        height: 200px;
        border-top: 1px dashed rgba(255, 255, 255, .1);
        border-bottom: 1px dashed rgba(255, 255, 255, .1);
        padding: .8rem .2rem 1.6rem .2rem;
        margin-bottom: .6rem;
    }

    .admin-dash .bar {
        background: linear-gradient(180deg, #60a5fa, #2563eb);
        border-radius: 8px 8px 4px 4px;
        position: relative;
        box-shadow: 0 0 12px rgba(59, 130, 246, 0.3);
    }

    .admin-dash .bar::after {
        content: attr(data-m);
        position: absolute;
        bottom: -1.35rem;
        left: 50%;
        transform: translateX(-50%);
        font-size: .68rem;
        color: #94a3b8;
        font-weight: 700;
        white-space: nowrap;
    }


    .admin-dash .target-wrap {
        display: grid;
        gap: .7rem;
    }

    .admin-dash .target-track {
        width: 100%;
        height: 12px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.08);
        overflow: hidden;
    }

    .admin-dash .target-fill {
        height: 100%;
        background: linear-gradient(90deg, #60a5fa, #2563eb);
    }

    .admin-dash .target-num {
        font-size: 2rem;
        font-weight: 800;
        color: #ffffff;
        letter-spacing: -.02em;
    }

    .admin-dash .gauge-meta {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: .55rem;
        margin-top: .2rem;
    }

    .admin-dash .gauge-meta div {
        text-align: center;
        background: rgba(12, 12, 18, 0.6);
        border: 1px solid rgba(255, 255, 255, .08);
        border-radius: 10px;
        padding: .46rem .32rem;
    }

    .admin-dash .gauge-meta .k {
        color: #94a3b8;
        font-size: .69rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .admin-dash .gauge-meta .v {
        font-size: .9rem;
        font-weight: 800;
        color: #ffffff;
        margin-top: .1rem;
    }

    .admin-dash .quick-actions {
        margin-top: .9rem;
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: .8rem;
    }

    .admin-dash .quick-action {
        text-decoration: none;
        color: #ffffff;
        padding: .82rem;
        transition: transform .16s ease, box-shadow .16s ease, border-color .16s ease;
    }

    .admin-dash .quick-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 18px 34px rgba(0, 0, 0, .4);
        border-color: rgba(59, 130, 246, 0.4);
    }


    .admin-dash .quick-action .title {
        font-weight: 800;
        font-size: .88rem;
        margin-bottom: .2rem;
        color: #ffffff;
    }

    .admin-dash .quick-action .desc {
        color: #94a3b8;
        font-size: .8rem;
    }

    .admin-dash .table-lite {
        width: 100%;
        border-collapse: collapse;
    }

    .admin-dash .table-lite th,
    .admin-dash .table-lite td {
        padding: .52rem .2rem;
        border-bottom: 1px solid rgba(255, 255, 255, .08);
        font-size: .82rem;
        color: #e2e8f0;
    }

    .admin-dash .table-lite th {
        color: #94a3b8;
        font-size: .72rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        font-weight: 800;
    }

    .admin-dash .status-pill {
        padding: .16rem .46rem;
        border-radius: 999px;
        font-size: .68rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .admin-dash .status-ok {
        background: rgba(16, 185, 129, .2);
        color: #34d399;
    }

    .admin-dash .status-pending {
        background: rgba(245, 158, 11, .2);
        color: #fbbf24;
    }


    .admin-dash .audit-list {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .admin-dash .audit-list li {
        padding: .5rem 0;
        border-bottom: 1px solid rgba(148, 163, 184, .16);
        color: #334155;
        font-size: .8rem;
    }

    .admin-dash .small-note {
        color: #64748b;
        font-size: .78rem;
    }

    @media (max-width: 1200px) {
        .admin-dash .kpi-grid,
        .admin-dash .quick-actions {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .admin-dash .layout-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 720px) {
        .admin-dash .kpi-grid,
        .admin-dash .quick-actions {
            grid-template-columns: 1fr;
        }

        .admin-dash .page-title {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
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

    $trendClass = function ($value) {
        if ($value > 0) {
            return 'up';
        }
        if ($value < 0) {
            return 'down';
        }
        return 'neutral';
    };

    $targetPercent = (float) ($target['percent'] ?? 0);
@endphp
<div class="admin-dash">
    <div class="page-title">
        <div>
            <h1>Dashboard {{ $isSuperadmin ? 'Super Admin' : 'Admin' }}</h1>
            <p>The dashboard theme is aligned with the landing page, and the metrics below pull live system data.</p>
        </div>
    </div>

    <section class="kpi-grid">
        <article class="kpi-card">
            <span class="kpi-icon"><i class="ph ph-users-three"></i></span>
            <div class="label">Total Users</div>
            <div class="value">{{ number_format((int) $stats['users']) }}</div>
            <div class="trend {{ $trendClass($kpiDeltas['users']) }}">
                <i class="ph {{ $kpiDeltas['users'] >= 0 ? 'ph-arrow-up-right' : 'ph-arrow-down-right' }}"></i>
                {{ $deltaLabel($kpiDeltas['users']) }} vs last month
            </div>
        </article>
        <article class="kpi-card">
            <span class="kpi-icon"><i class="ph ph-shopping-cart"></i></span>
            <div class="label">Orders This Month</div>
            <div class="value">{{ number_format((int) $monthOrders['current']) }}</div>
            <div class="trend {{ $trendClass($kpiDeltas['orders']) }}">
                <i class="ph {{ $kpiDeltas['orders'] >= 0 ? 'ph-arrow-up-right' : 'ph-arrow-down-right' }}"></i>
                {{ $deltaLabel($kpiDeltas['orders']) }} vs last month
            </div>
        </article>
        <article class="kpi-card">
            <span class="kpi-icon"><i class="ph ph-book-open-text"></i></span>
            <div class="label">Total Lessons</div>
            <div class="value">{{ number_format((int) $stats['lessons']) }}</div>
            <div class="trend {{ $trendClass($kpiDeltas['lessons']) }}">
                <i class="ph {{ $kpiDeltas['lessons'] >= 0 ? 'ph-arrow-up-right' : 'ph-arrow-down-right' }}"></i>
                {{ $deltaLabel($kpiDeltas['lessons']) }} new lessons
            </div>
        </article>
    </section>

    <section class="layout-grid">
        <article class="panel">
            <div class="panel-head">
                <h2>Monthly Revenue {{ now()->year }}</h2>
                <span class="chip">{{ $monthLabels['current'] }}</span>
            </div>
            <div class="bar-chart">
                @foreach($bars as $bar)
                    <div class="bar" data-m="{{ $bar['label'] }}" style="height: {{ $bar['height'] }}%" title="Rp {{ number_format((int) $bar['value'], 0, ',', '.') }}"></div>
                @endforeach
            </div>
            <p class="small-note mt-4 mb-0">Chart based on successful transactions (settlement/capture/success/paid/settled).</p>
        </article>

        <article class="panel">
            <div class="panel-head">
                <h2>Monthly Target Progress</h2>
                <span class="chip">{{ number_format($targetPercent, 2, ',', '.') }}%</span>
            </div>
            <div class="target-wrap">
                <div class="target-num">{{ number_format($targetPercent, 2, ',', '.') }}%</div>
                <div class="target-track">
                    <div class="target-fill" style="width: {{ max(0, min(100, $targetPercent)) }}%"></div>
                </div>
                <div class="small-note">The target is based on the previous month's revenue to make current-month tracking easier.</div>
            </div>
            <div class="gauge-meta">
                <div>
                    <div class="k">Target</div>
                    <div class="v">Rp {{ number_format((int) $target['value'], 0, ',', '.') }}</div>
                </div>
                <div>
                    <div class="k">Revenue</div>
                    <div class="v">Rp {{ number_format((int) $monthRevenue['current'], 0, ',', '.') }}</div>
                </div>
                <div>
                    <div class="k">Pending</div>
                    <div class="v">{{ number_format((int) $stats['pending_bookings']) }}</div>
                </div>
            </div>
        </article>
    </section>

    <section class="quick-actions">
        <a class="quick-action" href="{{ route('admin.lessons.create') }}">
            <div class="title">Add New Lesson</div>
            <div class="desc">Create modules and lesson content directly from the admin panel.</div>
        </a>
        <a class="quick-action" href="{{ url('/admin/coaching/bookings') }}">
            <div class="title">Manage Coaching Bookings</div>
            <div class="desc">Monitor coaching requests and respond faster.</div>
        </a>
        <a class="quick-action" href="{{ route('admin.users.packages') }}">
            <div class="title">Manage User Packages</div>
            <div class="desc">Control user class access from one place.</div>
        </a>
    </section>

    <section class="layout-grid mt-1">
        <article class="panel">
            <div class="panel-head">
                <h2>Recent Transactions</h2>
                @if($isSuperadmin)
                    <a class="chip" href="{{ route('admin.transactions.index') }}">See all</a>
                @endif
            </div>
            <div class="table-responsive">
                <table class="table-lite">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransactions as $txn)
                            @php
                                $status = strtolower((string) $txn->status);
                                $ok = in_array($status, ['settlement', 'capture', 'success', 'paid', 'settled']);
                            @endphp
                            <tr>
                                <td>{{ $txn->order_id }}</td>
                                <td>{{ optional($txn->user)->name ?? 'Guest' }}</td>
                                <td>Rp {{ number_format((int) $txn->amount, 0, ',', '.') }}</td>
                                <td><span class="status-pill {{ $ok ? 'status-ok' : 'status-pending' }}">{{ $txn->status }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="small-note">No recent transactions yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>

        <article class="panel">
            <div class="panel-head">
                <h2>{{ $isSuperadmin ? 'Audit Activity' : 'Operational Summary' }}</h2>
            </div>
            @if($isSuperadmin)
                <ul class="audit-list">
                    @forelse($recentAudits as $audit)
                        <li>
                            <strong>{{ $audit->action ?? 'activity' }}</strong><br>
                            {{ $audit->user_name ?? ('User #' . ($audit->user_id ?? '-')) }}
                            <div class="small-note">{{ optional($audit->created_at)->format('d M Y H:i') }}</div>
                        </li>
                    @empty
                        <li class="small-note">No recent audit data yet.</li>
                    @endforelse
                </ul>
            @else
                <ul class="audit-list">
                    <li><strong>Total Topics</strong><div class="small-note">{{ number_format((int) $stats['topics']) }} active topics across all lessons.</div></li>
                    <li><strong>Total Packages</strong><div class="small-note">{{ number_format((int) $stats['packages']) }} packages available for sale.</div></li>
                    <li><strong>Today's Revenue</strong><div class="small-note">Rp {{ number_format((int) $stats['today_revenue'], 0, ',', '.') }} from {{ number_format((int) $stats['today_transactions']) }} transactions.</div></li>
                </ul>
            @endif
        </article>
    </section>
</div>
@endsection
