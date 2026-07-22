@php
    $routeName = request()->route() ? request()->route()->getName() : '';
    $isDashboard = str_contains($routeName, 'dashboard');
    $isLessons = str_contains($routeName, 'kelas');
    $isCoaching = str_contains($routeName, 'coaching');
    $isSongLib = str_contains($routeName, 'song.tutorial');
    $isPractice = str_contains($routeName, 'practice.');
    $isPublicPage = request()->routeIs('compro', 'login', 'register', 'password.*');
@endphp

<div x-data="{ mobileMenuOpen: false }" class="sticky top-0 z-40 w-full">
    <header class="bg-[#08080a]/85 backdrop-blur-xl border-b border-white/5 px-4 lg:px-8 py-3 flex items-center justify-between relative shadow-2xl">
        
        <!-- Left: Logo -->
        <div class="flex items-center justify-start min-w-[200px] sm:min-w-[240px]">
            <a href="{{ route('compro') }}" class="flex items-center group shrink-0 py-1">
                <img src="{{ asset('compro/img/logo_styled.png') }}" alt="Guitarclassbynde Logo" class="h-10 sm:h-12 w-auto object-contain group-hover:scale-105 transition-transform filter drop-shadow-md">
            </a>
        </div>

        <!-- Center: Floating Glass Pill Navigation (100% Dead-Centered) -->
        <div class="hidden md:flex items-center justify-center flex-1">
            <nav class="flex items-center gap-1 bg-zinc-950/70 border border-white/10 backdrop-blur-xl rounded-full p-1.5 shadow-2xl">
                @if(!auth()->check() || $isPublicPage)
                    <a href="{{ route('compro') }}#hero" class="text-gray-400 hover:text-white hover:bg-white/5 font-semibold px-4 py-1.5 rounded-full text-xs transition-all">Home</a>
                    <a href="{{ route('compro') }}#tools" class="text-gray-400 hover:text-white hover:bg-white/5 font-semibold px-4 py-1.5 rounded-full text-xs transition-all">Practice Tools</a>
                    <a href="{{ route('compro') }}#packages" class="text-gray-400 hover:text-white hover:bg-white/5 font-semibold px-4 py-1.5 rounded-full text-xs transition-all">Packages & Pricing</a>
                    <a href="{{ route('compro') }}#faq" class="text-gray-400 hover:text-white hover:bg-white/5 font-semibold px-4 py-1.5 rounded-full text-xs transition-all">FAQ</a>
                @else
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

                    <a href="{{ route('graduates') }}" class="{{ request()->routeIs('graduates') ? 'bg-gradient-to-r from-amber-500 to-amber-600 text-white shadow-lg shadow-amber-500/25 font-bold' : 'text-gray-400 hover:text-white hover:bg-white/5 font-semibold' }} px-4 py-1.5 rounded-full text-xs transition-all flex items-center gap-2">
                        <i class="fa-solid fa-trophy text-[11px] text-amber-400"></i>
                        <span>Hall of Fame</span>
                    </a>
                @endif
            </nav>
        </div>

        <!-- Right Side User Menu / Guest Actions -->
        <div class="flex items-center justify-end min-w-[200px] sm:min-w-[240px] shrink-0 space-x-3">
            {{-- Mobile hamburger button --}}
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2.5 rounded-xl text-gray-300 hover:text-white hover:bg-white/5 transition cursor-pointer border border-white/10 flex items-center justify-center">
                <i class="fa-solid" :class="mobileMenuOpen ? 'fa-xmark text-xl' : 'fa-bars text-lg'"></i>
            </button>

            @auth
                <!-- User Actions & Avatar Pill for Logged In User -->
                <div class="hidden md:flex items-center gap-3">
                    @if(auth()->check())
                        @php $userRank = auth()->user()->guitar_rank; @endphp
                        <a href="{{ route('practice.quiz') }}" class="hidden lg:flex items-center gap-2 bg-zinc-950/70 border border-amber-500/30 rounded-full px-3 py-1.5 hover:border-amber-500/60 transition-all shadow-md group">
                            <span class="w-6 h-6 rounded-full {{ $userRank['badge_bg'] }} flex items-center justify-center text-xs shadow-inner">
                                <i class="fa-solid {{ $userRank['icon'] }}"></i>
                            </span>
                            <div class="text-left leading-none pr-1">
                                <div class="text-[10px] font-extrabold text-amber-400 font-mono tracking-tight">{{ auth()->user()->xp ?? 0 }} XP</div>
                                <div class="text-[9px] font-bold text-gray-300 group-hover:text-white transition-colors">{{ $userRank['tier'] }}</div>
                            </div>
                        </a>
                    @endif

                    @if($isPublicPage)
                    <!-- Direct Enter LMS Button (Shown only on public/landing pages) -->
                    <a href="{{ route('lms.dashboard') }}" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold rounded-full text-xs shadow-lg shadow-blue-600/25 transition-all hover:scale-105 flex items-center gap-2">
                        <i class="fa-solid fa-graduation-cap text-xs"></i>
                        <span>Enter LMS</span>
                    </a>
                    @endif

                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <div @click="open = !open" @click.away="open = false" class="flex items-center gap-2.5 bg-zinc-950/60 border border-white/10 rounded-full px-3 py-1.5 hover:border-blue-500/40 transition-all cursor-pointer group shadow-md">
                            @php $headerAvatar = auth()->user()->photoUrl(); @endphp
                            <div class="relative shrink-0 w-8 h-8">
                                <div class="w-8 h-8 rounded-full bg-zinc-900 border border-white/20 shadow-md overflow-hidden shrink-0 relative block" style="width:32px !important;height:32px !important;border-radius:9999px !important;padding:0 !important;margin:0 !important;">
                                    @if($headerAvatar)
                                        <img src="{{ $headerAvatar }}" alt="{{ auth()->user()->name }}" style="position:absolute !important;top:0 !important;left:0 !important;width:100% !important;height:100% !important;min-width:100% !important;min-height:100% !important;max-width:none !important;max-height:none !important;object-fit:cover !important;object-position:center 35% !important;border-radius:9999px !important;display:block !important;margin:0 !important;padding:0 !important;" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                        <span class="hidden w-full h-full items-center justify-center bg-gradient-to-tr from-blue-600 to-indigo-500 text-white font-bold text-xs rounded-full">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</span>
                                    @else
                                        <span class="w-full h-full flex items-center justify-center bg-gradient-to-tr from-blue-600 to-indigo-500 text-white font-bold text-xs rounded-full">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</span>
                                    @endif
                                </div>
                                <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-emerald-500 border border-zinc-950 rounded-full z-10"></span>
                            </div>
                            
                            <div class="hidden xl:block text-left leading-none pr-1">
                                <div class="text-xs font-bold text-white group-hover:text-blue-400 transition-colors mb-0.5">{{ auth()->user()->name ?? 'Student' }}</div>
                                <div class="text-[9px] font-semibold text-gray-400">{{ auth()->user()->hasIntermediateAccess() ? 'Intermediate Student' : 'Student' }}</div>
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
                            
                            @if($isPublicPage)
                            <a href="{{ route('lms.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2 text-xs text-white hover:bg-blue-500/10 hover:text-blue-400 rounded-xl transition font-bold mb-1">
                                <i class="fa-solid fa-graduation-cap text-xs text-blue-400"></i> Enter LMS
                            </a>
                            <div class="border-t border-white/5 my-1"></div>
                            @endif

                            @if(auth()->user()->is_admin || auth()->user()->is_superadmin)
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2 text-xs text-blue-400 hover:text-blue-300 hover:bg-blue-500/10 rounded-xl transition font-semibold mb-1">
                                <i class="fa-solid fa-shield-halved text-xs"></i> Admin Panel
                            </a>
                            <div class="border-t border-white/5 my-1"></div>
                            @endif
                            
                            <a href="{{ route('profile') }}" class="flex items-center gap-2.5 px-3 py-2 text-xs text-gray-300 hover:text-white hover:bg-white/5 rounded-xl transition font-medium">
                                <i class="fa-solid fa-id-card text-xs text-blue-400"></i> My Profile
                            </a>

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
            @else
                <!-- Guest Actions (Login & Join Class Buttons) -->
                <div class="hidden md:flex items-center gap-3">
                    <a href="{{ route('login') }}" class="text-xs font-bold text-gray-300 hover:text-white px-4 py-2 rounded-xl transition hover:bg-white/5">
                        Log in
                    </a>
                    <a href="{{ route('register') }}" class="px-5 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold rounded-xl text-xs shadow-lg shadow-blue-600/25 transition-all hover:scale-105">
                        Get Access
                    </a>
                </div>
            @endauth
        </div>
    </header>

    {{-- ─── MOBILE NAVIGATION OVERLAY ───────────────────────────────────── --}}
    <div x-show="mobileMenuOpen" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="mobile-menu-overlay fixed inset-0 z-50 bg-black/80 backdrop-blur-md md:hidden" 
         style="display: none;" 
         @click="mobileMenuOpen = false">
        <div @click.stop 
             x-show="mobileMenuOpen"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="mobile-menu-panel bg-zinc-950 border-r border-white/10 w-72 h-full p-6 space-y-4 overflow-y-auto shadow-2xl">
            <div class="flex items-center justify-between mb-6">
                <a href="{{ route('compro') }}" class="flex items-center">
                    <img src="{{ asset('compro/img/logo_styled.png') }}" alt="Guitarclassbynde Logo" class="h-9 w-auto object-contain">
                </a>
                <button @click="mobileMenuOpen = false" class="text-gray-400 hover:text-white p-1 rounded-lg hover:bg-white/5 transition cursor-pointer"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            @if(!auth()->check() || request()->routeIs('compro', 'login', 'register', 'password.*'))
                <a href="{{ route('compro') }}#hero" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 text-xs">
                    <i class="fa-solid fa-house"></i> Home
                </a>
                <a href="{{ route('compro') }}#tools" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 text-xs">
                    <i class="fa-solid fa-toolbox"></i> Practice Tools
                </a>
                <a href="{{ route('compro') }}#packages" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 text-xs">
                    <i class="fa-solid fa-tags"></i> Packages & Pricing
                </a>
                <a href="{{ route('compro') }}#faq" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 text-xs">
                    <i class="fa-solid fa-circle-question"></i> FAQ
                </a>
                @if(!auth()->check())
                    <div class="border-t border-white/10 my-2 pt-4 space-y-2">
                        <a href="{{ route('login') }}" class="block text-center py-2.5 px-4 rounded-xl text-xs font-bold text-gray-300 hover:text-white bg-white/5">
                            Log in
                        </a>
                        <a href="{{ route('register') }}" class="block text-center py-2.5 px-4 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600">
                            Get Access
                        </a>
                    </div>
                @else
                    <div class="border-t border-white/10 my-2 pt-4 space-y-2">
                        <a href="{{ route('lms.dashboard') }}" class="block text-center py-2.5 px-4 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-graduation-cap text-xs"></i>
                            <span>Enter LMS</span>
                        </a>
                    </div>
                @endif
            @else
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

                <a href="{{ route('profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 text-xs">
                    <i class="fa-solid fa-id-card text-blue-400"></i> My Profile
                </a>

            @endif
        </div>
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
