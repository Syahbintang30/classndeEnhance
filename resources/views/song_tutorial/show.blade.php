@extends('layouts.app')

@section('title', 'Song Tutorial')

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
        .tw-dash {
            background-color: #08080a !important;
            color: #f3f4f6 !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
        }
        .tw-dash .font-display {
            font-family: 'Bebas Neue', cursive;
            letter-spacing: 1px;
        }
        
        body > nav { display: none !important; }

        .tw-dash ::-webkit-scrollbar { width: 6px; }
        .tw-dash ::-webkit-scrollbar-track { background: #0d0d12; }
        .tw-dash ::-webkit-scrollbar-thumb { background: #222232; border-radius: 3px; }
        .tw-dash ::-webkit-scrollbar-thumb:hover { background: #3b82f6; }

        .tw-dash a { text-decoration: none; }
        .tw-dash *:focus { outline: none !important; }

        .sidebar { transition: all 0.3s ease; }
        
        .lesson-block.active .lesson-header {
            background: rgba(255, 255, 255, 0.05);
            color: #ffffff;
        }
        .lesson-block.active .lesson-arrow {
            transform: rotate(90deg);
        }
        .topic-list { display: none; }
        .topic-list.active { display: block; }
        
        .topic-item.completed .topic-box { color: #60a5fa; }
        .topic-item.selected .topic-box {
            background: rgba(59, 130, 246, 0.1);
            border-left-color: #3b82f6;
            color: #ffffff;
        }
    </style>
@endpush

@section('content')
<div class="tw-dash min-h-screen flex flex-col antialiased bg-black text-gray-200"
     x-data="{ mobileMenuOpen: false }">

    {{-- ─── TOP NAVIGATION BAR ──────────────────────────────────────────── --}}
    @include('layouts.lms_header')

    <!-- MAIN LESSONS CONTAINER -->
    <div class="flex-1 flex flex-col md:flex-row w-full max-w-screen-2xl mx-auto overflow-hidden bg-black relative">
        
        <!-- Sidebar Navigation for Modules/Topics -->
        <aside class="sidebar w-full md:w-80 flex-shrink-0 bg-zinc-900/40 border-r border-zinc-800/80 overflow-y-auto absolute md:relative z-20 inset-y-0 transform -translate-x-full md:translate-x-0">
            <!-- Mobile Close Button -->
            <button onclick="toggleSidebar()" class="md:hidden absolute top-4 right-4 text-gray-400 hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
            
            <div class="p-4 border-b border-zinc-800/80">
                <h3 class="font-display text-xl text-white tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-music text-blue-500"></i> Song Library
                </h3>
            </div>
            
            <ul class="menu p-0 m-0 list-none">
            @forelse($lessons as $ls)
                <li class="lesson-block border-b border-zinc-800/50 last:border-b-0">
                    <a href="{{ route('song.tutorial.show', $ls->id) }}" class="lesson-header flex items-center justify-between p-4 cursor-pointer text-gray-300 hover:bg-zinc-800/50 transition">
                        <div class="lesson-left flex items-center gap-3 font-semibold text-sm">
                            <div class="w-8 h-8 rounded-lg bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center">
                                <i class="fa-solid fa-music text-xs"></i>
                            </div>
                            {{ $ls->title }}
                        </div>
                        <span class="lesson-arrow text-gray-500 text-lg transition-transform duration-200">
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                        </span>
                    </a>
                    @php $topics = $ls->topics ?? collect(); @endphp
                    <ul class="topic-list bg-black/40">
                        @forelse($topics as $topic)
                            <li class="topic-item" 
                                data-bunny-guid="{{ $topic->bunny_guid }}"
                                data-description="{{ $topic->description }}"
                                data-topic-id="{{ $topic->id }}">
                                <div class="topic-box flex items-center gap-3 py-3 pr-4 pl-12 text-sm text-gray-400 cursor-pointer border-l-2 border-transparent transition hover:bg-zinc-800/30">
                                    <span class="truncate">{{ $topic->title }}</span>
                                </div>
                            </li>
                        @empty
                        @endforelse
                    </ul>
                </li>
            @empty
            @endforelse
        </ul>
    </aside>

    <!-- Main Content Area -->
    <div class="main-wrapper flex-1 relative overflow-y-auto w-full">
        <main class="content p-4 md:p-8 max-w-5xl mx-auto w-full flex flex-col items-center">
            @php $firstLesson = $lesson; @endphp
            @include('kelas._lesson_content', ['lesson' => $firstLesson])
        </main>
    </div>
</div>

<script>
function toggleSidebar() {
    const sb = document.querySelector('.sidebar');
    if(!sb) return;
    const isActive = sb.classList.toggle('active');
    document.body.classList.toggle('sidebar-open', isActive);
    const bd = document.getElementById('sidebar-backdrop');
    if(bd) bd.classList.toggle('visible', isActive);
    if(isActive) document.body.style.overflow = 'hidden'; else document.body.style.overflow = '';
}

function closeSidebar(){
    const sb = document.querySelector('.sidebar'); if(!sb) return;
    sb.classList.remove('active');
    const bd = document.getElementById('sidebar-backdrop'); if(bd) bd.classList.remove('visible');
    document.body.classList.remove('sidebar-open');
    document.body.style.overflow = '';
}

(function loadHlsScript(){
    if(window.Hls) return;
    const s = document.createElement('script');
    s.src = 'https://cdn.jsdelivr.net/npm/hls.js@latest';
    s.async = true;
    document.head.appendChild(s);
})();

let player = null;
let currentTopicId = null;
let progressTimer = null;
let lastProgressSentAt = 0;
const completionPostedTopics = new Set();

function isYouTubeUrl(url){ return /youtu\.be\/|youtube\.com\/.+v=/.test(url || ''); }

function getCurrentPlaybackSeconds(){
    const html5 = document.getElementById('html5-player');
    if (html5 && Number.isFinite(html5.currentTime)) return Math.max(0, Math.floor(html5.currentTime));
    try { if (player && typeof player.getCurrentTime === 'function') return Math.max(0, Math.floor(player.getCurrentTime() || 0)); } catch (e) {}
    return 0;
}

function getCurrentPlaybackDuration(){
    const html5 = document.getElementById('html5-player');
    if (html5 && Number.isFinite(html5.duration) && html5.duration > 0) return Math.floor(html5.duration);
    return 0;
}

function setTopicCompletedUI(topicId, completed){
    if(!topicId) return;
    const el = document.querySelector('.topic-item[data-topic-id="' + topicId + '"]');
    if(!el) return;
    el.classList.toggle('completed', !!completed);
    if(completed) completionPostedTopics.add(String(topicId));
}

function reportProgress(markComplete = false){
    if(!currentTopicId) return;
    const topicKey = String(currentTopicId);
    if(markComplete && completionPostedTopics.has(topicKey)) return;
    const now = Date.now();
    if(!markComplete && now - lastProgressSentAt < 5000) return;
    if(!markComplete) lastProgressSentAt = now;
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    fetch('/api/topics/' + currentTopicId + '/progress', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrf },
        body: JSON.stringify({ watched_seconds: getCurrentPlaybackSeconds(), duration_seconds: getCurrentPlaybackDuration(), completed: !!markComplete })
    }).then(async (res) => {
        if(!res.ok) return;
        const data = await res.json();
        setTopicCompletedUI(currentTopicId, !!data.completed);
    }).catch(() => {});
}

function maybeCompleteByThreshold(videoEl){
    if(!videoEl || !currentTopicId) return;
    const duration = Number(videoEl.duration || 0);
    const current = Number(videoEl.currentTime || 0);
    if(!Number.isFinite(duration) || duration <= 0) return;
    if(current >= Math.max(1, duration * 0.95)) reportProgress(true);
}

function fetchTopicProgress(topicId){
    if(!topicId) return;
    fetch('/api/topics/' + topicId + '/progress', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(async (res) => { if(!res.ok) return; const data = await res.json(); setTopicCompletedUI(topicId, !!data.completed); })
    .catch(() => {});
}

function onPlayerStateChange(event){
    if(!window.YT || !event) return;
    if(event.data === YT.PlayerState.PLAYING){ if(progressTimer) clearInterval(progressTimer); progressTimer = setInterval(function(){ reportProgress(false); }, 15000); }
    else if(event.data === YT.PlayerState.PAUSED){ if(progressTimer){ clearInterval(progressTimer); progressTimer = null; } reportProgress(false); }
    else if(event.data === YT.PlayerState.ENDED){ if(progressTimer){ clearInterval(progressTimer); progressTimer = null; } reportProgress(true); }
}

function createHtml5PlayerAndPlay(streamUrl, topicId){
    const container = document.getElementById('player'); if(!container) return;
    if(topicId) currentTopicId = String(topicId);
    let v = document.getElementById('html5-player');
    if(v){ try{ v.pause(); }catch(e){} v.remove(); }
    if(window._hlsInstance){ try{ window._hlsInstance.destroy(); }catch(e){} window._hlsInstance = null; }
    v = document.createElement('video');
    v.id = 'html5-player'; v.controls = true; v.setAttribute('playsinline','');
    v.style.position = 'absolute'; v.style.top = '0'; v.style.left = '0'; v.style.width = '100%'; v.style.height = '100%'; v.style.zIndex = '50'; v.style.backgroundColor = '#000';
    container.appendChild(v);
    
    // hide the placeholder so the video element is visible
    try{ const ph = document.getElementById('video-placeholder'); if(ph) { ph.style.setProperty('display', 'none', 'important'); ph.style.opacity = '0'; } }catch(e){}
    const attachAndPlay = () => {
        if(window.Hls && Hls.isSupported()){ 
            const hls = new Hls(); window._hlsInstance = hls; hls.loadSource(streamUrl); hls.attachMedia(v); 
            hls.on(Hls.Events.MANIFEST_PARSED, function(){
                v.play().catch(e => console.warn('Autoplay prevented:', e));
            });
        }
        else { 
            v.src = streamUrl; 
            v.play().catch(e => console.warn('Autoplay prevented:', e));
        }
    };
    if(!window.Hls){ const s = document.createElement('script'); s.src = 'https://cdn.jsdelivr.net/npm/hls.js@latest'; s.async = true; s.onload = () => { try{ attachAndPlay(); }catch(e){} }; s.onerror = () => { attachAndPlay(); }; document.head.appendChild(s); } else { attachAndPlay(); }
    v.addEventListener('play', function(){ if(progressTimer) clearInterval(progressTimer); progressTimer = setInterval(function(){ reportProgress(false); }, 15000); });
    v.addEventListener('pause', function(){ if(progressTimer){ clearInterval(progressTimer); progressTimer = null; } reportProgress(false); });
    v.addEventListener('timeupdate', function(){ maybeCompleteByThreshold(v); });
    v.addEventListener('seeked', function(){ maybeCompleteByThreshold(v); });
    v.addEventListener('ended', function(){ if(progressTimer){ clearInterval(progressTimer); progressTimer = null; } reportProgress(true); });
}

function destroyHtml5Player(){
    const v = document.getElementById('html5-player'); if(v){ try{ v.pause(); }catch(e){} v.remove(); }
    if(window._hlsInstance){ try{ window._hlsInstance.destroy(); }catch(e){} window._hlsInstance = null; }
    if(progressTimer){ clearInterval(progressTimer); progressTimer = null; }
    try{ const ph = document.getElementById('video-placeholder'); if(ph) ph.style.display = 'flex'; }catch(e){}
}

document.addEventListener('DOMContentLoaded', () => {
    (function createAjaxSpinner(){
        if(document.getElementById('ajax-spinner')) return;
        const s = document.createElement('div'); s.id = 'ajax-spinner'; s.style.display = 'none';
        s.innerHTML = '<div class="spinner-inner"><div class="spinner"></div></div>';
        document.body.appendChild(s);
    })();

    (function createSidebarBackdrop(){
        if(document.getElementById('sidebar-backdrop')) return;
        const b = document.createElement('div'); b.id = 'sidebar-backdrop';
        b.addEventListener('click', function(){ const sb = document.querySelector('.sidebar'); if(sb && sb.classList.contains('active')) toggleSidebar(); });
        document.body.appendChild(b);
    })();

    const openLessonsKey = 'song_open_lessons';
    const prefetchCache = {};
    const prefetchTimers = new Map();
    const prefetchControllers = new Map();

    async function prefetchLesson(url){
        try{
            const contentUrl = url.replace(/\/?$/, '') + '/content';
            if(prefetchCache[contentUrl]) return;
            if(prefetchControllers.has(contentUrl)) return;
            const ctrl = new AbortController(); prefetchControllers.set(contentUrl, ctrl);
            const res = await fetch(contentUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, signal: ctrl.signal });
            if(!res.ok){ prefetchControllers.delete(contentUrl); return; }
            prefetchCache[contentUrl] = await res.text();
            prefetchControllers.delete(contentUrl);
        }catch(e){ prefetchControllers.delete(url); }
    }

    function getOpenLessons(){ try{ return JSON.parse(localStorage.getItem(openLessonsKey) || '[]'); }catch(e){ return []; } }
    function setOpenLessons(arr){ localStorage.setItem(openLessonsKey, JSON.stringify(arr || [])); }

    function forceStopAll(){
        try{
            const html5 = document.getElementById('html5-player');
            if(html5) maybeCompleteByThreshold(html5);
            reportProgress(false);
            if(progressTimer){ clearInterval(progressTimer); progressTimer = null; }
            if(player && typeof player.stopVideo === 'function'){ try{ player.stopVideo(); }catch(e){} }
            if(player && typeof player.destroy === 'function'){ try{ player.destroy(); }catch(e){} }
            player = null; currentTopicId = null;
        }catch(e){}
    }

    function initSidebar(){
        let openLessonId = "{{ isset($lesson) ? (string)$lesson->id : '' }}";
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

            if(lessonId && lessonId === openLessonId){ if(topics) topics.style.display = 'block'; block.classList.add('active'); }
            else { if(topics) topics.style.display = 'none'; block.classList.remove('active'); }

            if(arrow){
                arrow.addEventListener('click', (ev) => {
                    ev.preventDefault(); ev.stopPropagation();
                    const isHidden = window.getComputedStyle(topics).display === 'none';
                    if(isHidden){ closeOtherLessons(block); topics.style.display = 'block'; block.classList.add('active');  setOpenLessons(lessonId ? [lessonId] : []); }
                    else { topics.style.display = 'none'; block.classList.remove('active');  setOpenLessons([]); }
                });
            }

            a.addEventListener('click', function(ev){
                if(ev.target.closest('.lesson-arrow')){ return; }
                ev.preventDefault();
                const isHidden = window.getComputedStyle(topics).display === 'none';
                if(isHidden){ closeOtherLessons(block); topics.style.display = 'block'; block.classList.add('active'); if(arrow)  setOpenLessons(lessonId ? [lessonId] : []); }
                else { topics.style.display = 'none'; block.classList.remove('active'); if(arrow)  setOpenLessons([]); }
            });

            a.addEventListener('mouseenter', function(){
                const url = a.getAttribute('href'); if(!url) return;
                const t = setTimeout(()=>{ prefetchLesson(url); prefetchTimers.delete(a); }, 180);
                prefetchTimers.set(a, t);
            });
            a.addEventListener('mouseleave', function(){
                const t = prefetchTimers.get(a); if(t){ clearTimeout(t); prefetchTimers.delete(a); }
                const contentUrl = (a.getAttribute('href') || '').replace(/\/?$/, '') + '/content';
                const ctrl = prefetchControllers.get(contentUrl); if(ctrl){ try{ ctrl.abort(); }catch(e){} prefetchControllers.delete(contentUrl); }
            });
        });
    }

    function initPage(lessonId){
        document.querySelectorAll('.topic-item').forEach(item => {
            item.addEventListener('click', () => {
                const topicId = item.getAttribute('data-topic-id');
                if(lessonId && topicId) localStorage.setItem('song_last_topic_' + lessonId, topicId);
                navigateTopic(lessonId, topicId, null, true);
                try{ if(window.innerWidth <= 900) closeSidebar(); }catch(e){}
                document.querySelectorAll('.topic-item.selected').forEach(s => s.classList.remove('selected'));
                item.classList.add('selected');
            });
        });

        document.querySelectorAll('.topic-item[data-topic-id]').forEach(item => {
            fetchTopicProgress(item.getAttribute('data-topic-id'));
        });

        const customPlay = document.getElementById('custom-play');
        if(customPlay){
            customPlay.addEventListener('click', function(){
                const placeholder = document.getElementById('video-placeholder');
                const ytId = placeholder ? placeholder.getAttribute('data-video-id') : null;
                const streamUrlAttr = placeholder ? placeholder.getAttribute('data-stream-url') : null;
                const topicId = placeholder ? placeholder.getAttribute('data-topic-id') : null;
                if(topicId) currentTopicId = String(topicId);
                if(streamUrlAttr){ destroyHtml5Player(); createHtml5PlayerAndPlay(streamUrlAttr, topicId); return; }
                if(topicId){
                    fetch(`/topics/${topicId}/stream`).then(async r=>{ try { return await r.json(); } catch(e){ return { url: null }; } }).then(data=>{
                        if(data && data.url){ 
                            if(data.url.match(/(youtu\.be\/|v=)([A-Za-z0-9_-]{11})/)){
                                const newYtId = data.url.match(/(youtu\.be\/|v=)([A-Za-z0-9_-]{11})/)[2];
                                try{ const ph = document.getElementById('video-placeholder'); if(ph) ph.style.display = 'none'; }catch(e){}
                                if(!player || typeof player.loadVideoById !== 'function'){ player = new YT.Player('player', { height:'100%', width:'100%', videoId: newYtId, playerVars:{rel:0,modestbranding:1}, events:{'onStateChange':onPlayerStateChange,'onReady':function(e){ player.playVideo(); }} }); } else { player.loadVideoById(newYtId); player.playVideo(); }
                                return;
                            }
                            placeholder.setAttribute('data-stream-url', data.url); 
                            destroyHtml5Player(); 
                            createHtml5PlayerAndPlay(data.url, topicId); 
                            return; 
                        }
                        if(ytId){ try{ const ph = document.getElementById('video-placeholder'); if(ph) ph.style.display = 'none'; }catch(e){} if(!player || typeof player.loadVideoById !== 'function'){ player = new YT.Player('player', { height:'100%', width:'100%', videoId: ytId, playerVars:{rel:0,modestbranding:1}, events:{'onStateChange':onPlayerStateChange,'onReady':function(e){ player.playVideo(); }} }); } else { player.loadVideoById(ytId); player.playVideo(); } }
                    }).catch(err => { if(ytId){ try{ const ph = document.getElementById('video-placeholder'); if(ph) ph.style.display = 'none'; }catch(e){} if(!player){ player = new YT.Player('player', { height:'100%', width:'100%', videoId: ytId, playerVars:{rel:0,modestbranding:1}, events:{'onStateChange':onPlayerStateChange} }); } else { player.loadVideoById(ytId); } } });
                } else if(ytId){ if(!player || typeof player.loadVideoById !== 'function'){ player = new YT.Player('player', { height:'100%', width:'100%', videoId: ytId, playerVars:{rel:0,modestbranding:1}, events:{'onStateChange':onPlayerStateChange} }); } else { player.loadVideoById(ytId); } }
            });
        }

        try{
            if(lessonId){
                const last = localStorage.getItem('song_last_topic_' + lessonId);
                if(last){ const el = document.querySelector('[data-topic-id="' + last + '"]'); if(el) el.click(); }
            }
        }catch(e){}

        const first = document.querySelector('.topic-item[data-topic-id]');
        if(first) first.click();

        const btnNext = document.getElementById('btn-next');
        const btnPrev = document.getElementById('btn-prev');
        function updateNavButtons(){
            const visibleTopics = Array.from(document.querySelectorAll('.topic-item[data-topic-id]'));
            const sel = document.querySelector('.topic-item.selected');
            const idx = sel ? visibleTopics.indexOf(sel) : -1;
            if(btnPrev) btnPrev.disabled = (idx <= 0);
            if(btnNext) btnNext.disabled = (idx < 0 || idx >= visibleTopics.length - 1);
        }

        if(btnNext){ btnNext.addEventListener('click', function(){ const topics = Array.from(document.querySelectorAll('.topic-item[data-topic-id]')); let sel = document.querySelector('.topic-item.selected'); let idx = sel ? topics.indexOf(sel) : -1; if(idx === -1 && topics.length){ const f = topics[0]; if(f){ f.click(); sel = f; idx = 0; } } if(idx >= 0 && idx < topics.length - 1){ const nxt = topics[idx+1]; if(nxt){ nxt.click(); } navigateTopic(lessonId, nxt.getAttribute('data-topic-id'), null, true); setTimeout(updateNavButtons, 50); } }); }
        if(btnPrev){ btnPrev.addEventListener('click', function(){ const topics = Array.from(document.querySelectorAll('.topic-item[data-topic-id]')); let sel = document.querySelector('.topic-item.selected'); let idx = sel ? topics.indexOf(sel) : -1; if(idx === -1 && topics.length){ const f = topics[0]; if(f){ f.click(); sel = f; idx = 0; } } if(idx > 0){ const prev = topics[idx-1]; if(prev){ prev.click(); } navigateTopic(lessonId, prev.getAttribute('data-topic-id'), null, true); setTimeout(updateNavButtons, 50); } }); }

        document.addEventListener('click', function(ev){ if(ev.target.closest('.topic-item')) setTimeout(updateNavButtons,30); });
        updateNavButtons();

        document.querySelectorAll('.video-nav-btn').forEach(btn => {
            btn.addEventListener('click', function(e){ const r = document.createElement('span'); r.className = 'ripple'; const rect = btn.getBoundingClientRect(); const size = Math.max(rect.width, rect.height); r.style.width = r.style.height = size + 'px'; r.style.left = (e.clientX - rect.left - size/2) + 'px'; r.style.top = (e.clientY - rect.top - size/2) + 'px'; btn.appendChild(r); setTimeout(()=>{ try{ r.remove(); }catch(e){} }, 700); });
        });
    }

    async function navigateTo(url, pushState=true){
        try{
            const spinner = document.getElementById('ajax-spinner'); if(spinner){ spinner.style.display = 'flex'; setTimeout(()=>spinner.classList.add('show'), 10); }
            const main = document.querySelector('main.content'); if(main){ main.classList.remove('fade-in'); main.classList.add('fade-out'); }
            const contentUrl = url.replace(/\/?$/, '') + '/content';
            let html;
            if(prefetchCache[contentUrl]){ html = prefetchCache[contentUrl]; } else { const res = await fetch(contentUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } }); if(!res.ok){ if(spinner){ spinner.classList.remove('show'); setTimeout(()=>spinner.style.display='none',200); } window.location.href = url; return; } html = await res.text(); }
            await new Promise(r => setTimeout(r, 260));
            document.querySelector('main.content').innerHTML = html;
            const lessonId = url.split('/').filter(Boolean).pop();
            if(pushState) history.pushState({ ajax: true, url: url }, '', url);
            initPage(lessonId);
            const m = document.querySelector('main.content'); if(m){ m.classList.remove('fade-out'); m.classList.add('fade-in'); }
            if(spinner){ spinner.classList.remove('show'); setTimeout(()=>spinner.style.display='none',220); }
        }catch(e){ window.location.href = url; }
    }

    window.addEventListener('popstate', function(ev){
        const path = location.pathname + location.search + location.hash;
        if(path.startsWith('/song-tutorial')){
            forceStopAll();
            const params = new URLSearchParams(location.search);
            const topicParam = params.get('topic');
            navigateTo(location.pathname, false).then(()=>{ if(topicParam){ const el = document.querySelector('[data-topic-id="'+topicParam+'"]'); if(el) el.click(); } });
        }
    });

    function navigateTopic(lessonId, topicId, videoUrl, pushState=true){
        try{
            forceStopAll();
            if(topicId) currentTopicId = String(topicId);
            const spinner = document.getElementById('ajax-spinner'); if(spinner){ spinner.style.display='flex'; setTimeout(()=>spinner.classList.add('show'),10); }
            const placeholder = document.getElementById('video-placeholder');
            if(placeholder){
                if(topicId) placeholder.setAttribute('data-topic-id', topicId);
                const vid = (videoUrl && videoUrl.match(/(youtu\.be\/|v=)([A-Za-z0-9_-]{11})/)) ? videoUrl.match(/(youtu\.be\/|v=)([A-Za-z0-9_-]{11})/)[2] : null;
                if(vid){ placeholder.style.backgroundImage = 'url(https://img.youtube.com/vi/'+vid+'/hqdefault.jpg)'; placeholder.setAttribute('data-video-id', vid); placeholder.removeAttribute('data-stream-url'); placeholder.removeAttribute('data-bunny-guid'); }
                else { try{ const topicEl = document.querySelector('[data-topic-id="' + topicId + '"]'); if(topicEl){ const bg = topicEl.getAttribute('data-bunny-guid'); if(bg){ placeholder.setAttribute('data-bunny-guid', bg); } else { placeholder.removeAttribute('data-bunny-guid'); } } placeholder.removeAttribute('data-video-id'); placeholder.removeAttribute('data-stream-url'); }catch(e){} }
            }
            try{ const topicEl = document.querySelector('[data-topic-id="' + topicId + '"]'); const titleEl = document.getElementById('video-title'); const descEl = document.getElementById('video-description'); if(topicEl){ if(titleEl) titleEl.textContent = topicEl.textContent.trim(); if(descEl) descEl.textContent = topicEl.getAttribute('data-description') || ''; } }catch(e){}
            const newUrl = '/song-tutorial/' + lessonId + (topicId ? '?topic=' + topicId : '');
            if(pushState) history.pushState({ ajax: true, url: newUrl }, '', newUrl);
            if(player){ player.loadVideoById(placeholder.getAttribute('data-video-id')); currentTopicId = topicId; player.playVideo(); }
            else { const customPlay = document.getElementById('custom-play'); if(customPlay) customPlay.click(); }
        }catch(e){ console.error('navigateTopic error', e); }
    }

    initSidebar();
    const pathParts = location.pathname.split('/').filter(Boolean);
    const currentLessonId = (pathParts.length && pathParts[0] === 'song-tutorial' && pathParts[1]) ? pathParts[1] : (document.querySelector('.lesson-block') ? document.querySelector('.lesson-block').querySelector('.lesson-header').getAttribute('href').split('/').filter(Boolean).pop() : null);
    initPage(currentLessonId);

    window.addEventListener('resize', function(){ try{ if(window.innerWidth > 900){ const sb = document.querySelector('.sidebar'); if(sb) sb.classList.remove('active'); const bd = document.getElementById('sidebar-backdrop'); if(bd) bd.classList.remove('visible'); document.body.classList.remove('sidebar-open'); document.body.style.overflow = ''; } }catch(e){} });

    window.addEventListener('pagehide', function(){ try{ const html5 = document.getElementById('html5-player'); if(html5) maybeCompleteByThreshold(html5); reportProgress(false); }catch(e){} });
});
</script>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/kelas.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
@endpush