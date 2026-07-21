@extends('layouts.app')

@section('title', 'Password Reset Successful - Guitarclassbynde')

@push('head')
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
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
                        'pop-in': 'popIn 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards',
                        'glow-pulse': 'glowPulse 3s ease-in-out infinite',
                    },
                    keyframes: {
                        popIn: {
                            '0%': { transform: 'scale(0.5)', opacity: '0' },
                            '100%': { transform: 'scale(1)', opacity: '1' },
                        },
                        glowPulse: {
                            '0%, 100%': { boxShadow: '0 0 30px rgba(16, 185, 129, 0.2)' },
                            '50%': { boxShadow: '0 0 60px rgba(16, 185, 129, 0.45)' },
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
<div class="min-h-screen bg-[#08080a] flex flex-col relative selection:bg-blue-600 selection:text-white overflow-x-hidden" x-data="{ mobileMenuOpen: false }">
    
    <!-- Ambient Glass Background Effects -->
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[800px] h-[500px] bg-emerald-600/15 rounded-full blur-[160px] mix-blend-screen"></div>
        <div class="absolute bottom-1/4 right-1/4 w-[500px] h-[500px] bg-blue-600/10 rounded-full blur-[160px] mix-blend-screen"></div>
        <div class="absolute inset-0 opacity-[0.02]" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 36px 36px;"></div>
    </div>

    <!-- Header Navigation -->
    <div class="relative z-30">
        @include('layouts.lms_header')
    </div>

    <!-- Main Content Container -->
    <main class="flex-1 w-full mx-auto p-4 sm:p-6 lg:p-8 flex items-center justify-center relative z-10 min-h-[calc(100vh-120px)]">
        
        <!-- Centered Glass Card -->
        <div class="w-full max-w-[540px] mx-auto animate-pop-in">
            <div class="bg-zinc-950/70 border border-white/10 backdrop-blur-3xl rounded-[2.5rem] p-8 sm:p-10 shadow-[0_0_60px_-15px_rgba(16,185,129,0.25)] relative overflow-hidden space-y-6 text-center">
                
                <!-- Top Emerald Accent Line -->
                <div class="absolute top-0 inset-x-0 h-[2px] bg-gradient-to-r from-transparent via-emerald-500/80 to-transparent"></div>

                <!-- Animated Icon Badge -->
                <div class="relative inline-block mx-auto">
                    <div class="w-20 h-20 rounded-3xl bg-gradient-to-tr from-emerald-600/30 to-teal-500/20 border border-emerald-500/40 text-emerald-400 flex items-center justify-center text-3xl mx-auto shadow-2xl animate-glow-pulse">
                        <i class="fa-solid fa-circle-check text-emerald-400"></i>
                    </div>
                    <span class="absolute -top-1 -right-1 flex h-4 w-4">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-4 w-4 bg-emerald-500"></span>
                    </span>
                </div>

                <!-- Status Badge -->
                <div>
                    <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/25 text-emerald-400 text-[11px] font-extrabold uppercase tracking-widest mb-3">
                        <i class="fa-solid fa-shield-check"></i>
                        <span>PASSWORD UPDATED SUCCESSFULLY</span>
                    </div>

                    <h1 class="font-display text-4xl sm:text-5xl text-white tracking-wide uppercase leading-none">
                        Password Has Been <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-teal-300 to-emerald-500">Reset!</span>
                    </h1>
                </div>

                <p class="text-gray-300 text-xs sm:text-sm leading-relaxed max-w-md mx-auto">
                    Your password has been successfully updated. Your account is now secured with your new password. Where would you like to go next?
                </p>

                <!-- Navigation Options Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-3">
                    <a href="{{ auth()->check() ? route('lms.entry') : route('login') }}" class="py-3.5 px-5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-xs uppercase tracking-wider text-center shadow-lg shadow-blue-600/30 transition-all hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-graduation-cap text-sm"></i>
                        <span>{{ auth()->check() ? 'Go to Dashboard' : 'Sign In to LMS' }}</span>
                    </a>

                    <a href="{{ route('compro') }}" class="py-3.5 px-5 rounded-xl bg-white/5 border border-white/10 hover:border-white/20 text-gray-300 hover:text-white text-xs font-bold transition text-center flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-house text-sm"></i>
                        <span>Back to Home</span>
                    </a>
                </div>

            </div>
        </div>
    </main>

</div>
@endsection
