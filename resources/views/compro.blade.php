<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nde Guitar Class - Premium Masterclass</title>
    <meta name="description" content="Kuasai gitar dengan metode terstruktur lewat video HD, coaching 1-on-1, dan sistem belajar premium.">
    <meta name="theme-color" content="#050505">

    <script>
        (function () {
            var match = document.cookie.match(/(?:^|; )theme=([^;]+)/);
            var theme = match ? decodeURIComponent(match[1]) : 'dark';
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    },
                    colors: {
                        background: 'var(--bg)',
                        surface: 'var(--surface)',
                        surfaceHover: 'var(--surface-hover)',
                        textDim: 'var(--text-dim)',
                    }
                }
            }
        };
    </script>

    <style>
        .nav-logo-dark { display: block; }
        .nav-logo-light { display: none; }
        :root[data-theme="light"] .nav-logo-dark { display: none; }
        :root[data-theme="light"] .nav-logo-light { display: block; }

        :root {
            color-scheme: light dark;
            --bg: #050505;
            --surface: #0a0a0a;
            --surface-hover: #141414;
            --text-dim: #888888;
            --text: #e6e6e6;
            --heading: #ffffff;
            --border: rgba(255, 255, 255, 0.10);
            --nav-bg: rgba(5, 5, 5, 0.8);
        }

        :root[data-theme="light"] {
            color-scheme: light;
            --bg: #f5f5f7;
            --surface: #ffffff;
            --surface-hover: #f1f5f9;
            --text-dim: #64748b;
            --text: #0f172a;
            --heading: #0f172a;
            --border: rgba(15, 23, 42, 0.08);
            --nav-bg: rgba(245, 245, 247, 0.92);
        }

        body {
            background-color: var(--bg);
            color: var(--text);
        }

        /* Light mode Tailwind overrides */
        :root[data-theme="light"] .text-white { color: var(--heading) !important; }
        :root[data-theme="light"] .text-gray-400 { color: #64748b !important; }
        :root[data-theme="light"] .text-gray-300 { color: #475569 !important; }
        :root[data-theme="light"] .text-gray-200 { color: #334155 !important; }
        :root[data-theme="light"] .bg-black { background-color: var(--bg) !important; }
        :root[data-theme="light"] .bg-surface\/50 { background-color: rgba(255,255,255,0.85) !important; }
        :root[data-theme="light"] .bg-background\/80 { background-color: var(--nav-bg) !important; }
        :root[data-theme="light"] .border-white\/5,
        :root[data-theme="light"] .border-white\/10,
        :root[data-theme="light"] .border-white\/20 { border-color: rgba(15,23,42,0.08) !important; }
        :root[data-theme="light"] .border-y { border-color: rgba(15,23,42,0.08) !important; }
        :root[data-theme="light"] .border-t { border-color: rgba(15,23,42,0.08) !important; }

        /* Nav links */
        :root[data-theme="light"] .nav-links-item { color: #475569 !important; }
        :root[data-theme="light"] .nav-links-item:hover { color: #0f172a !important; }

        /* Register CTA button */
        :root[data-theme="light"] .register-cta {
            background-color: #0f172a !important;
            color: #ffffff !important;
        }
        :root[data-theme="light"] .register-cta:hover {
            background-color: #1e293b !important;
            color: #ffffff !important;
        }

        /* Hero section */
        :root[data-theme="light"] .hero-heading { color: #0f172a !important; }
        :root[data-theme="light"] .hero-italic { color: #64748b !important; }
        :root[data-theme="light"] .hero-body { color: #475569 !important; }
        :root[data-theme="light"] .hero-strong { color: #0f172a !important; }

        /* Hero buttons */
        :root[data-theme="light"] .hero-btn-primary {
            background-color: #0f172a !important;
            color: #ffffff !important;
        }
        :root[data-theme="light"] .hero-btn-secondary {
            border-color: rgba(15,23,42,0.2) !important;
            color: #0f172a !important;
        }
        :root[data-theme="light"] .hero-btn-secondary:hover {
            background-color: rgba(15,23,42,0.05) !important;
        }

        /* Tech logos section */
        :root[data-theme="light"] .tech-section {
            border-color: rgba(15,23,42,0.08) !important;
        }
        :root[data-theme="light"] .tech-label { color: #94a3b8 !important; }
        :root[data-theme="light"] .tech-logo { color: #334155 !important; }

        /* Features section */
        :root[data-theme="light"] .features-section {
            background-color: #ffffff !important;
            border-color: rgba(15,23,42,0.08) !important;
        }
        :root[data-theme="light"] .feature-icon-wrap {
            background-color: rgba(15,23,42,0.04) !important;
            border-color: rgba(15,23,42,0.08) !important;
        }
        :root[data-theme="light"] .feature-icon-wrap:hover {
            background-color: #0f172a !important;
            color: #ffffff !important;
        }
        :root[data-theme="light"] .feature-title { color: #0f172a !important; }
        :root[data-theme="light"] .feature-body { color: #475569 !important; }
        :root[data-theme="light"] .feature-highlight { color: #334155 !important; }

        /* Pricing section */
        :root[data-theme="light"] .pricing-section { background-color: #f5f5f7 !important; }
        :root[data-theme="light"] .pricing-heading { color: #0f172a !important; }
        :root[data-theme="light"] .pricing-sub { color: #64748b !important; }

        /* Regular cards in light mode */
        :root[data-theme="light"] .pkg-regular {
            background: #ffffff !important;
            border-color: rgba(15,23,42,0.1) !important;
            box-shadow: 0 4px 20px rgba(15,23,42,0.06) !important;
        }
        :root[data-theme="light"] .pkg-regular:hover {
            border-color: rgba(15,23,42,0.2) !important;
        }
        :root[data-theme="light"] .pkg-regular .pkg-name { color: #0f172a !important; }
        :root[data-theme="light"] .pkg-regular .pkg-price { color: #0f172a !important; }
        :root[data-theme="light"] .pkg-regular .pkg-kicker { color: #64748b !important; }
        :root[data-theme="light"] .pkg-regular .pkg-benefit { color: #475569 !important; }
        :root[data-theme="light"] .pkg-regular .pkg-btn {
            background: rgba(15,23,42,0.06) !important;
            color: #0f172a !important;
            border-color: rgba(15,23,42,0.12) !important;
        }
        :root[data-theme="light"] .pkg-regular .pkg-btn:hover {
            background: #0f172a !important;
            color: #ffffff !important;
        }

        /* Footer */
        :root[data-theme="light"] .site-footer {
            background-color: #f1f5f9 !important;
            border-color: rgba(15,23,42,0.08) !important;
        }
        :root[data-theme="light"] .footer-logo-text { color: #475569 !important; }
        :root[data-theme="light"] .footer-link { color: #334155 !important; }
        :root[data-theme="light"] .footer-link:hover { color: #0f172a !important; }
        :root[data-theme="light"] .footer-divider { background: linear-gradient(to right, rgba(15,23,42,0.1), rgba(15,23,42,0.15), rgba(15,23,42,0.1)) !important; }
        :root[data-theme="light"] .footer-copy { color: #475569 !important; }
        :root[data-theme="light"] .footer-social {
            border-color: rgba(15,23,42,0.15) !important;
            color: #334155 !important;
        }
        :root[data-theme="light"] .footer-social:hover {
            border-color: rgba(15,23,42,0.3) !important;
            color: #0f172a !important;
            background-color: rgba(15,23,42,0.04) !important;
        }

        /* Theme toggle button */
        :root[data-theme="light"] #theme-toggle,
        :root[data-theme="light"] #theme-toggle-mobile {
            color: #334155 !important;
            border-color: rgba(15,23,42,0.15) !important;
            background: rgba(15,23,42,0.04) !important;
        }

        .hero-glow {
            position: absolute;
            width: 80vw;
            max-width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(255,255,255,0.04) 0%, rgba(5,5,5,0) 70%);
            top: -200px;
            left: 50%;
            transform: translateX(-50%);
            z-index: -1;
            pointer-events: none;
        }

        :root[data-theme="light"] .hero-glow {
            background: radial-gradient(circle, rgba(15,23,42,0.03) 0%, rgba(245,245,247,0) 70%);
        }

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #050505; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #555; }
        :root[data-theme="light"] ::-webkit-scrollbar-track { background: #e2e8f0; }
        :root[data-theme="light"] ::-webkit-scrollbar-thumb { background: #94a3b8; }
        :root[data-theme="light"] ::-webkit-scrollbar-thumb:hover { background: #64748b; }

        .faq-top {
            padding: 48px 24px 40px;
            position: relative;
            z-index: 1;
        }

        .faq-shell {
            max-width: 860px;
            margin: 0 auto;
        }

        .faq-kicker {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.3em;
            color: var(--text-dim);
            font-weight: 600;
        }

        .faq-title {
            font-size: clamp(28px, 4vw, 42px);
            font-weight: 700;
            color: var(--heading);
            margin-top: 12px;
        }

        .faq-subtitle {
            color: var(--text-dim);
            margin-top: 8px;
            line-height: 1.6;
        }

        .faq-list {
            margin-top: 28px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .faq-item {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 18px 22px;
            transition: border-color 0.25s ease, transform 0.25s ease;
        }

        .faq-item:hover {
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        :root[data-theme="light"] .faq-item:hover {
            border-color: rgba(15, 23, 42, 0.2);
        }

        .faq-question {
            width: 100%;
            background: transparent;
            border: none;
            padding: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            color: var(--heading);
            font-weight: 600;
            text-align: left;
            cursor: pointer;
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.35s ease;
        }

        .faq-answer p {
            margin-top: 14px;
            color: var(--text-dim);
            line-height: 1.7;
        }

        .faq-item.is-open .faq-answer {
            max-height: 260px;
        }

        .faq-icon {
            width: 28px;
            height: 28px;
            border-radius: 10px;
            border: 1px solid var(--border);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            position: relative;
            transition: transform 0.3s ease, border-color 0.3s ease;
        }

        .faq-icon::before,
        .faq-icon::after {
            content: '';
            position: absolute;
            width: 12px;
            height: 2px;
            background: var(--heading);
            transition: transform 0.3s ease;
        }

        .faq-icon::after {
            transform: rotate(90deg);
        }

        .faq-item.is-open .faq-icon {
            transform: rotate(45deg);
            border-color: rgba(255, 255, 255, 0.4);
        }

        :root[data-theme="light"] .faq-icon::before,
        :root[data-theme="light"] .faq-icon::after {
            background: #0f172a;
        }
    </style>
</head>
<body class="font-sans text-gray-200 antialiased selection:bg-white/20 selection:text-white">
    @php
        $currentUser = auth()->user();
        $isLoggedIn = (bool) $currentUser;
        $hasCheckoutAccess = false;
        if ($currentUser) {
            $hasCheckoutAccess = $currentUser->hasLmsAccess();
        }
        $lmsUrl = route('lms.entry');
        $lessonId = $firstLesson?->id ?? 1;
    @endphp

    <nav class="fixed w-full z-50 bg-background/80 backdrop-blur-xl border-b border-white/5 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="flex items-center justify-between h-24 md:grid md:grid-cols-[1fr_auto_1fr] md:gap-6">
                <a href="#" class="flex items-center md:justify-self-start" aria-label="Nde Guitar Class Home">
                    <img src="{{ asset('compro/img/ndelogo.png') }}" alt="NDE logo" class="h-14 w-auto object-contain nav-logo-dark">
                    <img src="{{ asset('compro/img/nde_logo_light.png') }}" alt="NDE logo" class="h-14 w-auto object-contain nav-logo-light">
                </a>

                <div class="hidden md:flex items-center space-x-10 md:justify-self-center">
                    <a href="#tentang" class="nav-links-item text-sm font-medium text-textDim hover:text-white transition-colors">About the Class</a>
                    <a href="#fitur" class="nav-links-item text-sm font-medium text-textDim hover:text-white transition-colors">Learning System</a>
                    <a href="#harga" class="nav-links-item text-sm font-medium text-textDim hover:text-white transition-colors">Pricing</a>
                </div>

                <div class="hidden md:flex items-center gap-4 md:justify-self-end">
                    <button id="theme-toggle" type="button" class="w-9 h-9 flex items-center justify-center rounded-full border border-white/20 text-white/70 hover:text-white hover:border-white/60 transition-all" aria-label="Toggle theme">
                        <svg id="theme-icon-moon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
                        <svg id="theme-icon-sun" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/></svg>
                    </button>
                    <a href="{{ auth()->check() ? $lmsUrl : route('login') }}" class="nav-links-item text-sm font-medium text-white hover:text-gray-300 transition-colors">Enter LMS</a>
                    @if (! $isLoggedIn)
                        <a href="{{ url('/registerclass') }}" class="register-cta bg-white text-black text-sm font-semibold px-6 py-2.5 rounded-full hover:bg-gray-200 transition-all transform hover:scale-105">
                            Register Now
                        </a>
                    @endif
                </div>

                <button id="mobile-menu-button" class="text-white md:hidden" aria-label="Toggle mobile menu" aria-expanded="false" aria-controls="mobile-menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>

            <div id="mobile-menu" class="md:hidden hidden pb-6 border-t border-white/10">
                <div class="flex flex-col gap-4 pt-5">
                    <a href="#tentang" class="nav-links-item text-sm font-medium text-textDim hover:text-white transition-colors">About the Class</a>
                    <a href="#fitur" class="nav-links-item text-sm font-medium text-textDim hover:text-white transition-colors">Learning System</a>
                    <a href="#harga" class="nav-links-item text-sm font-medium text-textDim hover:text-white transition-colors">Pricing</a>
                    <button id="theme-toggle-mobile" type="button" class="w-9 h-9 flex items-center justify-center rounded-full border border-white/20 text-white/70 hover:text-white hover:border-white/60 transition-all" aria-label="Toggle theme">
                        <svg id="theme-icon-moon-mobile" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
                        <svg id="theme-icon-sun-mobile" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/></svg>
                    </button>
                    <a href="{{ auth()->check() ? $lmsUrl : route('login') }}" class="nav-links-item text-sm font-medium text-white hover:text-gray-300 transition-colors">Enter LMS</a>
                    @if (! $isLoggedIn)
                        <a href="{{ url('/registerclass') }}" class="register-cta inline-flex justify-center bg-white text-black text-sm font-semibold px-6 py-2.5 rounded-full hover:bg-gray-200 transition-all">
                            Register Now
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <section id="tentang" class="relative pt-40 pb-20 lg:pt-56 lg:pb-32 overflow-hidden px-6">
        <div class="hero-glow"></div>
        <div class="max-w-5xl mx-auto text-center relative z-10">
        

            <h1 class="hero-heading font-serif text-5xl md:text-7xl lg:text-8xl text-white leading-tight mb-8">
                Master Guitar with a<br>
                <span class="hero-italic italic text-gray-400">Structured Method.</span>
            </h1>

            <p class="hero-body text-lg md:text-xl text-textDim max-w-2xl mx-auto leading-relaxed mb-12 font-light">
                Get lifetime access to high-quality video lessons, enhanced with <strong class="hero-strong text-white font-medium">1-on-1 Coaching</strong> to keep your learning progress moving forward.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ $isLoggedIn ? $lmsUrl : url('/registerclass') }}" class="hero-btn-primary w-full sm:w-auto bg-white text-black font-semibold px-8 py-4 rounded-full text-base transition-transform transform hover:scale-105 flex items-center justify-center gap-2">
                    {{ $isLoggedIn ? 'Enter LMS' : 'Start Your Journey' }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
                <a href="#video-preview" class="hero-btn-secondary w-full sm:w-auto bg-transparent border border-white/20 text-white font-medium px-8 py-4 rounded-full text-base hover:bg-white/5 transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    Watch Preview
                </a>
            </div>
        </div>

        <div class="tech-section max-w-7xl mx-auto mt-24 border-y border-white/5 py-8">
            <p class="tech-label text-center text-xs font-semibold tracking-widest text-textDim uppercase mb-6">Infrastruktur Teknologi Pendukung</p>
            <div class="flex flex-wrap justify-center items-center gap-8 md:gap-16 opacity-50 grayscale hover:grayscale-0 transition-all duration-500">
                <div class="tech-logo flex items-center gap-2 text-xl font-bold tracking-tighter text-white">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm0 22c-5.514 0-10-4.486-10-10s4.486-10 10-10 10 4.486 10 10-4.486 10-10 10zm-1-11v-4h2v4h-2zm0 4v-2h2v2h-2z"/></svg>
                    Bunny.net
                </div>
                <div class="tech-logo flex items-center gap-2 text-xl font-bold tracking-tighter text-white">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm0 22c-5.514 0-10-4.486-10-10s4.486-10 10-10 10 4.486 10 10-4.486 10-10 10zm-1-11v-4h2v4h-2zm0 4v-2h2v2h-2z"/></svg>
                    Twilio CPaaS
                </div>
                <div class="tech-logo flex items-center gap-2 text-xl font-bold tracking-tighter text-white">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm0 22c-5.514 0-10-4.486-10-10s4.486-10 10-10 10 4.486 10 10-4.486 10-10 10zm-1-11v-4h2v4h-2zm0 4v-2h2v2h-2z"/></svg>
                    Midtrans
                </div>
            </div>
        </div>
    </section>

    <section id="video-preview" class="py-20 px-6">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-6">
                <h3 class="hero-heading font-serif text-2xl md:text-3xl text-white">{{ $promo_title ?: 'Preview Video Kelas' }}</h3>
            </div>
            <div id="promo-preview" class="relative w-full aspect-video bg-surface rounded-2xl overflow-hidden border border-white/10 shadow-2xl group cursor-pointer">
                <img id="promo-preview-fallback" src="{{ $promo_thumbnail_url ?: 'https://images.unsplash.com/photo-1549298240-0d8e60513026?q=80&w=2000&auto=format&fit=crop' }}" alt="Preview Video" class="w-full h-full object-cover opacity-100 transition duration-500">
                <div id="promo-preview-loading" class="absolute inset-0 flex items-center justify-center">
                    <div class="w-20 h-20 bg-white/10 backdrop-blur-md rounded-full flex items-center justify-center border border-white/20 group-hover:scale-110 transition-transform duration-300 shadow-xl">
                        <svg class="w-8 h-8 text-white ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </div>
                </div>
                <video id="promo-preview-video" class="absolute inset-0 w-full h-full object-cover hidden" playsinline muted loop controls></video>
            </div>
            <p class="text-center text-textDim text-sm mt-6 font-light">Class material preview. Streaming quality is powered by Bunny.net.</p>
        </div>
    </section>

    <section id="fitur" class="features-section py-24 px-6 bg-surface/50 border-y border-white/5">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="feature-title font-serif text-3xl md:text-4xl text-white mb-4">Premium <span class="hero-italic italic text-gray-400">Learning System.</span></h2>
                <p class="feature-body text-textDim max-w-2xl mx-auto">This platform is built with modern technology to deliver an interactive and seamless learning experience.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-12 md:gap-8">
                <div class="space-y-6 group">
                    <div class="feature-icon-wrap w-14 h-14 bg-white/5 rounded-full flex items-center justify-center border border-white/10 group-hover:bg-white group-hover:text-black transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="feature-title font-serif text-2xl text-white">Unlimited HD Video</h3>
                    <p class="feature-body text-textDim leading-relaxed font-light">Video lessons are hosted privately with <span class="feature-highlight text-gray-300 font-medium">Bunny CDN</span>. Rewatch them anytime for life with smooth streaming.</p>
                </div>

                <div class="space-y-6 group">
                    <div class="feature-icon-wrap w-14 h-14 bg-white/5 rounded-full flex items-center justify-center border border-white/10 group-hover:bg-white group-hover:text-black transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                    </div>
                    <h3 class="feature-title font-serif text-2xl text-white">Live Coaching 1-on-1</h3>
                    <p class="feature-body text-textDim leading-relaxed font-light">Need help with technique? Book a live <span class="italic">video call</span> directly with the instructor on the website. Powered by clear and reliable <span class="feature-highlight text-gray-300 font-medium">Twilio WebRTC</span>.</p>
                </div>

                <div class="space-y-6 group">
                    <div class="feature-icon-wrap w-14 h-14 bg-white/5 rounded-full flex items-center justify-center border border-white/10 group-hover:bg-white group-hover:text-black transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <h3 class="feature-title font-serif text-2xl text-white">Akses Kelas Otomatis</h3>
                    <p class="feature-body text-textDim leading-relaxed font-light">Checkout terintegrasi penuh dengan <span class="feature-highlight text-gray-300 font-medium">Midtrans</span>. Bayar menggunakan QRIS, Gopay, atau Virtual Account, kelas Anda langsung terbuka otomatis detik itu juga.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="harga" class="pricing-section py-32 px-6 relative bg-black">
        <div class="max-w-4xl mx-auto text-center mb-16">
            <h2 class="pricing-heading font-serif text-4xl md:text-5xl text-white mb-6">Choose Your <span class="hero-italic italic text-gray-400">Learning Level.</span></h2>
            <p class="pricing-sub text-gray-400 text-lg font-light">A one-time investment for a lifetime skill. No monthly subscription.</p>
        </div>

        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">
            @php
                $orderMap = [
                    config('coaching.coaching_package_slug', 'coaching-ticket') => 0,
                    'intermediate' => 1,
                    'beginner' => 2,
                ];
                $orderedPackages = $packages->sortBy(function ($pkg) use ($orderMap) {
                    return $orderMap[$pkg->slug] ?? 99;
                })->values();
            @endphp
            @foreach($orderedPackages as $i => $pkg)
                @php
                    $isFeatured = ($pkg->slug ?? null) === 'intermediate';
                    $benefits = array_filter(array_map('trim', explode("\n", $pkg->benefits ?? '')));
                    $price = number_format($pkg->price, 0, ',', '.');
                    $imgSrc = $pkg->image
                        ? asset('storage/' . $pkg->image)
                        : asset('pictures/' . $pkg->slug . '.jpg');
                @endphp

                @if($isFeatured)
                <div class="flex-1 bg-white rounded-[2.5rem] text-center relative overflow-hidden shadow-[0_0_50px_rgba(255,255,255,0.1)] flex flex-col justify-between transform lg:scale-105 z-10 border-4 border-white">
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 px-4 py-1 bg-black text-white text-[10px] font-bold tracking-widest uppercase rounded-b-xl z-10">Best Value</div>
                    <div class="w-full h-52 overflow-hidden">
                        <img src="{{ $imgSrc }}" alt="{{ $pkg->name }}" class="w-full h-full object-cover">
                    </div>
                    <div class="p-10">
                        <span class="text-xs font-semibold tracking-[0.2em] text-gray-500 uppercase mb-4 block">Recommended</span>
                        <h3 class="text-4xl font-serif text-black mb-2">{{ $pkg->name }}</h3>
                        <div class="flex justify-center items-end gap-1 my-8">
                            <span class="text-5xl font-bold text-black tracking-tighter">Rp {{ $price }}</span>
                        </div>
                        <div class="space-y-4 mb-10 text-left">
                            @foreach($benefits as $benefit)
                            <div class="flex items-start gap-3 text-sm text-gray-700">
                                <svg class="w-5 h-5 text-black shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>{{ $benefit }}</span>
                            </div>
                            @endforeach
                        </div>
                        @if($isLoggedIn)
                            <a href="{{ route('kelas.buy', $lessonId) }}?package_id={{ $pkg->id }}&package_qty=1" class="w-full bg-black text-white font-bold px-6 py-5 rounded-2xl hover:opacity-90 transition-all shadow-xl block text-center">Get Access Now</a>
                        @else
                            <a href="{{ route('register') }}?package_id={{ $pkg->id }}&package_qty=1" class="w-full bg-black text-white font-bold px-6 py-5 rounded-2xl hover:opacity-90 transition-all shadow-xl block text-center">Get Access Now</a>
                        @endif
                    </div>
                </div>
                @else
                <div class="pkg-regular flex-1 bg-surface border border-white/10 rounded-[2.5rem] text-center relative overflow-hidden shadow-2xl flex flex-col justify-between group transition-all hover:border-white/30">
                    <div class="w-full h-52 overflow-hidden">
                        <img src="{{ $imgSrc }}" alt="{{ $pkg->name }}" class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition duration-300">
                    </div>
                    <div class="p-8 flex flex-col flex-1 justify-between">
                        <div>
                            <span class="pkg-kicker text-xs font-semibold tracking-[0.2em] text-gray-500 uppercase mb-4 block">{{ $pkg->description ?? '' }}</span>
                            <h3 class="pkg-name text-3xl font-serif text-white mb-2">{{ $pkg->name }}</h3>
                            <div class="flex justify-center items-end gap-1 my-8">
                                <span class="pkg-price text-4xl font-bold text-white tracking-tighter">Rp {{ $price }}</span>
                            </div>
                            <div class="space-y-4 mb-10 text-left">
                                @foreach($benefits as $benefit)
                                <div class="flex items-start gap-3 text-sm text-gray-300">
                                    <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <span class="pkg-benefit">{{ $benefit }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @if($isLoggedIn)
                            <a href="{{ route('kelas.buy', $lessonId) }}?package_id={{ $pkg->id }}&package_qty=1" class="pkg-btn w-full bg-white/10 text-white border border-white/20 font-semibold px-6 py-4 rounded-2xl hover:bg-white hover:text-black transition-all block text-center">Choose Package</a>
                        @else
                            <a href="{{ route('register') }}?package_id={{ $pkg->id }}&package_qty=1" class="pkg-btn w-full bg-white/10 text-white border border-white/20 font-semibold px-6 py-4 rounded-2xl hover:bg-white hover:text-black transition-all block text-center">Choose Package</a>
                        @endif
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </section>

    <section class="faq-top">
        <div class="faq-shell">
            <span class="faq-kicker">FAQ</span>
            <h2 class="faq-title">Frequently Asked Questions</h2>
            <p class="faq-subtitle">Find quick answers about NDE services, programs, and coaching tickets.</p>

            <div class="faq-list">
                @forelse(($faq_items ?? []) as $faq)
                    <div class="faq-item {{ $loop->first ? 'is-open' : '' }}">
                        <button class="faq-question" type="button">
                            <span>{{ $faq->question }}</span>
                            <span class="faq-icon" aria-hidden="true"></span>
                        </button>
                        <div class="faq-answer">
                            <p>{{ $faq->answer }}</p>
                        </div>
                    </div>
                @empty
                    <div class="faq-item is-open">
                        <button class="faq-question" type="button">
                            <span>No FAQ items are available yet.</span>
                            <span class="faq-icon" aria-hidden="true"></span>
                        </button>
                        <div class="faq-answer">
                            <p>Admins can add FAQ items from the dashboard.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <footer class="site-footer bg-[#07090d] border-t border-white/5 pt-16 pb-10 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-[1.35fr_repeat(4,minmax(0,1fr))] gap-8 items-start">
                <div>
                    <img src="{{ asset('compro/img/ndelogo.png') }}" alt="NDE logo" class="h-16 w-auto object-contain nav-logo-dark">
                    <img src="{{ asset('compro/img/nde_logo_light.png') }}" alt="NDE logo" class="h-16 w-auto object-contain nav-logo-light">
                    <p class="footer-logo-text mt-3 text-[13px] text-white/70 font-semibold tracking-[0.12em]">NDE GUITAR CLASS</p>
                </div>

                <nav class="flex flex-col gap-2">
                    <a href="#tentang" class="footer-link text-xs font-bold tracking-[0.12em] text-white/85 hover:text-white transition-colors">ABOUT THE CLASS</a>
                    <a href="#fitur" class="footer-link text-xs font-bold tracking-[0.12em] text-white/85 hover:text-white transition-colors">LEARNING SYSTEM</a>
                    <a href="#video-preview" class="footer-link text-xs font-bold tracking-[0.12em] text-white/85 hover:text-white transition-colors">VIDEO PREVIEW</a>
                </nav>

                <nav class="flex flex-col gap-2">
                    <a href="#harga" class="footer-link text-xs font-bold tracking-[0.12em] text-white/85 hover:text-white transition-colors">PACKAGE PRICING</a>
                    @foreach($packages as $pkg)
                    <a href="#harga" class="footer-link text-xs font-bold tracking-[0.12em] text-white/85 hover:text-white transition-colors">{{ strtoupper($pkg->name) }}</a>
                    @endforeach
                </nav>

                <nav class="flex flex-col gap-2">
                    <a href="{{ auth()->check() ? $lmsUrl : route('login') }}" class="footer-link text-xs font-bold tracking-[0.12em] text-white/85 hover:text-white transition-colors">ENTER LMS</a>
                    <a href="{{ url('/registerclass') }}" class="footer-link text-xs font-bold tracking-[0.12em] text-white/85 hover:text-white transition-colors">REGISTER NOW</a>
                    <a href="{{ route('coaching.index') }}" class="footer-link text-xs font-bold tracking-[0.12em] text-white/85 hover:text-white transition-colors">BOOK COACHING</a>
                </nav>

                <nav class="flex flex-col gap-2">
                    <a href="mailto:support@guitarclassbynde.id?subject=Support%20NDE%20Guitar%20Class" class="footer-link text-xs font-bold tracking-[0.12em] text-white/85 hover:text-white transition-colors">EMAIL SUPPORT</a>
                    <a href="https://wa.me/+6281273796646" target="_blank" rel="noopener" class="footer-link text-xs font-bold tracking-[0.12em] text-white/85 hover:text-white transition-colors">WHATSAPP ADMIN</a>
                    <a href="{{ route('coaching.index') }}" class="footer-link text-xs font-bold tracking-[0.12em] text-white/85 hover:text-white transition-colors">COACHING HELP</a>
                    <a href="{{ url('/ndeofficial') }}" class="footer-link text-xs font-bold tracking-[0.12em] text-white/85 hover:text-white transition-colors">HOME PAGE</a>
                </nav>
            </div>

            <div class="footer-divider h-px mt-10 mb-14 bg-gradient-to-r from-white/15 via-white/20 to-white/15"></div>

            <div class="flex flex-col items-center justify-center gap-4">
                <div class="flex items-center gap-3">
                    <a href="{{ url('/ndeofficial') }}" class="footer-social w-10 h-10 rounded-full border border-white/40 text-white/90 hover:text-white hover:border-white/70 hover:bg-white/10 transition-all inline-flex items-center justify-center">
                        <svg viewBox="0 0 24 24" class="w-4 h-4" fill="currentColor"><path d="M13.5 8H16V5h-2.5c-2.8 0-4.5 1.8-4.5 4.6V12H7v3h2v4h3v-4h2.6l.4-3H12V9.8c0-1.2.3-1.8 1.5-1.8z"/></svg>
                    </a>
                    <a href="{{ route('coaching.index') }}" class="footer-social w-10 h-10 rounded-full border border-white/40 text-white/90 hover:text-white hover:border-white/70 hover:bg-white/10 transition-all inline-flex items-center justify-center">
                        <svg viewBox="0 0 24 24" class="w-4 h-4" fill="currentColor"><path d="M22 5.8c-.7.3-1.5.5-2.3.6.8-.5 1.4-1.2 1.7-2.2-.8.5-1.6.8-2.5 1-1.4-1.5-3.9-1.6-5.4-.1-.9.9-1.3 2.1-1.1 3.3-3.2-.2-6-1.7-7.9-4-.4.7-.6 1.5-.6 2.3 0 1.6.8 3 2 3.8-.6 0-1.2-.2-1.8-.5 0 2.2 1.5 4.1 3.7 4.5-.6.2-1.3.2-1.9.1.5 1.8 2.2 3 4 3.1-1.5 1.2-3.3 1.8-5.2 1.8H4c1.9 1.2 4.2 1.9 6.5 1.9 7.8 0 12-6.5 12-12.1v-.6c.8-.5 1.5-1.2 2-2z"/></svg>
                    </a>
                    <a href="{{ auth()->check() ? $lmsUrl : route('login') }}" class="footer-social w-10 h-10 rounded-full border border-white/40 text-white/90 hover:text-white hover:border-white/70 hover:bg-white/10 transition-all inline-flex items-center justify-center">
                        <svg viewBox="0 0 24 24" class="w-4 h-4" fill="currentColor"><path d="M6 17a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm-4-7v3c3.9 0 7 3.1 7 7h3c0-5.5-4.5-10-10-10zm0-6v3c7.2 0 13 5.8 13 13h3C18 11.2 10.8 4 2 4z"/></svg>
                    </a>
                    <a href="{{ url('/registerclass') }}" class="footer-social w-10 h-10 rounded-full border border-white/40 text-white/90 hover:text-white hover:border-white/70 hover:bg-white/10 transition-all inline-flex items-center justify-center">
                        <svg viewBox="0 0 24 24" class="w-4 h-4" fill="currentColor"><path d="M12 10.2v3.3h4.7c-.2 1.2-1.4 3.4-4.7 3.4-2.8 0-5.1-2.3-5.1-5.1S9.2 6.7 12 6.7c1.6 0 2.7.7 3.3 1.2l2.3-2.2C16.2 4.4 14.3 3.6 12 3.6 7.5 3.6 3.8 7.3 3.8 11.8S7.5 20 12 20c6.9 0 8.5-4.8 8.5-7.2 0-.5-.1-.9-.1-1.3H12z"/></svg>
                    </a>
                    <a href="mailto:support@guitarclassbynde.id?subject=Support%20NDE%20Guitar%20Class" class="footer-social w-10 h-10 rounded-full border border-white/40 text-white/90 hover:text-white hover:border-white/70 hover:bg-white/10 transition-all inline-flex items-center justify-center">
                        <svg viewBox="0 0 24 24" class="w-4 h-4" fill="currentColor"><path d="M6 10.5A1.5 1.5 0 1 1 6 13.5 1.5 1.5 0 0 1 6 10.5zm6 0A1.5 1.5 0 1 1 12 13.5 1.5 1.5 0 0 1 12 10.5zm6 0A1.5 1.5 0 1 1 18 13.5 1.5 1.5 0 0 1 18 10.5z"/></svg>
                    </a>
                </div>
                <p class="footer-copy text-sm md:text-base text-white/80 font-semibold">&copy; {{ now()->year }} Nde Official. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        (function () {
            var promoStreamUrl = null;
            var previewVideo = document.getElementById('promo-preview-video');
            var fallbackImage = document.getElementById('promo-preview-fallback');
            var loadingOverlay = document.getElementById('promo-preview-loading');
            var previewCard = document.getElementById('promo-preview');

            function showFallback() {
                if (previewVideo) { previewVideo.classList.add('hidden'); previewVideo.removeAttribute('src'); try { previewVideo.pause(); } catch (e) {} }
                if (fallbackImage) fallbackImage.classList.remove('hidden');
                if (loadingOverlay) loadingOverlay.classList.remove('hidden');
            }

            function playStream(url) {
                if (!previewVideo || !url) { showFallback(); return; }
                if (fallbackImage) fallbackImage.classList.add('hidden');
                if (loadingOverlay) loadingOverlay.classList.remove('hidden');
                previewVideo.classList.remove('hidden');
                var useNativeHls = previewVideo.canPlayType('application/vnd.apple.mpegurl');
                function startPlayback() { previewVideo.src = url; previewVideo.load(); previewVideo.play().catch(function () {}); if (loadingOverlay) loadingOverlay.classList.add('hidden'); }
                if (useNativeHls) { startPlayback(); return; }
                if (window.Hls) {
                    try {
                        if (window._promoHls) { try { window._promoHls.destroy(); } catch (e) {} window._promoHls = null; }
                        var hls = new Hls(); window._promoHls = hls;
                        hls.loadSource(url); hls.attachMedia(previewVideo);
                        hls.on(Hls.Events.MANIFEST_PARSED, function () { previewVideo.play().catch(function () {}); if (loadingOverlay) loadingOverlay.classList.add('hidden'); });
                        hls.on(Hls.Events.ERROR, function (_, data) { if (data && data.fatal) showFallback(); });
                    } catch (e) { showFallback(); }
                    return;
                }
                var s = document.createElement('script'); s.src = 'https://cdn.jsdelivr.net/npm/hls.js@latest'; s.async = true;
                s.onload = function () { if (window.Hls) playStream(url); else showFallback(); };
                s.onerror = showFallback; document.head.appendChild(s);
            }

            fetch('{{ url('/promo-stream') }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function (r) { return r.ok ? r.json() : null; })
                .then(function (d) { if (d && d.url) promoStreamUrl = d.url; showFallback(); })
                .catch(showFallback);

            if (previewCard) {
                previewCard.addEventListener('click', function () {
                    if (promoStreamUrl && previewVideo && previewVideo.classList.contains('hidden')) { playStream(promoStreamUrl); return; }
                    if (previewVideo && previewVideo.paused) previewVideo.play().catch(function () {});
                });
            }
        })();

        (function () {
            var button = document.getElementById('mobile-menu-button');
            var menu = document.getElementById('mobile-menu');
            if (button && menu) {
                button.addEventListener('click', function () {
                    var isHidden = menu.classList.contains('hidden');
                    menu.classList.toggle('hidden');
                    button.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
                });
                menu.querySelectorAll('a[href^="#"]').forEach(function (link) {
                    link.addEventListener('click', function () { menu.classList.add('hidden'); button.setAttribute('aria-expanded', 'false'); });
                });
            }
        })();

        (function () {
            var toggles = [
                document.getElementById('theme-toggle'),
                document.getElementById('theme-toggle-mobile'),
            ].filter(Boolean);
            if (!toggles.length) return;

            function getTheme() {
                var match = document.cookie.match(/(?:^|; )theme=([^;]+)/);
                return match ? decodeURIComponent(match[1]) : 'dark';
            }

            function setTheme(theme) {
                document.documentElement.setAttribute('data-theme', theme);
                document.cookie = 'theme=' + encodeURIComponent(theme) + '; path=/; max-age=31536000; SameSite=Lax';
                var moonD = document.getElementById('theme-icon-moon');
                var sunD = document.getElementById('theme-icon-sun');
                var moonM = document.getElementById('theme-icon-moon-mobile');
                var sunM = document.getElementById('theme-icon-sun-mobile');
                if (theme === 'light') {
                    if (moonD) moonD.classList.remove('hidden');
                    if (sunD) sunD.classList.add('hidden');
                    if (moonM) moonM.classList.remove('hidden');
                    if (sunM) sunM.classList.add('hidden');
                } else {
                    if (moonD) moonD.classList.add('hidden');
                    if (sunD) sunD.classList.remove('hidden');
                    if (moonM) moonM.classList.add('hidden');
                    if (sunM) sunM.classList.remove('hidden');
                }
            }

            setTheme(getTheme());
            toggles.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var next = document.documentElement.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
                    setTheme(next);
                });
            });
        })();

        (function () {
            var items = document.querySelectorAll('.faq-item');
            if (!items.length) return;

            items.forEach(function (item) {
                var button = item.querySelector('.faq-question');
                if (!button) return;

                button.addEventListener('click', function () {
                    var isOpen = item.classList.contains('is-open');
                    items.forEach(function (other) {
                        other.classList.remove('is-open');
                    });
                    if (!isOpen) item.classList.add('is-open');
                });
            });
        })();
    </script>
</body>
</html>
