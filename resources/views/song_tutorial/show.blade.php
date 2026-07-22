@extends('layouts.app')

@section('title', 'Song Tutorial')

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
                            black: '#08080a',
                            card: '#121218',
                            border: '#222230',
                            accent: '#0066ff'
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
        .tw-dash ::-webkit-scrollbar-track { background: #08080a; }
        .tw-dash ::-webkit-scrollbar-thumb { background: #222232; border-radius: 3px; }
        .tw-dash ::-webkit-scrollbar-thumb:hover { background: #3b82f6; }

        .tw-dash a { text-decoration: none; }
        .tw-dash *:focus { outline: none !important; }

        /* Override legacy tree lines completely */
        .topic-item::before, .topic-item:before,
        .topic-list::before, .topic-list:before,
        .topic-box::before, .topic-box:before {
            display: none !important;
            content: none !important;
            width: 0 !important;
            height: 0 !important;
        }

        .sidebar { transition: all 0.3s ease; }
        .sidebar.active { transform: translateX(0) !important; }
        #sidebar-backdrop.visible { opacity: 1; pointer-events: auto; }

        .topic-item.selected {
            background: rgba(59, 130, 246, 0.15) !important;
            border-color: rgba(59, 130, 246, 0.4) !important;
            color: #ffffff !important;
            font-weight: 700 !important;
        }
        .topic-item.completed {
            color: #60a5fa !important;
        }
    </style>
@endpush

@section('content')
<div class="tw-dash min-h-screen flex flex-col antialiased bg-[#08080a] text-gray-200"
     x-data="{ mobileMenuOpen: false }">

    {{-- Backdrop for Mobile Sidebar --}}
    <div id="sidebar-backdrop" onclick="closeSidebar()" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-30 opacity-0 pointer-events-none transition-opacity duration-300 md:hidden"></div>

    {{-- ─── TOP NAVIGATION BAR ──────────────────────────────────────────── --}}
    @include('layouts.lms_header')

    <!-- MAIN LESSONS CONTAINER -->
    <!-- MAIN LESSONS CONTAINER -->
    <div class="flex-1 flex flex-col md:flex-row w-full max-w-screen-2xl mx-auto overflow-y-auto bg-[#08080a] relative">
        
        <!-- Main Content Area (Video First on Mobile) -->
        <div class="main-wrapper flex-1 relative w-full p-4 md:p-8 order-1 md:order-2">
            <main class="content max-w-4xl mx-auto w-full flex flex-col items-center">
                @php $firstLesson = $lesson; @endphp
                @include('kelas._lesson_content', ['lesson' => $firstLesson])
            </main>
        </div>

        <!-- Sidebar Navigation for Song Library (Below Video on Mobile, Left Sidebar on Desktop) -->
        <aside class="sidebar w-full md:w-80 flex-shrink-0 bg-zinc-950/90 border-t md:border-t-0 md:border-r border-white/10 order-2 md:order-1 relative p-4 md:p-0 backdrop-blur-xl">
            <div class="p-4 md:p-5 border-b border-white/10 flex items-center justify-between">
                <h3 class="font-display text-2xl text-white tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-music text-blue-500"></i> Song Library
                </h3>
            </div>
            
            <div class="p-0 md:p-4 mt-4 md:mt-0">
                <!-- SINGLE UNIFIED CONTAINER CARD (EXCLUSIVE ACCORDION) -->
                <div class="bg-zinc-900/60 border border-white/10 rounded-2xl overflow-hidden divide-y divide-white/5 shadow-xl"
                     x-data="{ activeSection: 0 }">
                    @forelse($lessons as $index => $ls)
                        @php $topics = $ls->topics ?? collect(); @endphp
                        <div>
                            <!-- Accordion Header Button -->
                            <button @click="activeSection = (activeSection === {{ $index }} ? null : {{ $index }})" type="button" class="w-full flex items-center justify-between p-4 text-left hover:bg-white/5 transition group cursor-pointer">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-8 h-8 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center text-xs flex-shrink-0">
                                        <i class="fa-solid fa-music"></i>
                                    </div>
                                    <div class="truncate">
                                        <span class="font-bold text-sm text-white group-hover:text-blue-400 transition block truncate">{{ $ls->title }}</span>
                                        <span class="text-[10px] text-gray-400 font-medium">{{ count($topics) }} Parts</span>
                                    </div>
                                </div>
                                
                                <i class="fa-solid fa-chevron-down text-xs text-gray-400 transition-transform duration-300 flex-shrink-0 ml-2"
                                   :class="activeSection === {{ $index }} ? 'rotate-180 text-blue-400' : ''"></i>
                            </button>

                            <!-- Collapsible Topics Body -->
                            <div x-show="activeSection === {{ $index }}" x-transition.opacity class="p-3 pt-2 space-y-1 bg-black/50 border-t border-white/5">
                                @forelse($topics as $tIndex => $topic)
                                    <div class="topic-item cursor-pointer px-3.5 py-2.5 rounded-xl text-xs font-semibold text-gray-400 hover:text-white hover:bg-white/5 border border-transparent transition flex items-center gap-2.5 {{ ($index === 0 && $tIndex === 0) ? 'selected' : '' }}" 
                                         data-bunny-guid="{{ $topic->bunny_guid }}"
                                         data-description="{{ $topic->description }}"
                                         data-topic-id="{{ $topic->id }}">
                                        <i class="fa-solid fa-circle-play text-[10px] text-blue-400"></i>
                                        <span class="truncate">{{ $topic->title }}</span>
                                    </div>
                                @empty
                                    <div class="text-xs text-gray-500 italic px-2 py-1">No parts available</div>
                                @endforelse
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500 text-xs">No song tutorials available yet.</div>
                    @endforelse
                </div>
            </div>
        </aside>
    </div>

</div>

<script>
function toggleSidebar() {
    const sb = document.querySelector('.sidebar');
    if(!sb) return;
    const isActive = sb.classList.toggle('active');
    const bd = document.getElementById('sidebar-backdrop');
    if(bd) bd.classList.toggle('visible', isActive);
}

function closeSidebar(){
    const sb = document.querySelector('.sidebar'); if(!sb) return;
    sb.classList.remove('active');
    const bd = document.getElementById('sidebar-backdrop'); if(bd) bd.classList.remove('visible');
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

function destroyHtml5Player(){
    const v = document.getElementById('html5-player'); 
    if(v){ try{ v.pause(); }catch(e){} v.remove(); }
    if(window._hlsInstance){ try{ window._hlsInstance.destroy(); }catch(e){} window._hlsInstance = null; }
    const ph = document.getElementById('video-placeholder'); 
    if(ph) { ph.style.display = 'flex'; ph.style.opacity = '1'; }
}

function createHtml5PlayerAndPlay(streamUrl, topicId){
    const container = document.getElementById('player'); 
    if(!container) return;
    if(topicId) currentTopicId = String(topicId);
    
    let v = document.getElementById('html5-player');
    if(v){ try{ v.pause(); }catch(e){} v.remove(); }
    if(window._hlsInstance){ try{ window._hlsInstance.destroy(); }catch(e){} window._hlsInstance = null; }
    
    const placeholder = document.getElementById('video-placeholder');
    if(placeholder) {
        placeholder.style.setProperty('display', 'none', 'important');
        placeholder.style.opacity = '0';
    }

    v = document.createElement('video');
    v.id = 'html5-player';
    v.controls = true;
    v.autoplay = true;
    v.setAttribute('playsinline', '');
    v.style.position = 'absolute';
    v.style.top = '0';
    v.style.left = '0';
    v.style.width = '100%';
    v.style.height = '100%';
    v.style.zIndex = '50';
    v.style.backgroundColor = '#000';
    v.className = 'rounded-2xl object-contain';
    container.appendChild(v);

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

    const attachAndPlay = () => {
        if(window.Hls && Hls.isSupported() && (streamUrl.includes('.m3u8') || streamUrl.includes('b-cdn.net') || streamUrl.includes('bunnycdn'))){
            const hls = new Hls(); 
            window._hlsInstance = hls; 
            hls.loadSource(streamUrl); 
            hls.attachMedia(v);
            hls.on(Hls.Events.MANIFEST_PARSED, function(){
                setupQualitySelector(hls);
                v.play().catch(e => console.warn('Autoplay prevented:', e));
            });
        } else {
            v.src = streamUrl;
            v.play().catch(e => console.warn('Autoplay prevented:', e));
        }
    };


    if(!window.Hls){
        const s = document.createElement('script');
        s.src = 'https://cdn.jsdelivr.net/npm/hls.js@latest';
        s.async = true;
        s.onload = () => { try{ attachAndPlay(); }catch(e){ console.error(e); } };
        s.onerror = () => attachAndPlay();
        document.head.appendChild(s);
    } else {
        attachAndPlay();
    }
}

function playTopicById(topicId, title, description) {
    if(!topicId) return;
    currentTopicId = String(topicId);

    const vTitle = document.getElementById('video-title');
    if(vTitle && title) vTitle.innerText = title;
    
    const vDesc = document.getElementById('video-description');
    if(vDesc && description) vDesc.innerText = description;

    const placeholder = document.getElementById('video-placeholder');
    if(placeholder) {
        placeholder.setAttribute('data-topic-id', topicId);
        placeholder.removeAttribute('data-stream-url');
    }

    // Fetch signed stream URL from server
    fetch(`/topics/${topicId}/stream`)
        .then(r => r.json())
        .then(data => {
            if(data && data.url) {
                // Check if YouTube URL
                const ytMatch = data.url.match(/(youtu\.be\/|v=)([A-Za-z0-9_-]{11})/);
                if(ytMatch) {
                    const ytId = ytMatch[2];
                    if(placeholder) placeholder.style.display = 'none';
                    if(!player || typeof player.loadVideoById !== 'function') {
                        player = new YT.Player('player', {
                            height: '100%', width: '100%', videoId: ytId,
                            playerVars: { rel:0, modestbranding:1, autoplay:1 },
                            events: { 'onReady': function(e){ player.playVideo(); } }
                        });
                    } else {
                        player.loadVideoById(ytId);
                        player.playVideo();
                    }
                    return;
                }

                if(placeholder) placeholder.setAttribute('data-stream-url', data.url);
                createHtml5PlayerAndPlay(data.url, topicId);
            } else {
                console.warn('No stream URL returned for topic', topicId);
            }
        })
        .catch(err => {
            console.error('Failed to load topic stream:', err);
        });

    if(window.innerWidth <= 900) closeSidebar();
}

document.addEventListener('DOMContentLoaded', () => {
    const topicItems = document.querySelectorAll('.topic-item');
    
    topicItems.forEach(item => {
        item.addEventListener('click', function() {
            topicItems.forEach(t => t.classList.remove('selected'));
            this.classList.add('selected');

            const title = this.querySelector('span')?.innerText || 'Lesson Topic';
            const desc = this.getAttribute('data-description') || '';
            const topicId = this.getAttribute('data-topic-id');

            playTopicById(topicId, title, desc);
        });
    });

    // Wire Custom Play Button
    const customPlayBtn = document.getElementById('custom-play');
    if(customPlayBtn) {
        customPlayBtn.addEventListener('click', function() {
            const placeholder = document.getElementById('video-placeholder');
            const streamUrlAttr = placeholder ? placeholder.getAttribute('data-stream-url') : null;
            const topicId = placeholder ? placeholder.getAttribute('data-topic-id') : null;

            if(streamUrlAttr) {
                createHtml5PlayerAndPlay(streamUrlAttr, topicId);
            } else if(topicId) {
                const selectedItem = document.querySelector(`.topic-item[data-topic-id="${topicId}"]`);
                const title = selectedItem ? (selectedItem.querySelector('span')?.innerText || '') : '';
                const desc = selectedItem ? selectedItem.getAttribute('data-description') : '';
                playTopicById(topicId, title, desc);
            }
        });
    }

    // Wire Navigation Buttons (Prev / Next)
    const btnPrev = document.getElementById('btn-prev');
    const btnNext = document.getElementById('btn-next');

    function navigateTopicOffset(offset) {
        const items = Array.from(document.querySelectorAll('.topic-item'));
        if(items.length === 0) return;

        let currentIndex = items.findIndex(i => i.classList.contains('selected'));
        if(currentIndex === -1) currentIndex = 0;

        let targetIndex = currentIndex + offset;
        if(targetIndex >= 0 && targetIndex < items.length) {
            items[targetIndex].click();
        }
    }

    if(btnPrev) {
        btnPrev.addEventListener('click', () => navigateTopicOffset(-1));
    }
    if(btnNext) {
        btnNext.addEventListener('click', () => navigateTopicOffset(1));
    }
});
</script>
@endsection