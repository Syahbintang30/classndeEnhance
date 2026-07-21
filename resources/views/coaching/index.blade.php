@extends('layouts.app')

@push('head')
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            important: true,
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: { 50: '#f0f9ff', 100: '#e0f2fe', 500: '#0ea5e9', 600: '#0284c7', 900: '#0c4a6e' },
                        dark: { 900: '#09090b', 800: '#18181b', 700: '#27272a' }
                    },
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
    <link rel="stylesheet" href="{{ asset('css/coaching.css') }}">

    <style>
        body > nav { display: none !important; }
        .tw-dash a { text-decoration: none; }
        .tw-dash *:focus { outline: none !important; }
    </style>
@endpush


@section('content')
<div class="tw-dash min-h-screen flex flex-col antialiased bg-black text-gray-200" x-data="{ mobileMenuOpen: false }">
    @include('layouts.lms_header')

    @php
        $hasSelectedWarranty = isset($selectedWarrantyTicket) && $selectedWarrantyTicket;
        $hasAnyTicket = $hasAvailableTicket || $hasSelectedWarranty;
    @endphp
    @if(auth()->check() && !$hasAnyTicket && ($tickets->where('is_used', true)->count() > 0) && ($bookings->count() == 0))
        <div class="coaching-page" style="display:flex;align-items:center;justify-content:center;min-height:60vh;padding:40px 16px;">
            <div style="text-align:center;max-width:760px;width:100%;">
                <h1 style="font-weight:600;font-size:28px;margin-bottom:12px">You don't have any sessions yet</h1>
                <p style="opacity:0.85;margin-bottom:22px;line-height:1.5">Choose your preferred date and time to book a session.</p>
                <a href="{{ route('coaching.checkout') }}" class="btn primary" style="display:inline-flex;align-items:center;gap:8px;padding:12px 22px;border-radius:24px;width:auto;">BUY A TICKET</a>
            </div>
        </div>
    @else
    <div class="coaching-page">
        
        <main class="max-w-3xl mx-auto px-4 py-12 md:py-20 flex flex-col items-center w-full">
            
            <!-- Hero Section & Context -->
            <div class="text-center mb-10 md:mb-16 space-y-5">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white tracking-tight" style="font-family: 'Bebas Neue', sans-serif;">
                    BOOK YOUR COACHING SESSION
                </h1>
                <p class="text-gray-400 text-lg max-w-xl mx-auto leading-relaxed">
                    Follow the steps below to schedule your one-on-one session. Your available tickets will be applied automatically.
                </p>
                
                <!-- Highlighted Ticket Info -->
                <div class="inline-flex flex-col items-center gap-1 mt-8">
                    <div class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-500/10 border border-blue-500/20 rounded-full text-blue-400 font-medium shadow-[0_0_15px_rgba(37,99,235,0.1)]">
                        <span class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-500"></span>
                        </span>
                        Available Tickets: <span id="availableCount" class="text-white font-bold ml-1 text-lg">{{ $tickets->where('is_used', false)->count() }}</span>
                    </div>
                    @if($hasSelectedWarranty)
                        <div class="text-sm text-yellow-500 font-medium mt-2">Using Warranty Ticket: {{ $selectedWarrantyTicket->warranty_minutes ?? '-' }} min</div>
                    @endif
                </div>
            </div>

            <!-- Main Booking Box -->
            <div class="w-full bg-[#111111] border border-gray-800/80 rounded-2xl shadow-2xl p-6 md:p-8 lg:p-10 mb-20">
                
                <!-- Step 1 -->
                <div id="step1-date" class="booking-step block">
                    <div class="flex items-center gap-4 mb-8 pb-6 border-b border-gray-800/50">
                        <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-lg shadow-[0_0_20px_rgba(37,99,235,0.4)] flex-shrink-0">1</div>
                        <div>
                            <h2 class="text-xl font-semibold text-white tracking-wide">SELECT A DATE</h2>
                            <p class="text-sm text-gray-500 mt-0.5">Choose an available slot for your session</p>
                        </div>
                    </div>

                    <div class="calendar w-full">
                        <div class="flex justify-between items-center mb-8">
                            <button id="prevMonth" class="p-2 rounded-full bg-gray-900/50 hover:bg-gray-800 border border-gray-800 text-gray-400 hover:text-white transition-colors disabled:opacity-30 disabled:cursor-not-allowed">
                                <i class="fa-solid fa-chevron-left"></i>
                            </button>
                            <h3 id="monthName" class="text-xl md:text-2xl font-bold text-white tracking-wide"></h3>
                            <button id="nextMonth" class="p-2 rounded-full bg-gray-900/50 hover:bg-gray-800 border border-gray-800 text-gray-400 hover:text-white transition-colors">
                                <i class="fa-solid fa-chevron-right"></i>
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-7 gap-2 md:gap-3 lg:gap-4 mb-4" id="calendarDays"></div>
                    </div>
                </div>

                <!-- Step 2 -->
                <div id="step2-time" class="booking-step hidden mt-8 pt-6 border-t border-gray-800/50">
                    <div class="flex items-center gap-4 mb-8 pb-6 border-b border-gray-800/50">
                        <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-lg shadow-[0_0_20px_rgba(37,99,235,0.4)] flex-shrink-0">2</div>
                        <div>
                            <h2 class="text-xl font-semibold text-white tracking-wide">SELECT A TIME</h2>
                            <p class="text-sm text-gray-500 mt-0.5">Pick a time that works best for you</p>
                        </div>
                    </div>
                    
                    <div class="times w-full">
                        <div id="timeSuggestions" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3"></div>
                    </div>
                </div>

                <!-- Step 3 -->
                <div id="step3-details" class="booking-step hidden mt-8 pt-6 border-t border-gray-800/50">
                    <div class="flex items-center gap-4 mb-8 pb-6 border-b border-gray-800/50">
                        <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-lg shadow-[0_0_20px_rgba(37,99,235,0.4)] flex-shrink-0">3</div>
                        <div>
                            <h2 class="text-xl font-semibold text-white tracking-wide">CONFIRM & ADD A NOTE</h2>
                            <p class="text-sm text-gray-500 mt-0.5">Finalize your booking</p>
                        </div>
                    </div>

                    <form id="bookingOrCheckoutForm" method="POST" action="{{ route('coaching.book') }}" class="space-y-6">
                        @csrf
                        <input type="hidden" id="booking_time" name="booking_time" value="" />
                        @if($hasSelectedWarranty)
                            <input type="hidden" name="warranty_ticket_id" value="{{ $selectedWarrantyTicket->id }}" />
                            <input type="hidden" name="use_warranty" value="1" />
                        @endif
                        
                        <div>
                            <label for="session_notes" class="block font-semibold text-sm text-gray-300 mb-2">What would you like to focus on? (Optional)</label>
                            <textarea id="session_notes" name="notes" placeholder="e.g., Flamenco techniques, strumming patterns, music theory..." class="w-full bg-gray-900/50 border border-gray-800 text-white px-4 py-3 rounded-xl outline-none transition-all focus:border-blue-500 focus:bg-gray-900 focus:ring-1 focus:ring-blue-500/50 min-h-[120px] resize-y placeholder-gray-600"></textarea>
                            <p class="text-gray-500 text-xs mt-2">Your notes will be sent to the coach when you book.</p>
                        </div>

                        <div class="bg-gray-900/40 p-5 rounded-xl border border-gray-800/60">
                            <h4 class="text-white font-medium mb-3">Your Selection</h4>
                            <div class="flex items-start gap-3">
                                <i class="fa-regular fa-calendar-check text-blue-500 mt-1"></i>
                                <div>
                                    <p id="selectionSummaryText" class="text-gray-300">—</p>
                                    <p id="ticketInfoText" class="text-sm text-blue-400 mt-1"></p>
                                    <div id="priceLine" class="text-sm text-gray-400 mt-2" style="display: {{ $hasAnyTicket ? 'none' : 'block' }};">
                                        Price: <strong class="text-white">Rp {{ $coachingPkg ? number_format($coachingPkg->price,0,',','.') : '0' }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button id="submitBtn" class="w-full py-4 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl flex items-center justify-center gap-2 transition-all hover:shadow-[0_0_20px_rgba(37,99,235,0.4)] disabled:bg-gray-800 disabled:text-gray-500 disabled:shadow-none disabled:cursor-not-allowed" type="submit" disabled data-has-ticket="{{ $hasAnyTicket ? '1' : '0' }}" data-using-warranty="{{ $hasSelectedWarranty ? '1' : '0' }}">
                            Select a Date & Time
                        </button>
                    </form>
                </div>
            </div>
        </main>
    </div>
    @endif
</div>

@endsection


@push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
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

            monthNameEl.textContent = first.toLocaleString('default', { month: 'long', year: 'numeric' });
            daysEl.innerHTML = '';
            prevBtn.disabled = first.getFullYear() === minMonth.getFullYear() && first.getMonth() === minMonth.getMonth();

            ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'].forEach(w => {
                const h = document.createElement('div');
                h.className = 'text-center text-xs font-semibold text-gray-500 uppercase tracking-widest mb-2';
                h.textContent = w;
                daysEl.appendChild(h);
            });

            for (let i = 0; i < first.getDay(); i++) {
                const ph = document.createElement('div');
                daysEl.appendChild(ph);
            }

            const availMap = await fetchMonthAvailability(year, month);

            for (let d = 1; d <= daysInMonth; d++) {
                const date = new Date(year, month, d);
                const wrapper = document.createElement('div');
                wrapper.className = 'flex justify-center py-1';
                
                const btn = document.createElement('button');
                btn.dataset.date = formatDateLocal(date);
                btn.className = 'day w-10 h-10 md:w-12 md:h-12 flex flex-col items-center justify-center rounded-full text-sm md:text-base transition-all duration-200 relative ';
                
                let isAvailable = true;
                if (new Date(date.getFullYear(), date.getMonth(), date.getDate()) < todayStart) {
                    isAvailable = false;
                } else {
                    const remaining = (availMap[btn.dataset.date] || 0);
                    if (remaining <= 0) {
                        isAvailable = false;
                    }
                }

                if (!isAvailable) {
                    btn.className += 'text-[#333333] cursor-not-allowed disabled';
                    btn.disabled = true;
                    btn.textContent = d;
                } else {
                    btn.className += 'text-gray-200 font-medium hover:bg-gray-800 hover:text-white cursor-pointer hover:scale-105';
                    btn.innerHTML = d + '<span class="w-1 h-1 bg-blue-500 rounded-full absolute bottom-1.5 opacity-50"></span>';
                    
                    btn.addEventListener('click', async () => {
                        document.querySelectorAll('.calendar .day.active').forEach(x => {
                            x.className = 'day w-10 h-10 md:w-12 md:h-12 flex flex-col items-center justify-center rounded-full text-sm md:text-base transition-all duration-200 relative text-gray-200 font-medium hover:bg-gray-800 hover:text-white cursor-pointer hover:scale-105';
                        });
                        btn.className = 'day active w-10 h-10 md:w-12 md:h-12 flex flex-col items-center justify-center rounded-full text-sm md:text-base transition-all duration-200 relative bg-blue-600 text-white font-bold shadow-[0_0_15px_rgba(37,99,235,0.4)] scale-110 z-10';
                        
                        selectedDate = btn.dataset.date;
                        selectedTime = null;
                        step2El.classList.remove('hidden');
                        step2El.classList.add('block');
                        step3El.classList.remove('block');
                        step3El.classList.add('hidden');
                        
                        await loadTimesForDate(selectedDate);
                        updateSummaryAndButtonState();
                        
                        setTimeout(() => step2El.scrollIntoView({ behavior: 'smooth', block: 'center' }), 100);
                    });
                }
                
                wrapper.appendChild(btn);
                daysEl.appendChild(wrapper);
            }
        }
