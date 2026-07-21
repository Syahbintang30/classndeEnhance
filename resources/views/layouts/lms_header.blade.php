@php
    $routeName = request()->route() ? request()->route()->getName() : '';
    $isDashboard = str_contains($routeName, 'dashboard');
    $isLessons = str_contains($routeName, 'kelas');
    $isCoaching = str_contains($routeName, 'coaching');
    $isSongLib = str_contains($routeName, 'song.tutorial');
    $isPractice = str_contains($routeName, 'practice.');
@endphp

<header class="sticky top-0 z-40 bg-[#0a0a0e]/90 backdrop-blur-md border-b border-zinc-800/80 px-4 lg:px-8 py-3.5 flex items-center justify-between relative">
    <!-- Left: Logo -->
    <a href="{{ route('compro') }}" class="flex items-center gap-2 group shrink-0">
        <span class="font-display text-3xl tracking-wider text-white group-hover:text-blue-500 transition-colors">
            NDE <span class="text-blue-500 group-hover:text-white transition-colors">GUITAR</span>
        </span>
        <span class="px-2 py-0.5 text-[10px] uppercase tracking-widest font-extrabold bg-blue-600/20 text-blue-400 border border-blue-500/30 rounded">PRO</span>
    </a>

    <!-- Center: Centered Navigation Links -->
    <nav class="hidden md:flex items-center space-x-1 absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2">
        <a href="{{ route('lms.dashboard') }}" class="{{ $isDashboard ? 'bg-blue-600/20 text-blue-400' : 'text-gray-400 hover:text-white hover:bg-zinc-800/50' }} px-4 py-2 rounded-lg text-sm font-semibold transition flex items-center gap-2">
            <i class="fa-solid fa-chart-pie text-xs"></i> Dashboard
        </a>
        <a href="{{ route('kelas') }}" class="{{ $isLessons ? 'bg-blue-600/20 text-blue-400' : 'text-gray-400 hover:text-white hover:bg-zinc-800/50' }} px-4 py-2 rounded-lg text-sm font-semibold transition flex items-center gap-2">
            <i class="fa-solid fa-book-open text-xs"></i> Lessons
        </a>
        <a href="{{ route('coaching.upcoming') }}" class="{{ $isCoaching ? 'bg-blue-600/20 text-blue-400' : 'text-gray-400 hover:text-white hover:bg-zinc-800/50' }} px-4 py-2 rounded-lg text-sm font-semibold transition flex items-center gap-2">
            <i class="fa-solid fa-user-ninja text-xs"></i> 1-on-1 Coaching
        </a>
        <a href="{{ route('practice.index') }}" class="{{ $isPractice ? 'bg-blue-600/20 text-blue-400' : 'text-gray-400 hover:text-white hover:bg-zinc-800/50' }} px-4 py-2 rounded-lg text-sm font-semibold transition flex items-center gap-2">
            <i class="fa-solid fa-toolbox text-xs"></i> Practice Tools
        </a>
        @if(auth()->check() && auth()->user()->hasIntermediateAccess())
        <a href="{{ route('song.tutorial.index') }}" class="{{ $isSongLib ? 'bg-blue-600/20 text-blue-400' : 'text-gray-400 hover:text-white hover:bg-zinc-800/50' }} px-4 py-2 rounded-lg text-sm font-semibold transition flex items-center gap-2">
            <i class="fa-solid fa-music text-xs"></i> Song Library
        </a>
        @endif
    </nav>

    <!-- Right Side User -->
    <div class="flex items-center space-x-3 lg:space-x-4 shrink-0">
        {{-- Mobile hamburger --}}
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-gray-300 hover:text-white">
            <i class="fa-solid fa-bars text-lg"></i>
        </button>

        <!-- User Dropdown & Avatar -->
        <div class="hidden md:flex items-center gap-3 border-l border-zinc-800 pl-4 relative" x-data="{ open: false }">
            <div @click="open = !open" @click.away="open = false" class="flex items-center gap-3 cursor-pointer group">
                <div class="relative">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center font-bold text-white shadow-lg border border-blue-400/30 group-hover:border-blue-400/60 transition-all">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 border-2 border-black rounded-full"></span>
                </div>
                <div class="hidden xl:block text-left leading-tight">
                    <div class="text-xs font-bold text-white group-hover:text-blue-400 transition-colors">{{ auth()->user()->name ?? 'Student' }}</div>
                    <div class="text-[10px] text-gray-400">{{ (auth()->check() && auth()->user()->hasIntermediateAccess()) ? 'Intermediate Student' : 'Student' }}</div>
                </div>
                <i class="fa-solid fa-chevron-down text-xs text-gray-500 group-hover:text-gray-300 ml-1"></i>
            </div>
            
            <!-- Dropdown Menu -->
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 top-12 w-48 bg-zinc-900 border border-zinc-800 rounded-xl shadow-2xl py-2 z-50"
                 style="display: none;">
                @if(auth()->check() && (auth()->user()->is_admin || auth()->user()->is_superadmin))
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-blue-400 hover:text-blue-300 hover:bg-zinc-800/80 transition-colors font-medium">
                    <i class="fa-solid fa-shield-halved w-5 text-center mr-1"></i> Admin Panel
                </a>
                <div class="border-t border-zinc-800/80 my-1"></div>
                @endif
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-zinc-800/80 transition-colors">
                    <i class="fa-solid fa-gear w-5 text-center text-gray-500 mr-1"></i> Settings
                </a>
                <div class="border-t border-zinc-800/80 my-1"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-400 hover:text-red-300 hover:bg-red-500/10 transition-colors">
                        <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center mr-1"></i> Log out
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

