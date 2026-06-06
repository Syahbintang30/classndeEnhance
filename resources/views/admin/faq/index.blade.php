@extends('layouts.admin')

@section('title', 'FAQ')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">FAQ</h2>
                <p style="color:#666; font-size:14px">Manage the questions and answers shown on the landing page.</p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="mb-3">Add FAQ Item</h5>
                <form method="POST" action="{{ route('admin.faq.store') }}" class="row g-3">
                    @csrf
                    <div class="col-12">
                        <label class="form-label">Question</label>
                        <input type="text" name="question" class="form-control" required maxlength="255" placeholder="Enter a question" />
                    </div>
                    <div class="col-12">
                        <label class="form-label">Answer</label>
                        <textarea name="answer" class="form-control" rows="4" required placeholder="Enter an answer"></textarea>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Order</label>
                        <input type="number" name="sort_order" class="form-control" min="0" value="0" />
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="faqActiveNew" checked>
                            <label class="form-check-label" for="faqActiveNew">Active</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Question</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $item)
                                <tr>
                                    <td style="width:90px;">{{ $item->sort_order }}</td>
                                    <td>
                                        <div style="font-weight:600">{{ $item->question }}</div>
                                        <div class="text-muted" style="font-size:13px">{{ \Illuminate\Support\Str::limit($item->answer, 120) }}</div>
                                    </td>
                                    <td style="width:120px;">
                                        @if($item->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-end" style="width:280px;">
                                        <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#faqEdit{{ $item->id }}">Edit</button>
                                        <form method="POST" action="{{ route('admin.faq.destroy', $item) }}" class="d-inline" onsubmit="return confirm('Delete this FAQ item?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                <tr class="collapse" id="faqEdit{{ $item->id }}">
                                    <td colspan="4" class="bg-light">
                                        <form method="POST" action="{{ route('admin.faq.update', $item) }}" class="row g-3">
                                            @csrf
                                            @method('PUT')
                                            <div class="col-12">
                                                <label class="form-label">Question</label>
                                                <input type="text" name="question" class="form-control" value="{{ $item->question }}" required maxlength="255" />
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Answer</label>
                                                <textarea name="answer" class="form-control" rows="4" required>{{ $item->answer }}</textarea>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Order</label>
                                                <input type="number" name="sort_order" class="form-control" min="0" value="{{ $item->sort_order }}" />
                                            </div>
                                            <div class="col-md-3 d-flex align-items-end">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="faqActive{{ $item->id }}" {{ $item->is_active ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="faqActive{{ $item->id }}">Active</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <button class="btn btn-primary">Update</button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted" style="padding:24px;">No FAQ items.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
