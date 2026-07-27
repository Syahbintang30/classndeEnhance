@extends('layouts.app')

@php
    $isEn = (session('app_lang', request('lang', 'id')) === 'en');
@endphp

@section('title', $isEn ? 'Choose Package - Guitarclassbynde' : 'Pilih Paket Kelas - Guitarclassbynde')

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
                        <strong class="text-white block font-bold text-xs">{{ $isEn ? 'Email Verification Sent!' : 'Verifikasi Email Terkirim!' }}</strong>
                        <span class="text-gray-300">{{ $isEn ? 'A verification link was sent to' : 'Link verifikasi telah dikirimkan ke' }} <u class="text-blue-400 font-semibold">{{ auth()->user()->email }}</u>. {{ $isEn ? 'Please verify to activate full course access.' : 'Silakan verifikasi email kamu untuk mengaktifkan akses penuh.' }}</span>
                    </div>
                </div>
                <form method="POST" action="{{ route('verification.send') }}" class="shrink-0">
                    @csrf
                    <button type="submit" class="px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-[11px] uppercase tracking-wider transition shadow-md hover:scale-105 active:scale-95 cursor-pointer">
                        {{ $isEn ? 'Resend' : 'Kirim Ulang' }}
                    </button>
                </form>
            </div>
        @endif

        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-bold uppercase tracking-widest">
            <i class="fa-solid fa-sparkles text-[11px]"></i>
            <span>{{ $isEn ? 'CHOOSE YOUR LEARNING PACKAGE' : 'PILIH PAKET BELAJAR KAMU' }}</span>
        </div>
        <h1 class="font-display text-4xl sm:text-6xl text-white tracking-wide uppercase leading-none">
            @if($isEn)
                The Final Step to <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-indigo-300 to-blue-500">Start Your Journey.</span>
            @else
                Langkah Terakhir untuk <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-indigo-300 to-blue-500">Memulai Perjalananmu.</span>
            @endif
        </h1>
        <p class="text-gray-400 text-xs sm:text-sm leading-relaxed max-w-xl mx-auto">
            {{ $isEn ? 'Choose the package that best fits your goals. Get lifetime access to video modules, structured lessons, and personalized 1-on-1 coaching.' : 'Pilih paket yang paling sesuai dengan tujuanmu. Dapatkan akses seumur hidup ke modul video, materi terstruktur, dan bimbingan 1-on-1.' }}
        </p>
    </section>


    @php
        $paymentBase = isset($lesson) && $lesson ? route('kelas.payment', $lesson->id) : null;
        $orderedPackages = $packages->reject(fn($p) => str_contains(strtolower((string)($p->slug ?? '')), 'upgrade'))
            ->sortBy(function ($pkg) {
                $slug = strtolower((string) ($pkg->slug ?? ''));
                $name = strtolower((string) ($pkg->name ?? ''));

                if (str_contains($slug, 'beginner') || str_contains($name, 'beginner')) {
                    return 0;
                }
                if (str_contains($slug, 'intermediate') || str_contains($name, 'intermediate')) {
                    return 1;
                }

                return 2;
            })->values();
    @endphp

    <!-- Package Cards Grid -->
    <main class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 pt-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8 items-stretch">
            
            @foreach ($orderedPackages as $pkg)
                @php
                    $isTicket = str_contains(strtolower((string)($pkg->slug ?? '')), 'ticket') || str_contains(strtolower((string)($pkg->name ?? '')), 'ticket');
                    $isFeatured = str_contains(strtolower((string)($pkg->slug ?? '')), 'intermediate') || str_contains(strtolower((string)($pkg->name ?? '')), 'intermediate');
                    $benefits = array_filter(array_map('trim', explode("\n", $pkg->benefits ?? '')));
                    $imgSrc = method_exists($pkg, 'imageUrl') ? $pkg->imageUrl() : ($pkg->image ? asset('storage/'.$pkg->image) : asset('pictures/'.$pkg->slug.'.jpg'));
                    $pricingUnit = $isTicket ? ($isEn ? '/ 1x session' : '/ 1x sesi') : ($isEn ? '/ lifetime' : '/ seumur hidup');
                @endphp

                <div class="bg-zinc-950/60 border rounded-[2rem] p-6 sm:p-7 backdrop-blur-2xl flex flex-col justify-between relative overflow-hidden transition-all duration-300 group hover:-translate-y-1.5 shadow-2xl {{ $isFeatured ? 'border-blue-500/50 shadow-[0_0_50px_rgba(59,130,246,0.25)] ring-1 ring-blue-500/30' : 'border-white/10 hover:border-white/20' }}">
                    
                    <!-- Inner Top Accent Border -->
                    <div class="absolute top-0 inset-x-0 h-[2px] bg-gradient-to-r {{ $isFeatured ? 'from-blue-500 via-indigo-400 to-cyan-400' : 'from-transparent via-white/20 to-transparent' }}"></div>

                    <div class="space-y-6">
                        <!-- Package Header Image & Badges -->
                        <div class="relative w-full h-40 rounded-2xl overflow-hidden bg-zinc-900 border border-white/10">
                            <img src="{{ $imgSrc }}" alt="{{ $pkg->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-80">
                            <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-950/40 to-transparent"></div>
                            
                            @if($isFeatured)
                                <div class="absolute top-3 right-3 z-20 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-[9px] font-extrabold px-3 py-1 rounded-full uppercase tracking-widest shadow-lg border border-white/20">
                                    {{ $isEn ? 'RECOMMENDED' : 'REKOMENDASI' }}
                                </div>
                            @endif

                            <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between z-10">
                                <span class="px-2.5 py-1 rounded-lg bg-zinc-950/80 border border-white/10 backdrop-blur-md text-blue-400 text-[10px] font-bold uppercase tracking-wider">
                                    {{ $pkg->slug }}
                                </span>
                            </div>
                        </div>

                        <!-- Title & Pricing -->
                        <div class="space-y-2">
                            <h3 class="font-display text-3xl text-white tracking-wide uppercase leading-tight">{{ $pkg->name }}</h3>
                            <div class="flex items-baseline gap-1.5">
                                <span class="text-xs text-gray-400 font-medium">Rp</span>
                                <span class="font-display text-4xl sm:text-5xl text-white tracking-tight">{{ number_format($pkg->price, 0, '', '.') }}</span>
                                <span class="text-[11px] text-gray-400 font-normal">{{ $pricingUnit }}</span>
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="h-px w-full bg-white/10"></div>

                        <!-- Benefits List -->
                        <div class="space-y-3">
                            <div class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">{{ $isEn ? 'WHAT\'S INCLUDED:' : 'FASILITAS YANG DIDAPAT:' }}</div>
                            <ul class="space-y-2.5 text-xs text-gray-300 font-medium">
                                @foreach($benefits as $b)
                                    <li class="flex items-start gap-2.5">
                                        <i class="fa-solid fa-circle-check text-blue-400 text-xs mt-0.5 shrink-0"></i>
                                        <span class="leading-relaxed">{{ $b }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- CTA Action Button -->
                    <div class="pt-8 space-y-3">
                        @if($paymentBase)
                            <a href="{{ $paymentBase }}?package_id={{ $pkg->id }}&package_qty=1" 
                               class="w-full py-4 rounded-xl font-display text-xl tracking-widest text-center flex items-center justify-center gap-2.5 shadow-xl transition-all duration-300 cursor-pointer {{ $isFeatured ? 'bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white shadow-blue-600/30 hover:scale-[1.02]' : 'bg-white/10 hover:bg-white/15 border border-white/10 text-white' }}">
                                <span>{{ $isEn ? 'BUY NOW' : 'BAYAR SEKARANG' }}</span>
                                <i class="fa-solid fa-arrow-right text-sm"></i>
                            </a>
                        @else
                            <a href="{{ route('register') }}?package_id={{ $pkg->id }}&package_qty=1" 
                               class="w-full py-4 rounded-xl font-display text-xl tracking-widest text-center flex items-center justify-center gap-2.5 shadow-xl transition-all duration-300 cursor-pointer {{ $isFeatured ? 'bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white shadow-blue-600/30 hover:scale-[1.02]' : 'bg-white/10 hover:bg-white/15 border border-white/10 text-white' }}">
                                <span>{{ $isEn ? 'SELECT PACKAGE' : 'PILIH PAKET INI' }}</span>
                                <i class="fa-solid fa-arrow-right text-sm"></i>
                            </a>
                        @endif

                        <div class="text-center text-[10px] text-gray-500 font-semibold flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-shield-halved text-emerald-400"></i>
                            <span>{{ $isEn ? '100% Secure Payment' : '100% Pembayaran Aman' }}</span>
                        </div>
                    </div>

                </div>
            @endforeach

        </div>
    </main>

</div>
@endsection
