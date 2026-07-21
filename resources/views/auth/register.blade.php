@extends('layouts.app')

@section('title', 'Register')

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
        <img src="{{ asset('compro/img/nde2.webp') }}" alt="Background" class="w-full h-full object-cover opacity-30 scale-105" style="object-position: center 20%; filter: contrast(1.1) brightness(0.7);">
        <div class="absolute inset-0 bg-gradient-to-b from-[#08080a]/90 via-[#08080a]/50 to-[#08080a]"></div>
    </div>
    
    {{-- Ambient Mesh Background Glows --}}
    <div class="absolute top-1/4 left-1/4 w-[600px] h-[600px] bg-blue-600/15 rounded-full blur-[150px] pointer-events-none z-0 mix-blend-screen"></div>
    <div class="absolute bottom-1/4 right-1/4 w-[500px] h-[500px] bg-emerald-600/15 rounded-full blur-[150px] pointer-events-none z-0 mix-blend-screen"></div>

    {{-- LMS Floating Glass Pill Header --}}
    <div class="relative z-20">
        @include('layouts.lms_header')
    </div>

    {{-- Main Centered Card Container --}}
    <main class="flex-1 w-full mx-auto p-4 sm:p-6 lg:p-8 flex items-center justify-center relative z-10 min-h-[calc(100vh-100px)]">
        
        <!-- Centered Glassmorphic Form Card -->
        <div class="w-full max-w-[440px] mx-auto animate-float-slow" style="animation-duration: 8s;">
            <div class="bg-zinc-950/60 border border-white/10 backdrop-blur-3xl rounded-[2rem] p-7 sm:p-8 shadow-[0_0_60px_-15px_rgba(59,130,246,0.3)] relative overflow-hidden space-y-5">
                
                <!-- Inner Glow top border -->
                <div class="absolute top-0 inset-x-0 h-[1px] bg-gradient-to-r from-transparent via-blue-500/50 to-transparent"></div>
                
                <!-- Top Title -->
                <div class="text-center space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-[10px] font-bold uppercase tracking-widest shadow-inner mb-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse shadow-[0_0_10px_rgba(59,130,246,1)]"></span>
                        New Account
                    </div>
                    <h1 class="font-display text-4xl text-white tracking-wide uppercase leading-none">Create <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">Account</span></h1>
                    <p class="text-gray-400 text-xs">Fill in your details to start learning guitar.</p>
                </div>

                @if(session('status'))
                    <div class="p-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs rounded-xl font-medium text-center">{{ session('status') }}</div>
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

                <!-- Register Form -->
                <form method="POST" action="{{ route('register') }}" id="registerForm" class="space-y-4">
                    @csrf

                    <!-- Google Sign Up -->
                    <a href="{{ route('auth.google.redirect') }}{{ request()->query('package_id') ? '?package_id=' . request()->query('package_id') . '&package_qty=' . request()->query('package_qty', 1) : '' }}" class="w-full py-3 px-4 bg-white/5 border border-white/10 hover:border-white/30 hover:bg-white/10 rounded-xl text-[13px] font-bold text-white transition flex items-center justify-center gap-3 backdrop-blur-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 48 48" aria-hidden="true">
                            <path fill="#FFC107" d="M43.6 20.5H42V20H24v8h11.3C33.7 32.7 29.3 36 24 36c-6.6 0-12-5.4-12-12s5.4-12 12-12c3 0 5.7 1.1 7.8 2.9l5.7-5.7C34 6.1 29.3 4 24 4 12.9 4 4 12.9 4 24s8.9 20 20 20 20-8.9 20-20c0-1.3-.1-2.4-.4-3.5z"/>
                            <path fill="#FF3D00" d="M6.3 14.7l6.6 4.8C14.7 15 18.9 12 24 12c3 0 5.7 1.1 7.8 2.9l5.7-5.7C34 6.1 29.3 4 24 4c-7.7 0-14.4 4.4-17.7 10.7z"/>
                            <path fill="#4CAF50" d="M24 44c5.1 0 9.8-2 13.3-5.2l-6.1-5.2C29.2 35.1 26.7 36 24 36c-5.3 0-9.7-3.3-11.3-8l-6.6 5.1C9.3 39.5 16.1 44 24 44z"/>
                            <path fill="#1976D2" d="M43.6 20.5H42V20H24v8h11.3c-.8 2.5-2.4 4.6-4.4 6.1l.1-.1 6.1 5.2C36.7 39.5 44 34 44 24c0-1.3-.1-2.4-.4-3.5z"/>
                        </svg>
                        <span>Sign Up with Google</span>
                    </a>

                    <div class="flex items-center gap-3 text-[11px] text-gray-500 my-2 uppercase font-bold tracking-widest">
                        <div class="h-px bg-white/10 flex-1"></div>
                        <span>Or</span>
                        <div class="h-px bg-white/10 flex-1"></div>
                    </div>

                    <!-- Name -->
                    <div class="space-y-1.5">
                        <label for="register-name" class="text-[12px] font-bold text-gray-300">Full Name</label>
                        <input id="register-name" name="name" type="text" value="{{ old('name') }}" required autofocus class="w-full px-4 py-3 rounded-xl bg-zinc-900/60 border border-white/10 text-white text-[13px] placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition backdrop-blur-md" placeholder="Enter your full name">
                    </div>

                    <!-- Email -->
                    <div class="space-y-1.5">
                        <label for="register-email" class="text-[12px] font-bold text-gray-300">Email Address</label>
                        <input id="register-email" name="email" type="email" value="{{ old('email') }}" required class="w-full px-4 py-3 rounded-xl bg-zinc-900/60 border border-white/10 text-white text-[13px] placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition backdrop-blur-md" placeholder="Enter your email">
                    </div>

                    <!-- Password Group (2 cols on sm) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Password -->
                        <div class="space-y-1.5" x-data="{ showPass: false }">
                            <label for="register-password" class="text-[12px] font-bold text-gray-300">Password</label>
                            <div class="relative flex items-center">
                                <input id="register-password" name="password" :type="showPass ? 'text' : 'password'" required class="w-full px-4 py-3 rounded-xl bg-zinc-900/60 border border-white/10 text-white text-[13px] placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition pr-10 backdrop-blur-md" placeholder="Create password">
                                <button type="button" @click="showPass = !showPass" class="absolute right-3.5 text-gray-400 hover:text-white transition text-sm">
                                    <i class="fa-solid" :class="showPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="space-y-1.5" x-data="{ showPassConf: false }">
                            <label for="register-password-confirmation" class="text-[12px] font-bold text-gray-300">Confirm</label>
                            <div class="relative flex items-center">
                                <input id="register-password-confirmation" name="password_confirmation" :type="showPassConf ? 'text' : 'password'" required class="w-full px-4 py-3 rounded-xl bg-zinc-900/60 border border-white/10 text-white text-[13px] placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition pr-10 backdrop-blur-md" placeholder="Repeat password">
                                <button type="button" @click="showPassConf = !showPassConf" class="absolute right-3.5 text-gray-400 hover:text-white transition text-sm">
                                    <i class="fa-solid" :class="showPassConf ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="package_id" value="{{ request()->query('package_id') }}">
                    <input type="hidden" name="package_qty" value="{{ request()->query('package_qty', 1) }}">

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-display text-xl tracking-widest shadow-lg shadow-blue-600/30 transition-all hover:scale-[1.02] cursor-pointer mt-2">
                        CREATE ACCOUNT
                    </button>
                </form>

                <!-- Footer -->
                <div class="text-center text-[13px] text-gray-400 pt-3 border-t border-white/10">
                    Already have an account? <a href="{{ route('login') }}" class="text-blue-400 font-bold hover:text-blue-300 transition">Sign in here</a>
                </div>

            </div>
        </div>
    </main>

</div>
@endsection