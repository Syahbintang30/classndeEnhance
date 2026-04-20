@extends('layouts.app')

@section('title', 'Register Class')

@section('content')
<div class="checkout-page">
    <nav class="checkout-nav" aria-label="Checkout logo navigation">
        <a href="{{ route('compro') }}" class="checkout-brand" aria-label="NDE Home">
            <img src="{{ asset('compro/img/ndelogo.png') }}" alt="NDE logo" class="checkout-brand-logo" />
        </a>
    </nav>

    <section class="checkout-hero">
        <span class="checkout-pill">PILIH PAKET BELAJAR</span>
        <h1>Langkah Terakhir untuk <span>Memulai Perjalanan.</span></h1>
        <p>Pilih paket yang paling sesuai dengan kebutuhan kamu. Akses materi seumur hidup dan mulai belajar dengan alur terstruktur.</p>
    </section>

    <div class="container checkout-main" style="padding:8px 60px 44px;color:#fff">
        <div style="display:flex;gap:34px;align-items:flex-start;overflow:visible;">
            <div style="flex:1;max-width:720px;overflow:visible;">
                <div class="packages-grid" style="display:flex;gap:20px;align-items:stretch;overflow-x:auto;-webkit-overflow-scrolling:touch;padding-top:12px;padding-bottom:8px;padding-left:4px;padding-right:4px;">
                    @foreach($packages as $pkg)
                        <div class="class-card" data-package-id="{{ $pkg->id }}" data-package-price="{{ $pkg->price }}" data-package-slug="{{ $pkg->slug }}" style="flex:1;border:1px solid rgba(255,255,255,0.06);padding:0;border-radius:18px;background:#0b0b0b;transition:transform .18s ease, box-shadow .18s ease;cursor:pointer;overflow:hidden;">
                            @if($pkg->slug === 'intermediate')
                                <span class="pkg-badge">Paling Diminati</span>
                            @endif
                            <div style="overflow:hidden;border-bottom:1px solid rgba(255,255,255,0.08);">
                                @php
                                    if (!empty($pkg->image)) {
                                        $imgSrc = asset('storage/' . $pkg->image);
                                    } else {
                                        $img = 'intermediate';
                                        if ($pkg->slug == 'beginner') $img = 'beginner';
                                        if ($pkg->slug == config('coaching.coaching_package_slug', 'coaching-ticket')) $img = 'coaching-ticket';
                                        $imgSrc = asset('pictures/' . $img . '.jpg');
                                    }
                                @endphp
                                <img src="{{ $imgSrc }}" alt="{{ $pkg->name }}" style="width:100%;height:220px;object-fit:cover;display:block;">
                            </div>
                            <div style="padding:18px 18px 16px;">
                                <h3 style="margin:0 0 6px 0;font-size:26px;font-family: 'Cormorant Garamond', serif;">{{ $pkg->name }}</h3>
                                <div style="margin-top:0;font-weight:500;font-size:20px;color:#d4d4d4">Rp <span class="pkg-price">{{ number_format($pkg->price,0,',','.') }}</span></div>

                                <div style="font-size:13px;opacity:0.9;border-top:1px solid rgba(255,255,255,0.08);margin-top:14px;padding-top:14px;">
                                    @if(!empty($pkg->description))
                                        <p class="pkg-description" style="margin:0 0 10px 0;color:#cfcfcf;font-size:13px;">{{ \Illuminate\Support\Str::limit($pkg->description, 160) }}</p>
                                    @endif

                                    @if(!empty($pkg->benefits))
                                        @php
                                            $lines = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $pkg->benefits)));
                                        @endphp
                                        @if(count($lines))
                                            <ul class="pkg-benefits-list">
                                                @foreach($lines as $line)
                                                    <li>{{ $line }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    @else
                                        @if($pkg->slug == 'beginner')
                                            <ul class="pkg-benefits-list">
                                                <li>Pahami anatomi dan fungsi gitar.</li>
                                                <li>Belajar tuning gitar dengan benar.</li>
                                                <li>Kuasai chord dasar (C, G, D, Am, Em).</li>
                                                <li>Latih pola strumming dasar.</li>
                                            </ul>
                                        @elseif($pkg->slug == config('coaching.coaching_package_slug', 'coaching-ticket'))
                                            <ul class="pkg-benefits-list">
                                                <li>Satu coaching ticket untuk sesi live coaching.</li>
                                                <li>Prioritas booking coaching slot.</li>
                                                <li>Feedback personal dari coach.</li>
                                            </ul>
                                        @else
                                            <ul class="pkg-benefits-list">
                                                <li>Kuasai barre chords dan variasinya.</li>
                                                <li>Dasar teknik fingerstyle.</li>
                                                <li>Penggunaan skala untuk improvisasi.</li>
                                                <li>Pemahaman ritme dan sinkopasi.</li>
                                                <li>Interpretasi lagu lebih personal.</li>
                                            </ul>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div style="width:460px;" class="checkout-summary-panel">
                <div id="package_validation" style="display:none;color:#ffb3b3;font-size:13px;margin-bottom:10px;"></div>
                <div id="pay_error" style="display:none;color:#ffb3b3;font-size:13px;margin-bottom:10px;"></div>
                <input type="hidden" name="package_qty" value="1" id="selected_package_qty_input" />

                @guest
                    <h2 style="font-size:30px;margin-bottom:6px;font-family:'Cormorant Garamond', serif;">Ringkasan Pesanan</h2>
                    <p style="opacity:0.68;margin-bottom:16px">Pilih paket, isi kode promo jika ada, lalu lanjut register/login.</p>

                    <input type="hidden" name="selected_package" value="" id="selected_package_input" />
                    <input type="hidden" name="selected_package_price" value="" id="selected_package_price_input" />
                    <input type="hidden" name="referral" id="hidden_referral_input" value="{{ old('referral') ?? session('referral') ?? '' }}" />

                    <div style="margin-bottom:14px">
                        <label style="display:block;margin-bottom:8px;font-size:11px;letter-spacing:.15em;text-transform:uppercase;color:rgba(255,255,255,0.55)">Kode Promo (Opsional)</label>
                        <input id="referral_code_input" name="referral" value="{{ old('referral') ?? session('referral') ?? '' }}" placeholder="Masukkan kode..." style="width:100%;padding:13px 14px;background:#101010;border:1px solid #2d2d2d;color:#fff !important;border-radius:12px;" />
                        @error('referral') <div style="color:#ffb3b3;margin-top:6px;font-size:13px">{{ $message }}</div> @enderror
                        <div id="referral_hint" style="margin-top:7px;color:rgba(255,255,255,0.56);font-size:12px">Kalau punya referral code, masukkan untuk diskon.</div>
                    </div>

                    <div id="selected_package_preview" style="margin-bottom:16px;padding:15px;border-radius:14px;background:#0e0e0e;border:1px solid rgba(255,255,255,0.08);display:flex;justify-content:space-between;align-items:flex-start;gap:14px;">
                        <div>
                            <div style="font-size:11px;opacity:0.7;letter-spacing:.12em;text-transform:uppercase">Paket</div>
                            <div id="selected_package_name" style="font-weight:700;margin-top:4px;font-size:21px;font-family:'Cormorant Garamond', serif;">-</div>
                            <div id="selected_package_price_display" style="font-size:17px;opacity:0.9;margin-top:4px">Rp -</div>
                            <div id="selected_package_original_price_display" style="font-size:12px;opacity:0.6;margin-top:6px;display:none">Original price: Rp -</div>
                            <div id="selected_package_discount_display" style="font-size:12px;color:#b8f0c6;margin-top:6px;display:none">Referral discount: -</div>
                        </div>
                        <div style="font-size:12px;color:rgba(255,255,255,0.6);max-width:128px;text-align:right;">Klik kartu paket lain untuk mengganti pilihan.</div>
                    </div>

                    <div style="display:flex;gap:10px;justify-content:flex-end;flex-wrap:wrap;">
                        <a href="{{ route('login', ['redirect_to' => route('registerclass')]) }}" class="checkout-ghost-btn">Login</a>
                        <a id="guest_register_btn" href="{{ route('register', ['redirect_to' => route('registerclass')]) }}" class="checkout-main-btn">Register</a>
                    </div>
                @else
                    <h2 style="font-size:30px;margin-bottom:6px;font-family:'Cormorant Garamond', serif;">Ringkasan Pesanan</h2>
                    <p style="opacity:0.68;margin-bottom:14px">Kamu sudah login. Tinggal cek paket dan lanjut pembayaran.</p>

                    @php $paymentBase = isset($lesson) && $lesson ? route('kelas.payment', $lesson->id) : null; @endphp
                    @if(!$paymentBase)
                        <div style="padding:10px 14px;border-radius:8px;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);font-size:13px;color:#ffd9d9;margin-bottom:12px;">No material available for payment. Please check back later.</div>
                    @endif

                    @php $uid = auth()->user()->package_id ?? null; @endphp
                    @if(empty($uid))
                        <input type="hidden" name="referral" id="hidden_referral_input" value="{{ old('referral') ?? session('referral') ?? '' }}" />
                        <div style="margin-top:4px;margin-bottom:12px">
                            <label style="display:block;margin-bottom:8px;font-size:11px;letter-spacing:.15em;text-transform:uppercase;color:rgba(255,255,255,0.55)">Kode Promo (Opsional)</label>
                            <input id="referral_code_input" name="referral" value="{{ old('referral') ?? session('referral') ?? '' }}" placeholder="Masukkan kode..." style="width:100%;padding:13px 14px;background:#101010;border:1px solid #2d2d2d;color:#fff !important;border-radius:12px;" />
                            @error('referral') <div style="color:#ffb3b3;margin-top:6px;font-size:13px">{{ $message }}</div> @enderror
                            <div id="referral_hint" style="margin-top:7px;color:rgba(255,255,255,0.56);font-size:12px">Kalau punya referral code, masukkan untuk diskon.</div>
                        </div>
                    @endif

                    <div id="selected_package_preview_logged" style="margin-top:8px;padding:15px;border-radius:14px;background:#0e0e0e;border:1px solid rgba(255,255,255,0.08);display:flex;justify-content:space-between;align-items:flex-start;gap:14px;">
                        <div>
                            <div style="font-size:11px;opacity:0.7;letter-spacing:.12em;text-transform:uppercase">Paket</div>
                            <div id="selected_package_name_logged" style="font-weight:700;margin-top:4px;font-size:21px;font-family:'Cormorant Garamond', serif;">-</div>
                            <div id="selected_package_price_display_logged" style="font-size:17px;opacity:0.9;margin-top:4px">Rp -</div>
                            <div id="selected_package_qty_container_logged" style="margin-top:10px;display:none;align-items:center;gap:8px;font-size:13px">
                                <label style="opacity:0.8;margin-right:8px">Quantity</label>
                                <button type="button" id="qty_decrease_logged" style="background:transparent;border:1px solid rgba(255,255,255,0.06);color:#fff;padding:6px 8px;border-radius:6px">-</button>
                                <span id="selected_package_qty_display_logged" style="min-width:28px;display:inline-block;text-align:center">1</span>
                                <button type="button" id="qty_increase_logged" style="background:transparent;border:1px solid rgba(255,255,255,0.06);color:#fff;padding:6px 8px;border-radius:6px">+</button>
                            </div>
                        </div>
                        <div style="font-size:12px;color:rgba(255,255,255,0.6);max-width:128px;text-align:right;">Klik kartu paket lain untuk mengganti pilihan.</div>
                    </div>

                    <input type="hidden" id="selected_package_input" value="" />
                    <input type="hidden" id="selected_package_price_input" value="" />

                    <div style="text-align:right;margin-top:14px">
                        @if($paymentBase)
                            <a id="continue_payment_btn" href="{{ $paymentBase }}" class="checkout-main-btn">Lanjutkan Pembayaran</a>
                        @endif
                    </div>
                @endguest
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .global-nav {
        display: none !important;
    }

    .checkout-page {
        min-height: 100vh;
        background: radial-gradient(55% 35% at 50% 0%, rgba(196, 196, 196, 0.12), rgba(5, 5, 5, 0)), #050505;
        color: #d4d4d4;
        padding-bottom: 28px;
    }

    .checkout-nav {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 28px 16px 20px;
    }

    .checkout-brand {
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .checkout-brand-logo {
        height: 62px;
        width: auto;
        display: block;
    }

    .checkout-hero {
        max-width: 760px;
        margin: 0 auto;
        text-align: center;
        padding: 8px 16px 26px;
    }

    .checkout-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(255, 255, 255, 0.13);
        border-radius: 999px;
        padding: 7px 12px;
        margin-bottom: 16px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .14em;
        color: rgba(255, 255, 255, 0.62);
    }

    .checkout-hero h1 {
        margin: 0;
        font-size: clamp(34px, 6vw, 64px);
        line-height: 1.06;
        font-family: 'Cormorant Garamond', serif;
        color: #fff;
        letter-spacing: -0.02em;
    }

    .checkout-hero h1 span {
        color: rgba(255, 255, 255, 0.6);
        font-style: italic;
    }

    .checkout-hero p {
        margin: 14px auto 0;
        max-width: 620px;
        font-size: 15px;
        line-height: 1.75;
        color: rgba(255, 255, 255, 0.5);
    }

    .checkout-main {
        max-width: 1260px;
        margin: 0 auto;
    }

    .checkout-summary-panel {
        border: 1px solid rgba(255, 255, 255, 0.09);
        background: #0a0a0a;
        border-radius: 22px;
        padding: 24px;
        position: sticky;
        top: 24px;
    }

    .checkout-main-btn,
    .checkout-ghost-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        border-radius: 999px;
        padding: 12px 22px;
        font-size: 13px;
        letter-spacing: .04em;
        text-transform: uppercase;
        font-weight: 700;
        transition: all .2s ease;
    }

    .checkout-main-btn {
        border: 1px solid #fff;
        background: #fff;
        color: #000;
    }

    .checkout-main-btn:hover {
        transform: translateY(-2px);
        color: #000;
        box-shadow: 0 8px 20px rgba(255, 255, 255, 0.18);
    }

    .checkout-ghost-btn {
        border: 1px solid rgba(255, 255, 255, 0.22);
        background: transparent;
        color: #fff;
    }

    .checkout-ghost-btn:hover {
        border-color: rgba(255, 255, 255, 0.5);
        color: #fff;
    }

    .class-card {
        position: relative;
        display: flex;
        flex-direction: column;
        box-sizing: border-box;
        flex: 0 0 325px;
        max-width: 325px;
        min-width: 280px;
        overflow: hidden;
    }

    .pkg-badge {
        position: absolute;
        z-index: 2;
        right: 14px;
        top: 14px;
        background: #fff;
        color: #000;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .1em;
        padding: 7px 10px;
    }

    .class-card img {
        filter: grayscale(1);
        opacity: .62;
        transition: transform .45s ease, filter .35s ease, opacity .35s ease;
    }

    .class-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.55);
    }

    .class-card.selected {
        border-color: rgba(255, 255, 255, 0.4) !important;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.7), 0 0 24px rgba(255, 255, 255, 0.1);
        transform: translateY(-8px);
        z-index: 7;
    }

    .class-card.selected img {
        filter: grayscale(0);
        opacity: .9;
        transform: scale(1.04);
    }

    .class-card.glow::after {
        content: '';
        position: absolute;
        left: -8px;
        right: -8px;
        top: -8px;
        bottom: -8px;
        border-radius: 20px;
        pointer-events: none;
        box-shadow: 0 0 34px rgba(255, 255, 255, 0.13);
    }

    .packages-grid {
        align-items: stretch;
        overflow-x: auto;
        overflow-y: visible;
        position: relative;
        z-index: 1;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .packages-grid::-webkit-scrollbar {
        display: none;
    }

    .pkg-benefits-list {
        margin: 8px 0 0 18px;
        padding: 0;
    }

    .pkg-benefits-list li {
        margin-bottom: 6px;
        word-break: break-word;
        white-space: normal;
        overflow-wrap: break-word;
        color: rgba(255, 255, 255, 0.72);
    }

    input#referral_code_input {
        color: #fff !important;
    }

    input#referral_code_input::placeholder {
        color: rgba(255, 255, 255, 0.42);
    }

    @media (max-width: 1080px) {
        .checkout-main {
            padding-left: 22px !important;
            padding-right: 22px !important;
        }

        .checkout-main > div {
            flex-direction: column;
        }

        .checkout-summary-panel {
            width: 100% !important;
            position: static;
        }

        .class-card {
            min-width: 300px;
        }
    }

    @media (max-width: 768px) {
        .checkout-brand-logo {
            height: 52px;
        }

        .checkout-hero {
            padding-bottom: 18px;
        }

        .checkout-main {
            padding-left: 14px !important;
            padding-right: 14px !important;
        }

        .checkout-summary-panel {
            padding: 18px;
            border-radius: 16px;
        }

        .class-card {
            min-width: 86vw;
            max-width: 86vw;
        }

        .class-card img {
            height: 190px !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const toggle = document.getElementById('toggle-password');
    const pwd = document.getElementById('register-password');
    const toggleConfirm = document.getElementById('toggle-password-confirm');
    const pwdConfirm = document.getElementById('register-password-confirm');
    function wireToggle(btn, input){
        if(!btn || !input) return;
        
        // SVG icons
        var eye = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="18" height="18"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
        var eyeOff = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="18" height="18"><path d="M17.94 17.94A10.94 10.94 0 0 1 12 19c-7 0-11-7-11-7a21.7 21.7 0 0 1 5-5"></path><path d="M1 1l22 22"></path></svg>';
        
        btn.addEventListener('click', function(){
            if(input.type === 'password'){
                input.type = 'text';
                btn.setAttribute('aria-label','Hide password');
                btn.title = 'Hide password';
                btn.innerHTML = eyeOff;
            } else {
                input.type = 'password';
                btn.setAttribute('aria-label','Show password');
                btn.title = 'Show password';
                btn.innerHTML = eye;
            }
        });
    }
    wireToggle(toggle, pwd);
    wireToggle(toggleConfirm, pwdConfirm);
});
</script>
@endpush

