@extends('layouts.app')

@section('title', 'Kelas Guitar')

@section('content')
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
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            black: '#0a0a0c',
                            card: '#121218',
                            border: '#222230',
                            accent: '#0066ff',
                            amber: '#f59e0b',
                            crimson: '#ef4444',
                            glow: 'rgba(0, 102, 255, 0.15)'
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        display: ['"Bebas Neue"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        /* Scope the tailwind dark design to the dashboard shell */
        .tw-dash {
            background-color: #08080a !important;
            color: #f3f4f6 !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
        }
        .tw-dash .font-display {
            font-family: 'Bebas Neue', cursive;
            letter-spacing: 1px;
        }
        
        /* Hide the existing global navbar when this dashboard is loaded */
        body > nav { display: none !important; }

        /* Custom scrollbar within the dashboard */
        .tw-dash ::-webkit-scrollbar { width: 6px; }
        .tw-dash ::-webkit-scrollbar-track { background: #0d0d12; }
        .tw-dash ::-webkit-scrollbar-thumb { background: #222232; border-radius: 3px; }
        .tw-dash ::-webkit-scrollbar-thumb:hover { background: #3b82f6; }

        /* Remove default anchor underlines inside the dashboard */
        .tw-dash a { text-decoration: none; }
        
        /* Remove default focus outlines */
        .tw-dash *:focus { outline: none !important; }

        /* Legacy classes needed for JS but restyled */
        .sidebar { transition: all 0.3s ease; }
        .topic-check {
            appearance: none;
            -webkit-appearance: none;
            border: 2px solid #52525b; /* zinc-600 */
            border-radius: 4px;
            background: transparent;
            width: 18px;
            height: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
        .topic-item::before, .topic-item:before {
            display: none !important;
            content: none !important;
            width: 0 !important;
        }
        .topic-item.completed .topic-check {
            background: #3b82f6; /* blue-500 */
            border-color: #3b82f6;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' fill='none'%3E%3Cpath d='M2 6l2.2 2.2L10 2.4' stroke='%23ffffff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: center;
            background-size: 12px 12px;
        }
        
        .topic-item.selected {
            background: rgba(59, 130, 246, 0.15) !important;
            border-color: rgba(59, 130, 246, 0.4) !important;
            color: #ffffff !important;
            font-weight: 700 !important;
        }
        
        .lesson-block.active .lesson-header {
            background: rgba(255, 255, 255, 0.05);
            color: #ffffff;
        }
        .lesson-block.active .lesson-arrow {
            transform: rotate(90deg);
        }
        .topic-list { display: none; }
        .topic-list.active { display: block; }
        
        #sidebar-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(4px);
            z-index: 30;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s;
        }
        #sidebar-backdrop.visible {
            opacity: 1;
            pointer-events: auto;
        }
        .video-nav-btn {
            position: relative !important;
            overflow: hidden !important;
            height: 40px !important;
            max-height: 40px !important;
            flex-shrink: 0 !important;
            box-sizing: border-box !important;
        }
        .ripple {
            position: absolute !important;
            border-radius: 50% !important;
            background: rgba(255, 255, 255, 0.3) !important;
            transform: scale(0);
            animation: ripple-anim 0.6s linear;
            pointer-events: none !important;
        }
        @keyframes ripple-anim {
            to { transform: scale(2.5); opacity: 0; }
        }
    </style>
@endpush

@section('content')
<div class="tw-dash min-h-screen flex flex-col antialiased bg-[#08080a] text-gray-200 relative overflow-hidden"
     x-data="{ mobileMenuOpen: false }">

    {{-- Ambient Mesh Glow Background --}}
    <div class="absolute -top-32 left-1/3 w-[600px] h-[600px] bg-blue-600/10 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute top-1/2 -right-32 w-[450px] h-[450px] bg-indigo-600/10 rounded-full blur-[120px] pointer-events-none"></div>

    {{-- ─── TOP NAVIGATION BAR ──────────────────────────────────────────── --}}
    @include('layouts.lms_header')

    <!-- MAIN LESSONS CONTAINER -->
    <!-- MAIN LESSONS CONTAINER -->
    <div class="flex-1 flex flex-col md:flex-row w-full max-w-screen-2xl mx-auto overflow-y-auto relative z-10">
        
        <!-- Main Content Area (Video First on Mobile) -->
        <div class="main-wrapper flex-1 relative w-full order-1 md:order-2">
            <main class="content p-4 md:p-8 max-w-5xl mx-auto w-full flex flex-col items-center">
                @php $firstLesson = $lessons->first(); @endphp
                @include('kelas._lesson_content', ['lesson' => $firstLesson])
            </main>
        </div>

        <!-- Sidebar Navigation for Modules/Topics (Below Video on Mobile, Left Sidebar on Desktop) -->
        <aside class="sidebar w-full md:w-80 flex-shrink-0 bg-zinc-950/80 backdrop-blur-md border-t md:border-t-0 md:border-r border-white/10 order-2 md:order-1 relative p-4 md:p-0">
            <div class="p-4 md:p-5 border-b border-white/10 flex items-center justify-between">
                <h3 class="font-display text-2xl text-white tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-list-ul text-blue-500"></i> Course Syllabus
                </h3>
            </div>
            
            <div class="p-0 md:p-4 mt-4 md:mt-0">
                <!-- SINGLE UNIFIED SYLLABUS CONTAINER (EXCLUSIVE ACCORDION) -->
                <div class="bg-zinc-900/60 border border-white/10 rounded-2xl overflow-hidden divide-y divide-white/5 shadow-xl"
                     x-data="{ activeSection: 0 }">
                    @forelse($lessons as $index => $ls)
                        @php $topics = $ls->topics ?? collect(); @endphp
                        <div>
                            <!-- Accordion Header Button -->
                            <button @click="activeSection = (activeSection === {{ $index }} ? null : {{ $index }})" type="button" class="w-full flex items-center justify-between p-4 text-left hover:bg-white/5 transition group cursor-pointer">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-8 h-8 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center text-xs flex-shrink-0">
                                        <i class="fa-solid fa-book-open"></i>
                                    </div>
                                    <div class="truncate">
                                        <span class="font-bold text-sm text-white group-hover:text-blue-400 transition block truncate">{{ $ls->title }}</span>
                                        <span class="text-[10px] text-gray-400 font-medium">{{ count($topics) }} Topics</span>
                                    </div>
                                </div>
                                
                                <i class="fa-solid fa-chevron-down text-xs text-gray-400 transition-transform duration-300 flex-shrink-0 ml-2"
                                   :class="activeSection === {{ $index }} ? 'rotate-180 text-blue-400' : ''"></i>
                            </button>

                            <!-- Collapsible Topics Body -->
                            <div x-show="activeSection === {{ $index }}" x-transition.opacity class="p-3 pt-2 space-y-1 bg-black/50 border-t border-white/5">
                                @forelse($topics as $tIndex => $topic)
                                    <div class="topic-item cursor-pointer px-3.5 py-2.5 rounded-xl text-xs font-semibold text-gray-400 hover:text-white hover:bg-white/5 border border-transparent transition flex items-center gap-2.5" 
                                         data-bunny-guid="{{ $topic->bunny_guid }}"
                                         data-description="{{ $topic->description }}"
                                         data-topic-id="{{ $topic->id }}">
                                        <input type="checkbox" class="topic-check cursor-pointer" style="cursor: pointer;">
                                        <span class="truncate">{{ $topic->title }}</span>
                                    </div>
                                @empty
                                    <div class="text-xs text-gray-500 italic px-2 py-1">No topics available</div>
                                @endforelse
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500 text-xs">No lessons available yet.</div>
                    @endforelse
                </div>
            </div>
        </aside>
    </div>


<script>
function toggleSidebar() {
    const sb = document.querySelector('.sidebar');
    if(!sb) return;
    const isActive = sb.classList.toggle('active');
    // body class for layout state
    document.body.classList.toggle('sidebar-open', isActive);
    // backdrop (created in DOMContentLoaded)
    const bd = document.getElementById('sidebar-backdrop');
    if(bd) bd.classList.toggle('visible', isActive);
    // prevent background scroll when sidebar open on small screens
    if(isActive) document.body.style.overflow = 'hidden'; else document.body.style.overflow = '';
}

function closeSidebar(){
    const sb = document.querySelector('.sidebar');
    if(!sb) return;
    sb.classList.remove('active');
    const bd = document.getElementById('sidebar-backdrop'); if(bd) bd.classList.remove('visible');
    document.body.classList.remove('sidebar-open');
    document.body.style.overflow = '';
}

// Update video, title, description saat klik topik
// --- HLS / HTML5 player + progress tracking (Bunny CDN) ---
// load hls.js dynamically
(function loadHlsScript(){
    if(window.Hls) return;
    const s = document.createElement('script');
    s.src = 'https://cdn.jsdelivr.net/npm/hls.js@latest';
    s.async = true;
    document.head.appendChild(s);
})();

let player = null; // for YT player or placeholder
let hlsInstance = null; // for hls.js instance
let currentTopicId = null;
let progressTimer = null;
let lastProgressSentAt = 0;
const completionPostedTopics = new Set();

function isYouTubeUrl(url){ return /youtu\.be\/|youtube\.com\/.+v=/.test(url || ''); }

function loadTopicVideo(url, topicId, title, description){
    currentTopicId = topicId;
    document.getElementById('video-title').textContent = title;
    document.getElementById('video-description').textContent = description;
    const placeholder = document.getElementById('video-placeholder');
    // clear prior attributes
    placeholder.removeAttribute('data-video-id');
    placeholder.removeAttribute('data-stream-url');
    placeholder.setAttribute('data-topic-id', topicId || '');

    if(!url) return;

    if(isYouTubeUrl(url)){
        // extract YouTube id for backward compatibility
        const m = url.match(/(youtu\.be\/|v=)([A-Za-z0-9_-]{11})/);
        const videoId = m ? m[2] : null;
        if(videoId){
            const thumb = `https://img.youtube.com/vi/${videoId}/hqdefault.jpg`;
            placeholder.style.backgroundImage = `url(${thumb})`;
            placeholder.setAttribute('data-video-id', videoId);
        }
    } else {
        // assume Bunny / HLS or MP4 full URL or path
        placeholder.style.backgroundImage = '';
        placeholder.setAttribute('data-stream-url', url);
    }

    // clear existing progress timer
    if(progressTimer){ clearInterval(progressTimer); progressTimer = null; }
}

function getCurrentPlaybackSeconds(){
    const html5 = document.getElementById('html5-player');
    if (html5 && Number.isFinite(html5.currentTime)) {
        return Math.max(0, Math.floor(html5.currentTime));
    }
    try {
        if (player && typeof player.getCurrentTime === 'function') {
            return Math.max(0, Math.floor(player.getCurrentTime() || 0));
        }
    } catch (e) {}
    return 0;
}

function getCurrentPlaybackDuration(){
    const html5 = document.getElementById('html5-player');
    if (html5 && Number.isFinite(html5.duration) && html5.duration > 0) {
        return Math.floor(html5.duration);
    }
    return 0;
}

function setTopicCompletedUI(topicId, completed){
    if(!topicId) return;
    const el = document.querySelector('.topic-item[data-topic-id="' + topicId + '"]');
    if(!el) return;
    el.classList.toggle('completed', !!completed);
    const checkbox = el.querySelector('.topic-check');
    if(checkbox) checkbox.checked = !!completed;
    if(completed) completionPostedTopics.add(String(topicId));
    else completionPostedTopics.delete(String(topicId));
}

function reportProgress(markComplete = false, targetTopicId = null){
    const topicIdToReport = String(targetTopicId || currentTopicId || '');
    if(!topicIdToReport) return;

    if(markComplete && completionPostedTopics.has(topicIdToReport)) return;

    const now = Date.now();
    if(!markComplete && now - lastProgressSentAt < 5000) return;
    if(!markComplete) lastProgressSentAt = now;

    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    fetch('/api/topics/' + topicIdToReport + '/progress', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrf,
        },
        body: JSON.stringify({
            watched_seconds: getCurrentPlaybackSeconds(),
            duration_seconds: getCurrentPlaybackDuration(),
            completed: !!markComplete,
        })
    }).then(async (res) => {
        if(!res.ok) return;
        const data = await res.json();
        setTopicCompletedUI(topicIdToReport, !!data.completed);
    }).catch(() => {});
}

function maybeCompleteByThreshold(videoEl, targetTopicId = null){
    const topicIdToReport = targetTopicId || currentTopicId;
    if(!videoEl || !topicIdToReport) return;
    const duration = Number(videoEl.duration || 0);
    const current = Number(videoEl.currentTime || 0);
    if(!Number.isFinite(duration) || duration < 3 || current < 3) return;

    const threshold = duration * 0.80;
    if(current >= threshold || (duration - current) <= 5){
        reportProgress(true, topicIdToReport);
    }
}


function fetchTopicProgress(topicId){
    if(!topicId) return;
    fetch('/api/topics/' + topicId + '/progress', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    }).then(async (res) => {
        if(!res.ok) return;
        const data = await res.json();
        setTopicCompletedUI(topicId, !!data.completed);
    }).catch(() => {});
}

