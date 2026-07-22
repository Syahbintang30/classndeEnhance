@extends('layouts.admin')

@section('title', $lesson->title . ' - Daftar Topic')

@section('content')
    @push('styles')
        <style>
            .topics-page {
                font-family: 'Inter', system-ui, -apple-system, sans-serif;
                background-color: #F8FAFC;
            }

            .table-topics {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
            }

            .table-topics th {
                background: #F8FAFC;
                padding: 14px 20px;
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: #475569;
                border-bottom: 1px solid #E2E8F0;
            }

            .table-topics td {
                padding: 16px 20px;
                border-bottom: 1px solid #F1F5F9;
                vertical-align: middle;
                font-size: 13px;
                background: #FFFFFF;
            }

            .table-topics tr:last-child td {
                border-bottom: none;
            }

            .table-topics tr:hover td {
                background-color: #F8FAFC;
            }

            .btn-action-icon {
                width: 32px;
                height: 32px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 8px;
                border: 1px solid #E2E8F0;
                background: #FFFFFF;
                color: #475569;
                transition: all 0.15s ease;
                cursor: pointer;
            }

            .btn-action-icon:hover {
                background: #F1F5F9;
                color: #0F172A;
                border-color: #CBD5E1;
            }

            .btn-action-delete:hover {
                background: #FEF2F2 !important;
                color: #DC2626 !important;
                border-color: #FCA5A5 !important;
            }

            .badge-bunny {
                background: #ECFDF5;
                color: #047857;
                border: 1px solid #A7F3D0;
                border-radius: 6px;
                padding: 4px 10px;
                font-size: 0.75rem;
                font-weight: 600;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                text-decoration: none;
            }

            .badge-bunny:hover {
                background: #D1FAE5;
                color: #065F46;
            }
        </style>
    @endpush

    <div class="topics-page min-h-screen p-3 md:p-6">
        <div class="max-w-7xl mx-auto space-y-6">

            <!-- Sub Navigation & Breadcrumb -->
            <div class="d-flex align-items-center justify-content-between mb-2">
                <a href="{{ route('admin.lessons.index') }}" class="d-inline-flex align-items-center gap-2 text-decoration-none fw-semibold text-slate-600 hover:text-blue-600 transition-colors" style="font-size: 0.875rem; color: #475569;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    <span>Kembali ke Daftar Lesson</span>
                </a>
            </div>

            <!-- Header Section -->
            <div class="d-flex flex-column flex-md-row md:items-start justify-content-between gap-3 mb-4">
                <div>
                    <div class="d-flex align-items-center gap-3 mb-1">
                        <h1 class="h4 fw-bold text-slate-900 tracking-tight mb-0" style="color: #0F172A; font-weight: 800; font-size: 1.5rem;">
                            {{ $lesson->title }}
                        </h1>
                        <span class="px-2.5 py-0.5 rounded-pill bg-blue-100 text-blue-700 fw-semibold" style="font-size: 0.75rem; background: #DBEAFE; color: #1D4ED8;">
                            Total {{ $topics->total() ?? $topics->count() }} Topic
                        </span>
                    </div>
                    <p class="text-slate-500 mb-0" style="color: #64748B; font-size: 0.875rem;">
                        Kelola urutan materi, video Bunny.net GUID, serta deskripsi pembelajaran untuk modul ini.
                    </p>
                </div>
                <div>
                    <a href="{{ route('admin.topics.create', $lesson->id) }}" class="btn btn-primary d-inline-flex align-items-center gap-2 px-4 py-2.5 rounded-lg fw-semibold shadow-xs" style="background: #2563EB; border: none; font-size: 0.875rem; border-radius: 8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        <span>Tambah Topic Baru</span>
                    </a>
                </div>
            </div>

            <!-- List Section Card -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-4" style="border-radius: 12px; border: 1px solid #E2E8F0;">
                <div class="table-responsive">
                    <table class="table-topics mb-0">
                        <thead>
                            <tr>
                                <th style="width: 8%;">POSISI</th>
                                <th style="width: 32%;">JUDUL TOPIC</th>
                                <th style="width: 25%;">VIDEO STREAM (BUNNY / URL)</th>
                                <th style="width: 25%;">DESKRIPSI</th>
                                <th style="width: 10%; text-align: right;">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($topics as $topic)
                            <tr>
                                <td>
                                    <span class="px-2.5 py-1 rounded font-mono fw-bold text-slate-700" style="background: #F1F5F9; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.78rem;">
                                        {{ str_pad($topic->position, 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold text-slate-900" style="color: #0F172A; font-size: 0.9rem;">
                                        {{ $topic->title }}
                                    </div>
                                </td>
                                <td>
                                    @if($topic->bunny_guid)
                                        <a href="{{ route('topics.stream', $topic->id) }}" target="_blank" class="badge-bunny">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                                            <span>Stream Bunny.net</span>
                                        </a>
                                    @elseif(!empty($topic->video_url))
                                        <a href="{{ $topic->video_url }}" target="_blank" class="badge-bunny" style="background: #EFF6FF; color: #1D4ED8; border-color: #BFDBFE;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                                            <span>External Video</span>
                                        </a>
                                    @else
                                        <span class="text-slate-400 font-mono" style="font-size: 0.8rem; color: #94A3B8;">- Belum Ada Video -</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-slate-600" style="color: #475569; font-size: 0.85rem;">
                                        {{ Str::limit($topic->description ?: 'Tidak ada deskripsi', 70) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-end gap-1.5">
                                        <a href="{{ route('admin.topics.edit', [$lesson->id, $topic->id]) }}" class="btn-action-icon" title="Edit Topic">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        </a>

                                        <button type="button" class="btn-action-icon btn-action-delete" title="Hapus Topic" onclick="confirmDeleteTopic('{{ route('admin.topics.destroy', [$lesson->id, $topic->id]) }}', '{{ addslashes($topic->title) }}')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5" style="color: #64748B;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="d-block mx-auto mb-2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                                    Belum ada topic pembelajaran pada lesson ini.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Footer Table -->
                <div class="px-4 py-3.5 bg-slate-50 border-top border-slate-200 text-sm text-slate-600 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2" style="background: #F8FAFC; border-top: 1px solid #E2E8F0; color: #475569; font-size: 0.85rem;">
                    <div>
                        Menampilkan <span class="fw-semibold text-slate-900" style="color: #0F172A; font-weight: 700;">{{ $topics->count() }}</span> dari {{ $topics->total() }} topic
                    </div>
                    <div>
                        {{ $topics->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Hidden Delete Form -->
    <form id="deleteTopicForm" action="" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- Modal Delete Confirmation (Bootstrap 5) -->
    <div class="modal fade" id="deleteTopicModal" tabindex="-1" aria-labelledby="deleteTopicModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <div class="modal-body p-4 text-center">
                    <div class="d-inline-flex align-items-center justify-content-center bg-red-100 text-red-600 rounded-circle mb-3" style="width: 54px; height: 54px; background: #FEF2F2; color: #DC2626;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                    </div>
                    <h5 class="fw-bold text-slate-900 mb-2" style="color: #0F172A; font-weight: 800;">Hapus Topic Pembelajaran?</h5>
                    <p class="text-slate-500 mb-4" style="font-size: 0.875rem; color: #64748B;">
                        Apakah Anda yakin ingin menghapus topic <strong id="deleteTopicTitle" style="color: #0F172A;"></strong>? Tindakan ini tidak dapat dibatalkan.
                    </p>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-light px-4 py-2 rounded-lg font-semibold" data-bs-dismiss="modal" style="border-radius: 8px; font-size: 0.875rem;">
                            Batal
                        </button>
                        <button type="button" class="btn btn-danger px-4 py-2 rounded-lg font-semibold" onclick="submitDeleteTopic()" style="background: #DC2626; border: none; border-radius: 8px; font-size: 0.875rem;">
                            Ya, Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function confirmDeleteTopic(actionUrl, topicTitle) {
                const form = document.getElementById('deleteTopicForm');
                form.action = actionUrl;
                document.getElementById('deleteTopicTitle').textContent = topicTitle;
                
                const deleteModal = new bootstrap.Modal(document.getElementById('deleteTopicModal'));
                deleteModal.show();
            }

            function submitDeleteTopic() {
                document.getElementById('deleteTopicForm').submit();
            }
        </script>
    @endpush
@endsection
