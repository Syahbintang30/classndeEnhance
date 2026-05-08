@extends('layouts.app')

@section('title', isset($package) && $package ? ($package->name . ' - One Step Away!') : 'Complete Your Payment')

@push('head')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&display=swap" rel="stylesheet">
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

<div class="payment-page">
    <style>
        :root {
            color-scheme: light dark;
            --checkout-bg: #050505;
            --checkout-surface: #ffffff;
            --checkout-surface-soft: #faf9f6;
            --checkout-surface-alt: #f3efe9;
            --checkout-text: #1b1b1b;
            --checkout-text-muted: #8a8a8a;
            --checkout-border: rgba(15, 15, 15, 0.10);
            --checkout-border-strong: rgba(15, 15, 15, 0.16);
            --checkout-pill: rgba(15, 15, 15, 0.06);
            --checkout-shadow: 0 18px 40px rgba(20, 12, 8, 0.08);
            --checkout-shadow-strong: 0 26px 60px rgba(20, 12, 8, 0.12);
            --checkout-accent: #111111;
            --checkout-accent-text: #ffffff;
            --checkout-payment-border: rgba(15, 15, 15, 0.10);
            --checkout-payment-hover: rgba(15, 15, 15, 0.04);
            --checkout-payment-active: rgba(15, 15, 15, 0.08);
            --checkout-check: #22c55e;
        }

        :root[data-theme="light"] {
            color-scheme: light;
            --checkout-bg: #f5f5f7;
            --checkout-surface: #ffffff;
            --checkout-surface-soft: #f8f9fb;
            --checkout-surface-alt: #f1f5f9;
            --checkout-text: #0f172a;
            --checkout-text-muted: #64748b;
            --checkout-border: rgba(15, 23, 42, 0.08);
            --checkout-border-strong: rgba(15, 23, 42, 0.12);
            --checkout-pill: rgba(15, 23, 42, 0.04);
            --checkout-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
            --checkout-shadow-strong: 0 26px 60px rgba(15, 23, 42, 0.12);
            --checkout-accent: #0f172a;
            --checkout-accent-text: #ffffff;
            --checkout-payment-border: rgba(15, 23, 42, 0.08);
            --checkout-payment-hover: rgba(15, 23, 42, 0.04);
            --checkout-payment-active: rgba(15, 23, 42, 0.08);
            --checkout-check: #22c55e;
        }

        :root[data-theme="dark"] {
            color-scheme: dark;
            --checkout-bg: #050505;
            --checkout-surface: #ffffff;
            --checkout-surface-soft: #f7f4ef;
            --checkout-surface-alt: #f0ebe4;
            --checkout-text: #1b1b1b;
            --checkout-text-muted: #7b7b7b;
            --checkout-border: rgba(15, 15, 15, 0.10);
            --checkout-border-strong: rgba(15, 15, 15, 0.14);
            --checkout-pill: rgba(15, 15, 15, 0.05);
            --checkout-shadow: 0 18px 40px rgba(0, 0, 0, 0.22);
            --checkout-shadow-strong: 0 26px 60px rgba(0, 0, 0, 0.26);
            --checkout-accent: #111111;
            --checkout-accent-text: #ffffff;
            --checkout-payment-border: rgba(15, 15, 15, 0.10);
            --checkout-payment-hover: rgba(15, 15, 15, 0.04);
            --checkout-payment-active: rgba(15, 15, 15, 0.08);
            --checkout-check: #22c55e;
        }

        html, body {
            overflow-x: hidden;
        }

        .global-nav {
            display: none !important;
        }

        .payment-page {
            min-height: 100vh;
            background: var(--checkout-bg);
            color: var(--checkout-text);
            font-family: 'Plus Jakarta Sans', sans-serif;
            padding-bottom: 32px;
        }

        :root[data-theme="dark"] .payment-page {
            background:
                radial-gradient(circle at top center, rgba(255, 255, 255, 0.02), transparent 34%),
                #050505;
        }

        .payment-shell {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px 40px;
        }

        .checkout-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 0 16px;
        }

        .checkout-header__spacer {
            width: 44px;
            height: 44px;
        }

        .checkout-header__title {
            margin: 0;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: .01em;
            color: var(--checkout-text);
        }

        .checkout-header__actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .checkout-header__icon {
            width: 38px;
            height: 38px;
            border-radius: 999px;
            border: 1px solid var(--checkout-border);
            background: var(--checkout-surface);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--checkout-text-muted);
            box-shadow: 0 2px 8px rgba(0,0,0,.04);
            text-decoration: none;
        }

        .checkout-header__icon svg {
            width: 18px;
            height: 18px;
        }

        .checkout-stepper {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            margin: 6px auto 20px;
            width: 100%;
            max-width: 420px;
        }

        .checkout-stepper__line {
            height: 1px;
            flex: 1;
            background: rgba(15,15,15,.10);
        }

        :root[data-theme="dark"] .checkout-header__title {
            color: rgba(255, 255, 255, 0.82);
        }

        :root[data-theme="dark"] .checkout-header__icon {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.14);
            color: rgba(255, 255, 255, 0.78);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.18);
        }

        :root[data-theme="dark"] .checkout-stepper__line {
            background: rgba(255, 255, 255, 0.10);
        }

        :root[data-theme="dark"] .checkout-stepper__item {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.14);
            color: rgba(255, 255, 255, 0.72);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.18);
        }

        :root[data-theme="dark"] .checkout-stepper__item svg {
            stroke: #ffffff;
            fill: none;
        }

        :root[data-theme="dark"] .checkout-stepper__item--active {
            color: #ffffff;
        }

        :root[data-theme="dark"] .checkout-stepper__item--active svg {
            stroke: #ffffff;
            fill: none;
            opacity: 1;
        }

        .checkout-stepper__item {
            width: 42px;
            height: 42px;
            border-radius: 999px;
            border: 1px solid rgba(15,15,15,.12);
            background: var(--checkout-surface);
            color: rgba(15,15,15,.45);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0,0,0,.04);
        }

        .checkout-stepper__item svg {
            width: 18px;
            height: 18px;
        }

        .checkout-stepper__item--active {
            width: 48px;
            height: 48px;
            background: #111111;
            color: #ffffff;
            border-color: #111111;
            box-shadow: 0 8px 18px rgba(0,0,0,.12);
        }

        .checkout-main-grid {
            max-width: 1040px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr;
            gap: 28px;
            align-items: start;
            padding-top: 10px;
        }

        .checkout-summary-card,
        .checkout-payment-card {
            background: var(--checkout-surface);
            border: 1px solid var(--checkout-border);
            box-shadow: var(--checkout-shadow);
        }

        .checkout-summary-card {
            border-radius: 22px;
            padding: 24px;
            position: relative;
            overflow: hidden;
        }

        .checkout-summary-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: #111111;
        }

        .checkout-summary-title {
            margin: 0 0 18px;
            font-size: 18px;
            font-weight: 700;
            color: var(--checkout-text);
        }

        .checkout-summary-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 18px;
        }

        .checkout-summary-item h3 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            color: var(--checkout-text);
        }

        .checkout-summary-item small {
            display: inline-block;
            margin-top: 4px;
            font-size: 13px;
            color: var(--checkout-text-muted);
        }

        .checkout-summary-price {
            font-size: 16px;
            font-weight: 700;
            color: var(--checkout-text);
            white-space: nowrap;
        }

        .checkout-note {
            margin-top: 8px;
            margin-bottom: 20px;
            padding: 14px 16px;
            background: var(--checkout-surface-soft);
            border: 1px solid var(--checkout-border);
            border-radius: 14px;
        }

        .checkout-note__label {
            margin: 0 0 4px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .15em;
            color: var(--checkout-text-muted);
        }

        .checkout-note p {
            margin: 0;
            font-size: 14px;
            line-height: 1.6;
            color: var(--checkout-text);
        }

        .checkout-breakdown {
            border-top: 1px dashed rgba(15,15,15,.16);
            padding-top: 18px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .checkout-breakdown__row,
        .checkout-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 18px;
            font-size: 14px;
        }

        .checkout-breakdown__row span:first-child {
            color: var(--checkout-text-muted);
            font-weight: 500;
        }

        .checkout-breakdown__row span:last-child {
            color: var(--checkout-text);
            font-weight: 600;
        }

        .checkout-total {
            margin-top: 4px;
            padding-top: 18px;
            border-top: 1px solid rgba(15,15,15,.08);
        }

        .checkout-total span:first-child {
            font-size: 16px;
            font-weight: 700;
            color: var(--checkout-text);
        }

        .checkout-total span:last-child {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -.03em;
            color: var(--checkout-text);
        }

        .checkout-payment-card {
            border-radius: 22px;
            padding: 24px;
        }

        .checkout-payment-title {
            margin: 0 0 18px;
            font-size: 24px;
            font-weight: 300;
            color: var(--checkout-text);
        }

        .checkout-midtrans-note {
            margin-bottom: 20px;
            padding: 16px 18px;
            border-radius: 14px;
            border: 1px solid var(--checkout-border);
            background: var(--checkout-surface-soft);
        }

        .checkout-midtrans-note p {
            margin: 0;
            font-size: 14px;
            line-height: 1.7;
            color: var(--checkout-text-muted);
        }

        .checkout-security {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 16px;
            background: var(--checkout-surface-soft);
            border: 1px solid var(--checkout-border);
            border-radius: 14px;
            margin-bottom: 20px;
        }

        .checkout-security svg {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
            margin-top: 2px;
            color: var(--checkout-text-muted);
        }

        .checkout-security p {
            margin: 0;
            font-size: 14px;
            line-height: 1.7;
            color: var(--checkout-text-muted);
        }

        .checkout-voucher {
            margin-bottom: 20px;
        }

        .checkout-voucher label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: var(--checkout-text-muted);
            font-weight: 500;
        }

        .checkout-voucher__row {
            display: flex;
            gap: 10px;
        }

        .checkout-voucher input {
            flex: 1;
            height: 48px;
            border-radius: 12px;
            border: 1px solid var(--checkout-border);
            background: var(--checkout-surface);
            color: var(--checkout-text);
            padding: 0 14px;
            font-size: 14px;
            outline: none;
        }

        :root[data-theme="light"] .checkout-voucher input {
            color: #1b1b1b;
            -webkit-text-fill-color: #1b1b1b;
        }

        :root[data-theme="dark"] .checkout-voucher input {
            color: #ffffff;
            -webkit-text-fill-color: #ffffff;
            background: rgba(255, 255, 255, 0.04);
        }

        .checkout-voucher input::placeholder {
            color: var(--checkout-text-muted);
        }

        :root[data-theme="light"] .checkout-voucher input::placeholder {
            color: #767676;
        }

        :root[data-theme="dark"] .checkout-voucher input::placeholder {
            color: rgba(255, 255, 255, 0.55);
        }

        .checkout-voucher input:-webkit-autofill,
        .checkout-voucher input:-webkit-autofill:hover,
        .checkout-voucher input:-webkit-autofill:focus,
        .checkout-voucher input:-webkit-autofill:active {
            -webkit-text-fill-color: var(--checkout-text);
            transition: background-color 9999s ease-in-out 0s;
            box-shadow: 0 0 0 1000px var(--checkout-surface) inset;
        }

        :root[data-theme="dark"] .checkout-voucher input:-webkit-autofill,
        :root[data-theme="dark"] .checkout-voucher input:-webkit-autofill:hover,
        :root[data-theme="dark"] .checkout-voucher input:-webkit-autofill:focus,
        :root[data-theme="dark"] .checkout-voucher input:-webkit-autofill:active {
            -webkit-text-fill-color: #ffffff;
            box-shadow: 0 0 0 1000px rgba(255, 255, 255, 0.04) inset;
        }

        .checkout-voucher button {
            min-width: 90px;
            height: 48px;
            border-radius: 12px;
            border: 1px solid var(--checkout-border);
            background: var(--checkout-surface);
            color: var(--checkout-text);
            font-weight: 600;
            cursor: pointer;
            transition: transform .18s ease, background .18s ease, border-color .18s ease;
        }

        .checkout-voucher button:hover {
            transform: translateY(-1px);
            background: var(--checkout-surface-soft);
        }

        .checkout-voucher button:disabled {
            opacity: .55;
            cursor: not-allowed;
            transform: none;
        }

        #voucher_feedback {
            margin-top: 8px;
            font-size: 13px;
            color: var(--checkout-text-muted);
            min-height: 18px;
        }

        .checkout-total-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            min-height: 54px;
            border: none;
            border-radius: 16px;
            background: #111111;
            color: #ffffff;
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
            transition: transform .18s ease, box-shadow .18s ease, opacity .18s ease;
            box-shadow: 0 12px 24px rgba(0,0,0,.12);
        }

        .checkout-total-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 16px 28px rgba(0,0,0,.16);
        }

        .checkout-back {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 18px;
            padding: 10px 14px;
            border-radius: 12px;
            border: 1px solid var(--checkout-border);
            background: var(--checkout-surface);
            color: var(--checkout-text);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: background .18s ease, transform .18s ease;
        }

        .checkout-back:hover {
            background: var(--checkout-surface-soft);
            transform: translateY(-1px);
        }

        .checkout-back--desktop {
            display: none;
        }

        .checkout-back--mobile {
            display: inline-flex;
            margin-top: 18px;
        }

        .checkout-hidden {
            display: none !important;
        }

        @media (min-width: 900px) {
            .payment-shell {
                padding: 0 24px 40px;
            }

            .checkout-header__title {
                font-size: 16px;
            }

            .checkout-main-grid {
                grid-template-columns: 5fr 7fr;
                gap: 36px;
                max-width: 1080px;
            }

            .checkout-summary-card {
                margin-top: 6px;
            }

            .checkout-back--desktop {
                display: inline-flex;
                margin-top: 22px;
            }

            .checkout-back--mobile {
                display: none;
            }
        }

        @media (max-width: 640px) {
            .payment-shell {
                padding: 0 16px 28px;
            }

            .checkout-header {
                padding-top: 16px;
            }

            .checkout-header__title {
                font-size: 14px;
            }

            .checkout-stepper {
                gap: 12px;
                margin-bottom: 18px;
            }

            .checkout-stepper__item {
                width: 40px;
                height: 40px;
            }

            .checkout-stepper__item--active {
                width: 46px;
                height: 46px;
            }

            .checkout-payment-title {
                font-size: 22px;
            }

            .checkout-summary-card,
            .checkout-payment-card {
                padding: 20px;
            }

            .checkout-total span:last-child {
                font-size: 22px;
            }
        }
    </style>

    <div class="payment-shell">
        <header class="checkout-header">
            <div class="checkout-header__spacer" aria-hidden="true"></div>
            <h1 class="checkout-header__title">Courses</h1>
            <div class="checkout-header__actions">
                <a href="{{ auth()->check() ? route('profile') : route('login') }}" class="checkout-header__icon" aria-label="User account">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M20 21a8 8 0 0 0-16 0"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </a>
            </div>
        </header>

        <div class="checkout-stepper" aria-label="Checkout progress">
            <div class="checkout-stepper__item" title="Info" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M12 16v-4"></path>
                    <path d="M12 8h.01"></path>
                </svg>
            </div>
            <div class="checkout-stepper__line" aria-hidden="true"></div>
            <div class="checkout-stepper__item checkout-stepper__item--active" title="Payment" aria-current="step">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                    <path d="M2 10h20"></path>
                </svg>
            </div>
            <div class="checkout-stepper__line" aria-hidden="true"></div>
            <div class="checkout-stepper__item" title="Done" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="m9 12 2 2 4-4"></path>
                </svg>
            </div>
        </div>

        <main class="checkout-main-grid">
            <section class="checkout-summary-card">
                <h2 class="checkout-summary-title">Your Order is Ready</h2>

                <div class="checkout-summary-item">
                    <div>
                        <h3>{{ $orderName }}</h3>
                        <small>{{ $orderPackageLabel }}@if($orderQuantity > 1) &middot; Qty: {{ $orderQuantity }}@endif</small>
                    </div>
                    <div class="checkout-summary-price">Rp {{ number_format($order['gross_amount'],0,',','.') }}</div>
                </div>

                <div class="checkout-note">
                    <span class="checkout-note__label">Note</span>
                    <p>{{ $orderNote }}</p>
                </div>

                <div class="checkout-breakdown">
                    <div class="checkout-breakdown__row">
                        <span>Subtotal (original price)</span>
                        <span>Rp {{ number_format($order['original_amount'] ?? $order['gross_amount'],0,',','.') }}</span>
                    </div>

                    @if(!empty($order['applied_referral_percent']) && $order['applied_referral_percent'] > 0)
                        <div class="checkout-breakdown__row" style="color:#4f8a5b;">
                            <span>Referral Discount ({{ $order['applied_referral_percent'] }}%)</span>
                            <span id="referral_discount_amount">- Rp {{ number_format( max(0, ($order['original_amount'] ?? $order['gross_amount']) - $order['gross_amount']),0,',','.') }}</span>
                        </div>
                        @if(!empty($order['referral_code']))
                            <div style="font-size:13px;color:var(--checkout-text-muted)">Referral code used: <strong id="referral_code_display">{{ $order['referral_code'] }}</strong></div>
                        @endif
                    @endif

                    <div id="voucher_discount_row" class="checkout-breakdown__row checkout-hidden" style="color:#4f8a5b;">
                        <span id="voucher_discount_label">Voucher Discount:</span>
                        <span id="voucher_discount_amount">- Rp 0</span>
                    </div>

                    <div class="checkout-breakdown__row">
                        <span>Tax</span>
                        <span>Rp 0</span>
                    </div>

                    <div class="checkout-total">
                        <span>Total Payment</span>
                        <span id="total_payment_amount">Rp {{ number_format($order['gross_amount'],0,',','.') }}</span>
                    </div>
                </div>

                <a href="{{ route('registerclass') }}" class="checkout-back checkout-back--desktop">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M19 12H5"></path>
                        <path d="m12 19-7-7 7-7"></path>
                    </svg>
                    Back to course selection
                </a>
            </section>

            <section class="checkout-payment-card">
                <h2 class="checkout-payment-title">Complete Your Payment</h2>

                <div id="payment-methods-list" data-total="{{ $order['gross_amount'] }}" data-order-id="{{ $order['order_id'] }}"></div>

                <div class="checkout-midtrans-note">
                    <p>Select your payment method directly in the Midtrans popup after clicking the pay button. Available channels such as bank transfer, e-wallets, and other supported options will appear there.</p>
                </div>

                <div class="checkout-security" id="payment-details-display">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="11" width="18" height="10" rx="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <p>
                        Rest assured, your transaction is 100% secure and processed by Midtrans. <strong>Payments are verified automatically</strong>, no need to upload proof of transfer.
                    </p>
                </div>

                <div class="checkout-voucher">
                    <label for="voucher_code_input">Have a voucher code?</label>
                    <div class="checkout-voucher__row">
                        <input id="voucher_code_input" type="text" class="form-control" placeholder="Enter code here" />
                        <button id="voucher_validate_btn" type="button">Apply</button>
                    </div>
                    <div id="voucher_feedback">&nbsp;</div>
                </div>

                <button id="pay-button" class="checkout-total-button">PAY &amp; Start Learning</button>

                <a href="{{ route('registerclass') }}" class="checkout-back checkout-back--mobile">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M19 12H5"></path>
                        <path d="m12 19-7-7 7-7"></path>
                    </svg>
                    Back
                </a>

                <form id="payment-complete-form" method="POST" action="{{ route('kelas.payment.complete', ['lesson' => $lesson->id]) }}" class="checkout-hidden">
                    @csrf
                    <input type="hidden" id="order_id_input" name="order_id" value="{{ $order['order_id'] }}" />
                    <input type="hidden" name="midtrans_result" id="midtrans_result" value="" />
                </form>
            </section>
        </main>
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
            payButton.textContent = 'Preparing payment...';
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
                payButton.textContent = 'PAY & Start Learning';
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
                    document.getElementById('voucher_feedback').style.color = '#4f8a5b';
                    try { updateTotalsAfterDiscounts(); } catch(e){ console.error(e); }
                } else {
                    appliedVoucher = null;
                    document.getElementById('voucher_feedback').innerText = json.message || 'Invalid voucher';
                    document.getElementById('voucher_feedback').style.color = '#b42318';
                    try { updateTotalsAfterDiscounts(); } catch(e){}
                }
            }).catch(e => { appliedVoucher = null; document.getElementById('voucher_feedback').innerText = 'Validation error'; document.getElementById('voucher_feedback').style.color = '#b42318'; });
    });
</script>
@endpush

@section('modals')
    <x-modal name="payment-method-modal" focusable>
        <div id="payment-modal-content" class="p-4">
        </div>
    </x-modal>
@endsection
