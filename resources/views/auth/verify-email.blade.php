@extends('layouts.app')

@section('title', 'Verify Email')

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
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        display: ['"Bebas Neue"', 'sans-serif'],
                    },
                    animation: {
                        'float-slow': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #08080a !important; color: #ffffff !important; font-family: 'Plus Jakarta Sans', sans-serif !important; }
        .font-display { font-family: 'Bebas Neue', cursive !important; letter-spacing: 1px; }
        body > nav { display: none !important; }
    </style>
@endpush

@section('content')
<div class="min-h-screen bg-[#08080a] flex flex-col relative selection:bg-blue-600 selection:text-white overflow-hidden">
    
    <!-- Full Screen Cinematic Background -->
    <div class="absolute inset-0 z-0 pointer-events-none">
        <img src="{{ asset('compro/img/ndehero.webp') }}" alt="Background" class="w-full h-full object-cover opacity-30 scale-105" style="object-position: center 20%; filter: contrast(1.1) brightness(0.7);">
        <div class="absolute inset-0 bg-gradient-to-b from-[#08080a]/90 via-[#08080a]/50 to-[#08080a]"></div>
    </div>
    
    {{-- Ambient Mesh Background Glows --}}
    <div class="absolute top-1/4 left-1/4 w-[600px] h-[600px] bg-blue-600/15 rounded-full blur-[150px] pointer-events-none z-0 mix-blend-screen"></div>
    <div class="absolute bottom-1/4 right-1/4 w-[500px] h-[500px] bg-indigo-600/15 rounded-full blur-[150px] pointer-events-none z-0 mix-blend-screen"></div>

    {{-- LMS Floating Header --}}
    <div class="relative z-20">
        @include('layouts.lms_header')
    </div>

    {{-- Main Centered Card Container --}}
    <main class="flex-1 w-full mx-auto p-4 sm:p-6 lg:p-8 flex items-center justify-center relative z-10 min-h-[calc(100vh-100px)]">
        
        <!-- Centered Glassmorphic Form Card -->
        <div class="w-full max-w-[460px] mx-auto animate-float-slow" style="animation-duration: 8s;">
            <div class="bg-zinc-950/60 border border-white/10 backdrop-blur-3xl rounded-[2rem] p-7 sm:p-8 shadow-[0_0_60px_-15px_rgba(59,130,246,0.3)] relative overflow-hidden space-y-6">
                
                <!-- Inner Glow top border -->
                <div class="absolute top-0 inset-x-0 h-[1px] bg-gradient-to-r from-transparent via-blue-500/50 to-transparent"></div>
                
                <!-- Icon Header -->
                <div class="text-center space-y-3">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-blue-600/20 to-indigo-600/20 border border-blue-500/30 text-blue-400 flex items-center justify-center text-2xl mx-auto shadow-lg shadow-blue-500/10">
                        <i class="fa-solid fa-envelope-open-text"></i>
                    </div>
                    <h1 class="font-display text-4xl text-white tracking-wide uppercase leading-none">Verify Your <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">Email</span></h1>
                    <p class="text-gray-300 text-xs leading-relaxed">
                        To activate your account and access full LMS features, please check your inbox and click the verification link we sent to your email.
                    </p>
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="p-3.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs rounded-xl font-medium text-center flex items-center gap-2 justify-center">
                        <i class="fa-solid fa-paper-plane text-sm"></i>
                        <span>A new verification link has been sent to your email address.</span>
                    </div>
                @endif

                <div class="space-y-3 pt-2">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="w-full py-3.5 px-6 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-xs tracking-wider uppercase shadow-lg shadow-blue-600/30 transition duration-300 hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-rotate-right text-xs"></i>
                            <span>Resend Verification Email</span>
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full py-3 px-6 rounded-xl bg-white/5 border border-white/10 hover:border-white/20 text-gray-400 hover:text-white text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-right-from-bracket text-xs"></i>
                            <span>Log Out</span>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </main>

</div>
@endsection