async function loadTimesForDate(dateStr) {
            timeSuggestions.innerHTML = '<p class="col-span-full text-gray-500 text-sm text-center py-4">Loading available times...</p>';
            try {
                const resp = await fetch(coachingAvailabilityUrl + '?date=' + dateStr, { credentials: 'same-origin' });
                if (!resp.ok) throw new Error();
                const json = await resp.json();
                timeSuggestions.innerHTML = '';

                let times = Array.isArray(allowedSlotTimes) && allowedSlotTimes.length > 0
                    ? allowedSlotTimes.slice()
                    : Object.keys(json.slots || {}).sort();

                if (times.length === 0) {
                    timeSuggestions.innerHTML = '<p class="col-span-full text-gray-500 text-sm text-center py-4">No available times for this date.</p>';
                    return;
                }

                times.forEach(t => {
                    const s = (json.slots && typeof json.slots[t] !== 'undefined') ? json.slots[t] : { remaining: 0 };
                    const b = document.createElement('button');
                    b.type = 'button';
                    b.textContent = t.replace(':', '.');
                    b.dataset.time = t;
                    
                    const baseClass = 'time flex items-center justify-center px-4 py-3 rounded-xl text-sm font-bold transition-all duration-200 border ';

                    if (typeof s.remaining !== 'undefined' && s.remaining <= 0) {
                        b.className = baseClass + 'bg-transparent text-gray-600 border-gray-800/50 cursor-not-allowed opacity-50 disabled';
                        b.disabled = true;
                    } else {
                        b.className = baseClass + 'bg-gray-900/50 text-gray-300 border-gray-700 hover:bg-gray-800 hover:text-white hover:border-gray-500 cursor-pointer';
                        b.addEventListener('click', () => {
                            selectedTime = b.dataset.time;
                            document.querySelectorAll('#timeSuggestions .time.selected').forEach(x => {
                                x.className = baseClass + 'bg-gray-900/50 text-gray-300 border-gray-700 hover:bg-gray-800 hover:text-white hover:border-gray-500 cursor-pointer time';
                            });
                            b.className = baseClass + 'bg-blue-600 text-white border-blue-500 shadow-[0_4px_15px_rgba(37,99,235,0.3)] scale-105 selected time';
                            
                            step3El.classList.remove('hidden');
                            step3El.classList.add('block');
                            updateSummaryAndButtonState();
                            
                            setTimeout(() => step3El.scrollIntoView({ behavior: 'smooth', block: 'center' }), 100);
                        });
                    }
                    timeSuggestions.appendChild(b);
                });
            } catch (e) {
                timeSuggestions.innerHTML = '<p class="col-span-full text-red-400 text-sm text-center py-4">Could not load times. Please try again.</p>';
            }
        }
