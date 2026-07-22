@extends('layouts.app')

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
                            border: 'rgba(255, 255, 255, 0.08)',
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
        body > nav { display: none !important; }
        .tw-dash ::-webkit-scrollbar { width: 6px; }
        .tw-dash ::-webkit-scrollbar-track { background: #08080a; }
        .tw-dash ::-webkit-scrollbar-thumb { background: #222232; border-radius: 3px; }
        .tw-dash a { text-decoration: none; }
        .tw-dash *:focus { outline: none !important; }

        @keyframes soundwave {
            0%, 100% { height: 4px; }
            50% { height: 16px; }
        }
        .bar-anim { height: 6px; }
        .group:hover .bar-anim { animation: soundwave 1s infinite ease-in-out; }
        .group:hover .bar-anim:nth-child(2) { animation-delay: 0.2s; }
        .group:hover .bar-anim:nth-child(3) { animation-delay: 0.4s; }
        .group:hover .bar-anim:nth-child(4) { animation-delay: 0.1s; }
    </style>
@endpush

@section('content')
<div class="tw-dash min-h-screen flex flex-col antialiased bg-[#08080a] text-gray-200 relative overflow-hidden" 
     x-data="{ mobileMenuOpen: false, playingNote: null }">

    {{-- Ambient Mesh Background Glow --}}
    <div class="absolute -top-32 left-1/3 w-[600px] h-[600px] bg-blue-600/10 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute top-1/2 -right-32 w-[450px] h-[450px] bg-emerald-500/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute -bottom-32 left-10 w-[450px] h-[450px] bg-purple-600/10 rounded-full blur-[120px] pointer-events-none"></div>

    {{-- ─── TOP NAVIGATION BAR ──────────────────────────────────────────── --}}
    @include('layouts.lms_header')

    <main class="flex-1 max-w-7xl mx-auto w-full px-4 lg:px-8 py-10 space-y-12 relative z-10">
        
        <!-- HEADER SECTION -->
        <header class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-bold uppercase tracking-wider mb-3">
                    <i class="fa-solid fa-toolbox"></i> Interactive Utility Suite
                </div>
                
                <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl tracking-wide text-white uppercase leading-none">
                    Practice <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-400 via-indigo-300 to-white">Tools</span>
                </h1>
                
                <p class="text-gray-400 text-sm max-w-xl mt-2 leading-relaxed">
                    Sharpen your skills with our suite of built-in practice utilities. Stay in tune, keep in time, and master the fretboard without ever leaving your dashboard.
                </p>
            </div>

            <!-- Feature Specs Badge -->
            <div class="flex items-center gap-3 bg-zinc-950/60 border border-white/10 rounded-2xl p-3 px-4 backdrop-blur-md self-start md:self-auto">
                <div class="w-10 h-10 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-microchip"></i>
                </div>
                <div>
                    <div class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Audio Engine</div>
                    <div class="text-xs font-bold text-white">Web Audio API Precision</div>
                </div>
            </div>
        </header>

        <!-- 5-CARD PRACTICE SUITE GRID (CENTERED BOTTOM ROW) -->
        <div class="flex flex-wrap justify-center gap-6">
            
            <!-- 0. Interactive Guitar Pitch Quiz Card -->
            <a href="{{ route('practice.quiz') }}" class="group glass-panel p-8 flex flex-col items-center text-center relative overflow-hidden transition-all duration-300 hover:border-amber-500/40 hover:shadow-[0_0_40px_rgba(245,158,11,0.2)] w-full md:w-[calc(50%-16px)] lg:w-[calc(33.333%-16px)] max-w-md lg:max-w-none">
                <div class="w-full flex items-center justify-between">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] uppercase font-bold text-amber-400 bg-amber-500/10 border border-amber-500/20">Gamified Rank Test</span>
                    <div class="flex items-end gap-1 h-4 opacity-40 group-hover:opacity-100 transition-opacity">
                        <span class="w-1 bg-amber-400 rounded-full bar-anim"></span>
                        <span class="w-1 bg-amber-400 rounded-full bar-anim"></span>
                        <span class="w-1 bg-amber-400 rounded-full bar-anim"></span>
                    </div>
                </div>

                <div class="w-20 h-20 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-400 group-hover:bg-gradient-to-br group-hover:from-amber-500 group-hover:to-orange-600 group-hover:text-black flex items-center justify-center text-3xl my-6 transition-all duration-300 shadow-lg shadow-amber-500/10 group-hover:shadow-amber-500/30 group-hover:scale-105">
                    <i class="fa-solid fa-gamepad"></i>
                </div>
                
                <h2 class="font-display text-3xl text-white mb-2 tracking-wide uppercase">Guitar Pitch Quiz</h2>
                <p class="text-gray-400 text-xs leading-relaxed mb-8 flex-1">
                    Play your real guitar to answer live note challenges. Earn XP, gain combos, and push your Guitarist Rank!
                </p>
                
                <div class="w-full py-3 rounded-xl bg-amber-500/20 group-hover:bg-gradient-to-r group-hover:from-amber-500 group-hover:to-orange-500 border border-amber-500/30 text-amber-300 group-hover:text-black text-xs font-bold transition-all flex items-center justify-center gap-2 shadow-md">
                    <span>Play Pitch Quiz</span>
                    <i class="fa-solid fa-play text-[10px] group-hover:translate-x-1 transition-transform"></i>
                </div>
            </a>

            <!-- 0.5. Songsterr-Style Interactive TAB Player Card -->
            <a href="{{ route('practice.guitarHero') }}" class="group glass-panel p-8 flex flex-col items-center text-center relative overflow-hidden transition-all duration-300 hover:border-amber-500/40 hover:shadow-[0_0_40px_rgba(245,158,11,0.2)] w-full md:w-[calc(50%-16px)] lg:w-[calc(33.333%-16px)] max-w-md lg:max-w-none">
                <div class="w-full flex items-center justify-between">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] uppercase font-bold text-amber-400 bg-amber-500/10 border border-amber-500/20">Songsterr-Style Player</span>
                    <div class="flex items-end gap-1 h-4 opacity-40 group-hover:opacity-100 transition-opacity">
                        <span class="w-1 bg-amber-400 rounded-full bar-anim"></span>
                        <span class="w-1 bg-amber-400 rounded-full bar-anim"></span>
                        <span class="w-1 bg-amber-400 rounded-full bar-anim"></span>
                    </div>
                </div>

                <div class="w-20 h-20 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-400 group-hover:bg-gradient-to-br group-hover:from-amber-500 group-hover:to-orange-600 group-hover:text-black flex items-center justify-center text-3xl my-6 transition-all duration-300 shadow-lg shadow-amber-500/10 group-hover:shadow-amber-500/30 group-hover:scale-105">
                    <i class="fa-solid fa-guitar"></i>
                </div>
                
                <h2 class="font-display text-3xl text-white mb-2 tracking-wide uppercase">Interactive TAB Player</h2>
                <p class="text-gray-400 text-xs leading-relaxed mb-8 flex-1">
                    Songsterr-style TAB player for Black Label Society, Sweet Child O' Mine & Sal Priadi with moving playhead and speed controls!
                </p>
                
                <div class="w-full py-3 rounded-xl bg-amber-500/20 group-hover:bg-gradient-to-r group-hover:from-amber-500 group-hover:to-orange-500 border border-amber-500/30 text-amber-300 group-hover:text-black text-xs font-bold transition-all flex items-center justify-center gap-2 shadow-md">
                    <span>Open TAB Player</span>
                    <i class="fa-solid fa-play text-[10px] group-hover:translate-x-1 transition-transform"></i>
                </div>
            </a>

            <!-- 1. Guitar Tuner Card -->
            <a href="{{ route('practice.tuner') }}" class="group glass-panel p-8 flex flex-col items-center text-center relative overflow-hidden transition-all duration-300 hover:border-blue-500/40 hover:shadow-[0_0_40px_rgba(59,130,246,0.15)] w-full md:w-[calc(50%-16px)] lg:w-[calc(33.333%-16px)] max-w-md lg:max-w-none">
                <div class="w-full flex items-center justify-between">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] uppercase font-bold text-blue-400 bg-blue-500/10 border border-blue-500/20">Mic Pitch Detection</span>
                    <!-- Equalizer animation -->
                    <div class="flex items-end gap-1 h-4 opacity-40 group-hover:opacity-100 transition-opacity">
                        <span class="w-1 bg-blue-400 rounded-full bar-anim"></span>
                        <span class="w-1 bg-blue-400 rounded-full bar-anim"></span>
                        <span class="w-1 bg-blue-400 rounded-full bar-anim"></span>
                    </div>
                </div>

                <div class="w-20 h-20 rounded-2xl bg-blue-500/10 border border-blue-500/20 text-blue-400 group-hover:bg-gradient-to-br group-hover:from-blue-600 group-hover:to-indigo-600 group-hover:text-white flex items-center justify-center text-3xl my-6 transition-all duration-300 shadow-lg shadow-blue-500/10 group-hover:shadow-blue-600/30 group-hover:scale-105">
                    <i class="fa-solid fa-guitar"></i>
                </div>
                
                <h2 class="font-display text-3xl text-white mb-2 tracking-wide uppercase">Guitar Tuner</h2>
                <p class="text-gray-400 text-xs leading-relaxed mb-8 flex-1">
                    Tune your guitar instantly using your microphone. Features highly accurate pitch detection and visual feedback.
                </p>
                
                <div class="w-full py-3 rounded-xl bg-blue-600/20 group-hover:bg-gradient-to-r group-hover:from-blue-600 group-hover:to-indigo-600 border border-blue-500/30 text-blue-300 group-hover:text-white text-xs font-bold transition-all flex items-center justify-center gap-2 shadow-md">
                    <span>Launch Tuner</span>
                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                </div>
            </a>

            <!-- 2. Metronome Card -->
            <a href="{{ route('practice.metronome') }}" class="group glass-panel p-8 flex flex-col items-center text-center relative overflow-hidden transition-all duration-300 hover:border-emerald-500/40 hover:shadow-[0_0_40px_rgba(16,185,129,0.15)] w-full md:w-[calc(50%-16px)] lg:w-[calc(33.333%-16px)] max-w-md lg:max-w-none">
                <div class="w-full flex items-center justify-between">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] uppercase font-bold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20">Audio Engine</span>
                    <!-- Equalizer animation -->
                    <div class="flex items-end gap-1 h-4 opacity-40 group-hover:opacity-100 transition-opacity">
                        <span class="w-1 bg-emerald-400 rounded-full bar-anim"></span>
                        <span class="w-1 bg-emerald-400 rounded-full bar-anim"></span>
                        <span class="w-1 bg-emerald-400 rounded-full bar-anim"></span>
                    </div>
                </div>

                <div class="w-20 h-20 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 group-hover:bg-gradient-to-br group-hover:from-emerald-500 group-hover:to-teal-600 group-hover:text-black flex items-center justify-center text-3xl my-6 transition-all duration-300 shadow-lg shadow-emerald-500/10 group-hover:shadow-emerald-500/30 group-hover:scale-105">
                    <i class="fa-solid fa-stopwatch"></i>
                </div>
                
                <h2 class="font-display text-3xl text-white mb-2 tracking-wide uppercase">Metronome</h2>
                <p class="text-gray-400 text-xs leading-relaxed mb-8 flex-1">
                    Build your internal clock and practice rhythm with a precision metronome. Adjustable BPM, time signatures, and accents.
                </p>
                
                <div class="w-full py-3 rounded-xl bg-emerald-500/20 group-hover:bg-gradient-to-r group-hover:from-emerald-500 group-hover:to-teal-500 border border-emerald-500/30 text-emerald-300 group-hover:text-black text-xs font-bold transition-all flex items-center justify-center gap-2 shadow-md">
                    <span>Launch Metronome</span>
                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                </div>
            </a>

            <!-- 3. Chord Library Card -->
            <a href="{{ route('practice.chords') }}" class="group glass-panel p-8 flex flex-col items-center text-center relative overflow-hidden transition-all duration-300 hover:border-purple-500/40 hover:shadow-[0_0_40px_rgba(168,85,247,0.15)] w-full md:w-[calc(50%-16px)] lg:w-[calc(33.333%-16px)] max-w-md lg:max-w-none">
                <div class="w-full flex items-center justify-between">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] uppercase font-bold text-purple-400 bg-purple-500/10 border border-purple-500/20">Interactive Fretboard</span>
                    <!-- Equalizer animation -->
                    <div class="flex items-end gap-1 h-4 opacity-40 group-hover:opacity-100 transition-opacity">
                        <span class="w-1 bg-purple-400 rounded-full bar-anim"></span>
                        <span class="w-1 bg-purple-400 rounded-full bar-anim"></span>
                        <span class="w-1 bg-purple-400 rounded-full bar-anim"></span>
                    </div>
                </div>

                <div class="w-20 h-20 rounded-2xl bg-purple-500/10 border border-purple-500/20 text-purple-400 group-hover:bg-gradient-to-br group-hover:from-purple-600 group-hover:to-indigo-600 group-hover:text-white flex items-center justify-center text-3xl my-6 transition-all duration-300 shadow-lg shadow-purple-500/10 group-hover:shadow-purple-600/30 group-hover:scale-105">
                    <i class="fa-solid fa-music"></i>
                </div>
                
                <h2 class="font-display text-3xl text-white mb-2 tracking-wide uppercase">Chord Library</h2>
                <p class="text-gray-400 text-xs leading-relaxed mb-8 flex-1">
                    Explore an interactive fretboard to learn and visualize thousands of chord shapes and voicings across the neck.
                </p>
                
                <div class="w-full py-3 rounded-xl bg-purple-600/20 group-hover:bg-gradient-to-r group-hover:from-purple-600 group-hover:to-indigo-600 border border-purple-500/30 text-purple-300 group-hover:text-white text-xs font-bold transition-all flex items-center justify-center gap-2 shadow-md">
                    <span>Browse Chords</span>
                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                </div>
            </a>

            <!-- 4. Scale Visualizer Card -->
            <a href="{{ route('practice.scales') }}" class="group glass-panel p-8 flex flex-col items-center text-center relative overflow-hidden transition-all duration-300 hover:border-cyan-500/40 hover:shadow-[0_0_40px_rgba(6,182,212,0.15)] w-full md:w-[calc(50%-16px)] lg:w-[calc(33.333%-16px)] max-w-md lg:max-w-none">
                <div class="w-full flex items-center justify-between">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] uppercase font-bold text-cyan-400 bg-cyan-500/10 border border-cyan-500/20">Solo & Improvisation</span>
                    <!-- Equalizer animation -->
                    <div class="flex items-end gap-1 h-4 opacity-40 group-hover:opacity-100 transition-opacity">
                        <span class="w-1 bg-cyan-400 rounded-full bar-anim"></span>
                        <span class="w-1 bg-cyan-400 rounded-full bar-anim"></span>
                        <span class="w-1 bg-cyan-400 rounded-full bar-anim"></span>
                    </div>
                </div>

                <div class="w-20 h-20 rounded-2xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 group-hover:bg-gradient-to-br group-hover:from-cyan-600 group-hover:to-blue-600 group-hover:text-white flex items-center justify-center text-3xl my-6 transition-all duration-300 shadow-lg shadow-cyan-500/10 group-hover:shadow-cyan-600/30 group-hover:scale-105">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                
                <h2 class="font-display text-3xl text-white mb-2 tracking-wide uppercase">Scale Visualizer</h2>
                <p class="text-gray-400 text-xs leading-relaxed mb-8 flex-1">
                    Master scale patterns across 12 frets. Learn Pentatonic, Blues, Dorian, and Major scales with ascending audio playback.
                </p>
                
                <div class="w-full py-3 rounded-xl bg-cyan-600/20 group-hover:bg-gradient-to-r group-hover:from-cyan-600 group-hover:to-blue-600 border border-cyan-500/30 text-cyan-300 group-hover:text-white text-xs font-bold transition-all flex items-center justify-center gap-2 shadow-md">
                    <span>Explore Scale Patterns</span>
                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                </div>
            </a>

            <!-- 5. Trainer Quiz Card -->
            <a href="{{ route('practice.trainer') }}" class="group glass-panel p-8 flex flex-col items-center text-center relative overflow-hidden transition-all duration-300 hover:border-rose-500/40 hover:shadow-[0_0_40px_rgba(244,63,94,0.15)] w-full md:w-[calc(50%-16px)] lg:w-[calc(33.333%-16px)] max-w-md lg:max-w-none">
                <div class="w-full flex items-center justify-between">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] uppercase font-bold text-rose-400 bg-rose-500/10 border border-rose-500/20">Ear & Memory Game</span>
                    <!-- Equalizer animation -->
                    <div class="flex items-end gap-1 h-4 opacity-40 group-hover:opacity-100 transition-opacity">
                        <span class="w-1 bg-rose-400 rounded-full bar-anim"></span>
                        <span class="w-1 bg-rose-400 rounded-full bar-anim"></span>
                        <span class="w-1 bg-rose-400 rounded-full bar-anim"></span>
                    </div>
                </div>

                <div class="w-20 h-20 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-400 group-hover:bg-gradient-to-br group-hover:from-rose-600 group-hover:to-amber-500 group-hover:text-white flex items-center justify-center text-3xl my-6 transition-all duration-300 shadow-lg shadow-rose-500/10 group-hover:shadow-rose-600/30 group-hover:scale-105">
                    <i class="fa-solid fa-gamepad"></i>
                </div>
                
                <h2 class="font-display text-3xl text-white mb-2 tracking-wide uppercase">Trainer Quiz Game</h2>
                <p class="text-gray-400 text-xs leading-relaxed mb-8 flex-1">
                    Test your ear and fretboard note memory with interactive audio quizzes, live scoring, and streak multipliers.
                </p>
                
                <div class="w-full py-3 rounded-xl bg-rose-600/20 group-hover:bg-gradient-to-r group-hover:from-rose-600 group-hover:to-amber-500 border border-rose-500/30 text-rose-300 group-hover:text-white text-xs font-bold transition-all flex items-center justify-center gap-2 shadow-md">
                    <span>Start Trainer Challenge</span>
                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                </div>
            </a>

        </div>

        <!-- QUICK INTERACTIVE TONE REFERENCE BAR (High Value Enhancement) -->
        <div class="glass-panel p-6 sm:p-8 relative overflow-hidden">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <div class="text-xs uppercase font-bold text-blue-400 tracking-wider flex items-center gap-2 mb-1">
                        <i class="fa-solid fa-volume-high"></i> Quick Reference Tones
                    </div>
                    <h3 class="text-xl font-bold text-white">Standard Tuning Pitch Synthesizer (E A D G B E)</h3>
                </div>
                <span class="text-xs text-gray-400">Click string to play reference pitch</span>
            </div>

            <!-- 6 Guitar String Buttons -->
            <div class="grid grid-cols-2 sm:grid-cols-6 gap-3">
                <button onclick="playTone(82.41, 'E2')" class="p-3.5 rounded-xl bg-zinc-950/60 border border-white/10 hover:border-blue-500/50 hover:bg-blue-500/10 text-center transition group cursor-pointer">
                    <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest block">6th String</span>
                    <span class="font-display text-3xl text-white group-hover:text-blue-400">E2</span>
                    <span class="text-[10px] text-gray-400 block mt-0.5">82.4 Hz</span>
                </button>

                <button onclick="playTone(110.00, 'A2')" class="p-3.5 rounded-xl bg-zinc-950/60 border border-white/10 hover:border-blue-500/50 hover:bg-blue-500/10 text-center transition group cursor-pointer">
                    <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest block">5th String</span>
                    <span class="font-display text-3xl text-white group-hover:text-blue-400">A2</span>
                    <span class="text-[10px] text-gray-400 block mt-0.5">110.0 Hz</span>
                </button>

                <button onclick="playTone(146.83, 'D3')" class="p-3.5 rounded-xl bg-zinc-950/60 border border-white/10 hover:border-blue-500/50 hover:bg-blue-500/10 text-center transition group cursor-pointer">
                    <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest block">4th String</span>
                    <span class="font-display text-3xl text-white group-hover:text-blue-400">D3</span>
                    <span class="text-[10px] text-gray-400 block mt-0.5">146.8 Hz</span>
                </button>

                <button onclick="playTone(196.00, 'G3')" class="p-3.5 rounded-xl bg-zinc-950/60 border border-white/10 hover:border-blue-500/50 hover:bg-blue-500/10 text-center transition group cursor-pointer">
                    <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest block">3rd String</span>
                    <span class="font-display text-3xl text-white group-hover:text-blue-400">G3</span>
                    <span class="text-[10px] text-gray-400 block mt-0.5">196.0 Hz</span>
                </button>

                <button onclick="playTone(246.94, 'B3')" class="p-3.5 rounded-xl bg-zinc-950/60 border border-white/10 hover:border-blue-500/50 hover:bg-blue-500/10 text-center transition group cursor-pointer">
                    <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest block">2nd String</span>
                    <span class="font-display text-3xl text-white group-hover:text-blue-400">B3</span>
                    <span class="text-[10px] text-gray-400 block mt-0.5">246.9 Hz</span>
                </button>

                <button onclick="playTone(329.63, 'E4')" class="p-3.5 rounded-xl bg-zinc-950/60 border border-white/10 hover:border-blue-500/50 hover:bg-blue-500/10 text-center transition group cursor-pointer">
                    <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest block">1st String</span>
                    <span class="font-display text-3xl text-white group-hover:text-blue-400">E4</span>
                    <span class="text-[10px] text-gray-400 block mt-0.5">329.6 Hz</span>
                </button>
            </div>
        </div>

    </main>
</div>

<script>
let synthAudioCtx = null;
function playTone(freq, noteName) {
    if (!synthAudioCtx) {
        synthAudioCtx = new (window.AudioContext || window.webkitAudioContext)();
    }
    if (synthAudioCtx.state === 'suspended') {
        synthAudioCtx.resume();
    }
    
    const osc = synthAudioCtx.createOscillator();
    const gain = synthAudioCtx.createGain();
    
    osc.type = 'triangle';
    osc.frequency.setValueAtTime(freq, synthAudioCtx.currentTime);
    
    gain.gain.setValueAtTime(0.4, synthAudioCtx.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.001, synthAudioCtx.currentTime + 1.8);
    
    osc.connect(gain);
    gain.connect(synthAudioCtx.destination);
    
    osc.start();
    osc.stop(synthAudioCtx.currentTime + 1.8);
}
</script>
@endsection
