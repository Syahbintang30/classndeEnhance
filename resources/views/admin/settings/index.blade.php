@extends('layouts.admin')

@php
    $errors = $errors ?? new \Illuminate\Support\ViewErrorBag;
@endphp

@section('title', 'System Settings')

@section('content')
<div class="container-fluid py-4 max-w-4xl">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h2 class="h4 font-weight-bold text-dark mb-1 flex items-center gap-2">
                <i class="fa-solid fa-sliders text-primary"></i> System Settings
            </h2>
            <p class="text-muted text-sm mb-0">Manage active system configurations, support contacts, and coaching rules.</p>
        </div>
        <form method="POST" action="{{ route('admin.settings.reset') }}" onsubmit="return confirm('Reset all system settings to default values?')">
            @csrf
            <button type="submit" class="btn btn-outline-secondary btn-sm rounded-3">
                <i class="fa-solid fa-rotate-left me-1"></i> Reset to Defaults
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert">
            <strong class="d-block mb-1"><i class="fa-solid fa-triangle-exclamation me-1"></i> Failed to save settings:</strong>
            <ul class="mb-0 ps-3 text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf

        <!-- 1. Customer Support & WhatsApp Contact -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="card-title font-weight-bold text-dark mb-0 text-sm uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-brands fa-whatsapp text-success"></i> Customer Support & Contact
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="whatsapp_number" class="form-label font-weight-bold text-xs text-uppercase text-secondary">WhatsApp Support Number</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="fa-brands fa-whatsapp text-success"></i></span>
                            <input
                                type="text"
                                name="whatsapp_number"
                                id="whatsapp_number"
                                class="form-control"
                                value="{{ old('whatsapp_number', $settings['whatsapp_number']->value ?? '6281234567890') }}"
                                placeholder="6281234567890"
                            >
                        </div>
                        <small class="text-muted text-xs">Used for the floating WhatsApp button and student support inquiries.</small>
                    </div>
                    <div class="col-md-6">
                        <label for="contact_email" class="form-label font-weight-bold text-xs text-uppercase text-secondary">Support Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="fa-regular fa-envelope"></i></span>
                            <input
                                type="email"
                                name="contact_email"
                                id="contact_email"
                                class="form-control"
                                value="{{ old('contact_email', $settings['contact_email']->value ?? 'support@guitarclassbynde.com') }}"
                                placeholder="support@guitarclassbynde.com"
                            >
                        </div>
                        <small class="text-muted text-xs">Official support email for student help requests.</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Coaching Session Rules -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="card-title font-weight-bold text-dark mb-0 text-sm uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-user-ninja text-primary"></i> Coaching Window Rules
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6 col-xl-3">
                        <label for="max_booking_days" class="form-label font-weight-bold text-xs text-uppercase text-secondary">Max Days Ahead</label>
                        <input
                            type="number"
                            name="coaching.max_booking_days_ahead"
                            id="max_booking_days"
                            class="form-control"
                            value="{{ old('coaching.max_booking_days_ahead', $settings['coaching.max_booking_days_ahead']->value ?? '30') }}"
                            min="1"
                            max="365"
                        >
                        <small class="text-muted text-xs">Days in advance students can book.</small>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <label for="session_duration" class="form-label font-weight-bold text-xs text-uppercase text-secondary">Session Duration (Mins)</label>
                        <input
                            type="number"
                            name="coaching.session_duration_minutes"
                            id="session_duration"
                            class="form-control"
                            value="{{ old('coaching.session_duration_minutes', $settings['coaching.session_duration_minutes']->value ?? '60') }}"
                            min="15"
                            max="240"
                        >
                        <small class="text-muted text-xs">Standard duration per session.</small>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <label for="buffer_before" class="form-label font-weight-bold text-xs text-uppercase text-secondary">Join Before (Mins)</label>
                        <input
                            type="number"
                            name="coaching.buffer_minutes_before"
                            id="buffer_before"
                            class="form-control"
                            value="{{ old('coaching.buffer_minutes_before', $settings['coaching.buffer_minutes_before']->value ?? '10') }}"
                            min="0"
                            max="60"
                        >
                        <small class="text-muted text-xs">Join window before start.</small>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <label for="buffer_after" class="form-label font-weight-bold text-xs text-uppercase text-secondary">Join After (Mins)</label>
                        <input
                            type="number"
                            name="coaching.buffer_minutes_after"
                            id="buffer_after"
                            class="form-control"
                            value="{{ old('coaching.buffer_minutes_after', $settings['coaching.buffer_minutes_after']->value ?? '60') }}"
                            min="0"
                            max="120"
                        >
                        <small class="text-muted text-xs">Allowed delay after start time.</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Referral Program Settings -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="card-title font-weight-bold text-dark mb-0 text-sm uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-gift text-warning"></i> Referral Program Settings
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="referral_discount" class="form-label font-weight-bold text-xs text-uppercase text-secondary">Referral Discount Percentage (%)</label>
                        <div class="input-group">
                            <input
                                type="number"
                                name="referral.discount_percent"
                                id="referral_discount"
                                class="form-control"
                                value="{{ old('referral.discount_percent', $settings['referral.discount_percent']->value ?? '10') }}"
                                min="0"
                                max="100"
                            >
                            <span class="input-group-text bg-light text-muted">%</span>
                        </div>
                        <small class="text-muted text-xs">Discount applied when a new student uses a friend's referral code.</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Email Notifications -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="card-title font-weight-bold text-dark mb-0 text-sm uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-regular fa-bell text-info"></i> Email Notifications
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-check form-switch">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="notifications.admin_booking_enabled"
                                id="admin_booking_notifications"
                                value="1"
                                {{ (old('notifications.admin_booking_enabled', $settings['notifications.admin_booking_enabled']->value ?? 'true')) === 'true' ? 'checked' : '' }}
                            >
                            <label class="form-check-label font-weight-bold text-dark text-sm" for="admin_booking_notifications">Admin Booking Email Alerts</label>
                            <small class="text-muted d-block">Automatically notify admins via email when a student schedules a coaching session.</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check form-switch">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="notifications.user_booking_status_enabled"
                                id="user_status_notifications"
                                value="1"
                                {{ (old('notifications.user_booking_status_enabled', $settings['notifications.user_booking_status_enabled']->value ?? 'true')) === 'true' ? 'checked' : '' }}
                            >
                            <label class="form-check-label font-weight-bold text-dark text-sm" for="user_status_notifications">User Status Email Alerts</label>
                            <small class="text-muted d-block">Notify students when their coaching booking status updates (confirmed/rescheduled).</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="d-flex justify-content-end mb-4">
            <button type="submit" class="btn btn-primary px-5 py-2.5 rounded-3 font-weight-bold shadow-sm d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-floppy-disk"></i> Save System Settings
            </button>
        </div>
    </form>
</div>
@endsection
