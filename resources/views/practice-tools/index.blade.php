@extends('layouts.app')

@push('head')
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            important: true,
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            black: '#0a0a0c',
                            card: '#121218',
                            border: '#222230',
                            accent: '#0066ff',
                            glow: 'rgba(0, 102, 255, 0.15)'
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
            font-family: 'Plus Jakarta Sans', sans-serif !important;
        }
        .tw-dash .font-display {
            font-family: 'Bebas Neue', cursive;
            letter-spacing: 1px;
        }
        
        body > nav { display: none !important; }
        
        .tw-dash ::-webkit-scrollbar { width: 6px; }
        .tw-dash ::-webkit-scrollbar-track { background: transparent; }
        .tw-dash ::-webkit-scrollbar-thumb { background: #222232; border-radius: 3px; }
        .tw-dash ::-webkit-scrollbar-thumb:hover { background: #3b82f6; }
        .tw-dash a { text-decoration: none; }
        .tw-dash *:focus { outline: none !important; }
    </style>
@endpush

@section('content')
<div class="tw-dash min-h-screen flex flex-col antialiased text-gray-200" x-data="{ mobileMenuOpen: false }">

    @include('layouts.lms_header')

    <main class="flex-1 max-w-7xl mx-auto w-full px-4 lg:px-8 py-10 space-y-12">
        <header class="mb-8 text-center sm:text-left">
            <h1 class="font-display text-5xl tracking-wide text-white mb-2">Practice <span class="text-blue-500">Tools</span></h1>
            <p class="text-gray-400 max-w-2xl">Sharpen your skills with our suite of built-in practice utilities. Stay in tune, keep in time, and master the fretboard without ever leaving the dashboard.</p>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <!-- Guitar Tuner Card -->
            <a href="{{ route('practice.tuner') }}" class="group block relative rounded-3xl p-1 bg-gradient-to-b from-zinc-800 to-black hover:from-blue-500 hover:to-indigo-600 transition-all duration-300">
                <div class="absolute inset-0 bg-blue-500/0 group-hover:bg-blue-500/20 blur-xl transition-all duration-500 rounded-3xl -z-10"></div>
                <div class="h-full bg-zinc-900/90 backdrop-blur-sm rounded-[22px] p-8 flex flex-col items-center text-center transition-all duration-300 group-hover:bg-zinc-900/80">
                    <div class="w-20 h-20 rounded-full bg-blue-500/10 text-blue-400 group-hover:bg-blue-500 group-hover:text-white flex items-center justify-center text-4xl mb-6 transition-all duration-300 shadow-[0_0_15px_rgba(59,130,246,0.3)] group-hover:shadow-[0_0_30px_rgba(59,130,246,0.6)]">
                        <i class="fa-solid fa-guitar"></i>
                    </div>
                    <h2 class="font-display text-3xl text-white mb-3">Guitar Tuner</h2>
                    <p class="text-gray-400 text-sm mb-6 flex-1">Tune your guitar instantly using your microphone. Features highly accurate pitch detection and visual feedback.</p>
                    <span class="inline-flex items-center gap-2 text-sm font-bold text-blue-400 group-hover:text-blue-300">
                        Launch Tuner <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </span>
                </div>
            </a>

            <!-- Metronome Card -->
            <a href="{{ route('practice.metronome') }}" class="group block relative rounded-3xl p-1 bg-gradient-to-b from-zinc-800 to-black hover:from-emerald-500 hover:to-emerald-700 transition-all duration-300">
                <div class="absolute inset-0 bg-emerald-500/0 group-hover:bg-emerald-500/20 blur-xl transition-all duration-500 rounded-3xl -z-10"></div>
                <div class="h-full bg-zinc-900/90 backdrop-blur-sm rounded-[22px] p-8 flex flex-col items-center text-center transition-all duration-300 group-hover:bg-zinc-900/80">
                    <div class="w-20 h-20 rounded-full bg-emerald-500/10 text-emerald-400 group-hover:bg-emerald-500 group-hover:text-white flex items-center justify-center text-4xl mb-6 transition-all duration-300 shadow-[0_0_15px_rgba(16,185,129,0.3)] group-hover:shadow-[0_0_30px_rgba(16,185,129,0.6)]">
                        <i class="fa-solid fa-stopwatch"></i>
                    </div>
                    <h2 class="font-display text-3xl text-white mb-3">Metronome</h2>
                    <p class="text-gray-400 text-sm mb-6 flex-1">Build your internal clock and practice rhythm with a precision metronome. Adjustable BPM, time signatures, and accents.</p>
                    <span class="inline-flex items-center gap-2 text-sm font-bold text-emerald-400 group-hover:text-emerald-300">
                        Launch Metronome <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </span>
                </div>
            </a>

            <!-- Chord Library Card -->
            <a href="{{ route('practice.chords') }}" class="group block relative rounded-3xl p-1 bg-gradient-to-b from-zinc-800 to-black hover:from-purple-500 hover:to-pink-600 transition-all duration-300 md:col-span-2 lg:col-span-1">
                <div class="absolute inset-0 bg-purple-500/0 group-hover:bg-purple-500/20 blur-xl transition-all duration-500 rounded-3xl -z-10"></div>
                <div class="h-full bg-zinc-900/90 backdrop-blur-sm rounded-[22px] p-8 flex flex-col items-center text-center transition-all duration-300 group-hover:bg-zinc-900/80">
                    <div class="w-20 h-20 rounded-full bg-purple-500/10 text-purple-400 group-hover:bg-purple-500 group-hover:text-white flex items-center justify-center text-4xl mb-6 transition-all duration-300 shadow-[0_0_15px_rgba(168,85,247,0.3)] group-hover:shadow-[0_0_30px_rgba(168,85,247,0.6)]">
                        <i class="fa-solid fa-music"></i>
                    </div>
                    <h2 class="font-display text-3xl text-white mb-3">Chord Library</h2>
                    <p class="text-gray-400 text-sm mb-6 flex-1">Explore an interactive fretboard to learn and visualize thousands of chord shapes and voicings across the neck.</p>
                    <span class="inline-flex items-center gap-2 text-sm font-bold text-purple-400 group-hover:text-purple-300">
                        Browse Chords <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </span>
                </div>
            </a>

        </div>
    </main>
</div>
@endsection
