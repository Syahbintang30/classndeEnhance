@extends('layouts.app')

@section('title', 'Register Class')

@section('content')
<div class="checkout-page">
    <nav class="checkout-nav" aria-label="Checkout logo navigation">
        <a href="{{ route('compro') }}" class="checkout-brand" aria-label="NDE Home">
            <img src="{{ asset('compro/img/ndelogo.png') }}" alt="NDE logo" class="checkout-brand-logo checkout-brand-dark" />
            <img src="{{ asset('compro/img/nde_logo_light.png') }}" alt="NDE logo" class="checkout-brand-logo checkout-brand-light" />
        </a>
    </nav>

    <section class="checkout-hero">
        <span class="checkout-pill">CHOOSE YOUR LEARNING PACKAGE</span>
        <h1>The Final Step to <span>Start Your Journey.</span></h1>
        <p>Choose the package that best fits your needs. Get lifetime access to the course materials and start learning with a structured path.</p>
    </section>

    @php
        $paymentBase = isset($lesson) && $lesson ? route('kelas.payment', $lesson->id) : null;
        $orderedPackages = $packages->sortBy(function ($pkg) {
            $slug = strtolower((string) ($pkg->slug ?? ''));
            $name = strtolower((string) ($pkg->name ?? ''));

            if (
                \Illuminate\Support\Str::contains($slug, 'intermediate') ||
                \Illuminate\Support\Str::contains($name, 'intermediate')
            ) {
                return 1;
            }

            if (
                \Illuminate\Support\Str::contains($slug, 'beginner') ||
                \Illuminate\Support\Str::contains($name, 'beginner')
            ) {
                return 2;
            }

            if (
                \Illuminate\Support\Str::contains($slug, 'ticket') ||
                \Illuminate\Support\Str::contains($slug, 'coaching') ||
                \Illuminate\Support\Str::contains($name, 'ticket')
            ) {
                return 0;
            }

            return 99;
        })->values();
    @endphp

    <div class="checkout-main">
        <div class="packages-grid">
            @foreach($orderedPackages as $i => $pkg)
                @php
                    $paymentLink = $paymentBase
                        ? ($paymentBase . '?package_id=' . $pkg->id . '&package_qty=1')
                        : '#';
                    $isFeatured = ($pkg->slug ?? null) === 'intermediate';
                    $benefits = array_filter(array_map('trim', explode("\n", $pkg->benefits ?? '')));
                    $price = number_format($pkg->price, 0, ',', '.');
                    $imgSrc = $pkg->image
                        ? asset('storage/' . $pkg->image)
                        : asset('pictures/' . $pkg->slug . '.jpg');
                @endphp

                @if($isFeatured)
                <a href="{{ $paymentLink }}" class="co-card co-card--featured" data-package-id="{{ $pkg->id }}">
                    <div class="co-card__best">Best Value</div>
                    <div class="co-card__img">
                        <img src="{{ $imgSrc }}" alt="{{ $pkg->name }}">
                    </div>
                    <div class="co-card__body">
                        <span class="co-card__kicker">Recommended</span>
                        <h3 class="co-card__name">{{ $pkg->name }}</h3>
                        <div class="co-card__price">Rp {{ $price }}</div>
                        <div class="co-card__benefits">
                            @foreach($benefits as $benefit)
                            <div class="co-card__benefit">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"></path></svg>
                                <span>{{ $benefit }}</span>
                            </div>
                            @endforeach
                        </div>
                        <div class="co-card__cta co-card__cta--dark">Get Access Now</div>
                    </div>
                </a>
                @else
                <a href="{{ $paymentLink }}" class="co-card co-card--regular" data-package-id="{{ $pkg->id }}">
                    <div class="co-card__img">
                        <img src="{{ $imgSrc }}" alt="{{ $pkg->name }}">
                    </div>
                    <div class="co-card__body">
                        <span class="co-card__kicker">{{ $pkg->description ?? '' }}</span>
                        <h3 class="co-card__name">{{ $pkg->name }}</h3>
                        <div class="co-card__price">Rp {{ $price }}</div>
                        <div class="co-card__benefits">
                            @foreach($benefits as $benefit)
                            <div class="co-card__benefit">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"></path></svg>
                                <span>{{ $benefit }}</span>
                            </div>
                            @endforeach
                        </div>
                        <div class="co-card__cta co-card__cta--light">Choose Package</div>
                    </div>
                </a>
                @endif
            @endforeach
        </div>

        @if(!$paymentBase)
            <div style="margin-top:14px;padding:12px 14px;border:1px solid rgba(255,255,255,0.12);background:rgba(255,255,255,0.04);border-radius:12px;color:#ffd9d9;font-size:13px;">
                No course material is currently available for checkout.
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    :root {
        --co-bg: #050505;
        --co-text: #ffffff;
        --co-muted: rgba(255,255,255,0.5);
        --co-border: rgba(255,255,255,0.1);
        --co-card-bg: #0a0a0a;
        --co-pill-text: rgba(255,255,255,0.62);
    }

    :root[data-theme="light"] {
        --co-bg: #f5f5f7;
        --co-text: #0f172a;
        --co-muted: #64748b;
        --co-border: rgba(15, 23, 42, 0.08);
        --co-card-bg: #ffffff;
        --co-pill-text: #64748b;
    }

    .checkout-brand-dark { display: block; }
    .checkout-brand-light { display: none; }
    :root[data-theme="light"] .checkout-brand-dark { display: none; }
    :root[data-theme="light"] .checkout-brand-light { display: block; }

    .global-nav { display: none !important; }

    .checkout-page {
        min-height: 100vh;
        background: var(--co-bg);
        color: var(--co-text);
        padding-bottom: 60px;
    }

    .checkout-nav {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 28px 16px 20px;
    }

    .checkout-brand { display: inline-flex; align-items: center; justify-content: center; }
    .checkout-brand-logo { height: 62px; width: auto; }

    .checkout-hero {
        max-width: 760px;
        margin: 0 auto;
        text-align: center;
        padding: 8px 16px 40px;
    }

    .checkout-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--co-border);
        border-radius: 999px;
        padding: 7px 12px;
        margin-bottom: 16px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .14em;
        color: var(--co-pill-text);
    }

    .checkout-hero h1 {
        margin: 0;
        font-size: clamp(32px, 5vw, 56px);
        line-height: 1.06;
        font-family: 'Playfair Display', serif;
        color: var(--co-text);
        letter-spacing: -0.02em;
    }

    .checkout-hero h1 span { color: var(--co-muted); font-style: italic; }

    .checkout-hero p {
        margin: 14px auto 0;
        max-width: 560px;
        font-size: 15px;
        line-height: 1.75;
        color: var(--co-muted);
    }

    .checkout-main {
        max-width: 1160px;
        margin: 0 auto;
        padding: 0 24px;
    }

    .packages-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        align-items: stretch;
    }

    .co-card {
        position: relative;
        display: flex;
        flex-direction: column;
        height: 100%;
        box-sizing: border-box;
        border-radius: 2.5rem;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        transition: transform .2s ease, box-shadow .2s ease, border-color .3s ease;
    }

    .co-card--regular {
        background: var(--co-card-bg);
        border: 1px solid var(--co-border);
        box-shadow: 0 8px 30px rgba(0,0,0,0.4);
    }

    .co-card--regular:hover {
        transform: translateY(-5px);
        border-color: rgba(255,255,255,0.3);
        box-shadow: 0 12px 40px rgba(0,0,0,0.55);
    }

    :root[data-theme="light"] .co-card--regular:hover {
        border-color: rgba(15,15,15,0.22);
        box-shadow: 0 12px 30px rgba(0,0,0,0.1);
    }

    .co-card--featured {
        background: #ffffff;
        border: 1px solid #ffffff;
        box-shadow: 0 0 50px rgba(255,255,255,0.1);
        z-index: 10;
        color: #111111;
    }

    .co-card--featured:hover {
        transform: translateY(-5px);
        box-shadow: 0 0 60px rgba(255,255,255,0.18);
    }

    .co-card__best {
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        background: #000000;
        color: #ffffff;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .15em;
        text-transform: uppercase;
        padding: 5px 14px;
        border-radius: 0 0 12px 12px;
        z-index: 10;
        white-space: nowrap;
    }

    .co-card__img {
        width: 100%;
        height: 210px;
        overflow: hidden;
    }

    .co-card__img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform .4s ease, opacity .3s ease;
        opacity: .92;
    }

    .co-card:hover .co-card__img img {
        transform: scale(1.04);
        opacity: 1;
    }

    .co-card__body {
        padding: 22px 22px 26px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .co-card__kicker {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .2em;
        color: var(--co-muted);
        margin-bottom: 6px;
        display: block;
        min-height: 14px;
    }

    .co-card--featured .co-card__kicker { color: rgba(15,15,15,0.5); }

    .co-card__name {
        margin: 0 0 6px;
        font-size: 26px;
        font-family: 'Playfair Display', serif;
        font-weight: 400;
        line-height: 1.1;
        color: var(--co-text);
    }

    .co-card--featured .co-card__name { color: #111111; }

    .co-card__price {
        font-size: 20px;
        font-weight: 700;
        margin: 8px 0 16px;
        color: var(--co-text);
    }

    .co-card--featured .co-card__price { color: #111111; }

    .co-card__benefits {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 20px;
        flex: 1;
    }

    .co-card__benefit {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        font-size: 13px;
        line-height: 1.45;
        color: rgba(255,255,255,0.75);
    }

    .co-card--featured .co-card__benefit { color: rgba(15,15,15,0.78); }
    :root[data-theme="light"] .co-card--regular .co-card__benefit { color: rgba(15,15,15,0.72); }

    .co-card__benefit svg {
        width: 15px;
        height: 15px;
        flex-shrink: 0;
        margin-top: 2px;
        color: #22c55e;
    }

    .co-card--featured .co-card__benefit svg { color: #111111; }

    .co-card__cta {
        display: block;
        width: 100%;
        padding: 12px;
        border-radius: 1.25rem;
        font-size: 13px;
        font-weight: 600;
        text-align: center;
        transition: all .2s ease;
    }

    .co-card__cta--light {
        background: rgba(255,255,255,0.08);
        color: #ffffff;
        border: 1px solid rgba(255,255,255,0.2);
    }

    .co-card--regular:hover .co-card__cta--light { background: rgba(255,255,255,0.14); }

    :root[data-theme="light"] .co-card__cta--light {
        background: rgba(15,15,15,0.05);
        color: #111111;
        border: 1px solid rgba(15,15,15,0.15);
    }

    .co-card__cta--dark {
        background: #111111;
        color: #ffffff;
        border: none;
    }

    .co-card--featured:hover .co-card__cta--dark { background: #000000; }

    @media (max-width: 1024px) {
        .packages-grid {
            grid-template-columns: repeat(3, minmax(260px, 1fr));
            overflow-x: auto;
            padding-bottom: 16px;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }
        .packages-grid::-webkit-scrollbar { display: none; }
    }

    @media (max-width: 768px) {
        .packages-grid { grid-template-columns: repeat(3, 82vw); }
        .co-card--featured { transform: none; }
        .co-card--featured:hover { transform: translateY(-4px); }
        .checkout-main { padding: 0 16px; }
        .co-card__img { height: 180px; }
    }
</style>
@endpush
