@extends('layouts.app')

@push('styles')
<style>
/* Page shell */
.coaching-checkout { max-width: 980px; margin: 28px auto; padding: 0 16px; }

/* Steps */
.steps { display:flex; align-items:center; justify-content:center; gap:10px; margin: 0 0 18px 0; }
.steps .step { width:44px; height:44px; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#111; background:#fff; box-shadow: 0 6px 18px rgba(0,0,0,0.35); position:relative; }
.steps .step i { font-size:18px; }
.steps .step:not(.active) { opacity:.55; background: rgba(255,255,255,0.9); }
.steps .line { flex:0 0 54px; height:2px; background: linear-gradient(90deg, rgba(255,255,255,0.25), rgba(255,255,255,0.05)); border-radius:2px; }
.steps .step.active { outline: 2px solid rgba(255,255,255,0.85); box-shadow: 0 0 0 4px rgba(255,255,255,0.08), 0 12px 28px rgba(0,0,0,0.55); }

/* Cards */
.cc-card { background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; }
.cc-body { padding: 20px; color:#fff; }
.cc-title { margin: 0 0 10px 0; font-weight: 800; font-size: 18px; color:#fff; }
.muted { color: rgba(255,255,255,0.75); }

/* Grid */
.summary-grid { display:flex; gap:20px; align-items:flex-start; }
.summary-grid .col { flex:1 1 0; }
.summary-grid .side { width:340px; flex:0 0 340px; }

/* Info blocks */
.info-block { border-radius: 10px; padding: 14px; background: rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.07); }
.info-block .h { font-weight: 800; font-size: 14px; color:#fff; }
.info-block .v { margin-top: 6px; }
.price { margin-top:8px; font-size: 20px; font-weight: 900; }

/* Payment card */
.payment-card .cc-title { display:flex; align-items:center; gap:10px; }
.pay-icon { width:36px; height:36px; border-radius:10px; display:inline-flex; align-items:center; justify-content:center; color:#111; background:#fff; box-shadow:0 0 0 3px rgba(255,255,255,0.2), 0 12px 24px rgba(0,0,0,0.45); position:relative; }
.pay-icon::after { content:""; position:absolute; inset:-6px; border-radius:14px; background: radial-gradient(60% 60% at 50% 50%, rgba(255,255,255,0.25), rgba(255,255,255,0)); pointer-events:none; }

.total-line { margin-top:8px; font-size:13px; color:rgba(255,255,255,0.8); }
.total-amount { font-weight:900; font-size:22px; margin-top:6px; }

.btn-primary.wide { width:100%; padding: 12px 16px; border-radius: 12px; font-weight:800; }

/* Responsive */
@media (max-width: 860px) {
  .summary-grid { flex-direction: column; }
  .summary-grid .side { width:100%; flex:1 1 auto; }
}
</style>
@endpush

@section('content')
<div class="container coaching-checkout">
    @if($hasPackage)
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
                <p class="muted">Pastikan Anda sudah membeli salah satu package yang diperlukan sebelum membeli ticket coaching.</p>

                <div class="summary-grid">
                    <div class="col">
                        <div class="info-block">
                            <div class="h">Package</div>
                            <div id="pkgName" class="v">{{ $package ? $package->name : 'Not configured' }}</div>
                            <div id="pkgPrice" class="price">Rp {{ $package ? number_format($package->price,0,',','.') : '0' }}</div>
                        </div>

                        <div class="info-block" style="margin-top:12px;">
                            <div class="h">Schedule</div>
                            <div id="scheduleDisplay" class="v">{{ $scheduleDisplay ?? 'Belum memilih tanggal/jam' }}</div>
                        </div>
                    </div>

                    <div class="side">
                        <div class="info-block payment-card">
                            <div class="cc-title"><span class="pay-icon" aria-hidden="true"><i class="icon-credit-card"></i></span> Payment</div>
                            <div class="total-line">Total</div>
                            <div id="totalAmount" class="total-amount">Rp {{ $package ? number_format($package->price,0,',','.') : '0' }}</div>

                            <div style="margin-top:12px">
                                @if(! $hasPackage)
                                    <div class="alert alert-warning">Anda belum memiliki package yang memenuhi syarat. Silakan beli package yang diperlukan terlebih dahulu.</div>
                                @endif
                                <button id="payBtn" class="btn btn-primary wide" {{ $hasPackage ? '' : 'disabled' }}>Pay with Midtrans</button>
                            </div>

                            <div class="muted" style="margin-top:8px;font-size:12px;">Harga dapat diubah oleh Admin di Admin → Packages.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div style="min-height:50vh;display:flex;align-items:center;justify-content:center;flex-direction:column;text-align:center;padding:48px 16px;">
            <h2 style="font-size:20px;font-weight:800;color:#fff;max-width:900px;">Pastikan Anda sudah membeli salah satu package yang diperlukan sebelum membeli ticket coaching.</h2>
            <a href="{{ url('/registerclass') }}" class="btn btn-primary" style="margin-top:24px;padding:12px 28px;border-radius:8px;font-weight:700;text-decoration:none;">Kembali ke Dashboard</a>
        </div>
    @endif
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
    payBtn && payBtn.addEventListener('click', async function(){
        payBtn.disabled = true;
        // create order on server
        const schedule = '{{ $scheduleDisplay ?? '' }}';
        const packageId = {{ $package ? $package->id : 'null' }};
        try {
            const res = await fetch('{{ route('coaching.checkout.create') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ schedule: schedule, package_id: packageId })
            });
            const json = await res.json();
            if (! res.ok) {
                alert(json.error || 'Failed to create order');
                payBtn.disabled = false; return;
            }

            // Request snap token from existing midtrans endpoint
            const snapRes = await fetch('/api/midtrans/create', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ order_id: json.order_id, gross_amount: json.gross_amount, package_id: json.package_id })
            });
            const snapJson = await snapRes.json();
            if (! snapRes.ok) {
                alert(snapJson.error || 'Midtrans create failed'); payBtn.disabled = false; return;
            }

            const token = snapJson.snap_token || snapJson.raw?.token;
            if (! token) { alert('Midtrans token not returned'); payBtn.disabled = false; return; }

            window.snap.pay(token, {
                onSuccess: function(result){ window.location.href = '/coaching'; },
                onPending: function(result){ window.location.href = '/coaching'; },
                onError: function(err){ alert('Payment failed'); payBtn.disabled = false; }
            });

        } catch (e) {
            console.error(e); alert('Unexpected error'); payBtn.disabled = false;
        }
    });
});
</script>
@endpush
