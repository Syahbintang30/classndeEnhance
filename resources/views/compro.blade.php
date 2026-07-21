<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nde Guitar Class — Premium Guitar Coaching</title>
    <meta name="description" content="Learn guitar the right way. Lifetime access to HD videos, direct 1-on-1 coaching with the instructor.">

    <script>
        (function(){
            var m=document.cookie.match(/(?:^|; )theme=([^;]+)/);
            document.documentElement.setAttribute('data-theme', m ? decodeURIComponent(m[1]) : 'dark');
        })();
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter','sans-serif'], display: ['Anton','sans-serif'] },
                    colors: {
                        accent: '#ffffff',
                    }
                }
            }
        };
    </script>

    <style>
        /* ── THEME TOKENS ─────────────────────────────── */
        :root {
            --bg:        #0A0A0A;
            --surface:   #141414;
            --border:    #282828;
            --text:      #FAFAFA;
            --dim:       #A1A1A1;
            --heading:   #FFFFFF;
            --primary:   #5CA8F5;
            --primary-hover: #7CB9F8;
            --success:   #A8D63A;
            --warning:   #FFC107;
            --danger:    #E05252;
            --nav-bg:    rgba(10,10,10,0.80);
        }
        :root[data-theme="light"] {
            --bg:        #FAFAFA;
            --surface:   #FFFFFF;
            --bg-alt:    #F4F7FB;
            --bg-alt2:   #F7F7F7;
            --border:    #E5E5E5;
            --text:      #121212;
            --dim:       #737373;
            --heading:   #090909;
            --primary:   #4DA6FF;
            --primary-hover: #2F8BFF;
            --success:   #9BC636;
            --warning:   #FFB300;
            --danger:    #D84545;
            --nav-bg:    rgba(255,255,255,0.88);
        }

        *, *::before, *::after { box-sizing: border-box; }

        html { scroll-behavior: smooth; font-size: 17px; }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        /* ── SCROLLBAR ────────────────────────────────── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg); }
        ::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 3px; }

        /* ── SELECTION ────────────────────────────────── */
        ::selection { background: var(--primary); color: #000000; }

        /* ── NAVBAR ───────────────────────────────────── */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            padding: 1rem 2rem;
            display: flex; align-items: center; justify-content: space-between;
            background: transparent;
            transition: padding .4s, background .4s, box-shadow .4s, backdrop-filter .4s;
        }
        .navbar.scrolled {
            padding: .85rem 2rem;
            background: rgba(10,10,10,.78);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 1px 0 rgba(255,255,255,.06);
        }
        :root[data-theme="light"] .navbar.scrolled {
            background: rgba(255,255,255,.78);
            box-shadow: 0 1px 0 rgba(0,0,0,.06);
        }
        .nav-logo-dark { display: block; }
        .nav-logo-light { display: none; }
        :root[data-theme="light"] .navbar.scrolled .nav-logo-dark { display: none; }
        :root[data-theme="light"] .navbar.scrolled .nav-logo-light { display: block; }

        .nav-link {
            font-size: .75rem; font-weight: 600; letter-spacing: .1em;
            text-transform: uppercase; color: var(--dim);
            text-decoration: none; transition: color .25s, transform .25s;
            position: relative;
        }
        .nav-link:hover { 
            color: var(--heading); 
            transform: translateY(-2px);
        }
        .nav-link.active {
            color: var(--heading);
        }
        .nav-link::after {
            content: ''; position: absolute; left: 0; bottom: -4px;
            width: 100%; height: 1px; background: var(--heading);
            transform: scaleX(0); transform-origin: right; transition: transform .3s ease;
        }
        .nav-link:hover::after, .nav-link.active::after {
            transform: scaleX(1); transform-origin: left;
        }

        /* Force dark theme style on transparent navbar (over dark hero) in light mode */
        :root[data-theme="light"] .navbar:not(.scrolled) .nav-link { color: rgba(255,255,255,0.7); }
        :root[data-theme="light"] .navbar:not(.scrolled) .nav-link:hover { color: #FFFFFF; }
        :root[data-theme="light"] .navbar:not(.scrolled) .nav-link.active { color: #FFFFFF; }
        :root[data-theme="light"] .navbar:not(.scrolled) .nav-link::after { background: #FFFFFF; }
        :root[data-theme="light"] .navbar:not(.scrolled) #theme-toggle { color: rgba(255,255,255,0.7); border-color: rgba(255,255,255,0.3); }
        :root[data-theme="light"] .navbar:not(.scrolled) #theme-toggle:hover { color: #FFFFFF; border-color: rgba(255,255,255,0.5); }
        :root[data-theme="light"] .navbar:not(.scrolled) #burger { color: #FFFFFF; }
        :root[data-theme="light"] .navbar:not(.scrolled) span[style*="color:var(--dim)"] { color: rgba(255,255,255,0.7) !important; }

        /* ── BUTTONS ──────────────────────────────────── */
        .btn-primary {
            display: inline-flex; align-items: center; justify-content: center; gap: .5rem; min-height: 48px;
            background: #FFFFFF; color: #0A0A0A;
            font-family: 'Anton', sans-serif; font-size: 1rem; letter-spacing: .06em;
            text-transform: uppercase; text-decoration: none;
            padding: 1rem 2.5rem; border-radius: 9999px;
            border: none; cursor: pointer;
            transition: transform .25s, box-shadow .25s, background .25s, color .25s;
        }
        .btn-primary:hover {
            transform: translateY(-3px);
            background: var(--primary); color: #FFFFFF;
            box-shadow: 0 12px 40px rgba(92,168,245,.25);
        }
        :root[data-theme="light"] .btn-primary {
            background: #090909; color: #FFFFFF;
        }
        :root[data-theme="light"] .btn-primary:hover {
            background: var(--primary);
            box-shadow: 0 12px 40px rgba(92,168,245,.3);
        }

        .btn-outline {
            display: inline-flex; align-items: center; justify-content: center; gap: .5rem; min-height: 48px;
            background: transparent; color: var(--text);
            font-family: 'Inter', sans-serif; font-size: .85rem; font-weight: 700;
            letter-spacing: .12em; text-transform: uppercase; text-decoration: none;
            padding: .9rem 2rem; border-radius: 9999px;
            border: 1px solid var(--border);
            cursor: pointer; transition: border-color .25s, background .25s;
        }
        .btn-outline:hover {
            border-color: var(--primary); background: rgba(92,168,245,.08);
        }
        :root[data-theme="light"] .btn-outline {
            border-color: var(--border);
        }
        :root[data-theme="light"] .btn-outline:hover {
            border-color: var(--primary); background: rgba(92,168,245,.08);
        }

        /* ── SECTION TAGS ─────────────────────────────── */
        .sec-tag {
            display: inline-block;
            font-size: .7rem; font-weight: 700; letter-spacing: .18em;
            text-transform: uppercase; color: var(--dim);
            padding: .35rem .9rem; border-radius: 9999px;
            border: 1px solid var(--border);
            background: rgba(255,255,255,.04);
            margin-bottom: 1.25rem;
        }
        :root[data-theme="light"] .sec-tag {
            background: rgba(0,0,0,.04);
            color: var(--heading);
        }

        /* ── HEADINGS ─────────────────────────────────── */
        .heading-xl {
            font-family: 'Anton', sans-serif;
            font-size: clamp(3.5rem, 9vw, 8.5rem);
            line-height: .9; letter-spacing: .01em;
            text-transform: uppercase; color: var(--heading);
        }
        .heading-lg {
            font-family: 'Anton', sans-serif;
            font-size: clamp(2.5rem, 5vw, 5rem);
            line-height: .92; letter-spacing: .01em;
            text-transform: uppercase; color: var(--heading);
        }
        .heading-md {
            font-family: 'Anton', sans-serif;
            font-size: clamp(1.6rem, 3vw, 2.4rem);
            line-height: 1; letter-spacing: .03em;
            text-transform: uppercase; color: var(--heading);
        }
        .stroke-text {
            -webkit-text-stroke: 2px var(--heading);
            color: transparent;
        }
        :root[data-theme="light"] .stroke-text {
            -webkit-text-stroke: 2px var(--heading);
        }

        /* ── HERO ─────────────────────────────────────── */
        #hero {
            position: relative; min-height: 100vh; height: auto;
            display: flex; align-items: flex-end;
            overflow: hidden;
            padding-top: 120px; box-sizing: border-box;
        }
        .hero-bg {
            position: absolute; inset: 0; z-index: 0;
            background-image: url("{{ asset('compro/img/ndehero.webp') }}");
            background-size: cover; background-position: center top;
            will-change: transform;
        }
        .hero-overlay {
            position: absolute; inset: 0; z-index: 1;
            background: 
                radial-gradient(circle at 75% 20%, rgba(92,168,245,0.2) 0%, transparent 35%),
                radial-gradient(circle at 15% 40%, rgba(255,255,255,0.08) 0%, transparent 25%),
                linear-gradient(to bottom, rgba(10,10,10,0.3) 0%, rgba(10,10,10,0.7) 60%, var(--bg) 100%);
        }
        .hero-content {
            position: relative; z-index: 2;
            width: 100%; max-width: 1280px; margin: 0 auto;
            padding: 0 2rem 6.5rem; /* Reduced bottom padding slightly for better small screen fit */
        }
        
        /* Force dark theme aesthetics on hero content regardless of theme */
        :root[data-theme="light"] .hero-content .heading-xl { color: #FFFFFF; }
        :root[data-theme="light"] .hero-content .stroke-text { -webkit-text-stroke: 2px #FFFFFF; color: transparent; }
        :root[data-theme="light"] .hero-content p { color: rgba(255,255,255,0.85) !important; }
        :root[data-theme="light"] .hero-content .sec-tag { color: #FFFFFF; background: rgba(255,255,255,.1); border-color: rgba(255,255,255,.2); }
        :root[data-theme="light"] .hero-content .btn-outline { color: #FFFFFF; border-color: rgba(255,255,255,.4); }
        :root[data-theme="light"] .hero-content .btn-outline:hover { border-color: var(--primary); background: rgba(92,168,245,.08); }
        :root[data-theme="light"] .hero-content span { color: #FFFFFF !important; }
        :root[data-theme="light"] .hero-content span[style*="color:var(--dim)"] { color: rgba(255,255,255,0.7) !important; }

        /* ── MARQUEE ──────────────────────────────────── */
        .marquee-wrap {
            width: 100%; overflow: hidden;
            background: var(--primary); padding: .85rem 0;
            transform: rotate(-1.2deg) scaleX(1.04);
            box-shadow: 0 8px 32px rgba(92,168,245,.25);
        }
        .marquee-track {
            display: inline-flex; gap: 0;
            animation: marquee 25s linear infinite;
            white-space: nowrap;
        }
        .marquee-track span {
            font-family: 'Anton', sans-serif;
            font-size: 1.1rem; letter-spacing: .08em;
            text-transform: uppercase; color: #0A0906;
            padding: 0 2rem;
        }
        @keyframes marquee {
            from { transform: translateX(0); }
            to   { transform: translateX(-50%); }
        }

        /* ── STATS ────────────────────────────────────── */
        .stat-num {
            font-family: 'Anton', sans-serif;
            font-size: clamp(2.5rem, 5vw, 4rem);
            letter-spacing: .02em; color: var(--heading); line-height: 1;
        }

        /* ── METHOD / PARALLAX SECTIONS ──────────────── */
        .parallax-section {
            position: relative; overflow: hidden;
            min-height: 80vh;
        }
        .parallax-bg {
            position: absolute; inset: 0; z-index: 0;
            background-size: cover; background-position: center;
            will-change: transform;
        }
        .parallax-overlay {
            position: absolute; inset: 0; z-index: 1;
            background: linear-gradient(135deg, rgba(10,10,10,.94) 0%, rgba(10,10,10,.82) 100%);
        }
        :root[data-theme="light"] .parallax-overlay {
            background: rgba(255,255,255,.85);
        }

        /* ── METHOD STEPS ─────────────────────────────── */
        .step-num {
            font-family: 'Anton', sans-serif;
            font-size: 6rem; line-height: 1; opacity: .12;
            color: var(--primary); letter-spacing: -.02em;
            position: absolute; top: -1.5rem; left: -1rem;
        }
        :root[data-theme="light"] .step-num {
            opacity: .25;
        }

        /* ── FEATURE NUMBERS ──────────────────────────── */
        .feature-num {
            position: absolute; top: 1.5rem; right: 1.5rem;
            font-family: 'Anton', sans-serif; font-size: 4rem;
            color: var(--primary); opacity: 0.05; line-height: 1;
        }
        :root[data-theme="light"] .feature-num {
            opacity: 0.15;
        }

        /* ── GLASS CARDS ──────────────────────────────── */
        .glass-card {
            background: rgba(255,255,255,.02);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 1.75rem;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            transition: transform .35s ease, border-color .35s ease, box-shadow .35s ease;
        }
        .glass-card:hover {
            transform: translateY(-6px);
            border-color: rgba(255,255,255,.25);
            box-shadow: 0 20px 40px rgba(0,0,0,.35);
        }
        :root[data-theme="light"] .glass-card {
            background: rgba(255,255,255,.9);
            border-color: rgba(15,23,42,.08);
            box-shadow: 0 10px 40px rgba(0,0,0,.05);
        }
        :root[data-theme="light"] .glass-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 40px rgba(15,23,42,.12);
            border-color: rgba(15,23,42,.2);
        }

        /* ── PRICING CARDS ────────────────────────────── */
        .pricing-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; align-items: start;
        }
        @media (max-width: 768px) {
            .pricing-grid {
                display: flex; overflow-x: auto; scroll-snap-type: x mandatory; padding-bottom: 2rem; -webkit-overflow-scrolling: touch;
                margin-inline: -2rem; padding-inline: 2rem;
            }
            .pricing-grid::-webkit-scrollbar { display: none; }
            .pricing-grid { -ms-overflow-style: none; scrollbar-width: none; }
            .pricing-grid > .pricing-card { min-width: 85vw; scroll-snap-align: center; flex-shrink: 0; }
        }

        .pricing-card {
            background: var(--surface);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 1.75rem; position: relative;
            display: flex; flex-direction: column;
            transition: transform .35s, box-shadow .35s, border-color .35s;
        }
        .pricing-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 24px 60px rgba(0,0,0,.5);
            border-color: rgba(255,255,255,.2);
        }
        .pricing-card.featured {
            border: 2px solid var(--primary);
            box-shadow: 0 0 60px rgba(77,166,255,.1);
        }
        .pricing-card.featured:hover {
            box-shadow: 0 24px 60px rgba(77,166,255,.15);
        }
        :root[data-theme="light"] .pricing-card {
            border-color: rgba(15,23,42,.1);
            box-shadow: 0 4px 20px rgba(15,23,42,.06);
        }
        :root[data-theme="light"] .pricing-card.featured {
            border-color: var(--primary);
        }
        
        /* ── MEET NDE ─────────────────────────────────── */
        .meet-nde-grid { display: grid; grid-template-columns: 1fr 1fr; min-height: 85vh; background: var(--surface); }
        .meet-nde-img { position: relative; min-height: 400px; }
        .meet-nde-img img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; filter: grayscale(100%) contrast(1.1); opacity: .85; }
        .meet-nde-content { display: flex; flex-direction: column; justify-content: center; padding: 6rem 5rem; }
        @media (max-width: 992px) {
            .meet-nde-grid { grid-template-columns: 1fr; }
            .meet-nde-content { padding: 5rem 2rem; }
        }

        .ticket-title {
            font-family: 'Anton', sans-serif; font-size: 1.5rem;
            letter-spacing: .08em; color: var(--heading); text-transform: uppercase;
        }
        .ticket-price-wrap { display: flex; align-items: flex-start; margin-top: .75rem; margin-bottom: 2.5rem; }
        .ticket-price {
            font-family: 'Anton', sans-serif; font-size: 4rem;
            line-height: .9; letter-spacing: -.02em; color: var(--heading);
        }
        .ticket-price-currency {
            font-family: 'Inter', sans-serif; font-size: 1rem; font-weight: 700;
            margin-right: .25rem; margin-top: .5rem; color: var(--dim);
        }
        .ticket-price-zeros {
            font-family: 'Anton', sans-serif; font-size: 1.75rem; line-height: 1;
            color: var(--dim); margin-top: .25rem; letter-spacing: .02em;
        }

        /* ── REVEAL ANIMATIONS ────────────────────────── */
        .reveal {
            opacity: 0; transform: translateY(40px);
            transition: opacity .7s ease, transform .7s ease;
        }
        .reveal.visible { opacity: 1; transform: translateY(0); }
        .reveal-delay-1 { transition-delay: .1s; }
        .reveal-delay-2 { transition-delay: .2s; }
        .reveal-delay-3 { transition-delay: .3s; }
        .reveal-delay-4 { transition-delay: .45s; }

        /* ── VIDEO SECTION ────────────────────────────── */
        .video-section {
            position: relative; overflow: hidden;
            background-image: url('https://images.unsplash.com/photo-1516924962500-2b4b3b99ea02?q=80&w=2000&auto=format&fit=crop');
            background-size: cover; background-position: center;
            background-attachment: fixed;
        }
        @media (max-width: 768px) {
            .video-section { padding: 4rem 1rem !important; }
        }
        .video-section::before {
            content: ''; position: absolute; inset: 0;
            background: rgba(10,9,6,.82);
        }
        :root[data-theme="light"] .video-section::before {
            background: rgba(255,255,255,.85);
        }

        /* ── CTA SECTION ──────────────────────────────── */
        .cta-section {
            position: relative; overflow: hidden;
            background: var(--surface);
        }
        .cta-glow {
            position: absolute; width: 60vw; height: 60vw;
            background: var(--primary); border-radius: 50%;
            filter: blur(120px); opacity: .15;
            top: 50%; left: 50%; transform: translate(-50%,-50%);
            pointer-events: none;
        }

        /* ── DIVIDER ──────────────────────────────────── */
        .section-divider {
            height: 1px; background: linear-gradient(90deg, transparent, var(--border), transparent);
            border: none; margin: 0;
        }

        /* ── MOBILE MENU ──────────────────────────────── */
        #mobile-menu {
            position: fixed; inset: 0; z-index: 90;
            background: var(--bg); display: none;
            flex-direction: column; align-items: center; justify-content: center; gap: 2.5rem;
            backdrop-filter: blur(20px);
        }
        #mobile-menu.open { display: flex; }

        /* ── LIGHT MODE TEXT OVERRIDES ────────────────── */
        :root[data-theme="light"] .text-cream   { color: var(--heading) !important; }
        :root[data-theme="light"] .text-dim     { color: var(--dim) !important; }

        /* ── FAQ ──────────────────────────────────────── */
        .faq-item { transition: background .3s, border-color .3s; }
        :root[data-theme="light"] .faq-item:hover { background: #F5F8FF; border-color: var(--primary); }
        .faq-body { max-height: 0; overflow: hidden; transition: max-height .35s ease, opacity .35s, margin-top .35s; opacity: 0; }
        .faq-item.open .faq-body { max-height: 300px; opacity: 1; margin-top: 1rem; }
        .faq-icon-plus { transition: transform .3s; }
        .faq-item.open .faq-icon-plus { transform: rotate(45deg); }

        /* ── ANIMATIONS ───────────────────────────────── */
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(92,168,245,0.7); }
            70% { box-shadow: 0 0 0 20px rgba(92,168,245,0); }
            100% { box-shadow: 0 0 0 0 rgba(92,168,245,0); }
        }
        .play-btn-pulse { animation: pulse 2s infinite; }

    </style>
</head>
<body>

@php
    $currentUser    = auth()->user();
    $isLoggedIn     = (bool) $currentUser;
    $lmsUrl         = route('lms.entry');
    $lessonId       = $firstLesson?->id ?? 1;
@endphp

<!-- ══════════════════════════════════════════════════════
     NAVBAR
══════════════════════════════════════════════════════ -->
<nav class="navbar" id="navbar">
    <a href="#" aria-label="Home" style="display:flex; align-items:flex-end; gap: .75rem; text-decoration:none; flex: 1;">
        <div style="position:relative; transition: transform .4s;" id="nav-logo-wrap">
            <img src="{{ asset('compro/img/ndelogo.png') }}"    alt="NDE" class="h-12 nav-logo-dark">
            <img src="{{ asset('compro/img/nde_logo_light.png') }}" alt="NDE" class="h-12 nav-logo-light">
        </div>
        <span style="font-family:'Inter',sans-serif; font-size:.65rem; font-weight:600; letter-spacing:.1em; color:var(--dim); padding-bottom:.4rem; text-transform:uppercase; white-space:nowrap; display:none; @media(min-width: 1024px) { display:block; }">GuitarClassByNde</span>
    </a>

    <div class="hidden md:flex items-center justify-center gap-[3.25rem]">
        <a href="#method"  class="nav-link">Method</a>
        <a href="#preview" class="nav-link">Preview</a>
        <a href="#pricing" class="nav-link">Pricing</a>
    </div>

    <div class="hidden md:flex items-center justify-end gap-[1.75rem]" style="flex: 1;">
        <button id="theme-toggle" class="w-9 h-9 rounded-full border border-gray-700 flex items-center justify-center text-gray-400 hover:text-white hover:border-gray-500 transition-all" aria-label="Toggle theme">
            <svg id="icon-moon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
            <svg id="icon-sun"  class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/></svg>
        </button>
        <a href="{{ $isLoggedIn ? $lmsUrl : route('login') }}" class="nav-link">Login</a>
        @if(!$isLoggedIn)
            <a href="{{ url('/registerclass') }}" class="btn-primary" style="padding: 0 1.25rem; min-height: 44px; font-size: .85rem; border-radius: 18px;">Start Learning</a>
        @endif
    </div>

    <button id="burger" class="md:hidden text-cream" aria-label="Menu">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
    </button>
</nav>

<!-- Mobile Menu -->
<div id="mobile-menu">
    <a href="#method"  class="heading-md" onclick="closeMobile()">Method</a>
    <a href="#preview" class="heading-md" onclick="closeMobile()">Preview</a>
    <a href="#pricing" class="heading-md" onclick="closeMobile()">Pricing</a>
    <a href="{{ $isLoggedIn ? $lmsUrl : route('login') }}" class="heading-md" onclick="closeMobile()">Login</a>
    @if(!$isLoggedIn)
        <a href="{{ url('/registerclass') }}" class="btn-primary text-xl px-10 py-4" onclick="closeMobile()">Join Now</a>
    @endif
</div>

<!-- ══════════════════════════════════════════════════════
     HERO — PARALLAX
══════════════════════════════════════════════════════ -->
<section id="hero">
    <div class="hero-bg" id="hero-bg"></div>
    <div class="hero-overlay"></div>

    <div class="hero-content">
        <div class="reveal" style="max-width:900px;">
            <span class="sec-tag reveal">Why Nde Guitar Class</span>
            <h1 class="heading-xl mt-3" style="line-height:.88;">
                Learn Guitar.<br>
                <span class="stroke-text">The Real Way.</span>
            </h1>
            <p style="color:var(--dim); font-size:1.2rem; max-width:520px; margin:1.75rem 0 2.5rem; line-height:1.65; font-weight:400;">
                Lifetime access to HD videos plus direct 1-on-1 coaching with the instructor. Not just tutorials, but a proper learning system.
            </p>
            <div style="display:flex; gap:1rem; flex-wrap:wrap;">
                <a href="{{ $isLoggedIn ? $lmsUrl : url('/registerclass') }}" class="btn-primary">
                    {{ $isLoggedIn ? 'Enter LMS' : 'Get Started' }}
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                <a href="#preview" class="btn-outline">Watch Preview</a>
            </div>
            
            <!-- Social Proof -->
            <div class="reveal reveal-delay-2" style="margin-top: 3.5rem; display: flex; gap: 2rem; align-items: center; flex-wrap: wrap; border-top: 1px solid var(--border); padding-top: 1.5rem;">
                <div style="display: flex; align-items: center; gap: .75rem;">
                    <div style="display: flex; margin-right: -8px;">
                        <img src="https://i.pravatar.cc/100?img=12" alt="Student" style="width:36px; height:36px; border-radius:50%; border:2px solid var(--bg); margin-left:-12px;" />
                        <img src="https://i.pravatar.cc/100?img=33" alt="Student" style="width:36px; height:36px; border-radius:50%; border:2px solid var(--bg); margin-left:-12px;" />
                        <img src="https://i.pravatar.cc/100?img=47" alt="Student" style="width:36px; height:36px; border-radius:50%; border:2px solid var(--bg); margin-left:-12px;" />
                    </div>
                    <span style="font-size: .85rem; font-weight: 600; color: var(--text);">1,200+ <span style="color:var(--dim); font-weight:400;">active students</span></span>
                </div>
                <div style="width: 1px; height: 24px; background: var(--border); display: none; @media(min-width: 768px) { display: block; }"></div>
                <div style="display: flex; align-items: center; gap: .5rem;">
                    <span style="color: var(--warning) !important; font-size:1.1rem; letter-spacing: 2px;">★★★★★</span>
                    <span style="font-size: .85rem; font-weight: 600; color: var(--text);">4.9/5 <span style="color:var(--dim); font-weight:400;">rating</span></span>
                </div>
                <div style="width: 1px; height: 24px; background: var(--border); display: none; @media(min-width: 768px) { display: block; }"></div>
                <div style="display: flex; align-items: center;">
                    <span style="font-size: .85rem; font-weight: 600; color: var(--text);">100+ <span style="color:var(--dim); font-weight:400;">video lessons</span></span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════════════════
     MARQUEE
══════════════════════════════════════════════════════ -->
<div style="margin: 3rem 0; position:relative; z-index:10;">
    <div class="marquee-wrap">
        <div class="marquee-track" aria-hidden="true">
            <span>Lifetime Access</span><span>✦</span>
            <span>Private HD Videos</span><span>✦</span>
            <span>Coaching 1-on-1</span><span>✦</span>
            <span>Auto Enrollment</span><span>✦</span>
            <span>Lifetime Access</span><span>✦</span>
            <span>Private HD Videos</span><span>✦</span>
            <span>Coaching 1-on-1</span><span>✦</span>
            <span>Auto Enrollment</span><span>✦</span>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════
     STATS
══════════════════════════════════════════════════════ -->
<section style="padding: 5rem 2rem; max-width:1100px; margin:0 auto;">
    <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:3rem;">
        <div class="reveal text-center" style="display:flex; flex-direction:column; align-items:center;">
            <div class="stat-num" style="font-size: 5.5rem;">3+</div>
            <div style="color:var(--dim); font-size:.85rem; letter-spacing:.15em; text-transform:uppercase; font-weight:700; margin-top:1rem;">PACKAGES</div>
            <div style="width:40px; height:2px; background:var(--primary); margin-top:1.25rem;"></div>
        </div>
        <div class="reveal reveal-delay-1 text-center" style="display:flex; flex-direction:column; align-items:center;">
            <div class="stat-num" style="font-size: 5.5rem;">HD</div>
            <div style="color:var(--dim); font-size:.85rem; letter-spacing:.15em; text-transform:uppercase; font-weight:700; margin-top:1rem;">VIDEOS</div>
            <div style="width:40px; height:2px; background:var(--primary); margin-top:1.25rem;"></div>
        </div>
        <div class="reveal reveal-delay-2 text-center" style="display:flex; flex-direction:column; align-items:center;">
            <div class="stat-num" style="font-size: 5.5rem;">1:1</div>
            <div style="color:var(--dim); font-size:.85rem; letter-spacing:.15em; text-transform:uppercase; font-weight:700; margin-top:1rem;">COACHING</div>
            <div style="width:40px; height:2px; background:var(--primary); margin-top:1.25rem;"></div>
        </div>
        <div class="reveal reveal-delay-3 text-center" style="display:flex; flex-direction:column; align-items:center;">
            <div class="stat-num" style="font-size: 5.5rem;">∞</div>
            <div style="color:var(--dim); font-size:.85rem; letter-spacing:.15em; text-transform:uppercase; font-weight:700; margin-top:1rem;">LIFETIME</div>
            <div style="width:40px; height:2px; background:var(--primary); margin-top:1.25rem;"></div>
        </div>
    </div>
</section>

<hr class="section-divider">

<!-- ══════════════════════════════════════════════════════
     METHOD — PARALLAX SECTION
══════════════════════════════════════════════════════ -->
<section id="method" class="parallax-section" style="min-height:auto;">
    <div class="parallax-bg" id="method-bg"
         style="background-image: url('https://images.unsplash.com/photo-1601342630314-8427c38bf5e6?q=80&w=2000&auto=format&fit=crop'); background-attachment:fixed;">
    </div>
    <div class="parallax-overlay"></div>

    <div style="position:relative; z-index:2; max-width:1200px; margin:0 auto; padding:7rem 2rem;">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:5rem; align-items:start;" class="method-grid">
            <!-- Left: Text -->
            <div>
                <span class="sec-tag reveal">Why Nde Guitar Class</span>
                <h2 class="heading-lg reveal reveal-delay-1" style="margin-top:.5rem; color:var(--heading);">
                    A Different <br><span class="stroke-text">System.</span>
                </h2>
                <p class="reveal reveal-delay-2" style="color:var(--dim); font-size:1.05rem; line-height:1.7; margin-top:1.5rem; max-width:440px;">
                    Not just watching videos and getting assignments. Here you can directly book coaching calls, discuss techniques, and get real-time feedback from the instructor.
                </p>
            </div>

            <!-- Right: Steps -->
            <div style="display:flex; flex-direction:column; gap:2.5rem;">
                <div class="reveal reveal-delay-1" style="position:relative; padding-left:1.5rem; border-left:2px solid rgba(92,168,245,.3);">
                    <div class="step-num">01</div>
                    <h3 class="heading-md" style="font-size:1.6rem; position:relative;">Choose Package</h3>
                    <p style="color:var(--dim); font-size:.95rem; line-height:1.65; margin-top:.5rem; position:relative;">Choose the package that fits you — from Beginner to those that include a Coaching Ticket for a 1-on-1 session.</p>
                </div>
                <div class="reveal reveal-delay-2" style="position:relative; padding-left:1.5rem; border-left:2px solid rgba(92,168,245,.3);">
                    <div class="step-num">02</div>
                    <h3 class="heading-md" style="font-size:1.6rem; position:relative;">Access HD Videos</h3>
                    <p style="color:var(--dim); font-size:.95rem; line-height:1.65; margin-top:.5rem; position:relative;">All videos are streamed via Bunny CDN — private, fast, and can be rewatched forever from anywhere.</p>
                </div>
                <div class="reveal reveal-delay-3" style="position:relative; padding-left:1.5rem; border-left:2px solid rgba(92,168,245,.3);">
                    <div class="step-num">03</div>
                    <h3 class="heading-md" style="font-size:1.6rem; position:relative;">Book Coaching Call</h3>
                    <p style="color:var(--dim); font-size:.95rem; line-height:1.65; margin-top:.5rem; position:relative;">Use your Coaching Ticket to book a direct video call session with the instructor, powered by Twilio WebRTC.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<hr class="section-divider">

<!-- ══════════════════════════════════════════════════════
     VIDEO PREVIEW — PARALLAX BG
══════════════════════════════════════════════════════ -->
<section id="preview" class="video-section" style="padding:7rem 2rem;">
    <div style="position:relative; z-index:1; max-width:900px; margin:0 auto; text-align:center;">
        <span class="sec-tag reveal">Watch First</span>
        <h2 class="heading-lg reveal reveal-delay-1" style="margin-top:.5rem;">Course <span class="stroke-text">Preview.</span></h2>
        <p class="reveal reveal-delay-2" style="color:var(--dim); font-size:1.05rem; margin:1.25rem 0 3rem; line-height:1.65;">Click to play an exclusive preview of the course content.</p>

        <!-- Video Card -->
        <div class="reveal reveal-delay-3" style="position:relative; border-radius:1.75rem; overflow:hidden; aspect-ratio:16/9; cursor:pointer; border:1px solid var(--border);" id="promo-preview">
            <img id="promo-preview-fallback"
                 src="{{ $promo_thumbnail_url ?: 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?q=80&w=1600&auto=format&fit=crop' }}"
                 alt="Preview" style="width:100%;height:100%;object-fit:cover;">
            <!-- Play overlay -->
            <div id="promo-preview-loading" style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(10,10,10,.65);">
                <div class="play-btn-pulse" style="width:80px;height:80px;border-radius:50%;background:rgba(92,168,245,.9);display:flex;align-items:center;justify-content:center;transition:transform .25s,box-shadow .25s;"
                     onmouseover="this.style.transform='scale(1.1)';this.classList.remove('play-btn-pulse');this.style.boxShadow='0 0 40px rgba(92,168,245,.6)'"
                     onmouseout="this.style.transform='scale(1)';this.classList.add('play-btn-pulse');this.style.boxShadow='none'">
                    <svg width="28" height="28" fill="white" viewBox="0 0 24 24" style="margin-left:4px;"><path d="M8 5v14l11-7z"/></svg>
                </div>
            </div>
            <video id="promo-preview-video" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;display:none;" playsinline muted loop controls></video>
        </div>
    </div>
</section>

<hr class="section-divider">

<!-- ══════════════════════════════════════════════════════
     MEET NDE (PATTERN BREAKER)
══════════════════════════════════════════════════════ -->
<section id="meet" class="meet-nde-grid">
    <div class="meet-nde-img">
        <img src="https://images.unsplash.com/photo-1510915361894-db8b60106cb1?q=80&w=2000&auto=format&fit=crop" alt="Instructor">
    </div>
    <div class="meet-nde-content">
        <span class="sec-tag reveal" style="align-self:flex-start;">Meet The Instructor</span>
        <h2 class="heading-lg reveal reveal-delay-1" style="margin-top:1rem; font-size:clamp(2.5rem, 5vw, 4.5rem); letter-spacing:-0.02em; line-height:.95;">
            Learn from someone who <br><span class="stroke-text">actually plays.</span>
        </h2>
        <div style="width:60px; height:2px; background:var(--primary); margin:2rem 0;" class="reveal reveal-delay-2"></div>
        <p class="reveal reveal-delay-2" style="color:var(--dim); font-size:1.15rem; line-height:1.75; max-width:500px; margin-bottom:1.5rem;">
            Hi, I'm Nde. I've been playing and teaching guitar for over a decade. I created this system because I was tired of seeing students get stuck with generic tutorials that teach songs, but never teach <em>how</em> to actually play the guitar.
        </p>
        <p class="reveal reveal-delay-3" style="color:var(--text); font-size:1.15rem; line-height:1.75; max-width:500px; font-weight:600;">
            My goal is simple: to make you a self-sufficient guitarist who understands the fretboard, rhythm, and theory, all while having fun.
        </p>
    </div>
</section>

<hr class="section-divider">

<!-- ══════════════════════════════════════════════════════
     FEATURES
══════════════════════════════════════════════════════ -->
<section id="fitur" style="padding:7rem 2rem;">
    <div style="max-width:1200px; margin:0 auto;">
        <div style="text-align:center; margin-bottom:4rem;">
            <span class="sec-tag reveal">What You Get</span>
            <h2 class="heading-lg reveal reveal-delay-1">Inside <span class="stroke-text">the Class.</span></h2>
        </div>
        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); gap:1.5rem;">
            <!-- F1 -->
            <div class="glass-card reveal p-8" style="position:relative; overflow:hidden;">
                <div class="feature-num">01</div>
                <div style="width:3.5rem;height:3.5rem;border-radius:1rem;background:rgba(92,168,245,.12);border:1px solid rgba(92,168,245,.25);display:flex;align-items:center;justify-content:center;margin-bottom:1.5rem; position:relative; z-index:2;">
                    <svg width="22" height="22" fill="none" stroke="var(--primary)" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                </div>
                <h3 style="font-family:'Anton',sans-serif;font-size:1.5rem;color:var(--heading);letter-spacing:.04em;margin-bottom:.75rem; position:relative; z-index:2;">Unlimited HD Video</h3>
                <p style="color:var(--dim);line-height:1.65;font-size:.9rem; position:relative; z-index:2;">Video lessons are streamed via <strong style="color:var(--text);">Bunny CDN</strong>. Private, fast, and can be accessed for a lifetime without limits.</p>
            </div>
            <!-- F2 -->
            <div class="glass-card reveal reveal-delay-1 p-8" style="position:relative; overflow:hidden;">
                <div class="feature-num">02</div>
                <div style="width:3.5rem;height:3.5rem;border-radius:1rem;background:rgba(92,168,245,.12);border:1px solid rgba(92,168,245,.25);display:flex;align-items:center;justify-content:center;margin-bottom:1.5rem; position:relative; z-index:2;">
                    <svg width="22" height="22" fill="none" stroke="var(--primary)" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
                </div>
                <h3 style="font-family:'Anton',sans-serif;font-size:1.5rem;color:var(--heading);letter-spacing:.04em;margin-bottom:.75rem; position:relative; z-index:2;">Live 1-on-1 Coaching</h3>
                <p style="color:var(--dim);line-height:1.65;font-size:.9rem; position:relative; z-index:2;">Book a direct video call session with the instructor. Ask about techniques, ask for feedback, or discuss progress — in real-time via <strong style="color:var(--text);">Twilio WebRTC</strong>.</p>
            </div>
            <!-- F3 -->
            <div class="glass-card reveal reveal-delay-2 p-8" style="position:relative; overflow:hidden;">
                <div class="feature-num">03</div>
                <div style="width:3.5rem;height:3.5rem;border-radius:1rem;background:rgba(92,168,245,.12);border:1px solid rgba(92,168,245,.25);display:flex;align-items:center;justify-content:center;margin-bottom:1.5rem; position:relative; z-index:2;">
                    <svg width="22" height="22" fill="none" stroke="var(--primary)" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3 style="font-family:'Anton',sans-serif;font-size:1.5rem;color:var(--heading);letter-spacing:.04em;margin-bottom:.75rem; position:relative; z-index:2;">Auto Enrollment</h3>
                <p style="color:var(--dim);line-height:1.65;font-size:.9rem; position:relative; z-index:2;">Checkout integrated with <strong style="color:var(--text);">Midtrans</strong>. Pay with QRIS, Gopay, or VA — class access opens automatically.</p>
            </div>
        </div>
    </div>
