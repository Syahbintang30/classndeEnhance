@extends('layouts.app')

@section('title', isset($package) && $package ? ($package->name . ' - One Step Away!') : 'Complete Your Payment')

@section('content')


<div class="steps-wrap" style="display:flex;justify-content:center;padding-top:18px;padding-left:18px;padding-right:18px;">
    <div class="steps" role="tablist" aria-label="Booking steps" style="display:flex;align-items:center;gap:12px;max-width:780px;width:100%;justify-content:center;margin:0 auto;">
    <div class="step" title="Info"><i class="icon-info" aria-hidden="true"></i><span class="sr-only">Info</span></div>
        <div class="line" aria-hidden="true"></div>
    <div class="step active" aria-current="step" title="Payment"><i class="icon-credit-card" aria-hidden="true"></i><span class="sr-only">Payment</span></div>
        <div class="line" aria-hidden="true"></div>
    <div class="step" title="Done"><i class="icon-check" aria-hidden="true"></i><span class="sr-only">Done</span></div>
    </div>
</div>

<div class="payment-page" style="padding:24px;color:#fff;">
    <style>
        /* Mobile-first responsive layout for payment page */
        .payment-wrap { display:flex; flex-direction:column; gap:24px; max-width:1100px; margin:0 auto; }
        .payment-left { flex:1; width:100%; }
        .payment-right { flex:1; width:100%; }
        .order-card { max-width:unset; }
        @media (min-width: 900px) {
            .payment-wrap { flex-direction:row; gap:40px; align-items:flex-start; }
            .order-card { max-width:420px; }
        }
        /* Tighten paddings on small screens */
        @media (max-width: 480px) {
            .payment-page { padding:16px; }
        }
    </style>
    <div class="payment-wrap">
        <!-- Left: Order Summary -->
        <div class="payment-left order-card" style="border:1px solid rgba(255,255,255,0.06);padding:20px;border-radius:6px;background:rgba(0,0,0,0.2)">
            <h3 style="margin-bottom:8px">Your Order is Ready</h3>
            <div style="display:flex;justify-content:space-between;margin:16px 0">
                <div>
                    <div style="font-weight:600">{{ $package ? $package->name : $lesson->title }}</div>
                    <div style="opacity:0.7;font-size:13px">
                        {{ $package ? 'Package' : 'Lifetime Access' }}
                        @if(!empty($order['item_details'][0]['quantity']) && $order['item_details'][0]['quantity'] > 1)
                            &middot; Qty: {{ $order['item_details'][0]['quantity'] }}
                        @endif
                    </div>
                </div>
                <div style="font-weight:700">Rp {{ number_format($order['gross_amount'],0,',','.') }}</div>
            </div>

            <div style="height:18px;border-top:1px solid rgba(255,255,255,0.03);margin:24px 0"></div>

            <div style="font-size:13px;opacity:0.85">Note</div>
            @if(isset($package) && $package && isset($package->slug) && $package->slug === 'coaching-ticket')
                <div style="margin:12px 0 32px 0">You're one step closer to achieving your goals with our professional coach</div>
            @else
                <div style="margin:12px 0 32px 0">Lifetime access to all materials. Learn without limits!</div>
            @endif

            <div style="display:flex;justify-content:space-between;font-weight:600">
                <div>Subtotal (original price):</div>
                <div>Rp {{ number_format($order['original_amount'] ?? $order['gross_amount'],0,',','.') }}</div>
            </div>
            @if(!empty($order['applied_referral_percent']) && $order['applied_referral_percent'] > 0)
                <div style="display:flex;justify-content:space-between;color:#b8f0c6;margin-top:8px;font-weight:600">
                    <div>Referral Discount ({{ $order['applied_referral_percent'] }}%):</div>
                    <div id="referral_discount_amount">- Rp {{ number_format( max(0, ($order['original_amount'] ?? $order['gross_amount']) - $order['gross_amount']),0,',','.') }}</div>
                </div>
                @if(!empty($order['referral_code']))
                    <div style="margin-top:6px;font-size:13px;color:rgba(255,255,255,0.75)">Referral code used: <strong id="referral_code_display">{{ $order['referral_code'] }}</strong></div>
                @endif
            @endif

            <!-- Voucher discount (calculated client-side when a voucher is applied) -->
            <div id="voucher_discount_row" style="display:none;justify-content:space-between;color:#b8f0c6;margin-top:8px;font-weight:600">
                <div id="voucher_discount_label">Voucher Discount:</div>
                <div id="voucher_discount_amount">- Rp 0</div>
            </div>
            <div style="display:flex;justify-content:space-between;color:rgba(255,255,255,0.7);margin-top:8px">
                <div>Tax:</div>
                <div>Rp 0</div>
            </div>

            <div style="height:1px;background:rgba(255,255,255,0.03);margin:22px 0"></div>
            <div style="display:flex;justify-content:space-between;align-items:center">
                <div style="font-weight:700">Total Payment:</div>
                <div id="total_payment_amount" style="font-weight:800; font-size: 1.1em;">Rp {{ number_format($order['gross_amount'],0,',','.') }}</div>
            </div>
        </div>

        <!-- Right: Payment options -->
        <div class="payment-right">
            <h3 style="margin-bottom:12px">Choose Your Payment Method</h3>
            <div class="payment-grid-container" id="payment-methods-list" data-total="{{ $order['gross_amount'] }}" data-order-id="{{ $order['order_id'] }}">
            @foreach($methods as $m)
                {{-- Hide QRIS payment option on this page --}}
                @if((isset($m->name) && strtolower($m->name) === 'qris') || (isset($m->display_name) && strtolower($m->display_name) === 'qris'))
                    @continue
                @endif
                <label class="payment-option" for="payment-{{ $m->id }}" aria-label="{{ $m->display_name }}">
                <input
                    type="radio"
                    name="payment_method"
                    id="payment-{{ $m->id }}"
                    value="{{ $m->name }}"
                    class="sr-only"
                    data-details="{{ $m->account_details }}"
                    data-name="{{ $m->display_name }}"
                >
                <div class="payment-option-visual" title="{{ $m->display_name }}">
                    @if($m->logo_url)
                    <img src="{{ asset($m->logo_url) }}" alt="{{ $m->display_name }}" class="payment-logo" />
                    @else
                    <div class="payment-logo-fallback">{{ strtoupper(mb_substr($m->display_name, 0, 1)) }}</div>
                    @endif
                </div>
                </label>
            @endforeach
            </div>
            <div id="payment-details-display" class="mt-4">
                 <!-- Trust Signal Text -->
                 <p style="font-size: 14px; opacity: 0.8; margin-top: 24px;">
                    <i class="icon-lock" aria-hidden="true"></i>
                    Rest assured, your transaction is 100% secure and processed by Midtrans. <strong>Payments are verified automatically</strong>, no need to upload proof of transfer.
                </p>
            </div>

            <!-- Voucher code form -->
            <div style="margin-top:18px;">
                <label style="font-size:13px;opacity:0.85;display:block">Have a voucher code?</label>
                <div style="display:flex;gap:8px;align-items:center;max-width:420px;">
                    <input id="voucher_code_input" type="text" class="form-control" placeholder="Enter voucher code" style="flex:1;padding:8px;border-radius:6px;border:1px solid rgba(255,255,255,0.08);background:transparent;color:#fff" />
                    <button id="voucher_validate_btn" class="btn btn-outline-light" style="padding:8px 12px;border-radius:6px">Apply</button>
                </div>
                <div id="voucher_feedback" style="margin-top:8px;font-size:13px;color:#ffd">&nbsp;</div>
            </div>

            <div style="margin-top:24px; margin-bottom:18px">
                <button id="pay-button" style="background:#007bff;border-radius:999px;padding:14px 32px;color:#fff;border:none;font-weight:700; font-size: 16px; cursor:pointer; width: 100%; max-width: 340px;">PAY & Start Learning</button>
            </div>

            <div style="margin-top:36px">
                <form id="payment-complete-form" method="POST" action="{{ route('kelas.payment.complete', ['lesson' => $lesson->id]) }}">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order['order_id'] }}" />
                    <input type="hidden" name="midtrans_result" id="midtrans_result" value="" />
                </form>
            </div>

        </div>
    </div>
