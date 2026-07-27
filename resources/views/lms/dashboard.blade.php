@extends('layouts.app')

@php
    $isEn = (session('app_lang', request('lang', 'id')) === 'en');
@endphp

@section('title', $isEn ? 'Student Dashboard' : 'Dashboard Murid')

@push('head')
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
                            border: 'rgba(255, 255, 255, 0.07)',
                            accent: '#0066ff',
                            amber: '#f59e0b',
                            crimson: '#ef4444'
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
        .tw-dash {
            background-color: #08080a !important;
            color: #f3f4f6 !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
        }
        .tw-dash .font-display {
            font-family: 'Bebas Neue', cursive;
            letter-spacing: 1px;
        }
        .glass-panel {
            background: rgba(18, 18, 26, 0.55);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 1.5rem;
        }
        .glass-panel-glow:hover {
            border-color: rgba(59, 130, 246, 0.4);
            box-shadow: 0 0 30px rgba(59, 130, 246, 0.15);
        }
        body > nav { display: none !important; }
        .tw-dash { min-height: 100vh; }
        .tw-dash ::-webkit-scrollbar { width: 6px; }
        .tw-dash ::-webkit-scrollbar-track { background: #08080a; }
        .tw-dash ::-webkit-scrollbar-thumb { background: #222232; border-radius: 3px; }
        .tw-dash a { text-decoration: none; }
        .tw-dash *:focus { outline: none !important; }
    </style>
@endpush

@section('content')
<div class="tw-dash min-h-screen flex flex-col antialiased bg-[#08080a] text-gray-200 relative overflow-hidden"
     x-data="lmsApp()"
     x-init="initAudio()">

    {{-- Ambient Mesh Glow Background Elements --}}
    <div class="absolute -top-32 left-1/4 w-[500px] h-[500px] bg-blue-600/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute top-1/3 -right-32 w-[400px] h-[400px] bg-purple-600/10 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute -bottom-32 left-10 w-[450px] h-[450px] bg-amber-500/10 rounded-full blur-[120px] pointer-events-none"></div>

    {{-- ─── TOP NAVIGATION BAR ──────────────────────────────────────────── --}}
    @include('layouts.lms_header')

    <!-- DASHBOARD CONTAINER -->
    <div class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative z-10">
        
        <!-- TOP HEADER GREETING (Seamless, No heavy box) -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-bold uppercase tracking-wider mb-3">
                    <i class="fa-solid fa-bolt text-blue-400"></i> {{ $isEn ? 'Keep Up The Momentum' : 'Pertahankan Semangat Latihan' }}
                </div>
                <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl text-white tracking-wide uppercase leading-none">
                    @php $firstName = strtoupper(explode(' ', auth()->user()->name ?? 'STUDENT')[0]); @endphp
                    @if($isEn)
                        WELCOME BACK, <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-400 via-indigo-300 to-white">{{ $firstName }}</span>!
                    @else
                        SELAMAT DATANG, <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-400 via-indigo-300 to-white">{{ $firstName }}</span>!
                    @endif
                </h1>
                <p class="text-gray-400 text-sm mt-2 max-w-xl">
                    {{ $isEn ? 'You\'re on track to master the guitar. Practice for 15 minutes today to keep your streak alive!' : 'Kamu sudah berada di alur yang benar untuk menguasai gitar. Luangkan 15 menit latihan hari ini!' }}
                </p>
            </div>

            <!-- Quick Resume Button Pill -->
            <a href="{{ $coursesUrl ?? route('kelas') }}" class="glass-panel p-3.5 px-5 flex items-center gap-4 hover:border-blue-500/40 transition group max-w-xs self-start md:self-auto">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center text-white text-lg shadow-lg group-hover:scale-105 transition-transform flex-shrink-0">
                    <i class="fa-solid fa-play ml-0.5"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-[10px] font-bold text-blue-400 uppercase tracking-widest">{{ $isEn ? 'Resume Lesson' : 'Lanjutkan Modul' }}</div>
                    <div class="text-sm font-bold text-white truncate" title="{{ $resumeTopic->title ?? ($isEn ? 'Continue Learning' : 'Lanjutkan Belajar') }}">
                        {{ $resumeTopic->title ?? ($isEn ? 'Continue Learning' : 'Lanjutkan Belajar') }}
                    </div>
                    <div class="text-xs text-gray-400 truncate" title="{{ $resumeLesson->title ?? ($isEn ? 'Pick up where you left off' : 'Mulai dari materi terakhirmu') }}">
                        {{ $resumeLesson->title ?? ($isEn ? 'Pick up where you left off' : 'Mulai dari materi terakhirmu') }}
                    </div>
                </div>
            </a>
        </div>


        <!-- MAIN 2-COLUMN LAYOUT -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- LEFT COLUMN: PROGRESS & MENTOR REVIEW (8 Cols) -->
            <div class="lg:col-span-7 xl:col-span-8 space-y-8">
                
                @if(! auth()->check() || ! auth()->user()->isPaidMember())
                    <!-- FREE TRIAL MEMBER BANNER -->
                    <div class="glass-panel p-5 border-amber-500/30 bg-gradient-to-r from-amber-500/10 via-zinc-950 to-blue-500/10 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-xl">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-amber-500/20 border border-amber-500/30 text-amber-400 flex items-center justify-center text-xl shrink-0">
                                <i class="fa-solid fa-lock-open"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-extrabold text-amber-400 bg-amber-500/10 px-2.5 py-0.5 rounded-full border border-amber-500/20 uppercase tracking-widest">Free Trial</span>
                                    <span class="text-xs text-gray-400 font-semibold">{{ $isEn ? 'Preview Unlocked' : 'Pratinjau Terbuka' }}</span>
                                </div>
                                <h4 class="text-base font-bold text-white mt-1">{{ $isEn ? 'You are currently in Free Trial Mode' : 'Kamu saat ini dalam Mode Akses Gratis (Free Trial)' }}</h4>
                                <p class="text-xs text-gray-400 leading-relaxed">
                                    {{ $isEn ? 'Enjoy free preview lessons. Upgrade your membership to get Full Access.' : 'Nikmati modul pratinjau gratis. Tingkatkan paketmu untuk mendapatkan Akses Penuh.' }}
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('registerclass') }}" class="px-5 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-black font-extrabold rounded-xl text-xs uppercase tracking-wider transition transform hover:scale-105 shrink-0 flex items-center gap-2 shadow-lg shadow-amber-500/20">
                            <i class="fa-solid fa-bolt"></i>
                            <span>{{ $isEn ? 'Get Full Access' : 'Beli Akses Penuh' }}</span>
                        </a>
                    </div>
                @endif

                <!-- ROADMAP & PROGRESS CARD -->
                <div class="glass-panel p-6 sm:p-8 relative overflow-hidden">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                        <div>
                            <div class="text-xs uppercase font-bold text-blue-400 tracking-wider flex items-center gap-2 mb-1">
                                <i class="fa-solid fa-graduation-cap"></i> {{ $isEn ? 'Curriculum Progress' : 'Progres Kurikulum' }}
                            </div>
                            <h3 class="text-2xl font-bold text-white">{{ $isEn ? 'Guitar Mastery Roadmap' : 'Peta Jalan Penguasaan Gitar' }}</h3>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span class="font-display text-5xl text-blue-400">{{ $progressPercent ?? 0 }}%</span>
                            <span class="text-xs text-gray-400 font-semibold">({{ $completedTopics ?? 0 }}/{{ $totalTopics ?? 0 }} {{ $isEn ? 'Completed' : 'Selesai' }})</span>
                        </div>
                    </div>

                    <!-- Custom Glowing Progress Bar -->
                    <div class="w-full bg-zinc-950/80 rounded-full h-3.5 p-0.5 overflow-hidden border border-white/5">
                        <div class="bg-gradient-to-r from-blue-600 via-indigo-500 to-cyan-400 h-full rounded-full transition-all duration-500 shadow-[0_0_15px_rgba(59,130,246,0.5)]" style="width: {{ max(0, min(100, (int) ($progressPercent ?? 0))) }}%"></div>
                    </div>

                    <!-- Stats Strip inside Roadmap -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-6 pt-6 border-t border-white/5">
                        <div class="bg-zinc-950/40 rounded-xl p-3 border border-white/5">
                            <span class="text-xs text-gray-400 block mb-1">{{ $isEn ? 'Completed Topics' : 'Materi Selesai' }}</span>
                            <span class="font-bold text-white text-lg">{{ $completedTopics ?? 0 }}</span>
                        </div>
                        <div class="bg-zinc-950/40 rounded-xl p-3 border border-white/5">
                            <span class="text-xs text-gray-400 block mb-1">{{ $isEn ? 'Remaining' : 'Tersisa' }}</span>
                            <span class="font-bold text-gray-300 text-lg">{{ max(0, ($totalTopics ?? 0) - ($completedTopics ?? 0)) }}</span>
                        </div>
                        <div class="bg-zinc-950/40 rounded-xl p-3 border border-white/5 col-span-2 sm:col-span-1">
                            <span class="text-xs text-gray-400 block mb-1">{{ $isEn ? 'Next Step' : 'Langkah Selanjutnya' }}</span>
                            <a href="{{ route('kelas') }}" class="font-bold text-blue-400 text-sm hover:underline flex items-center gap-1 mt-0.5">
                                {{ $isEn ? 'Go to Lessons' : 'Buka Modul Kelas' }} <i class="fa-solid fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- RECOMMENDED PRACTICE ROUTINE CARD -->
                <div class="glass-panel p-6 sm:p-8">
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center gap-2">
                            <span class="text-xs uppercase font-bold text-blue-400 tracking-wider flex items-center gap-2">
                                <i class="fa-solid fa-fire text-amber-400"></i> {{ $isEn ? 'Daily Practice Routine' : 'Rutinitas Latihan Harian' }}
                            </span>
                        </div>
                        <span class="text-[10px] font-extrabold text-blue-400 bg-blue-500/10 px-2.5 py-1 rounded-full border border-blue-500/20 uppercase tracking-widest">{{ $isEn ? 'Recommended' : 'Rekomendasi' }}</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <!-- Routine Item 1 -->
                        <div class="bg-zinc-950/60 border border-white/5 rounded-2xl p-4 flex flex-col justify-between space-y-3 group hover:border-blue-500/30 transition">
                            <div class="flex items-center justify-between">
                                <div class="w-9 h-9 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center text-sm">
                                    <i class="fa-solid fa-guitar"></i>
                                </div>
                                <span class="text-[10px] font-bold text-gray-500">STEP 01</span>
                            </div>
                            <div>
                                <h5 class="text-xs font-bold text-white mb-1">{{ $isEn ? 'Standard Tuning' : 'Setem Gitar' }}</h5>
                                <p class="text-[11px] text-gray-400 leading-relaxed">{{ $isEn ? 'Check guitar tuning with mic pitch detection.' : 'Setem gitar dengan deteksi nada mikrofon presisi.' }}</p>
                            </div>
                            <a href="{{ route('practice.tuner') }}" class="text-[11px] font-bold text-blue-400 hover:text-blue-300 flex items-center gap-1 transition">
                                <span>{{ $isEn ? 'Tune Guitar' : 'Setem Gitar' }}</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
                            </a>
                        </div>

                        <!-- Routine Item 2 -->
                        <div class="bg-zinc-950/60 border border-white/5 rounded-2xl p-4 flex flex-col justify-between space-y-3 group hover:border-emerald-500/30 transition">
                            <div class="flex items-center justify-between">
                                <div class="w-9 h-9 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-sm">
                                    <i class="fa-solid fa-stopwatch"></i>
                                </div>
                                <span class="text-[10px] font-bold text-gray-500">STEP 02</span>
                            </div>
                            <div>
                                <h5 class="text-xs font-bold text-white mb-1">{{ $isEn ? 'Rhythm & Speed' : 'Ritme & Kecepatan' }}</h5>
                                <p class="text-[11px] text-gray-400 leading-relaxed">{{ $isEn ? 'Practice alternate picking with metronome click.' : 'Latih ketukan & picking dengan ketukan metronom.' }}</p>
                            </div>
                            <a href="{{ route('practice.metronome') }}" class="text-[11px] font-bold text-emerald-400 hover:text-emerald-300 flex items-center gap-1 transition">
                                <span>{{ $isEn ? 'Launch Metronome' : 'Buka Metronom' }}</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
                            </a>
                        </div>

                        <!-- Routine Item 3 -->
                        <div class="bg-zinc-950/60 border border-white/5 rounded-2xl p-4 flex flex-col justify-between space-y-3 group hover:border-amber-500/30 transition">
                            <div class="flex items-center justify-between">
                                <div class="w-9 h-9 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center text-sm">
                                    <i class="fa-solid fa-user-ninja"></i>
                                </div>
                                <span class="text-[10px] font-bold text-gray-500">STEP 03</span>
                            </div>
                            <div>
                                <h5 class="text-xs font-bold text-white mb-1">{{ $isEn ? 'Mentor Feedback' : 'Bimbingan Mentor' }}</h5>
                                <p class="text-[11px] text-gray-400 leading-relaxed">{{ $isEn ? 'Join 1-on-1 private video review session.' : 'Ikuti sesi video call review privat 1-on-1.' }}</p>
                            </div>
                            <a href="{{ route('coaching.upcoming') }}" class="text-[11px] font-bold text-amber-400 hover:text-amber-300 flex items-center gap-1 transition">
                                <span>{{ $isEn ? 'Book Session' : 'Jadwalkan Sesi' }}</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN: COMPACT FLOATING HUB DOCK (4 Cols) -->
            <div class="lg:col-span-5 xl:col-span-4 space-y-6">
                
                <div class="text-xs uppercase font-bold text-gray-400 tracking-wider flex items-center gap-2 px-1">
                    <i class="fa-solid fa-sliders text-indigo-400"></i> {{ $isEn ? 'Quick Hub & Tools' : 'Menu & Tools Cepat' }}
                </div>

                <!-- FLOATING HUB ITEM 1: 1-ON-1 COACHING & TICKETS -->
                <div class="glass-panel p-5 glass-panel-glow transition duration-300">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center text-base">
                                <i class="fa-solid fa-ticket"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-white">Coaching 1-on-1</h4>
                                <div class="text-xs text-gray-400">{{ $isEn ? 'Personal Video Session' : 'Sesi Video Call Privat' }}</div>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-amber-400 bg-amber-500/10 border border-amber-500/20 px-2.5 py-1 rounded-full">
                            {{ $availableTicketCount ?? 0 }} {{ $isEn ? 'Tickets' : 'Tiket Sesi' }}
                        </span>
                    </div>

                    <div class="bg-zinc-950/50 rounded-xl p-3 my-3 border border-white/5 flex items-center justify-between text-xs">
                        <span class="text-gray-400">{{ $isEn ? 'Scheduled Session:' : 'Jadwal Sesi:' }}</span>
                        <span class="font-semibold {{ ($upcomingCoachingCount ?? 0) > 0 ? 'text-emerald-400' : 'text-gray-400' }}">
                            @if(($upcomingCoachingCount ?? 0) > 0)
                                {{ $upcomingCoachingCount }} {{ $isEn ? 'Upcoming' : 'Terjadwal' }}
                            @else
                                {{ $isEn ? 'None Booked' : 'Belum Ada Jadwal' }}
                            @endif
                        </span>
                    </div>

                    <a href="{{ route('coaching.upcoming') }}" class="w-full py-2.5 rounded-xl bg-gradient-to-r from-amber-500/20 to-amber-600/20 hover:from-amber-500/30 hover:to-amber-600/30 border border-amber-500/30 text-amber-300 text-xs font-bold transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-calendar-plus text-[10px]"></i>
                        <span>{{ $isEn ? 'Book Session / View Details' : 'Pesan Sesi / Lihat Detail' }}</span>
                    </a>
                </div>

                <!-- FLOATING HUB ITEM 2: SONG TUTORIALS VAULT -->
                <div class="glass-panel p-5 glass-panel-glow transition duration-300">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center text-base">
                                <i class="fa-solid fa-compact-disc"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-white">{{ $isEn ? 'Song Vault' : 'Pustaka Lagu' }}</h4>
                                <div class="text-xs text-gray-400">{{ $isEn ? 'Interactive TABS & Tracks' : 'TAB & Tutorial Interaktif' }}</div>
                            </div>
                        </div>
                        @if(auth()->check() && auth()->user()->hasIntermediateAccess())
                            <span class="text-[10px] font-bold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-1 rounded-full">{{ $isEn ? 'Unlocked' : 'Terbuka' }}</span>
                        @else
                            <span class="text-[10px] font-bold text-red-400 bg-red-500/10 border border-red-500/20 px-2.5 py-1 rounded-full">{{ $isEn ? 'Locked' : 'Terkunci' }}</span>
                        @endif
                    </div>

                    @if(auth()->check() && auth()->user()->hasIntermediateAccess())
                        <a href="{{ route('song.tutorial.index') }}" class="w-full py-2.5 rounded-xl bg-purple-600/20 hover:bg-purple-600/30 border border-purple-500/30 text-purple-300 text-xs font-bold transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-guitar text-[10px]"></i>
                            <span>{{ $isEn ? 'Explore Song Tutorials' : 'Buka Pustaka Lagu' }}</span>
                        </a>
                    @else
                        <a href="{{ route('registerclass') }}" class="w-full py-2.5 rounded-xl bg-zinc-800 hover:bg-zinc-700 text-gray-300 text-xs font-bold transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-lock text-[10px]"></i>
                            <span>{{ $isEn ? 'Upgrade Package to Unlock' : 'Upgrade Paket Untuk Membuka' }}</span>
                        </a>
                    @endif
                </div>

                <!-- FLOATING HUB ITEM 3: PRACTICE TOOLS SUITE -->
                <div class="glass-panel p-5 glass-panel-glow transition duration-300">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-base">
                                <i class="fa-solid fa-toolbox"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-white">{{ $isEn ? 'Practice Suite' : 'Tools Latihan' }}</h4>
                                <div class="text-xs text-gray-400">{{ $isEn ? 'Tuner, Metronome & Chords' : 'Tuner, Metronom & Chord' }}</div>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-1 rounded-full">
                            {{ $isEn ? '3 Tools' : '3 Alat Latihan' }}
                        </span>
                    </div>

                    <a href="{{ route('practice.index') }}" class="w-full py-2.5 rounded-xl bg-emerald-600/20 hover:bg-emerald-600/30 border border-emerald-500/30 text-emerald-300 text-xs font-bold transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-sliders text-[10px]"></i>
                        <span>{{ $isEn ? 'Open Practice Tools Hub' : 'Buka Hub Tools Latihan' }}</span>
                    </a>
                </div>

            </div>

        </div>

    </div>

