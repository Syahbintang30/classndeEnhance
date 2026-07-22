@extends('layouts.app')

@section('title', 'Verified Certificate - ' . ($user->name ?? 'Student'))

@section('content')
@push('head')
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Cinzel:wght@600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Reenie+Beanie&display=swap" rel="stylesheet">
    
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
                        cinzel: ['"Cinzel"', 'serif'],
                        signature: ['"Reenie Beanie"', 'cursive'],
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
        .font-display {
            font-family: 'Bebas Neue', cursive;
            letter-spacing: 2px;
        }
        .font-cinzel {
            font-family: 'Cinzel', serif;
        }
        .font-signature {
            font-family: 'Reenie Beanie', cursive;
        }
        .cert-frame {
            background: linear-gradient(135deg, #111116 0%, #0a0a0d 100%);
            border: 10px solid #1E1B18;
            box-shadow: 0 20px 50px rgba(0,0,0,0.8), inset 0 0 40px rgba(245, 158, 11, 0.08);
            position: relative;
        }
        .cert-inner-border {
            border: 1px solid rgba(245, 158, 11, 0.3);
            outline: 1px solid rgba(245, 158, 11, 0.15);
            outline-offset: -6px;
        }
        .gold-gradient-text {
            background: linear-gradient(135deg, #FDE68A 0%, #F59E0B 50%, #D97706 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        @media print {
            @page {
                size: A4 landscape;
                margin: 0;
            }
            html, body {
                background-color: #08080a !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                height: 100% !important;
            }
            body * {
                visibility: hidden;
            }
            .cert-printable, .cert-printable * {
                visibility: visible;
            }
            .cert-printable {
                position: absolute !important;
                left: 50% !important;
                top: 50% !important;
                transform: translate(-50%, -50%) !important;
                width: 90% !important;
                max-width: 980px !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .cert-frame {
                border: 3px solid #F59E0B !important;
                box-shadow: none !important;
                background: #0f0f14 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .cert-inner-border {
                border: 1px solid rgba(245, 158, 11, 0.4) !important;
                outline: none !important;
            }
            .gold-gradient-text {
                background: none !important;
                -webkit-text-fill-color: initial !important;
                color: #F59E0B !important;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
@endpush

<div class="tw-dash min-h-screen flex flex-col antialiased bg-[#08080a] text-gray-200 relative overflow-hidden"
     x-data="{ mobileMenuOpen: false }">

    {{-- Ambient Mesh Glow Background --}}
    <div class="absolute -top-32 left-1/3 w-[600px] h-[600px] bg-amber-500/10 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute top-1/2 -right-32 w-[450px] h-[450px] bg-indigo-600/10 rounded-full blur-[120px] pointer-events-none"></div>

    {{-- Top Navigation Bar --}}
    <div class="no-print">
        @include('layouts.lms_header')
    </div>
    
    <main class="flex-1 py-8 px-4 sm:px-6 relative z-10">
        <!-- Top Action Buttons (Hidden when printing) -->
        <div class="max-w-4xl mx-auto flex flex-wrap items-center justify-between gap-4 mb-8 no-print">
            <a href="{{ route('graduates') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-zinc-900/60 border border-white/5 text-xs font-semibold text-gray-300 hover:text-white transition shadow-lg">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Hall of Fame</span>
            </a>

            <div class="flex items-center gap-2">
                <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/20 text-amber-400 text-xs font-bold transition shadow-lg">
                    <i class="fa-solid fa-print"></i>
                    <span>Print / Download PDF</span>
                </button>
                
                <a href="https://www.tiktok.com/@nde_guitar" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/5 hover:bg-white/10 border border-white/5 text-white text-xs font-bold transition shadow-lg">
                    <i class="fa-brands fa-tiktok"></i>
                    <span>Tag @nde_guitar</span>
                </a>
            </div>
        </div>

        <!-- CERTIFICATE PRINTABLE CONTAINER -->
        <div class="max-w-4xl mx-auto cert-printable">
            <div class="cert-frame rounded-3xl p-6 sm:p-12 relative overflow-hidden">
                
                <!-- Corner Decorative Badges -->
                <div class="absolute top-4 left-4 w-10 h-10 border-t-2 border-l-2 border-amber-500/50 pointer-events-none"></div>
                <div class="absolute top-4 right-4 w-10 h-10 border-t-2 border-r-2 border-amber-500/50 pointer-events-none"></div>
                <div class="absolute bottom-4 left-4 w-10 h-10 border-b-2 border-l-2 border-amber-500/50 pointer-events-none"></div>
                <div class="absolute bottom-4 right-4 w-10 h-10 border-b-2 border-r-2 border-amber-500/50 pointer-events-none"></div>

                <div class="cert-inner-border rounded-2xl p-6 sm:p-10 text-center relative z-10 space-y-6">
                    
                    <!-- Brand Badge Header -->
                    <div class="flex items-center justify-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-400 text-lg">
                            <i class="fa-solid fa-guitar"></i>
                        </div>
                        <span class="font-display text-2xl tracking-widest text-white">GUITARCLASSBYNDE</span>
                    </div>

                    <!-- Certificate Title -->
                    <div>
                        <h2 class="font-cinzel text-xs sm:text-sm uppercase tracking-[0.3em] text-amber-400 font-bold mb-1">
                            Official Verified Document
                        </h2>
                        <h1 class="font-cinzel text-2xl sm:text-4xl text-white font-extrabold tracking-wide">
                            CERTIFICATE OF COMPLETION
                        </h1>
                    </div>

                    <!-- Presented To -->
                    <div class="space-y-2 py-2">
                        <p class="text-xs text-gray-400 uppercase tracking-widest font-semibold">PROUDLY PRESENTED TO:</p>
                        <h3 class="font-cinzel text-2xl sm:text-4xl font-extrabold gold-gradient-text tracking-wide border-b border-amber-500/30 pb-3 inline-block px-8">
                            {{ $user->name }}
                        </h3>
                    </div>

                    <!-- Description Text -->
                    <p class="text-xs sm:text-sm text-gray-300 max-w-2xl mx-auto leading-relaxed font-normal">
                        In recognition of outstanding commitment, dedication, and successful completion of 100% of the official guitar learning curriculum at <strong>Guitarclassbynde</strong> (Total {{ $totalCourseTopics }} Course Topics).
                    </p>

                    <!-- Verification Status Banner -->
                    @if($isVerified)
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold">
                            <i class="fa-solid fa-square-check"></i>
                            <span>VERIFIED OFFICIAL GRADUATE</span>
                        </div>
                    @else
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs font-bold">
                            <i class="fa-solid fa-clock"></i>
                            <span>COURSE PROGRESS: {{ $completedCount }} / {{ $totalCourseTopics }} TOPICS</span>
                        </div>
                    @endif

                    <!-- Footer Signatures & QR Section -->
                    <div class="pt-8 border-t border-white/10 grid grid-cols-1 sm:grid-cols-3 gap-6 items-end text-center sm:text-left">
                        
                        <!-- Left: Date & Code -->
                        <div class="space-y-1">
                            <span class="block text-[10px] text-gray-500 uppercase tracking-wider font-semibold">DATE ISSUED</span>
                            <span class="block text-xs font-bold text-white">{{ $completedDate }}</span>
                            <span class="block font-mono text-[10px] text-amber-400/80 mt-1">ID: {{ $certCode }}</span>
                        </div>

                        <!-- Center: Verification QR Code -->
                        <div class="text-center flex flex-col items-center justify-center">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=90x90&data={{ urlencode(route('certificate.verify', $certCode)) }}&color=F59E0B&bgcolor=0A0A0D" alt="QR Verify" class="w-16 h-16 rounded-lg p-1 bg-zinc-950 border border-amber-500/30 mb-1" />
                            <span class="text-[9px] text-gray-400 uppercase tracking-wider">Scan to Verify</span>
                        </div>

                        <!-- Right: Instructor Signature -->
                        <div class="text-center sm:text-right space-y-1">
                            <span class="font-signature text-3xl text-amber-300 block -mb-2">Nde Guitar</span>
                            <span class="block text-xs font-bold text-white border-t border-amber-500/30 pt-1">NDE</span>
                            <span class="block text-[10px] text-gray-400">Founder & TikTok Creator (@nde_guitar)</span>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </main>

</div>
@endsection
