@extends('layouts.app')

@push('styles')
<style>
/* Page shell */
.coaching-checkout { max-width: 980px; margin: 28px auto; padding: 0 16px; }

.coaching-checkout {
  --cc-text: #ffffff;
  --cc-muted: rgba(255,255,255,0.75);
  --cc-subtle: rgba(255,255,255,0.62);
  --cc-card-bg: rgba(255,255,255,0.02);
  --cc-card-border: rgba(255,255,255,0.08);
  --cc-block-bg: rgba(255,255,255,0.03);
  --cc-block-border: rgba(255,255,255,0.07);
  --cc-step-bg: #ffffff;
  --cc-step-text: #111111;
  --cc-step-muted-bg: rgba(255,255,255,0.9);
  --cc-line: linear-gradient(90deg, rgba(255,255,255,0.25), rgba(255,255,255,0.05));
  --cc-primary-bg: #ffffff;
  --cc-primary-text: #09090b;
}

:root[data-theme="light"] .coaching-checkout {
  --cc-text: #0f172a;
  --cc-muted: #64748b;
  --cc-subtle: #7c8594;
  --cc-card-bg: #ffffff;
  --cc-card-border: rgba(15,23,42,0.08);
  --cc-block-bg: #f8fafc;
  --cc-block-border: rgba(15,23,42,0.08);
  --cc-step-bg: #ffffff;
  --cc-step-text: #0f172a;
  --cc-step-muted-bg: #ffffff;
  --cc-line: linear-gradient(90deg, rgba(15,23,42,0.18), rgba(15,23,42,0.05));
  --cc-primary-bg: #0f172a;
  --cc-primary-text: #ffffff;
}

/* Steps */
.steps { display:flex; align-items:center; justify-content:center; gap:10px; margin: 0 0 18px 0; }
.steps .step { width:44px; height:44px; border-radius:50%; display:flex; align-items:center; justify-content:center; color:var(--cc-step-text); background:var(--cc-step-bg); box-shadow: 0 6px 18px rgba(0,0,0,0.35); position:relative; }
.steps .step i { font-size:18px; }
.steps .step:not(.active) { opacity:.72; background: var(--cc-step-muted-bg); }
.steps .line { flex:0 0 54px; height:2px; background: var(--cc-line); border-radius:2px; }
.steps .step.active { outline: 2px solid rgba(255,255,255,0.85); box-shadow: 0 0 0 4px rgba(255,255,255,0.08), 0 12px 28px rgba(0,0,0,0.55); }
:root[data-theme="light"] .steps .step { box-shadow: 0 10px 24px rgba(15,23,42,0.12); }
:root[data-theme="light"] .steps .step.active { outline-color: rgba(15,23,42,0.10); box-shadow: 0 0 0 4px rgba(15,23,42,0.04), 0 16px 30px rgba(15,23,42,0.16); }

/* Cards */
.cc-card { background: var(--cc-card-bg); border: 1px solid var(--cc-card-border); border-radius: 14px; box-shadow: 0 16px 36px rgba(0,0,0,0.16); }
.cc-body { padding: 20px; color:var(--cc-text); }
.cc-title { margin: 0 0 10px 0; font-weight: 800; font-size: 18px; color:var(--cc-text); }
.muted { color: var(--cc-muted); }
:root[data-theme="light"] .cc-card { box-shadow: 0 18px 42px rgba(15,23,42,0.08); }

/* Grid */
.summary-grid { display:flex; gap:20px; align-items:flex-start; }
.summary-grid .col { flex:1 1 0; }
.summary-grid .side { width:340px; flex:0 0 340px; }

/* Info blocks */
.info-block { border-radius: 10px; padding: 14px; background: var(--cc-block-bg); border:1px solid var(--cc-block-border); }
.info-block .h { font-weight: 800; font-size: 14px; color:var(--cc-text); }
.info-block .v { margin-top: 6px; }
.price { margin-top:8px; font-size: 20px; font-weight: 900; }

/* Payment card */
.payment-card .cc-title { display:flex; align-items:center; gap:10px; }
.pay-icon { width:36px; height:36px; border-radius:10px; display:inline-flex; align-items:center; justify-content:center; color:var(--cc-step-text); background:var(--cc-step-bg); box-shadow:0 0 0 3px rgba(255,255,255,0.2), 0 12px 24px rgba(0,0,0,0.45); position:relative; }
.pay-icon::after { content:""; position:absolute; inset:-6px; border-radius:14px; background: radial-gradient(60% 60% at 50% 50%, rgba(255,255,255,0.25), rgba(255,255,255,0)); pointer-events:none; }
:root[data-theme="light"] .pay-icon { box-shadow:0 0 0 3px rgba(15,23,42,0.05), 0 12px 24px rgba(15,23,42,0.14); }
:root[data-theme="light"] .pay-icon::after { background: radial-gradient(60% 60% at 50% 50%, rgba(15,23,42,0.08), rgba(15,23,42,0)); }

