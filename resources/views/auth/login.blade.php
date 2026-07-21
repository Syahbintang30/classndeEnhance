@extends('layouts.app')

@section('title', 'Login')

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
<div class="min-h-screen bg-[#08080a] text-white flex flex-col relative selection:bg-blue-600 selection:text-white overflow-x-hidden">
    
    {{-- Ambient Mesh Background Glows --}}
    <div class="absolute -top-32 left-1/4 w-[500px] h-[500px] bg-blue-600/10 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute bottom-10 right-1/4 w-[400px] h-[400px] bg-purple-600/10 rounded-full blur-[140px] pointer-events-none"></div>

    {{-- LMS Floating Glass Pill Header --}}
    @include('layouts.lms_header')

    {{-- Main 2-Column Split Screen --}}
    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8 flex items-center justify-center relative z-10">
        <div class="w-full grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
            
            <!-- Left Column: Cinematic Brand Showcase (50-50 Split) -->
            <div class="lg:col-span-7 relative hidden lg:block">
                <div class="relative rounded-3xl overflow-hidden border border-white/10 shadow-2xl aspect-[4/3] bg-zinc-950 group">
                    <!-- Background image -->
                    <img src="{{ asset('compro/img/nde2.webp') }}" alt="Nde Guitar Session" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 opacity-80">
                    
                    <!-- Ambient Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-950/40 to-transparent"></div>

                    <!-- Floating Content inside Left Image -->
                    <div class="absolute inset-0 p-10 flex flex-col justify-between">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-500/20 border border-blue-500/30 text-blue-400 text-xs font-bold uppercase tracking-wider backdrop-blur-md self-start">
                            <span class="w-2 h-2 rounded-full bg-blue-400 animate-ping"></span>
                            <span>Student Portal</span>
                        </div>

                        <div class="space-y-4">
                            <h2 class="font-display text-5xl text-white tracking-wide uppercase leading-none">
                                Master Guitar <br><span class="text-blue-500">10x Faster</span> With Nde
                            </h2>
                            <p class="text-gray-300 text-sm leading-relaxed max-w-md">
                                Access your 100+ HD video lessons, 1-on-1 live coaching calls, and 5 AI practice tools in one portal.
                            </p>

                            <!-- Mini Review Card -->
                            <div class="pt-4 flex items-center gap-4 border-t border-white/10">
                                <div class="flex -space-x-2">
                                    <img src="https://i.pravatar.cc/100?img=12" class="w-8 h-8 rounded-full border-2 border-zinc-900 object-cover" />
                                    <img src="https://i.pravatar.cc/100?img=33" class="w-8 h-8 rounded-full border-2 border-zinc-900 object-cover" />
                                    <img src="https://i.pravatar.cc/100?img=47" class="w-8 h-8 rounded-full border-2 border-zinc-900 object-cover" />
                                </div>
                                <div class="text-xs">
                                    <div class="text-amber-400 font-bold">★★★★★ <span class="text-white ml-1">4.9/5 Rating</span></div>
                                    <div class="text-gray-400">Trusted by 1,200+ active students</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Glassmorphic Login Form -->
            <div class="lg:col-span-5 w-full max-w-md mx-auto">
                <div class="bg-zinc-950/70 border border-white/10 backdrop-blur-2xl rounded-3xl p-8 sm:p-10 shadow-2xl relative overflow-hidden space-y-6">
                    
                    <!-- Top Title -->
                    <div class="text-center space-y-1.5">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 text-gray-300 text-[10px] font-bold uppercase tracking-widest">
                            ClassNde Portal
                        </div>
                        <h1 class="font-display text-4xl text-white tracking-wide uppercase">Welcome <span class="text-blue-500">Back</span></h1>
                        <p class="text-gray-400 text-xs">Enter your credentials to access your dashboard.</p>
                    </div>

                    @if(session('status'))
                        <div class="p-3.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs rounded-xl font-medium">{{ session('status') }}</div>
                    @endif

                    @if(session('error') || $errors->any())
                        <div class="p-3.5 bg-red-500/10 border border-red-500/20 text-red-400 text-xs rounded-xl font-medium space-y-1">
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
                        <a href="{{ route('auth.google.redirect') }}" class="w-full py-3 px-4 bg-zinc-900 border border-white/10 hover:border-white/20 rounded-xl text-xs font-bold text-gray-200 hover:text-white transition flex items-center justify-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 48 48" aria-hidden="true">
                                <path fill="#FFC107" d="M43.6 20.5H42V20H24v8h11.3C33.7 32.7 29.3 36 24 36c-6.6 0-12-5.4-12-12s5.4-12 12-12c3 0 5.7 1.1 7.8 2.9l5.7-5.7C34 6.1 29.3 4 24 4 12.9 4 4 12.9 4 24s8.9 20 20 20 20-8.9 20-20c0-1.3-.1-2.4-.4-3.5z"/>
                                <path fill="#FF3D00" d="M6.3 14.7l6.6 4.8C14.7 15 18.9 12 24 12c3 0 5.7 1.1 7.8 2.9l5.7-5.7C34 6.1 29.3 4 24 4c-7.7 0-14.4 4.4-17.7 10.7z"/>
                                <path fill="#4CAF50" d="M24 44c5.1 0 9.8-2 13.3-5.2l-6.1-5.2C29.2 35.1 26.7 36 24 36c-5.3 0-9.7-3.3-11.3-8l-6.6 5.1C9.3 39.5 16.1 44 24 44z"/>
                                <path fill="#1976D2" d="M43.6 20.5H42V20H24v8h11.3c-.8 2.5-2.4 4.6-4.4 6.1l.1-.1 6.1 5.2C36.7 39.5 44 34 44 24c0-1.3-.1-2.4-.4-3.5z"/>
                            </svg>
                            <span>Sign In with Google</span>
                        </a>

                        <div class="flex items-center gap-3 text-[11px] text-gray-500 my-2">
                            <div class="h-px bg-white/10 flex-1"></div>
                            <span>Or continue with email</span>
                            <div class="h-px bg-white/10 flex-1"></div>
                        </div>

                        <!-- Email -->
                        <div class="space-y-1.5">
                            <label for="login-email" class="text-xs font-bold text-gray-300">Email Address</label>
                            <input id="login-email" name="email" type="email" value="{{ old('email') }}" required autofocus class="w-full px-4 py-3 rounded-xl bg-zinc-900/80 border border-white/10 text-white text-xs placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition" placeholder="Enter your email">
                        </div>

                        <!-- Password -->
                        <div class="space-y-1.5" x-data="{ showPass: false }">
                            <label for="login-password" class="text-xs font-bold text-gray-300">Password</label>
                            <div class="relative flex items-center">
                                <input id="login-password" name="password" :type="showPass ? 'text' : 'password'" required class="w-full px-4 py-3 rounded-xl bg-zinc-900/80 border border-white/10 text-white text-xs placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition pr-10" placeholder="Enter your password">
                                <button type="button" @click="showPass = !showPass" class="absolute right-3 text-gray-400 hover:text-white transition">
                                    <i class="fa-solid" :class="showPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Meta Row -->
                        <div class="flex items-center justify-between text-xs text-gray-400 pt-1">
                            <label class="flex items-center gap-2 cursor-pointer hover:text-white transition">
                                <input type="checkbox" name="remember" class="rounded bg-zinc-900 border-white/10 text-blue-600 focus:ring-0">
                                <span>Remember me</span>
                            </label>
                            @if(Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-blue-400 hover:underline">Forgot password?</a>
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-display text-xl tracking-wider shadow-lg shadow-blue-600/30 transition hover:scale-[1.02] cursor-pointer">
                            SIGN IN
                        </button>
                    </form>

                    <!-- Footer -->
                    <div class="text-center text-xs text-gray-400 pt-2 border-t border-white/5">
                        Don’t have an account yet? <a href="{{ route('register') }}" class="text-blue-400 font-bold hover:underline">Sign up here</a>
                    </div>

                </div>
            </div>

        </div>
    </main>

</div>
@endsection
