@extends('layouts.admin')

@section('title', 'Referral Leaderboard')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h2 class="h4 font-weight-bold text-dark mb-1 flex items-center gap-2">
                <i class="fa-solid fa-trophy text-warning"></i> Referral Leaderboard
            </h2>
            <p class="text-muted text-sm mb-0">Rankings of top users based on successful invitation referrals.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ Route::has('admin.referral.settings') ? route('admin.referral.settings') : '#' }}" class="btn btn-outline-secondary btn-sm rounded-3">
                <i class="fa-solid fa-gear me-1"></i> Referral Settings
            </a>
        </div>
    </div>

    <!-- Leaderboard Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="width: 80px;">Rank</th>
                            <th class="py-3 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">User Member</th>
                            <th class="py-3 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Successful Referrals</th>
                            <th class="pe-4 py-3 text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @php $rank = 1; @endphp
                        @forelse($rows as $row)
                            @php $u = $users->get($row->referred_by); @endphp
                            <tr>
                                <td class="ps-4">
                                    @if($rank === 1)
                                        <span class="badge bg-warning text-dark font-weight-bolder rounded-circle p-2 shadow-sm" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">🥇 1</span>
                                    @elseif($rank === 2)
                                        <span class="badge bg-secondary text-white font-weight-bolder rounded-circle p-2 shadow-sm" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">🥈 2</span>
                                    @elseif($rank === 3)
                                        <span class="badge bg-amber-700 text-white font-weight-bolder rounded-circle p-2 shadow-sm" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; background-color: #cd7f32;">🥉 3</span>
                                    @else
                                        <span class="badge bg-light text-dark font-weight-bold rounded-circle border p-2" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">#{{ $rank }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-initial rounded-circle bg-primary text-white font-weight-bold d-flex align-items-center justify-center shadow-sm" style="width: 38px; height: 38px; font-size: 14px;">
                                            {{ mb_substr($u->name ?? 'U', 0, 1) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0 text-sm font-weight-bold text-dark">{{ $u ? $u->name : ('User ID #' . $row->referred_by) }}</h6>
                                            <span class="text-xs text-muted">{{ $u->email ?? '-' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary-soft text-primary font-weight-bold px-3 py-1.5 rounded-pill text-xs">
                                        <i class="fa-solid fa-users me-1"></i> {{ $row->referrals }} invited
                                    </span>
                                </td>
                                <td class="pe-4 text-end">
                                    @if($u)
                                        <a href="{{ route('admin.referral.users', $u->id) }}" class="btn btn-sm btn-outline-primary rounded-3 px-3 py-1 text-xs font-weight-semibold">
                                            <i class="fa-solid fa-list-check me-1"></i> View Invites
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @php $rank++; @endphp
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <div class="py-4">
                                        <i class="fa-solid fa-award text-secondary opacity-50 mb-3" style="font-size: 3rem;"></i>
                                        <p class="mb-0">No referral leaderboard data recorded yet.</p>
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
