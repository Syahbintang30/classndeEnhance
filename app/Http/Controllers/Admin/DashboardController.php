<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditTrail;
use App\Models\CoachingBooking;
use App\Models\Lesson;
use App\Models\Package;
use App\Models\Topic;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        // Dashboard admin ini jadi pusat kontrol operasional: statistik, transaksi, booking, dan audit.
        $user = auth()->user();
        $isSuperadmin = (bool) ($user->is_superadmin ?? false);
        $successStatuses = ['settlement', 'capture', 'success', 'paid', 'settled'];

        $now = now();
        $monthStart = $now->copy()->startOfMonth();
        $prevMonthStart = $now->copy()->subMonthNoOverflow()->startOfMonth();
        $prevMonthEnd = $now->copy()->subMonthNoOverflow()->endOfMonth();

        // Ringkasan angka dasar yang dipakai widget KPI di halaman dashboard.
        $stats = [
            'lessons' => Lesson::count(),
            'topics' => Topic::count(),
            'packages' => Package::count(),
            'users' => User::count(),
            'pending_bookings' => CoachingBooking::where('status', 'pending')->count(),
            'today_transactions' => Transaction::whereDate('created_at', now()->toDateString())->count(),
            'today_revenue' => Transaction::whereDate('created_at', now()->toDateString())
                ->whereIn('status', $successStatuses)
                ->sum('amount'),
        ];

        // Grafik bulanan dan revenue dihitung terpisah supaya tampilan chart bisa langsung pakai data siap render.
        $monthlyOrderCounts = Transaction::query()
            ->selectRaw('MONTH(created_at) as month_num, COUNT(*) as total')
            ->whereYear('created_at', $now->year)
            ->groupBy('month_num')
            ->pluck('total', 'month_num');

        $monthlyRevenue = Transaction::query()
            ->selectRaw('MONTH(created_at) as month_num, SUM(amount) as total')
            ->whereYear('created_at', $now->year)
            ->whereIn('status', $successStatuses)
            ->groupBy('month_num')
            ->pluck('total', 'month_num');

        // Bar chart dipaketkan dalam format yang cocok untuk view: label, value, dan height.
        $chartBars = collect(range(1, 12))->map(function ($m) use ($monthlyRevenue) {
            $monthValue = (int) ($monthlyRevenue[$m] ?? 0);

            return [
                'key' => $m,
                'label' => \Carbon\Carbon::create()->month($m)->locale('id')->translatedFormat('M'),
                'value' => $monthValue,
            ];
        });

        $maxBarValue = max(1, (int) $chartBars->max('value'));
        $chartBars = $chartBars->map(function ($bar) use ($maxBarValue) {
            $rawHeight = $bar['value'] > 0 ? (int) round(($bar['value'] / $maxBarValue) * 100) : 8;
            $bar['height'] = max(8, min(100, $rawHeight));

            return $bar;
        })->values();

        $currentMonthRevenue = (int) ($monthlyRevenue[$now->month] ?? 0);
        $prevMonthRevenue = (int) ($monthlyRevenue[$prevMonthStart->month] ?? 0);
        $monthlyTarget = $prevMonthRevenue > 0 ? $prevMonthRevenue : max(1, $currentMonthRevenue);
        $targetPercent = $monthlyTarget > 0 ? round(min(100, ($currentMonthRevenue / $monthlyTarget) * 100), 2) : 0;

        // KPI delta membandingkan bulan ini vs bulan lalu untuk lihat growth, bukan angka mentah saja.
        $kpiUsersCurrent = User::where('created_at', '>=', $monthStart)->count();
        $kpiUsersPrev = User::whereBetween('created_at', [$prevMonthStart, $prevMonthEnd])->count();
        $kpiOrdersCurrent = Transaction::where('created_at', '>=', $monthStart)->count();
        $kpiOrdersPrev = Transaction::whereBetween('created_at', [$prevMonthStart, $prevMonthEnd])->count();
        $kpiLessonsCurrent = Lesson::where('created_at', '>=', $monthStart)->count();
        $kpiLessonsPrev = Lesson::whereBetween('created_at', [$prevMonthStart, $prevMonthEnd])->count();

        $calcDelta = function (int $current, int $previous): float {
            if ($previous <= 0) {
                return $current > 0 ? 100.0 : 0.0;
            }

            return round((($current - $previous) / $previous) * 100, 1);
        };

        $kpiDeltas = [
            'users' => $calcDelta($kpiUsersCurrent, $kpiUsersPrev),
            'orders' => $calcDelta($kpiOrdersCurrent, $kpiOrdersPrev),
            'lessons' => $calcDelta($kpiLessonsCurrent, $kpiLessonsPrev),
        ];

        // Data yang dikirim ke blade dashboard sengaja sudah dirapikan agar view tetap tipis.
        $dashboardMetrics = [
            'chart_bars' => $chartBars,
            'month_labels' => [
                'current' => $monthStart->translatedFormat('F Y'),
                'previous' => $prevMonthStart->translatedFormat('F Y'),
            ],
            'month_orders' => [
                'current' => (int) ($monthlyOrderCounts[$now->month] ?? 0),
                'previous' => (int) ($monthlyOrderCounts[$prevMonthStart->month] ?? 0),
            ],
            'month_revenue' => [
                'current' => $currentMonthRevenue,
                'previous' => $prevMonthRevenue,
            ],
            'target' => [
                'value' => $monthlyTarget,
                'percent' => $targetPercent,
            ],
            'kpi_deltas' => $kpiDeltas,
        ];

        // Transaksi terbaru dipakai buat panel aktivitas terakhir.
        $recentTransactions = Transaction::with(['user', 'package'])
            ->latest()
            ->limit(8)
            ->get();

        // Audit trail hanya diambil kalau memang superadmin dan tabelnya tersedia.
        $recentAudits = collect();
        if ($isSuperadmin && Schema::hasTable('audit_trails')) {
            $recentAudits = AuditTrail::query()->latest()->limit(8)->get();
        }

        // View admin.dashboard menerima semua komponen siap pakai dari controller ini.
        return view('admin.dashboard', [
            'isSuperadmin' => $isSuperadmin,
            'stats' => $stats,
            'dashboardMetrics' => $dashboardMetrics,
            'recentTransactions' => $recentTransactions,
            'recentAudits' => $recentAudits,
        ]);
    }
}
