@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="cyber-auth-page">
    <div class="cyber-auth-bg"></div>
    <div class="cyber-auth-overlay"></div>

    <header class="cyber-auth-header">
        <a href="{{ route('compro') }}" class="cyber-brand" aria-label="ClassNDE home">
            <img src="{{ asset('compro/img/ndelogo.png') }}" alt="NDE Logo" class="cyber-brand-logo">
        </a>
        <nav class="cyber-nav">
            <a href="{{ route('registerclass') }}">Courses</a>
            <a href="{{ route('login') }}">Sign in</a>
            <a href="{{ route('register') }}" class="btn-nav active">Register</a>
        </nav>
    </header>

    <div class="cyber-auth-layout">
        <div class="cyber-auth-left" aria-hidden="true"></div>

        <section class="cyber-auth-right">
            <div class="cyber-card">
                <p class="cyber-kicker">ClassNDE Portal</p>
                <h1>Create Account</h1>
                <p class="cyber-subtitle">Sign up with Google or continue with email registration.</p>

                <a href="{{ route('auth.google.redirect') }}" class="cyber-btn google">
                    <svg viewBox="0 0 24 24" class="g-icon" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"></path>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"></path>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"></path>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"></path>
                    </svg>
                    Sign Up with Google
                </a>

                <div class="cyber-divider"><span>Or continue with email</span></div>

                @if(session('status'))
                    <div class="cyber-alert success">{{ session('status') }}</div>
                @endif

                @if($errors->any())
                    <div class="cyber-alert error">
                        <ul>
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" id="registerForm" class="cyber-form">
                    @csrf
                    <label for="register-name" class="cyber-label">Name</label>
                    <input id="register-name" name="name" type="text" value="{{ old('name') }}" required class="cyber-input @error('name') input-error @enderror" placeholder="Enter your name" />

                    <label for="register-email" class="cyber-label">Email</label>
                    <input id="register-email" name="email" type="email" value="{{ old('email') }}" required class="cyber-input @error('email') input-error @enderror" placeholder="Enter your email" />

                    <label for="register-password" class="cyber-label">Password</label>
                    <div class="cyber-password-wrap">
                        <input id="register-password" name="password" type="password" required class="cyber-input @error('password') input-error @enderror" placeholder="Create password" />
                        <button type="button" class="cyber-toggle" data-target="register-password" aria-label="Toggle password visibility">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        </button>
                    </div>

                    <label for="register-password-confirmation" class="cyber-label">Confirm Password</label>
                    <div class="cyber-password-wrap">
                        <input id="register-password-confirmation" name="password_confirmation" type="password" required class="cyber-input" placeholder="Repeat password" />
                        <button type="button" class="cyber-toggle" data-target="register-password-confirmation" aria-label="Toggle password visibility">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        </button>
                    </div>

                    <button type="submit" class="cyber-btn primary">Create Account</button>
                </form>

                <p class="cyber-footer">Already have an account? <a href="{{ route('login') }}">Sign in here</a></p>
            </div>
        </section>
    </div>
</div>