</section>

<hr class="section-divider">

<!-- ══════════════════════════════════════════════════════
     PRICING
══════════════════════════════════════════════════════ -->
<section id="pricing" style="padding:7rem 2rem; background: var(--bg-alt, var(--bg));">
    <div style="max-width:1050px; margin:0 auto;">
        <div style="text-align:center; margin-bottom:4.5rem;">
            <span class="sec-tag reveal">Once in a Lifetime Investment</span>
            <h2 class="heading-lg reveal reveal-delay-1">Choose <span class="stroke-text">Package.</span></h2>
        </div>

        <div class="pricing-grid">
            @php
                $orderMap = [
                    config('coaching.coaching_package_slug','coaching-ticket') => 0,
                    'intermediate' => 1,
                    'beginner'     => 2,
                ];
                $orderedPackages = $packages->sortBy(fn($p) => $orderMap[$p->slug] ?? 99)->values();
            @endphp

            @foreach($orderedPackages as $pkg)
                @php
                    $isFeatured = ($pkg->slug ?? null) === 'intermediate';
                    $benefits   = array_filter(array_map('trim', explode("\n", $pkg->benefits ?? '')));
                    $imgSrc     = $pkg->image ? asset('storage/'.$pkg->image) : asset('pictures/'.$pkg->slug.'.jpg');
                    
                    $priceRaw = $pkg->price;
                    $priceFormatted = number_format($priceRaw, 0, '', '.');
                    $priceParts = explode('.', $priceFormatted, 2);
                    $priceMain = $priceParts[0];
                    $priceDecimals = isset($priceParts[1]) ? '.' . $priceParts[1] : '';
                @endphp

                <div class="pricing-card reveal {{ $isFeatured ? 'featured reveal-delay-1' : ($loop->last ? 'reveal-delay-2' : '') }}"
                     style="{{ $isFeatured ? 'transform:scale(1.03);' : '' }}">

                    @if($isFeatured)
                        <div style="position:absolute;top:0;left:50%;transform:translate(-50%,-50%);background:var(--primary);color:#0A0A0A;font-size:.65rem;font-weight:800;letter-spacing:.15em;padding:.4rem 1.25rem;border-radius:999px;text-transform:uppercase;z-index:10;box-shadow:0 4px 12px rgba(92,168,245,.3);">BEST VALUE</div>
                    @endif
                    <div style="height:160px; position:relative; overflow:hidden; border-top-left-radius:1.75rem; border-top-right-radius:1.75rem;">
                        <img src="{{ $imgSrc }}" alt="{{ $pkg->name }}" style="width:100%; height:100%; object-fit:cover; filter:brightness(0.85);">
                        <div style="position:absolute;inset:0;background:linear-gradient(to bottom, transparent 40%, var(--surface) 100%);"></div>
                    </div>

                    <div style="padding: 1.25rem 1.75rem 1.5rem; text-align:left; display:flex; flex-direction:column; flex:1;">
                        @if($isFeatured)
                            <div style="color:var(--primary); font-size:.7rem; font-weight:800; letter-spacing:.15em; text-transform:uppercase; margin-bottom:.5rem;">RECOMMENDED</div>
                        @endif
                        <div style="font-family:'Anton',sans-serif; font-size:1.5rem; letter-spacing:.08em; color:var(--heading); text-transform:uppercase;">
                            {{ $pkg->name }}
                        </div>
                        
                        <div style="font-family:'Inter',sans-serif; font-size:2.25rem; font-weight:800; color:var(--heading); letter-spacing:-.03em; margin:.25rem 0 1.25rem;">
                            Rp {{ $priceFormatted }}
                        </div>

                        <div style="display:flex;flex-direction:column;gap:0.6rem;flex:1;margin-bottom:1.5rem; text-align:left;">
                            @foreach($benefits as $b)
                            <div style="display:flex;align-items:flex-start;gap:.6rem;font-size:.85rem;color:var(--dim);font-weight:500;">
                                <svg width="15" height="15" fill="none" stroke="{{ $isFeatured ? 'var(--primary)' : '#ef4444' }}" viewBox="0 0 24 24" stroke-width="2.5" style="flex-shrink:0;margin-top:2px;"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                <span style="line-height:1.4;">{{ $b }}</span>
                            </div>
                            @endforeach
                        </div>

                        <a href="{{ $isLoggedIn ? route('kelas.buy',$lessonId).'?package_id='.$pkg->id.'&package_qty=1' : route('register').'?package_id='.$pkg->id.'&package_qty=1' }}"
                           class="{{ $isFeatured ? 'btn-primary' : 'btn-outline' }}" style="width:100%; border-radius:18px; justify-content:center; text-transform:uppercase; font-size:.85rem; font-weight:800; letter-spacing:.1em; min-height:48px; {{ !$isFeatured ? 'border-color:var(--border); color:var(--heading);' : '' }}">
                            {{ $isFeatured ? 'Get Access Now' : 'Select Package' }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<hr class="section-divider">

<!-- ══════════════════════════════════════════════════════
     STUDENT PROGRESS
══════════════════════════════════════════════════════ -->
<section style="padding:7rem 2rem; background: var(--surface); position:relative; overflow:hidden;">
    <div style="max-width:1000px; margin:0 auto;">
        <div style="text-align:center; margin-bottom:4rem;">
            <span class="sec-tag reveal">Real Progress</span>
            <h2 class="heading-lg reveal reveal-delay-1">From Zero to <span class="stroke-text">Hero.</span></h2>
        </div>

        <div class="glass-card reveal reveal-delay-2" style="display:flex; flex-wrap:wrap; overflow:hidden; border-radius:1.75rem; padding: 0;">
            <div style="flex:1 1 300px; padding:3.5rem; border-right:1px solid var(--border); display:flex; flex-direction:column; justify-content:center;">
                <div style="display:flex; align-items:center; gap:.25rem; margin-bottom:2rem;">
                    <svg width="20" height="20" fill="#F3C969" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <svg width="20" height="20" fill="#F3C969" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <svg width="20" height="20" fill="#F3C969" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <svg width="20" height="20" fill="#F3C969" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <svg width="20" height="20" fill="#F3C969" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                </div>
                <p style="font-size:1.25rem; line-height:1.6; color:var(--heading); margin-bottom:2.5rem; font-style:italic;">
                    "I've only been learning basic mechanics for 2 weeks and getting evaluations from the instructor, and I can already play a full song without any mistakes. It's truly different from learning by myself."
                </p>
                <div style="display:flex; align-items:center; gap:1.25rem;">
                    <img src="https://i.pravatar.cc/100?img=11" alt="Aldi" style="width:64px; height:64px; border-radius:50%; border: 3px solid var(--border);">
                    <div>
                        <div style="font-weight:700; font-size:1.1rem; color:var(--heading);">Aldi, 20 years old</div>
                        <div style="font-size:.9rem; color:var(--dim);">College Student</div>
                    </div>
                </div>
            </div>
            
            <div style="flex:1 1 300px; background:rgba(0,0,0,0.2); padding:3.5rem; display:flex; flex-direction:column; justify-content:center;">
                <div style="display:flex; flex-direction:column; gap:2.5rem;">
                    <div style="position:relative; padding-left: 2rem;">
                        <div style="position:absolute; left:0; top:0.25rem; width:10px; height:10px; border-radius:50%; background:var(--dim);"></div>
                        <div style="position:absolute; left:4px; top:1rem; bottom:-2.5rem; width:2px; background:var(--border);"></div>
                        <div style="font-family:'Anton',sans-serif; color:var(--dim); letter-spacing:.08em; font-size:.85rem; margin-bottom:.35rem; text-transform:uppercase;">Week 1</div>
                        <h4 style="font-size:1.05rem; font-weight:700; color:var(--heading); margin:0 0 .35rem;">Couldn't switch chords</h4>
                        <p style="font-size:.9rem; color:var(--dim); margin:0; line-height:1.5;">Fingers hurt often, slow transitions, and messy rhythm.</p>
                    </div>
                    
                    <div style="position:relative; padding-left: 2rem;">
                        <div style="position:absolute; left:-1px; top:0.25rem; width:12px; height:12px; border-radius:50%; background:var(--success); box-shadow: 0 0 12px rgba(168,214,58,0.6);"></div>
                        <div style="font-family:'Anton',sans-serif; color:var(--success); letter-spacing:.08em; font-size:.85rem; margin-bottom:.35rem; text-transform:uppercase;">Week 6</div>
                        <h4 style="font-size:1.05rem; font-weight:700; color:var(--heading); margin:0 0 .35rem;">Can play full songs</h4>
                        <p style="font-size:.9rem; color:var(--dim); margin:0; line-height:1.5;">Reflexive chord transitions, on-tempo strumming, solid basic techniques.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<hr class="section-divider">

<!-- ══════════════════════════════════════════════════════
     FAQ
══════════════════════════════════════════════════════ -->
<section style="padding:7rem 2rem; background: var(--bg-alt2, var(--bg));">
    <div style="max-width:720px;margin:0 auto;">
        <div style="text-align:center;margin-bottom:3.5rem;">
            <span class="sec-tag reveal">FAQ</span>
            <h2 class="heading-lg reveal reveal-delay-1">Have <span class="stroke-text">Questions?</span></h2>
        </div>
        <div style="display:flex;flex-direction:column;gap:.75rem;">
            @forelse($faq_items ?? [] as $faq)
            <div class="glass-card faq-item reveal" style="padding:1.5rem;cursor:pointer;" onclick="this.classList.toggle('open')">
                <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;">
                    <h4 style="font-size:1rem;font-weight:600;color:var(--heading);margin:0;font-family:'Inter',sans-serif;text-transform:none;letter-spacing:0;">{{ $faq->question }}</h4>
                    <div class="faq-icon-plus" style="width:2rem;height:2rem;border-radius:50%;border:1px solid var(--border);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:var(--primary);font-size:1.4rem;line-height:1;">+</div>
                </div>
                <div class="faq-body">
                    <p style="color:var(--dim);margin:0;font-size:.92rem;line-height:1.7;">{{ $faq->answer }}</p>
                </div>
            </div>
            @empty
            <p style="color:var(--dim);text-align:center;">No FAQs available yet.</p>
            @endforelse
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════════════════
     CTA FINAL SECTION
══════════════════════════════════════════════════════ -->
<section class="cta-section" style="padding:7rem 2rem;">
    <div class="cta-glow"></div>
    <div style="position:relative;z-index:1;max-width:900px;margin:0 auto;text-align:center;">
        <span class="sec-tag reveal">Get Started</span>
        <h2 class="heading-xl reveal reveal-delay-1" style="font-size:clamp(3rem,8vw,7rem);margin:.5rem 0 1.5rem;">
            Your Guitar <br><span class="stroke-text">Awaits.</span>
        </h2>
        <p class="reveal reveal-delay-2" style="color:var(--dim);font-size:1.1rem;line-height:1.65;max-width:520px;margin:0 auto 2.5rem;">
            Sign up now and start your journey of learning guitar the right way — structured, personal, and for a lifetime.
        </p>

        <!-- Social Proof above CTA -->
        <div class="reveal reveal-delay-3" style="display:flex; justify-content:center; gap:3rem; margin-bottom: 3.5rem;">
            <div style="text-align:center;">
                <div style="font-family:'Anton',sans-serif; font-size:2.5rem; color:var(--heading); line-height:1;">1,200+</div>
                <div style="font-size:.75rem; font-weight:700; color:var(--dim); letter-spacing:.1em; text-transform:uppercase; margin-top:.5rem;">Students</div>
            </div>
            <div style="text-align:center;">
                <div style="font-family:'Anton',sans-serif; font-size:2.5rem; color:var(--heading); line-height:1;">4.9</div>
                <div style="font-size:.75rem; font-weight:700; color:var(--dim); letter-spacing:.1em; text-transform:uppercase; margin-top:.5rem;">Rating</div>
            </div>
            <div style="text-align:center;">
                <div style="font-family:'Anton',sans-serif; font-size:2.5rem; color:var(--heading); line-height:1;">100+</div>
                <div style="font-size:.75rem; font-weight:700; color:var(--dim); letter-spacing:.1em; text-transform:uppercase; margin-top:.5rem;">Lessons</div>
            </div>
        </div>

        <div class="reveal reveal-delay-4" style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
            <a href="{{ $isLoggedIn ? $lmsUrl : url('/registerclass') }}" class="btn-primary" style="font-size:1.1rem;padding:1.1rem 3rem;">
                {{ $isLoggedIn ? 'Enter LMS' : 'Join Class' }}
            </a>
            <a href="https://wa.me/+6281273796646" class="btn-outline" style="font-size:1rem;">
                Ask via WhatsApp
            </a>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════════════════
     FOOTER
══════════════════════════════════════════════════════ -->
<footer style="background:var(--surface);border-top:1px solid var(--border);padding:3.5rem 2rem 2rem;">
    <div style="max-width:1200px;margin:0 auto;">
        <div style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:2rem;padding-bottom:2rem;border-bottom:1px solid var(--border);">
            <div>
                <img src="{{ asset('compro/img/ndelogo.png') }}" alt="NDE" class="h-12 nav-logo-dark" style="margin-bottom:.75rem;">
                <img src="{{ asset('compro/img/nde_logo_light.png') }}" alt="NDE" class="h-12 nav-logo-light" style="margin-bottom:.75rem;">
                <p style="color:var(--dim);font-size:.75rem;letter-spacing:.14em;text-transform:uppercase;font-weight:600;">Zero Bullshit Guitar Lessons.</p>
            </div>
            <div style="display:flex;flex-wrap:wrap;gap:.75rem;">
                <a href="#" class="btn-outline" style="padding:.65rem 1.25rem;font-size:.75rem;">Instagram</a>
                <a href="#" class="btn-outline" style="padding:.65rem 1.25rem;font-size:.75rem;">YouTube</a>
                <a href="#" class="btn-outline" style="padding:.65rem 1.25rem;font-size:.75rem;">TikTok</a>
                <a href="#" class="btn-outline" style="padding:.65rem 1.25rem;font-size:.75rem;">Spotify Playlist</a>
                <a href="https://wa.me/+6281273796646" class="btn-outline" style="padding:.65rem 1.25rem;font-size:.75rem;">WhatsApp</a>
            </div>
        </div>
        <div style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:1rem;padding-top:1.5rem;">
            <p style="color:var(--dim);font-size:.8rem;">&copy; {{ now()->year }} Nde Official. All rights reserved.</p>
            <div style="display:flex;gap:1.5rem;">
                <a href="#" style="color:var(--dim);font-size:.8rem;text-decoration:none;hover:color:var(--heading)">Terms</a>
                <a href="#" style="color:var(--dim);font-size:.8rem;text-decoration:none;">Privacy</a>
            </div>
        </div>
    </div>
</footer>

<!-- ══════════════════════════════════════════════════════
     SCRIPTS
══════════════════════════════════════════════════════ -->
<script>
    /* ── NAVBAR SCROLL & ACTIVE STATE ──────── */
    const navbar = document.getElementById('navbar');
    const logoWrap = document.getElementById('nav-logo-wrap');
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-link[href^="#"]');

    window.addEventListener('scroll', () => {
        const scrolled = window.scrollY > 60;
        navbar.classList.toggle('scrolled', scrolled);
        if (logoWrap) logoWrap.style.transform = scrolled ? 'scale(0.85)' : 'scale(1)';

        let current = '';
        sections.forEach(sec => {
            const secTop = sec.offsetTop;
            if (window.scrollY >= secTop - 200) current = sec.getAttribute('id');
        });
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === '#' + current) {
                link.classList.add('active');
            }
        });
    }, { passive: true });

    /* ── MOBILE MENU ───────────────────────── */
    const burger = document.getElementById('burger');
    const mobileMenu = document.getElementById('mobile-menu');
    burger.addEventListener('click', () => mobileMenu.classList.toggle('open'));
    function closeMobile() { mobileMenu.classList.remove('open'); }

    /* ── THEME TOGGLE ──────────────────────── */
    (function(){
        const toggle = document.getElementById('theme-toggle');
        const moon = document.getElementById('icon-moon');
        const sun  = document.getElementById('icon-sun');
        function getTheme() {
            const m = document.cookie.match(/(?:^|; )theme=([^;]+)/);
            return m ? decodeURIComponent(m[1]) : 'dark';
        }
        function setTheme(t) {
            document.documentElement.setAttribute('data-theme', t);
            document.cookie = 'theme='+encodeURIComponent(t)+'; path=/; max-age=31536000; SameSite=Lax';
            const isLight = t === 'light';
            moon.classList.toggle('hidden', isLight);
            sun.classList.toggle('hidden', !isLight);
        }
        setTheme(getTheme());
        toggle.addEventListener('click', () => setTheme(getTheme() === 'dark' ? 'light' : 'dark'));
    })();

    /* ── PARALLAX HERO ─────────────────────── */
    const heroBg = document.getElementById('hero-bg');
    function onScroll() {
        if (!heroBg) return;
        const sy = window.scrollY;
        heroBg.style.transform = 'translateY(' + (sy * 0.45) + 'px)';
    }
    window.addEventListener('scroll', onScroll, { passive: true });

    /* ── SCROLL REVEAL ─────────────────────── */
    const revealEls = document.querySelectorAll('.reveal');
    const revealObs = new IntersectionObserver((entries) => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); revealObs.unobserve(e.target); } });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
    revealEls.forEach(el => revealObs.observe(el));

    /* ── METHOD GRID RESPONSIVE ────────────── */
    function checkMethodGrid() {
        const grid = document.querySelector('.method-grid');
        if (!grid) return;
        if (window.innerWidth < 768) {
            grid.style.gridTemplateColumns = '1fr';
            grid.style.gap = '2.5rem';
        } else {
            grid.style.gridTemplateColumns = '1fr 1fr';
            grid.style.gap = '5rem';
        }
    }
    checkMethodGrid();
    window.addEventListener('resize', checkMethodGrid);

    /* ── PROMO VIDEO ───────────────────────── */
    (function(){
        var promoUrl = null;
        var vid = document.getElementById('promo-preview-video');
        var thumb = document.getElementById('promo-preview-fallback');
        var overlay = document.getElementById('promo-preview-loading');
        var card = document.getElementById('promo-preview');

        function showFallback() {
            if (vid) { vid.style.display='none'; vid.removeAttribute('src'); try{vid.pause();}catch(e){} }
            if (thumb) thumb.style.display='block';
            if (overlay) overlay.style.display='flex';
        }

        function playStream(url) {
            if (!vid || !url) { showFallback(); return; }
            if (thumb) thumb.style.display='none';
            if (overlay) overlay.style.display='none';
            vid.style.display='block';
            var native = vid.canPlayType('application/vnd.apple.mpegurl');
            function start() { vid.src=url; vid.load(); vid.play().catch(function(){}); }
            if (native) { start(); return; }
            if (window.Hls) {
                try {
                    if (window._ph) { try{window._ph.destroy();}catch(e){} }
                    var h=new Hls(); window._ph=h;
                    h.loadSource(url); h.attachMedia(vid);
                    h.on(Hls.Events.MANIFEST_PARSED, function(){ vid.play().catch(function(){}); });
                    h.on(Hls.Events.ERROR, function(_,d){ if(d&&d.fatal) showFallback(); });
                } catch(e){ showFallback(); }
                return;
            }
            var s=document.createElement('script'); s.src='https://cdn.jsdelivr.net/npm/hls.js@latest'; s.async=true;
            s.onload=function(){ if(window.Hls) playStream(url); else showFallback(); };
            s.onerror=showFallback; document.head.appendChild(s);
        }

        fetch('{{ url("/promo-stream") }}', { headers: {'X-Requested-With':'XMLHttpRequest'} })
            .then(r => r.ok ? r.json() : null)
            .then(d => { if(d&&d.url) promoUrl=d.url; showFallback(); })
            .catch(showFallback);

        if (card) {
            card.addEventListener('click', function(){
                if (promoUrl && vid && vid.style.display==='none') { playStream(promoUrl); return; }
                if (vid && vid.paused) vid.play().catch(function(){});
            });
        }
    })();
</script>
</body>
</html>