function onPlayerStateChange(event){
    if(!window.YT || !event) return;
    const topicIdForPlayer = currentTopicId;
    if(event.data === YT.PlayerState.PLAYING){
        if(progressTimer) clearInterval(progressTimer);
        progressTimer = setInterval(function(){
            reportProgress(false, topicIdForPlayer);
            try {
                if (player && typeof player.getCurrentTime === 'function' && typeof player.getDuration === 'function') {
                    const dur = player.getDuration();
                    const cur = player.getCurrentTime();
                    if (dur > 3 && (cur / dur >= 0.80 || (dur - cur) <= 5)) {
                        reportProgress(true, topicIdForPlayer);
                    }
                }
            } catch(e){}
        }, 3000);
    } else if(event.data === YT.PlayerState.PAUSED){
        if(progressTimer){ clearInterval(progressTimer); progressTimer = null; }
        reportProgress(false, topicIdForPlayer);
    } else if(event.data === YT.PlayerState.ENDED){
        if(progressTimer){ clearInterval(progressTimer); progressTimer = null; }
        reportProgress(true, topicIdForPlayer);
    }
}

function createHtml5PlayerAndPlay(streamUrl, topicId){
    // ensure container
    const container = document.getElementById('player');
    if(!container) return;
    if(topicId) currentTopicId = String(topicId);
    // remove any previous html5 player
    let v = document.getElementById('html5-player');
    if(v){ try{ v.pause(); }catch(e){} v.remove(); }
    // destroy hls instance
    if(window._hlsInstance){ try{ window._hlsInstance.destroy(); }catch(e){} window._hlsInstance = null; }

    v = document.createElement('video');
    v.id = 'html5-player'; v.controls = true; v.setAttribute('playsinline','');
    // position the video absolutely so it sits above the thumbnail placeholder
    v.style.position = 'absolute'; v.style.top = '0'; v.style.left = '0'; v.style.width = '100%'; v.style.height = '100%'; v.style.zIndex = '50'; v.style.backgroundColor = '#000';
    container.appendChild(v);
    // hide the placeholder so the video element is visible
    try{ const ph = document.getElementById('video-placeholder'); if(ph) { ph.style.setProperty('display', 'none', 'important'); ph.style.opacity = '0'; } }catch(e){}

    const setupQualitySelector = (hls) => {
        const select = document.getElementById('quality-select');
        if(!select || !hls || !hls.levels || hls.levels.length === 0) return;
        
        select.innerHTML = '<option value="-1" class="bg-zinc-900 text-white">Auto (Adaptive)</option>';
        
        const levelItems = hls.levels.map((lvl, idx) => ({
            index: idx,
            height: lvl.height || (lvl.attrs && lvl.attrs.RESOLUTION ? parseInt(lvl.attrs.RESOLUTION.split('x')[1]) : 0),
            bitrate: lvl.bitrate || 0
        })).sort((a, b) => b.height - a.height);

        levelItems.forEach(item => {
            const label = item.height > 0 ? (item.height + 'p' + (item.height >= 720 ? ' HD' : '')) : ('Quality ' + (item.index + 1));
            const opt = document.createElement('option');
            opt.value = item.index;
            opt.className = 'bg-zinc-900 text-white';
            opt.textContent = label;
            select.appendChild(opt);
        });

        select.onchange = function(){
            const val = parseInt(this.value, 10);
            hls.currentLevel = val;
            try { localStorage.setItem('user_preferred_quality', val); } catch(e){}
        };

        try {
            const saved = localStorage.getItem('user_preferred_quality');
            if(saved !== null){
                const val = parseInt(saved, 10);
                if(val === -1 || (val >= 0 && val < hls.levels.length)){
                    select.value = val;
                    hls.currentLevel = val;
                }
            }
        } catch(e){}
    };

    // attach HLS if available; if hls.js not yet loaded, load it dynamically then retry
    const attachAndPlay = () => {
        if(window.Hls && Hls.isSupported()){
            const hls = new Hls(); window._hlsInstance = hls; hls.loadSource(streamUrl); hls.attachMedia(v);
            hls.on(Hls.Events.MANIFEST_PARSED, function(){
                setupQualitySelector(hls);
            });
        } else {
            // native HLS (iOS) or MP4
            v.src = streamUrl;
        }
    };


    if(!window.Hls){
        // try to load hls.js from CDN, then attach
        const s = document.createElement('script');
        s.src = 'https://cdn.jsdelivr.net/npm/hls.js@latest';
        s.async = true;
        s.onload = () => { try{ attachAndPlay(); }catch(e){ console.error('hls attach failed', e); } };
        s.onerror = () => { console.warn('Failed to load hls.js, falling back to native playback'); attachAndPlay(); };
        document.head.appendChild(s);
    } else {
        attachAndPlay();
    }

    // wire events for progress reporting
    v.addEventListener('play', function(){
        const sp = document.getElementById('ajax-spinner'); if(sp) sp.classList.remove('show');
        if(progressTimer) clearInterval(progressTimer);
        progressTimer = setInterval(function(){ reportProgress(false); }, 15000);
    });
    v.addEventListener('pause', function(){
        if(progressTimer){ clearInterval(progressTimer); progressTimer = null; }
        reportProgress(false);
    });
    v.addEventListener('timeupdate', function(){
        maybeCompleteByThreshold(v);
    });
    v.addEventListener('seeked', function(){
        maybeCompleteByThreshold(v);
    });
    v.addEventListener('ended', function(){
        if(progressTimer){ clearInterval(progressTimer); progressTimer = null; }
        reportProgress(true);
    });
}

