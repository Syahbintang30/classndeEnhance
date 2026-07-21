@extends('layouts.app')

@section('title', isset($package) && $package ? ($package->name . ' - Complete Payment') : 'Complete Your Payment')

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
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        display: ['"Bebas Neue"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #08080a !important; color: #ffffff !important; font-family: 'Plus Jakarta Sans', sans-serif !important; }
        .font-display { font-family: 'Bebas Neue', cursive !important; letter-spacing: 1px; }
        body > nav, .global-nav { display: none !important; }
        .checkout-hidden { display: none !important; }
    </style>
@endpush

@section('content')
@php
    $orderName = $package ? $package->name : ($lesson->title ?? 'Lifetime Access');
    $orderNote = isset($package) && $package && isset($package->slug) && $package->slug === 'coaching-ticket'
        ? "You're one step closer to achieving your goals with our professional coach"
        : 'Lifetime access to all materials. Learn without limits!';
    $orderPackageLabel = $package ? 'Package' : 'Lifetime Access';
    $orderQuantity = !empty($order['item_details'][0]['quantity']) ? (int) $order['item_details'][0]['quantity'] : 1;
@endphp

<div class="min-h-screen bg-[#08080a] text-white flex flex-col relative selection:bg-blue-600 selection:text-white overflow-hidden pb-12">
    
    {{-- Ambient Mesh Background Glows --}}
    <div class="absolute top-1/4 left-1/4 w-[600px] h-[600px] bg-blue-600/15 rounded-full blur-[150px] pointer-events-none z-0 mix-blend-screen"></div>
    <div class="absolute bottom-1/4 right-1/4 w-[500px] h-[500px] bg-purple-600/15 rounded-full blur-[150px] pointer-events-none z-0 mix-blend-screen"></div>

    {{-- LMS Floating Glass Pill Header --}}
    <div class="relative z-20">
        @include('layouts.lms_header')
    </div>

    {{-- Main Checkout Container --}}
    <main class="flex-1 w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative z-10">
        
        <!-- Top Stepper -->
        <div class="max-w-md mx-auto mb-8 flex items-center justify-center gap-4">
            <!-- Step 1: Info (Done) -->
            <div class="flex items-center gap-2 text-xs font-bold text-emerald-400">
                <div class="w-8 h-8 rounded-full bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center text-emerald-400 shadow-md">
                    <i class="fa-solid fa-check text-xs"></i>
                </div>
                <span class="hidden sm:inline uppercase tracking-wider text-[11px]">Select</span>
            </div>

            <div class="h-px bg-white/10 flex-1 max-w-[60px]"></div>

            <!-- Step 2: Payment (Active) -->
            <div class="flex items-center gap-2 text-xs font-bold text-white">
                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-blue-600/30 ring-4 ring-blue-500/20 animate-pulse">
                    <i class="fa-solid fa-credit-card text-xs"></i>
                </div>
                <span class="uppercase tracking-widest text-[11px] font-extrabold text-blue-400">Payment</span>
            </div>

            <div class="h-px bg-white/10 flex-1 max-w-[60px]"></div>

            <!-- Step 3: Finish -->
            <div class="flex items-center gap-2 text-xs font-bold text-gray-500">
                <div class="w-8 h-8 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-500">
                    <i class="fa-solid fa-flag-checkered text-xs"></i>
                </div>
                <span class="hidden sm:inline uppercase tracking-wider text-[11px]">Access</span>
            </div>
        </div>

        <!-- 2-Column Grid Checkout Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Column: Order Summary Card (5 columns) -->
            <div class="lg:col-span-5 w-full">
                <div class="bg-zinc-950/60 border border-white/10 backdrop-blur-3xl rounded-[2rem] p-6 sm:p-7 shadow-[0_0_50px_rgba(0,0,0,0.5)] shadow-blue-900/10 relative overflow-hidden space-y-5">
                    
                    <!-- Inner Glow top border -->
                    <div class="absolute top-0 inset-x-0 h-[1px] bg-gradient-to-r from-transparent via-blue-500/50 to-transparent"></div>
                    
                    <div class="space-y-1">
                        <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-[10px] font-bold uppercase tracking-widest mb-1">
                            Order Summary
                        </div>
                        <h2 class="font-display text-3xl text-white tracking-wide uppercase leading-none">Your Order Is <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">Ready</span></h2>
                    </div>

                    <!-- Package Info Box -->
                    <div class="p-4 rounded-2xl bg-white/5 border border-white/10 space-y-2">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-display text-2xl text-white tracking-wide uppercase leading-tight">{{ $orderName }}</h3>
                                <p class="text-[11px] text-blue-400 font-bold uppercase tracking-wider mt-0.5">
                                    {{ $orderPackageLabel }} @if($orderQuantity > 1) &middot; Qty: {{ $orderQuantity }} @endif
                                </p>
                            </div>
                            <div class="font-display text-2xl text-white">
                                Rp {{ number_format($order['gross_amount'],0,',','.') }}
                            </div>
                        </div>
                    </div>

                    <!-- Note Badge -->
                    <div class="p-3.5 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-300 text-xs space-y-1">
                        <div class="text-[9px] font-extrabold uppercase tracking-widest text-blue-400 flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-info"></i> Note
                        </div>
                        <p class="text-xs text-gray-300 leading-relaxed">{{ $orderNote }}</p>
                    </div>

                    <!-- Price Breakdown -->
                    <div class="space-y-3 pt-3 border-t border-white/10 text-xs">
                        <div class="flex justify-between items-center text-gray-400">
                            <span>Subtotal (Original Price)</span>
                            <span class="text-white font-medium">Rp {{ number_format($order['original_amount'] ?? $order['gross_amount'],0,',','.') }}</span>
                        </div>

                        @if(!empty($order['applied_referral_percent']) && $order['applied_referral_percent'] > 0)
                            <div class="flex justify-between items-center text-emerald-400 font-bold">
                                <span>Referral Discount ({{ $order['applied_referral_percent'] }}%)</span>
                                <span id="referral_discount_amount">- Rp {{ number_format( max(0, ($order['original_amount'] ?? $order['gross_amount']) - $order['gross_amount']),0,',','.') }}</span>
                            </div>
                            @if(!empty($order['referral_code']))
                                <div class="text-[11px] text-gray-400">Referral code: <strong id="referral_code_display" class="text-emerald-400">{{ $order['referral_code'] }}</strong></div>
                            @endif
                        @endif

                        <div id="voucher_discount_row" class="flex justify-between items-center text-emerald-400 font-bold checkout-hidden">
                            <span id="voucher_discount_label">Voucher Discount:</span>
                            <span id="voucher_discount_amount">- Rp 0</span>
                        </div>

                        <div class="flex justify-between items-center text-gray-400">
                            <span>Tax</span>
                            <span class="text-white font-medium">Rp 0</span>
                        </div>

                        <div class="pt-3 border-t border-white/10 flex justify-between items-center">
                            <span class="text-xs font-bold text-gray-300 uppercase tracking-wider">Total Payment</span>
                            <span id="total_payment_amount" class="font-display text-3xl text-white">Rp {{ number_format($order['gross_amount'],0,',','.') }}</span>
                        </div>
                    </div>

                    <!-- Back Button -->
                    <div class="pt-2">
                        <a href="{{ route('compro') }}#packages" class="w-full py-2.5 px-4 rounded-xl bg-white/5 border border-white/10 hover:border-white/20 text-xs font-bold text-gray-300 hover:text-white transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-arrow-left text-[10px]"></i>
                            <span>Back to Course Selection</span>
                        </a>
                    </div>

                </div>
            </div>

            <!-- Right Column: Complete Payment Action Card (7 columns) -->
            <div class="lg:col-span-7 w-full">
                <div class="bg-zinc-950/60 border border-white/10 backdrop-blur-3xl rounded-[2rem] p-6 sm:p-8 shadow-[0_0_50px_rgba(0,0,0,0.5)] shadow-blue-900/10 relative overflow-hidden space-y-6">
                    
                    <!-- Inner Glow top border -->
                    <div class="absolute top-0 inset-x-0 h-[1px] bg-gradient-to-r from-transparent via-blue-500/50 to-transparent"></div>
                    
                    <!-- Title -->
                    <div class="space-y-1">
                        <h2 class="font-display text-3xl sm:text-4xl text-white tracking-wide uppercase leading-none">Complete Your <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">Payment</span></h2>
                        <p class="text-gray-400 text-xs">Select your payment channel and finalize your enrollment.</p>
                    </div>

                    <div id="payment-methods-list" data-total="{{ $order['gross_amount'] }}" data-order-id="{{ $order['order_id'] }}"></div>

                    <!-- Midtrans Information Notice -->
                    <div class="p-4 rounded-2xl bg-blue-500/10 border border-blue-500/20 text-xs text-blue-200 leading-relaxed flex items-start gap-3">
                        <i class="fa-solid fa-credit-card text-blue-400 text-sm mt-0.5 shrink-0"></i>
                        <p>Select your payment channel directly in the secure Midtrans gateway popup. Options like Bank Transfer (VA), QRIS, GoPay, and credit cards are available.</p>
                    </div>

                    <!-- Security Notice -->
                    <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-xs text-emerald-300 leading-relaxed flex items-start gap-3" id="payment-details-display">
                        <i class="fa-solid fa-shield-halved text-emerald-400 text-sm mt-0.5 shrink-0"></i>
                        <p>Rest assured, your transaction is 100% secure and processed by Midtrans. <strong>Payments are verified automatically</strong>; no manual proof upload needed.</p>
                    </div>

                    <!-- Voucher Code Form -->
                    <div class="space-y-2 pt-2 border-t border-white/10">
                        <label for="voucher_code_input" class="text-xs font-bold text-gray-300">Have a voucher code?</label>
                        <div class="flex gap-2">
                            <input id="voucher_code_input" type="text" class="flex-1 px-4 py-2.5 rounded-xl bg-zinc-900/80 border border-white/10 text-white text-xs placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition" placeholder="Enter voucher code" />
                            <button id="voucher_validate_btn" type="button" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs transition shadow-md cursor-pointer">
                                Apply
                            </button>
                        </div>
                        <div id="voucher_feedback" class="text-xs text-gray-400 font-medium min-h-[18px]">&nbsp;</div>
                    </div>

                    <!-- Submit Pay Button -->
                    <button id="pay-button" class="w-full py-4 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-display text-2xl tracking-widest shadow-lg shadow-blue-600/30 transition-all hover:scale-[1.01] cursor-pointer">
                        PAY &amp; START LEARNING
                    </button>

                    <form id="payment-complete-form" method="POST" action="{{ route('kelas.payment.complete', ['lesson' => $lesson->id]) }}" class="checkout-hidden">
                        @csrf
                        <input type="hidden" id="order_id_input" name="order_id" value="{{ $order['order_id'] }}" />
                        <input type="hidden" name="midtrans_result" id="midtrans_result" value="" />
                    </form>

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
    let activeSnapToken = null;
    let isCreatingSnapToken = false;
    let appliedVoucher = null;

    function formatIDR(amount) {
        return 'Rp ' + Number(amount || 0).toLocaleString('id-ID');
    }

    function getCurrentAmount() {
        const dataEl = document.getElementById('payment-methods-list');
        if (!dataEl) return {{ (int) ($order['gross_amount'] ?? 0) }};
        return parseInt(dataEl.getAttribute('data-total') || '{{ $order['gross_amount'] }}', 10) || 0;
    }

    function getOriginalAmount() {
        return parseInt('{{ $order['original_amount'] ?? $order['gross_amount'] }}', 10) || getCurrentAmount();
    }

    function getReferralPercent() {
        return parseFloat('{{ $order['applied_referral_percent'] ?? 0 }}') || 0;
    }

    function updateTotalsAfterDiscounts() {
        const originalAmount = getOriginalAmount();
        const referralPercent = getReferralPercent();
        const afterReferral = Math.round(originalAmount * (100 - referralPercent) / 100);
        const voucherPercent = appliedVoucher && appliedVoucher.discount_percent ? parseFloat(appliedVoucher.discount_percent) : 0;
        const afterVoucher = Math.round(afterReferral * (100 - voucherPercent) / 100);

        const referralDiscountAmount = Math.max(0, originalAmount - afterReferral);
        const voucherDiscountAmount = Math.max(0, afterReferral - afterVoucher);

        const referralRow = document.getElementById('referral_discount_amount');
        if (referralRow) {
            referralRow.textContent = '- ' + formatIDR(referralDiscountAmount);
        }

        const voucherRow = document.getElementById('voucher_discount_row');
        const voucherAmount = document.getElementById('voucher_discount_amount');
        const voucherLabel = document.getElementById('voucher_discount_label');
        if (voucherPercent > 0) {
            if (voucherRow) voucherRow.classList.remove('checkout-hidden');
            if (voucherAmount) voucherAmount.textContent = '- ' + formatIDR(voucherDiscountAmount);
            if (voucherLabel) voucherLabel.textContent = 'Voucher Discount (' + voucherPercent + '%)';
        } else {
            if (voucherRow) voucherRow.classList.add('checkout-hidden');
            if (voucherAmount) voucherAmount.textContent = '- Rp 0';
            if (voucherLabel) voucherLabel.textContent = 'Voucher Discount:';
        }

        const totalEl = document.getElementById('total_payment_amount');
        if (totalEl) totalEl.textContent = formatIDR(afterVoucher);

        const dataEl = document.getElementById('payment-methods-list');
        if (dataEl) dataEl.setAttribute('data-total', String(afterVoucher));

        return afterVoucher;
    }

    function openSnapPopup(token) {
        snap.pay(token, {
            onSuccess: function(result){
                try { document.getElementById('midtrans_result').value = JSON.stringify(result); } catch (e) {}
                if (result && result.order_id) {
                    const orderInput = document.getElementById('order_id_input');
                    if (orderInput) orderInput.value = result.order_id;
                }
                document.getElementById('payment-complete-form').submit();
            },
            onPending: function(result){
                try { document.getElementById('midtrans_result').value = JSON.stringify(result); } catch (e) {}
                if (result && result.order_id) {
                    const orderInput = document.getElementById('order_id_input');
                    if (orderInput) orderInput.value = result.order_id;
                }
                document.getElementById('payment-complete-form').submit();
            },
            onError: function(){
                alert('Payment Failed. Please try again.');
            },
            onClose: function(){
            }
        });
    }

    updateTotalsAfterDiscounts();

    document.getElementById('pay-button').addEventListener('click', function(){
        if (isCreatingSnapToken) {
            return;
        }

        if (activeSnapToken) {
            openSnapPopup(activeSnapToken);
            return;
        }

        const payload = { order_id: '{{ $order['order_id'] }}', gross_amount: getCurrentAmount() };
        payload.referral = '{{ $order['referral_code'] ?? '' }}';
        if (typeof appliedVoucher !== 'undefined' && appliedVoucher && appliedVoucher.code) {
            payload.voucher_code = appliedVoucher.code;
            if (appliedVoucher.id) payload.voucher_id = appliedVoucher.id;
        }
        @if(isset($package))
            payload.package_id = {{ $package->id }};
            payload.package_qty = {{ request()->input('package_qty') ?: 1 }};
            payload.package_unit_price = {{ $package->price }};
        @else
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('package_id')) {
                payload.package_id = urlParams.get('package_id');
                payload.package_qty = parseInt(urlParams.get('package_qty') || '1', 10);
            }
        @endif

        isCreatingSnapToken = true;
        const payButton = document.getElementById('pay-button');
        if (payButton) {
            payButton.disabled = true;
            payButton.textContent = 'PREPARING PAYMENT...';
        }

        fetch('/api/midtrans/create', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content},
            body: JSON.stringify(payload)
        }).then(r => r.json()).then(json => {
            if (json.snap_token) {
                activeSnapToken = json.snap_token;
                if (json.order_id) {
                    const orderInput = document.getElementById('order_id_input');
                    if (orderInput) orderInput.value = json.order_id;
                }
                openSnapPopup(json.snap_token);
            } else {
                const midtransError = json && json.body && Array.isArray(json.body.error_messages) && json.body.error_messages.length
                    ? json.body.error_messages[0]
                    : (json.message || json.error || 'Failed to process payment. Please try again in a moment.');
                alert(midtransError);
            }
        }).catch(e => {
            console.error(e);
            alert('A network error occurred. Please check your connection and try again.');
        }).finally(() => {
            isCreatingSnapToken = false;
            if (payButton) {
                payButton.disabled = false;
                payButton.textContent = 'PAY & START LEARNING';
            }
        });
    });

    document.getElementById('voucher_validate_btn').addEventListener('click', function(e){
        e.preventDefault();
        const code = document.getElementById('voucher_code_input').value.trim();
        if (! code) { document.getElementById('voucher_feedback').innerText = 'Please enter a voucher code.'; return; }
        document.getElementById('voucher_feedback').innerText = 'Checking...';
        fetch('/vouchers/validate', { method: 'POST', headers: {'Content-Type':'application/json','X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }, body: JSON.stringify({ code }) })
            .then(r => r.json()).then(json => {
                if (json.valid) {
                    appliedVoucher = { code: code, id: json.voucher_id, discount_percent: json.discount_percent };
                    document.getElementById('voucher_feedback').innerText = 'Voucher applied: ' + json.discount_percent + '% off';
                    document.getElementById('voucher_feedback').style.color = '#34d399';
                    try { updateTotalsAfterDiscounts(); } catch(e){ console.error(e); }
                } else {
                    appliedVoucher = null;
                    document.getElementById('voucher_feedback').innerText = json.message || 'Invalid voucher';
                    document.getElementById('voucher_feedback').style.color = '#f87171';
                    try { updateTotalsAfterDiscounts(); } catch(e){}
                }
            }).catch(e => { appliedVoucher = null; document.getElementById('voucher_feedback').innerText = 'Validation error'; document.getElementById('voucher_feedback').style.color = '#f87171'; });
    });
</script>
@endpush

@section('modals')
    <x-modal name="payment-method-modal" focusable>
        <div id="payment-modal-content" class="p-4">
        </div>
    </x-modal>
@endsection
