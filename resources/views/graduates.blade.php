@extends('layouts.app')

@section('title', 'Hall of Fame - Graduates Guitarclassbynde')

@section('content')
@push('head')
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        .grad-wrapper {
            background-color: #08080a;
            color: #f3f4f6;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .font-display {
            font-family: 'Bebas Neue', cursive;
            letter-spacing: 1.5px;
        }
        .grad-card {
            background: rgba(18, 18, 24, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .grad-card:hover {
            border-color: rgba(245, 158, 11, 0.4);
            transform: translateY(-3px);
            box-shadow: 0 12px 30px -5px rgba(245, 158, 11, 0.15);
        }
        .gold-gradient-text {
            background: linear-gradient(135deg, #FCD34D 0%, #F59E0B 50%, #D97706 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .gold-border-glow {
            box-shadow: 0 0 15px rgba(245, 158, 11, 0.3);
        }
    </style>
@endpush

<div class="grad-wrapper min-h-screen relative overflow-hidden py-12 px-4 sm:px-6 lg:px-8">
    {{-- Background Ambient Lights --}}
    <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[700px] h-[700px] bg-amber-500/10 rounded-full blur-[160px] pointer-events-none"></div>
    <div class="absolute top-1/3 -right-32 w-[500px] h-[500px] bg-blue-600/10 rounded-full blur-[140px] pointer-events-none"></div>

    <div class="max-w-6xl mx-auto relative z-10 space-y-10">
        
        <!-- Header Title Section -->
        <div class="text-center space-y-4">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-400 text-xs font-bold uppercase tracking-wider shadow-inner">
                <i class="fa-solid fa-trophy text-amber-400"></i>
                <span>Official Hall of Fame</span>
            </div>
            
            <h1 class="font-display text-4xl sm:text-6xl text-white tracking-wider">
                GUITARCLASSBYNDE <span class="gold-gradient-text">GRADUATES</span>
            </h1>
            
            <p class="text-gray-400 max-w-2xl mx-auto text-sm sm:text-base font-normal leading-relaxed">
                Daftar alumni terverifikasi yang telah menuntaskan 100% kurikulum pembelajaran gitar di Guitarclassbynde.
            </p>

            <div class="pt-2">
                <a href="{{ route('lms.dashboard') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-zinc-900 border border-white/10 text-xs font-semibold text-gray-300 hover:text-white hover:border-amber-500/40 transition">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Kembali ke LMS Dashboard</span>
                </a>
            </div>
        </div>

        <!-- Graduates Stats Bar -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-3xl mx-auto">
            <div class="bg-zinc-900/60 border border-white/10 rounded-2xl p-4 text-center">
                <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">TOTAL LULUSAN</span>
                <span class="block text-2xl font-extrabold text-white mt-1">{{ count($graduates) }} Murid</span>
            </div>
            <div class="bg-zinc-900/60 border border-white/10 rounded-2xl p-4 text-center">
                <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">TOTAL MODUL TAMAT</span>
                <span class="block text-2xl font-extrabold text-amber-400 mt-1">{{ $totalCourseTopics }} Topics</span>
            </div>
            <div class="bg-zinc-900/60 border border-white/10 rounded-2xl p-4 text-center">
                <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">STATUS VERIFIKASI</span>
                <span class="block text-2xl font-extrabold text-emerald-400 mt-1">100% Authentic</span>
            </div>
        </div>

        <!-- Graduates Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($graduates as $grad)
                <div class="grad-card rounded-2xl p-6 relative flex flex-col justify-between overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-28 h-28 bg-amber-500/10 rounded-full blur-xl pointer-events-none"></div>

                    <div>
                        <!-- Top Row: Badge & Cert Code -->
                        <div class="flex items-center justify-between gap-2 mb-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[11px] font-bold">
                                <i class="fa-solid fa-circle-check text-[10px]"></i>
                                <span>VERIFIED GRADUATE</span>
                            </span>
                            <span class="font-mono text-[11px] text-gray-400 bg-white/5 px-2 py-0.5 rounded border border-white/5">
                                {{ $grad->cert_code }}
                            </span>
                        </div>

                        <!-- Student Profile Row -->
                        <div class="flex items-center gap-4 mb-4">
                            @if($grad->photo)
                                <img src="{{ $grad->photo }}" alt="{{ $grad->name }}" class="w-14 h-14 rounded-2xl object-cover border-2 border-amber-500/60 gold-border-glow flex-shrink-0" />
                            @else
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-500 to-amber-700 text-white font-black text-xl flex items-center justify-center border-2 border-amber-400/60 gold-border-glow flex-shrink-0">
                                    {{ strtoupper(substr($grad->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <h3 class="font-bold text-lg text-white truncate mb-0.5">
                                    {{ $grad->name }}
                                </h3>
                                <span class="inline-block px-2 py-0.5 rounded bg-blue-500/10 text-blue-400 border border-blue-500/20 text-[10px] font-semibold uppercase tracking-wider">
                                    {{ $grad->package_name }}
                                </span>
                            </div>
                        </div>

                        <p class="text-xs text-gray-400 mb-4 line-clamp-2 leading-relaxed">
                            Telah berhasil menyelesaikan seluruh materi & modul pembelajaran gitar resmi dari @nde_guitar.
                        </p>
                    </div>

                    <!-- Card Footer & Verify Button -->
                    <div class="pt-4 border-t border-white/5 flex items-center justify-between gap-2 mt-2">
                        <span class="text-[11px] text-gray-400">
                            <i class="fa-regular fa-calendar me-1"></i> {{ $grad->completed_at }}
                        </span>
                        <a href="{{ route('certificate.verify', $grad->cert_code) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-500/10 hover:bg-amber-500/20 text-amber-400 border border-amber-500/30 text-xs font-semibold transition">
                            <span>Sertifikat</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16 bg-zinc-900/40 border border-white/5 rounded-2xl">
                    <i class="fa-solid fa-award text-4xl text-gray-600 mb-3 block"></i>
                    <h3 class="font-bold text-white text-lg">Belum Ada Lulusan Terdaftar</h3>
                    <p class="text-xs text-gray-400 mt-1 max-w-md mx-auto">Selesaikan seluruh modul di kelas untuk menjadi lulusan pertama yang tampil di Hall of Fame!</p>
                </div>
            @endforelse
        </div>

    </div>
</div>
@endsection
