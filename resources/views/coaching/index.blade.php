@extends('layouts.app')

@push('head')
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            important: true,
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        display: ['"Bebas Neue"', 'sans-serif']
                    }
                }
            }
        };
    </script>
@endpush

@push('styles')
    <style>
        body { background-color: #08080a !important; color: #ffffff !important; font-family: 'Plus Jakarta Sans', sans-serif !important; }
        .font-display { font-family: 'Bebas Neue', cursive !important; letter-spacing: 1px; }
        body > nav, .global-nav { display: none !important; }
        .tw-dash a { text-decoration: none; }
        .tw-dash *:focus { outline: none !important; }
        
        .glass-card {
            background: rgba(12, 12, 18, 0.7);
            backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
    </style>
@endpush

@section('content')
<div class="tw-dash min-h-screen flex flex-col antialiased bg-[#08080a] text-gray-200 relative overflow-hidden selection:bg-blue-600 selection:text-white" x-data="{ mobileMenuOpen: false }">
    
    <!-- Ambient Background Glows -->
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute top-1/4 left-1/4 w-[700px] h-[500px] bg-blue-600/10 rounded-full blur-[160px] mix-blend-screen"></div>
        <div class="absolute bottom-1/4 right-1/4 w-[600px] h-[600px] bg-indigo-600/10 rounded-full blur-[160px] mix-blend-screen"></div>
        <div class="absolute inset-0 opacity-[0.02]" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 36px 36px;"></div>
    </div>

    <!-- LMS Header -->
    <div class="relative z-30">
        @include('layouts.lms_header')
    </div>

    @php
        $hasSelectedWarranty = isset($selectedWarrantyTicket) && $selectedWarrantyTicket;
        $hasAvailableWarranty = isset($hasWarrantyTicket) && $hasWarrantyTicket;
        $hasAnyTicket = $hasAvailableTicket || $hasSelectedWarranty || $hasAvailableWarranty;

        $regularCount = isset($tickets) ? $tickets->where('is_used', false)->count() : 0;
        $warrantyCount = isset($warrantyTickets) ? $warrantyTickets->where('status', 'available')->count() : 0;
        $totalAvailableTickets = $regularCount + $warrantyCount;
    @endphp

    @if(auth()->check() && !$hasAnyTicket && ($tickets->where('is_used', true)->count() > 0) && ($bookings->count() == 0))
        <!-- Empty State -->
        <div class="flex-1 flex items-center justify-center min-h-[65vh] p-6 relative z-10">
            <div class="glass-card max-w-lg w-full p-10 rounded-[2.5rem] text-center space-y-6 border border-white/10 shadow-2xl">
                <div class="w-20 h-20 rounded-3xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center text-3xl mx-auto shadow-inner">
                    <i class="fa-solid fa-ticket-simple"></i>
                </div>
                <div class="space-y-2">
                    <h1 class="font-display text-4xl text-white tracking-wide uppercase">No Session Tickets Available</h1>
                    <p class="text-gray-400 text-xs sm:text-sm leading-relaxed">You currently don't have any active coaching tickets to book a session.</p>
                </div>
                <a href="{{ route('coaching.checkout') }}" class="inline-flex items-center gap-2 py-3.5 px-8 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-blue-600/30 transition-all hover:scale-105">
                    <i class="fa-solid fa-cart-shopping text-xs"></i>
                    <span>Buy Coaching Ticket</span>
                </a>
            </div>
        </div>
    @else
        <!-- Header & Interactive Selection Section -->
        <main class="flex-1 w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12 relative z-10 space-y-8">
            
            <!-- Page Header Section -->
            <div class="text-center space-y-4 max-w-3xl mx-auto">
                <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-bold uppercase tracking-widest shadow-inner">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-500"></span>
                    </span>
                    <span>1-ON-1 PERSONAL MENTORSHIP</span>
                </div>

                <h1 class="font-display text-4xl sm:text-6xl text-white tracking-wide uppercase leading-none">
                    Book Your <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-indigo-300 to-blue-500">Coaching Session</span>
                </h1>

                <p class="text-gray-400 text-xs sm:text-sm leading-relaxed max-w-xl mx-auto">
                    Select a date &amp; time for your live video session with Nde. Your available tickets will be applied automatically.
                </p>

                <!-- Ticket Badge Info -->
                <div class="inline-flex items-center gap-3.5 px-5 py-2.5 rounded-2xl bg-zinc-950/80 border border-white/10 text-xs font-semibold shadow-xl flex-wrap justify-center">
                    <!-- Regular Ticket Badge -->
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-ticket text-blue-400 text-sm"></i>
                        <span class="text-gray-300">Regular Ticket:</span>
                        <span id="availableCount" class="px-2.5 py-0.5 rounded-lg bg-blue-500/20 border border-blue-500/30 text-blue-300 font-extrabold text-sm">{{ $regularCount }}</span>
                    </div>

                    <!-- Warranty Ticket Badge -->
                    @if($warrantyCount > 0)
                        <div class="flex items-center gap-2 border-l border-white/10 pl-3.5 text-amber-400 font-bold">
                            <i class="fa-solid fa-shield-halved text-amber-400 text-sm"></i>
                            <span class="text-amber-300">Warranty Ticket:</span>
                            <span class="px-2.5 py-0.5 rounded-lg bg-amber-500/20 border border-amber-500/30 text-amber-300 font-extrabold text-sm">{{ $warrantyCount }} ({{ $selectedWarrantyTicket->warranty_minutes ?? $warrantyTickets->where('status', 'available')->first()->warranty_minutes ?? 60 }} min)</span>
                        </div>
                    @endif
                </div>

            </div>


            <!-- Reschedule Active Banner -->
            @if(isset($rescheduleBooking) && $rescheduleBooking)
                <div class="p-5 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-300 flex items-center justify-between flex-wrap gap-4 shadow-lg shadow-amber-500/10 mb-6">
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-2xl bg-amber-500/20 border border-amber-500/40 text-amber-400 flex items-center justify-center font-bold shrink-0">
                            <i class="fa-solid fa-calendar-days text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-sm text-white">Rescheduling Coaching Session #{{ $rescheduleBooking->id }}</h3>
                            <p class="text-xs text-amber-300/90 leading-relaxed">
                                Current Appointment: <strong class="text-white">{{ \Carbon\Carbon::parse($rescheduleBooking->booking_time)->format('F j, Y, H:i') }} WIB</strong>. Pick a new open date and time slot below.
                            </p>
                            <p class="text-[11px] text-amber-400/80 font-medium mt-1">
                                📌 <strong>Reschedule Policy:</strong> Each session can only be rescheduled <strong>maximum 1 time</strong>, at least <strong>1 day in advance (H-1)</strong>.
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('coaching.index') }}" class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition">Cancel Reschedule</a>
                </div>
            @endif


            <!-- 2-Column Split Booking Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">
                
                <!-- Left Column: Step 1 Calendar Picker (col-span-7) -->
                <div class="lg:col-span-7">
                    <div class="glass-card rounded-[2rem] p-6 sm:p-8 border border-white/10 shadow-2xl relative overflow-hidden space-y-6">
                        
                        <!-- Step 1 Header -->
                        <div class="flex items-center justify-between pb-5 border-b border-white/10">
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white flex items-center justify-center font-extrabold text-sm shadow-lg shadow-blue-600/30">
                                    01
                                </div>
                                <div>
                                    <h2 class="font-bold text-white text-base tracking-wide uppercase">Select a Date</h2>
                                    <p class="text-xs text-gray-400">Choose an available slot on the calendar</p>
                                </div>
                            </div>
                            <div class="hidden sm:flex items-center gap-1.5 text-[11px] text-gray-400 font-medium bg-white/5 px-3 py-1.5 rounded-xl border border-white/5">
                                <span class="w-2 h-2 rounded-full bg-blue-500"></span> Available Day
                            </div>
                        </div>

                        <!-- Calendar Control Header -->
                        <div class="calendar w-full">
                            <div class="flex items-center justify-between mb-6 px-2">
                                <button id="prevMonth" type="button" class="w-10 h-10 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-gray-300 hover:text-white flex items-center justify-center transition disabled:opacity-20 disabled:cursor-not-allowed cursor-pointer">
                                    <i class="fa-solid fa-chevron-left text-xs"></i>
                                </button>

                                <h3 id="monthName" class="font-display text-2xl text-white tracking-wide uppercase"></h3>

                                <button id="nextMonth" type="button" class="w-10 h-10 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-gray-300 hover:text-white flex items-center justify-center transition cursor-pointer">
                                    <i class="fa-solid fa-chevron-right text-xs"></i>
                                </button>
                            </div>

                            <!-- Days Grid -->
                            <div class="grid grid-cols-7 gap-2 text-center" id="calendarDays"></div>
                        </div>

                    </div>
                </div>

                <!-- Right Column: Step 2 & Step 3 Time & Confirmation (col-span-5) -->
                <div class="lg:col-span-5 space-y-6 lg:sticky lg:top-24">
                    
                    <!-- Step 2: Time Selection Card -->
                    <div id="step2-time" class="glass-card rounded-[2rem] p-6 sm:p-7 border border-white/10 shadow-2xl relative overflow-hidden space-y-5 transition-all">
                        <div class="flex items-center gap-3.5 pb-4 border-b border-white/10">
                            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white flex items-center justify-center font-extrabold text-sm shadow-lg shadow-blue-600/30">
                                02
                            </div>
                            <div>
                                <h2 class="font-bold text-white text-base tracking-wide uppercase">Select a Time</h2>
                                <p class="text-xs text-gray-400">Pick a time slot for your session</p>
                            </div>
                        </div>

                        <div class="times w-full">
                            <div id="timeSuggestions" class="grid grid-cols-3 sm:grid-cols-4 gap-2.5">
                                <p class="col-span-full text-gray-500 text-xs text-center py-6">Please select a date on the calendar first.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Details & Confirmation Card -->
                    <div id="step3-details" class="glass-card rounded-[2rem] p-6 sm:p-7 border border-white/10 shadow-2xl relative overflow-hidden space-y-5 hidden">
                        <div class="flex items-center gap-3.5 pb-4 border-b border-white/10">
                            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white flex items-center justify-center font-extrabold text-sm shadow-lg shadow-blue-600/30">
                                03
                            </div>
                            <div>
                                <h2 class="font-bold text-white text-base tracking-wide uppercase">{{ isset($rescheduleBooking) && $rescheduleBooking ? 'Confirm Reschedule' : 'Confirm & Note' }}</h2>
                                <p class="text-xs text-gray-400">Finalize your booking details</p>
                            </div>
                        </div>

                        @php
                            $formAction = (isset($rescheduleBooking) && $rescheduleBooking)
                                ? route('coaching.bookings.reschedule', $rescheduleBooking->id)
                                : route('coaching.book');
                        @endphp

                        <form id="bookingOrCheckoutForm" method="POST" action="{{ $formAction }}" class="space-y-5">
                            @csrf
                            <input type="hidden" id="booking_time" name="booking_time" value="" />
                            <input type="hidden" id="new_booking_time" name="new_booking_time" value="" />
                            @if($hasSelectedWarranty)
                                <input type="hidden" name="warranty_ticket_id" value="{{ $selectedWarrantyTicket->id }}" />
                                <input type="hidden" name="use_warranty" value="1" />
                            @endif

                            <!-- Selected Session Summary Box -->
                            <div class="p-4 rounded-2xl bg-blue-500/10 border border-blue-500/20 text-xs space-y-2">
                                <div class="font-bold text-blue-300 uppercase tracking-wider text-[11px] flex items-center gap-1.5">
                                    <i class="fa-regular fa-calendar-check"></i>
                                    <span>Selected New Session Slot</span>
                                </div>
                                <p id="selectionSummaryText" class="text-white font-bold text-sm">—</p>
                                <p id="ticketInfoText" class="text-xs text-blue-400 font-medium"></p>
                                @if(!isset($rescheduleBooking) || !$rescheduleBooking)
                                    <div id="priceLine" class="text-xs text-gray-400 pt-1" style="display: {{ $hasAnyTicket ? 'none' : 'block' }};">
                                        Price: <strong class="text-white">Rp {{ $coachingPkg ? number_format($coachingPkg->price,0,',','.') : '0' }}</strong>
                                    </div>
                                @endif
                            </div>

                            @if(isset($rescheduleBooking) && $rescheduleBooking)
                                <!-- Mandatory Reschedule Reason Field -->
                                <div class="space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <label for="reschedule_reason" class="block font-bold text-xs text-amber-300 uppercase tracking-wider">
                                            Reason for Rescheduling <span class="text-red-400 font-bold">*</span>
                                        </label>
                                        <span class="text-[10px] text-red-400 font-semibold uppercase tracking-wider">(Required)</span>
                                    </div>
                                    <textarea id="reschedule_reason" name="reschedule_reason" required placeholder="Please state why you need to reschedule (e.g., Work conflict, emergency, personal reason)..." class="w-full bg-zinc-900/80 border border-amber-500/30 text-white p-3.5 rounded-xl text-xs outline-none transition focus:border-amber-400 focus:ring-1 focus:ring-amber-400/30 min-h-[90px] resize-y placeholder-gray-500"></textarea>
                                    <p id="reasonErrorMsg" class="text-xs text-red-400 font-medium hidden mt-1 flex items-center gap-1">
                                        <i class="fa-solid fa-circle-exclamation text-xs"></i>
                                        <span>Please state your reason for rescheduling before confirming.</span>
                                    </p>
                                </div>
                            @else
                                <!-- Session Notes -->
                                <div class="space-y-1.5">
                                    <label for="session_notes" class="block font-bold text-xs text-gray-300">What would you like to focus on? (Optional)</label>
                                    <textarea id="session_notes" name="notes" placeholder="e.g., Flamenco fingerpicking, chord transitions, theory..." class="w-full bg-zinc-900/80 border border-white/10 text-white p-3.5 rounded-xl text-xs outline-none transition focus:border-blue-500/60 focus:ring-1 focus:ring-blue-500/20 min-h-[90px] resize-y placeholder-gray-500"></textarea>
                                </div>
                            @endif


                            @php
                                $canSubmitForFree = $hasAnyTicket || (isset($rescheduleBooking) && $rescheduleBooking);
                            @endphp

                            <!-- Submit Button -->
                            <button id="submitBtn" class="w-full py-4 px-6 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-xs tracking-wider uppercase shadow-lg shadow-blue-600/30 transition-all hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-2 cursor-pointer disabled:opacity-40 disabled:scale-100 disabled:cursor-not-allowed" type="submit" disabled data-has-ticket="{{ $canSubmitForFree ? '1' : '0' }}" data-using-warranty="{{ $hasSelectedWarranty ? '1' : '0' }}">
                                <i class="fa-solid fa-circle-check text-xs"></i>
                                <span>{{ isset($rescheduleBooking) && $rescheduleBooking ? 'Confirm Reschedule' : 'Select a Date & Time' }}</span>
                            </button>
                        </form>
                    </div>



                </div>

            </div>

        </main>
    @endif
