@extends('layouts.admin')

@section('title', 'Transactions')

@push('styles')
<style>
    .txn-page .txn-toolbar {
        padding: 14px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        background: rgba(255, 255, 255, 0.01);
    }

    .txn-page .txn-toolbar-grid {
        display: grid;
        grid-template-columns: 160px minmax(0, 1fr) auto;
        gap: 10px;
        align-items: center;
    }

    .txn-page .txn-date-row,
    .txn-page .txn-preset-row,
    .txn-page .txn-search-row {
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap;
    }

    .txn-page .txn-date-row .input-group,
    .txn-page .txn-search-row .form-control {
        width: 170px;
    }

    .txn-page .txn-search-row .form-control {
        width: 230px;
    }

    .txn-page .txn-preset-row .btn {
        min-width: 78px;
    }

    .txn-page .txn-table th,
    .txn-page .txn-table td {
        vertical-align: middle;
    }

    .txn-page .txn-table thead th {
        color: #1e293b;
        font-weight: 700;
        letter-spacing: 0.01em;
        background: linear-gradient(180deg, #f8fbff 0%, #eef4ff 100%);
        border-bottom: 1px solid rgba(15, 23, 42, 0.08);
        white-space: nowrap;
    }

    .txn-page .txn-table tbody tr:hover {
        background: rgba(29, 78, 216, 0.035);
    }

    .txn-page .txn-order-id {
        font-weight: 700;
        letter-spacing: 0.2px;
    }

    .txn-page .txn-amount {
        font-weight: 700;
        white-space: nowrap;
    }

    .txn-page .txn-created {
        white-space: nowrap;
    }

    .txn-page .txn-created-pill {
        display: inline-flex;
        align-items: center;
        padding: 0.34rem 0.68rem;
        border-radius: 999px;
        background: #eef4ff;
        color: #1e293b;
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 0.01em;
        font-variant-numeric: tabular-nums;
        white-space: nowrap;
    }

    @media (max-width: 1200px) {
        .txn-page .txn-toolbar-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .txn-page .txn-search-row {
            justify-content: flex-start;
        }
    }
</style>
@endpush

@section('content')
<div class="container txn-page">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-start mb-3 header">
                <div>
                    <h2 class="mb-1">Transactions</h2>
                    <p class="mb-0 text-muted">Track payment history and quickly filter by date range.</p>
                </div>
            </div>

            <div class="card">
                <div class="card-body p-0">
                    <div class="txn-toolbar">
                        <form id="txn-filter-form" method="GET" action="{{ route('admin.transactions.index') }}" class="txn-toolbar-grid">
                            <select name="status" class="form-select form-select-sm">
                                <option value="">All statuses</option>
                                <option value="pending" {{ (isset($status) && $status==='pending') ? 'selected' : '' }}>Pending</option>
                                <option value="settlement" {{ (isset($status) && $status==='settlement') ? 'selected' : '' }}>Settlement</option>
                            </select>

                            <div class="txn-date-row">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text" title="From"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg></span>
                                    <input type="date" name="from" id="filter-from" value="{{ request()->get('from') }}" class="form-control form-control-sm" title="From date" />
                                </div>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text" title="To"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg></span>
                                    <input type="date" name="to" id="filter-to" value="{{ request()->get('to') }}" class="form-control form-control-sm" title="To date" />
                                </div>
                                <div class="txn-preset-row">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" data-preset="today">Today</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" data-preset="week">This Week</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" data-preset="month">This Month</button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" id="preset-clear">Clear</button>
                                </div>
                            </div>

                            <div class="txn-search-row">
                                <input name="q" value="{{ $search ?? '' }}" placeholder="Search order id" class="form-control form-control-sm" />
                                <button class="btn btn-sm btn-primary" type="submit">Filter</button>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="light-custom-table table-sm mb-0 txn-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Order ID</th>
                                    <th>User</th>
                                    <th>Package</th>
                                    <th>Method</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($txns as $txn)
                                    @php
                                        $status = strtolower((string) $txn->status);
                                        $statusClass = 'bg-secondary';
                                        if (in_array($status, ['settlement', 'success', 'paid', 'capture', 'completed'])) {
                                            $statusClass = 'bg-success';
                                        } elseif (in_array($status, ['pending', 'challenge'])) {
                                            $statusClass = 'bg-warning text-dark';
                                        } elseif (in_array($status, ['deny', 'cancel', 'expire', 'failed'])) {
                                            $statusClass = 'bg-danger';
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration + ($txns->currentPage()-1)*$txns->perPage() }}</td>
                                        <td class="txn-order-id">{{ $txn->order_id }}</td>
                                        <td>{{ optional($txn->user)->name ?? 'Guest' }}</td>
                                        <td>{{ optional($txn->package)->name ?? '-' }}</td>
                                        <td>{{ $txn->method }}</td>
                                        <td class="txn-amount">Rp {{ number_format($txn->amount,0,',','.') }}</td>
                                        <td><span class="badge {{ $statusClass }}">{{ strtoupper($txn->status) }}</span></td>
                                        <td class="txn-created">
                                            <span class="txn-created-pill">{{ $txn->created_at->format('Y-m-d H:i') }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">No transactions found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                {{ $txns->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    function formatDate(d){
        const mm = String(d.getMonth()+1).padStart(2,'0');
        const dd = String(d.getDate()).padStart(2,'0');
        return d.getFullYear() + '-' + mm + '-' + dd;
    }

    document.querySelectorAll('#txn-filter-form [data-preset]').forEach(btn=>{
        btn.addEventListener('click', function(){
            const preset = this.getAttribute('data-preset');
            const today = new Date();
            let from=null, to=null;
            if (preset === 'today'){
                from = new Date(today.getFullYear(), today.getMonth(), today.getDate());
                to = new Date(from);
            } else if (preset === 'week'){
                const day = today.getDay();
                const diff = (day === 0) ? -6 : (1 - day);
                from = new Date(today);
                from.setDate(today.getDate() + diff);
                to = new Date(from);
                to.setDate(from.getDate() + 6);
            } else if (preset === 'month'){
                from = new Date(today.getFullYear(), today.getMonth(), 1);
                to = new Date(today.getFullYear(), today.getMonth()+1, 0);
            }

            if (from && to){
                document.getElementById('filter-from').value = formatDate(from);
                document.getElementById('filter-to').value = formatDate(to);
                document.getElementById('txn-filter-form').submit();
            }
        });
    });

    const presetClear = document.getElementById('preset-clear');
    if (presetClear){
        presetClear.addEventListener('click', function(){
            document.getElementById('filter-from').value = '';
            document.getElementById('filter-to').value = '';
            document.getElementById('txn-filter-form').submit();
        });
    }
});
</script>
@endsection
