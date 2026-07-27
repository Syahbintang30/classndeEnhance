@extends('layouts.app')

@php
    $isEn = (session('app_lang', request('lang', 'id')) === 'en');
    $errors = $errors ?? new \Illuminate\Support\ViewErrorBag();
@endphp

@section('title', $isEn ? 'Login' : 'Masuk')

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

    {{-- LMS Floating Glass Pill Header --}}
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
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse shadow-[0_0_10px_rgba(59,130,246,1)]"></span>
                        {{ $isEn ? 'Student Portal' : 'Portal Murid' }}
                    </div>
                    <h1 class="font-display text-4xl text-white tracking-wide uppercase leading-none">
                        @if($isEn)
                            Welcome <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">Back</span>
                        @else
                            Selamat <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">Datang</span>
                        @endif
                    </h1>
                    <p class="text-gray-400 text-xs">{{ $isEn ? 'Enter your credentials to access your dashboard.' : 'Masukkan email & password untuk masuk ke dashboard kamu.' }}</p>
                </div>

                @if(session('status'))
                    <div class="p-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs rounded-xl font-medium text-center">{{ session('status') }}</div>
                @endif

                @if(session('error') || $errors->any())
                    <div class="p-3 bg-red-500/10 border border-red-500/20 text-red-400 text-xs rounded-xl font-medium space-y-1">
                        @if(session('error')) <div>{{ session('error') }}</div> @endif
                        @if($errors->any())
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $err)
                                    @if(str_contains(strtolower($err), 'these credentials do not match')) @continue @endif
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- Google Sign In -->
                    <a href="{{ route('auth.google.redirect') }}" class="w-full py-3 px-4 bg-white/5 border border-white/10 hover:border-white/30 hover:bg-white/10 rounded-xl text-[13px] font-bold text-white transition flex items-center justify-center gap-3 backdrop-blur-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 48 48" aria-hidden="true">
                            <path fill="#FFC107" d="M43.6 20.5H42V20H24v8h11.3C33.7 32.7 29.3 36 24 36c-6.6 0-12-5.4-12-12s5.4-12 12-12c3 0 5.7 1.1 7.8 2.9l5.7-5.7C34 6.1 29.3 4 24 4 12.9 4 4 12.9 4 24s8.9 20 20 20 20-8.9 20-20c0-1.3-.1-2.4-.4-3.5z"/>
                            <path fill="#FF3D00" d="M6.3 14.7l6.6 4.8C14.7 15 18.9 12 24 12c3 0 5.7 1.1 7.8 2.9l5.7-5.7C34 6.1 29.3 4 24 4c-7.7 0-14.4 4.4-17.7 10.7z"/>
                            <path fill="#4CAF50" d="M24 44c5.1 0 9.8-2 13.3-5.2l-6.1-5.2C29.2 35.1 26.7 36 24 36c-5.3 0-9.7-3.3-11.3-8l-6.6 5.1C9.3 39.5 16.1 44 24 44z"/>
                            <path fill="#1976D2" d="M43.6 20.5H42V20H24v8h11.3c-.8 2.5-2.4 4.6-4.4 6.1l.1-.1 6.1 5.2C36.7 39.5 44 34 44 24c0-1.3-.1-2.4-.4-3.5z"/>
                        </svg>
                        <span>{{ $isEn ? 'Sign In with Google' : 'Masuk dengan Google' }}</span>
                    </a>

                    <div class="flex items-center gap-3 text-[11px] text-gray-500 my-2 uppercase font-bold tracking-widest">
                        <div class="h-px bg-white/10 flex-1"></div>
                        <span>{{ $isEn ? 'Or' : 'Atau' }}</span>
                        <div class="h-px bg-white/10 flex-1"></div>
                    </div>

                    <!-- Email -->
                    <div class="space-y-1.5">
                        <label for="login-email" class="text-[12px] font-bold text-gray-300">{{ $isEn ? 'Email Address' : 'Alamat Email' }}</label>
                        <input id="login-email" name="email" type="email" value="{{ old('email') }}" required autofocus class="w-full px-4 py-3 rounded-xl bg-zinc-900/60 border border-white/10 text-white text-[13px] placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition backdrop-blur-md" placeholder="{{ $isEn ? 'Enter your email' : 'Masukkan alamat email kamu' }}">
                    </div>

                    <!-- Password -->
                    <div class="space-y-1.5" x-data="{ showPass: false }">
                        <div class="flex justify-between items-center">
                            <label for="login-password" class="text-[12px] font-bold text-gray-300">{{ $isEn ? 'Password' : 'Kata Sandi' }}</label>
                            @if(Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-[11px] text-blue-400 hover:text-blue-300 transition">{{ $isEn ? 'Forgot?' : 'Lupa?' }}</a>
                            @endif
                        </div>
                        <div class="relative flex items-center">
                            <input id="login-password" name="password" :type="showPass ? 'text' : 'password'" required class="w-full px-4 py-3 rounded-xl bg-zinc-900/60 border border-white/10 text-white text-[13px] placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition pr-11 backdrop-blur-md" placeholder="{{ $isEn ? 'Enter your password' : 'Masukkan kata sandi kamu' }}">
                            <button type="button" @click="showPass = !showPass" class="absolute right-3.5 text-gray-400 hover:text-white transition text-sm">
                                <i class="fa-solid" :class="showPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Meta Row -->
                    <div class="flex items-center text-[12px] text-gray-400 pt-1">
                        <label class="flex items-center gap-2.5 cursor-pointer hover:text-white transition group">
                            <div class="relative flex items-center justify-center w-4 h-4 rounded border border-white/20 bg-zinc-900/60 group-hover:border-blue-500 transition">
                                <input type="checkbox" name="remember" class="absolute opacity-0 w-full h-full cursor-pointer peer">
                                <i class="fa-solid fa-check text-[10px] text-blue-500 opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                            </div>
                            <span>{{ $isEn ? 'Remember me for 30 days' : 'Ingat saya di perangkat ini' }}</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-display text-xl tracking-widest shadow-lg shadow-blue-600/30 transition-all hover:scale-[1.02] cursor-pointer mt-2">
                        {{ $isEn ? 'SIGN IN' : 'MASUK' }}
                    </button>
                </form>

                <!-- Footer -->
                <div class="text-center text-[13px] text-gray-400 pt-3 border-t border-white/10">
                    {{ $isEn ? 'Don’t have an account yet?' : 'Belum punya akun?' }} <a href="{{ route('register') }}" class="text-blue-400 font-bold hover:text-blue-300 transition">{{ $isEn ? 'Sign up now' : 'Daftar sekarang' }}</a>
                </div>

            </div>
        </div>
    </main>

</div>
@endsection
