@extends('layouts.app')

@push('head')
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            important: true,
            theme: {
                fontFamily: {
                    sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    display: ['"Bebas Neue"', 'sans-serif'],
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
        .tw-dash .font-display { font-family: 'Bebas Neue', cursive; letter-spacing: 1px; }
        body > nav { display: none !important; }
    </style>
@endpush

@section('title', 'Song Tutorial')

@section('content')
<div class="tw-dash min-h-screen bg-black flex flex-col antialiased">
    
    {{-- Header --}}
    <header class="sticky top-0 z-40 bg-[#0a0a0e]/90 backdrop-blur-md border-b border-zinc-800/80 px-4 lg:px-8 py-3.5 flex items-center justify-between">
        <a href="{{ route('lms.dashboard') }}" class="flex items-center gap-2 group">
            <span class="font-display text-3xl tracking-wider text-white group-hover:text-blue-500 transition-colors">
                NDE <span class="text-blue-500 group-hover:text-white transition-colors">GUITAR</span>
            </span>
            <span class="px-2 py-0.5 text-[10px] uppercase tracking-widest font-extrabold bg-blue-600/20 text-blue-400 border border-blue-500/30 rounded">PRO</span>
        </a>
        <a href="{{ route('lms.dashboard') }}" class="text-sm font-semibold text-gray-400 hover:text-white flex items-center gap-2 transition">
            <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
        </a>
    </header>

    <div class="flex-1 flex items-center justify-center p-6 relative overflow-hidden">
        <!-- Background Glow -->
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
            <div class="w-96 h-96 bg-blue-600/10 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 max-w-lg w-full bg-zinc-900/50 border border-zinc-800 rounded-3xl p-8 md:p-12 text-center backdrop-blur-sm shadow-2xl shadow-black">
            <div class="w-20 h-20 bg-blue-500/10 border border-blue-500/20 text-blue-500 rounded-2xl flex items-center justify-center text-4xl mx-auto mb-6">
                <i class="fa-solid fa-music"></i>
            </div>
            
            <h1 class="font-display text-4xl tracking-wider text-white mb-4">Song <span class="text-blue-500">Tutorial</span></h1>
            
            @auth
                @if (! empty($hasIntermediate))
                    <p class="text-gray-400 mb-8 leading-relaxed text-sm md:text-base">No Song Tutorial content is available yet. Our mentors are currently producing new song breakdowns. Please check back later.</p>
                @else
                    <p class="text-gray-400 mb-8 leading-relaxed text-sm md:text-base">
                        Song Tutorial access is available exclusively for the <strong class="text-white">Intermediate</strong> package. Upgrade your package to unlock our comprehensive song library and technique breakdowns.
                    </p>
                    <a href="{{ route('registerclass') }}" class="inline-flex items-center gap-2 px-8 py-3.5 bg-white text-black font-bold rounded-xl transition hover:-translate-y-1 shadow-lg shadow-white/10">
                        View Packages <i class="fa-solid fa-arrow-right"></i>
                    </a>
                @endif
            @else
                <p class="text-gray-400 mb-8 leading-relaxed text-sm md:text-base">
                    Sign in with an account that has the Intermediate package to access the Song Tutorial library.
                </p>
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-8 py-3.5 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl transition hover:-translate-y-1 shadow-lg shadow-blue-500/20 border border-blue-500/30">
                    <i class="fa-solid fa-right-to-bracket"></i> Login
                </a>
            @endauth
        </div>
    </div>
</div>
@endsection
