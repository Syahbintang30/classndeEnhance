<div class="w-full max-w-4xl mx-auto flex flex-col items-center py-8">
    <div class="w-full relative overflow-hidden rounded-3xl border border-blue-500/20 bg-gradient-to-b from-[#12121e] to-[#0a0a12] p-8 sm:p-12 text-center shadow-2xl">
        
        <!-- Ambient Glow -->
        <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-96 h-96 bg-blue-600/15 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Lock Icon Badge -->
        <div class="w-20 h-20 mx-auto rounded-3xl bg-blue-500/10 border border-blue-500/30 text-blue-400 flex items-center justify-center text-3xl mb-6 shadow-lg shadow-blue-500/10">
            <i class="fa-solid fa-lock"></i>
        </div>

        <!-- Header Titles -->
        <span class="inline-block px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs font-bold uppercase tracking-widest mb-3">
            Free Trial Limit
        </span>

        <h2 class="text-3xl sm:text-4xl font-extrabold text-white uppercase tracking-wider mb-4">
            {{ $lesson->title ?? 'Locked Module' }}
        </h2>

        <p class="text-gray-300 text-sm sm:text-base leading-relaxed max-w-xl mx-auto mb-8">
            You are currently in <strong class="text-blue-400">Free Trial Mode</strong>. Upgrade your membership to get <strong class="text-white">Full Access</strong> to all course levels, interactive song libraries, and 1-on-1 private coaching sessions with <strong class="text-white">Mentor Nde</strong>!
        </p>

        <!-- Features Included List -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-2xl mx-auto mb-8 text-left">
            <div class="bg-zinc-950/60 border border-white/10 rounded-2xl p-4 flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-layer-group text-sm"></i>
                </div>
                <div>
                    <div class="text-xs font-bold text-white">Full Curriculum</div>
                    <div class="text-[11px] text-gray-400">All Modules & Lessons</div>
                </div>
            </div>

            <div class="bg-zinc-950/60 border border-white/10 rounded-2xl p-4 flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-user-ninja text-sm"></i>
                </div>
                <div>
                    <div class="text-xs font-bold text-white">1-on-1 Coaching</div>
                    <div class="text-[11px] text-gray-400">Private Session</div>
                </div>
            </div>

            <div class="bg-zinc-950/60 border border-white/10 rounded-2xl p-4 flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-music text-sm"></i>
                </div>
                <div>
                    <div class="text-xs font-bold text-white">Song Vault</div>
                    <div class="text-[11px] text-gray-400">Interactive TABs</div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('registerclass') }}" class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-extrabold rounded-2xl text-sm uppercase tracking-wider shadow-xl shadow-blue-600/30 transition transform hover:scale-105 flex items-center justify-center gap-2">
                <i class="fa-solid fa-bolt"></i>
                <span>Get Full Access Now</span>
            </a>

            <a href="https://wa.me/6285695988172?text=Halo%20Admin%20Guitarclassbynde%2C%20saya%20mau%20tanya%20upgrade%20full%20access" target="_blank" rel="noopener noreferrer" class="w-full sm:w-auto px-6 py-4 bg-white/5 hover:bg-white/10 text-gray-300 hover:text-white font-bold rounded-2xl text-sm border border-white/10 transition flex items-center justify-center gap-2">
                <i class="fa-brands fa-whatsapp text-emerald-400 text-base"></i>
                <span>Chat Support</span>
            </a>
        </div>

    </div>
</div>