</div>

<!-- Back button moved to page bottom -->
<div style="max-width:1100px;margin:12px auto 40px;display:flex;justify-content:flex-end;padding:0 16px;">
    <a href="{{ route('registerclass') }}" style="color:#fff;text-decoration:none;border:1px solid rgba(255,255,255,0.06);padding:10px 14px;border-radius:8px;background:rgba(255,255,255,0.02)" aria-label="Back to package selection">Back</a>
</div>

@endsection

@push('scripts')
@php
    $midtransHost = config('services.midtrans.is_production') ? 'https://app.midtrans.com' : 'https://app.sandbox.midtrans.com';
    $midtransClientKey = $midtrans['client_key'] ?? config('services.midtrans.client_key') ?? '';
@endphp
<script src="{{ $midtransHost }}/snap/snap.js" data-client-key="{{ $midtransClientKey }}"></script>
<script>
    // trigger initial update so server-applied referral is reflected in the UI
    try { updateTotalsAfterDiscounts(); } catch(e){}

    // Checkout flow uses Midtrans Snap popup

    document.getElementById('pay-button').addEventListener('click', function(){
        const selected = document.querySelector('input[name="payment_method"]:checked');
        if (! selected) {
            alert('Please select a payment method first.');
            return;
        }

        const paymentMethod = selected.value;
    const payload = { order_id: '{{ $order['order_id'] }}', gross_amount: {{ $order['gross_amount'] }}, payment_method: paymentMethod };
    // include referral code so server can validate and apply referral discount
    payload.referral = '{{ $order['referral_code'] ?? '' }}';
    // include voucher details if user applied one (so server can recalc and include in Midtrans order)
    if (typeof appliedVoucher !== 'undefined' && appliedVoucher && appliedVoucher.code) {
        payload.voucher_code = appliedVoucher.code;
        if (appliedVoucher.id) payload.voucher_id = appliedVoucher.id;
    }
    @if(isset($package))
            payload.package_id = {{ $package->id }};
            payload.package_qty = {{ request()->input('package_qty') ?: 1 }};
            payload.package_unit_price = {{ $package->price }};
        @else
            // if package_id was passed as query param (logged-in user flow)
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('package_id')) {
                payload.package_id = urlParams.get('package_id');
                payload.package_qty = parseInt(urlParams.get('package_qty') || '1', 10);
                // do not set package_unit_price here — let the server lookup canonical package price
            }
        @endif

        fetch("/api/midtrans/create", {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content},
            body: JSON.stringify(payload)
        }).then(r => r.json()).then(json => {
            if (json.snap_token) {
                snap.pay(json.snap_token, {
                    onSuccess: function(result){
                        try { document.getElementById('midtrans_result').value = JSON.stringify(result); } catch (e) {}
                        document.getElementById('payment-complete-form').submit();
                    },
                    onPending: function(result){
                        try { document.getElementById('midtrans_result').value = JSON.stringify(result); } catch (e) {}
                        document.getElementById('payment-complete-form').submit();
                    },
                    onError: function(){
                        alert('Payment Failed. Please try again.');
                    },
                    onClose: function(){
                        // User closed popup without finishing payment.
                    }
                });
            } else {
                const midtransError = json && json.body && Array.isArray(json.body.error_messages) && json.body.error_messages.length
                    ? json.body.error_messages[0]
                    : (json.message || json.error || 'Failed to process payment. Please try again in a moment.');
                alert(midtransError);
            }
        }).catch(e => { console.error(e); alert('A network error occurred. Please check your connection and try again.'); });
    });

    // voucher validation and attach to payload
    let appliedVoucher = null;
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
                    document.getElementById('voucher_feedback').style.color = '#b8f0c6';
                    // update UI: compute voucher discount amount and show row + update total
                    try { updateTotalsAfterDiscounts(); } catch(e){ console.error(e); }
                } else {
                    appliedVoucher = null;
                    document.getElementById('voucher_feedback').innerText = json.message || 'Invalid voucher';
                    document.getElementById('voucher_feedback').style.color = '#f8d7da';
                    try { updateTotalsAfterDiscounts(); } catch(e){}
                }
            }).catch(e => { appliedVoucher = null; document.getElementById('voucher_feedback').innerText = 'Validation error'; document.getElementById('voucher_feedback').style.color = '#f8d7da'; });
    });

    // helper: compute and render combined referral + voucher discount and update displayed gross amount
    function updateTotalsAfterDiscounts(){
        const dataEl = document.getElementById('payment-methods-list');
        if(!dataEl) return;
        const original = parseInt(dataEl.getAttribute('data-total') || '{{ $order['gross_amount'] }}', 10) || 0;

        // referral percent provided server-side (if any)
        const appliedReferralPercent = parseFloat('{{ $order['applied_referral_percent'] ?? 0 }}') || 0;
        // compute referral-reduced gross (server already applied referral when creating order.gross_amount,
        // but for client-side display of voucher we derive from original_amount if available)
        const originalAmount = parseInt('{{ $order['original_amount'] ?? $order['gross_amount'] }}', 10) || original;
        // amount after referral (server-side may have applied referral already to gross_amount)
        const afterReferral = Math.round(originalAmount * (100 - appliedReferralPercent) / 100);

        // voucher percent
        const voucherPct = appliedVoucher && appliedVoucher.discount_percent ? parseFloat(appliedVoucher.discount_percent) : 0;

        // voucher applies on top of referral-reduced amount (sequential percentage discounts)
        const afterVoucher = Math.round(afterReferral * (100 - voucherPct) / 100);

        const referralDiscountAmount = Math.max(0, originalAmount - afterReferral);
        const voucherDiscountAmount = Math.max(0, afterReferral - afterVoucher);

        // update referral display if exists
        const refRow = document.getElementById('referral_discount_amount');
        if (refRow) {
            refRow.textContent = '- Rp ' + referralDiscountAmount.toLocaleString('id-ID');
        }

        // update voucher row
        const voucherRow = document.getElementById('voucher_discount_row');
        const voucherAmountEl = document.getElementById('voucher_discount_amount');
        const voucherLabel = document.getElementById('voucher_discount_label');
        if (voucherPct > 0) {
            if (voucherRow) voucherRow.style.display = 'flex';
            if (voucherAmountEl) voucherAmountEl.textContent = '- Rp ' + voucherDiscountAmount.toLocaleString('id-ID');
            if (voucherLabel) voucherLabel.textContent = 'Voucher Discount (' + voucherPct + '%):';
        } else {
            if (voucherRow) voucherRow.style.display = 'none';
            if (voucherAmountEl) voucherAmountEl.textContent = '- Rp 0';
        }

        const totalEl = document.getElementById('total_payment_amount');
        if (totalEl) totalEl.textContent = 'Rp ' + afterVoucher.toLocaleString('id-ID');

        // also update the data-total attribute so other scripts using it see current value
        try { dataEl.setAttribute('data-total', String(afterVoucher)); } catch(e){}
    }
