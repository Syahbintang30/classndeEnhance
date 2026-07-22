@php
    $firstTopic = $lesson->topics->first();
    $initialGuid = $firstTopic?->bunny_guid ?? '';
    $initialUrl = $firstTopic?->video_url ?? '';
@endphp

<style>
    /* Full-bleed Edge-to-Edge Video in Landscape Mode on Mobile & Tablet */
    @media (orientation: landscape) and (max-height: 600px) {
        .player-wrapper {
            margin-bottom: 8px !important;
        }
        #player {
            padding-bottom: 0 !important;
            height: 84vh !important;
            max-height: 84vh !important;
            border-radius: 14px !important;
        }
        #html5-player,
        #player iframe {
            height: 100% !important;
            width: 100% !important;
            object-fit: contain !important;
        }
        .header-lesson-info {
            margin-bottom: 8px !important;
        }
        .header-lesson-info h1 {
            font-size: 1.5rem !important;
        }
    }
</style>

<div class="w-full max-w-4xl mx-auto flex flex-col items-center">
    
    <!-- BADGE & LESSON TITLE HEADER -->
    <div class="header-lesson-info text-center space-y-2 mb-6">

        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-bold uppercase tracking-wider">
            <i class="fa-solid fa-circle-play text-blue-400"></i> Active Video Lesson
        </div>
        
        <h1 id="video-title" class="font-display text-4xl sm:text-5xl text-white tracking-wide uppercase leading-tight">
            {{ $firstTopic->title ?? 'No Topic Selected' }}
        </h1>
        
        <p id="video-description" class="text-gray-400 text-sm leading-relaxed max-w-xl mx-auto">
            {{ $firstTopic->description ?? '' }}
        </p>
    </div>

    <!-- THEATER VIDEO PLAYER CONTAINER WITH AMBIENT GLOW -->
    <div class="player-wrapper w-full relative mb-8 group">
        
        <!-- Ambient Backlight Glow Effect -->
        <div class="absolute -inset-1.5 bg-gradient-to-r from-blue-600/30 via-indigo-600/25 to-purple-600/30 rounded-[1.8rem] blur-xl opacity-75 group-hover:opacity-100 transition duration-500 pointer-events-none"></div>

        <!-- Video Player Box -->
        <div id="player" class="relative overflow-hidden rounded-2xl border border-white/10 shadow-[0_0_50px_rgba(0,0,0,0.8)] bg-zinc-950 w-full z-10" style="padding-bottom: 56.25%;">
            {{-- HTML5 player will be injected here when playing Bunny HLS/MP4 --}}
            <div id="video-placeholder" class="video-placeholder bg-cover bg-center absolute inset-0 flex items-center justify-center z-10" style="background-color:#08080a;"
                data-bunny-guid="{{ $initialGuid }}"
                data-topic-id="{{ $firstTopic?->id }}"
                data-video-id="{{ preg_match('/(youtu\.be\/|v=)([A-Za-z0-9_-]{11})/', $initialUrl, $m) ? ($m[2] ?? '') : '' }}"
            >
                <button id="custom-play" class="custom-play-btn w-20 h-20 rounded-full border border-white/20 bg-blue-600/80 hover:bg-blue-600 backdrop-blur-md cursor-pointer flex items-center justify-center text-white transition-all hover:scale-110 shadow-[0_0_30px_rgba(59,130,246,0.5)]" aria-label="Play video">
                    <i class="fa-solid fa-play text-2xl ml-1"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- CONTROLS & NAVIGATION BAR -->
    <div class="video-controls flex items-center justify-between gap-4 w-full bg-zinc-900/50 backdrop-blur-md border border-white/5 rounded-2xl p-4 shadow-lg flex-wrap sm:flex-nowrap">
        <button id="btn-prev" class="video-nav-btn relative overflow-hidden h-10 px-5 bg-zinc-800/80 hover:bg-zinc-700 text-gray-300 font-bold rounded-xl text-xs transition inline-flex items-center gap-2 border border-white/5 flex-none self-center disabled:opacity-40 disabled:cursor-not-allowed">
            <i class="fa-solid fa-chevron-left text-[10px]"></i>
            <span class="label">Previous Topic</span>
        </button>

        <!-- VIDEO RESOLUTION / QUALITY SELECTOR -->
        <div id="quality-selector-wrapper" class="relative inline-flex items-center gap-2 bg-zinc-950/80 border border-white/10 rounded-xl px-3.5 py-2 text-xs text-gray-300 hover:border-blue-500/50 transition shadow-inner">
            <i class="fa-solid fa-sliders text-blue-400 text-xs"></i>
            <span class="font-semibold text-gray-400 text-[11px] uppercase tracking-wider hidden sm:inline">Quality:</span>
            <select id="quality-select" class="bg-transparent text-white font-bold text-xs cursor-pointer border-none focus:ring-0 focus:outline-none pr-1">
                <option value="-1" class="bg-zinc-900 text-white">Auto (Adaptive)</option>
            </select>
        </div>

        <button id="btn-next" class="video-nav-btn relative overflow-hidden h-10 px-5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold rounded-xl text-xs transition inline-flex items-center gap-2 shadow-lg shadow-blue-600/20 border border-blue-500/30 flex-none self-center disabled:opacity-40 disabled:cursor-not-allowed">
            <span class="label">Next Topic</span>
            <i class="fa-solid fa-chevron-right text-[10px]"></i>
        </button>
    </div>


</div>



