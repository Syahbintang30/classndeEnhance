@php
    $routeName = request()->route() ? request()->route()->getName() : '';
    $isDashboard = str_contains($routeName, 'dashboard');
    $isLessons = str_contains($routeName, 'kelas');
    $isCoaching = str_contains($routeName, 'coaching');
    $isSongLib = str_contains($routeName, 'song.tutorial');
    $isPractice = str_contains($routeName, 'practice.');
@endphp

<header class="sticky top-0 z-40 bg-[#08080a]/85 backdrop-blur-xl border-b border-white/5 px-4 lg:px-8 py-3 flex items-center justify-between relative shadow-2xl">
    
    <!-- Left: Logo -->
    <div class="flex items-center justify-start min-w-[200px] sm:min-w-[240px]">
        <a href="{{ route('compro') }}" class="flex items-center gap-2.5 group shrink-0">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white text-base shadow-lg shadow-blue-600/30 group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-guitar"></i>
            </div>
            <div class="flex items-center gap-2">
                <span class="font-display text-2xl tracking-wide text-white group-hover:text-blue-400 transition-colors">
                    NDE <span class="text-blue-500 group-hover:text-white transition-colors">GUITAR</span>
                </span>
                <span class="px-2 py-0.5 text-[9px] uppercase tracking-widest font-extrabold bg-blue-500/10 text-blue-400 border border-blue-500/20 rounded-full">PRO</span>
            </div>
        </a>
    </div>

    <!-- Center: Floating Glass Pill Navigation (100% Dead-Centered) -->
    <div class="hidden md:flex items-center justify-center flex-1">
        <nav class="flex items-center gap-1 bg-zinc-950/70 border border-white/10 backdrop-blur-xl rounded-full p-1.5 shadow-2xl">
            <a href="{{ route('lms.dashboard') }}" class="{{ $isDashboard ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-600/25 font-bold' : 'text-gray-400 hover:text-white hover:bg-white/5 font-semibold' }} px-4 py-1.5 rounded-full text-xs transition-all flex items-center gap-2">
                <i class="fa-solid fa-chart-pie text-[11px]"></i>
                <span>Dashboard</span>
            </a>
            
            <a href="{{ route('kelas') }}" class="{{ $isLessons ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-600/25 font-bold' : 'text-gray-400 hover:text-white hover:bg-white/5 font-semibold' }} px-4 py-1.5 rounded-full text-xs transition-all flex items-center gap-2">
                <i class="fa-solid fa-book-open text-[11px]"></i>
                <span>Lessons</span>
            </a>
            
            <a href="{{ route('coaching.upcoming') }}" class="{{ $isCoaching ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-600/25 font-bold' : 'text-gray-400 hover:text-white hover:bg-white/5 font-semibold' }} px-4 py-1.5 rounded-full text-xs transition-all flex items-center gap-2">
                <i class="fa-solid fa-user-ninja text-[11px]"></i>
                <span>1-on-1 Coaching</span>
            </a>
            
            <a href="{{ route('practice.index') }}" class="{{ $isPractice ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-600/25 font-bold' : 'text-gray-400 hover:text-white hover:bg-white/5 font-semibold' }} px-4 py-1.5 rounded-full text-xs transition-all flex items-center gap-2">
                <i class="fa-solid fa-toolbox text-[11px]"></i>
                <span>Practice Tools</span>
            </a>
            
            @if(auth()->check() && auth()->user()->hasIntermediateAccess())
            <a href="{{ route('song.tutorial.index') }}" class="{{ $isSongLib ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-600/25 font-bold' : 'text-gray-400 hover:text-white hover:bg-white/5 font-semibold' }} px-4 py-1.5 rounded-full text-xs transition-all flex items-center gap-2">
                <i class="fa-solid fa-music text-[11px]"></i>
                <span>Song Library</span>
            </a>
            @endif
        </nav>
    </div>

    <!-- Right Side User Menu -->
    <div class="flex items-center justify-end min-w-[200px] sm:min-w-[240px] shrink-0 space-x-3">
        {{-- Mobile hamburger --}}
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-gray-300 hover:text-white">
            <i class="fa-solid fa-bars text-lg"></i>
        </button>

        <!-- User Dropdown & Avatar Pill -->
        <div class="hidden md:flex items-center relative" x-data="{ open: false }">
            <div @click="open = !open" @click.away="open = false" class="flex items-center gap-2.5 bg-zinc-950/60 border border-white/10 rounded-full px-3 py-1.5 hover:border-blue-500/40 transition-all cursor-pointer group shadow-md">
                <div class="relative">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center font-bold text-white text-xs shadow-md border border-white/20">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 border-2 border-[#08080a] rounded-full"></span>
                </div>
                
                <div class="hidden xl:block text-left leading-none pr-1">
                    <div class="text-xs font-bold text-white group-hover:text-blue-400 transition-colors mb-0.5">{{ auth()->user()->name ?? 'Student' }}</div>
                    <div class="text-[9px] font-semibold text-gray-400">{{ (auth()->check() && auth()->user()->hasIntermediateAccess()) ? 'Intermediate Student' : 'Student' }}</div>
                </div>
                
                <i class="fa-solid fa-chevron-down text-[10px] text-gray-500 group-hover:text-gray-300"></i>
            </div>
            
            <!-- Dropdown Menu -->
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="transform opacity-0 scale-95 -translate-y-2"
                 x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="transform opacity-0 scale-95 -translate-y-2"
                 class="absolute right-0 top-full mt-2.5 w-52 bg-[#0c0c12] border border-white/10 rounded-2xl shadow-2xl p-2 z-50 shadow-black/80"
                 style="display: none;">
                
                @if(auth()->check() && (auth()->user()->is_admin || auth()->user()->is_superadmin))
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2 text-xs text-blue-400 hover:text-blue-300 hover:bg-blue-500/10 rounded-xl transition font-semibold mb-1">
                    <i class="fa-solid fa-shield-halved text-xs"></i> Admin Panel
                </a>
                <div class="border-t border-white/5 my-1"></div>
                @endif
                
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-3 py-2 text-xs text-gray-300 hover:text-white hover:bg-white/5 rounded-xl transition font-medium">
                    <i class="fa-solid fa-gear text-xs text-gray-500"></i> Settings
                </a>
                
                <div class="border-t border-white/5 my-1"></div>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-xl transition font-semibold">
                        <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i> Log out
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

