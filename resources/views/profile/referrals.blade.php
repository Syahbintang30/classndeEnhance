@extends('layouts.app')

@section('title', 'My Referrals - Guitarclassbynde')

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
    <div class="absolute top-1/4 left-1/4 w-[600px] h-[600px] bg-emerald-600/15 rounded-full blur-[150px] pointer-events-none z-0 mix-blend-screen"></div>
    <div class="absolute bottom-1/4 right-1/4 w-[500px] h-[500px] bg-blue-600/15 rounded-full blur-[150px] pointer-events-none z-0 mix-blend-screen"></div>

    {{-- LMS Floating Glass Pill Header --}}
    <div class="relative z-20">
        @include('layouts.lms_header')
    </div>

    {{-- Main Container --}}
    <main class="flex-1 w-full max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative z-10 space-y-8">
        
        <!-- Header Page Title -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold uppercase tracking-widest mb-2">
                    Rewards Program
                </div>
                <h1 class="font-display text-4xl sm:text-5xl text-white tracking-wide uppercase leading-none">My <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-400">Referrals</span></h1>
                <p class="text-gray-400 text-xs sm:text-sm mt-1">Track your referred students and active discount rewards.</p>
            </div>
            <a href="{{ route('profile') }}" class="py-2.5 px-5 rounded-xl bg-white/5 border border-white/10 hover:border-white/20 text-xs font-bold text-gray-300 hover:text-white transition inline-flex items-center gap-2 self-start sm:self-auto">
                <i class="fa-solid fa-arrow-left text-[10px]"></i>
                <span>Back to Profile</span>
            </a>
        </div>

        <!-- Referral Summary Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
            
            <!-- Left: Discount Summary -->
            <div class="md:col-span-5 bg-zinc-950/60 border border-white/10 backdrop-blur-3xl rounded-[2rem] p-6 shadow-2xl relative overflow-hidden space-y-4">
                <div class="absolute top-0 inset-x-0 h-[1px] bg-gradient-to-r from-transparent via-emerald-500/50 to-transparent"></div>
                
                <h3 class="font-display text-2xl text-white tracking-wide uppercase">Discount Summary</h3>

                <div class="space-y-3 text-xs">
                    <div class="flex justify-between items-center py-2 border-b border-white/5">
                        <span class="text-gray-400">Referred Students</span>
                        <span class="font-bold text-white text-sm">{{ $referred->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-white/5">
                        <span class="text-gray-400">Units Available</span>
                        <span class="font-bold text-emerald-400 text-sm">{{ $availableUnits ?? 0 }} &times; 25%</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-white/5">
                        <span class="text-gray-400">Current Discount</span>
                        <span class="font-display text-2xl text-emerald-400">{{ $referralDiscountPercent ?? 0 }}%</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-400">Units Redeemed</span>
                        <span class="font-bold text-gray-300">{{ $redeemedUnits ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- Right: Invite Link Box -->
            <div class="md:col-span-7 bg-zinc-950/60 border border-white/10 backdrop-blur-3xl rounded-[2rem] p-6 shadow-2xl relative overflow-hidden space-y-4">
                <div class="absolute top-0 inset-x-0 h-[1px] bg-gradient-to-r from-transparent via-blue-500/50 to-transparent"></div>

                <h3 class="font-display text-2xl text-white tracking-wide uppercase">Your Unique Invite Link</h3>
                @php $invite = url('/').'?ref='.(auth()->user()->referral_code ?? ''); @endphp
                
                <div class="space-y-3">
                    <div class="flex gap-2">
                        <input type="text" readonly value="{{ $invite }}" class="flex-1 px-4 py-3 rounded-xl bg-zinc-900/80 border border-white/10 text-emerald-400 font-mono text-xs focus:outline-none select-all">
                        <button type="button" onclick="copyInviteLink('{{ $invite }}', this)" class="px-5 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs transition cursor-pointer flex items-center gap-2 shrink-0 shadow-md">
                            <i class="fa-regular fa-copy text-xs"></i>
                            <span>Copy Link</span>
                        </button>
                    </div>
                    <p class="text-xs text-gray-400 leading-relaxed">
                        Each successful student signup adds 25% discount towards your next 1-on-1 coaching ticket (auto-applied at checkout, up to 100% off).
                    </p>
                </div>
            </div>

        </div>

        <!-- Referred Users Table Card -->
        <div class="bg-zinc-950/60 border border-white/10 backdrop-blur-3xl rounded-[2rem] p-6 sm:p-8 shadow-2xl relative overflow-hidden space-y-5">
            <div class="flex items-center justify-between border-b border-white/10 pb-4">
                <h3 class="font-display text-2xl text-white tracking-wide uppercase">Referred Students History</h3>
                <span class="text-xs font-bold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-3 py-1 rounded-full">
                    {{ $referred->count() }} Total
                </span>
            </div>

            @if($referred->isEmpty())
                <div class="text-center py-12 space-y-3">
                    <div class="w-16 h-16 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-2xl mx-auto">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>
                    <h4 class="font-bold text-white text-base">No Referrals Yet</h4>
                    <p class="text-gray-400 text-xs max-w-sm mx-auto">Share your unique invite link with friends to start earning coaching discounts.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-gray-400 uppercase text-[10px] tracking-wider border-b border-white/10">
                                <th class="py-3 px-4">Student Name</th>
                                <th class="py-3 px-4">Email</th>
                                <th class="py-3 px-4">Joined Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($referred as $r)
                                <tr class="hover:bg-white/5 transition">
                                    <td class="py-3.5 px-4 font-bold text-white flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center font-bold text-[10px] text-white">
                                            {{ mb_substr($r->name ?? 'U', 0, 1) }}
                                        </div>
                                        <span>{{ $r->name }}</span>
                                    </td>
                                    <td class="py-3.5 px-4 text-gray-300">{{ $r->email }}</td>
                                    <td class="py-3.5 px-4 text-gray-400 font-mono">{{ $r->created_at ? $r->created_at->format('M d, Y') : '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </main>

</div>

<script>
function copyInviteLink(text, btn) {
    if (!text) return;
    navigator.clipboard.writeText(text).then(() => {
        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-check text-xs"></i> <span>Copied!</span>';
        setTimeout(() => { btn.innerHTML = orig; }, 2000);
    });
}
</script>
@endsection
