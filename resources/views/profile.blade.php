@extends('layouts.app')

@section('title', 'My Profile - Guitarclassbynde')

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
        body > nav, .global-nav { display: none !important; }
        .glass-panel {
            background: rgba(12, 12, 18, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
@endpush

@section('content')
<div class="min-h-screen bg-[#08080a] text-white flex flex-col relative selection:bg-blue-600 selection:text-white overflow-hidden pb-16">
    
    {{-- Ambient Mesh Background Glows --}}
    <div class="absolute top-1/4 left-1/4 w-[600px] h-[600px] bg-blue-600/15 rounded-full blur-[150px] pointer-events-none z-0 mix-blend-screen"></div>
    <div class="absolute bottom-1/4 right-1/4 w-[500px] h-[500px] bg-purple-600/15 rounded-full blur-[150px] pointer-events-none z-0 mix-blend-screen"></div>

    {{-- LMS Floating Glass Pill Header --}}
    <div class="relative z-20">
        @include('layouts.lms_header')
    </div>

    {{-- Main Container --}}
    <main class="flex-1 w-full max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative z-10 space-y-8">
        
        <!-- Header Profile Card -->
        <div class="bg-zinc-950/60 border border-white/10 backdrop-blur-3xl rounded-[2rem] p-6 sm:p-8 shadow-2xl relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6">
            <!-- Glowing top accent line -->
            <div class="absolute top-0 inset-x-0 h-[1px] bg-gradient-to-r from-transparent via-blue-500/50 to-transparent"></div>

            <div class="flex flex-col sm:flex-row items-center gap-5 text-center sm:text-left">
                @php $avatar = auth()->user()->photoUrl(); @endphp
                <div class="w-20 h-20 rounded-full bg-zinc-900 border-2 border-white/20 flex items-center justify-center font-bold text-3xl text-white shadow-xl overflow-hidden shrink-0">
                    @if($avatar)
                        <img src="{{ $avatar }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover object-center rounded-full block">
                    @else
                        <span>{{ mb_substr(auth()->user()->name ?? 'U', 0, 1) }}</span>
                    @endif
                </div>

                <div class="space-y-1">
                    <div class="inline-flex items-center gap-2 px-3 py-0.5 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-[10px] font-bold uppercase tracking-widest">
                        {{ auth()->user()->hasIntermediateAccess() ? 'Intermediate Student' : 'Student Member' }}
                    </div>
                    <h1 class="font-display text-3xl sm:text-4xl text-white tracking-wide uppercase">{{ auth()->user()->name }}</h1>
                    <p class="text-xs text-gray-400 font-medium">{{ auth()->user()->email }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('profile.edit') }}" class="px-5 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-xs shadow-lg shadow-blue-600/30 transition hover:scale-105 inline-flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                    <span>Edit Account</span>
                </a>
            </div>
        </div>

        <!-- Quick Links Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <a href="{{ route('profile.edit') }}" class="glass-panel p-5 rounded-2xl border border-white/10 hover:border-blue-500/40 hover:bg-white/5 transition flex items-center justify-between group">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center text-sm group-hover:bg-blue-600 group-hover:text-white transition">
                        <i class="fa-solid fa-user-gear"></i>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-white group-hover:text-blue-400 transition-colors">Edit Profile</div>
                        <div class="text-[10px] text-gray-400">Name &amp; Avatar photo</div>
                    </div>
                </div>
                <i class="fa-solid fa-chevron-right text-xs text-gray-500 group-hover:text-white transition-transform group-hover:translate-x-1"></i>
            </a>

            <a href="{{ route('profile.edit') }}#password" class="glass-panel p-5 rounded-2xl border border-white/10 hover:border-blue-500/40 hover:bg-white/5 transition flex items-center justify-between group">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center text-sm group-hover:bg-purple-600 group-hover:text-white transition">
                        <i class="fa-solid fa-key"></i>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-white group-hover:text-purple-400 transition-colors">Change Password</div>
                        <div class="text-[10px] text-gray-400">Security &amp; Auth</div>
                    </div>
                </div>
                <i class="fa-solid fa-chevron-right text-xs text-gray-500 group-hover:text-white transition-transform group-hover:translate-x-1"></i>
            </a>

            <a href="{{ route('profile.referrals') }}" class="glass-panel p-5 rounded-2xl border border-white/10 hover:border-blue-500/40 hover:bg-white/5 transition flex items-center justify-between group">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-sm group-hover:bg-emerald-600 group-hover:text-white transition">
                        <i class="fa-solid fa-share-nodes"></i>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-white group-hover:text-emerald-400 transition-colors">My Referrals</div>
                        <div class="text-[10px] text-gray-400">Referral rewards</div>
                    </div>
                </div>
                <i class="fa-solid fa-chevron-right text-xs text-gray-500 group-hover:text-white transition-transform group-hover:translate-x-1"></i>
            </a>
        </div>

        <!-- Referral Code Banner Card -->
        <div class="bg-zinc-950/60 border border-white/10 backdrop-blur-3xl rounded-[2rem] p-6 sm:p-8 shadow-2xl relative overflow-hidden space-y-4">
            <!-- Glowing top accent line -->
            <div class="absolute top-0 inset-x-0 h-[1px] bg-gradient-to-r from-transparent via-emerald-500/50 to-transparent"></div>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="text-xs font-bold text-emerald-400 uppercase tracking-widest mb-1 flex items-center gap-2">
                        <i class="fa-solid fa-ticket"></i> Your Referral Code
                    </div>
                    <h3 class="font-display text-2xl text-white tracking-wide uppercase">Invite Friends &amp; Share Benefits</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Share your unique code with friends to enjoy learning discounts together.</p>
                </div>

                <div class="flex items-center gap-2 bg-white/5 border border-white/10 rounded-2xl p-2 px-4 shrink-0">
                    <span class="font-mono font-bold text-lg text-emerald-400 tracking-wider">{{ auth()->user()->referral_code ?? '—' }}</span>
                    <button onclick="copyCode('{{ auth()->user()->referral_code ?? '' }}', this)" class="p-2 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 hover:bg-emerald-500 hover:text-white transition cursor-pointer" title="Copy Referral Code">
                        <i class="fa-regular fa-copy text-xs"></i>
                    </button>
                </div>
            </div>
        </div>

    </main>

</div>

<script>
function copyCode(text, btn) {
    if (!text) return;
    navigator.clipboard.writeText(text).then(() => {
        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-check text-xs text-emerald-400"></i>';
        setTimeout(() => { btn.innerHTML = orig; }, 2000);
    });
}
</script>
@endsection