{{-- ─── MOBILE NAVIGATION OVERLAY ───────────────────────────────────── --}}
<div x-show="mobileMenuOpen" x-transition.opacity class="mobile-menu-overlay fixed inset-0 z-50 bg-black/80 backdrop-blur-md md:hidden" style="display: none;" @click="mobileMenuOpen = false">
    <div @click.stop class="mobile-menu-panel bg-zinc-950 border-r border-white/10 w-72 h-full p-6 space-y-4 overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <span class="font-display text-2xl text-white">NDE <span class="text-blue-500">GUITAR</span></span>
            <button @click="mobileMenuOpen = false" class="text-gray-400 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>

        <a href="{{ route('lms.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ $isDashboard ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold' : 'text-gray-400 hover:text-white hover:bg-white/5' }} text-xs">
            <i class="fa-solid fa-chart-pie"></i> Dashboard
        </a>
        <a href="{{ route('kelas') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ $isLessons ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold' : 'text-gray-400 hover:text-white hover:bg-white/5' }} text-xs">
            <i class="fa-solid fa-book-open"></i> Lessons
        </a>
        <a href="{{ route('coaching.upcoming') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ $isCoaching ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold' : 'text-gray-400 hover:text-white hover:bg-white/5' }} text-xs">
            <i class="fa-solid fa-user-ninja"></i> 1-on-1 Coaching
        </a>
        <a href="{{ route('practice.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ $isPractice ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold' : 'text-gray-400 hover:text-white hover:bg-white/5' }} text-xs">
            <i class="fa-solid fa-toolbox"></i> Practice Tools
        </a>
        @if(auth()->check() && auth()->user()->hasIntermediateAccess())
        <a href="{{ route('song.tutorial.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ $isSongLib ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold' : 'text-gray-400 hover:text-white hover:bg-white/5' }} text-xs">
            <i class="fa-solid fa-music"></i> Song Library
        </a>
        @endif
    </div>
</div>

<!-- GLOBAL AMBIENT BACKGROUND -->
<style>
    body { background-color: #050508 !important; }
    .tw-dash { background-color: transparent !important; }
</style>
<div class="fixed inset-0 z-[-1] pointer-events-none overflow-hidden">
    <!-- Subtle Grid Pattern -->
    <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 48px 48px;"></div>
    
    <!-- Ambient Glows -->
    <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] bg-blue-600/10 rounded-full blur-[120px]"></div>
    <div class="absolute top-[60%] -right-[10%] w-[60%] h-[60%] bg-indigo-900/10 rounded-full blur-[150px]"></div>
</div>
