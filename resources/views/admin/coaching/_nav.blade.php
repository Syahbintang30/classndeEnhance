<div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom" style="border-bottom: 1px solid #E2E8F0 !important;">
    <a href="{{ url('/admin/coaching/bookings') }}" class="btn btn-sm font-semibold rounded-lg px-3 py-1.5 d-inline-flex align-items-center" style="font-size: 0.78rem; gap: 8px; {{ request()->is('admin/coaching/bookings*') ? 'background: #EFF6FF; color: #1D4ED8; border: 1.5px solid #2563EB;' : 'border: 1px solid #CBD5E1; color: #475569; background: #FFFFFF;' }}">
        <i class="fa-solid fa-calendar-check" style="font-size: 0.75rem;"></i>
        <span>Bookings</span>
    </a>
    <a href="{{ url('/admin/coaching/slot-capacities') }}" class="btn btn-sm font-semibold rounded-lg px-3 py-1.5 d-inline-flex align-items-center" style="font-size: 0.78rem; gap: 8px; {{ request()->is('admin/coaching/slot-capacities*') ? 'background: #EFF6FF; color: #1D4ED8; border: 1.5px solid #2563EB;' : 'border: 1px solid #CBD5E1; color: #475569; background: #FFFFFF;' }}">
        <i class="fa-solid fa-clock" style="font-size: 0.75rem;"></i>
        <span>Slot Capacities</span>
    </a>
    <a href="{{ url('/admin/coaching/warranty-tickets') }}" class="btn btn-sm font-semibold rounded-lg px-3 py-1.5 d-inline-flex align-items-center" style="font-size: 0.78rem; gap: 8px; {{ request()->is('admin/coaching/warranty-tickets*') ? 'background: #EFF6FF; color: #1D4ED8; border: 1.5px solid #2563EB;' : 'border: 1px solid #CBD5E1; color: #475569; background: #FFFFFF;' }}">
        <i class="fa-solid fa-ticket" style="font-size: 0.75rem;"></i>
        <span>Warranty Tickets</span>
    </a>
</div>
