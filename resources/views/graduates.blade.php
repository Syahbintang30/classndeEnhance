@extends('layouts.app')

@section('title', 'Hall of Fame - Graduates - Guitarclassbynde')

@section('content')
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
                            border: 'rgba(255, 255, 255, 0.05)',
                            accent: '#0066ff',
                            amber: '#f59e0b',
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
        /* Hide default legacy navbar */
        body > nav { display: none !important; }

        .tw-dash {
            background-color: #08080a !important;
            color: #f3f4f6 !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
        }
        .tw-dash .font-display {
            font-family: 'Bebas Neue', cursive;
            letter-spacing: 1.5px;
        }
        .grad-card {
            background: rgba(18, 18, 24, 0.6);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .grad-card:hover {
            border-color: rgba(245, 158, 11, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 12px 30px -5px rgba(245, 158, 11, 0.12);
        }
    </style>
@endpush

<div class="tw-dash min-h-screen flex flex-col antialiased bg-[#08080a] text-gray-200 relative overflow-hidden"
     x-data="{ mobileMenuOpen: false }">

    {{-- Ambient Mesh Glow Background --}}
    <div class="absolute -top-32 left-1/3 w-[600px] h-[600px] bg-amber-500/10 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute top-1/2 -right-32 w-[450px] h-[450px] bg-indigo-600/10 rounded-full blur-[120px] pointer-events-none"></div>

    {{-- TOP NAVIGATION BAR --}}
    @include('layouts.lms_header')

    <!-- MAIN HALL OF FAME CONTENT -->
    <main class="flex-1 w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 relative z-10 space-y-8 sm:space-y-10">
        
        <!-- Header Section -->
        <div class="text-center space-y-3">
            <div class="flex justify-center mb-3">
                <img src="{{ asset('compro/img/logo_styled.png') }}" alt="Guitarclassbynde Logo" class="h-12 sm:h-16 w-auto object-contain filter drop-shadow-[0_0_20px_rgba(245,158,11,0.55)]" />
            </div>

            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-gradient-to-r from-amber-500/15 via-orange-500/15 to-amber-500/15 border border-amber-500/30 text-amber-400 text-xs font-bold uppercase tracking-wider shadow-[0_0_15px_rgba(245,158,11,0.2)]">
                <i class="fa-solid fa-trophy text-amber-400 text-xs"></i>
                <span>Official Hall of Fame</span>
            </div>
            
            <h1 class="font-display text-4xl sm:text-6xl text-white tracking-wider">
                GUITARCLASSBYNDE <span class="bg-gradient-to-r from-amber-300 via-amber-400 to-orange-500 bg-clip-text text-transparent">GRADUATES</span>
            </h1>
            
            <p class="text-gray-400 max-w-xl mx-auto text-xs sm:text-sm font-normal leading-relaxed">
                Verified directory of students who have completed 100% of the Guitarclassbynde course curriculum.
            </p>
        </div>

        <!-- Graduates Stats Bar (Soft Borders) -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-3xl mx-auto">
            <div class="bg-zinc-900/50 backdrop-blur-md border border-white/5 rounded-2xl p-4 text-center shadow-lg">
                <span class="block text-[11px] font-semibold text-gray-400 uppercase tracking-wider">TOTAL GRADUATES</span>
                <span class="block text-2xl font-extrabold text-white mt-1">{{ count($graduates) }} Students</span>
            </div>
            <div class="bg-zinc-900/50 backdrop-blur-md border border-white/5 rounded-2xl p-4 text-center shadow-lg">
                <span class="block text-[11px] font-semibold text-gray-400 uppercase tracking-wider">COMPLETED TOPICS</span>
                <span class="block text-2xl font-extrabold text-amber-400 mt-1">{{ $totalCourseTopics }} Topics</span>
            </div>
            <div class="bg-zinc-900/50 backdrop-blur-md border border-white/5 rounded-2xl p-4 text-center shadow-lg">
                <span class="block text-[11px] font-semibold text-gray-400 uppercase tracking-wider">VERIFICATION STATUS</span>
                <span class="block text-2xl font-extrabold text-emerald-400 mt-1">100% Authentic</span>
            </div>
        </div>

        <!-- Graduates Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($graduates as $index => $grad)
                @php
                    $isSelf = auth()->check() && auth()->id() === $grad->id;
                @endphp
                <div class="grad-card rounded-2xl p-5 relative flex flex-col justify-between overflow-hidden shadow-lg border {{ $isSelf ? 'border-amber-500/50 bg-amber-500/5' : 'border-white/10 bg-zinc-950/60' }}">
                    <div class="absolute -top-10 -right-10 w-28 h-28 bg-amber-500/10 rounded-full blur-xl pointer-events-none"></div>

                    <div>
                        <!-- Top Row: Badge & Cert Code -->
                        <div class="flex items-center justify-between gap-2 mb-4">
                            <div class="flex items-center gap-1.5">
                                @if($index === 0)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-500/20 border border-amber-500/40 text-amber-300 text-[10px] font-extrabold">
                                        <i class="fa-solid fa-crown text-amber-400"></i> #1 GRADUATE
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-bold">
                                        <i class="fa-solid fa-circle-check text-[9px]"></i> VERIFIED
                                    </span>
                                @endif
                                @if($isSelf)
                                    <span class="px-2 py-0.5 rounded bg-blue-500/20 text-blue-400 border border-blue-500/30 text-[9px] font-extrabold uppercase">YOU</span>
                                @endif
                            </div>

                            <span class="font-mono text-[10px] text-gray-400 bg-white/5 px-2 py-0.5 rounded border border-white/5">
                                {{ $grad->cert_code }}
                            </span>
                        </div>

                        <!-- Student Profile Row -->
                        <div class="flex items-center gap-3.5 mb-4">
                            @if($grad->photo)
                                <img src="{{ $grad->photo }}" alt="{{ $grad->name }}" class="w-12 h-12 rounded-xl object-cover border border-amber-500/50 flex-shrink-0" />
                            @else
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-amber-700 text-white font-black text-lg flex items-center justify-center border border-amber-400/50 flex-shrink-0">
                                    {{ strtoupper(substr($grad->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <h3 class="font-bold text-base text-white truncate mb-0.5">
                                    {{ $grad->name }}
                                </h3>
                                <span class="inline-block px-2 py-0.5 rounded bg-blue-500/10 text-blue-400 border border-blue-500/20 text-[10px] font-semibold uppercase tracking-wider">
                                    {{ $grad->package_name }}
                                </span>
                            </div>
                        </div>

                        <p class="text-xs text-gray-400 mb-4 line-clamp-2 leading-relaxed">
                            Has successfully completed all official guitar learning modules by @nde_guitar.
                        </p>
                    </div>

                    <!-- Card Footer & Verify Button -->
                    <div class="pt-3.5 border-t border-white/5 flex items-center justify-between gap-2 mt-2">
                        <span class="text-[11px] text-gray-400">
                            <i class="fa-regular fa-calendar me-1"></i> {{ $grad->completed_at }}
                        </span>

                        @if($isSelf)
                            <a href="{{ route('certificate.verify', $grad->cert_code) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-black font-extrabold border border-amber-400/40 text-xs shadow-md shadow-amber-500/20 transition">
                                <span>My Certificate</span>
                                <i class="fa-solid fa-award text-[10px]"></i>
                            </a>
                        @else
                            <a href="{{ route('certificate.verify', $grad->cert_code) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white/5 hover:bg-white/10 text-gray-300 hover:text-white border border-white/10 text-xs font-semibold transition">
                                <span>Verify Record</span>
                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-14 bg-zinc-900/40 border border-white/5 rounded-2xl shadow-lg">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center text-xl mx-auto mb-3">
                        <i class="fa-solid fa-award"></i>
                    </div>
                    <h3 class="font-bold text-white text-base">No Graduates Registered Yet</h3>
                    <p class="text-xs text-gray-400 mt-1 max-w-md mx-auto">Complete all course modules to become the first graduate featured in the Hall of Fame!</p>
                </div>
            @endforelse
        </div>

    </main>
</div>
@endsection
