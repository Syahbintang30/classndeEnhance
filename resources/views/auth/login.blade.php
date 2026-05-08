@extends('layouts.app')

@section('title', 'Login')

@push('head')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;1,400;1,500&display=swap" rel="stylesheet">
@endpush

@section('content')
<div class="auth-page-v2">
    <header class="auth-header-v2">
        <a href="{{ route('compro') }}" class="brand-logo-v2" aria-label="ClassNDE home">
            <img src="{{ asset('compro/img/ndelogo.png') }}" alt="NDE Logo" class="brand-logo-dark">
            <img src="{{ asset('compro/img/nde_logo_light.png') }}" alt="NDE Logo" class="brand-logo-light">
        </a>
        <nav class="auth-nav-v2">
            <a href="{{ route('registerclass') }}">Courses</a>
            <a href="{{ route('login') }}">Sign in</a>
            <a href="{{ route('register') }}" class="btn-nav-v2">Register</a>
        </nav>
    </header>

    <main class="auth-main-v2">
        <section class="auth-card-v2" aria-label="Login form">
            <div class="title-group-v2">
                <div class="badge-v2">CLASSNDE PORTAL</div>
                <h1 class="title-v2">Welcome <em>Back</em>.</h1>
            </div>

            @if(session('status'))
                <div class="alert-v2 success">{{ session('status') }}</div>
            @endif

            @if(session('error') || $errors->any())
                <div class="alert-v2 error">
                    @if(session('error'))
                        <div>{{ session('error') }}</div>
                    @endif
                    @if($errors->any())
                        <ul>
                            @foreach($errors->all() as $err)
                                @if(str_contains(strtolower($err), 'these credentials do not match'))
                                    @continue
                                @endif
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="auth-form-v2">
                @csrf

                <a href="{{ route('auth.google.redirect') }}" class="btn-v2 btn-outline-v2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 48 48" aria-hidden="true">
                        <path fill="#FFC107" d="M43.6 20.5H42V20H24v8h11.3C33.7 32.7 29.3 36 24 36c-6.6 0-12-5.4-12-12s5.4-12 12-12c3 0 5.7 1.1 7.8 2.9l5.7-5.7C34 6.1 29.3 4 24 4 12.9 4 4 12.9 4 24s8.9 20 20 20 20-8.9 20-20c0-1.3-.1-2.4-.4-3.5z"/>
                        <path fill="#FF3D00" d="M6.3 14.7l6.6 4.8C14.7 15 18.9 12 24 12c3 0 5.7 1.1 7.8 2.9l5.7-5.7C34 6.1 29.3 4 24 4c-7.7 0-14.4 4.4-17.7 10.7z"/>
                        <path fill="#4CAF50" d="M24 44c5.1 0 9.8-2 13.3-5.2l-6.1-5.2C29.2 35.1 26.7 36 24 36c-5.3 0-9.7-3.3-11.3-8l-6.6 5.1C9.3 39.5 16.1 44 24 44z"/>
                        <path fill="#1976D2" d="M43.6 20.5H42V20H24v8h11.3c-.8 2.5-2.4 4.6-4.4 6.1l.1-.1 6.1 5.2C36.7 39.5 44 34 44 24c0-1.3-.1-2.4-.4-3.5z"/>
                    </svg>
                    Sign In with Google
                </a>

                <div class="divider-v2">
                    <span>Or continue with email</span>
                </div>

                <div class="input-group-v2">
                    <label for="login-email">Email</label>
                    <div class="input-wrapper-v2">
                        <input id="login-email" name="email" type="email" value="{{ old('email') }}" required autofocus class="input-field-v2 @error('email') input-error @enderror" placeholder="Enter your email">
                    </div>
                </div>

                <div class="input-group-v2">
                    <label for="login-password">Password</label>
                    <div class="input-wrapper-v2">
                        <input id="login-password" name="password" type="password" required class="input-field-v2 @error('password') input-error @enderror" placeholder="Enter your password">
                        <button type="button" class="icon-btn-v2" data-target="login-password" aria-label="Toggle password visibility">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>

                <div class="meta-row-v2">
                    <label class="remember-v2">
                        <input type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-v2">Forgot password?</a>
                    @endif
                </div>

                <button type="submit" class="btn-v2 btn-primary-v2">Sign In</button>
            </form>

            <div class="auth-footer-v2">
                Don’t have an account yet? <a href="{{ route('register') }}">Sign up here</a>
            </div>
        </section>
    </main>