<style>
    .cyber-auth-page { position: relative; min-height: 100vh; overflow: hidden; color: #e2e8f0; }
    .cyber-auth-bg { position: absolute; inset: 0; background: url('{{ asset('compro/img/ndehero.webp') }}') center/cover no-repeat; transform: scale(1.02); }
    .cyber-auth-overlay { position: absolute; inset: 0; background: linear-gradient(90deg, rgba(2, 6, 23, 0.82) 0%, rgba(15, 23, 42, 0.4) 52%, rgba(2, 6, 23, 0.84) 100%); }
    .cyber-auth-header { position: absolute; top: 0; left: 0; right: 0; z-index: 5; padding: 18px 32px; display: flex; align-items: center; justify-content: space-between; }
    .cyber-brand { display: inline-flex; align-items: center; justify-content: center; width: 42px; height: 42px; text-decoration: none; }
    .cyber-brand-logo { width: 42px; height: 42px; object-fit: contain; display: block; }
    .cyber-nav { display: inline-flex; align-items: center; gap: 18px; }
    .cyber-nav a { color: rgba(226, 232, 240, 0.9); text-decoration: none; font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; height: 42px; }
    .cyber-nav a.active { color: #0f172a; background: rgba(255, 255, 255, 0.92); border-radius: 999px; padding: 0 14px; }
    .cyber-nav .btn-nav { padding: 0 14px; border-radius: 999px; background: rgba(255, 255, 255, 0.92); color: #0f172a; }

    .cyber-auth-layout { position: relative; z-index: 2; min-height: 100vh; display: flex; }
    .cyber-auth-left { flex: 1; }
    .cyber-auth-right { width: min(520px, 100%); margin-left: auto; display: flex; align-items: center; justify-content: center; padding: 100px 24px 24px; }

    .cyber-card { width: 100%; border-radius: 26px; background: rgba(15, 23, 42, 0.36); border: 1px solid rgba(255, 255, 255, 0.2); backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px); box-shadow: 0 22px 48px rgba(2, 6, 23, 0.45); padding: 28px; }
    .cyber-kicker { margin: 0 0 10px; font-size: 11px; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: #a5b4fc; }
    .cyber-card h1 { margin: 0; font-size: 40px; line-height: 1.02; color: #fff; }
    .cyber-subtitle { margin: 8px 0 18px; font-size: 14px; color: rgba(226, 232, 240, 0.78); }

    .cyber-alert { border-radius: 12px; padding: 10px 12px; font-size: 13px; margin-bottom: 12px; border: 1px solid transparent; }
    .cyber-alert ul { margin: 0; padding-left: 16px; }
    .cyber-alert.success { background: rgba(16, 185, 129, 0.2); border-color: rgba(16, 185, 129, 0.45); color: #d1fae5; }
    .cyber-alert.error { background: rgba(239, 68, 68, 0.2); border-color: rgba(248, 113, 113, 0.45); color: #fecaca; }

    .cyber-form { display: grid; gap: 10px; }
    .cyber-label { font-size: 13px; font-weight: 600; color: rgba(226, 232, 240, 0.92); }
    .cyber-input { width: 100%; min-height: 47px; border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.14); background: rgba(15, 23, 42, 0.44); color: #fff; padding: 0 14px; outline: none; transition: .2s ease; }
    .cyber-input::placeholder { color: rgba(226, 232, 240, 0.46); }
    .cyber-input:focus { border-color: rgba(129, 140, 248, 0.75); box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2); background: rgba(15, 23, 42, 0.62); }
    .cyber-input.input-error { border-color: rgba(248, 113, 113, 0.75); }

    .cyber-password-wrap { position: relative; }
    .cyber-password-wrap .cyber-input { padding-right: 44px; }
    .cyber-toggle { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); border: none; background: transparent; color: rgba(203, 213, 225, 0.72); padding: 5px; cursor: pointer; }
    .cyber-toggle svg { width: 18px; height: 18px; }

    .cyber-btn { width: 100%; min-height: 47px; border-radius: 12px; border: 1px solid transparent; display: inline-flex; align-items: center; justify-content: center; gap: 10px; text-decoration: none; font-size: 14px; font-weight: 700; transition: .2s ease; cursor: pointer; }
    .cyber-btn.primary { background: #f8fafc; color: #0f172a; }
    .cyber-btn.primary:hover { transform: translateY(-1px); background: #fff; }
    .cyber-divider { display: flex; align-items: center; gap: 10px; margin: 8px 0; }
    .cyber-divider::before, .cyber-divider::after { content: ''; height: 1px; flex: 1; background: rgba(255, 255, 255, 0.2); }
    .cyber-divider span { font-size: 12px; color: rgba(226, 232, 240, 0.65); }
    .cyber-btn.google { border-color: rgba(255, 255, 255, 0.15); background: rgba(255, 255, 255, 0.08); color: #fff; }
    .cyber-btn.google:hover { background: rgba(255, 255, 255, 0.14); }
    .g-icon { width: 18px; height: 18px; }

    .cyber-footer { margin: 14px 0 0; font-size: 13px; text-align: center; color: rgba(226, 232, 240, 0.72); }
    .cyber-footer a { color: #fff; font-weight: 700; text-decoration: none; }
    .cyber-footer a:hover { text-decoration: underline; }

    @media (max-width: 900px) {
        .cyber-auth-header { padding: 16px 16px; }
        .cyber-brand,
        .cyber-brand-logo { width: 36px; height: 36px; }
        .cyber-nav a:not(.btn-nav) { display: none; }
        .cyber-auth-layout { min-height: 100vh; }
        .cyber-auth-right { width: 100%; padding: 92px 16px 20px; }
        .cyber-card h1 { font-size: 34px; }
    }
</style>

<script>
    document.addEventListener('click', function (e) {
        var btn = e.target.closest && e.target.closest('.cyber-toggle');
        if (!btn) return;
        var target = btn.getAttribute('data-target');
        var input = document.getElementById(target);
        if (!input) return;
        input.type = input.type === 'password' ? 'text' : 'password';
    });
</script>
@endsection