</div>

<script>
function lmsApp() {
    return {
        bpm: 120,
        metronomeRunning: false,
        timerId: null,
        audioCtx: null,

        initAudio() { },

        toggleMetronome() {
            this.metronomeRunning = !this.metronomeRunning;
            if (this.metronomeRunning) {
                this.startAudioMetronome();
            } else {
                clearInterval(this.timerId);
            }
        },

        restartIfRunning() {
            if (this.metronomeRunning) {
                clearInterval(this.timerId);
                this.startAudioMetronome();
            }
        },

        startAudioMetronome() {
            if (!this.audioCtx) {
                this.audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            }
            if (this.audioCtx.state === 'suspended') this.audioCtx.resume();

            const interval = 60000 / this.bpm;

            const playClick = () => {
                const osc  = this.audioCtx.createOscillator();
                const gain = this.audioCtx.createGain();
                osc.connect(gain);
                gain.connect(this.audioCtx.destination);
                osc.frequency.value = 800;
                gain.gain.setValueAtTime(1, this.audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, this.audioCtx.currentTime + 0.1);
                osc.start(this.audioCtx.currentTime);
                osc.stop(this.audioCtx.currentTime + 0.1);
            };

            playClick();
            this.timerId = setInterval(playClick, interval);
        }
    }
}
</script>