function updateSummaryAndButtonState() {
            const hasTicket = submitBtn.getAttribute('data-has-ticket') === '1';
            const usingWarranty = submitBtn.getAttribute('data-using-warranty') === '1';
            if (!selectedDate) {
                selectionSummaryTextEl.textContent = '—';
                submitBtn.disabled = true;
                submitBtn.textContent = 'Select a Date & Time';
                return;
            }
            const parts = selectedDate.split('-').map(x => parseInt(x, 10));
            const d = new Date(parts[0], parts[1] - 1, parts[2]);
            const monthNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
            const dayName = d.toLocaleString('id-ID', { weekday: 'long' });
            let summary = `${dayName}, ${d.getDate()} ${monthNames[d.getMonth()]} ${d.getFullYear()}`;

            if (selectedTime) {
                summary += ` — ${selectedTime}`;
                document.getElementById('booking_time').value = `${selectedDate} ${selectedTime}:00`;
                submitBtn.disabled = false;
                submitBtn.textContent = hasTicket ? (usingWarranty ? 'Confirm & Use Warranty Ticket' : 'Confirm & Use 1 Ticket') : 'Proceed to Payment';
                ticketInfoTextEl.textContent = hasTicket
                    ? (usingWarranty ? '🎟️ This session will use your warranty ticket.' : '🎟️ This session will use 1 of your available tickets.')
                    : '';
            } else {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Select a Time';
                ticketInfoTextEl.textContent = '';
            }
            selectionSummaryTextEl.innerHTML = `🗓️ <strong>${summary}</strong>`;
        }

        prevBtn.addEventListener('click', () => {
            const firstOfCurrent = new Date(current.getFullYear(), current.getMonth(), 1);
            if (firstOfCurrent.getFullYear() === minMonth.getFullYear() && firstOfCurrent.getMonth() === minMonth.getMonth()) return;
            current = new Date(current.getFullYear(), current.getMonth() - 1, 1);
            renderMonth(current);
        });

        nextBtn.addEventListener('click', () => {
            current = new Date(current.getFullYear(), current.getMonth() + 1, 1);
            renderMonth(current);
        });

        form && form.addEventListener('submit', async function (e) {
            const bookingInput = document.getElementById('booking_time');
            if (!bookingInput || !bookingInput.value) {
                e.preventDefault();
                alert('Please select a date and time before booking.');
                return;
            }
            const hasTicket = submitBtn.getAttribute('data-has-ticket') === '1';
            if (!hasTicket) {
                e.preventDefault();
                window.location.href = `${coachingCheckoutUrl}?schedule=${encodeURIComponent(bookingInput.value)}&notes=${encodeURIComponent(document.getElementById('session_notes').value)}`;
                return;
            }
            e.preventDefault();
            submitBtn.disabled = true;
            submitBtn.textContent = 'Booking...';
            const payload = new URLSearchParams();
            payload.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            payload.append('booking_time', bookingInput.value);
            payload.append('notes', document.getElementById('session_notes').value || '');
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

        renderMonth(current);
        updateSummaryAndButtonState();
    });
    </script>
@endpush