.total-line { margin-top:8px; font-size:13px; color:var(--cc-subtle); }
.total-amount { font-weight:900; font-size:22px; margin-top:6px; }

.btn-primary.wide { width:100%; padding: 12px 16px; border-radius: 12px; font-weight:800; background: var(--cc-primary-bg); color: var(--cc-primary-text); border-color: transparent; }

/* Responsive */
@media (max-width: 860px) {
  .summary-grid { flex-direction: column; }
  .summary-grid .side { width:100%; flex:1 1 auto; }
}
</style>
@endpush

@section('content')
<div class="container coaching-checkout">
        <div class="steps">
            <div class="step"><i class="icon-info" aria-hidden="true"></i></div>
            <div class="line"></div>
            <div class="step active" title="Payment"><i class="icon-credit-card" aria-hidden="true"></i></div>
            <div class="line"></div>
            <div class="step"><i class="icon-check" aria-hidden="true"></i></div>
        </div>

        <div class="cc-card">
            <div class="cc-body">
                <div class="cc-title">Order Summary</div>
                <p class="muted">
                    @if($isCoachingMember ?? false)
                        You are detected as an active member (Beginner/Intermediate). Your coaching ticket uses the special member price.
                    @else
                        You do not currently have an active Beginner/Intermediate package. Your coaching ticket uses the regular non-member price.
                    @endif
                </p>

                <div class="summary-grid">
                    <div class="col">
                        <div class="info-block">
                            <div class="h">Package</div>
                            <div id="pkgName" class="v">{{ $package ? $package->name : 'Not configured' }}</div>
                            <div id="pkgPrice" class="price">Rp {{ number_format((int) ($displayPrice ?? 0),0,',','.') }}</div>
                            <div class="muted" style="margin-top:6px;font-size:12px;">
                                {{ ($isCoachingMember ?? false) ? 'Member Price' : 'Non-Member Price' }}
                            </div>
                        </div>

                        <div class="info-block" style="margin-top:12px;">
                            <div class="h">Schedule</div>
                            <div id="scheduleDisplay" class="v">{{ $scheduleDisplay ?? 'No date/time selected yet' }}</div>
                        </div>
                    </div>

                    <div class="side">
                        <div class="info-block payment-card">
                            <div class="cc-title"><span class="pay-icon" aria-hidden="true"><i class="icon-credit-card"></i></span> Payment</div>
                            <div class="total-line">Total</div>
                            <div id="totalAmount" class="total-amount">Rp {{ number_format((int) ($displayPrice ?? 0),0,',','.') }}</div>

                            <div style="margin-top:12px">
                                <button id="payBtn" class="btn btn-primary wide">Pay with Midtrans</button>
                            </div>

                            <div class="muted" style="margin-top:8px;font-size:12px;"> </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
        // create order on server
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
                payBtn.disabled = false; return;
            }

            // Request snap token from existing midtrans endpoint
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
                alert(resolveErrorMessage(snapJson, 'Midtrans create failed')); payBtn.disabled = false; payBtn.textContent = 'Pay with Midtrans'; return;
            }

            const token = snapJson.snap_token || snapJson.raw?.token;
            if (! token) { alert('Midtrans token not returned'); payBtn.disabled = false; payBtn.textContent = 'Pay with Midtrans'; return; }

            if (!window.snap || typeof window.snap.pay !== 'function') {
                alert('Midtrans popup gagal dimuat. Coba nonaktifkan ad-blocker/shield browser lalu refresh halaman.');
                payBtn.disabled = false;
                payBtn.textContent = 'Pay with Midtrans';
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
                    // Non-fatal: webhook may still grant later.
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
                    payBtn.textContent = 'Pay with Midtrans';
                },
                onClose: function(){
                    payBtn.disabled = false;
                    payBtn.textContent = 'Pay with Midtrans';
                }
            });

        } catch (e) {
            console.error(e);
            alert((e && e.message) ? e.message : 'Unexpected error');
            payBtn.disabled = false;
            payBtn.textContent = 'Pay with Midtrans';
        }
    });
});
</script>
@endpush
