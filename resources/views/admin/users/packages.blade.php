@extends('layouts.admin')

@section('title','User Packages & Coaching Tickets')

@section('content')
<div class="header mb-4">
    <h2>User Packages & Coaching Tickets</h2>
    <p>List of users, their selected package (if any) and available coaching tickets.</p>
</div>

@if(session('success'))
    <div class="alert alert-success" id="success-alert">
        {{ session('success') }}
    </div>

    <script>
        setTimeout(function() {
            let alert = document.getElementById('success-alert');
            if (alert) {
                alert.style.transition = "opacity 0.5s ease";
                alert.style.opacity = "0";
                setTimeout(() => alert.remove(), 500);
            }
        }, 3000);
    </script>
@endif

<div class="card-table">
    <table class="custom-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Package</th>
                <th>Available / Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $u)
                <tr>
                    <td>{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td>
                        {{-- Prefer actual package relation from DB; fallback to rolePackages if needed --}}
                        @if($u->package)
                            {{ $u->package->name }}
                        @elseif($u->package_id && isset($rolePackages[$u->package_id]))
                            {{ $rolePackages[$u->package_id]->name }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @php
                            $avail = $u->available_tickets_count ?? 0;
                            $total = $u->total_tickets_count ?? 0;
                            $badgeClass = $avail > 0 ? 'badge bg-success' : 'badge bg-danger';
                        @endphp
                        <span class="{{ $badgeClass }}">{{ $avail }} / {{ $total }}</span>
                    </td>
                    {{-- Actions removed per request --}}
                </tr>
            @empty
                <tr style="pointer-events: none; background: transparent;">
                    <td colspan="4" class="text-center pt-5">Belum ada users</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $users->links('pagination::bootstrap-5') }}
</div>

{{ $users->links() }}

@endsection
