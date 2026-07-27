@extends('layouts.admin')

@section('title', 'Manage Vouchers')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h2 class="h4 font-weight-bold text-dark mb-1 flex items-center gap-2">
                <i class="fa-solid fa-ticket text-primary"></i> Discount Vouchers
            </h2>
            <p class="text-muted text-sm mb-0">Create and manage promotional discount codes for checkout.</p>
        </div>
        <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary px-4 py-2.5 rounded-3 d-inline-flex align-items-center gap-2 shadow-sm font-weight-bold">
            <i class="fa-solid fa-plus"></i> Add New Voucher
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Vouchers Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Voucher Code</th>
                            <th class="py-3 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Discount</th>
                            <th class="py-3 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                            <th class="py-3 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Usage</th>
                            <th class="py-3 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Expires At</th>
                            <th class="pe-4 py-3 text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($vouchers as $v)
                            <tr>
                                <td class="ps-4">
                                    <span class="badge bg-primary-soft text-primary font-mono text-xs px-3 py-2 rounded-2 border border-primary-subtle font-weight-bold">
                                        <i class="fa-solid fa-tag me-1"></i> {{ $v->code }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-success-soft text-success text-xs px-2.5 py-1.5 rounded-2 font-weight-bold">
                                        {{ $v->discount_percent }}% OFF
                                    </span>
                                </td>
                                <td>
                                    @if($v->active)
                                        <span class="badge bg-success text-white text-xs px-2.5 py-1 rounded-pill">Active</span>
                                    @else
                                        <span class="badge bg-secondary text-white text-xs px-2.5 py-1 rounded-pill">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-sm font-weight-semibold text-dark">
                                        {{ $v->used_count }}{{ $v->usage_limit ? ' / '.$v->usage_limit : ' (Unlimited)' }}
                                    </span>
                                </td>
                                <td>
                                    @if($v->expires_at)
                                        <span class="text-xs text-muted">
                                            <i class="fa-regular fa-calendar me-1"></i> {{ \Carbon\Carbon::parse($v->expires_at)->format('d M Y H:i') }}
                                        </span>
                                    @else
                                        <span class="text-xs text-muted font-italic">No Expiration</span>
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-inline-flex gap-2">
                                        <a href="{{ route('admin.vouchers.edit', $v->id) }}" class="btn btn-sm btn-outline-secondary rounded-2 px-2.5 py-1.5" title="Edit Voucher">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('admin.vouchers.destroy', $v->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete voucher {{ $v->code }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-2 px-2.5 py-1.5" title="Delete Voucher">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <div class="py-4">
                                        <i class="fa-solid fa-ticket text-secondary opacity-50 mb-3" style="font-size: 3rem;"></i>
                                        <p class="mb-0">No vouchers available yet.</p>
                                        <a href="{{ route('admin.vouchers.create') }}" class="btn btn-link btn-sm mt-2 text-decoration-none">Create your first voucher</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
