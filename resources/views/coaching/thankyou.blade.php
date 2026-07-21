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
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: { 50: '#f0f9ff', 100: '#e0f2fe', 500: '#0ea5e9', 600: '#0284c7', 900: '#0c4a6e' },
                        dark: { 900: '#08080a', 800: '#121218', 700: '#222230' }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        display: ['"Bebas Neue"', 'sans-serif']
                    }
                }
            }
        };
    </script>
    <style>
        .tw-dash {
            background-color: #08080a !important;
            color: #f3f4f6 !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
        }
        .tw-dash .font-display {
            font-family: 'Bebas Neue', cursive;
            letter-spacing: 1px;
        }
        .glass-panel {
            background: rgba(18, 18, 26, 0.6);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 1.5rem;
        }
        body > nav { display: none !important; }
        .tw-dash a { text-decoration: none; }
        .tw-dash *:focus { outline: none !important; }
    </style>
@endpush

@section('content')
<div class="tw-dash min-h-screen flex flex-col antialiased bg-[#08080a] text-gray-200 relative overflow-hidden" x-data="{ mobileMenuOpen: false }">
    
    {{-- Ambient Mesh Glow Background --}}
    <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-emerald-500/10 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute top-1/2 -left-32 w-[400px] h-[400px] bg-blue-600/10 rounded-full blur-[120px] pointer-events-none"></div>

    @include('layouts.lms_header')

    <main class="flex-1 flex flex-col items-center justify-center p-6 relative z-10 py-12">
        <div class="w-full max-w-xl mx-auto">
            
            @php
                $bookingObj = null;
                try {
                    $bookingId = request()->query('booking') ?? (isset($booking) && is_object($booking) ? $booking->id : (isset($booking) ? $booking : null));
                    if (!empty($bookingId)) {
                        $bookingObj = \App\Models\CoachingBooking::with('coach')->find($bookingId);
                    }
                } catch (\Throwable $e) {
                    $bookingObj = null;
                }
                
                $dateStr = "N/A";
                $timeStr = "N/A";
                $studentStr = auth()->user()->name ?? "Student";
                $coachStr = "Nde";
                
                if ($bookingObj && $bookingObj->booking_time) {
                    $dateStr = $bookingObj->booking_time->format('l, F j, Y');
                    $endTime = $bookingObj->booking_time->copy()->addMinutes($bookingObj->session_duration_minutes ?? 60);
                    $timeStr = $bookingObj->booking_time->format('h:i A') . ' - ' . $endTime->format('h:i A') . ' (WIB)';
                    if ($bookingObj->user) {
                        $studentStr = $bookingObj->user->name;
                    }
                    if ($bookingObj->coach) {
                        $coachStr = $bookingObj->coach->name;
                    }
                }
            @endphp

            <!-- SUCCESS HERO ANIMATION -->
            <div class="text-center space-y-4 mb-8 flex flex-col items-center">
                
                <!-- Glowing Pulsing Icon -->
                <div class="relative flex items-center justify-center mb-2">
                    <div class="absolute inset-0 bg-emerald-500/20 rounded-full blur-xl animate-pulse"></div>
                    <div class="w-24 h-24 bg-gradient-to-br from-emerald-500/20 to-emerald-600/10 rounded-full flex items-center justify-center border border-emerald-500/30 shadow-[0_0_40px_rgba(16,185,129,0.3)] relative z-10">
                        <div class="w-16 h-16 bg-gradient-to-tr from-emerald-500 to-emerald-400 rounded-full flex items-center justify-center text-black shadow-lg">
                            <i class="fa-solid fa-check text-3xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold uppercase tracking-wider">
                    <i class="fa-solid fa-circle-check"></i> Reservation Confirmed
                </div>

                <h1 class="font-display text-4xl sm:text-5xl text-white tracking-wide uppercase leading-none">
                    BOOKING CONFIRMED!
                </h1>
                <p class="text-gray-400 text-sm max-w-md mx-auto leading-relaxed">
                    You're all set! Your 1-on-1 private coaching session has been successfully scheduled. We look forward to meeting you.
                </p>
            </div>

            @if($bookingObj)
                <!-- GLASS SESSION DETAILS CARD -->
                <div class="glass-panel p-6 sm:p-8 mb-8 relative overflow-hidden">
                    <div class="flex items-center justify-between pb-4 mb-6 border-b border-white/5">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                            <i class="fa-solid fa-receipt text-blue-400"></i> Session Summary
                        </span>
                        <span class="text-[10px] font-bold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-0.5 rounded-full">
                            CONFIRMED
                        </span>
                    </div>
                    
                    <div class="space-y-5">
                        <!-- Date -->
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center text-lg flex-shrink-0">
                                <i class="fa-solid fa-calendar-day"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Date</p>
                                <p class="text-white font-bold text-base truncate">{{ $dateStr }}</p>
                            </div>
                        </div>

                        <!-- Time -->
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center text-lg flex-shrink-0">
                                <i class="fa-solid fa-clock"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Time</p>
                                <p class="text-white font-bold text-base truncate">{{ $timeStr }}</p>
                            </div>
                        </div>

                        <!-- Mentor / Coach -->
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center text-lg flex-shrink-0">
                                <i class="fa-solid fa-user-ninja"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Coach</p>
                                <p class="text-white font-bold text-base truncate">{{ $coachStr }}</p>
                            </div>
                        </div>

                        <!-- Student -->
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center text-lg flex-shrink-0">
                                <i class="fa-solid fa-user-graduate"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Student</p>
                                <p class="text-white font-bold text-base truncate">{{ $studentStr }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- DUAL ACTION BUTTONS -->
            <div class="flex flex-col sm:flex-row items-center gap-4 w-full">
                <a href="{{ route('coaching.upcoming') }}" class="w-full sm:flex-1 py-3.5 px-6 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-sm flex items-center justify-center gap-2 transition shadow-lg hover:shadow-blue-600/25">
                    <i class="fa-solid fa-calendar-check"></i>
                    <span>View Appointments</span>
                </a>
                
                <a href="{{ route('dashboard') }}" class="w-full sm:flex-1 py-3.5 px-6 rounded-xl bg-zinc-900 hover:bg-zinc-800 border border-white/10 text-gray-300 hover:text-white font-bold text-sm flex items-center justify-center gap-2 transition">
                    <i class="fa-solid fa-house"></i>
                    <span>Back to Dashboard</span>
                </a>
            </div>

        </div>
    </main>
</div>
@endsection
