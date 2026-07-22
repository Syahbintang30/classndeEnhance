@extends('layouts.admin')

@section('title', 'User Packages & Coaching Tickets')

@section('content')
    @push('styles')
        <style>
            .users-packages-page {
                font-family: 'Inter', system-ui, -apple-system, sans-serif;
                background-color: #F8FAFC;
            }

            .table-users-pkg {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
            }

            .table-users-pkg th {
                background: #F8FAFC;
                padding: 14px 20px;
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: #475569;
                border-bottom: 1px solid #E2E8F0;
            }

            .table-users-pkg td {
                padding: 16px 20px;
                border-bottom: 1px solid #F1F5F9;
                vertical-align: middle;
                font-size: 13px;
                background: #FFFFFF;
            }

            .table-users-pkg tr:last-child td {
                border-bottom: none;
            }

            .table-users-pkg tr:hover td {
                background-color: #F8FAFC;
            }

            .badge-ticket-avail {
                background: #ECFDF5;
                color: #047857;
                border: 1px solid #A7F3D0;
                border-radius: 6px;
                padding: 4px 10px;
                font-size: 0.78rem;
                font-weight: 700;
                display: inline-flex;
                align-items: center;
                gap: 6px;
            }

            .badge-ticket-empty {
                background: #FEF2F2;
                color: #DC2626;
                border: 1px solid #FCA5A5;
                border-radius: 6px;
                padding: 4px 10px;
                font-size: 0.78rem;
                font-weight: 700;
                display: inline-flex;
                align-items: center;
                gap: 6px;
            }

            .user-avatar-circle {
                width: 36px;
                height: 36px;
                border-radius: 9999px;
                background: #EFF6FF;
                color: #2563EB;
                border: 1px solid #BFDBFE;
                font-weight: 800;
                font-size: 0.85rem;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }
        </style>
    @endpush

    <div class="users-packages-page min-h-screen p-3 md:p-6">
        <div class="max-w-7xl mx-auto space-y-6">

            <!-- Header Section -->
            <div class="d-flex flex-column flex-md-row md:items-start justify-content-between gap-3 mb-4">
                <div>
                    <div class="d-flex align-items-center gap-3 mb-1">
                        <h1 class="h4 fw-bold text-slate-900 tracking-tight mb-0" style="color: #0F172A; font-weight: 800; font-size: 1.5rem;">
                            User Packages & Coaching Tickets
                        </h1>
                        <span class="px-2.5 py-0.5 rounded-pill bg-blue-100 text-blue-700 fw-semibold" style="font-size: 0.75rem; background: #DBEAFE; color: #1D4ED8;">
                            Total {{ $users->total() ?? $users->count() }} Users
                        </span>
                    </div>
                    <p class="text-slate-500 mb-0" style="color: #64748B; font-size: 0.875rem;">
                        Daftar murid, paket berlangganan aktif yang dipilih, dan sisa tiket coaching yang tersedia.
                    </p>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success d-flex align-items-center gap-2 border-0 shadow-sm rounded-lg mb-4" style="background: #ECFDF5; color: #047857; border: 1px solid #A7F3D0 !important;" id="success-alert">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    <span>{{ session('success') }}</span>
                </div>
                <script>
                    setTimeout(function() {
                        let alert = document.getElementById('success-alert');
                        if (alert) {
                            alert.style.transition = "opacity 0.5s ease";
                            alert.style.opacity = "0";
                            setTimeout(() => alert.remove(), 500);
                        }
                    }, 3000);
                </script>
            @endif

            <!-- List Section Card -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden" style="border-radius: 12px; border: 1px solid #E2E8F0;">
                <div class="table-responsive">
                    <table class="table-users-pkg mb-0">
                        <thead>
                            <tr>
                                <th style="width: 30%;">NAMA MURID</th>
                                <th style="width: 30%;">EMAIL</th>
                                <th style="width: 22%;">PAKET MEMBERSHIP</th>
                                <th style="width: 18%;">TIKET COACHING (AVAILABLE / TOTAL)</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($users as $u)
                            @php
                                $pkgName = null;
                                if ($u->package) {
                                    $pkgName = $u->package->name;
                                } elseif ($u->package_id && isset($rolePackages[$u->package_id])) {
                                    $pkgName = $rolePackages[$u->package_id]->name;
                                }
                                $avail = $u->available_tickets_count ?? 0;
                                $total = $u->total_tickets_count ?? 0;
                                $initial = strtoupper(substr($u->name ?? 'U', 0, 1));
                                $userPhoto = method_exists($u, 'photoUrl') ? $u->photoUrl() : null;
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        @if($userPhoto)
                                            <img src="{{ $userPhoto }}" alt="{{ $u->name }}" class="rounded-circle object-fit-cover flex-shrink-0" style="width: 36px; height: 36px; object-fit: cover; border: 1px solid #CBD5E1;">
                                        @else
                                            <div class="user-avatar-circle">
                                                {{ $initial }}
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <div class="fw-bold text-slate-900 text-truncate" style="color: #0F172A; font-size: 0.9rem;">
                                                {{ $u->name }}
                                            </div>
                                            <div class="text-slate-400" style="color: #94A3B8; font-size: 0.75rem;">
                                                ID: #{{ $u->id }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-slate-600 font-medium" style="color: #475569; font-size: 0.85rem;">
                                        {{ $u->email }}
                                    </span>
                                </td>
                                <td>
                                    @if($pkgName)
                                        <span class="px-2.5 py-1 rounded font-semibold" style="background: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE; border-radius: 6px; font-size: 0.8rem;">
                                            {{ $pkgName }}
                                        </span>
                                    @else
                                        <span class="text-slate-400" style="color: #94A3B8; font-size: 0.85rem;">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($avail > 0)
                                        <span class="badge-ticket-avail">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                                            <span>{{ $avail }} / {{ $total }} Available</span>
                                        </span>
                                    @else
                                        <span class="badge-ticket-empty">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                            <span>{{ $avail }} / {{ $total }} Ticket</span>
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5" style="color: #64748B;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="d-block mx-auto mb-2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                    Belum ada data user yang terdaftar.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Footer of the Table -->
                <div class="px-4 py-3.5 bg-slate-50 border-top border-slate-200 text-sm text-slate-600 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2" style="background: #F8FAFC; border-top: 1px solid #E2E8F0; color: #475569; font-size: 0.85rem;">
                    <div>
                        Menampilkan <span class="fw-semibold text-slate-900" style="color: #0F172A; font-weight: 700;">{{ $users->count() }}</span> dari {{ $users->total() }} users
                    </div>
                    <div>
                        {{ $users->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