</script>
@endpush

@push('styles')
<style>
    .buy-progress { position:relative; }
    .buy-progress .progress-line { flex:1;height:2px;background:rgba(255,255,255,0.06);border-radius:2px; }
    .buy-progress .circle { width:44px;height:44px;border-radius:50%;display:flex;align-items:center;justify-content:center;border:2px solid rgba(255,255,255,0.12);background:transparent;color:#fff;font-size:18px }
    .buy-progress .circle.active { background:transparent;border-color:#fff;color:#fff }

        /* New steps component */
    .steps { display:flex;align-items:center;gap:12px; }
    .step { width:56px;height:56px;border-radius:50%;display:flex;align-items:center;justify-content:center;border:2px solid rgba(255,255,255,0.12);background:transparent;color:#fff;font-size:20px;transition:transform .12s ease, box-shadow .12s ease, background .12s ease; }
    .step.active { background:#fff;color:#000;border-color:#fff; box-shadow:0 8px 20px rgba(0,0,0,0.45); transform: translateY(-4px); }
    .steps .line { flex:1;height:3px;background:rgba(255,255,255,0.06);border-radius:2px; }
    .step i { font-size:20px; line-height:1; }

    /* Centering and safe gutters so it doesn't hug edges */
    .steps-wrap { max-width: 980px; margin: 0 auto; padding: 0 20px; }
    @media (max-width: 600px) {
        .steps-wrap { padding: 0 24px; }
        .steps { gap: 10px; }
        .step { width: 52px; height: 52px; }
        .steps .line { height: 2px; }
    }

    /* Style tambahan untuk tombol bayar agar lebih menonjol */
    #pay-button:hover {
        background: #0056b3; /* Warna lebih gelap saat hover */
        transform: scale(1.02);
        transition: all 0.2s ease-in-out;
    }

</style>
@endpush

@section('modals')
    <x-modal name="payment-method-modal" focusable>
        <div id="payment-modal-content" class="p-4">
            <!-- dynamic content injected here -->
        </div>
    </x-modal>
@endsection
