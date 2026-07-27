@extends('layouts.admin')

@php
    $errors = $errors ?? new \Illuminate\Support\ViewErrorBag;
@endphp

@section('title', 'Edit Voucher')

@section('content')
<div class="container-fluid py-4 max-w-3xl">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="h4 font-weight-bold text-dark mb-1">Edit Voucher</h2>
            <p class="text-muted text-sm mb-0">Update settings for voucher <code>{{ $voucher->code }}</code>.</p>
        </div>
        <a href="{{ route('admin.vouchers.index') }}" class="btn btn-outline-secondary btn-sm rounded-3">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Vouchers
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
            <strong class="d-block mb-1">Please fix the following errors:</strong>
            <ul class="mb-0 ps-3 text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.vouchers.update', $voucher->id) }}">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label font-weight-bold text-xs text-uppercase text-secondary">Voucher Code</label>
                        <input name="code" class="form-control font-mono uppercase" required value="{{ old('code', $voucher->code) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-weight-bold text-xs text-uppercase text-secondary">Discount Percentage (%)</label>
                        <input type="number" name="discount_percent" class="form-control" value="{{ old('discount_percent', $voucher->discount_percent) }}" min="0" max="100" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-weight-bold text-xs text-uppercase text-secondary">Usage Limit (Optional)</label>
                        <input type="number" name="usage_limit" class="form-control" value="{{ old('usage_limit', $voucher->usage_limit) }}" placeholder="Leave blank for unlimited">
                        <small class="text-muted text-xs">Currently used: <strong>{{ $voucher->used_count }}</strong> times.</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-weight-bold text-xs text-uppercase text-secondary">Expires At (Optional)</label>
                        <input type="datetime-local" name="expires_at" class="form-control" value="{{ old('expires_at', optional($voucher->expires_at)->format('Y-m-d\TH:i')) }}">
                    </div>
                    <div class="col-12">
                        <div class="form-check form-switch pt-2">
                            <input class="form-check-input" type="checkbox" name="active" id="activeToggle" value="1" {{ old('active', $voucher->active) ? 'checked' : '' }}>
                            <label class="form-check-label font-weight-semibold text-dark" for="activeToggle">Voucher Active Status</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('admin.vouchers.index') }}" class="btn btn-light rounded-3 px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 font-weight-bold">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Update Voucher
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
