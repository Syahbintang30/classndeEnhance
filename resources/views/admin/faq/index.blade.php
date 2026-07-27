@extends('layouts.admin')

@section('title', 'FAQ Management')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">FAQ Management (Bilingual)</h2>
                <p style="color:#666; font-size:14px">Kelola pertanyaan dan jawaban FAQ dalam 2 Bahasa (Bahasa Indonesia & English).</p>
            </div>
        </div>

        <!-- ADD NEW FAQ CARD -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="mb-3 font-weight-bold text-primary">
                    <i class="fa-solid fa-plus-circle me-1"></i> Tambah Pertanyaan FAQ Baru
                </h5>
                <form method="POST" action="{{ route('admin.faq.store') }}" class="row g-3">
                    @csrf
                    
                    <!-- INDONESIAN SECTION -->
                    <div class="col-md-6 border-end pe-md-4">
                        <div class="badge bg-danger mb-2">🇮🇩 Bahasa Indonesia</div>
                        <div class="mb-3">
                            <label class="form-label font-semibold">Pertanyaan (ID)</label>
                            <input type="text" name="question_id" class="form-control" required placeholder="Contoh: Apa itu Guitarclassbynde?" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label font-semibold">Jawaban (ID)</label>
                            <textarea name="answer_id" class="form-control" rows="4" required placeholder="Masukkan jawaban dalam Bahasa Indonesia"></textarea>
                        </div>
                    </div>

                    <!-- ENGLISH SECTION -->
                    <div class="col-md-6 ps-md-4">
                        <div class="badge bg-primary mb-2">🇬🇧 English</div>
                        <div class="mb-3">
                            <label class="form-label font-semibold">Question (EN)</label>
                            <input type="text" name="question_en" class="form-control" required placeholder="Example: What is Guitarclassbynde?" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label font-semibold">Answer (EN)</label>
                            <textarea name="answer_en" class="form-control" rows="4" required placeholder="Enter answer in English"></textarea>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Urutan (Sort Order)</label>
                        <input type="number" name="sort_order" class="form-control" min="0" value="0" />
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="faqActiveNew" checked>
                            <label class="form-check-label font-semibold" for="faqActiveNew">Aktifkan di Web</label>
                        </div>
                    </div>
                    <div class="col-12 pt-2">
                        <button class="btn btn-primary px-4 font-bold">
                            <i class="fa-solid fa-save me-1"></i> Simpan FAQ Baru
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- FAQ ITEMS LIST -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th style="width:70px;">Urutan</th>
                                <th>Pertanyaan & Jawaban (ID 🇮🇩 / EN 🇬🇧)</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $item)
                                <tr>
                                    <td class="text-center font-mono font-bold">{{ $item->sort_order }}</td>
                                    <td>
                                        <!-- ID Preview -->
                                        <div class="mb-2">
                                            <span class="badge bg-danger text-white text-[10px] me-1">ID</span>
                                            <strong class="text-dark">{{ $item->question_id ?: $item->question }}</strong>
                                            <div class="text-muted text-xs mt-0.5">{{ \Illuminate\Support\Str::limit($item->answer_id ?: $item->answer, 100) }}</div>
                                        </div>
                                        <!-- EN Preview -->
                                        <div>
                                            <span class="badge bg-primary text-white text-[10px] me-1">EN</span>
                                            <span class="text-secondary font-semibold">{{ $item->question_en ?: $item->question }}</span>
                                            <div class="text-muted text-xs mt-0.5">{{ \Illuminate\Support\Str::limit($item->answer_en ?: $item->answer, 100) }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($item->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Non-Aktif</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-primary me-1" type="button" data-bs-toggle="collapse" data-bs-target="#faqEdit{{ $item->id }}">
                                            Edit FAQ
                                        </button>
                                        <form method="POST" action="{{ route('admin.faq.destroy', $item) }}" class="d-inline" onsubmit="return confirm('Hapus FAQ ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                <tr class="collapse" id="faqEdit{{ $item->id }}">
                                    <td colspan="4" class="bg-light p-4">
                                        <form method="POST" action="{{ route('admin.faq.update', $item) }}" class="row g-3">
                                            @csrf
                                            @method('PUT')
                                            
                                            <!-- EDIT ID -->
                                            <div class="col-md-6 border-end pe-md-4">
                                                <div class="badge bg-danger mb-2">🇮🇩 Bahasa Indonesia</div>
                                                <div class="mb-3">
                                                    <label class="form-label font-semibold">Pertanyaan (ID)</label>
                                                    <input type="text" name="question_id" class="form-control" value="{{ $item->question_id ?: $item->question }}" required />
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label font-semibold">Jawaban (ID)</label>
                                                    <textarea name="answer_id" class="form-control" rows="4" required>{{ $item->answer_id ?: $item->answer }}</textarea>
                                                </div>
                                            </div>

                                            <!-- EDIT EN -->
                                            <div class="col-md-6 ps-md-4">
                                                <div class="badge bg-primary mb-2">🇬🇧 English</div>
                                                <div class="mb-3">
                                                    <label class="form-label font-semibold">Question (EN)</label>
                                                    <input type="text" name="question_en" class="form-control" value="{{ $item->question_en ?: $item->question }}" required />
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label font-semibold">Answer (EN)</label>
                                                    <textarea name="answer_en" class="form-control" rows="4" required>{{ $item->answer_en ?: $item->answer }}</textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">Urutan</label>
                                                <input type="number" name="sort_order" class="form-control" min="0" value="{{ $item->sort_order }}" />
                                            </div>
                                            <div class="col-md-3 d-flex align-items-end">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="faqActive{{ $item->id }}" {{ $item->is_active ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="faqActive{{ $item->id }}">Aktifkan</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <button class="btn btn-primary px-4 font-bold">Update FAQ</button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Belum ada item FAQ.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