@push('scripts')
        <script>
    (function(){
    // lazy-query DOM elements after DOMContentLoaded to avoid null accesses
    let cards = [];
    let pkgHidden = null;
    let pkgPriceHidden = null;
    let pkgQtyHidden = null;
    let pkgIdInput = null;
    let pkgPriceInput = null;
    let previewName = null;
    let previewPrice = null;
    let previewBox = null;
    let validationBox = null;
    let qtyContainer = null;
    let qtyDisplay = null;
    let qtyInc = null;
    let qtyDec = null;
    // logged-in preview controls
    let qtyContainerLogged = null;
    let qtyDisplayLogged = null;
    let qtyIncLogged = null;
    let qtyDecLogged = null;
    let previewNameLogged = null;
    let previewPriceLogged = null;

    // coaching package slug from server-side config
    const coachingSlug = '{{ config('coaching.coaching_package_slug', 'coaching-ticket') }}';

        function applySelection(card){
            cards.forEach(x => { x.classList.remove('selected'); x.classList.remove('glow'); });
            card.classList.add('selected'); card.classList.add('glow');
            const pid = card.dataset.packageId || '';
            const pprice = card.dataset.packagePrice || '';
            if(pkgHidden) pkgHidden.value = pid;
            if(pkgPriceHidden) pkgPriceHidden.value = pprice;
            if(pkgQtyHidden) pkgQtyHidden.value = 1;
            if(pkgIdInput) pkgIdInput.value = pid;
            if(pkgPriceInput) pkgPriceInput.value = pprice;
            // update preview box
            if(previewName) previewName.textContent = card.querySelector('h3')?.textContent || '-';
            if(previewPrice) previewPrice.textContent = 'Rp ' + (card.querySelector('.pkg-price')?.textContent || '-');
            if(validationBox) { validationBox.style.display = 'none'; validationBox.textContent = ''; }
            // update continue to payment link for logged-in users
            const continueBtn = document.getElementById('continue_payment_btn');
            if (continueBtn) {
                const base = @json(isset($lesson) && $lesson ? route('kelas.payment', $lesson->id) : null);
                const pid = card.dataset.packageId || '';
                const qty = pkgQtyHidden ? parseInt(pkgQtyHidden.value || '1', 10) : 1;
                // append package_id, qty and referral as query params so server can pick them up for logged-in flow
                const ref = document.getElementById('referral_code_input') ? document.getElementById('referral_code_input').value.trim() : (document.getElementById('hidden_referral_input') ? document.getElementById('hidden_referral_input').value.trim() : '');
                if (base) {
                    const params = pid ? ('?package_id=' + encodeURIComponent(pid) + '&package_qty=' + encodeURIComponent(qty)) : '';
                    continueBtn.href = base + params + (ref ? (params ? '&referral=' + encodeURIComponent(ref) : '?referral=' + encodeURIComponent(ref)) : '');
                }
            }
            // update guest register/login deep links with selection and redirect target
            const regBtn = document.getElementById('guest_register_btn');
            const logBtn = document.getElementById('guest_login_btn');
            if (regBtn || logBtn) {
                const pid = card.dataset.packageId || '';
                const ref = document.getElementById('referral_code_input') ? document.getElementById('referral_code_input').value.trim() : '';
                const basePay = @json(isset($lesson) && $lesson ? route('kelas.payment', $lesson->id) : null);
                const payUrl = basePay ? basePay + (pid ? ('?package_id=' + encodeURIComponent(pid)) : '') : null;
                if (regBtn) {
                    let q = '';
                    if (pid || ref) {
                        q = '?';
                        if (pid) q += 'package_id=' + encodeURIComponent(pid);
                        if (ref) q += (pid ? '&' : '') + 'referral=' + encodeURIComponent(ref);
                    }
                    // add redirect_to param so after register we can continue to payment page
                    if (payUrl) regBtn.href = '{{ route('register') }}' + q + (q ? '&' : '?') + 'redirect_to=' + encodeURIComponent(payUrl);
                    else regBtn.href = '{{ route('register') }}' + q;
                }
                if (logBtn) {
                    if (payUrl) logBtn.href = '{{ route('login') }}' + '?redirect_to=' + encodeURIComponent(payUrl);
                    else logBtn.href = '{{ route('login') }}';
                }
            }
            // update logged-in preview display
            if (previewNameLogged) previewNameLogged.textContent = card.querySelector('h3')?.textContent || '-';
            if (previewPriceLogged) previewPriceLogged.textContent = 'Rp ' + ((parseInt(card.dataset.packagePrice||'0',10) * (pkgQtyHidden?parseInt(pkgQtyHidden.value||'1',10):1)).toLocaleString('id-ID'));
            if (card.dataset.packageSlug === coachingSlug) {
                if (qtyContainerLogged) qtyContainerLogged.style.display = 'flex';
                if (qtyDisplayLogged) qtyDisplayLogged.textContent = pkgQtyHidden ? String(parseInt(pkgQtyHidden.value||'1',10)) : '1';
            } else {
                if (qtyContainerLogged) qtyContainerLogged.style.display = 'none';
                if (qtyDisplayLogged) qtyDisplayLogged.textContent = '1';
            }
            // refresh referral discount preview when package changes (no page reload)
            try {
                const refInputEl = document.getElementById('referral_code_input') || document.getElementById('hidden_referral_input');
                const code = refInputEl ? (refInputEl.value||'').trim() : '';
                if (code) {
                    if (window.lastReferralCode && window.lastReferralCode === code && typeof window.updatePricesWithDiscount === 'function') {
                        window.updatePricesWithDiscount(window.lastReferralDiscountPercent || 0);
                    } else if (typeof window.validateReferralCode === 'function') {
                        // re-validate in background and update preview
                        window.validateReferralCode(code);
                    }
                } else if (typeof window.updatePricesWithDiscount === 'function') {
                    window.updatePricesWithDiscount(0);
                }
            } catch (e) { console.error('refresh referral preview', e); }
        }

        // initialize after DOM ready
        document.addEventListener('DOMContentLoaded', function(){
            cards = Array.from(document.querySelectorAll('.class-card'));
            pkgHidden = document.getElementById('selected_package_input');
            pkgPriceHidden = document.getElementById('selected_package_price_input');
            pkgQtyHidden = document.getElementById('selected_package_qty_input');
            pkgIdInput = document.getElementById('package_id_input');
            pkgPriceInput = document.getElementById('package_price_input');
            previewName = document.getElementById('selected_package_name');
            previewPrice = document.getElementById('selected_package_price_display');
            previewBox = document.getElementById('selected_package_preview');
            validationBox = document.getElementById('package_validation');
            qtyContainer = document.getElementById('selected_package_qty_container');
            qtyDisplay = document.getElementById('selected_package_qty_display');
            qtyInc = document.getElementById('qty_increase');
            qtyDec = document.getElementById('qty_decrease');
            qtyContainerLogged = document.getElementById('selected_package_qty_container_logged');
            qtyDisplayLogged = document.getElementById('selected_package_qty_display_logged');
            qtyIncLogged = document.getElementById('qty_increase_logged');
            qtyDecLogged = document.getElementById('qty_decrease_logged');
            previewNameLogged = document.getElementById('selected_package_name_logged');
            previewPriceLogged = document.getElementById('selected_package_price_display_logged');

            // pre-select by package passed in query or session pre_register; fallback to first card
            const preselectedPackageId = '{{ request()->input("package_id") ?? session("pre_register.package_id") ?? "" }}';
            let prePicked = null;
            if (preselectedPackageId) {
                prePicked = cards.find(c => (c.dataset.packageId || '') === String(preselectedPackageId));
            }
            if (prePicked) {
                applySelection(prePicked);
            } else if (cards.length) {
                applySelection(cards[0]);
            }

            cards.forEach(c => {
                c.addEventListener('click', () => applySelection(c));
                c.addEventListener('mouseenter', () => { c.style.zIndex = 5; });
                c.addEventListener('mouseleave', () => { c.style.zIndex = ''; });
            });

            // attach qty handlers now that elements exist
            if (qtyInc) {
                qtyInc.addEventListener('click', function(){
                    if (!pkgQtyHidden) return;
                    let q = parseInt(pkgQtyHidden.value || '1',10) || 1; q = q + 1; pkgQtyHidden.value = q; if (qtyDisplay) qtyDisplay.textContent = q;
                    const continueBtn = document.getElementById('continue_payment_btn');
                    if (continueBtn) {
                        const url = new URL(continueBtn.href, window.location.origin);
                        url.searchParams.set('package_qty', String(q));
                        continueBtn.href = url.toString();
                    }
                    if (qtyDisplayLogged) qtyDisplayLogged.textContent = String(q);
                    const sel = document.querySelector('.class-card.selected');
                    if (sel && previewPriceLogged) {
                        const unit = parseInt(sel.dataset.packagePrice || '0', 10) || 0;
                        previewPriceLogged.textContent = 'Rp ' + (unit * q).toLocaleString('id-ID');
                    }
                });
            }
            if (qtyDec) {
                qtyDec.addEventListener('click', function(){
                    if (!pkgQtyHidden) return;
                    let q = parseInt(pkgQtyHidden.value || '1',10) || 1; q = Math.max(1, q - 1); pkgQtyHidden.value = q; if (qtyDisplay) qtyDisplay.textContent = q;
                    const continueBtn = document.getElementById('continue_payment_btn');
                    if (continueBtn) {
                        const url = new URL(continueBtn.href, window.location.origin);
                        url.searchParams.set('package_qty', String(q));
                        continueBtn.href = url.toString();
                    }
                    if (qtyDisplayLogged) qtyDisplayLogged.textContent = String(q);
                    const sel = document.querySelector('.class-card.selected');
                    if (sel && previewPriceLogged) {
                        const unit = parseInt(sel.dataset.packagePrice || '0', 10) || 0;
                        previewPriceLogged.textContent = 'Rp ' + (unit * q).toLocaleString('id-ID');
                    }
                });
            }
            if (qtyIncLogged) {
                qtyIncLogged.addEventListener('click', function(){
                    if (!pkgQtyHidden) return;
                    let q = parseInt(pkgQtyHidden.value || '1',10) || 1; q = q + 1; pkgQtyHidden.value = q; if (qtyDisplay) qtyDisplay.textContent = q; if (qtyDisplayLogged) qtyDisplayLogged.textContent = String(q);
                    const continueBtn = document.getElementById('continue_payment_btn');
                    if (continueBtn) { const url = new URL(continueBtn.href, window.location.origin); url.searchParams.set('package_qty', String(q)); continueBtn.href = url.toString(); }
                    const sel = document.querySelector('.class-card.selected'); if (sel && previewPriceLogged) { const unit = parseInt(sel.dataset.packagePrice || '0', 10) || 0; previewPriceLogged.textContent = 'Rp ' + (unit * q).toLocaleString('id-ID'); if (previewPrice) previewPrice.textContent = 'Rp ' + (unit * q).toLocaleString('id-ID'); }
                });
            }
            if (qtyDecLogged) {
                qtyDecLogged.addEventListener('click', function(){
                    if (!pkgQtyHidden) return;
                    let q = parseInt(pkgQtyHidden.value || '1',10) || 1; q = Math.max(1, q - 1); pkgQtyHidden.value = q; if (qtyDisplay) qtyDisplay.textContent = q; if (qtyDisplayLogged) qtyDisplayLogged.textContent = String(q);
                    const continueBtn = document.getElementById('continue_payment_btn');
                    if (continueBtn) { const url = new URL(continueBtn.href, window.location.origin); url.searchParams.set('package_qty', String(q)); continueBtn.href = url.toString(); }
                    const sel = document.querySelector('.class-card.selected'); if (sel && previewPriceLogged) { const unit = parseInt(sel.dataset.packagePrice || '0', 10) || 0; previewPriceLogged.textContent = 'Rp ' + (unit * q).toLocaleString('id-ID'); if (previewPrice) previewPrice.textContent = 'Rp ' + (unit * q).toLocaleString('id-ID'); }
                });
            }
        });

        // Quantity controls: only visible when coaching-ticket slug is selected
        function updateQtyControls(card){
            if (!card) return;
            const slug = card.dataset?.packageSlug || '';
            if (slug === coachingSlug) {
                if (qtyContainer) qtyContainer.style.display = 'flex';
            } else {
                if (qtyContainer) qtyContainer.style.display = 'none';
                if (pkgQtyHidden) pkgQtyHidden.value = 1;
                if (qtyDisplay) qtyDisplay.textContent = '1';
            }
        }

    // Quantity handlers are attached inside DOMContentLoaded initialization so elements exist

        // ensure qty controls updated when selection changes
        (function observeSelection(){
            const grid = document.querySelector('.packages-grid');
            if (!grid) return;
            const observer = new MutationObserver(() => {
                const sel = document.querySelector('.class-card.selected');
                if (sel) updateQtyControls(sel);
            });
            observer.observe(grid, { attributes:true, childList:true, subtree:true });
            // initial
            const initialSel = document.querySelector('.class-card.selected'); if(initialSel) updateQtyControls(initialSel);
        })();

    // form validation: ensure a package is selected before submit
    // avoid referencing $lesson (may be undefined on this page). Select forms by the hidden inputs we added.
    const guestForm = document.getElementById('selected_package_input')?.closest('form') || document.querySelector('form[action="{{ route('registerclass.register') }}"]');
    const purchaseForm = document.getElementById('package_id_input')?.closest('form') || null;
        function requirePackageOnSubmit(e){
            const hasPkg = (pkgHidden && pkgHidden.value) || (pkgIdInput && pkgIdInput.value);
            if(!hasPkg){
                e.preventDefault();
                if(validationBox){ validationBox.style.display = 'block'; validationBox.textContent = 'Please select a package before continuing.'; }
                // scroll preview into view on small screens
                previewBox?.scrollIntoView({behavior:'smooth', block:'center'});
                return false;
            }
            return true;
        }
        if(guestForm) guestForm.addEventListener('submit', requirePackageOnSubmit);
        if(purchaseForm) purchaseForm.addEventListener('submit', requirePackageOnSubmit);
        // client-side guard: ensure password and confirmation match before submit
        const registerForm = document.querySelector('form[action="{{ route('registerclass.register') }}"]');
        if (registerForm) {
            registerForm.addEventListener('submit', function(e){
                const nameInput = document.getElementById('fullname_input');
                const nameError = document.getElementById('name_error');
                const pwd = document.getElementById('register-password');
                const pwdc = document.getElementById('register-password-confirm');
                const confirmError = document.getElementById('confirm_password_error');

                // validate name: required + max 255
                if (nameInput) {
                    const v = String(nameInput.value || '').trim();
                    if (!v) {
                        e.preventDefault();
                        if (nameError) { nameError.style.display = 'block'; nameError.textContent = 'Name is required.'; }
                        nameInput.focus();
                        return false;
                    }
                    if (v.length > 255) {
                        e.preventDefault();
                        if (nameError) { nameError.style.display = 'block'; nameError.textContent = 'Name is too long (max 255 characters).'; }
                        nameInput.focus();
                        return false;
                    }
                }
                if (nameError) { nameError.style.display = 'none'; nameError.textContent = ''; }

                // password confirmation
                if (pwd && pwdc && pwd.value !== pwdc.value) {
                    e.preventDefault();
                    if (confirmError) { confirmError.style.display = 'block'; confirmError.textContent = 'Password confirmation does not match.'; }
                    pwd.focus();
                    return false;
                }
                if (confirmError) { confirmError.style.display = 'none'; confirmError.textContent = ''; }
                return true;
            });
        }
    })();
