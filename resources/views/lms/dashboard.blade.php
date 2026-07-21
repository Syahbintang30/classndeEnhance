@extends('layouts.app')

@section('title', 'Student Dashboard')

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
                    <i class="fa-solid fa-bolt text-amber-400"></i> Keep Up The Momentum
                </div>
                <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl text-white tracking-wide uppercase leading-none">
                    @php $firstName = explode(' ', auth()->user()->name ?? 'STUDENT')[0]; @endphp
                    WELCOME BACK, <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-400 via-indigo-300 to-white">{{ $firstName }}</span>!
                </h1>
                <p class="text-gray-400 text-sm mt-2 max-w-xl">
                    You're on track to master the guitar. Practice for 15 minutes today to keep your streak alive!
                </p>
            </div>

            <!-- Quick Resume Button Pill -->
            <a href="{{ route('kelas') }}" class="glass-panel p-3.5 px-5 flex items-center gap-4 hover:border-blue-500/40 transition group max-w-xs self-start md:self-auto">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center text-white text-lg shadow-lg group-hover:scale-105 transition-transform flex-shrink-0">
                    <i class="fa-solid fa-play ml-0.5"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-[10px] font-bold text-blue-400 uppercase tracking-widest">Resume Lesson</div>
                    <div class="text-sm font-bold text-white truncate">Continue Learning</div>
                    <div class="text-xs text-gray-400">Pick up where you left off</div>
                </div>
            </a>
        </div>

        <!-- MAIN 2-COLUMN LAYOUT -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- LEFT COLUMN: PROGRESS & MENTOR REVIEW (8 Cols) -->
            <div class="lg:col-span-7 xl:col-span-8 space-y-8">
                
                <!-- ROADMAP & PROGRESS CARD -->
                <div class="glass-panel p-6 sm:p-8 relative overflow-hidden">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                        <div>
                            <div class="text-xs uppercase font-bold text-blue-400 tracking-wider flex items-center gap-2 mb-1">
                                <i class="fa-solid fa-graduation-cap"></i> Curriculum Progress
                            </div>
                            <h3 class="text-2xl font-bold text-white">Guitar Mastery Roadmap</h3>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span class="font-display text-5xl text-blue-400">{{ $progressPercent ?? 0 }}%</span>
                            <span class="text-xs text-gray-400 font-semibold">({{ $completedTopics ?? 0 }}/{{ $totalTopics ?? 0 }} Completed)</span>
                        </div>
                    </div>

                    <!-- Custom Glowing Progress Bar -->
                    <div class="w-full bg-zinc-950/80 rounded-full h-3.5 p-0.5 overflow-hidden border border-white/5">
                        <div class="bg-gradient-to-r from-blue-600 via-indigo-500 to-cyan-400 h-full rounded-full transition-all duration-500 shadow-[0_0_15px_rgba(59,130,246,0.5)]" style="width: {{ max(0, min(100, (int) ($progressPercent ?? 0))) }}%"></div>
                    </div>

                    <!-- Stats Strip inside Roadmap -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-6 pt-6 border-t border-white/5">
                        <div class="bg-zinc-950/40 rounded-xl p-3 border border-white/5">
                            <span class="text-xs text-gray-400 block mb-1">Completed Topics</span>
                            <span class="font-bold text-white text-lg">{{ $completedTopics ?? 0 }}</span>
                        </div>
                        <div class="bg-zinc-950/40 rounded-xl p-3 border border-white/5">
                            <span class="text-xs text-gray-400 block mb-1">Remaining</span>
                            <span class="font-bold text-gray-300 text-lg">{{ max(0, ($totalTopics ?? 0) - ($completedTopics ?? 0)) }}</span>
                        </div>
                        <div class="bg-zinc-950/40 rounded-xl p-3 border border-white/5 col-span-2 sm:col-span-1">
                            <span class="text-xs text-gray-400 block mb-1">Next Step</span>
                            <a href="{{ route('kelas') }}" class="font-bold text-blue-400 text-sm hover:underline flex items-center gap-1 mt-0.5">
                                Go to Lessons <i class="fa-solid fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- LATEST MENTOR FEEDBACK -->
                <div class="glass-panel p-6 sm:p-8">
                    <div class="flex items-center justify-between mb-5">
                        <h4 class="text-lg font-bold text-white flex items-center gap-2">
                            <i class="fa-solid fa-comment-dots text-blue-400"></i> Latest Mentor Video Review
                        </h4>
                        <span class="text-xs font-semibold text-gray-400 bg-white/5 px-3 py-1 rounded-full">Personalized</span>
                    </div>

                    <div class="bg-zinc-950/60 border border-white/5 rounded-2xl p-5 flex flex-col sm:flex-row gap-5 items-center">
                        <div class="w-full sm:w-44 h-32 bg-zinc-900 rounded-xl relative overflow-hidden flex-shrink-0 flex items-center justify-center border border-white/5 group cursor-pointer">
                            <img src="https://images.unsplash.com/photo-1510915361894-db8b60106cb1?auto=format&fit=crop&w=300&q=80" class="w-full h-full object-cover opacity-60 group-hover:scale-105 transition-transform duration-500" alt="Video Review">
                            <div class="absolute inset-0 bg-blue-950/30 flex items-center justify-center">
                                <div class="w-11 h-11 rounded-full bg-blue-600/90 flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform">
                                    <i class="fa-solid fa-play text-sm"></i>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2 flex-1 text-center sm:text-left">
                            <div class="flex items-center justify-center sm:justify-between gap-2">
                                <span class="text-[10px] font-bold text-blue-400 bg-blue-500/10 px-2.5 py-0.5 rounded-full border border-blue-500/20">INFO</span>
                                <span class="text-xs text-gray-400">Mentor: <strong class="text-white">Nde</strong></span>
                            </div>
                            <h5 class="text-base font-bold text-white">How Video Review Works</h5>
                            <p class="text-xs text-gray-400 leading-relaxed">
                                Submit your practice video or join 1-on-1 coaching for personalized picking technique and speed feedback directly from Mentor Nde.
                            </p>
                            <div class="pt-2">
                                <a href="{{ route('coaching.upcoming') }}" class="text-xs text-blue-400 hover:text-blue-300 font-bold transition inline-flex items-center gap-1.5">
                                    <span>Book a session now</span> <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN: COMPACT FLOATING HUB DOCK (4 Cols) -->
            <div class="lg:col-span-5 xl:col-span-4 space-y-6">
                
                <div class="text-xs uppercase font-bold text-gray-400 tracking-wider flex items-center gap-2 px-1">
                    <i class="fa-solid fa-sliders text-indigo-400"></i> Quick Hub & Tools
                </div>

                <!-- FLOATING HUB ITEM 1: 1-ON-1 COACHING & TICKETS -->
                <div class="glass-panel p-5 glass-panel-glow transition duration-300">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center text-base">
                                <i class="fa-solid fa-ticket"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-white">1-on-1 Coaching</h4>
                                <div class="text-xs text-gray-400">Personal Video Session</div>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-amber-400 bg-amber-500/10 border border-amber-500/20 px-2.5 py-1 rounded-full">
                            {{ $availableTicketCount ?? 0 }} Tickets
                        </span>
                    </div>

                    <div class="bg-zinc-950/50 rounded-xl p-3 my-3 border border-white/5 flex items-center justify-between text-xs">
                        <span class="text-gray-400">Scheduled Session:</span>
                        <span class="font-semibold {{ ($upcomingCoachingCount ?? 0) > 0 ? 'text-emerald-400' : 'text-gray-400' }}">
                            {{ ($upcomingCoachingCount ?? 0) > 0 ? ($upcomingCoachingCount.' Upcoming') : 'None Booked' }}
                        </span>
                    </div>

                    <a href="{{ route('coaching.upcoming') }}" class="w-full py-2.5 rounded-xl bg-gradient-to-r from-amber-500/20 to-amber-600/20 hover:from-amber-500/30 hover:to-amber-600/30 border border-amber-500/30 text-amber-300 text-xs font-bold transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-calendar-plus text-[10px]"></i>
                        <span>Book Session / View Details</span>
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
                                <h4 class="text-sm font-bold text-white">Song Vault</h4>
                                <div class="text-xs text-gray-400">Interactive TABS & Tracks</div>
                            </div>
                        </div>
                        @if(auth()->check() && auth()->user()->hasIntermediateAccess())
                            <span class="text-[10px] font-bold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-1 rounded-full">Unlocked</span>
                        @else
                            <span class="text-[10px] font-bold text-red-400 bg-red-500/10 border border-red-500/20 px-2.5 py-1 rounded-full">Locked</span>
                        @endif
                    </div>

                    @if(auth()->check() && auth()->user()->hasIntermediateAccess())
                        <a href="{{ route('song.tutorial.index') }}" class="w-full py-2.5 rounded-xl bg-purple-600/20 hover:bg-purple-600/30 border border-purple-500/30 text-purple-300 text-xs font-bold transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-guitar text-[10px]"></i>
                            <span>Explore Song Tutorials</span>
                        </a>
                    @else
                        <a href="{{ route('registerclass') }}" class="w-full py-2.5 rounded-xl bg-zinc-800 hover:bg-zinc-700 text-gray-300 text-xs font-bold transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-lock text-[10px]"></i>
                            <span>Upgrade Package to Unlock</span>
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
                                <h4 class="text-sm font-bold text-white">Practice Suite</h4>
                                <div class="text-xs text-gray-400">Tuner, Metronome & Chords</div>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-1 rounded-full">
                            3 Tools
                        </span>
                    </div>

                    <a href="{{ route('practice.index') }}" class="w-full py-2.5 rounded-xl bg-emerald-600/20 hover:bg-emerald-600/30 border border-emerald-500/30 text-emerald-300 text-xs font-bold transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-sliders text-[10px]"></i>
                        <span>Open Practice Tools Hub</span>
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
@endsection
