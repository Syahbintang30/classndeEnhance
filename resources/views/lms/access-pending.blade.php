@extends('layouts.app')

@section('title', 'Access Pending - Guitarclassbynde')

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
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[800px] h-[500px] bg-blue-600/15 rounded-full blur-[160px] mix-blend-screen"></div>
        <div class="absolute bottom-1/4 right-1/4 w-[500px] h-[500px] bg-indigo-600/10 rounded-full blur-[160px] mix-blend-screen"></div>
        <div class="absolute inset-0 opacity-[0.02]" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 36px 36px;"></div>
    </div>

    <!-- Header Navigation -->
    <div class="relative z-30">
        @include('layouts.lms_header')
    </div>

    <!-- Main Content Container -->
    <main class="flex-1 w-full mx-auto p-4 sm:p-6 lg:p-8 flex items-center justify-center relative z-10 min-h-[calc(100vh-120px)]">
        
        <!-- Centered Glass Card -->
        <div class="w-full max-w-[620px] mx-auto">
            <div class="bg-zinc-950/70 border border-white/10 backdrop-blur-3xl rounded-[2.5rem] p-7 sm:p-10 shadow-[0_0_60px_-15px_rgba(59,130,246,0.3)] relative overflow-hidden space-y-6">
                
                <!-- Top Accent Line -->
                <div class="absolute top-0 inset-x-0 h-[2px] bg-gradient-to-r from-transparent via-blue-500/60 to-transparent"></div>

                <!-- Icon & Kicker -->
                <div class="text-center space-y-3">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-blue-600/20 to-indigo-600/20 border border-blue-500/30 text-blue-400 flex items-center justify-center text-2xl mx-auto shadow-lg shadow-blue-500/10">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-[11px] font-extrabold uppercase tracking-widest">
                        <i class="fa-solid fa-graduation-cap"></i>
                        <span>CLASS ACCESS PENDING</span>
                    </div>
                    <h1 class="font-display text-3xl sm:text-4xl text-white tracking-wide uppercase leading-none pt-1">
                        Activate Your <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">Course Access</span>
                    </h1>
                    <p class="text-gray-300 text-xs sm:text-sm leading-relaxed max-w-lg mx-auto">
                        Your account is active, but you don't have an active learning package yet. Select a course package to unlock full video modules and learning tools.
                    </p>
                </div>

                <!-- Steps List -->
                <div class="space-y-3 pt-2">
                    <div class="p-3.5 rounded-2xl bg-white/[0.03] border border-white/10 flex items-center gap-3 text-xs text-gray-200">
                        <div class="w-7 h-7 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center shrink-0 border border-blue-500/30 font-bold text-xs">1</div>
                        <span>Choose the learning package that fits your goals (Intermediate, Beginner, Coaching).</span>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-white/[0.03] border border-white/10 flex items-center gap-3 text-xs text-gray-200">
                        <div class="w-7 h-7 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center shrink-0 border border-blue-500/30 font-bold text-xs">2</div>
                        <span>Complete quick payment via Midtrans (QRIS, Bank Transfer, E-Wallet).</span>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-white/[0.03] border border-white/10 flex items-center gap-3 text-xs text-gray-200">
                        <div class="w-7 h-7 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-500/30 font-bold text-xs">3</div>
                        <span>Instant automatic activation of all video modules & student portal features!</span>
                    </div>
                </div>

                <!-- CTA Action Buttons -->
                <div class="flex flex-col sm:flex-row items-center gap-3 pt-2">
                    <a href="{{ route('registerclass') }}" class="w-full sm:flex-1 py-3.5 px-6 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-xs uppercase tracking-wider text-center shadow-lg shadow-blue-600/30 transition-all hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-2 cursor-pointer">
                        <span>Choose Package & Pay</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>

                    <a href="{{ route('compro') }}" class="w-full sm:w-auto py-3.5 px-6 rounded-xl bg-white/5 border border-white/10 hover:border-white/20 text-gray-300 hover:text-white text-xs font-bold transition text-center flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-house text-xs"></i>
                        <span>Back to Home</span>
                    </a>
                </div>

            </div>
        </div>
    </main>

</div>
@endsection
