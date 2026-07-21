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
        .glass-panel {
            background: rgba(18, 18, 26, 0.55);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 1.5rem;
        }
        body > nav { display: none !important; }
    </style>
@endpush

@section('title', 'Song Tutorial Library')

@section('content')
<div class="tw-dash min-h-screen bg-[#08080a] flex flex-col antialiased relative overflow-hidden" x-data="{ mobileMenuOpen: false }">
    
    {{-- Ambient Mesh Background Glow --}}
    <div class="absolute -top-32 left-1/3 w-[600px] h-[600px] bg-blue-600/10 rounded-full blur-[140px] pointer-events-none"></div>

    {{-- Top Navigation Header --}}
    @include('layouts.lms_header')

    <div class="flex-1 flex items-center justify-center p-6 relative z-10">

        <div class="glass-panel max-w-lg w-full p-8 md:p-12 text-center shadow-2xl relative overflow-hidden">
            <div class="w-20 h-20 bg-blue-500/10 border border-blue-500/20 text-blue-400 rounded-2xl flex items-center justify-center text-4xl mx-auto mb-6 shadow-lg shadow-blue-500/10">
                <i class="fa-solid fa-music"></i>
            </div>
            
            <h1 class="font-display text-4xl text-white mb-3 tracking-wide uppercase">
                Song <span class="text-blue-500">Library</span>
            </h1>
            
            @auth
                @if (! empty($hasIntermediate))
                    <p class="text-gray-400 mb-8 leading-relaxed text-xs sm:text-sm">
                        No Song Tutorial content is available yet. Our mentors are currently producing new song breakdowns. Please check back soon!
                    </p>
                @else
                    <p class="text-gray-400 mb-8 leading-relaxed text-xs sm:text-sm">
                        Song Tutorial access is available exclusively for the <strong class="text-white font-bold">Intermediate Student</strong> tier. Upgrade your package to unlock song library breakdowns.
                    </p>
                    <a href="{{ route('registerclass') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl text-xs shadow-lg shadow-blue-600/30 hover:scale-105 transition-all">
                        <span>View Packages</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                @endif
            @else
                <p class="text-gray-400 mb-8 leading-relaxed text-xs sm:text-sm">
                    Sign in with an account that has the Intermediate package to access the Song Tutorial library.
                </p>
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl text-xs shadow-lg shadow-blue-600/30 hover:scale-105 transition-all">
                    <i class="fa-solid fa-right-to-bracket text-xs"></i>
                    <span>Log in</span>
                </a>
            @endauth
        </div>
    </div>
</div>
@endsection
