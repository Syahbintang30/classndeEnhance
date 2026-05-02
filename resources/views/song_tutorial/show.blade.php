@extends('layouts.app')

@section('title', 'Song Tutorial')

@section('content')
<style>
    /* Hide global navbar */
    body > nav { display: none; }

    /* Custom LMS Navbar */
    .lms-navbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 80px;
        background: linear-gradient(180deg, #111 0%, #0a0a0a 100%);
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        padding: 0 20px;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .lms-navbar-left {
        display: flex;
        align-items: center;
        width: 280px;
    }

    .lms-home-link {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #e0e0e0;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .lms-home-link:hover { color: #fff; }

    .lms-navbar-right {
        display: flex;
        align-items: center;
        gap: 32px;
    }

    .lms-nav-link {
        color: #a0a0a0;
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .lms-nav-link:hover { color: #fff; }
    .lms-nav-link.active { color: #fff; font-weight: 600; }

    :root[data-theme="light"] .lms-navbar {
        background: linear-gradient(180deg, #ffffff 0%, #f4f5f7 100%);
        border-bottom-color: rgba(15, 23, 42, 0.08);
    }

    :root[data-theme="light"] .lms-home-link,
    :root[data-theme="light"] .lms-nav-link { color: #334155; }

    :root[data-theme="light"] .lms-home-link:hover,
    :root[data-theme="light"] .lms-nav-link:hover,
    :root[data-theme="light"] .lms-nav-link.active { color: #0f172a; }

    :root[data-theme="light"] .kelas-container { background: #f5f5f7; }

    :root[data-theme="light"] .sidebar {
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        border-right-color: rgba(15, 23, 42, 0.08);
    }

    :root[data-theme="light"] .lesson-header { color: #0f172a; }
    :root[data-theme="light"] .lesson-header:hover { background: rgba(15, 23, 42, 0.04); }
    :root[data-theme="light"] .lesson-block.active .lesson-header { background: rgba(15, 23, 42, 0.06); color: #0f172a; }
    :root[data-theme="light"] .lesson-block.active > .lesson-header:before { background: #0f172a; }
    :root[data-theme="light"] .lesson-logo { background: #e2e8f0; color: #0f172a; }
    :root[data-theme="light"] .lesson-arrow,
    :root[data-theme="light"] .lesson-title,
    :root[data-theme="light"] .topic-item,
    :root[data-theme="light"] #video-title { color: #0f172a; }
    :root[data-theme="light"] #video-description { color: #475569; }
    :root[data-theme="light"] .topic-item .topic-box { color: #111827; }
    :root[data-theme="light"] .topic-item:hover .topic-box { background: rgba(15, 23, 42, 0.04); color: #0f172a; }
    :root[data-theme="light"] .topic-item.selected .topic-box { background: rgba(15, 23, 42, 0.08); border-left-color: #0f172a; color: #0f172a; }
    :root[data-theme="light"] .topic-item.completed .topic-box { color: #0f172a; }
    :root[data-theme="light"] .player-wrapper #player { box-shadow: 0 8px 34px rgba(15, 23, 42, 0.12); border-color: rgba(15, 23, 42, 0.08); }
    :root[data-theme="light"] .custom-play-btn { background: rgba(15, 23, 42, 0.08); border-color: rgba(15, 23, 42, 0.12); box-shadow: 0 6px 18px rgba(15, 23, 42, 0.12); }
    :root[data-theme="light"] .custom-play-btn:before { border-left-color: #0f172a; }
    :root[data-theme="light"] .video-nav-btn#btn-prev { background: linear-gradient(180deg, #ffffff, #eef2ff); color: #0f172a; }
    :root[data-theme="light"] .video-nav-btn#btn-next { background: linear-gradient(180deg, #0f172a, #111827); color: #ffffff; }

    /* Layout */
    * { box-sizing: border-box; }

    .kelas-container {
        display: flex;
        min-height: calc(100vh - 80px);
        background: #0a0a0a;
    }

    .sidebar {
        width: 280px;
        background: linear-gradient(180deg, #111 0%, #0a0a0a 100%);
        border-right: 1px solid rgba(255, 255, 255, 0.06);
        overflow-y: auto;
        padding: 0;
        position: relative;
        z-index: 5;
    }

    .sidebar::-webkit-scrollbar { width: 6px; }
    .sidebar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 3px; }

    .lesson-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 20px;
        cursor: pointer;
        color: #e0e0e0;
        background: transparent;
        border: none;
        width: 100%;
        text-align: left;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .lesson-header:hover { background: rgba(255, 255, 255, 0.04); }
    .lesson-block.active .lesson-header { background: rgba(255, 255, 255, 0.08); color: #fff; }
    .lesson-left { display: flex; align-items: center; gap: 10px; }

    .lesson-arrow {
        display: inline-block;
        font-size: 22px;
        line-height: 1;
        font-weight: 700;
        color: #d8d8d8;
        transition: transform 0.2s ease;
    }

    .topic-list { list-style: none; padding: 0; margin: 0; display: none; }

    .topic-item .topic-box {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 20px 10px 42px;
        color: #a0a0a0;
        text-decoration: none;
        font-size: 13px;
        transition: all 0.2s ease;
        border-left: 2px solid transparent;
        cursor: pointer;
    }

    .topic-item.completed .topic-box { color: #d7f6ff; }
    .topic-item:hover .topic-box { background: rgba(255, 255, 255, 0.04); color: #d0d0d0; }
    .topic-item.selected .topic-box { background: rgba(255, 255, 255, 0.08); border-left-color: #888; color: #fff; font-weight: 600; }

    .home-float-btn { display: none; }

    .main-wrapper { flex: 1; padding: 0; overflow-y: auto; position: relative; }

    .content { padding: 14px 56px 28px 56px; max-width: 1120px; margin: 0 auto; }

    .main-wrapper::-webkit-scrollbar { width: 8px; }
    .main-wrapper::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 4px; }

    #video-title { font-size: 30px; font-weight: 900; margin: 0 0 12px 0; color: #fff; letter-spacing: -0.02em; text-align: center; }
    #video-description { font-size: 15px; color: #a0a0a0; margin: 0 0 28px 0; line-height: 1.6; text-align: center; }

    .player-wrapper { width: 100%; max-width: 980px; margin: 0 auto; }

    #player {
        width: 100%;
        aspect-ratio: 16 / 9;
        min-height: unset !important;
        border-radius: 18px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 12px 36px rgba(0, 0, 0, 0.45);
        background: #000;
    }

    .video-placeholder {
        background-size: cover;
        background-position: center;
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
    }

    .custom-play-btn {
        width: 86px;
        height: 86px;
        border-radius: 999px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        background: rgba(0, 0, 0, 0.45);
        backdrop-filter: blur(4px);
        cursor: pointer;
        position: relative;
    }

    .custom-play-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-42%, -50%);
        width: 0;
        height: 0;
        border-left: 22px solid #fff;
        border-top: 14px solid transparent;
        border-bottom: 14px solid transparent;
    }

    .video-controls { display: flex; gap: 10px; justify-content: center; margin-top: 14px; }

    .video-nav-btn {
        appearance: none;
        border: 1px solid rgba(255, 255, 255, 0.16);
        border-radius: 10px;
        height: 30px;
        min-width: 92px;
        padding: 0 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.22);
    }

    .video-nav-btn .label { font-size: 14px; font-weight: 700; letter-spacing: -0.01em; }
    .video-nav-btn svg { width: 14px; height: 14px; stroke-width: 2.5; }
    #btn-prev.video-nav-btn { background: linear-gradient(180deg, #b7b7b7 0%, #9f9f9f 100%); color: #0f0f0f; border-color: rgba(255, 255, 255, 0.28); }
    #btn-next.video-nav-btn { background: linear-gradient(180deg, #111316 0%, #090a0c 100%); color: #fff; }
    .video-nav-btn:hover:not(:disabled) { transform: translateY(-2px); filter: brightness(1.05); }
    .video-nav-btn:disabled { opacity: 0.4; cursor: not-allowed; }

    @media (max-width: 768px) {
        .kelas-container { flex-direction: column; }
        .sidebar { width: 100%; border-right: none; border-bottom: 1px solid rgba(255,255,255,0.06); }
        .content { padding: 14px 16px 22px 16px; }
        #video-title { font-size: 24px; }
        .video-nav-btn { min-width: 88px; height: 34px; font-size: 13px; padding: 0 9px; }
    }
</style>

<!-- Custom LMS Navbar -->
<nav class="lms-navbar">
    <div class="lms-navbar-left">
        <a href="{{ route('lms.dashboard') }}" class="lms-home-link">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            Home
        </a>
    </div>
    <div class="lms-navbar-right">
        <a href="{{ route('lms.entry') }}" class="lms-nav-link @if(request()->routeIs('kelas.show') || request()->routeIs('lms.entry')) active @endif">Lessons</a>
        <a href="{{ route('coaching.upcoming') }}" class="lms-nav-link @if(request()->routeIs('coaching.*')) active @endif">Coaching</a>
        @php $user = auth()->user(); @endphp
        @if($user && $user->hasLmsAccess())
            <a href="{{ route('song.tutorial.index') }}" class="lms-nav-link @if(request()->routeIs('song.tutorial.*')) active @endif">Song Tutorial</a>
        @endif
    </div>
</nav>

<div class="kelas-container" style="display:flex;">
    <aside class="sidebar" style="width:280px;">
        <ul class="menu" style="list-style:none;padding:0;margin:0;">
            @forelse($lessons as $ls)
                <li class="lesson-block">
                    <a href="{{ route('song.tutorial.show', $ls->id) }}" class="lesson-header">
                        <div class="lesson-left">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 19H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h4m6 0h4a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-4m-6-4h12"/><circle cx="12" cy="12" r="2"/></svg>
                            {{ $ls->title }}
                        </div>
                        <span class="lesson-arrow">▸</span>
                    </a>
                    @php $topics = $ls->topics ?? collect(); @endphp
                    <ul class="topic-list">
                        @forelse($topics as $topic)
                            <li class="topic-item"
                                data-bunny-guid="{{ $topic->bunny_guid }}"
                                data-description="{{ $topic->description }}"
                                data-topic-id="{{ $topic->id }}">
                                <div class="topic-box">
                                    <span>{{ $topic->title }}</span>
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

    <div class="main-wrapper" style="flex:1;">
        <main class="content">
            @php $firstLesson = $lessons->first(); @endphp
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
    v.style.cssText = 'position:absolute;top:0;left:0;width:100%;height:100%;z-index:2;';
    container.appendChild(v);
    try{ const ph = document.getElementById('video-placeholder'); if(ph) ph.style.display = 'none'; }catch(e){}
    const attachAndPlay = () => {
        if(window.Hls && Hls.isSupported()){ const hls = new Hls(); window._hlsInstance = hls; hls.loadSource(streamUrl); hls.attachMedia(v); }
        else { v.src = streamUrl; }
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
        const open = getOpenLessons();
        const openLessonId = Array.isArray(open) && open.length ? open[0] : null;
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

            if(lessonId && lessonId === openLessonId){ if(topics) topics.style.display = 'block'; block.classList.add('active'); if(arrow) arrow.textContent = '▾'; }
            else { if(topics) topics.style.display = 'none'; block.classList.remove('active'); if(arrow) arrow.textContent = '▸'; }

            if(arrow){
                arrow.addEventListener('click', (ev) => {
                    ev.preventDefault(); ev.stopPropagation();
                    const isHidden = window.getComputedStyle(topics).display === 'none';
                    if(isHidden){ closeOtherLessons(block); topics.style.display = 'block'; block.classList.add('active'); arrow.textContent = '▾'; setOpenLessons(lessonId ? [lessonId] : []); }
                    else { topics.style.display = 'none'; block.classList.remove('active'); arrow.textContent = '▸'; setOpenLessons([]); }
                });
            }

            a.addEventListener('click', function(ev){
                if(ev.target.closest('.lesson-arrow')){ return; }
                ev.preventDefault();
                const isHidden = window.getComputedStyle(topics).display === 'none';
                if(isHidden){ closeOtherLessons(block); topics.style.display = 'block'; block.classList.add('active'); if(arrow) arrow.textContent = '▾'; setOpenLessons(lessonId ? [lessonId] : []); }
                else { topics.style.display = 'none'; block.classList.remove('active'); if(arrow) arrow.textContent = '▸'; setOpenLessons([]); }
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
                        if(data && data.url){ placeholder.setAttribute('data-stream-url', data.url); destroyHtml5Player(); createHtml5PlayerAndPlay(data.url, topicId); return; }
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