<!-- MENTOR VIDEO REVIEW MODAL -->
<div id="mentorVideoModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md hidden items-center justify-center p-4 sm:p-6">
    <div class="bg-[#181822] border border-white/10 rounded-2xl w-full max-w-3xl overflow-hidden shadow-2xl relative">
        <div class="px-6 py-4 border-b border-white/10 flex items-center justify-between">
            <h3 class="text-base font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-circle-play text-blue-400"></i> <span id="mentorModalTitle">Mentor Video Review</span>
            </h3>
            <button onclick="closeMentorVideoModal()" class="text-gray-400 hover:text-white p-2 rounded-lg bg-white/5 hover:bg-white/10 transition cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <div class="p-4 sm:p-6 bg-black">
            <div class="relative w-full aspect-video rounded-xl overflow-hidden bg-zinc-950 flex items-center justify-center">
                <iframe id="mentorVideoIframe" class="w-full h-full border-0 hidden" allowfullscreen allow="autoplay"></iframe>
                <video id="mentorVideoPlayer" class="w-full h-full hidden" controls autoplay></video>
                <div id="mentorVideoFallback" class="text-center p-8 hidden">
                    <i class="fa-solid fa-video-slash text-4xl text-gray-500 mb-3"></i>
                    <p class="text-sm text-gray-400">Video review stream is loading...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openMentorVideoModal(url, title) {
        const modal = document.getElementById('mentorVideoModal');
        const modalTitle = document.getElementById('mentorModalTitle');
        const iframe = document.getElementById('mentorVideoIframe');
        const video = document.getElementById('mentorVideoPlayer');
        const fallback = document.getElementById('mentorVideoFallback');

        if (title) modalTitle.textContent = title;

        iframe.classList.add('hidden');
        video.classList.add('hidden');
        fallback.classList.add('hidden');

        if (!url || url === '' || url === 'null') {
            fallback.classList.remove('hidden');
        } else if (url.includes('youtube.com') || url.includes('youtu.be') || url.includes('bunny.net') || url.includes('iframe') || url.includes('embed')) {
            iframe.src = url.includes('watch?v=') ? url.replace('watch?v=', 'embed/') : url;
            iframe.classList.remove('hidden');
        } else {
            video.src = url;
            video.classList.remove('hidden');
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeMentorVideoModal() {
        const modal = document.getElementById('mentorVideoModal');
        const iframe = document.getElementById('mentorVideoIframe');
        const video = document.getElementById('mentorVideoPlayer');

        iframe.src = '';
        video.pause();
        video.src = '';

        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endsection