{{-- ─── MOBILE NAVIGATION OVERLAY ───────────────────────────────────── --}}
<div x-show="mobileMenuOpen" x-transition.opacity class="mobile-menu-overlay fixed inset-0 z-50 bg-black/70 backdrop-blur-sm md:hidden" style="display: none;" @click="mobileMenuOpen = false">
    <div @click.stop class="mobile-menu-panel bg-zinc-900 border-r border-zinc-800 w-72 h-full p-6 space-y-4 overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <span class="font-display text-2xl text-white">NDE <span class="text-blue-500">GUITAR</span></span>
            <button @click="mobileMenuOpen = false" class="text-gray-400 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>

        <a href="{{ route('lms.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ $isDashboard ? 'bg-blue-600/20 text-blue-400' : 'text-gray-400 hover:text-white hover:bg-zinc-800/50' }} font-semibold text-sm">
            <i class="fa-solid fa-chart-pie"></i> Dashboard
        </a>
        <a href="{{ route('kelas') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ $isLessons ? 'bg-blue-600/20 text-blue-400' : 'text-gray-400 hover:text-white hover:bg-zinc-800/50' }} font-semibold text-sm">
            <i class="fa-solid fa-book-open"></i> Lessons
        </a>
        <a href="{{ route('coaching.upcoming') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ $isCoaching ? 'bg-blue-600/20 text-blue-400' : 'text-gray-400 hover:text-white hover:bg-zinc-800/50' }} font-semibold text-sm">
            <i class="fa-solid fa-user-ninja"></i> 1-on-1 Coaching
        </a>
        <a href="{{ route('practice.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ $isPractice ? 'bg-blue-600/20 text-blue-400' : 'text-gray-400 hover:text-white hover:bg-zinc-800/50' }} font-semibold text-sm">
            <i class="fa-solid fa-toolbox"></i> Practice Tools
        </a>
        @if(auth()->check() && auth()->user()->hasIntermediateAccess())
        <a href="{{ route('song.tutorial.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ $isSongLib ? 'bg-blue-600/20 text-blue-400' : 'text-gray-400 hover:text-white hover:bg-zinc-800/50' }} font-semibold text-sm">
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
