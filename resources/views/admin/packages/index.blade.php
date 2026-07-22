@extends('layouts.admin')

@section('title', 'Manage Packages')

@section('content')
    @push('styles')
        <style>
            .packages-page {
                font-family: 'Inter', system-ui, -apple-system, sans-serif;
                background-color: #F8FAFC;
            }

            .table-packages {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
            }

            .table-packages th {
                background: #F8FAFC;
                padding: 14px 20px;
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: #475569;
                border-bottom: 1px solid #E2E8F0;
            }

            .table-packages td {
                padding: 16px 20px;
                border-bottom: 1px solid #F1F5F9;
                vertical-align: middle;
                font-size: 13px;
                background: #FFFFFF;
            }

            .table-packages tr:last-child td {
                border-bottom: none;
            }

            .table-packages tr:hover td {
                background-color: #F8FAFC;
            }

            .btn-action-icon {
                width: 34px;
                height: 34px;
                border-radius: 8px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border: 1px solid #E2E8F0;
                background: #FFFFFF;
                color: #475569;
                transition: all 0.2s ease;
                text-decoration: none;
                cursor: pointer;
            }

            .btn-action-icon:hover {
                background: #F1F5F9;
                color: #0F172A;
                border-color: #CBD5E1;
            }

            .btn-action-delete:hover {
                background: #FEF2F2;
                color: #DC2626;
                border-color: #FCA5A5;
            }
        </style>
    @endpush

    <div class="packages-page min-h-screen p-3 md:p-6">
        <div class="max-w-7xl mx-auto space-y-6">

            <!-- Header Section -->
            <div class="d-flex flex-column flex-md-row md:items-start justify-content-between gap-3 mb-4">
                <div>
                    <div class="d-flex align-items-center gap-3 mb-1">
                        <h1 class="h4 fw-bold text-slate-900 tracking-tight mb-0" style="color: #0F172A; font-weight: 800; font-size: 1.5rem;">
                            Paket & Membership Kelas
                        </h1>
                        <span class="px-2.5 py-0.5 rounded-pill bg-blue-100 text-blue-700 fw-semibold" style="font-size: 0.75rem; background: #DBEAFE; color: #1D4ED8;">
                            Total {{ $packages->count() }} Paket
                        </span>
                    </div>
                    <p class="text-slate-500 mb-0" style="color: #64748B; font-size: 0.875rem;">
                        Kelola nama paket berlangganan, slug, harga, dan daftar benefit murid.
                    </p>
                </div>
                <div>
                    <a href="{{ route('admin.packages.create') }}" class="btn btn-primary d-inline-flex align-items-center justify-content-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg fw-semibold border-0 shadow-sm" style="background: #2563EB; font-size: 0.875rem; border-radius: 8px; gap: 8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        <span>Tambah Paket Baru</span>
                    </a>
                </div>
            </div>

            @if(session('status'))
                <div class="alert alert-success d-flex align-items-center gap-2 border-0 shadow-sm rounded-lg mb-4" style="background: #ECFDF5; color: #047857; border: 1px solid #A7F3D0 !important;" id="success-alert">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- List Section Card -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden" style="border-radius: 12px; border: 1px solid #E2E8F0;">
                <div class="table-responsive">
                    <table class="table-packages mb-0">
                        <thead>
                            <tr>
                                <th style="width: 8%;">ID</th>
                                <th style="width: 12%;">GAMBAR</th>
                                <th style="width: 25%;">NAMA PAKET & SLUG</th>
                                <th style="width: 30%;">BENEFIT / DESKRIPSI</th>
                                <th style="width: 15%;">HARGA</th>
                                <th style="width: 10%; text-align: right;">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($packages as $p)
                            <tr>
                                <td class="fw-bold text-slate-500" style="color: #64748B;">#{{ $p->id }}</td>
                                <td>
                                    @if(!empty($p->image))
                                        <img src="{{ asset('storage/'.$p->image) }}" alt="{{ $p->name }}" class="rounded-lg object-fit-cover shadow-xs" style="height: 44px; width: 68px; object-fit: cover; border-radius: 8px; border: 1px solid #E2E8F0;">
                                    @else
                                        <div class="rounded-lg d-flex align-items-center justify-content-center text-slate-400" style="height: 44px; width: 68px; background: #F8FAFC; border-radius: 8px; border: 1px solid #E2E8F0; font-size: 0.75rem; color: #94A3B8;">—</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold text-slate-900 mb-1" style="color: #0F172A; font-size: 0.9rem;">
                                        {{ $p->name }}
                                    </div>
                                    <span class="px-2 py-0.5 rounded font-mono text-slate-600" style="background: #F1F5F9; color: #475569; border: 1px solid #CBD5E1; font-size: 0.72rem; border-radius: 4px;">
                                        {{ $p->slug }}
                                    </span>
                                </td>
                                <td>
                                    @if(!empty($p->benefits))
                                        @php
                                            $lines = array_values(array_filter(array_map('trim', preg_split('/\\r\\n|\\r|\\n/', $p->benefits))));
                                        @endphp
                                        @if(count($lines))
                                            @foreach(array_slice($lines, 0, 2) as $line)
                                                <div class="text-slate-700" style="color: #334155; font-size: 0.8rem;">• {{ Illuminate\Support\Str::limit($line, 50) }}</div>
                                            @endforeach
                                            @if(count($lines) > 2)
                                                <div style="font-size: 0.75rem; color: #94A3B8;">...dan {{ count($lines) - 2 }} benefit lainnya</div>
                                            @endif
                                        @endif
                                    @elseif(!empty($p->description))
                                        <div class="text-slate-600" style="color: #475569; font-size: 0.8rem;">{{ Illuminate\Support\Str::limit($p->description, 70) }}</div>
                                    @else
                                        <div class="text-slate-400" style="color: #94A3B8; font-size: 0.8rem;">—</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold text-emerald-600" style="color: #059669; font-size: 0.95rem;">
                                        Rp {{ number_format($p->price, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td style="text-align: right;">
                                    <div class="d-inline-flex align-items-center gap-2">
                                        <a href="{{ route('admin.packages.edit', $p->id) }}" class="btn-action-icon" title="Edit Paket">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        </a>
                                        <form action="{{ route('admin.packages.destroy', $p->id) }}" method="POST" class="d-inline delete-form">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn-action-icon btn-action-delete btn-delete" title="Hapus Paket">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5" style="color: #64748B;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="d-block mx-auto mb-2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                                    Belum ada data paket yang dibuat.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Footer of the Table -->
                <div class="px-4 py-3.5 bg-slate-50 border-top border-slate-200 text-sm text-slate-600 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2" style="background: #F8FAFC; border-top: 1px solid #E2E8F0; color: #475569; font-size: 0.85rem;">
                    <div>
                        Menampilkan <span class="fw-semibold text-slate-900" style="color: #0F172A; font-weight: 700;">{{ $packages->count() }}</span> paket berlangganan
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Confirm Delete Modal -->
    <div class="modal fade" id="modalConfirm" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <div class="modal-body text-center p-4">
                    <div class="rounded-circle bg-danger-subtle text-danger d-inline-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px; background: #FEF2F2; color: #DC2626;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                    </div>
                    <h5 class="fw-bold text-slate-900 mb-1" style="color: #0F172A;">Hapus Paket Ini?</h5>
                    <p class="text-slate-500 mb-4" style="color: #64748B; font-size: 0.85rem;">
                        Apakah Anda yakin ingin menghapus paket ini? Pengguna yang membeli paket ini dapat terpengaruh.
                    </p>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-light px-4 fw-semibold" data-bs-dismiss="modal" style="border-radius: 8px; font-size: 0.85rem;">Batal</button>
                        <button type="button" id="confirmYes" class="btn btn-danger px-4 fw-semibold" style="border-radius: 8px; background: #DC2626; font-size: 0.85rem;">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {
    const modalEl = document.getElementById("modalConfirm");
    const bsModal = modalEl ? new bootstrap.Modal(modalEl) : null;
    const confirmYes = document.getElementById("confirmYes");
    let currentForm = null;

    document.querySelectorAll(".btn-delete").forEach(btn => {
        btn.addEventListener("click", (e) => {
            e.preventDefault();
            e.stopPropagation();
            currentForm = btn.closest("form");
            if (bsModal) bsModal.show();
        });
    });

    if (confirmYes) {
        confirmYes.addEventListener("click", () => {
            if (currentForm) currentForm.submit();
        });
    }
});
</script>
@endsection