</script>
<script>
    (function(){
        // Midtrans integration + referral quick-validation
        const payBtn = document.getElementById('pay_button');
        const spinner = document.getElementById('pay_spinner');
        const errorBox = document.getElementById('pay_error');
        const selectedMethodText = document.getElementById('selected_method_text');
        let selectedMethod = null;

        const methodEls = Array.from(document.querySelectorAll('.payment-method') || []);
        methodEls.forEach(el => {
            el.addEventListener('click', () => {
                methodEls.forEach(x => x.style.outline='');
                el.style.outline = '2px solid rgba(255,255,255,0.12)';
                selectedMethod = el.dataset.method;
                if (selectedMethodText) selectedMethodText.textContent = el.textContent.trim();
            });
        });
        if(methodEls.length && !selectedMethod){ methodEls[0].click(); }

        // referral validation and client-side discounted price preview
        (function(){
            const input = document.getElementById('referral_code_input');
            const hint = document.getElementById('referral_hint');
            const discountPercent = {{ config('referral.discount_percent', 2) }};
            function updatePricesWithDiscount(pct){
                const sel = document.querySelector('.class-card.selected');
                if(!sel) return;
                const unit = parseInt(sel.dataset.packagePrice||'0',10) || 0;
                const qty = parseInt(document.getElementById('selected_package_qty_input')?.value || '1',10) || 1;
                const raw = unit * qty;
                const discounted = Math.round(raw * (100 - pct)/100);
                const disp = document.getElementById('selected_package_price_display');
                const orig = document.getElementById('selected_package_original_price_display');
                const disc = document.getElementById('selected_package_discount_display');
                if(disp) disp.textContent = 'Rp ' + discounted.toLocaleString('id-ID');
                if(orig) { orig.style.display = 'block'; orig.textContent = 'Original price: Rp ' + raw.toLocaleString('id-ID'); }
                if(pct && pct > 0){
                    const amount = raw - discounted;
                    if(disc){ disc.style.display = 'block'; disc.textContent = 'Referral discount ('+pct+'%): - Rp ' + amount.toLocaleString('id-ID'); }
                } else {
                    if(disc){ disc.style.display = 'none'; disc.textContent = ''; }
                }
                // store last applied percent so other code can reuse without revalidating
                try { window.lastReferralDiscountPercent = pct; } catch(e){}
            }

            async function validate(code){
                if(!code) { if(hint) hint.textContent = 'If you have a referral code, enter it to get a discount.'; updatePricesWithDiscount(0); return; }
                try{
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const res = await fetch('/referral/validate', { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN': token}, body: JSON.stringify({ code }) });
                    const body = await res.json();
                    if(body && body.valid){
                        const pct = body.discount_percent || discountPercent;
                        if(hint) hint.textContent = 'Code valid. Referral discount: ' + pct + '%';
                        // persist last validated code and percent
                        try { window.lastReferralCode = code; window.lastReferralDiscountPercent = pct; } catch(e){}
                        updatePricesWithDiscount(pct);
                    } else {
                        // clear last validated state when invalid
                        try { window.lastReferralCode = null; window.lastReferralDiscountPercent = 0; } catch(e){}
                        if(hint) hint.textContent = 'Referral code is not valid.';
                        updatePricesWithDiscount(0);
                    }
                } catch (err) {
                    console.error('referral validate error', err);
                    if(hint) hint.textContent = 'An error occurred while validating referral code';
                    updatePricesWithDiscount(0);
                }
            }

            // expose helpers globally so selection changes can refresh the price preview
            try { window.updatePricesWithDiscount = updatePricesWithDiscount; window.validateReferralCode = validate; } catch(e){}

            if(input){
                let timeout = null;
                input.addEventListener('input', function(e){
                    clearTimeout(timeout);
                    const code = e.target.value.trim();
                    timeout = setTimeout(()=> validate(code), 600);
                });
                // validate initial value
                if(input.value) validate(input.value.trim());
            }
        })();

        function loadSnapJs() {
            const isProd = {{ config('services.midtrans.is_production') ? 'true' : 'false' }};
            const clientKey = '{{ config('services.midtrans.client_key') }}';
            const src = isProd ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js';
            return new Promise((res, rej) => {
                if (window.snap) return res();
                const s = document.createElement('script');
                s.src = src;
                s.setAttribute('data-client-key', clientKey);
                s.onload = () => res();
                s.onerror = () => rej(new Error('Failed to load Midtrans snap.js'));
                document.head.appendChild(s);
            });
        }

        async function createSnapToken(payload) {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const res = await fetch('/api/midtrans/create', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
                body: JSON.stringify(payload)
            });
            const body = await res.json().catch(() => null);
            return { ok: res.ok, status: res.status, body };
        }

        async function onPayClick(e){
            e.preventDefault();
            if (errorBox) errorBox.style.display = 'none';
            if(!selectedMethod){ if(errorBox){ errorBox.textContent = 'Please choose a payment method.'; errorBox.style.display = 'block'; } return; }
            const pkgId = document.getElementById('package_id_input')?.value || document.getElementById('selected_package_input')?.value;
            const gross = document.getElementById('package_price_input')?.value || document.getElementById('selected_package_price')?.value;
            if(!pkgId || !gross){ if(errorBox){ errorBox.textContent = 'Please select a package.'; errorBox.style.display = 'block'; } return; }

            if(payBtn) payBtn.disabled = true; if(spinner) spinner.style.display = 'inline-block';
            try {
                const payload = {
                    gross_amount: parseInt(gross,10),
                    payment_method: selectedMethod,
                    package_id: pkgId,
                    package_qty: parseInt(document.getElementById('selected_package_qty_input')?.value || '1', 10) || 1,
                    package_unit_price: parseInt(document.querySelector('.class-card.selected')?.dataset.packagePrice || '0', 10) || 0,
                    referral: document.getElementById('referral_code_input') ? document.getElementById('referral_code_input').value : null
                };

                const tokenResp = await createSnapToken(payload);
                if(!tokenResp.ok){
                    const message = (tokenResp.body && (tokenResp.body.message || tokenResp.body.error)) || 'Server returned an error';
                    if(errorBox){ errorBox.textContent = 'Failed to create snap token: ' + message; errorBox.style.display = 'block'; }
                    return;
                }

                await loadSnapJs();
                const snapToken = tokenResp.body && tokenResp.body.snap_token ? tokenResp.body.snap_token : null;
                const serverOrderId = tokenResp.body && tokenResp.body.order_id ? tokenResp.body.order_id : null;
                if(!snapToken){ if(errorBox){ errorBox.textContent = 'No snap token returned from server.'; errorBox.style.display = 'block'; } return; }

                window.snap.pay(snapToken, {
                    onSuccess: function(result){
                        const q = serverOrderId ? ('?order_id=' + encodeURIComponent(serverOrderId)) : '';
                        window.location = '{{ route('payments.thankyou') }}' + q;
                    },
                    onPending: function(result){
                        const q = serverOrderId ? ('?order_id=' + encodeURIComponent(serverOrderId)) : '';
                        window.location = '{{ route('payments.thankyou') }}' + q;
                    },
                    onError: function(err){
                        const msg = (err && err.message) || 'Payment failed';
                        if(errorBox){ errorBox.textContent = 'Payment failed: ' + msg; errorBox.style.display = 'block'; }
                    }
                });

            } catch (err) {
                const msg = (err && err.message) || String(err);
                if(errorBox){ errorBox.textContent = 'Unexpected error: ' + msg; errorBox.style.display = 'block'; }
            } finally {
                if(payBtn) payBtn.disabled = false; if(spinner) spinner.style.display = 'none';
            }
        }

        if (payBtn) payBtn.addEventListener('click', onPayClick);
    })();
</script>
@endpush