</div>
@endsection

@push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const isRescheduleMode = {{ (isset($rescheduleBooking) && $rescheduleBooking) ? 'true' : 'false' }};
        const step2El = document.getElementById('step2-time');
        const step3El = document.getElementById('step3-details');
        const monthNameEl = document.getElementById('monthName');
        const daysEl = document.getElementById('calendarDays');
        const prevBtn = document.getElementById('prevMonth');
        const nextBtn = document.getElementById('nextMonth');
        const timeSuggestions = document.getElementById('timeSuggestions');
        const selectionSummaryTextEl = document.getElementById('selectionSummaryText');
        const ticketInfoTextEl = document.getElementById('ticketInfoText');
        const submitBtn = document.getElementById('submitBtn');
        const form = document.getElementById('bookingOrCheckoutForm');

        const allowedSlotTimes = {!! json_encode($slotTimes ?? []) !!};
        const coachingAvailabilityRangeUrl = '/coaching/availability-range';
        const coachingAvailabilityUrl = '/coaching/availability';
        const coachingBookUrl = '/coaching/book';
        const coachingCheckoutUrl = '/coaching/checkout';
        const coachingThankYouUrl = '/coaching/thankyou';

        let current = new Date();
        let selectedDate = null;
        let selectedTime = null;
        let today = new Date();
        let todayStart = new Date(today.getFullYear(), today.getMonth(), today.getDate());
        let minMonth = new Date(today.getFullYear(), today.getMonth(), 1);

        function formatDateLocal(d) {
            const Y = d.getFullYear();
            const M = ('0' + (d.getMonth() + 1)).slice(-2);
            const D = ('0' + d.getDate()).slice(-2);
            return `${Y}-${M}-${D}`;
        }

        async function fetchMonthAvailability(year, month) {
            const first = new Date(year, month, 1);
            const last = new Date(year, month + 1, 0);
            try {
                const url = coachingAvailabilityRangeUrl + '?start=' + formatDateLocal(first) + '&end=' + formatDateLocal(last);
                const resp = await fetch(url, { credentials: 'same-origin' });
                if (!resp.ok) return {};
                const json = await resp.json();
                return json.days || {};
            } catch (e) { return {}; }
        }

        async function renderMonth(dt) {
            const year = dt.getFullYear();
            const month = dt.getMonth();
            const first = new Date(year, month, 1);
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            if (monthNameEl) monthNameEl.textContent = first.toLocaleString('default', { month: 'long', year: 'numeric' });
            if (daysEl) daysEl.innerHTML = '';
            if (prevBtn) prevBtn.disabled = first.getFullYear() === minMonth.getFullYear() && first.getMonth() === minMonth.getMonth();

            ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'].forEach(w => {
                const h = document.createElement('div');
                h.className = 'text-center text-[11px] font-bold text-gray-500 uppercase tracking-widest py-1';
                h.textContent = w;
                if (daysEl) daysEl.appendChild(h);
            });

            for (let i = 0; i < first.getDay(); i++) {
                const ph = document.createElement('div');
                if (daysEl) daysEl.appendChild(ph);
            }

            const availMap = await fetchMonthAvailability(year, month);

            for (let d = 1; d <= daysInMonth; d++) {
                const date = new Date(year, month, d);
                const wrapper = document.createElement('div');
                wrapper.className = 'flex justify-center py-1';
                
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.dataset.date = formatDateLocal(date);
                btn.className = 'day w-10 h-10 sm:w-11 sm:h-11 flex flex-col items-center justify-center rounded-2xl text-xs sm:text-sm font-semibold transition-all relative cursor-pointer ';
                
                let isAvailable = true;
                const minDateBoundary = isRescheduleMode
                    ? new Date(today.getFullYear(), today.getMonth(), today.getDate() + 1)
                    : todayStart;

                if (new Date(date.getFullYear(), date.getMonth(), date.getDate()) < minDateBoundary) {
                    isAvailable = false;
                }

                if (!isAvailable) {
                    btn.className += 'text-gray-600 bg-white/[0.02] border border-white/5 cursor-not-allowed opacity-40 disabled';
                    btn.disabled = true;
                    btn.textContent = d;
                } else {
                    const remaining = (availMap[btn.dataset.date] || 0);
                    btn.className += 'text-gray-200 bg-white/5 border border-white/10 hover:bg-blue-600/30 hover:border-blue-500/50 hover:text-white hover:scale-105';
                    btn.innerHTML = d + (remaining > 0 ? '<span class="w-1.5 h-1.5 bg-blue-400 rounded-full absolute bottom-1 shadow-sm"></span>' : '');
                    
                    btn.addEventListener('click', async () => {
                        document.querySelectorAll('#calendarDays .day.active').forEach(x => {
                            x.className = 'day w-10 h-10 sm:w-11 sm:h-11 flex flex-col items-center justify-center rounded-2xl text-xs sm:text-sm font-semibold transition-all relative cursor-pointer text-gray-200 bg-white/5 border border-white/10 hover:bg-blue-600/30 hover:border-blue-500/50 hover:text-white hover:scale-105';
                        });
                        btn.className = 'day active w-10 h-10 sm:w-11 sm:h-11 flex flex-col items-center justify-center rounded-2xl text-xs sm:text-sm font-bold transition-all relative cursor-pointer bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-600/40 border border-blue-400 scale-105 z-10';
                        
                        selectedDate = btn.dataset.date;
                        selectedTime = null;
                        if (step3El) step3El.classList.add('hidden');
                        
                        await loadTimesForDate(selectedDate);
                        updateSummaryAndButtonState();
                    });
                }
                
                wrapper.appendChild(btn);
                if (daysEl) daysEl.appendChild(wrapper);
            }
        }

        async function loadTimesForDate(dateStr) {
            if (!timeSuggestions) return;
            timeSuggestions.innerHTML = '<p class="col-span-full text-gray-400 text-xs text-center py-4"><i class="fa-solid fa-spinner fa-spin mr-1.5 text-blue-400"></i> Loading available times...</p>';
            try {
                const resp = await fetch(coachingAvailabilityUrl + '?date=' + dateStr, { credentials: 'same-origin' });
                if (!resp.ok) throw new Error();
                const json = await resp.json();
                timeSuggestions.innerHTML = '';

                const defaultTimes = ['09:00', '10:00', '11:00', '14:00', '15:00', '16:00', '19:00', '20:00'];
                const hasConfiguredSlots = json.slots && Object.keys(json.slots).length > 0;
                
                let times = hasConfiguredSlots
                    ? Object.keys(json.slots).sort()
                    : (Array.isArray(allowedSlotTimes) && allowedSlotTimes.length > 0 ? allowedSlotTimes.slice() : defaultTimes);

                if (times.length === 0) {
                    timeSuggestions.innerHTML = '<p class="col-span-full text-gray-400 text-xs text-center py-4">No available times for this date.</p>';
                    return;
                }

                times.forEach(t => {
                    const s = hasConfiguredSlots
                        ? ((json.slots && typeof json.slots[t] !== 'undefined') ? json.slots[t] : { remaining: 0 })
                        : { remaining: 1 };

                    const b = document.createElement('button');
                    b.type = 'button';
                    b.textContent = t.replace(':', '.');
                    b.dataset.time = t;
                    
                    const baseClass = 'time flex items-center justify-center px-3 py-2.5 rounded-xl text-xs font-bold transition-all border ';

                    if (typeof s.remaining !== 'undefined' && s.remaining <= 0) {
                        b.className = baseClass + 'bg-white/[0.02] text-gray-600 border-white/5 cursor-not-allowed opacity-40 disabled';
                        b.disabled = true;
                    } else {
                        b.className = baseClass + 'bg-white/5 text-gray-300 border-white/10 hover:bg-white/10 hover:border-white/20 hover:text-white cursor-pointer';
                        b.addEventListener('click', () => {
                            selectedTime = b.dataset.time;
                            document.querySelectorAll('#timeSuggestions .time.selected').forEach(x => {
                                x.className = baseClass + 'bg-white/5 text-gray-300 border-white/10 hover:bg-white/10 hover:border-white/20 hover:text-white cursor-pointer time';
                            });
                            b.className = baseClass + 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white border-blue-400 shadow-md shadow-blue-600/30 selected time';
                            
                            if (step3El) step3El.classList.remove('hidden');
                            updateSummaryAndButtonState();
                        });
                    }
                    timeSuggestions.appendChild(b);
                });

            } catch (e) {
                timeSuggestions.innerHTML = '<p class="col-span-full text-red-400 text-xs text-center py-4">Could not load times. Please try again.</p>';
            }
        }

        function updateSummaryAndButtonState() {
            if (!submitBtn) return;
            const hasTicket = submitBtn.getAttribute('data-has-ticket') === '1';
            const usingWarranty = submitBtn.getAttribute('data-using-warranty') === '1';
            if (!selectedDate) {
                if (selectionSummaryTextEl) selectionSummaryTextEl.textContent = '—';
                submitBtn.disabled = true;
                submitBtn.textContent = 'Select a Date & Time';
                return;
            }
            const parts = selectedDate.split('-').map(x => parseInt(x, 10));
            const d = new Date(parts[0], parts[1] - 1, parts[2]);
            const monthNames = ['January','February','March','April','May','June','July','August','September','October','November','December'];
            const dayName = d.toLocaleString('en-US', { weekday: 'long' });
            let summary = `${dayName}, ${monthNames[d.getMonth()]} ${d.getDate()}, ${d.getFullYear()}`;

            if (selectedTime) {
                summary += ` — ${selectedTime}`;
                const dtStr = `${selectedDate} ${selectedTime}:00`;
                const bTimeInput = document.getElementById('booking_time');
                if (bTimeInput) bTimeInput.value = dtStr;
                const newTimeInput = document.getElementById('new_booking_time');
                if (newTimeInput) newTimeInput.value = dtStr;

                if (isRescheduleMode) {
                    const reasonInput = document.getElementById('reschedule_reason');
                    const reasonVal = reasonInput ? reasonInput.value.trim() : '';
                    const reasonErrorMsg = document.getElementById('reasonErrorMsg');

                    if (!reasonVal) {
                        submitBtn.disabled = true;
                        submitBtn.textContent = 'Please State Reason First';
                        if (ticketInfoTextEl) ticketInfoTextEl.textContent = '⚠️ Please fill in the reason for rescheduling above to proceed.';
                        if (reasonErrorMsg) reasonErrorMsg.classList.remove('hidden');
                    } else {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Confirm Reschedule Session';
                        if (ticketInfoTextEl) ticketInfoTextEl.textContent = '📅 Session will be moved to this new schedule.';
                        if (reasonErrorMsg) reasonErrorMsg.classList.add('hidden');
                    }
                } else {
                    submitBtn.disabled = false;
                    submitBtn.textContent = hasTicket ? (usingWarranty ? 'Confirm & Use Warranty Ticket' : 'Confirm & Use 1 Ticket') : 'Proceed to Payment';
                    if (ticketInfoTextEl) {
                        ticketInfoTextEl.textContent = hasTicket
                            ? (usingWarranty ? '🎟️ Session will use your warranty ticket.' : '🎟️ Session will use 1 of your available tickets.')
                            : '';
                    }
                }
            } else {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Select a Time';
                if (ticketInfoTextEl) ticketInfoTextEl.textContent = '';
            }
            if (selectionSummaryTextEl) selectionSummaryTextEl.innerHTML = `🗓️ <strong>${summary}</strong>`;
        }

        const reasonInput = document.getElementById('reschedule_reason');
        if (reasonInput) {
            reasonInput.addEventListener('input', function() {
                const reasonErrorMsg = document.getElementById('reasonErrorMsg');
                if (this.value.trim().length > 0) {
                    if (reasonErrorMsg) reasonErrorMsg.classList.add('hidden');
                }
                updateSummaryAndButtonState();
            });
        }


        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                const firstOfCurrent = new Date(current.getFullYear(), current.getMonth(), 1);
                if (firstOfCurrent.getFullYear() === minMonth.getFullYear() && firstOfCurrent.getMonth() === minMonth.getMonth()) return;
                current = new Date(current.getFullYear(), current.getMonth() - 1, 1);
                renderMonth(current);
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                current = new Date(current.getFullYear(), current.getMonth() + 1, 1);
                renderMonth(current);
            });
        }

        if (form) {
            form.addEventListener('submit', async function (e) {
                const bookingInput = document.getElementById('booking_time');
                if (!bookingInput || !bookingInput.value) {
                    e.preventDefault();
                    alert('Please select a date and time before booking.');
                    return;
                }

                if (isRescheduleMode) {
                    e.preventDefault();
                    const reasonInput = document.getElementById('reschedule_reason');
                    if (!reasonInput || !reasonInput.value.trim()) {
                        alert('Please state your reason for rescheduling before submitting.');
                        if (reasonInput) reasonInput.focus();
                        return;
                    }

                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Rescheduling...';

                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const formAction = form.getAttribute('action');

                    try {
                        const resp = await fetch(formAction, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                new_booking_time: bookingInput.value,
                                reschedule_reason: reasonInput.value.trim()
                            }),
                            credentials: 'same-origin'
                        });

                        const json = await resp.json();
                        if (json.ok) {
                            window.location.href = "{{ route('coaching.upcoming') }}";
                        } else {
                            alert(json.error || 'Failed to reschedule appointment.');
                            submitBtn.disabled = false;
                            submitBtn.textContent = 'Confirm Reschedule Session';
                        }
                    } catch (err) {
                        alert('Network error. Please try again.');
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Confirm Reschedule Session';
                    }
                    return;
                }

                const hasTicket = submitBtn.getAttribute('data-has-ticket') === '1';
                const usingWarranty = submitBtn.getAttribute('data-using-warranty') === '1';

                if (!hasTicket) {
                    e.preventDefault();
                    window.location.href = `${coachingCheckoutUrl}?schedule=${encodeURIComponent(bookingInput.value)}&notes=${encodeURIComponent(document.getElementById('session_notes') ? document.getElementById('session_notes').value : '')}`;
                    return;
                }
                e.preventDefault();
                submitBtn.disabled = true;
                submitBtn.textContent = 'Booking...';
                const payload = new URLSearchParams();
                payload.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                payload.append('booking_time', bookingInput.value);
                payload.append('notes', document.getElementById('session_notes') ? document.getElementById('session_notes').value || '' : '');
                const warrantyInput = form.querySelector('input[name="warranty_ticket_id"]');
                if (warrantyInput && warrantyInput.value) {
                    payload.append('warranty_ticket_id', warrantyInput.value);
                }
                try {
                    const resp = await fetch(coachingBookUrl, {
                        method: 'POST', body: payload.toString(),
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'Accept': 'application/json' },
                        credentials: 'same-origin'
                    });
                    if (resp.ok) {
                        const json = await resp.json().catch(() => null);
                        if (json && json.booking) { window.location.href = `${coachingThankYouUrl}?booking=${json.booking}`; return; }
                        window.location.reload();
                    } else {
                        const json = await resp.json().catch(() => null);
                        const msg = (json && json.errors) ? Object.values(json.errors).flat().join('\n') : 'Failed to create booking.';
                        alert(msg);
                    }
                } catch (err) {
                    alert('Failed to create booking. Please try again.');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.textContent = usingWarranty ? 'Confirm & Use Warranty Ticket' : 'Confirm & Use 1 Ticket';
                }
            });
        }

        renderMonth(current);
        updateSummaryAndButtonState();
    });
    </script>
@endpush