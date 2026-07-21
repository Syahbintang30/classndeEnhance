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
                        brand: {
                            black: '#08080a',
                            card: 'rgba(18, 18, 24, 0.65)',
                            border: 'rgba(255, 255, 255, 0.08)',
                            accent: '#0066ff',
                            amber: '#f59e0b',
                            crimson: '#ef4444'
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        display: ['"Bebas Neue"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        .tw-dash {
            background-color: #08080a !important;
            color: #f3f4f6 !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
        }
        .tw-dash .font-display {
            font-family: 'Bebas Neue', cursive;
            letter-spacing: 1px;
        }
        .glass-panel {
            background: rgba(18, 18, 26, 0.55);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 1.5rem;
        }
        body > nav { display: none !important; }
        .tw-dash ::-webkit-scrollbar { width: 6px; }
        .tw-dash ::-webkit-scrollbar-track { background: #08080a; }
        .tw-dash ::-webkit-scrollbar-thumb { background: #222232; border-radius: 3px; }
        .tw-dash a { text-decoration: none; }
        .tw-dash *:focus { outline: none !important; }
    </style>
@endpush

@section('content')
<div class="tw-dash min-h-screen flex flex-col antialiased bg-[#08080a] text-gray-200 relative overflow-hidden" x-data="{ mobileMenuOpen: false }">

    {{-- Ambient Mesh Background Glow --}}
    <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-blue-600/10 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute top-1/2 -right-32 w-[400px] h-[400px] bg-indigo-600/10 rounded-full blur-[120px] pointer-events-none"></div>

    {{-- ─── TOP NAVIGATION BAR ──────────────────────────────────────────── --}}
    @include('layouts.lms_header')

    <main class="flex-1 max-w-4xl mx-auto w-full px-4 lg:px-8 py-10 space-y-8 relative z-10">
        
        <!-- STEP INDICATOR BAR -->
        <div class="flex items-center justify-center gap-3 sm:gap-6 mb-8">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 rounded-full bg-blue-500/20 border border-blue-500/40 text-blue-400 font-bold flex items-center justify-center text-sm shadow-md">
                    <i class="fa-solid fa-circle-info"></i>
                </div>
                <span class="text-xs font-bold text-gray-400 hidden sm:inline">Info</span>
            </div>

            <div class="w-12 sm:w-20 h-0.5 bg-gradient-to-r from-blue-500/40 to-indigo-500/60 rounded-full"></div>

            <div class="flex items-center gap-2">
                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-white font-bold flex items-center justify-center text-sm shadow-lg shadow-blue-600/30 ring-4 ring-blue-500/20">
                    <i class="fa-solid fa-credit-card"></i>
                </div>
                <span class="text-xs font-bold text-white hidden sm:inline">Payment</span>
            </div>

            <div class="w-12 sm:w-20 h-0.5 bg-zinc-800 rounded-full"></div>

            <div class="flex items-center gap-2">
                <div class="w-10 h-10 rounded-full bg-zinc-900 border border-white/10 text-gray-500 font-bold flex items-center justify-center text-sm">
                    <i class="fa-solid fa-check"></i>
                </div>
                <span class="text-xs font-bold text-gray-500 hidden sm:inline">Confirmation</span>
            </div>
        </div>

        <!-- MAIN CHECKOUT GLASS CARD -->
        <div class="glass-panel p-6 sm:p-8 relative overflow-hidden">
            <div class="flex items-center justify-between pb-4 mb-6 border-b border-white/5">
                <div>
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-cart-shopping text-blue-400"></i> Order Summary
                    </h2>
                    <p class="text-xs text-gray-400 mt-1">
                        @if($isCoachingMember ?? false)
                            You are detected as an active member. Your coaching ticket uses the special member price.
                        @else
                            You do not currently have an active Beginner/Intermediate package. Your ticket uses regular pricing.
                        @endif
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left Column: Details (7 Cols) -->
                <div class="lg:col-span-7 space-y-4">
                    
                    <!-- Package Info -->
                    <div class="bg-zinc-950/60 rounded-2xl p-5 border border-white/5 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold text-blue-400 uppercase tracking-widest bg-blue-500/10 px-2.5 py-0.5 rounded-full border border-blue-500/20">Package</span>
                            <span class="text-xs font-semibold text-gray-400">
                                {{ ($isCoachingMember ?? false) ? 'Member Special' : 'Standard Rate' }}
                            </span>
                        </div>
                        
                        <h3 id="pkgName" class="text-xl font-bold text-white">
                            {{ $package ? $package->name : 'Coaching Ticket Pass' }}
                        </h3>
                        
                        <div id="pkgPrice" class="font-display text-4xl text-blue-400">
                            Rp {{ number_format((int) ($displayPrice ?? 0),0,',','.') }}
                        </div>
                    </div>

                    <!-- Schedule Info -->
                    <div class="bg-zinc-950/60 rounded-2xl p-5 border border-white/5">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-2">Schedule Selection</span>
                        <div id="scheduleDisplay" class="text-sm font-semibold text-gray-200 flex items-center gap-2">
                            <i class="fa-regular fa-calendar-check text-blue-400"></i>
                            <span>{{ $scheduleDisplay ?? 'No specific date selected (Flexible Booking)' }}</span>
                        </div>
                    </div>

                </div>

                <!-- Right Column: Payment Box (5 Cols) -->
                <div class="lg:col-span-5">
                    <div class="bg-gradient-to-b from-zinc-900/80 to-zinc-950/90 rounded-2xl p-6 border border-blue-500/30 shadow-xl space-y-6">
                        
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center text-lg shadow-md">
                                <i class="fa-solid fa-credit-card"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-white">Payment Method</h4>
                                <div class="text-xs text-gray-400">Instant via Midtrans</div>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-white/10 space-y-2">
                            <div class="flex items-center justify-between text-xs text-gray-400">
                                <span>Subtotal</span>
                                <span>Rp {{ number_format((int) ($displayPrice ?? 0),0,',','.') }}</span>
                            </div>
                            <div class="flex items-center justify-between text-xs text-gray-400">
                                <span>Platform Fee</span>
                                <span class="text-emerald-400 font-semibold">FREE</span>
                            </div>
                            <div class="flex items-center justify-between pt-3 border-t border-white/10">
                                <span class="text-sm font-bold text-white">Total Payment</span>
                                <span id="totalAmount" class="font-display text-3xl text-white">
                                    Rp {{ number_format((int) ($displayPrice ?? 0),0,',','.') }}
                                </span>
                            </div>
                        </div>

                        <button id="payBtn" class="w-full py-4 rounded-xl bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-500 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-sm tracking-wide shadow-lg shadow-blue-600/30 transition-all hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-lock text-xs"></i>
                            <span>PAY WITH MIDTRANS</span>
                        </button>

                        <div class="flex items-center justify-center gap-2 text-[11px] text-gray-400">
                            <i class="fa-solid fa-shield-halved text-emerald-400"></i>
                            <span>256-bit Encrypted Secure Checkout</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </main>
</div>
@endsection

@push('scripts')
@php
    $midtransHost = config('services.midtrans.is_production') ? 'https://app.midtrans.com' : 'https://app.sandbox.midtrans.com';
    $midtransClientKey = $midtrans['client_key'] ?? config('services.midtrans.client_key') ?? '';
@endphp
<script src="{{ $midtransHost }}/snap/snap.js" data-client-key="{{ $midtransClientKey }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    const payBtn = document.getElementById('payBtn');
    const selectedSchedule = @json($scheduleValue ?? null);
    const createOrderUrl = @json(route('coaching.checkout.create', [], false));
    const finalizeOrderUrl = @json(route('coaching.checkout.finalize', [], false));
    const upcomingUrl = @json(route('coaching.upcoming', [], false));

    function resolveErrorMessage(payload, fallback) {
        if (!payload) return fallback;
        if (typeof payload === 'string') return payload;
        if (payload.error) return payload.error;
        if (payload.message) return payload.message;
        if (payload.body && payload.body.error_messages && Array.isArray(payload.body.error_messages) && payload.body.error_messages.length) {
            return payload.body.error_messages[0];
        }
        return fallback;
    }

    payBtn && payBtn.addEventListener('click', async function(){
        payBtn.disabled = true;
        payBtn.textContent = 'Preparing...';
        
        const packageId = {{ $package ? $package->id : 'null' }};
        try {
            if (!packageId) {
                throw new Error('Coaching package not found. Please contact the admin to configure the package.');
            }

            const createBody = { package_id: packageId };
            if (selectedSchedule && typeof selectedSchedule === 'string' && selectedSchedule.trim() !== '') {
                createBody.schedule = selectedSchedule.trim();
            }

            const res = await fetch(createOrderUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(createBody)
            });

            let json = {};
            try {
                json = await res.json();
            } catch (e) {
                json = { error: 'Server returned non-JSON response' };
            }

            if (! res.ok) {
                alert(resolveErrorMessage(json, 'Failed to create order'));
                payBtn.disabled = false;
                payBtn.textContent = 'PAY WITH MIDTRANS';
                return;
            }

            const snapRes = await fetch('/api/midtrans/create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ order_id: json.order_id, gross_amount: json.gross_amount, package_id: json.package_id })
            });

            let snapJson = {};
            try {
                snapJson = await snapRes.json();
            } catch (e) {
                snapJson = { error: 'Server returned non-JSON response' };
            }

            if (! snapRes.ok) {
                alert(resolveErrorMessage(snapJson, 'Midtrans create failed')); 
                payBtn.disabled = false; 
                payBtn.textContent = 'PAY WITH MIDTRANS'; 
                return;
            }

            const token = snapJson.snap_token || snapJson.raw?.token;
            if (! token) { 
                alert('Midtrans token not returned'); 
                payBtn.disabled = false; 
                payBtn.textContent = 'PAY WITH MIDTRANS'; 
                return; 
            }

            if (!window.snap || typeof window.snap.pay !== 'function') {
                alert('Midtrans popup gagal dimuat. Coba nonaktifkan ad-blocker/shield browser lalu refresh halaman.');
                payBtn.disabled = false;
                payBtn.textContent = 'PAY WITH MIDTRANS';
                return;
            }

            async function finalizeAfterSnap(result){
                try {
                    await fetch(finalizeOrderUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            order_id: json.order_id,
                            transaction_status: result?.transaction_status || null,
                            result: result || null
                        })
                    });
                } catch (e) {
                    console.warn('Finalize checkout failed', e);
                }
            }

            window.snap.pay(token, {
                onSuccess: async function(result){
                    await finalizeAfterSnap(result);
                    window.location.href = `${upcomingUrl}?paid=1`;
                },
                onPending: async function(result){
                    await finalizeAfterSnap(result);
                    window.location.href = `${upcomingUrl}?paid=pending`;
                },
                onError: function(err){
                    const msg = (err && (err.status_message || err.message)) ? (err.status_message || err.message) : 'Payment failed';
                    alert(msg);
                    payBtn.disabled = false;
                    payBtn.textContent = 'PAY WITH MIDTRANS';
                },
                onClose: function(){
                    payBtn.disabled = false;
                    payBtn.textContent = 'PAY WITH MIDTRANS';
                }
            });

        } catch (e) {
            console.error(e);
            alert((e && e.message) ? e.message : 'Unexpected error');
            payBtn.disabled = false;
            payBtn.textContent = 'PAY WITH MIDTRANS';
        }
    });
});
</script>
@endpush
