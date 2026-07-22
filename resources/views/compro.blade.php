<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guitarclassbynde — Master Guitar 10x Faster</title>
    <meta name="description" content="Master guitar with Nde's proven curriculum, 1-on-1 coaching, HD video lessons, and interactive practice tools.">
    <link rel="icon" type="image/png" href="{{ asset('compro/img/logo_icon.png') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            important: true,
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            black: '#08080a',
                            card: 'rgba(18, 18, 24, 0.65)',
                            border: 'rgba(255, 255, 255, 0.08)',
                            blue: '#3b82f6',
                            emerald: '#10b981',
                            amber: '#f59e0b',
                            purple: '#a855f7'
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        display: ['"Bebas Neue"', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #08080a !important;
            color: #f3f4f6 !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            overflow-x: hidden;
        }
        .font-display {
            font-family: 'Bebas Neue', cursive !important;
            letter-spacing: 1px;
        }
        .glass-panel {
            background: rgba(18, 18, 26, 0.65);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 1.5rem;
        }
        body > nav { display: none !important; }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #08080a; }
        ::-webkit-scrollbar-thumb { background: #3b82f6; border-radius: 3px; }
    </style>
</head>

<body class="bg-[#08080a] text-gray-100 antialiased relative selection:bg-blue-600 selection:text-white" x-data="{ mobileMenuOpen: false }">

    {{-- Ambient Mesh Background Glows --}}
    <div class="absolute -top-32 left-1/3 w-[600px] h-[600px] bg-blue-600/15 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute top-[800px] -right-32 w-[500px] h-[500px] bg-purple-600/10 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute top-[1800px] -left-32 w-[500px] h-[500px] bg-emerald-600/10 rounded-full blur-[140px] pointer-events-none"></div>

    {{-- ─── LMS FLOATING GLASS PILL NAVBAR ───────────────────────────────── --}}
    @include('layouts.lms_header')

    @php
        $isLoggedIn = auth()->check();
        $lmsUrl = route('lms.dashboard');
        $firstLesson = \App\Models\Lesson::orderBy('position')->first();
        $lessonId = $firstLesson ? $firstLesson->id : 9;
    @endphp

    <!-- ─── HERO SECTION (HIGH CONVERTING SALES HOOK) ───────────────────── -->
    <header class="relative pt-12 pb-20 lg:pt-20 lg:pb-32 px-4 lg:px-8 max-w-7xl mx-auto z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <!-- Left Hero Content -->
            <div class="lg:col-span-7 space-y-6 text-left">
                
                <!-- Tag Badge -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-bold uppercase tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-blue-400 animate-ping"></span>
                    <span>Exclusive Guitar Mentorship Program</span>
                </div>

                <!-- Main Hook Heading -->
                <h1 class="font-display text-5xl sm:text-6xl md:text-7xl text-white tracking-wide uppercase leading-none">
                    Play Guitar Like A Pro <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-indigo-400 to-purple-400">10x Faster</span> With Nde
                </h1>

                <!-- Body Paragraph -->
                <p class="text-gray-300 text-sm sm:text-base leading-relaxed max-w-2xl">
                    Stop wasting months on confusing YouTube tutorials. Access Nde's proven step-by-step video curriculum, 1-on-1 live video coaching, and interactive practice tools in one unified platform.
                </p>

                <!-- Sales Action Buttons -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-2">
                    <a href="{{ $isLoggedIn ? $lmsUrl : url('/registerclass') }}" class="px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold rounded-2xl text-sm shadow-xl shadow-blue-600/30 transition-all hover:scale-105 flex items-center justify-center gap-3">
                        <span>{{ $isLoggedIn ? 'Enter Student LMS' : 'Claim Your Access Now' }}</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>

                    <a href="#packages" class="px-7 py-4 bg-zinc-950/60 border border-white/10 hover:border-white/20 text-gray-300 hover:text-white font-bold rounded-2xl text-sm transition backdrop-blur-md flex items-center justify-center gap-2">
                        <i class="fa-solid fa-tags text-blue-400"></i>
                        <span>Explore Packages</span>
                    </a>
                </div>

                <!-- Social Proof Badges -->
                <div class="pt-6 border-t border-white/10 flex flex-wrap items-center gap-6 text-xs text-gray-400">
                    <div class="flex items-center gap-2">
                        <div class="flex -space-x-2">
                            <img src="https://i.pravatar.cc/100?img=12" alt="Student" class="w-8 h-8 rounded-full border-2 border-zinc-900 object-cover" />
                            <img src="https://i.pravatar.cc/100?img=33" alt="Student" class="w-8 h-8 rounded-full border-2 border-zinc-900 object-cover" />
                            <img src="https://i.pravatar.cc/100?img=47" alt="Student" class="w-8 h-8 rounded-full border-2 border-zinc-900 object-cover" />
                        </div>
                        <span class="font-bold text-white">1,200+ <span class="font-normal text-gray-400">Students Mentored</span></span>
                    </div>

                    <div class="h-4 w-px bg-white/10 hidden sm:block"></div>

                    <div class="flex items-center gap-1.5">
                        <div class="text-amber-400 text-sm">★★★★★</div>
                        <span class="font-bold text-white">4.9/5 <span class="font-normal text-gray-400">Rating</span></span>
                    </div>

                    <div class="h-4 w-px bg-white/10 hidden sm:block"></div>

                    <div class="flex items-center gap-1.5 text-emerald-400 font-bold">
                        <i class="fa-solid fa-shield-halved"></i>
                        <span>100% Guaranteed Results</span>
                    </div>
                </div>
            </div>

            <!-- Right Hero Visual Box -->
            <div class="lg:col-span-5 relative">
                <div class="glass-panel p-2 relative overflow-hidden shadow-2xl group">
                    <div class="relative rounded-2xl overflow-hidden aspect-video sm:aspect-square bg-zinc-950">
                        <img src="{{ asset('compro/img/nde2.webp') }}" alt="Nde Guitar Session" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90">
                        
                        <!-- Floating Glass Badge -->
                        <div class="absolute bottom-4 left-4 right-4 bg-zinc-950/80 border border-white/10 rounded-xl p-4 backdrop-blur-md flex items-center justify-between">
                            <div>
                                <div class="text-[10px] text-blue-400 font-bold uppercase tracking-wider">Mentored by Nde</div>
                                <div class="text-xs font-bold text-white">Personalized 1-on-1 Feedback Session</div>
                            </div>
                            <span class="px-2.5 py-1 rounded-lg bg-emerald-500/20 text-emerald-400 text-[10px] font-bold">LIVE ON AIR</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </header>

    <!-- ─── STATS BANNER ────────────────────────────────────────────────── -->
    <section class="py-8 bg-zinc-950/80 border-y border-white/5 relative z-10">
        <div class="max-w-7xl mx-auto px-4 lg:px-8 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div>
                <div class="font-display text-4xl sm:text-5xl text-white">100+</div>
                <div class="text-xs text-gray-400 font-bold uppercase tracking-wider mt-1">HD Video Lessons</div>
            </div>
            <div>
                <div class="font-display text-4xl sm:text-5xl text-blue-400">1-on-1</div>
                <div class="text-xs text-gray-400 font-bold uppercase tracking-wider mt-1">Live Coaching Calls</div>
            </div>
            <div>
                <div class="font-display text-4xl sm:text-5xl text-purple-400">5 Suite</div>
                <div class="text-xs text-gray-400 font-bold uppercase tracking-wider mt-1">Interactive Practice Tools</div>
            </div>
            <div>
                <div class="font-display text-4xl sm:text-5xl text-emerald-400">∞</div>
                <div class="text-xs text-gray-400 font-bold uppercase tracking-wider mt-1">Lifetime LMS Access</div>
            </div>
        </div>
    </section>

    <!-- ─── 3 CORE PILLARS SHOWCASE SECTION ───────────────────────────── -->
    <section id="features" class="py-20 px-4 lg:px-8 max-w-7xl mx-auto relative z-10 space-y-12">
        <div class="text-center space-y-3">
            <span class="text-xs font-bold text-blue-400 uppercase tracking-widest block">The Ultimate Learning Ecosystem</span>
            <h2 class="font-display text-4xl sm:text-5xl text-white tracking-wide uppercase">
                3 Core Pillars of <span class="text-blue-500">Guitarclassbynde</span>
            </h2>
            <p class="text-gray-400 text-xs sm:text-sm max-w-xl mx-auto">
                The most effective guitar learning system combining video flexibility, 1-on-1 live coaching, and song tutorials.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <!-- Pillar 1: Video Lessons 24/7 -->
            <div class="glass-panel p-8 space-y-5 border-blue-500/30 hover:border-blue-500/50 transition-all duration-300 relative overflow-hidden group">
                <div class="w-14 h-14 rounded-2xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-laptop-code"></i>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-blue-400 uppercase tracking-widest block mb-1">Pillar 1 • Lifetime Access</span>
                    <h3 class="font-display text-3xl text-white">Video Lessons 24/7</h3>
                </div>
                <p class="text-gray-400 text-xs sm:text-sm leading-relaxed">
                    Access structured HD video modules that you can learn <strong class="text-white">anytime and anywhere</strong> at your own pace across all devices.
                </p>
                <div class="pt-2 text-xs font-bold text-blue-400 flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-check text-emerald-400"></i>
                    <span>Unlimited Rewatch Access</span>
                </div>
            </div>

            <!-- Pillar 2: 1-on-1 Video Call Coaching -->
            <div class="glass-panel p-8 space-y-5 border-purple-500/30 hover:border-purple-500/50 transition-all duration-300 relative overflow-hidden group">
                <div class="w-14 h-14 rounded-2xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-video"></i>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-purple-400 uppercase tracking-widest block mb-1">Pillar 2 • On-Website Video Call</span>
                    <h3 class="font-display text-3xl text-white">1-on-1 Live Coaching</h3>
                </div>
                <p class="text-gray-400 text-xs sm:text-sm leading-relaxed">
                    Live 1-on-1 <strong class="text-white">video call coaching inside the website</strong> with Nde for real-time technique & finger placement correction.
                </p>
                <div class="pt-2 text-xs font-bold text-purple-400 flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-check text-emerald-400"></i>
                    <span>Integrated Booking Schedule</span>
                </div>
            </div>

            <!-- Pillar 3: Exclusive Song Tutorial -->
            <div class="glass-panel p-8 space-y-5 border-emerald-500/30 hover:border-emerald-500/50 transition-all duration-300 relative overflow-hidden group">
                <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-compact-disc"></i>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest block mb-1">Pillar 3 • Intermediate Tier</span>
                    <h3 class="font-display text-3xl text-white">Song Tutorial Library</h3>
                </div>
                <p class="text-gray-400 text-xs sm:text-sm leading-relaxed">
                    Exclusive song breakdown library for <strong class="text-white">Intermediate Students</strong> to master popular song arrangements, strumming, and advanced chords.
                </p>
                <div class="pt-2 text-xs font-bold text-emerald-400 flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-check text-emerald-400"></i>
                    <span>Regular Song Updates</span>
                </div>
            </div>

        </div>
    </section>

    <!-- ─── PROBLEM VS SOLUTION (THE SALES COMPARISON) ───────────────────── -->
    <section class="py-20 px-4 lg:px-8 max-w-6xl mx-auto relative z-10 space-y-12">
        <div class="text-center space-y-3">
            <span class="text-xs font-bold text-blue-400 uppercase tracking-widest block">Why Most Guitar Learners Fail</span>
            <h2 class="font-display text-4xl sm:text-5xl text-white tracking-wide uppercase">
                The Old Way vs <span class="text-blue-500">The Nde System</span>
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-stretch">
            
            <!-- Old Way Card -->
            <div class="glass-panel p-8 border-red-500/20 bg-red-500/5 flex flex-col justify-between space-y-6 hover:border-red-500/40 transition">
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 flex items-center justify-center text-base shrink-0">
                                <i class="fa-solid fa-xmark"></i>
                            </div>
                            <h3 class="font-display text-2xl text-white tracking-wide uppercase">The Frustrating Old Way</h3>
                        </div>
                        <span class="px-2.5 py-1 bg-red-500/10 border border-red-500/20 text-red-400 text-[10px] font-extrabold uppercase rounded-full tracking-wider">High Risk</span>
                    </div>

                    <ul class="space-y-4 text-xs sm:text-sm text-gray-400">
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-xmark text-red-400 text-sm mt-0.5 shrink-0"></i>
                            <span class="leading-relaxed">Watching random YouTube tutorials with no clear structure or path.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-xmark text-red-400 text-sm mt-0.5 shrink-0"></i>
                            <span class="leading-relaxed">No feedback from a mentor, leading to bad finger habits and pain.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-xmark text-red-400 text-sm mt-0.5 shrink-0"></i>
                            <span class="leading-relaxed">Stuck on basic chord switching for months without progress.</span>
                        </li>
                    </ul>
                </div>

                <div class="pt-4 border-t border-red-500/10 flex items-center gap-2 text-xs font-bold text-red-400/80">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>90% Quit Within 3 Months</span>
                </div>
            </div>

            <!-- Nde System Card -->
            <div class="glass-panel p-8 border-emerald-500/30 bg-emerald-500/5 flex flex-col justify-between space-y-6 hover:border-emerald-500/50 transition">
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-base shrink-0">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <h3 class="font-display text-2xl text-white tracking-wide uppercase">The Guitarclassbynde System</h3>
                        </div>
                        <span class="px-2.5 py-1 bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 text-[10px] font-extrabold uppercase rounded-full tracking-wider">Recommended</span>
                    </div>

                    <ul class="space-y-4 text-xs sm:text-sm text-gray-300 font-medium">
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-check text-emerald-400 text-sm mt-0.5 shrink-0"></i>
                            <span class="leading-relaxed">Structured 100% step-by-step video curriculum from zero to advanced.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-check text-emerald-400 text-sm mt-0.5 shrink-0"></i>
                            <span class="leading-relaxed">Direct 1-on-1 live video coaching calls with Nde for instant fixes.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-check text-emerald-400 text-sm mt-0.5 shrink-0"></i>
                            <span class="leading-relaxed">Built-in 5 Suite Practice Tools (Tuner, Metronome, Chord/Scale, Quiz).</span>
                        </li>
                    </ul>
                </div>

                <div class="pt-4 border-t border-emerald-500/10 flex items-center gap-2 text-xs font-bold text-emerald-400">
                    <i class="fa-solid fa-shield-halved"></i>
                    <span>98% Success Rate Guaranteed</span>
                </div>
            </div>

        </div>
    </section>

    <!-- ─── INTEGRATED PRACTICE TOOLS SHOWCASE ─────────────────────────── -->
    <section id="tools" class="py-20 px-4 lg:px-8 max-w-7xl mx-auto relative z-10 space-y-12">
        <div class="text-center space-y-3">
            <span class="text-xs font-bold text-purple-400 uppercase tracking-widest block">Integrated Practice Suite</span>
            <h2 class="font-display text-4xl sm:text-5xl text-white tracking-wide uppercase">
                5 Interactive <span class="text-purple-400">Practice Tools Included</span>
            </h2>
            <p class="text-gray-400 text-xs sm:text-sm max-w-xl mx-auto">
                No need to buy external tuner or metronome apps. All tools are built directly inside your student dashboard.
            </p>
        </div>

        <!-- 3 Cards Top Row -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- 1. Tuner -->
            <div class="glass-panel p-6 space-y-3 border-blue-500/20 hover:border-blue-500/40 transition">
                <div class="w-12 h-12 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-guitar"></i>
                </div>
                <h3 class="font-display text-2xl text-white">Guitar Tuner</h3>
                <p class="text-gray-400 text-xs leading-relaxed">Mic pitch detection for accurate guitar tuning visual feedback.</p>
            </div>

            <!-- 2. Metronome -->
            <div class="glass-panel p-6 space-y-3 border-emerald-500/20 hover:border-emerald-500/40 transition">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-stopwatch"></i>
                </div>
                <h3 class="font-display text-2xl text-white">Precision Metronome</h3>
                <p class="text-gray-400 text-xs leading-relaxed">Tap tempo, time signatures, and custom beat sound engines.</p>
            </div>

            <!-- 3. Chord Library -->
            <div class="glass-panel p-6 space-y-3 border-purple-500/20 hover:border-purple-500/40 transition">
                <div class="w-12 h-12 rounded-xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-music"></i>
                </div>
                <h3 class="font-display text-2xl text-white">Chord Library</h3>
                <p class="text-gray-400 text-xs leading-relaxed">Interactive Rosewood fretboard with audio strum audio playback.</p>
            </div>
        </div>

        <!-- 2 Cards Centered Bottom Row -->
        <div class="flex flex-col sm:flex-row justify-center gap-6 max-w-4xl mx-auto">
            <!-- 4. Scale Visualizer -->
            <div class="glass-panel p-6 space-y-3 border-cyan-500/20 hover:border-cyan-500/40 transition flex-1">
                <div class="w-12 h-12 rounded-xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <h3 class="font-display text-2xl text-white">Scale Visualizer</h3>
                <p class="text-gray-400 text-xs leading-relaxed">Master Pentatonic, Blues, and Major scales with ascending audio.</p>
            </div>

            <!-- 5. Trainer Quiz Game -->
            <div class="glass-panel p-6 space-y-3 border-rose-500/20 hover:border-rose-500/40 transition flex-1">
                <div class="w-12 h-12 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-gamepad"></i>
                </div>
                <h3 class="font-display text-2xl text-white">Trainer Quiz Game</h3>
                <p class="text-gray-400 text-xs leading-relaxed">Interactive audio ear pitch quiz and fretboard note memory game.</p>
            </div>
        </div>
    </section>

    <!-- ─── PRICING PACKAGES (HIGH CONVERTING PURE SALES) ────────────────── -->
    <section id="packages" class="py-20 px-4 lg:px-8 max-w-7xl mx-auto relative z-10 space-y-12">
        <div class="text-center space-y-3">
            <span class="text-xs font-bold text-blue-400 uppercase tracking-widest block">Choose Your Membership Tier</span>
            <h2 class="font-display text-4xl sm:text-5xl text-white tracking-wide uppercase">
                Simple <span class="text-blue-500">Transparent Pricing</span>
            </h2>
            <p class="text-gray-400 text-xs sm:text-sm max-w-xl mx-auto">
                Once in a lifetime investment. No hidden monthly subscriptions.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
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
                    $priceFormatted = number_format($pkg->price, 0, '', '.');
                @endphp

                <div class="glass-panel p-8 flex flex-col justify-between relative overflow-hidden transition-all duration-300 {{ $isFeatured ? 'border-blue-500/50 shadow-[0_0_50px_rgba(59,130,246,0.2)] md:-translate-y-2' : 'border-white/10' }}">
                    
                    @if($isFeatured)
                        <div class="absolute top-0 right-0 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-[10px] font-extrabold px-4 py-1.5 rounded-bl-2xl uppercase tracking-wider">
                            Best Value & Recommended
                        </div>
                    @endif

                    <div class="space-y-6">
                        <div class="w-full h-40 rounded-2xl overflow-hidden relative">
                            <img src="{{ $imgSrc }}" alt="{{ $pkg->name }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-transparent to-transparent"></div>
                        </div>

                        @php
                            $isTicketPkg = str_contains(strtolower((string)($pkg->slug ?? '')), 'ticket') || str_contains(strtolower((string)($pkg->name ?? '')), 'ticket');
                            $pricingUnit = $isTicketPkg ? '/ 1x' : '/ lifetime';
                        @endphp
                        <div>
                            <div class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ $pkg->slug }}</div>
                            <h3 class="font-display text-3xl text-white tracking-wide uppercase">{{ $pkg->name }}</h3>
                            <div class="font-display text-4xl text-white mt-2">
                                Rp {{ $priceFormatted }}
                                <span class="text-xs font-sans text-gray-400 font-normal">{{ $pricingUnit }}</span>
                            </div>
                        </div>


                        <ul class="space-y-3 text-xs text-gray-300 font-medium">
                            @foreach($benefits as $b)
                                <li class="flex items-start gap-2.5">
                                    <i class="fa-solid fa-circle-check text-blue-400 text-xs mt-0.5 flex-shrink-0"></i>
                                    <span>{{ $b }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="pt-8">
                        @php
                            $isCoachingPkg = ($pkg->slug ?? null) === config('coaching.coaching_package_slug', 'coaching-ticket');
                            $userHasLmsAccess = $isLoggedIn && auth()->user()->hasLmsAccess();
                        @endphp

                        @if($userHasLmsAccess && !$isCoachingPkg)
                            <a href="{{ route('lms.dashboard') }}"
                               class="w-full py-4 rounded-2xl font-bold text-xs uppercase tracking-wider text-center flex items-center justify-center gap-2 shadow-lg transition-all bg-emerald-600 hover:bg-emerald-500 text-white shadow-emerald-600/30">
                                <span>Access Your Course</span>
                                <i class="fa-solid fa-graduation-cap text-[10px]"></i>
                            </a>
                        @else
                            <a href="{{ $isLoggedIn ? route('kelas.buy',$lessonId).'?package_id='.$pkg->id.'&package_qty=1' : route('register').'?package_id='.$pkg->id.'&package_qty=1' }}"
                               class="w-full py-4 rounded-2xl font-bold text-xs uppercase tracking-wider text-center flex items-center justify-center gap-2 shadow-lg transition-all {{ $isFeatured ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-blue-600/30 hover:scale-105' : 'bg-zinc-900 border border-white/10 hover:bg-white/5 text-white' }}">
                                <span>Get Enrolled Now</span>
                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>
                        @endif
                    </div>

                </div>
            @endforeach
        </div>
    </section>

    <!-- ─── FAQ SECTION ────────────────────────────────────────────────── -->
    <section id="faq" class="py-20 px-4 lg:px-8 max-w-5xl mx-auto space-y-12 relative z-10">
        <div class="text-center space-y-3">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-bold uppercase tracking-widest">
                Got Questions?
            </div>
            <h2 class="font-display text-4xl sm:text-5xl text-white tracking-wide uppercase">
                Frequently Asked <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">Questions</span>
            </h2>
            <p class="text-gray-400 text-xs sm:text-sm max-w-xl mx-auto">
                Everything you need to know about Guitarclassbynde, course access, practice tools, and 1-on-1 coaching.
            </p>
        </div>

        @php
            $defaultFaqs = [
                [
                    'question' => 'What is Guitarclassbynde?',
                    'answer' => 'Guitarclassbynde is an elite, structured online guitar learning platform founded by Nde. It combines step-by-step video courses, interactive practice tools (Tuner, Metronome, Chord & Scale visualizers), song library tutorials, and live 1-on-1 coaching sessions.'
                ],
                [
                    'question' => 'How long do I get access to my purchased courses?',
                    'answer' => 'Once enrolled, you get lifetime access to all course modules, materials, and practice suite tools included in your package. You can learn at your own pace whenever you want.'
                ],
                [
                    'question' => 'Do I need prior guitar experience to get started?',
                    'answer' => 'No experience needed! Our courses start from absolute beginner fundamentals (holding the guitar, open chords, basic strumming) all the way up to advanced soloing, speed picking, and fretboard theory.'
                ],
                [
                    'question' => 'How do 1-on-1 Coaching Sessions work?',
                    'answer' => 'Coaching sessions are conducted live directly inside our platform’s built-in interactive Video Call Room! Pick an open time slot in your dashboard, and when your session starts, click "Join Video Session" to meet live with Nde.'
                ],

                [
                    'question' => 'What payment channels are supported?',
                    'answer' => 'We accept instant, automated payments via Midtrans including Bank Transfer (Virtual Accounts for BCA, Mandiri, BNI, BRI), QRIS, GoPay, ShopeePay, and major Credit Cards.'
                ],
            ];

            $faqsToDisplay = [];
            if (isset($faq_items) && count($faq_items) > 0) {
                foreach ($faq_items as $item) {
                    $faqsToDisplay[] = [
                        'question' => $item->question ?? $item->title ?? '',
                        'answer' => $item->answer ?? $item->content ?? ''
                    ];
                }
                $faqsToDisplay = array_slice($faqsToDisplay, 0, 4);
            } else {
                $faqsToDisplay = array_slice($defaultFaqs, 0, 4);
            }
        @endphp


        <div class="space-y-4" x-data="{ active: 0 }">
            @foreach($faqsToDisplay as $idx => $faq)
                <div class="glass-panel rounded-2xl border border-white/10 overflow-hidden transition-all duration-300"
                     :class="{ 'border-blue-500/40 bg-zinc-950/80 shadow-[0_0_30px_rgba(59,130,246,0.15)]': active === {{ $idx }} }">
                    <button @click="active = (active === {{ $idx }} ? null : {{ $idx }})" 
                            class="w-full p-5 sm:p-6 text-left flex items-center justify-between gap-4 cursor-pointer hover:bg-white/5 transition-colors focus:outline-none">
                        <span class="font-bold text-white text-sm sm:text-base flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs flex items-center justify-center font-mono font-bold shrink-0 shadow-sm transition-all"
                                  :class="{ 'bg-blue-600 text-white border-blue-500 shadow-blue-600/30': active === {{ $idx }} }">
                                {{ sprintf('%02d', $idx + 1) }}
                            </span>
                            <span class="transition-colors" :class="{ 'text-blue-300': active === {{ $idx }} }">
                                {{ is_array($faq) ? $faq['question'] : ($faq->question ?? '') }}
                            </span>
                        </span>
                        <div class="w-8 h-8 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 shrink-0 transition-all duration-300"
                             :class="{ 'rotate-180 text-blue-400 bg-blue-500/10 border-blue-500/20 shadow-md': active === {{ $idx }} }">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </div>
                    </button>
                    <div x-show="active === {{ $idx }}" 
                         x-collapse
                         class="px-5 sm:px-6 pt-2 pb-6 sm:pb-8 text-xs sm:text-sm text-gray-300 leading-relaxed">
                        <div class="p-5 sm:p-6 rounded-2xl bg-white/[0.03] border border-white/5 text-gray-300 leading-relaxed shadow-inner my-1">
                            {{ is_array($faq) ? $faq['answer'] : ($faq->answer ?? '') }}
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        <!-- View All FAQs Button -->
        <div class="text-center pt-2">
            <a href="{{ route('faq') }}" class="inline-flex items-center gap-2.5 py-3 px-8 rounded-full bg-white/5 border border-white/10 hover:border-blue-500/50 hover:bg-blue-500/10 text-xs font-bold text-gray-300 hover:text-white transition-all shadow-md group">
                <span>View All FAQs</span>
                <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </section>

    <!-- ─── FINAL HIGH-IMPACT SALES CTA FOOTER ──────────────────────────── -->
    <section class="py-20 px-4 lg:px-8 max-w-5xl mx-auto relative z-10">
        <div class="glass-panel p-10 sm:p-16 text-center space-y-8 relative overflow-hidden bg-gradient-to-br from-blue-900/30 via-zinc-950 to-indigo-900/20 border-blue-500/30">
            <div class="max-w-2xl mx-auto space-y-4">
                <h2 class="font-display text-4xl sm:text-6xl text-white tracking-wide uppercase leading-none">
                    Ready to Play Guitar <span class="text-blue-400">Confidently?</span>
                </h2>
                <p class="text-gray-300 text-xs sm:text-sm leading-relaxed">
                    Join over 1,200+ students who have transformed their guitar playing with Nde's direct mentorship.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ url('/registerclass') }}" class="px-9 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold rounded-2xl text-sm shadow-xl shadow-blue-600/30 transition-all hover:scale-105 flex items-center justify-center gap-3">
                    <span>Get Instant Enrollment Access</span>
                    <i class="fa-solid fa-rocket"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- FOOTER COPYRIGHT -->
    <footer class="py-8 text-center text-xs text-gray-500 border-t border-white/5 relative z-10">
        <p>&copy; {{ date('Y') }} GUITARCLASSBYNDE. All rights reserved.</p>
    </footer>

    @include('components.ai_chatbot')
</body>
</html>


