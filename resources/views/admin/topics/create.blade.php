@extends('layouts.admin')

@section('title', 'Tambah Topic - ' . $lesson->title)

@section('content')
    @push('styles')
        <style>
            .topics-create-page {
                font-family: 'Inter', system-ui, -apple-system, sans-serif;
                background-color: #F8FAFC;
            }
        </style>
    @endpush

    <div class="topics-create-page min-h-screen p-3 md:p-6">
        <div class="max-w-4xl mx-auto space-y-6">

            <!-- Sub Navigation & Breadcrumb -->
            <div class="d-flex align-items-center justify-content-between mb-2">
                <a href="{{ route('admin.lessons.show', $lesson->id) }}" class="d-inline-flex align-items-center gap-2 text-decoration-none fw-semibold text-slate-600 hover:text-blue-600 transition-colors" style="font-size: 0.875rem; color: #475569;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    <span>Kembali ke Topic {{ $lesson->title }}</span>
                </a>
            </div>

            <!-- Header Section -->
            <div class="mb-4">
                <h1 class="h4 fw-bold text-slate-900 tracking-tight mb-1" style="color: #0F172A; font-weight: 800; font-size: 1.5rem;">
                    Tambah Topic Pembelajaran
                </h1>
                <p class="text-slate-500 mb-0" style="color: #64748B; font-size: 0.875rem;">
                    Modul: <strong class="text-slate-800">{{ $lesson->title }}</strong>
                </p>
            </div>

            <!-- Form Card Container -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 p-md-5 mb-4" style="border-radius: 14px; border: 1px solid #E2E8F0;">
                <form action="{{ route('admin.topics.store', $lesson->id) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label font-semibold text-slate-700 mb-1.5" style="font-size: 0.875rem; color: #334155; font-weight: 600;">
                            Judul Topic <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title') }}" class="form-control" style="border-radius: 8px; border: 1px solid #CBD5E1; height: 42px; font-size: 0.875rem;" placeholder="Contoh: Pengenalan Bagian-bagian Pada Gitar" required />
                    </div>

                    <div class="mb-4">
                        <label class="form-label font-semibold text-slate-700 mb-1.5" style="font-size: 0.875rem; color: #334155; font-weight: 600;">
                            Bunny Video ID (GUID)
                        </label>
                        <input type="text" name="bunny_guid" class="form-control font-mono" style="border-radius: 8px; border: 1px solid #CBD5E1; height: 42px; font-size: 0.85rem;" placeholder="Contoh: 123e4567-e89b-12d3-a456-426614174000" value="{{ old('bunny_guid') }}" />
                        <div class="form-text mt-1.5" style="font-size: 0.78rem; color: #64748B;">
                            Upload video langsung di panel Bunny.net, lalu salin Video GUID dan tempelkan di sini.
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label font-semibold text-slate-700 mb-1.5" style="font-size: 0.875rem; color: #334155; font-weight: 600;">
                            Deskripsi Materi
                        </label>
                        <textarea name="description" rows="4" class="form-control" style="border-radius: 8px; border: 1px solid #CBD5E1; font-size: 0.875rem;" placeholder="Penjelasan singkat mengenai materi dalam video ini...">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label font-semibold text-slate-700 mb-1.5" style="font-size: 0.875rem; color: #334155; font-weight: 600;">
                            Urutan Posisi <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="position" value="{{ old('position', 1) }}" class="form-control" style="border-radius: 8px; border: 1px solid #CBD5E1; height: 42px; font-size: 0.875rem; width: 140px;" required />
                    </div>

                    <div class="d-flex align-items-center justify-content-end gap-2.5 pt-3 border-top border-slate-200">
                        <a href="{{ route('admin.lessons.show', $lesson->id) }}" class="btn btn-light px-4 py-2 rounded-lg font-semibold" style="border-radius: 8px; border: 1px solid #CBD5E1; font-size: 0.875rem; color: #475569;">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary px-4 py-2 rounded-lg font-semibold" style="background: #2563EB; border: none; border-radius: 8px; font-size: 0.875rem;">
                            Simpan Topic
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
