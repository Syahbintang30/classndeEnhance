<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <script>
        (function() {
            var match = document.cookie.match(/(?:^|; )theme=([^;]+)/);
            var theme = match ? decodeURIComponent(match[1]) : 'dark';
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
    <title>@yield('title', 'Nde Official')</title>
    <meta name="description" content="@yield('meta_description', 'Nde Official - Exclusive Guitar Sessions')">
    <meta name="keywords" content="@yield('meta_keywords', 'Nde Official, guitar sessions, content creator, Indonesia')">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta property="og:title" content="@yield('og_title', 'Nde Official')">
    <meta property="og:description" content="@yield('og_description', 'Nde Official - Exclusive Guitar Sessions')">
    <meta property="og:image" content="@yield('og_image', asset('compro/img/ndelogo.png'))">
    <meta property="og:url" content="@yield('og_url', url()->current())">

    @stack('head')

    <link href="{{ asset('compro/css/bootstrap.css') }}" rel="stylesheet" type="text/css" media="all" />
    <link href="{{ asset('compro/css/base.css') }}" rel="stylesheet" type="text/css" media="all" />
    <link href="{{ asset('compro/css/main.css') }}" rel="stylesheet" type="text/css" media="all" />
    <link href="{{ asset('compro/css/flexslider.css') }}" rel="stylesheet" type="text/css" media="all" />
    <link href="{{ asset('compro/css/magnific-popup.css') }}" rel="stylesheet" type="text/css" media="all" />
    <link href="{{ asset('compro/css/fonts.css') }}" rel="stylesheet" type="text/css" media="all" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <link rel="icon" type="image/png" href="{{ asset('compro/img/ndelogo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/google-fonts.css') }}" rel="stylesheet" type="text/css" media="all" />
    <link href="{{ asset('css/payment-grid.css') }}" rel="stylesheet" type="text/css" media="all" />

    <style>
        html, body { overflow-x: hidden; }
        .nav-logo-dark { display: block; }
        .nav-logo-light { display: none; }
        :root[data-theme="light"] .nav-logo-dark { display: none; }
        :root[data-theme="light"] .nav-logo-light { display: block; }
    </style>

    @stack('styles')

    @php
        $enableThemeToggle = request()->routeIs('lms.*')
            || request()->routeIs('kelas.*')
            || request()->routeIs('coaching.*')
            || request()->routeIs('song.tutorial.*')
            || request()->routeIs('payments.*')
            || request()->routeIs('login')
            || request()->routeIs('register')
            || request()->routeIs('password.*')
            || request()->routeIs('verification.*');
    @endphp

    @if ($enableThemeToggle)
        <style>
            :root {
                color-scheme: light dark;
                --lms-bg: #0a0a0a;
                --lms-hero: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
                --lms-text: #f5f5f5;
                --lms-muted: #888888;
                --lms-subtle: #666666;
                --lms-border: rgba(255, 255, 255, 0.08);
                --lms-card: linear-gradient(180deg, rgba(30, 30, 30, 0.6) 0%, rgba(20, 20, 20, 0.4) 100%);
                --lms-heading-gradient: linear-gradient(135deg, #ffffff 0%, #d0d0d0 100%);
                --lms-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
                --lms-nav-bg: linear-gradient(90deg, rgba(6, 6, 6, 0.92), rgba(14, 14, 14, 0.86));
                --lms-nav-border: 1px solid rgba(255, 255, 255, 0.04);
                --lms-nav-text: #ffffff;
                --lms-nav-text-dim: rgba(255, 255, 255, 0.75);
                --lms-pill-bg: rgba(255, 255, 255, 0.06);
                --lms-pill-border: rgba(255, 255, 255, 0.18);
                --lms-btn-bg: #ffffff;
                --lms-btn-text: #050505;
            }

            :root[data-theme="light"] {
                color-scheme: light;
                --lms-bg: #f5f5f7;
                --lms-hero: linear-gradient(135deg, #f5f5f7 0%, #ebe5dc 100%);
                --lms-text: #0f172a;
                --lms-muted: #64748b;
                --lms-subtle: #475569;
                --lms-border: rgba(15, 23, 42, 0.08);
                --lms-card: #ffffff;
                --lms-heading-gradient: linear-gradient(135deg, #0f172a 0%, #4a4a4a 100%);
                --lms-shadow: 0 8px 24px rgba(15, 23, 42, 0.12);
                --lms-nav-bg: linear-gradient(180deg, #ffffff 0%, #f4f5f7 100%);
                --lms-nav-border: 1px solid rgba(15, 23, 42, 0.08);
                --lms-nav-text: #0f172a;
                --lms-nav-text-dim: rgba(15, 23, 42, 0.72);
                --lms-pill-bg: rgba(15, 23, 42, 0.04);
                --lms-pill-border: rgba(15, 23, 42, 0.12);
                --lms-btn-bg: #0f172a;
                --lms-btn-text: #ffffff;
            }

            body.lms-theme { background: var(--lms-bg); color: var(--lms-text); }
            body.lms-theme .global-nav .nav-wrap { background: var(--lms-nav-bg); border-bottom: var(--lms-nav-border); box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06); }
            body.lms-theme .nav-links a,
            body.lms-theme .nav-username,
            body.lms-theme .nav-login-button,
            body.lms-theme .nav-login-button span { color: var(--lms-nav-text); }
            body.lms-theme .nav-links a,
            body.lms-theme .nav-login-button,
            body.lms-theme .nav-toggle,
            body.lms-theme .nav-username,
            body.lms-theme .btn-ghost { color: var(--lms-nav-text); }
            body.lms-theme .nav-links a:hover,
            body.lms-theme .nav-links a.active,
            body.lms-theme .nav-login-button:hover,
            body.lms-theme .nav-username { color: var(--lms-nav-text); }
            body.lms-theme .btn-ghost {
                background: #ffffff;
                color: #0f172a;
                border-color: rgba(255, 255, 255, 0.22);
            }
            body.lms-theme .btn-ghost:hover { background: #ffffff; border-color: rgba(255, 255, 255, 0.35); }
            :root[data-theme="light"] body.lms-theme .nav-links a,
            :root[data-theme="light"] body.lms-theme .nav-login-button,
            :root[data-theme="light"] body.lms-theme .nav-toggle,
            :root[data-theme="light"] body.lms-theme .nav-username { color: var(--lms-nav-text) !important; }
            :root[data-theme="light"] body.lms-theme .btn-ghost {
                border-color: rgba(15, 23, 42, 0.12);
                background: #0f172a;
                color: #ffffff !important;
            }
        </style>
    @endif
</head>

<body class="{{ $enableThemeToggle ? 'lms-theme' : '' }}">
    @php
        $hideGlobalNav = Route::currentRouteName() === 'compro'
            || request()->routeIs('kelas.payment')
            || request()->routeIs('coaching.session')
            || request()->routeIs('song.tutorial.show')
            || request()->routeIs('admin.audit.*')
            || request()->is('admin/audit*')
            || request()->routeIs('login')
            || request()->routeIs('register')
            || request()->routeIs('password.*')
            || request()->routeIs('verification.*');
    @endphp

    @if (! $hideGlobalNav)
        <nav class="global-nav" aria-label="Main navigation">
            <style>
                .global-nav { position: sticky; top: 0; z-index: 9999; }
                .global-nav .nav-inner { max-width: 1200px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; padding: 14px 22px; gap: 18px; }
                .global-nav .nav-wrap { background: linear-gradient(180deg, #ffffff 0%, #f4f5f7 100%); border-bottom: 1px solid rgba(15, 23, 42, 0.08); box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); }
                .nav-logo { height: 64px; transition: transform .18s ease, filter .18s ease; display: block }
                .nav-logo:hover { transform: translateY(-4px) scale(1.02); filter: drop-shadow(0 12px 30px rgba(0, 0, 0, 0.6)); }
                .nav-links { display: flex; align-items: center; gap: 18px }
                .nav-links a { color: #334155; text-decoration: none; font-weight: 600; padding: 6px 4px; position: relative; opacity: 0.95; transition: color .14s ease, transform .14s ease; }
                .nav-links a:before { content: ''; position: absolute; left: 0; bottom: -6px; width: 0; height: 3px; border-radius: 3px; background: linear-gradient(90deg, #0f172a, #64748b); transition: width .22s cubic-bezier(.2, .9, .2, 1); }
                .nav-links a:hover { transform: translateY(-4px); }
                .nav-links a:hover:before { width: 100%; }
                .nav-links a.active { color: #0f172a; }
                .nav-actions { display: flex; align-items: center; gap: 10px }
                .nav-username { color: #0f172a; font-weight: 600; opacity: 0.95; }
                .btn-ghost { width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center; border-radius: 999px; border: 1px solid rgba(15, 23, 42, 0.12); color: #0f172a; background: #ffffff; padding: 0; transition: transform .12s ease, box-shadow .12s ease, background .12s ease, border-color .12s ease; }
                .btn-ghost:hover { transform: translateY(-2px); box-shadow: 0 12px 30px rgba(15, 23, 42, 0.10); border-color: rgba(15, 23, 42, 0.18); }
                .nav-login-button { display: inline-flex; align-items: center; gap: 10px; background: #ffffff; color: #0f172a; padding: 10px 14px; border-radius: 12px; text-decoration: none; font-weight: 700; border: 1px solid rgba(15, 23, 42, 0.12); transition: transform .14s ease, box-shadow .14s ease; }
                .nav-login-button svg { width: 18px; height: 18px }
                .nav-toggle { display: none; background: transparent; border: none; color: #fff; padding: 8px; border-radius: 8px }
                .nav-profile-button { width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid rgba(15, 23, 42, 0.12); border-radius: 999px; background: #c91863; padding: 0; cursor: pointer; overflow: hidden; transition: transform .12s ease, box-shadow .12s ease; flex: 0 0 36px; }
                .nav-profile-button--has-image { background: transparent; }
                .nav-profile-button:hover { transform: translateY(-2px); box-shadow: 0 12px 30px rgba(15, 23, 42, 0.10); }
                .nav-profile-avatar { width: 100%; height: 100%; border-radius: inherit; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #c91863; color: #ffffff; line-height: 0; }
                .nav-profile-avatar--has-image { background: transparent; }
                .nav-profile-avatar img,
                .nav-profile-avatar svg { width: 100%; height: 100%; display: block; border: 0; flex: 0 0 100%; }
                .nav-profile-avatar img { object-fit: cover; object-position: center; max-width: none; min-width: 100%; min-height: 100%; transform: scale(1.28); transform-origin: center; }
                .nav-profile-avatar svg { padding: 7px; box-sizing: border-box; }
                @media (max-width:900px) {
                    .nav-links { display: none; position: absolute; left: 16px; right: 16px; top: 84px; flex-direction: column; gap: 10px; background: linear-gradient(180deg, rgba(10, 10, 10, 0.96), rgba(8, 8, 8, 0.98)); border-radius: 12px; padding: 14px; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6); }
                    .nav-links.show { display: flex; }
                    .nav-logo { height: 52px }
                    .nav-inner { padding: 10px 16px }
                    .nav-toggle { display: inline-flex; align-items: center; justify-content: center }
                }
            </style>

            <div class="nav-wrap">
                <div class="nav-inner">
                    <div style="display:flex;align-items:center;gap:16px">
                        @if (!request()->is('kelas*') && !request()->routeIs('kelas.*'))
                            <a href="{{ url('/ndeofficial') }}" aria-label="NDE Home" style="display:inline-flex;align-items:center;">
                                <img class="nav-logo nav-logo-dark" src="{{ asset('compro/img/ndelogo.png') }}" alt="NDE logo" />
                                <img class="nav-logo nav-logo-light" src="{{ asset('compro/img/nde_logo_light.png') }}" alt="NDE logo" />
                            </a>
                        @endif
                        <button class="nav-toggle" aria-expanded="false" aria-controls="main-nav" aria-label="Open menu">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M4 7H20M4 12H20M4 17H20" stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" /></svg>
                        </button>
                    </div>

                    <div id="main-nav" class="nav-links" role="navigation">
                        @php
                            $coachingLink = auth()->check() ? route('coaching.upcoming') : route('login');
                            $showSongTutorial = false;
                            $hasCourseAccess = false;
                            $hasCoachingAccess = false;
                            if (auth()->check()) {
                                $u = auth()->user();
                                $hasCourseAccess = $u->hasLmsAccess();
                                $hasCoachingAccess = method_exists($u, 'hasCoachingAccess') && $u->hasCoachingAccess();
                                $showSongTutorial = $hasCourseAccess;
                            }
                        @endphp

                        {{-- Coaching-only users --}}
                        @if (auth()->check() && !$hasCourseAccess && $hasCoachingAccess)
                            <a href="{{ $coachingLink }}" class="{{ request()->routeIs('coaching.*') ? 'active' : '' }}">Coaching</a>

                        {{-- Logged in but no access at all — show nothing or just buy link --}}
                        @elseif (auth()->check() && !$hasCourseAccess)
                            {{-- No nav links for unpaid users --}}

                        {{-- Full access users --}}
                        @else
                            @auth
                                <a href="{{ route('lms.dashboard') }}" class="{{ request()->routeIs('lms.dashboard') ? 'active' : '' }}">Home</a>
                            @endauth
                            @if($hasCourseAccess)
                                <a href="{{ route('lms.entry') }}" class="{{ request()->routeIs('kelas.show') || request()->routeIs('lms.entry') ? 'active' : '' }}">Courses</a>
                            @endif
                            @auth
                                <a href="{{ $coachingLink }}" class="{{ request()->routeIs('coaching.*') ? 'active' : '' }}">Coaching</a>
                                @if ($showSongTutorial)
                                    <a href="{{ route('song.tutorial.index') }}" class="{{ request()->routeIs('song.tutorial.*') ? 'active' : '' }}">Song Tutorial</a>
                                @endif
                            @endauth
                        @endif
                    </div>

                    <div class="nav-actions">
                        @if ($enableThemeToggle)
                            <button id="theme-toggle-app" type="button" class="btn-ghost" aria-label="Toggle theme">
                                <svg id="theme-app-moon" style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
                                <svg id="theme-app-sun" style="width:16px;height:16px;display:none;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/></svg>
                            </button>
                        @endif

                        @auth
                            <div style="position:relative;display:flex;align-items:center;">
                                @php $avatar = auth()->user()->photoUrl(); @endphp
                                <button id="profile-toggle" class="nav-profile-button {{ $avatar ? 'nav-profile-button--has-image' : '' }}" aria-haspopup="true" aria-expanded="false" aria-label="Open profile menu">
                                    <span class="nav-profile-avatar {{ $avatar ? 'nav-profile-avatar--has-image' : '' }}">
                                        @if ($avatar)
                                            <img src="{{ $avatar }}" alt="" onerror="this.hidden=true;this.nextElementSibling.hidden=false;">
                                            <svg hidden viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                                <path d="M20 21a8 8 0 0 0-16 0"></path>
                                                <circle cx="12" cy="7" r="4"></circle>
                                            </svg>
                                        @else
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                                <path d="M20 21a8 8 0 0 0-16 0"></path>
                                                <circle cx="12" cy="7" r="4"></circle>
                                            </svg>
                                        @endif
                                    </span>
                                </button>
                                <div id="profile-menu" role="menu"
                                    style="display:none;position:absolute;right:0;top:44px;background:linear-gradient(180deg,#0b0b0b,#0e0e0e);border-radius:10px;padding:8px;border:1px solid rgba(255,255,255,0.04);box-shadow:0 18px 40px rgba(0,0,0,0.6);min-width:180px;z-index:999">
                                    <div style="padding:8px 10px;border-bottom:1px solid rgba(255,255,255,0.02)">
                                        <div style="font-weight:700;color:#fff">{{ auth()->user()->name }}</div>
                                        <div style="font-size:12px;color:rgba(255,255,255,0.65)">{{ auth()->user()->email }}</div>
                                    </div>
                                    <a href="{{ route('profile') }}" style="display:block;padding:8px 10px;color:#fff;text-decoration:none">Profile</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" style="width:100%;text-align:left;padding:8px 10px;border:none;background:transparent;color:#fff;cursor:pointer">Logout</button>
                                    </form>
                                </div>
                            </div>
                        @else
                            @if (Route::currentRouteName() !== 'login')
                                <a href="{{ route('login') }}" class="nav-login-button" aria-label="Login">
                                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false">
                                        <path d="M15 12H3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M10 17L15 12L10 7" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                                        <circle cx="18.5" cy="7.5" r="3" stroke="currentColor" stroke-width="1.6" />
                                    </svg>
                                    <span>Login</span>
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>

            <script>
                (function() {
                    const toggle = document.querySelector('.global-nav .nav-toggle');
                    const navLinks = document.getElementById('main-nav');
                    if (!toggle || !navLinks) return;
                    toggle.addEventListener('click', function() {
                        const expanded = this.getAttribute('aria-expanded') === 'true';
                        this.setAttribute('aria-expanded', String(!expanded));
                        navLinks.classList.toggle('show');
                    });
                })();
            </script>
        </nav>
    @endif

    @auth
        @php
            $isAdminVisible = auth()->user()->is_admin || auth()->user()->is_superadmin;
            $onAdminRoute = request()->routeIs('admin.*') || request()->is('admin*');
        @endphp
        @if ($isAdminVisible && $onAdminRoute)
            <nav style="background:#0b1220;color:#fff;padding:8px 20px;border-bottom:1px solid #0f1724;">
                <div style="max-width:1200px;margin:0 auto;display:flex;align-items:center;gap:18px;">
                    <a href="{{ route('admin.dashboard') ?? url('/admin') }}" style="color:#fff;text-decoration:none;font-weight:500;">Admin Dashboard</a>
                    <a href="{{ url('/admin/coaching/bookings') }}" style="color:#fff;text-decoration:none;font-weight:500;">Coaching Bookings</a>
                    <a href="{{ url('/coaching') }}" style="color:#fff;text-decoration:none;font-weight:500;">Public Coaching Page</a>
                </div>
            </nav>
        @endif
    @endauth

    @yield('content')

    <script src="{{ asset('compro/js/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ asset('compro/js/jquery.flexslider-min.js') }}"></script>
    <script src="{{ asset('compro/js/smooth-scroll.js') }}"></script>
    <script src="{{ asset('compro/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('compro/js/twitterFetcher_min.js') }}"></script>
    <script src="{{ asset('compro/js/instafeed.min.js') }}"></script>
    <script src="{{ asset('compro/js/jquery.countdown.min.js') }}"></script>
    <script src="{{ asset('compro/js/placeholders.min.js') }}"></script>
    <script src="{{ asset('compro/js/script.js') }}"></script>
    <script src="{{ asset('js/payment-grid.js') }}"></script>

    @if ($enableThemeToggle)
        <script>
            (function () {
                var toggle = document.getElementById('theme-toggle-app');
                if (!toggle) return;
                function getTheme() {
                    var match = document.cookie.match(/(?:^|; )theme=([^;]+)/);
                    return match ? decodeURIComponent(match[1]) : 'dark';
                }
                function setTheme(theme) {
                    document.documentElement.setAttribute('data-theme', theme);
                    document.cookie = 'theme=' + encodeURIComponent(theme) + '; path=/; max-age=31536000; SameSite=Lax';
                    var moon = document.getElementById('theme-app-moon');
                    var sun = document.getElementById('theme-app-sun');
                    if (theme === 'light') {
                        if (moon) moon.style.display = 'block';
                        if (sun) sun.style.display = 'none';
                    } else {
                        if (moon) moon.style.display = 'none';
                        if (sun) sun.style.display = 'block';
                    }
                }
                setTheme(getTheme());
                toggle.addEventListener('click', function () {
                    var next = document.documentElement.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
                    setTheme(next);
                });
            })();
        </script>
    @endif

    @stack('scripts')

    <script>
        (function() {
            const btn = document.getElementById('profile-toggle');
            const menu = document.getElementById('profile-menu');
            if (btn && menu) {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const open = menu.style.display !== 'none';
                    menu.style.display = open ? 'none' : 'block';
                    btn.setAttribute('aria-expanded', String(!open));
                });
                document.addEventListener('click', function() {
                    if (menu) menu.style.display = 'none';
                });
            }
        })();
    </script>
</body>

</html>
