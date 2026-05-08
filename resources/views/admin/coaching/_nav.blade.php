<div style="margin-bottom:18px;display:flex;gap:12px;align-items:center;">
    <a href="{{ url('/admin/coaching/bookings') }}" class="btn btn-sm {{ request()->is('admin/coaching/bookings*') ? 'btn-primary' : 'btn-outline-secondary' }}">Bookings</a>
    <a href="{{ url('/admin/coaching/slot-capacities') }}" class="btn btn-sm {{ request()->is('admin/coaching/slot-capacities*') ? 'btn-primary' : 'btn-outline-secondary' }}">Slot Capacities</a>
    <a href="{{ url('/admin/coaching/warranty-tickets') }}" class="btn btn-sm {{ request()->is('admin/coaching/warranty-tickets*') ? 'btn-primary' : 'btn-outline-secondary' }}">Warranty Tickets</a>
</div>
