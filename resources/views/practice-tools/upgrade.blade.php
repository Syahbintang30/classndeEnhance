@extends('layouts.app')

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
                        display: ['"Bebas Neue"', 'sans-serif']
                    }
                }
            }
        };
    </script>
@endpush

@push('styles')
    <style>
        body { background-color: #08080a !important; color: #ffffff !important; font-family: 'Plus Jakarta Sans', sans-serif !important; }
        .font-display { font-family: 'Bebas Neue', cursive !important; letter-spacing: 1px; }
        body > nav, .global-nav { display: none !important; }
        .tw-dash a { text-decoration: none; }
        .tw-dash *:focus { outline: none !important; }
        
        .glass-card {
            background: rgba(12, 12, 18, 0.75);
            backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
    </style>
@endpush

@section('content')
<div class="tw-dash min-h-screen flex flex-col antialiased bg-[#08080a] text-gray-200 relative overflow-hidden selection:bg-blue-600 selection:text-white" x-data="{ mobileMenuOpen: false }">
    
    <!-- Ambient Background Glows -->
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[700px] h-[500px] bg-blue-600/10 rounded-full blur-[160px] mix-blend-screen"></div>
        <div class="absolute bottom-1/4 right-1/4 w-[500px] h-[500px] bg-indigo-600/10 rounded-full blur-[160px] mix-blend-screen"></div>
        <div class="absolute inset-0 opacity-[0.02]" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 36px 36px;"></div>
    </div>

    <!-- LMS Header -->
    <div class="relative z-30">
        @include('layouts.lms_header')
    </div>

    <!-- Main Upgrade Hero -->
    <main class="flex-1 flex flex-col items-center justify-center p-6 relative z-10 py-12 lg:py-20">
        <div class="max-w-2xl w-full mx-auto text-center space-y-8">
            
            <!-- Lock Icon Badge -->
            <div class="space-y-4">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs font-bold uppercase tracking-widest shadow-inner">
                    <i class="fa-solid fa-lock text-[11px]"></i>
                    <span>INTERMEDIATE EXCLUSIVE FEATURE</span>
                </div>

                <h1 class="font-display text-4xl sm:text-6xl text-white tracking-wide uppercase leading-none">
                    Unlock <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 via-yellow-300 to-blue-500">Practice Tools</span>
                </h1>

                <p class="text-gray-400 text-xs sm:text-sm leading-relaxed max-w-lg mx-auto">
                    The Tuner, Metronome, Chord Library, Scale Finder &amp; Fretboard Trainer are exclusively available for <strong class="text-white">Intermediate</strong> members.
                </p>
            </div>

            <!-- Upgrade Offer Glass Card -->
            <div class="glass-card rounded-[2.5rem] p-8 sm:p-10 border-2 border-blue-500/30 shadow-[0_0_60px_-15px_rgba(59,130,246,0.3)] text-left relative overflow-hidden space-y-6">
                
                <!-- Card Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-white/10">
                    <div>
                        <span class="text-xs font-bold text-blue-400 uppercase tracking-wider block">Target Tier: Intermediate</span>
                        <h2 class="font-display text-3xl text-white uppercase tracking-wide">Intermediate Membership</h2>
                    </div>

                    <div class="text-left sm:text-right">
                        @if($isBeginner)
                            <div class="flex items-center gap-2 sm:justify-end">
                                <span class="text-xs text-gray-500 line-through">Rp 250.000</span>
                                <span class="px-2.5 py-0.5 rounded-md bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 font-extrabold text-[10px] uppercase">Special Upgrade Price</span>
                            </div>
                            <div class="flex items-baseline gap-1 text-white">
                                <span class="text-xs font-bold text-gray-400">Rp</span>
                                <span class="text-3xl font-extrabold text-blue-400 tracking-tight">150.000</span>
                                <span class="text-xs text-gray-400 font-normal">/ upgrade</span>
                            </div>
                        @else
                            <div class="flex items-baseline gap-1 text-white">
                                <span class="text-xs font-bold text-gray-400">Rp</span>
                                <span class="text-3xl font-extrabold text-blue-400 tracking-tight">250.000</span>
                                <span class="text-xs text-gray-400 font-normal">/ lifetime</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Explanation Banner -->
                @if($isBeginner)
                    <div class="p-4 rounded-2xl bg-blue-500/10 border border-blue-500/20 text-xs text-blue-300 flex items-center gap-3">
                        <i class="fa-solid fa-sparkles text-base shrink-0 text-blue-400"></i>
                        <span>As a <strong>Reguler / Beginner</strong> member, you only need <strong>Rp 150.000</strong> to upgrade to Intermediate and unlock Practice Tools!</span>
                    </div>
                @endif

                <!-- Included Features -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">What you get when upgrading:</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs text-gray-200">
                        <div class="flex items-center gap-2.5">
                            <div class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-500/30 text-[10px]">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <span>Full Access to Practice Tools</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <div class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-500/30 text-[10px]">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <span>Song Tutorial Feature</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <div class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-500/30 text-[10px]">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <span>2x Coaching Tickets Included</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <div class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-500/30 text-[10px]">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <span>Lifetime Course Access</span>
                        </div>
                    </div>
                </div>

                <!-- CTA Action Button -->
                @php
                    $intermediatePackage = \App\Models\Package::where('slug', 'intermediate')->first();
                    $targetLesson = \App\Models\Lesson::first();
                    $targetLessonId = $targetLesson ? $targetLesson->id : 1;
                    $checkoutUrl = route('kelas.payment', ['lesson' => $targetLessonId]) . '?package_id=' . ($intermediatePackage->id ?? 9) . '&package_qty=1';
                @endphp

                <a href="{{ $checkoutUrl }}" class="w-full py-4 px-6 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-xs uppercase tracking-wider text-center shadow-lg shadow-blue-600/40 transition-all hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-2.5 cursor-pointer">
                    <i class="fa-solid fa-bolt text-sm"></i>
                    <span>Upgrade to Intermediate — Rp {{ $isBeginner ? '150.000' : '250.000' }}</span>
                </a>

            </div>

        </div>
    </main>
</div>
@endsection
