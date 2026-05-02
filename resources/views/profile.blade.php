@extends('layouts.app')

@section('title', 'Profile')

@section('head')
<script>
    (function() {
        var match = document.cookie.match(/(?:^|; )theme=([^;]+)/);
        var theme = match ? decodeURIComponent(match[1]) : 'dark';
        document.documentElement.setAttribute('data-theme', theme);
    })();
</script>
@endsection

@section('content')
<style>
    /* Hide global navbar */
    body > nav { display: none; }

    /* Theme Variables */
    :root {
        --profile-bg: #0a0a0a;
        --profile-card-bg: rgba(24,24,27,0.5);
        --profile-card-border: rgba(255,255,255,0.08);
        --profile-text: #fff;
        --profile-muted: rgba(255,255,255,0.55);
        --profile-subtle: rgba(255,255,255,0.5);
        --profile-btn-bg: #fff;
        --profile-btn-text: #09090b;
        --profile-action-bg: rgba(24,24,27,0.3);
        --profile-action-hover-bg: rgba(63,63,70,0.35);
    }

    :root[data-theme="light"] {
        --profile-bg: #f5f5f7;
        --profile-card-bg: #ffffff;
        --profile-card-border: rgba(15,23,42,0.08);
        --profile-text: #0f172a;
        --profile-muted: rgba(15,23,42,0.55);
        --profile-subtle: rgba(15,23,42,0.5);
        --profile-btn-bg: #0f172a;
        --profile-btn-text: #ffffff;
        --profile-action-bg: rgba(15,23,42,0.03);
        --profile-action-hover-bg: rgba(15,23,42,0.07);
    }

    html, body { background: var(--profile-bg) !important; color: var(--profile-text) !important; }

    /* LMS Navbar */
    .lms-navbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 72px;
        background: linear-gradient(180deg, #111 0%, #0a0a0a 100%);
        border-bottom: 1px solid rgba(255,255,255,0.06);
        padding: 0 28px;
        position: sticky;
        top: 0;
        z-index: 100;
    }

    .lms-home-link {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #e0e0e0;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: color 0.2s;
    }

    .lms-home-link:hover { color: #fff; }

    .lms-navbar-right {
        display: flex;
        align-items: center;
        gap: 28px;
    }

    .lms-nav-link {
        color: #888;
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
        transition: color 0.2s;
    }

    .lms-nav-link:hover,
    .lms-nav-link.active { color: #fff; font-weight: 600; }

    :root[data-theme="light"] .lms-navbar {
        background: linear-gradient(180deg, #ffffff 0%, #f4f5f7 100%);
        border-bottom-color: rgba(15,23,42,0.08);
    }

    :root[data-theme="light"] .lms-home-link,
    :root[data-theme="light"] .lms-nav-link { color: #475569; }

    :root[data-theme="light"] .lms-home-link:hover,
    :root[data-theme="light"] .lms-nav-link:hover,
    :root[data-theme="light"] .lms-nav-link.active { color: #0f172a; }

    .lms-theme-btn {
        width: 34px; height: 34px;
        display: inline-flex; align-items: center; justify-content: center;
        border-radius: 999px;
        border: 1px solid rgba(255,255,255,0.12);
        background: transparent;
        color: #fff;
        cursor: pointer;
        transition: all 0.2s;
    }

    .lms-theme-btn:hover { background: rgba(255,255,255,0.08); }

    :root[data-theme="light"] .lms-theme-btn {
        border-color: rgba(15,23,42,0.12);
        color: #0f172a;
    }

    :root[data-theme="light"] .lms-theme-btn:hover { background: rgba(15,23,42,0.06); }

    /* Page wrapper */
    .profile-page {
        background: var(--profile-bg);
        min-height: calc(100vh - 72px);
        padding: 40px 24px 60px;
    }

    .profile-inner {
        max-width: 1100px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 28px;
        align-items: start;
    }

    @media (max-width: 900px) {
        .profile-inner { grid-template-columns: 1fr; }
    }

    /* Left card */
    .profile-left {
        background: var(--profile-card-bg);
        border: 1px solid var(--profile-card-border);
        border-radius: 20px;
        padding: 32px 24px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    }

    :root[data-theme="light"] .profile-left {
        box-shadow: 0 4px 20px rgba(15,23,42,0.06);
    }

    .profile-avatar-wrap {
        width: 100px; height: 100px;
        border-radius: 999px;
        overflow: hidden;
        border: 3px solid var(--profile-card-border);
        margin-bottom: 16px;
        background: #c91863;
        display: flex; align-items: center; justify-content: center;
        color: #ffffff;
        line-height: 0;
        isolation: isolate;
    }

    :root[data-theme="light"] .profile-avatar-wrap { background: #c91863; }
    .profile-avatar-wrap--has-image,
    :root[data-theme="light"] .profile-avatar-wrap--has-image { background: transparent; }

    .profile-avatar-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        display: block;
        max-width: none;
        min-width: 100%;
        min-height: 100%;
        transform: scale(1.12);
    }

    .profile-avatar-fallback {
        width: 100%;
        height: 100%;
        border-radius: inherit;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #c91863;
        color: #ffffff;
        font-size: 54px;
        line-height: 1;
        font-weight: 500;
        text-transform: uppercase;
    }

    .profile-name {
        font-size: 18px;
        font-weight: 700;
        color: var(--profile-text);
        margin: 0 0 4px;
    }

    .profile-email {
        font-size: 13px;
        color: var(--profile-muted);
        margin: 0 0 24px;
        word-break: break-all;
    }

    .profile-btn-explore {
        display: block;
        width: 100%;
        background: var(--profile-btn-bg);
        color: var(--profile-btn-text);
        font-weight: 600;
        font-size: 14px;
        padding: 11px 16px;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
        transition: opacity 0.2s, transform 0.2s;
    }

    .profile-btn-explore:hover { opacity: 0.88; transform: translateY(-1px); }

    /* Right content */
    .profile-right { display: flex; flex-direction: column; gap: 20px; }

    .profile-section-title {
        font-size: 24px;
        font-weight: 700;
        color: var(--profile-text);
        margin: 0 0 16px;
    }

    /* Info cards */
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    @media (max-width: 600px) { .info-grid { grid-template-columns: 1fr; } }

    .info-card {
        background: var(--profile-card-bg);
        border: 1px solid var(--profile-card-border);
        border-radius: 12px;
        padding: 14px 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    :root[data-theme="light"] .info-card { box-shadow: 0 2px 8px rgba(15,23,42,0.04); }

    .info-label {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--profile-muted);
        display: block;
        margin-bottom: 6px;
    }

    .info-value {
        font-size: 15px;
        font-weight: 500;
        color: var(--profile-text);
    }

    .subscription-card {
        grid-column: 1 / -1;
        background: var(--profile-card-bg);
        border: 1px solid var(--profile-card-border);
        border-radius: 12px;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    :root[data-theme="light"] .subscription-card { box-shadow: 0 2px 8px rgba(15,23,42,0.04); }

    .status-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 6px;
        background: rgba(99,102,241,0.12);
        color: #a5b4fc;
        font-size: 12px;
        font-weight: 600;
        border: 1px solid rgba(99,102,241,0.2);
        margin-left: 8px;
    }

    :root[data-theme="light"] .status-badge {
        background: rgba(99,102,241,0.08);
        color: rgba(79,70,229,0.9);
        border-color: rgba(99,102,241,0.18);
    }

    .manage-link {
        font-size: 13px;
        color: var(--profile-muted);
        text-decoration: none;
        white-space: nowrap;
        transition: color 0.2s;
    }

    .manage-link:hover { color: var(--profile-text); }

    /* Action buttons */
    .actions-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }

    @media (max-width: 600px) { .actions-grid { grid-template-columns: 1fr; } }

    .action-btn {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 16px;
        background: var(--profile-action-bg);
        border: 1px solid var(--profile-card-border);
        border-radius: 12px;
        text-decoration: none;
        color: var(--profile-text);
        transition: background 0.2s, border-color 0.2s, transform 0.15s;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }

    .action-btn:hover {
        background: var(--profile-action-hover-bg);
        border-color: rgba(255,255,255,0.14);
        transform: translateY(-2px);
    }

    :root[data-theme="light"] .action-btn:hover { border-color: rgba(15,23,42,0.14); }

    .action-left { display: flex; align-items: center; gap: 10px; }
    .action-icon { width: 18px; height: 18px; color: var(--profile-muted); flex-shrink: 0; }
    .action-label { font-size: 14px; font-weight: 500; color: var(--profile-text); }
    .action-chevron { width: 15px; height: 15px; color: var(--profile-muted); }

    /* Referral section */
    .referral-card {
        background: var(--profile-card-bg);
        border: 1px solid var(--profile-card-border);
        border-radius: 16px;
        padding: 22px 22px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    }

    :root[data-theme="light"] .referral-card { box-shadow: 0 4px 16px rgba(15,23,42,0.05); }

    .referral-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--profile-text);
        margin: 0 0 4px;
    }

    .referral-desc { font-size: 13px; color: var(--profile-muted); margin: 0 0 16px; }

    .referral-row { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }

    .referral-code-box {
        display: flex;
        align-items: center;
        gap: 10px;
        background: rgba(0,0,0,0.3);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 10px;
        padding: 9px 14px;
    }

    :root[data-theme="light"] .referral-code-box {
        background: rgba(15,23,42,0.04);
        border-color: rgba(15,23,42,0.1);
    }

    .referral-code { font-family: monospace; font-size: 17px; font-weight: 700; letter-spacing: 2px; color: #a5b4fc; }
    :root[data-theme="light"] .referral-code { color: #4f46e5; }

    .copy-btn {
        width: 30px; height: 30px;
        background: rgba(255,255,255,0.08);
        border: none; border-radius: 7px;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        color: rgba(255,255,255,0.55);
        transition: all 0.2s;
    }

    .copy-btn:hover { background: rgba(255,255,255,0.14); color: #fff; }
    .copy-btn.copied { color: #4ade80; }

    :root[data-theme="light"] .copy-btn { background: rgba(15,23,42,0.06); color: rgba(15,23,42,0.5); }
    :root[data-theme="light"] .copy-btn:hover { background: rgba(15,23,42,0.1); color: #0f172a; }

    .referral-hint { font-size: 12px; color: var(--profile-muted); }
    .referral-hint a { color: var(--profile-text); text-decoration: underline; }

    /* Flash messages */
    .msg-wrap { max-width: 1100px; margin: 0 auto 16px; padding: 0 24px; }
    .msg-box { padding: 12px 16px; border-radius: 10px; font-weight: 600; font-size: 14px; margin-bottom: 8px; }
    .msg-success { background: rgba(11,122,68,0.15); color: #86efac; border: 1px solid rgba(34,197,94,0.2); }
    .msg-error { background: rgba(192,57,43,0.15); color: #fca5a5; border: 1px solid rgba(248,113,113,0.2); }
    .msg-error ul { margin: 6px 0 0; padding-left: 18px; }
</style>

<!-- LMS Navbar -->
<nav class="lms-navbar">
    <a href="{{ route('lms.dashboard') }}" class="lms-home-link">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
            <polyline points="9 22 9 12 15 12 15 22"></polyline>
        </svg>
        Home
    </a>

    <div class="lms-navbar-right">
        <a href="{{ route('lms.entry') }}" class="lms-nav-link @if(request()->routeIs('kelas.show') || request()->routeIs('lms.entry')) active @endif">Lessons</a>
        <a href="{{ route('coaching.upcoming') }}" class="lms-nav-link @if(request()->routeIs('coaching.*')) active @endif">Coaching</a>
        @php $user = auth()->user(); @endphp
        @if($user && $user->hasLmsAccess())
            <a href="{{ route('song.tutorial.index') }}" class="lms-nav-link @if(request()->routeIs('song.tutorial.*')) active @endif">Song Tutorial</a>
        @endif
        <button id="theme-toggle-profile" type="button" class="lms-theme-btn" aria-label="Toggle theme">
            <svg id="theme-profile-moon" style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
            <svg id="theme-profile-sun" style="width:15px;height:15px;display:none;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/></svg>
        </button>
    </div>
</nav>

<!-- Flash messages -->
@if(session('status') || session('success') || session('error') || $errors->any())
<div class="msg-wrap">
    @if(session('status') === 'profile-updated' || session('success'))
        <div class="msg-box msg-success">{{ session('success') ?? 'Profile updated.' }}</div>
    @endif
    @if(session('status') === 'password-updated')
        <div class="msg-box msg-success">Password updated.</div>
    @endif
    @if(session('error'))
        <div class="msg-box msg-error">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="msg-box msg-error">
            <ul>@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
        </div>
    @endif
</div>
@endif

<div class="profile-page">
    <div class="profile-inner">

        <!-- LEFT: Profile Card -->
        <div class="profile-left">
            @php $avatar = auth()->user()->photoUrl(); @endphp
            <div class="profile-avatar-wrap {{ $avatar ? 'profile-avatar-wrap--has-image' : '' }}">
                @if($avatar)
                    <img src="{{ $avatar }}" alt="" onerror="this.hidden=true;this.nextElementSibling.hidden=false;">
                    <span class="profile-avatar-fallback" hidden>{{ mb_substr(auth()->user()->name ?? 'U', 0, 1) }}</span>
                @else
                    <span class="profile-avatar-fallback">{{ mb_substr(auth()->user()->name ?? 'U', 0, 1) }}</span>
                @endif
            </div>
            <h2 class="profile-name">{{ auth()->user()->name }}</h2>
            <p class="profile-email">{{ auth()->user()->email }}</p>
            <a href="{{ route('registerclass') }}" class="profile-btn-explore">Jelajahi Kursus</a>
        </div>

        <!-- RIGHT: Account Details -->
        <div class="profile-right">
            <div>
                <h1 class="profile-section-title">Akun Saya</h1>

                <div class="info-grid">
                    <div class="info-card">
                        <span class="info-label">Nama Lengkap</span>
                        <div class="info-value">{{ auth()->user()->name }}</div>
                    </div>
                    <div class="info-card">
                        <span class="info-label">Alamat Email</span>
                        <div class="info-value" style="font-size:13px;">{{ auth()->user()->email }}</div>
                    </div>
                    <div class="subscription-card">
                        <div>
                            <span class="info-label">Status Langganan</span>
                            <div style="display:flex;align-items:center;margin-top:4px;">
                                @php
                                    $pid = auth()->user()->package_id;
                                    $pkgName = match($pid) { 1 => 'Beginner', 2 => 'Intermediate', 3 => 'Intermediate', default => (isset($package) && $package ? $package->name : 'None') };
                                @endphp
                                <span class="info-value">{{ $pkgName }}</span>
                                <span class="status-badge">Aktif</span>
                            </div>
                        </div>
                        <a href="{{ route('registerclass') }}" class="manage-link">Kelola →</a>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="actions-grid">
                <a href="{{ route('profile.edit') }}" class="action-btn">
                    <div class="action-left">
                        <svg class="action-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        <span class="action-label">Edit Profil</span>
                    </div>
                    <svg class="action-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </a>
                <a href="{{ route('profile.edit') }}#password" class="action-btn">
                    <div class="action-left">
                        <svg class="action-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        <span class="action-label">Ubah Sandi</span>
                    </div>
                    <svg class="action-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </a>
                <a href="{{ route('profile.referrals') }}" class="action-btn">
                    <div class="action-left">
                        <svg class="action-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <span class="action-label">Referral Saya</span>
                    </div>
                    <svg class="action-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </a>
            </div>

            <!-- Referral Section -->
            <div class="referral-card">
                <h3 class="referral-title">Kode Referral Anda</h3>
                <p class="referral-desc">Bagikan kode ini ke teman dan dapatkan keuntungan bersama.</p>
                <div class="referral-row">
                    <div class="referral-code-box">
                        <span class="referral-code">{{ auth()->user()->referral_code ?? '—' }}</span>
                        <button class="copy-btn" onclick="copyCode('{{ auth()->user()->referral_code ?? '' }}', this)" title="Salin">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>
                        </button>
                    </div>
                    <span class="referral-hint">Detail lengkap di <a href="{{ route('profile.referrals') }}">My Referrals</a></span>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
function copyCode(text, btn) {
    if (!text) return;
    navigator.clipboard.writeText(text).then(() => {
        const orig = btn.innerHTML;
        btn.classList.add('copied');
        btn.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>';
        setTimeout(() => { btn.classList.remove('copied'); btn.innerHTML = orig; }, 2000);
    });
}

(function() {
    function getTheme() {
        const m = document.cookie.match(/(?:^|; )theme=([^;]+)/);
        return m ? decodeURIComponent(m[1]) : 'dark';
    }

    function setTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        document.cookie = `theme=${encodeURIComponent(theme)}; path=/; max-age=31536000; SameSite=Lax`;
        const moon = document.getElementById('theme-profile-moon');
        const sun = document.getElementById('theme-profile-sun');
        if (moon && sun) {
            moon.style.display = theme === 'light' ? 'none' : 'block';
            sun.style.display = theme === 'light' ? 'block' : 'none';
        }
    }

    setTheme(getTheme());

    const btn = document.getElementById('theme-toggle-profile');
    if (btn) btn.addEventListener('click', () => setTheme(getTheme() === 'light' ? 'dark' : 'light'));
})();
</script>
@endsection
