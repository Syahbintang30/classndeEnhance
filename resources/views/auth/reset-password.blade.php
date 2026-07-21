@extends('layouts.app')

@section('title', 'Reset Password')

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
        <div class="w-full max-w-[420px] mx-auto animate-float-slow" style="animation-duration: 8s;">
            <div class="bg-zinc-950/60 border border-white/10 backdrop-blur-3xl rounded-[2rem] p-7 sm:p-8 shadow-[0_0_60px_-15px_rgba(59,130,246,0.3)] relative overflow-hidden space-y-5">
                
                <!-- Inner Glow top border -->
                <div class="absolute top-0 inset-x-0 h-[1px] bg-gradient-to-r from-transparent via-blue-500/50 to-transparent"></div>
                
                <!-- Top Title -->
                <div class="text-center space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-[10px] font-bold uppercase tracking-widest shadow-inner mb-2">
                        <i class="fa-solid fa-lock text-blue-400 text-xs"></i>
                        New Password
                    </div>
                    <h1 class="font-display text-4xl text-white tracking-wide uppercase leading-none">Set New <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">Password</span></h1>
                    <p class="text-gray-400 text-xs leading-relaxed">Create a strong new password for your account.</p>
                </div>

                @if(session('status'))
                    <div class="p-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs rounded-xl font-medium text-center flex items-center gap-2 justify-center">
                        <i class="fa-solid fa-circle-check text-sm"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="p-3 bg-red-500/10 border border-red-500/20 text-red-400 text-xs rounded-xl font-medium space-y-1">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Reset Password Form -->
                <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div class="space-y-1.5">
                        <label for="email" class="block text-xs font-bold text-gray-300">Email Address</label>
                        <div class="relative">
                            <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus placeholder="name@example.com" class="w-full bg-zinc-900/80 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-blue-500/60 focus:ring-2 focus:ring-blue-500/20 transition shadow-inner">
                        </div>
                    </div>

                    <div class="space-y-1.5" x-data="{ show: false }">
                        <label for="password" class="block text-xs font-bold text-gray-300">New Password</label>
                        <div class="relative">
                            <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input id="password" :type="show ? 'text' : 'password'" name="password" required placeholder="••••••••" class="w-full bg-zinc-900/80 border border-white/10 rounded-xl pl-11 pr-11 py-3 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-blue-500/60 focus:ring-2 focus:ring-blue-500/20 transition shadow-inner">
                            <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white transition cursor-pointer">
                                <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-1.5" x-data="{ show: false }">
                        <label for="password_confirmation" class="block text-xs font-bold text-gray-300">Confirm New Password</label>
                        <div class="relative">
                            <i class="fa-solid fa-shield-halved absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input id="password_confirmation" :type="show ? 'text' : 'password'" name="password_confirmation" required placeholder="••••••••" class="w-full bg-zinc-900/80 border border-white/10 rounded-xl pl-11 pr-11 py-3 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-blue-500/60 focus:ring-2 focus:ring-blue-500/20 transition shadow-inner">
                            <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white transition cursor-pointer">
                                <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3.5 px-6 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-xs tracking-wider uppercase shadow-lg shadow-blue-600/30 transition duration-300 hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-rotate text-xs"></i>
                        <span>Update Password</span>
                    </button>
                </form>

                <div class="pt-2 text-center border-t border-white/10">
                    <a href="{{ route('login') }}" class="text-xs text-gray-400 hover:text-white transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                        <span>Back to Sign In</span>
                    </a>
                </div>

            </div>
        </div>
    </main>

</div>
@endsection
