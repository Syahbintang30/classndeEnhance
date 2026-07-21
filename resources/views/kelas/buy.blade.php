@extends('layouts.app')

@section('title', 'Choose Package - Guitarclassbynde')

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
<div class="min-h-screen bg-[#08080a] flex flex-col relative selection:bg-blue-600 selection:text-white pb-20 overflow-x-hidden" x-data="{ mobileMenuOpen: false }">
    
    <!-- Ambient Glass Background Effects -->
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[1000px] h-[500px] bg-blue-600/15 rounded-full blur-[160px] mix-blend-screen"></div>
        <div class="absolute top-1/2 -left-40 w-[600px] h-[600px] bg-indigo-600/10 rounded-full blur-[160px] mix-blend-screen"></div>
        <div class="absolute bottom-0 -right-40 w-[600px] h-[600px] bg-blue-500/10 rounded-full blur-[160px] mix-blend-screen"></div>
        <div class="absolute inset-0 opacity-[0.02]" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 36px 36px;"></div>
    </div>

    <!-- Header Navigation -->
    <div class="relative z-30">
        @include('layouts.lms_header')
    </div>

    <!-- Hero Header -->
    <section class="relative z-10 pt-10 pb-6 px-4 text-center max-w-3xl mx-auto space-y-4">
        @if (session('status'))
            <div class="max-w-xl mx-auto p-3.5 rounded-2xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 text-xs font-semibold flex items-center justify-center gap-2 shadow-lg">
                <i class="fa-solid fa-circle-check text-sm"></i>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if(auth()->check() && !auth()->user()->hasVerifiedEmail())
            <div class="max-w-2xl mx-auto p-4 rounded-2xl bg-blue-500/10 border border-blue-500/30 backdrop-blur-xl text-blue-200 text-xs text-left flex items-center justify-between gap-4 shadow-xl">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center shrink-0 border border-blue-500/30">
                        <i class="fa-solid fa-envelope-circle-check text-base"></i>
                    </div>
                    <div>
                        <strong class="text-white block font-bold text-xs">Email Verification Sent!</strong>
                        <span class="text-gray-300">A verification link was sent to <u class="text-blue-400 font-semibold">{{ auth()->user()->email }}</u>. Please verify to activate full course access.</span>
                    </div>
                </div>
                <form method="POST" action="{{ route('verification.send') }}" class="shrink-0">
                    @csrf
                    <button type="submit" class="px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-[11px] uppercase tracking-wider transition shadow-md hover:scale-105 active:scale-95 cursor-pointer">
                        Resend
                    </button>
                </form>
            </div>
        @endif

        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-bold uppercase tracking-widest">
            <i class="fa-solid fa-sparkles text-[11px]"></i>
            <span>CHOOSE YOUR LEARNING PACKAGE</span>
        </div>
        <h1 class="font-display text-4xl sm:text-6xl text-white tracking-wide uppercase leading-none">
            The Final Step to <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-indigo-300 to-blue-500">Start Your Journey.</span>
        </h1>
        <p class="text-gray-400 text-xs sm:text-sm leading-relaxed max-w-xl mx-auto">
            Choose the package that best fits your goals. Get lifetime access to video modules, structured lessons, and personalized 1-on-1 coaching.
        </p>
    </section>


    @php
        $paymentBase = isset($lesson) && $lesson ? route('kelas.payment', $lesson->id) : null;
        $orderedPackages = $packages->sortBy(function ($pkg) {
            $slug = strtolower((string) ($pkg->slug ?? ''));
            $name = strtolower((string) ($pkg->name ?? ''));

            if (str_contains($slug, 'intermediate') || str_contains($name, 'intermediate')) {
                return 1;
            }
            if (str_contains($slug, 'beginner') || str_contains($name, 'beginner')) {
                return 2;
            }
            if (str_contains($slug, 'ticket') || str_contains($slug, 'coaching') || str_contains($name, 'ticket')) {
                return 0;
            }
            return 99;
        })->values();
    @endphp

    <!-- Package Cards Grid -->
    <main class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8 items-stretch">
            @foreach($orderedPackages as $i => $pkg)
                @php
                    $paymentLink = $paymentBase
                        ? ($paymentBase . '?package_id=' . $pkg->id . '&package_qty=1')
                        : '#';
                    $isFeatured = ($pkg->slug ?? null) === 'intermediate';
                    $benefits = array_filter(array_map('trim', explode("\n", $pkg->benefits ?? '')));
                    $price = number_format($pkg->price, 0, ',', '.');
                    $imgSrc = $pkg->image
                        ? asset('storage/' . $pkg->image)
                        : asset('pictures/' . $pkg->slug . '.jpg');
                    $isTicketPkg = str_contains(strtolower((string)($pkg->slug ?? '')), 'ticket') || str_contains(strtolower((string)($pkg->name ?? '')), 'ticket');
                    $pricingUnit = $isTicketPkg ? '/ 1x' : '/ lifetime';
                @endphp


                @if($isFeatured)
                <!-- Featured Glass Card (Intermediate) -->
                <div class="group relative flex flex-col bg-zinc-950/70 border-2 border-blue-500/50 backdrop-blur-2xl rounded-3xl overflow-hidden shadow-[0_0_60px_-15px_rgba(59,130,246,0.35)] transition-all duration-300 hover:scale-[1.02] hover:border-blue-400">
                    
                    <!-- Top Gradient Line -->
                    <div class="h-1.5 bg-gradient-to-r from-blue-500 via-indigo-500 to-blue-400"></div>

                    <!-- Best Value Floating Badge -->
                    <div class="absolute top-4 right-4 z-20 px-3 py-1 rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-extrabold text-[10px] uppercase tracking-wider shadow-lg shadow-blue-600/40 border border-blue-400/30 flex items-center gap-1.5">
                        <i class="fa-solid fa-crown text-[10px]"></i>
                        <span>BEST VALUE</span>
                    </div>

                    <!-- Card Header Image -->
                    <div class="relative h-44 w-full overflow-hidden bg-zinc-900">
                        <img src="{{ $imgSrc }}" alt="{{ $pkg->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#08080a] via-[#08080a]/40 to-transparent"></div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-6 sm:p-7 flex-1 flex flex-col justify-between space-y-6 -mt-8 relative z-10">
                        <div>
                            <span class="text-blue-400 text-[11px] font-bold uppercase tracking-wider block mb-1">Recommended</span>
                            <h3 class="font-display text-3xl text-white uppercase tracking-wide leading-tight mb-2">{{ $pkg->name }}</h3>
                            
                            <div class="flex items-baseline gap-1 my-3">
                                <span class="text-xs font-bold text-gray-400">Rp</span>
                                <span class="text-3xl font-extrabold text-white tracking-tight">{{ $price }}</span>
                                <span class="text-xs text-gray-400 font-normal ml-1">{{ $pricingUnit }}</span>
                            </div>


                            <p class="text-gray-400 text-xs leading-relaxed mb-5">
                                {{ $pkg->description ?? 'Full access to intermediate modules, advanced techniques, and personal coaching.' }}
                            </p>

                            <!-- Benefits List -->
                            <div class="space-y-2.5 pt-3 border-t border-white/10">
                                @foreach($benefits as $benefit)
                                <div class="flex items-center gap-2.5 text-xs text-gray-200">
                                    <div class="w-4 h-4 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-500/30 text-[10px]">
                                        <i class="fa-solid fa-check"></i>
                                    </div>
                                    <span>{{ $benefit }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- CTA Button -->
                        <a href="{{ $paymentLink }}" class="w-full py-3.5 px-6 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-xs uppercase tracking-wider text-center shadow-lg shadow-blue-600/30 transition-all hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-2 cursor-pointer">
                            <span>Get Access Now</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>
                @else
                <!-- Regular Glass Card -->
                <div class="group relative flex flex-col bg-zinc-950/50 border border-white/10 backdrop-blur-xl rounded-3xl overflow-hidden shadow-xl transition-all duration-300 hover:scale-[1.02] hover:border-white/20 hover:bg-zinc-950/80">
                    
                    <!-- Card Header Image -->
                    <div class="relative h-44 w-full overflow-hidden bg-zinc-900">
                        <img src="{{ $imgSrc }}" alt="{{ $pkg->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-80">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#08080a] via-[#08080a]/40 to-transparent"></div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-6 sm:p-7 flex-1 flex flex-col justify-between space-y-6 -mt-8 relative z-10">
                        <div>
                            <span class="text-gray-400 text-[11px] font-semibold uppercase tracking-wider block mb-1">Standard Package</span>
                            <h3 class="font-display text-3xl text-white uppercase tracking-wide leading-tight mb-2">{{ $pkg->name }}</h3>
                            
                            <div class="flex items-baseline gap-1 my-3">
                                <span class="text-xs font-bold text-gray-400">Rp</span>
                                <span class="text-3xl font-extrabold text-white tracking-tight">{{ $price }}</span>
                                <span class="text-xs text-gray-400 font-normal ml-1">{{ $pricingUnit }}</span>
                            </div>


                            <p class="text-gray-400 text-xs leading-relaxed mb-5">
                                {{ $pkg->description ?? 'Get started with video lessons and personal coaching session.' }}
                            </p>

                            <!-- Benefits List -->
                            <div class="space-y-2.5 pt-3 border-t border-white/10">
                                @foreach($benefits as $benefit)
                                <div class="flex items-center gap-2.5 text-xs text-gray-300">
                                    <div class="w-4 h-4 rounded-full bg-blue-500/15 text-blue-400 flex items-center justify-center shrink-0 border border-blue-500/30 text-[10px]">
                                        <i class="fa-solid fa-check"></i>
                                    </div>
                                    <span>{{ $benefit }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- CTA Button -->
                        <a href="{{ $paymentLink }}" class="w-full py-3.5 px-6 rounded-xl bg-white/10 hover:bg-white/15 border border-white/15 text-white font-bold text-xs uppercase tracking-wider text-center transition-all hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-2 cursor-pointer">
                            <span>Choose Package</span>
                            <i class="fa-solid fa-chevron-right text-xs text-gray-400"></i>
                        </a>
                    </div>
                </div>
                @endif
            @endforeach
        </div>

        @if(!$paymentBase)
            <div class="mt-8 p-4 bg-amber-500/10 border border-amber-500/20 rounded-2xl text-amber-400 text-xs text-center flex items-center justify-center gap-2">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>No course material is currently available for checkout. Please contact support.</span>
            </div>
        @endif
    </main>

</div>
@endsection
