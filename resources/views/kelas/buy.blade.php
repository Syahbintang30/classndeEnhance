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

    @php
        $paymentBase = isset($lesson) && $lesson ? route('kelas.payment', $lesson->id) : null;
    @endphp

    <div class="container checkout-main" style="padding:8px 60px 44px;color:#fff">
        <div class="packages-grid" style="display:flex;gap:20px;align-items:stretch;overflow-x:auto;-webkit-overflow-scrolling:touch;padding-top:12px;padding-bottom:8px;padding-left:4px;padding-right:4px;">
            @foreach($packages as $pkg)
                @php
                    $paymentLink = $paymentBase
                        ? ($paymentBase . '?package_id=' . $pkg->id . '&package_qty=1')
                        : '#';
                @endphp
                <a href="{{ $paymentLink }}" class="class-card" data-package-id="{{ $pkg->id }}" data-package-price="{{ $pkg->price }}" data-package-slug="{{ $pkg->slug }}" style="border:1px solid rgba(255,255,255,0.06);padding:0;border-radius:18px;background:#0b0b0b;transition:transform .18s ease, box-shadow .18s ease;cursor:pointer;overflow:hidden;text-decoration:none;color:inherit;">
                    @if($pkg->slug === 'intermediate')
                        <span class="pkg-badge">Paling Diminati</span>
                    @endif
                    <div class="class-card-media" style="overflow:hidden;border-bottom:1px solid rgba(255,255,255,0.08);">
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
                    <div class="class-card-body" style="padding:18px 18px 16px;">
                        <h3 style="margin:0 0 6px 0;font-size:26px;font-family: 'Cormorant Garamond', serif;">{{ $pkg->name }}</h3>
                        <div style="margin-top:0;font-weight:500;font-size:20px;color:#d4d4d4">Rp <span class="pkg-price">{{ number_format($pkg->price,0,',','.') }}</span></div>

                        <div class="class-card-copy" style="font-size:13px;opacity:0.9;border-top:1px solid rgba(255,255,255,0.08);margin-top:14px;padding-top:14px;">
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
                </a>
            @endforeach
        </div>

        @if(!$paymentBase)
            <div style="margin-top:14px;padding:12px 14px;border:1px solid rgba(255,255,255,0.12);background:rgba(255,255,255,0.04);border-radius:12px;color:#ffd9d9;font-size:13px;">
                Belum ada materi yang bisa dipilih untuk checkout saat ini.
            </div>
        @endif
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
        flex: 0 0 320px;
        max-width: 320px;
        min-width: 320px;
        overflow: hidden;
    }

    .class-card-body {
        display: flex;
        flex-direction: column;
        min-height: 250px;
    }

    .class-card-copy {
        flex: 1;
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
        filter: none;
        opacity: .92;
        transition: transform .45s ease, opacity .35s ease;
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
        opacity: 1;
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
        justify-content: center;
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

    .pkg-description {
        min-height: 38px;
    }

    @media (max-width: 1080px) {
        .packages-grid {
            justify-content: flex-start;
        }

        .checkout-main {
            padding-left: 22px !important;
            padding-right: 22px !important;
        }

        .class-card {
            min-width: 300px;
            max-width: 300px;
            flex-basis: 300px;
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

        .class-card {
            min-width: 86vw;
            max-width: 86vw;
            flex-basis: 86vw;
        }

        .class-card img {
            height: 190px !important;
        }
    }
</style>
@endpush
