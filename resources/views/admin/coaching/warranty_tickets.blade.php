@extends('layouts.admin')

@section('title', 'Warranty Tickets')

@section('content')
    <div class="container-fluid py-4">
        @include('admin.coaching._nav')

        <div class="content-wrapper">
            <div class="d-flex justify-content-between align-items-center mb-4 header">
                <div>
                    <h2>Warranty Tickets</h2>
                    <p style="color:#666; font-size:14px">Auto-issued warranty tickets based on session downtime rules. No manual actions here.</p>
                </div>

                <div style="min-width:320px;">
                    <form method="GET" class="d-flex flex-wrap justify-content-end gap-2">
                        <input name="q" value="{{ request('q') }}" class="form-control form-control-sm" style="width:260px;" placeholder="Search user, ticket, booking..." />

                        <select name="status" class="form-select form-select-sm text-white" style="width:150px; background-color: #1a1a1a; border:1px solid #333;">
                            <option value="">All status</option>
                            <option value="available" {{ request('status')=='available' ? 'selected' : '' }}>Available</option>
                            <option value="used" {{ request('status')=='used' ? 'selected' : '' }}>Used</option>
                            <option value="expired" {{ request('status')=='expired' ? 'selected' : '' }}>Expired</option>
                            <option value="rejected" {{ request('status')=='rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>

                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control form-control-sm text-white" style="width:150px; background-color:#1a1a1a; border:1px solid #333;" title="Start date" />
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control form-control-sm text-white" style="width:150px; background-color:#1a1a1a; border:1px solid #333;" title="End date" />

                        <button class="btn btn-sm btn-primary">Filter</button>
                        @if(request()->hasAny(['q', 'status', 'date_from', 'date_to']))
                            <a href="{{ url('/admin/coaching/warranty-tickets') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                        @endif
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover light-custom-table mb-0">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Booking</th>
                                    <th>Warranty Minutes</th>
                                    <th>Status</th>
                                    <th>Issued</th>
                                    <th>Ticket</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tickets as $t)
                                    <tr>
                                        <td>
                                            <div style="font-weight:700">{{ optional($t->user)->name ?? '-' }}</div>
                                            <div class="text-muted" style="font-size:13px">{{ optional($t->user)->email ?? '-' }}</div>
                                        </td>
                                        <td>
                                            <div>#{{ $t->booking_id ?? '-' }}</div>
                                            <div class="text-muted" style="font-size:13px">{{ optional($t->booking)->booking_time ? optional($t->booking)->booking_time->format('d M Y H:i') : '-' }}</div>
                                        </td>
                                        <td>{{ $t->warranty_minutes !== null ? $t->warranty_minutes . ' min' : '-' }}</td>
                                        <td>
                                            @php $s = strtolower($t->status); @endphp
                                            @if($s === 'available')
                                                <span class="badge bg-success">Available</span>
                                            @elseif($s === 'used')
                                                <span class="badge bg-primary">Used</span>
                                            @elseif($s === 'expired')
                                                <span class="badge bg-secondary">Expired</span>
                                            @elseif($s === 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @else
                                                <span class="badge bg-light text-dark">{{ $t->status }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $t->issued_at ? $t->issued_at->format('d M Y H:i') : ($t->created_at ? $t->created_at->format('d M Y H:i') : '-') }}</td>
                                        <td>#{{ $t->ticket_id ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted" style="padding:24px;">No warranty tickets yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if(method_exists($tickets, 'links'))
                    <div class="card-footer bg-white">
                        {{ $tickets->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