</div>

<style>
    :root {
        --bg-base: #030303;
        --text-main: #ffffff;
        --text-muted: #a1a1aa;
        --border-color: rgba(255, 255, 255, 0.15);
        --accent-white: #ffffff;
        --accent-black: #000000;
        --card-bg: rgba(10, 10, 10, 0.4);
        --card-border: rgba(255, 255, 255, 0.08);
        --badge-bg: rgba(255, 255, 255, 0.05);
        --badge-border: rgba(255, 255, 255, 0.15);
        --badge-text: #d4d4d8;
        --field-bg: rgba(0, 0, 0, 0.4);
        --field-bg-focus: rgba(0, 0, 0, 0.6);
        --field-placeholder: #71717a;
        --link-hover: #ffffff;
    }

    :root[data-theme="light"] {
        --bg-base: #f6f3ee;
        --text-main: #1b1b1b;
        --text-muted: #6d6d6d;
        --border-color: rgba(15, 15, 15, 0.14);
        --accent-white: #111111;
        --accent-black: #ffffff;
        --card-bg: rgba(255, 255, 255, 0.75);
        --card-border: rgba(15, 15, 15, 0.08);
        --badge-bg: rgba(15, 15, 15, 0.06);
        --badge-border: rgba(15, 15, 15, 0.12);
        --badge-text: #2b2b2b;
        --field-bg: rgba(255, 255, 255, 0.8);
        --field-bg-focus: rgba(255, 255, 255, 0.95);
        --field-placeholder: #8a8a8a;
        --link-hover: #111111;
    }

    .auth-page-v2 {
        background-color: var(--bg-base);
        background-image:
            radial-gradient(circle at top left, rgba(3, 3, 3, 0.72) 0%, rgba(3, 3, 3, 0.42) 18%, rgba(3, 3, 3, 0.12) 48%, rgba(3, 3, 3, 0) 72%),
            linear-gradient(to left, rgba(3, 3, 3, 0.18) 0%, rgba(3, 3, 3, 0.36) 38%, rgba(3, 3, 3, 0.55) 100%),
            url('{{ asset('compro/img/ndehero.webp') }}');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        color: var(--text-main);
        font-family: 'Inter', sans-serif;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        -webkit-font-smoothing: antialiased;
    }

    .auth-header-v2 {
        padding: 24px 6%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        position: relative;
        z-index: 10;
    }

    .brand-logo-v2 img {
        height: 52px;
        width: auto;
        display: block;
    }

    .brand-logo-v2 {
        display: inline-flex;
        align-items: center;
        line-height: 0;
        flex-shrink: 0;
        text-decoration: none;
    }

    .brand-logo-dark { display: block; }
    .brand-logo-light { display: none; }
    :root[data-theme="light"] .brand-logo-dark { display: none; }
    :root[data-theme="light"] .brand-logo-light { display: block; }

    .auth-nav-v2 {
        display: flex;
        gap: 24px;
        align-items: center;
    }

    .auth-nav-v2 a {
        color: rgba(255, 255, 255, 0.92);
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
        transition: color 0.2s;
    }

    :root[data-theme="light"] .auth-nav-v2 a:not(.btn-nav-v2) {
        color: rgba(17, 17, 17, 0.84);
    }

    :root[data-theme="light"] .auth-nav-v2 a:not(.btn-nav-v2):hover {
        color: #111111;
    }

    .auth-nav-v2 a:hover:not(.btn-nav-v2) {
        color: var(--link-hover);
    }

    .btn-nav-v2 {
        background: var(--accent-white);
        color: var(--accent-black) !important;
        padding: 8px 16px;
        border-radius: 999px;
        font-weight: 500;
        font-size: 0.85rem;
    }

    .auth-main-v2 {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        padding: 20px 8%;
        position: relative;
        z-index: 10;
    }

    .auth-card-v2 {
        width: 100%;
        max-width: 440px;
        display: flex;
        flex-direction: column;
        align-items: center;
        background: var(--card-bg);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--card-border);
        border-radius: 24px;
        padding: 48px 40px;
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4);
    }

    .title-group-v2 { text-align: center; margin-bottom: 32px; width: 100%; }

    .badge-v2 {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 16px;
        border: 1px solid var(--badge-border);
        border-radius: 999px;
        font-size: 0.65rem;
        font-weight: 600;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: var(--badge-text);
        margin-bottom: 24px;
        background: var(--badge-bg);
    }

    .badge-v2::before {
        content: '';
        display: block;
        width: 6px;
        height: 6px;
        background: var(--badge-text);
        border-radius: 50%;
    }

    .title-v2 {
        font-family: 'Playfair Display', serif;
        font-size: 3.2rem;
        font-weight: 400;
        line-height: 1.1;
        letter-spacing: -0.5px;
        color: var(--text-main);
        margin: 0;
    }

    .title-v2 em { font-style: italic; font-weight: 500; color: var(--text-muted); }

    .alert-v2 {
        width: 100%;
        border-radius: 12px;
        padding: 10px 12px;
        font-size: 0.9rem;
        margin-bottom: 16px;
        border: 1px solid transparent;
    }

    .alert-v2 ul { margin: 8px 0 0; padding-left: 18px; }
    .alert-v2.success { background: rgba(16, 185, 129, 0.2); border-color: rgba(16, 185, 129, 0.45); color: #d1fae5; }
    .alert-v2.error { background: rgba(239, 68, 68, 0.2); border-color: rgba(248, 113, 113, 0.45); color: #fecaca; }
        :root[data-theme="light"] .alert-v2.success { background: rgba(16, 185, 129, 0.12); border-color: rgba(16, 185, 129, 0.28); color: #0f5132; }
        :root[data-theme="light"] .alert-v2.error { background: rgba(239, 68, 68, 0.12); border-color: rgba(239, 68, 68, 0.32); color: #7f1d1d; }

    .auth-form-v2 { width: 100%; display: flex; flex-direction: column; gap: 20px; }

    .input-group-v2 { display: flex; flex-direction: column; gap: 8px; }
    .input-group-v2 label { font-size: 0.85rem; color: var(--text-muted); font-weight: 500; }

    .input-wrapper-v2 { position: relative; display: flex; align-items: center; }

    .input-field-v2 {
        width: 100%;
        background: var(--field-bg);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 14px 16px;
        color: var(--text-main);
        font-size: 0.95rem;
        font-family: inherit;
        transition: all 0.2s ease;
    }

    .input-field-v2::placeholder { color: var(--field-placeholder); }
    .input-field-v2:focus { outline: none; border-color: rgba(255, 255, 255, 0.4); background: var(--field-bg-focus); }
    .input-field-v2.input-error { border-color: rgba(248, 113, 113, 0.8); }

    .icon-btn-v2 { position: absolute; right: 16px; background: none; border: none; color: var(--field-placeholder); cursor: pointer; padding: 0; display: flex; }
    .icon-btn-v2:hover { color: var(--text-main); }

    .meta-row-v2 {
        margin-top: -6px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        font-size: 0.84rem;
        color: var(--text-muted);
    }

    .remember-v2 { display: inline-flex; align-items: center; gap: 8px; cursor: pointer; }
    .remember-v2 input { accent-color: var(--text-main); }
    .forgot-v2 { color: var(--text-main); text-decoration: none; }
    .forgot-v2:hover { text-decoration: underline; }

    .btn-v2 {
        width: 100%;
        padding: 14px 16px;
        border-radius: 999px;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        transition: all 0.3s ease;
        font-family: inherit;
        border: none;
        text-decoration: none;
    }

    .btn-primary-v2 { background: var(--accent-white); color: var(--accent-black); margin-top: -4px; }
    .btn-primary-v2:hover { background: #e5e5e5; transform: translateY(-1px); }
    .btn-outline-v2 { background: transparent; color: var(--text-main); border: 1px solid var(--border-color); }
    .btn-outline-v2:hover { background: rgba(255, 255, 255, 0.08); }

    .divider-v2 { display: flex; align-items: center; text-align: center; color: var(--field-placeholder); font-size: 0.8rem; margin: 2px 0; }
    .divider-v2::before, .divider-v2::after { content: ''; flex: 1; border-bottom: 1px solid var(--border-color); }
    .divider-v2 span { padding: 0 16px; }

    .auth-footer-v2 { margin-top: 30px; text-align: center; font-size: 0.9rem; color: var(--text-muted); }
    .auth-footer-v2 a { color: var(--text-main); text-decoration: none; font-weight: 500; border-bottom: 1px solid transparent; padding-bottom: 2px; transition: border-color 0.2s; }
    .auth-footer-v2 a:hover { border-color: var(--text-main); }

    :root[data-theme="light"] .auth-page-v2 {
        background-image:
            linear-gradient(to right, rgba(246, 243, 238, 0.1) 0%, rgba(246, 243, 238, 0.35) 60%, rgba(246, 243, 238, 0.55) 100%),
            url('{{ asset('compro/img/ndehero.webp') }}');
    }

    :root[data-theme="light"] .auth-nav-v2 a { color: var(--text-muted); }
    :root[data-theme="light"] .auth-nav-v2 a:hover:not(.btn-nav-v2) { color: var(--text-main); }
    :root[data-theme="light"] .title-v2 { color: var(--text-main); }
    :root[data-theme="light"] .title-v2 em { color: var(--text-muted); }
    :root[data-theme="light"] .badge-v2 { color: var(--badge-text); background: var(--badge-bg); border-color: var(--badge-border); }
    :root[data-theme="light"] .badge-v2::before { background: var(--badge-text); }
    :root[data-theme="light"] .input-group-v2 label,
    :root[data-theme="light"] .meta-row-v2,
    :root[data-theme="light"] .divider-v2,
    :root[data-theme="light"] .auth-footer-v2 { color: var(--text-muted); }
    :root[data-theme="light"] .input-field-v2 { background: var(--field-bg); color: var(--text-main); border-color: var(--border-color); }
    :root[data-theme="light"] .input-field-v2::placeholder { color: var(--field-placeholder); }
    :root[data-theme="light"] .input-field-v2:focus { background: var(--field-bg-focus); border-color: rgba(15, 15, 15, 0.2); }
    :root[data-theme="light"] .icon-btn-v2 { color: var(--field-placeholder); }
    :root[data-theme="light"] .icon-btn-v2:hover { color: var(--text-main); }
    :root[data-theme="light"] .btn-outline-v2 { color: var(--text-main); border-color: var(--border-color); background: rgba(255, 255, 255, 0.72); }
    :root[data-theme="light"] .btn-outline-v2:hover { background: rgba(15, 15, 15, 0.04); }
    :root[data-theme="light"] .btn-primary-v2 { background: var(--accent-white); color: var(--accent-black); }
    :root[data-theme="light"] .btn-primary-v2:hover { background: #e8e1d8; }

    @media (max-width: 900px) {
        .auth-page-v2 {
            background-image:
                linear-gradient(to bottom, rgba(3, 3, 3, 0.6) 0%, rgba(3, 3, 3, 0.95) 100%),
                url('{{ asset('compro/img/ndehero.webp') }}');
        }
        .auth-main-v2 { justify-content: center; padding: 40px 20px; }
    }

    @media (max-width: 600px) {
        .auth-header-v2 { padding: 20px; }
        .brand-logo-v2 img { height: 44px; }
        .auth-card-v2 { padding: 40px 24px; }
        .title-v2 { font-size: 2.8rem; }
        .auth-nav-v2 a:first-child { display: none; }
    }
</style>

<script>
    document.addEventListener('click', function (e) {
        var btn = e.target.closest && e.target.closest('.icon-btn-v2');
        if (!btn) return;
        var target = btn.getAttribute('data-target');
        var input = document.getElementById(target);
        if (!input) return;
        input.type = input.type === 'password' ? 'text' : 'password';
    });
</script>
@endsection