function destroyHtml5Player(){
    const v = document.getElementById('html5-player'); if(v){ try{ v.pause(); }catch(e){} v.remove(); }
    if(window._hlsInstance){ try{ window._hlsInstance.destroy(); }catch(e){} window._hlsInstance = null; }
    if(progressTimer){ clearInterval(progressTimer); progressTimer = null; }
    // restore placeholder visibility when player is destroyed
    try{ const ph = document.getElementById('video-placeholder'); if(ph) ph.style.display = 'flex'; }catch(e){}
}

// SPA-like navigation and page initialization
document.addEventListener('DOMContentLoaded', () => {
    // create a global ajax spinner overlay (hidden by default)
    (function createAjaxSpinner(){
        if(document.getElementById('ajax-spinner')) return;
        const s = document.createElement('div');
        s.id = 'ajax-spinner';
        s.style.display = 'none';
        s.innerHTML = '<div class="spinner-inner"><div class="spinner"></div></div>';
        document.body.appendChild(s);
        // allow CSS transitions via class
    })();

        // create a backdrop for mobile sidebar overlay
        (function createSidebarBackdrop(){
            if(document.getElementById('sidebar-backdrop')) return;
            const b = document.createElement('div');
            b.id = 'sidebar-backdrop';
            b.addEventListener('click', function(){
                // close sidebar when tapping backdrop
                const sb = document.querySelector('.sidebar');
                if(sb && sb.classList.contains('active')) toggleSidebar();
            });
            document.body.appendChild(b);
        })();

    const openLessonsKey = 'kelas_open_lessons';
    // prefetch cache and timers for hover intent
    const prefetchCache = {}; // keyed by contentUrl
    const prefetchTimers = new Map();
    const prefetchControllers = new Map();

    async function prefetchLesson(url){
        try{
            const contentUrl = url.replace(/\/?$/, '') + '/content';
            if(prefetchCache[contentUrl]) return; // already cached
            // avoid duplicate controllers
            if(prefetchControllers.has(contentUrl)) return;
            const ctrl = new AbortController();
            prefetchControllers.set(contentUrl, ctrl);
            const res = await fetch(contentUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, signal: ctrl.signal });
            if(!res.ok){ prefetchControllers.delete(contentUrl); return; }
            const html = await res.text();
            prefetchCache[contentUrl] = html;
            prefetchControllers.delete(contentUrl);
        }catch(e){
            // ignore abort or network errors
            prefetchControllers.delete(url);
        }
    }

    function getOpenLessons(){
        try{ return JSON.parse(localStorage.getItem(openLessonsKey) || '[]'); }catch(e){ return []; }
    }
    function setOpenLessons(arr){ localStorage.setItem(openLessonsKey, JSON.stringify(arr || [])); }

    // force stop player/timers
    function forceStopAll(){
        try{
            const prevTopicId = currentTopicId;
            const html5 = document.getElementById('html5-player');
            if(html5 && prevTopicId) maybeCompleteByThreshold(html5, prevTopicId);
            destroyHtml5Player();
            if(player && typeof player.stopVideo === 'function'){ try{ player.stopVideo(); }catch(e){} }
            if(player && typeof player.destroy === 'function'){ try{ player.destroy(); }catch(e){} }
            player = null;
            currentTopicId = null;
        }catch(e){ console.warn('forceStopAll error', e); }
    }

    // initialize sidebar interactions (toggle/restore open state)
    function initSidebar(){
        let openLessonId = "{{ isset($firstLesson) ? (string)$firstLesson->id : '' }}";
        if (openLessonId) setOpenLessons([openLessonId]);
        
        const lessonBlocks = document.querySelectorAll('.lesson-block');

        function closeOtherLessons(activeBlock){
            lessonBlocks.forEach(otherBlock => {
                if(otherBlock === activeBlock) return;
                const otherTopics = otherBlock.querySelector('.topic-list');
                const otherArrow = otherBlock.querySelector('.lesson-arrow');
                if(otherTopics) otherTopics.style.display = 'none';
                otherBlock.classList.remove('active');
                if(otherArrow) otherArrow.textContent = '▸';
            });
        }

        lessonBlocks.forEach(block => {
            const a = block.querySelector('.lesson-header');
            const arrow = block.querySelector('.lesson-arrow');
            const topics = block.querySelector('.topic-list');
            const href = a.getAttribute('href');
            const lessonId = href ? href.split('/').filter(Boolean).pop() : null;

            // restore open state
            if(lessonId && lessonId === openLessonId){
                if(topics) topics.style.display = 'block';
                block.classList.add('active');
            } else {
                if(topics) topics.style.display = 'none';
                block.classList.remove('active');
            }

            // arrow click toggles expand without navigation
            if(arrow){
                arrow.addEventListener('click', (ev) => {
                    ev.preventDefault(); ev.stopPropagation();
                    const isHidden = window.getComputedStyle(topics).display === 'none';
                    if(isHidden){
                        closeOtherLessons(block);
                        topics.style.display = 'block';
                        block.classList.add('active');
                        
                        setOpenLessons(lessonId ? [lessonId] : []);
                    } else {
                        topics.style.display = 'none';
                        block.classList.remove('active');
                        
                        setOpenLessons([]);
                    }
                });
            }

            // header (anchor) click -> toggle topic list only (do not navigate)
            a.addEventListener('click', function(ev){
                // if click originated from arrow, ignore (arrow handled above)
                if(ev.target.closest('.lesson-arrow')){ return; }
                ev.preventDefault();
                // toggle topics visible state
                const isHidden = window.getComputedStyle(topics).display === 'none';
                if(isHidden){
                    closeOtherLessons(block);
                    topics.style.display = 'block';
                    block.classList.add('active');
                    if(arrow) 
                    setOpenLessons(lessonId ? [lessonId] : []);
                } else {
                    topics.style.display = 'none';
                    block.classList.remove('active');
                    if(arrow) 
                    setOpenLessons([]);
                }
            });

            // hover intent: start a short timer then prefetch partial
            a.addEventListener('mouseenter', function(){
                const url = a.getAttribute('href');
                if(!url) return;
                const t = setTimeout(()=>{
                    prefetchLesson(url);
                    prefetchTimers.delete(a);
                }, 180);
                prefetchTimers.set(a, t);
            });
            a.addEventListener('mouseleave', function(){
                const t = prefetchTimers.get(a);
                if(t){ clearTimeout(t); prefetchTimers.delete(a); }
                // if a prefetch is in-flight, abort it to avoid wasted bandwidth
                const contentUrl = (a.getAttribute('href') || '').replace(/\/?$/, '') + '/content';
                const ctrl = prefetchControllers.get(contentUrl);
                if(ctrl){ try{ ctrl.abort(); }catch(e){} prefetchControllers.delete(contentUrl); }
            });
        });
    }

    // initialize topic handlers and player bindings inside main content
    function initPage(lessonId){
    // topic clicks (sidebar only)
    document.querySelectorAll('.topic-item').forEach(item => {
            item.addEventListener('click', () => {
                const bunnyGuid = item.getAttribute('data-bunny-guid');
                const title = item.textContent.trim();
                const description = item.getAttribute('data-description');
                const topicId = item.getAttribute('data-topic-id');
                // persist last topic for this lesson
                if(lessonId && topicId) localStorage.setItem('kelas_last_topic_' + lessonId, topicId);
                // SPA-style topic navigation (pushState + play)
                // We do not pass a videoUrl; the player will request /topics/{id}/stream to get the signed URL based on bunny_guid
                navigateTopic(lessonId, topicId, null, true);
                // On small screens close the sidebar so the video becomes visible again
                try{ if(window.innerWidth <= 900) closeSidebar(); }catch(e){}
                // selection highlight
                document.querySelectorAll('.topic-item.selected').forEach(s => s.classList.remove('selected'));
                item.classList.add('selected');
            });
        });

        // Allow clicking checkbox directly to toggle completion manually
        document.querySelectorAll('.topic-check').forEach(chk => {
            chk.addEventListener('click', (e) => {
                e.stopPropagation();
                const item = chk.closest('.topic-item');
                if(!item) return;
                const topicId = item.getAttribute('data-topic-id');
                const isChecked = chk.checked;
                setTopicCompletedUI(topicId, isChecked);
                
                const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                fetch('/api/topics/' + topicId + '/progress', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrf,
                    },
                    body: JSON.stringify({
                        completed: isChecked,
                    })
                }).catch(() => {});
            });
        });

        // Load completion markers for topic list
        document.querySelectorAll('.topic-item[data-topic-id]').forEach(item => {
            fetchTopicProgress(item.getAttribute('data-topic-id'));
        });

        // play button behavior
        const customPlay = document.getElementById('custom-play');
        if(customPlay){
            customPlay.addEventListener('click', function(){
                const placeholder = document.getElementById('video-placeholder');
                const ytId = placeholder ? placeholder.getAttribute('data-video-id') : null;
                const streamUrlAttr = placeholder ? placeholder.getAttribute('data-stream-url') : null;
                const topicId = placeholder ? placeholder.getAttribute('data-topic-id') : null;
                if(topicId) currentTopicId = String(topicId);

                // If we have a stream URL attribute already, use it
                if(streamUrlAttr){
                    destroyHtml5Player();
                    createHtml5PlayerAndPlay(streamUrlAttr, topicId);
                    return;
                }

                // If placeholder doesn't have stream URL but topic id exists, ask server for it
                if(topicId){
                    fetch(`/topics/${topicId}/stream`).then(async r=>{
                        try { return await r.json(); } catch(e){ return { url: null, error: 'non-json' }; }
                    }).then(data=>{
                        if(data && data.url){
                            if(data.url.match(/(youtu\.be\/|v=)([A-Za-z0-9_-]{11})/)){
                                const newYtId = data.url.match(/(youtu\.be\/|v=)([A-Za-z0-9_-]{11})/)[2];
                                try{ const ph = document.getElementById('video-placeholder'); if(ph) ph.style.display = 'none'; }catch(e){}
                                if(!player || typeof player.loadVideoById !== 'function'){ 
                                    player = new YT.Player('player', { height:'100%', width:'100%', videoId: newYtId, playerVars:{rel:0,modestbranding:1}, events:{'onStateChange':onPlayerStateChange,'onReady':function(e){ player.playVideo(); }} }); 
                                } else { 
                                    player.loadVideoById(newYtId); 
                                    player.playVideo(); 
                                }
                                return;
                            }
                            placeholder.setAttribute('data-stream-url', data.url);
                            destroyHtml5Player();
                            createHtml5PlayerAndPlay(data.url, topicId);
                            return;
                        }
                        // fallback to YouTube if present
                            if(ytId){
                            // create or load YT player as before (keep legacy support)
                                // hide placeholder so the iframe is visible
                                try{ const ph = document.getElementById('video-placeholder'); if(ph) ph.style.display = 'none'; }catch(e){}
                                if(!player || typeof player.loadVideoById !== 'function'){
                                player = new YT.Player('player', {
                                    height: '100%', width: '100%', videoId: ytId,
                                    playerVars: { rel:0, modestbranding:1 },
                                    events: { 'onStateChange': onPlayerStateChange, 'onReady': function(e){ player.playVideo(); }}
                                });
                            } else { player.loadVideoById(ytId); player.playVideo(); }
                        }
                    }).catch(err => {
                        console.warn('stream lookup failed', err);
                        if(ytId){
                            try{ const ph = document.getElementById('video-placeholder'); if(ph) ph.style.display = 'none'; }catch(e){}
                            if(!player){ player = new YT.Player('player', { height:'100%', width:'100%', videoId: ytId, playerVars:{rel:0,modestbranding:1}, events:{'onStateChange':onPlayerStateChange} }); } else { player.loadVideoById(ytId); }
                        }
                    });
                } else if(ytId){
                    // no topicId but have yt fallback
                    if(!player || typeof player.loadVideoById !== 'function'){
                        player = new YT.Player('player', { height:'100%', width:'100%', videoId: ytId, playerVars:{rel:0,modestbranding:1}, events:{'onStateChange':onPlayerStateChange} });
                    } else { player.loadVideoById(ytId); }
                }
            });
        }

        // Check URL parameter ?topic=... first for direct Resume link navigation
        let topicSelected = false;
        try {
            const urlParams = new URLSearchParams(window.location.search);
            const targetTopicId = urlParams.get('topic');
            if (targetTopicId) {
                const targetEl = document.querySelector('.topic-item[data-topic-id="' + targetTopicId + '"]');
                if (targetEl) {
                    targetEl.click();
                    topicSelected = true;
                }
            }
        } catch(e) {}

        // restore last topic selection for this lesson if exists
        if (!topicSelected) {
            try {
                if (lessonId) {
                    const last = localStorage.getItem('kelas_last_topic_' + lessonId);
                    if (last) {
                        const el = document.querySelector('[data-topic-id="' + last + '"]');
                        if (el) { el.click(); topicSelected = true; }
                    }
                }
            } catch(e) {}
        }

        // otherwise auto-click first topic if present
        if (!topicSelected) {
            const first = document.querySelector('.topic-item[data-topic-id]');
            if (first) first.click();
        }


        // wire next/back buttons (if present in the partial)
        const btnNext = document.getElementById('btn-next');
        const btnPrev = document.getElementById('btn-prev');
        function updateNavButtons(){
            const visibleTopics = Array.from(document.querySelectorAll('.topic-item[data-topic-id]'));
            const sel = document.querySelector('.topic-item.selected');
            const idx = sel ? visibleTopics.indexOf(sel) : -1;
            if(btnPrev) btnPrev.disabled = (idx <= 0);
            if(btnNext) btnNext.disabled = (idx < 0 || idx >= visibleTopics.length - 1);
        }

        if(btnNext){
            btnNext.addEventListener('click', function(){
                console.debug('btnNext clicked');
                const topics = Array.from(document.querySelectorAll('.topic-item[data-topic-id]'));
                let sel = document.querySelector('.topic-item.selected');
                let idx = sel ? topics.indexOf(sel) : -1;
                // fallback: if nothing selected, select first topic first
                if(idx === -1 && topics.length){
                    const firstTopic = topics[0];
                    if(firstTopic){ firstTopic.click(); sel = firstTopic; idx = 0; }
                }
                if(idx >= 0 && idx < topics.length - 1){
                    const nxt = topics[idx+1];
                    if(nxt){ nxt.click(); }
                    // ensure navigation behavior triggers
                    const lessonIdLocal = lessonId || (location.pathname.split('/').filter(Boolean)[1] || null);
                    navigateTopic(lessonIdLocal, nxt.getAttribute('data-topic-id'), null, true);
                    setTimeout(updateNavButtons, 50);
                }
            });
        }
        if(btnPrev){
            btnPrev.addEventListener('click', function(){
                console.debug('btnPrev clicked');
                const topics = Array.from(document.querySelectorAll('.topic-item[data-topic-id]'));
                let sel = document.querySelector('.topic-item.selected');
                let idx = sel ? topics.indexOf(sel) : -1;
                // if nothing selected, select first and do nothing else
                if(idx === -1 && topics.length){
                    const firstTopic = topics[0];
                    if(firstTopic){ firstTopic.click(); sel = firstTopic; idx = 0; }
                }
                if(idx > 0){
                    const prev = topics[idx-1];
                    if(prev){ prev.click(); }
                    const lessonIdLocal = lessonId || (location.pathname.split('/').filter(Boolean)[1] || null);
                    navigateTopic(lessonIdLocal, prev.getAttribute('data-topic-id'), null, true);
                    setTimeout(updateNavButtons, 50);
                }
            });
        }

        // update buttons whenever topics change or selection changes
        document.addEventListener('click', function(ev){ if(ev.target.closest('.topic-item')) setTimeout(updateNavButtons,30); });
        updateNavButtons();

        // ripple effect for nav buttons
        document.querySelectorAll('.video-nav-btn').forEach(btn => {
            btn.addEventListener('click', function(e){
                // create ripple
                const r = document.createElement('span');
                r.className = 'ripple';
                const rect = btn.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                r.style.width = r.style.height = size + 'px';
                r.style.left = (e.clientX - rect.left - size/2) + 'px';
                r.style.top = (e.clientY - rect.top - size/2) + 'px';
                btn.appendChild(r);
                setTimeout(()=>{ try{ r.remove(); }catch(e){} }, 700);
            });
        });
    }

    // fetch content for a lesson and swap into main content area
    async function navigateTo(url, pushState=true){
        try{
            const spinner = document.getElementById('ajax-spinner');
            if(spinner){ spinner.style.display = 'flex'; setTimeout(()=>spinner.classList.add('show'), 10); }
            const main = document.querySelector('main.content');
            if(main){ main.classList.remove('fade-in'); main.classList.add('fade-out'); }
            const contentUrl = url.replace(/\/?$/, '') + '/content';
            let html;
            if(prefetchCache[contentUrl]){
                html = prefetchCache[contentUrl];
            } else {
                const res = await fetch(contentUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                if(!res.ok) { if(spinner){ spinner.classList.remove('show'); setTimeout(()=>spinner.style.display='none',200); } window.location.href = url; return; }
                html = await res.text();
            }
            // wait for fade out transition (250ms)
            await new Promise(r => setTimeout(r, 260));
            document.querySelector('main.content').innerHTML = html;
            // extract lesson id from url
            const lessonId = url.split('/').filter(Boolean).pop();
            if(pushState) history.pushState({ ajax: true, url: url }, '', url);
            // re-init page scripts for newly loaded content
            initPage(lessonId);
            // fade in and hide spinner
            const m = document.querySelector('main.content'); if(m){ m.classList.remove('fade-out'); m.classList.add('fade-in'); }
            if(spinner){ spinner.classList.remove('show'); setTimeout(()=>spinner.style.display='none',220); }
        }catch(e){ console.error('navigate error', e); window.location.href = url; }
    }

    // handle back/forward
    window.addEventListener('popstate', function(ev){
        const path = location.pathname + location.search + location.hash;
        // handle lesson content swap
        if(path.startsWith('/kelas')){
            forceStopAll();
            // if URL contains topic query or hash, attempt to parse and play topic
            const params = new URLSearchParams(location.search);
            const topicParam = params.get('topic') || (location.hash ? location.hash.replace('#','') : null);
            navigateTo(location.pathname, false).then(()=>{
                if(topicParam){
                    const el = document.querySelector('[data-topic-id="'+topicParam+'"]');
                    if(el) el.click();
                }
            });
        }
    });

    // navigate between topics without full reload
    function navigateTopic(lessonId, topicId, videoUrl, pushState=true){
        try{
            forceStopAll();
            if(topicId) currentTopicId = String(topicId);
            const spinner = document.getElementById('ajax-spinner'); if(spinner){ spinner.style.display='flex'; setTimeout(()=>spinner.classList.add('show'),10); }
            // update placeholder and start playback after creating player
            const placeholder = document.getElementById('video-placeholder');
            if(placeholder){
                // Always set current topic id for the placeholder so the player can request stream metadata
                if(topicId) placeholder.setAttribute('data-topic-id', topicId);

                // If this is a YouTube link, set video-id and thumbnail
                const vid = (videoUrl && videoUrl.match(/(youtu\.be\/|v=)([A-Za-z0-9_-]{11})/)) ? videoUrl.match(/(youtu\.be\/|v=)([A-Za-z0-9_-]{11})/)[2] : null;
                if(vid){
                    placeholder.style.backgroundImage = 'url(https://img.youtube.com/vi/'+vid+'/hqdefault.jpg)';
                    placeholder.setAttribute('data-video-id', vid);
                    // clear any existing stream-url/bunny-guid since this is YouTube
                    placeholder.removeAttribute('data-stream-url');
                    placeholder.removeAttribute('data-bunny-guid');
                } else {
                    // For non-YouTube topics, set bunny GUID from sidebar item if available
                    try{
                        const topicEl = document.querySelector('[data-topic-id="' + topicId + '"]');
                        if(topicEl){
                            const bg = topicEl.getAttribute('data-bunny-guid');
                            if(bg) {
                                placeholder.setAttribute('data-bunny-guid', bg);
                            } else {
                                placeholder.removeAttribute('data-bunny-guid');
                            }
                        }
                        // clear video-id (youtube) if present
                        placeholder.removeAttribute('data-video-id');
                        // also clear any previously cached stream-url so it will be fetched fresh
                        placeholder.removeAttribute('data-stream-url');
                    }catch(e){}
                }
            }
            // update displayed title/description using sidebar item text if available
            try{
                const topicEl = document.querySelector('[data-topic-id="' + topicId + '"]');
                const titleEl = document.getElementById('video-title');
                const descEl = document.getElementById('video-description');
                if(topicEl){
                    if(titleEl) titleEl.textContent = topicEl.textContent.trim();
                    if(descEl) descEl.textContent = topicEl.getAttribute('data-description') || '';
                }
            }catch(e){ /* ignore */ }
            // push topic into URL as query param (keeps lesson path)
            if(lessonId && String(lessonId) !== 'null'){
                const newUrl = '/kelas/' + lessonId + (topicId ? '?topic=' + topicId : '');
                if(pushState) history.pushState({ ajax: true, url: newUrl }, '', newUrl);
            }
            // create player if needed and play
            if(player){
                player.loadVideoById(placeholder.getAttribute('data-video-id'));
                currentTopicId = topicId;
                player.playVideo();
                // spinner will be hidden on PlayerStateChange when PLAYING
            } else {
                // trigger the custom-play button's click to create player
                const customPlay = document.getElementById('custom-play');
                if(customPlay) customPlay.click();
                // spinner will be hidden once player fires onReady/onStateChange
            }
        }catch(e){ console.error('navigateTopic error', e); }
    }

    // initial setup
    initSidebar();
    // initialize page for current lesson (try to extract lesson id from url)
    const pathParts = location.pathname.split('/').filter(Boolean);
    // Try URL first (/kelas/{id}), then Blade variable, then DOM fallback
    let currentLessonId = (pathParts.length && pathParts[0] === 'kelas' && pathParts[1] && pathParts[1] !== 'null') ? pathParts[1] : null;
    if(!currentLessonId){
        // use server-rendered lesson id as reliable fallback
        currentLessonId = "{{ isset($lesson) && $lesson ? $lesson->id : '' }}" || null;
    }
    if(!currentLessonId){
        // last resort: try first sidebar lesson block
        try{
            const firstBlock = document.querySelector('.lesson-block .lesson-header');
            if(firstBlock){
                const href = firstBlock.getAttribute('href') || '';
                const parts = href.split('/').filter(Boolean);
                currentLessonId = parts.length ? parts[parts.length - 1] : null;
            }
        }catch(e){}
    }
    initPage(currentLessonId);

    // close overlay sidebar automatically when resizing to desktop
    window.addEventListener('resize', function(){
        try{
            if(window.innerWidth > 900){
                const sb = document.querySelector('.sidebar'); if(sb) sb.classList.remove('active');
                const bd = document.getElementById('sidebar-backdrop'); if(bd) bd.classList.remove('visible');
                document.body.classList.remove('sidebar-open');
                document.body.style.overflow = '';
            }
        }catch(e){}
    });

    window.addEventListener('pagehide', function(){
        try{
            const html5 = document.getElementById('html5-player');
            if(html5) maybeCompleteByThreshold(html5);
            reportProgress(false);
        }catch(e){}
    });

    const lmsThemeToggle = document.getElementById('lms-theme-toggle');
    const lmsThemeLabel = document.getElementById('lms-theme-toggle-label');
    const lmsThemeIcon = document.getElementById('lms-theme-toggle-icon');

    function updateLmsThemeToggle() {
        const theme = document.documentElement.getAttribute('data-theme') || 'dark';
        if (lmsThemeLabel) {
            lmsThemeLabel.textContent = theme === 'light' ? 'Dark' : 'Light';
        }
        if (lmsThemeIcon) {
            lmsThemeIcon.textContent = theme === 'light' ? '🌙' : '☀';
        }
    }

    if (lmsThemeToggle) {
        lmsThemeToggle.addEventListener('click', function () {
            const nextTheme = (document.documentElement.getAttribute('data-theme') === 'light') ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', nextTheme);
            document.cookie = 'theme=' + nextTheme + '; path=/; max-age=' + (60 * 60 * 24 * 365);
            updateLmsThemeToggle();
        });
    }

    updateLmsThemeToggle();
});
</script>
@endsection

