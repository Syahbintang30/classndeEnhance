@extends('layouts.admin')

@section('title', 'Referral Program Settings')

@section('content')
<div class="container-fluid py-4 max-w-4xl">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h2 class="h4 font-weight-bold text-dark mb-1 flex items-center gap-2">
                <i class="fa-solid fa-gift text-warning"></i> Referral Program Settings
            </h2>
            <p class="text-muted text-sm mb-0">Configure referral discount rewards, track user referral statistics, and export reports.</p>
        </div>
        <a href="{{ route('admin.referral.leaderboard') }}" class="btn btn-outline-secondary btn-sm rounded-3">
            <i class="fa-solid fa-trophy me-1"></i> Leaderboard
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Referral Metrics Row -->
    @if(isset($metrics))
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="w-12 h-12 rounded-3 bg-primary-soft text-primary d-flex align-items-center justify-center fs-4">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <div>
                            <span class="text-xs text-muted font-weight-semibold text-uppercase d-block">Referred Users</span>
                            <h3 class="h4 font-weight-bold text-dark mb-0">{{ number_format($metrics['total_referrals']) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="w-12 h-12 rounded-3 bg-success-soft text-success d-flex align-items-center justify-center fs-4">
                            <i class="fa-solid fa-ticket"></i>
                        </div>
                        <div>
                            <span class="text-xs text-muted font-weight-semibold text-uppercase d-block">Tickets Granted</span>
                            <h3 class="h4 font-weight-bold text-dark mb-0">{{ number_format($metrics['total_referral_tickets']) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="w-12 h-12 rounded-3 bg-warning-soft text-warning d-flex align-items-center justify-center fs-4">
                            <i class="fa-solid fa-coins"></i>
                        </div>
                        <div>
                            <span class="text-xs text-muted font-weight-semibold text-uppercase d-block">Total Discount Given</span>
                            <h3 class="h4 font-weight-bold text-dark mb-0">Rp {{ number_format($metrics['total_discount'], 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Form Card -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
            <h5 class="card-title font-weight-bold text-dark mb-0 text-sm uppercase tracking-wider">Discount Configuration</h5>
            <a href="{{ route('admin.referral.export') }}" class="btn btn-sm btn-outline-success rounded-3">
                <i class="fa-solid fa-file-csv me-1"></i> Export CSV
            </a>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.referral.save') }}">
                @csrf
                <div class="mb-4">
                    <label class="form-label font-weight-bold text-xs text-uppercase text-secondary">Default Referral Discount Percent (0-100%)</label>
                    <div class="input-group" style="max-width: 320px;">
                        <input type="number" name="discount_percent" class="form-control" value="{{ old('discount_percent', $discount) }}" min="0" max="100" required>
                        <span class="input-group-text bg-light text-muted">%</span>
                    </div>
                    <small class="text-muted text-xs d-block mt-1">Discount percentage granted to new users ordering with a valid referral code.</small>
                </div>

                <div class="d-flex justify-content-end pt-3 border-top gap-2">
                    <button type="submit" class="btn btn-primary px-4 py-2.5 rounded-3 font-weight-bold d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i> Save Referral Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
