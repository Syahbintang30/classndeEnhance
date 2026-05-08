@extends('layouts.admin')

@section('title', 'System Settings')

@section('content')
<div class="container-fluid py-4 settings-clean-page">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h2 class="mb-1">System Settings</h2>
            <p class="mb-0 text-muted">Manage the core system configuration in one place.</p>
        </div>
        <form method="POST" action="{{ route('admin.settings.reset') }}" onsubmit="return confirm('Reset all settings to default?')">
            @csrf
            <button type="submit" class="btn btn-outline-secondary btn-sm">Reset Default</button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Failed to save.</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Package Access</h5>
                    <small class="text-muted">Packages that are considered to have intermediate access.</small>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-lg-6">
                        <label for="intermediate_package_id" class="form-label">Primary Package</label>
                        <select name="intermediate_package_id" id="intermediate_package_id" class="form-select">
                            <option value="">Select a package</option>
                            @foreach($packages as $package)
                                <option value="{{ $package->id }}" {{ ($settings['intermediate_package_id']->value ?? '2') == $package->id ? 'selected' : '' }}>
                                    {{ $package->name }} (ID {{ $package->id }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <label for="intermediate_package_slugs" class="form-label">Fallback Slugs</label>
                        <input
                            type="text"
                            name="intermediate_package_slugs"
                            id="intermediate_package_slugs"
                            class="form-control"
                            value="{{ $settings['intermediate_package_slugs']->value ?? 'intermediate,upgrade-intermediate' }}"
                            placeholder="intermediate,upgrade-intermediate"
                        >
                        <small class="text-muted">Separate values with commas.</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Coaching Window</h5>
                <small class="text-muted">Control booking limits and the allowed session join window.</small>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6 col-xl-3">
                        <label for="max_booking_days" class="form-label">Max Days Ahead</label>
                        <input
                            type="number"
                            name="coaching.max_booking_days_ahead"
                            id="max_booking_days"
                            class="form-control"
                            value="{{ $settings['coaching.max_booking_days_ahead']->value ?? '30' }}"
                            min="1"
                            max="365"
                        >
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <label for="session_duration" class="form-label">Session Duration</label>
                        <input
                            type="number"
                            name="coaching.session_duration_minutes"
                            id="session_duration"
                            class="form-control"
                            value="{{ $settings['coaching.session_duration_minutes']->value ?? '60' }}"
                            min="15"
                            max="240"
                        >
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <label for="buffer_before" class="form-label">Join Before</label>
                        <input
                            type="number"
                            name="coaching.buffer_minutes_before"
                            id="buffer_before"
                            class="form-control"
                            value="{{ $settings['coaching.buffer_minutes_before']->value ?? '10' }}"
                            min="0"
                            max="60"
                        >
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <label for="buffer_after" class="form-label">Join After</label>
                        <input
                            type="number"
                            name="coaching.buffer_minutes_after"
                            id="buffer_after"
                            class="form-control"
                            value="{{ $settings['coaching.buffer_minutes_after']->value ?? '60' }}"
                            min="0"
                            max="120"
                        >
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Notifications</h5>
                <small class="text-muted">Enable or disable notification emails.</small>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-check form-switch">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="notifications.admin_booking_enabled"
                                id="admin_booking_notifications"
                                value="1"
                                {{ ($settings['notifications.admin_booking_enabled']->value ?? 'true') === 'true' ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="admin_booking_notifications">Admin booking email</label>
                            <small class="text-muted d-block">Send an email to the admin when a user creates a booking.</small>
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
                                {{ ($settings['notifications.user_booking_status_enabled']->value ?? 'true') === 'true' ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="user_status_notifications">User status email</label>
                            <small class="text-muted d-block">Send an email to the user when the booking status changes.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary px-4">Save Settings</button>
        </div>
    </form>

    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Package Reference</h6>
            <small class="text-muted">Quick reference for package IDs and slugs.</small>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table light-custom-table mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($packages as $package)
                            <tr>
                                <td>{{ $package->id }}</td>
                                <td>{{ $package->name }}</td>
                                <td>{{ $package->slug }}</td>
                                <td>Rp {{ number_format($package->price, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
