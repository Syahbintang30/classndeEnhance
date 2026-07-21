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
    <div class="flex-1 flex flex-col md:flex-row w-full max-w-screen-2xl mx-auto overflow-hidden bg-[#08080a] relative">
        
        <!-- Sidebar Navigation for Song Library -->
        <aside class="sidebar w-full md:w-80 flex-shrink-0 bg-zinc-950/90 border-r border-white/10 overflow-y-auto absolute md:relative z-40 inset-y-0 transform -translate-x-full md:translate-x-0 backdrop-blur-xl h-full">
            <!-- Mobile Close Button -->
            <button onclick="closeSidebar()" class="md:hidden absolute top-4 right-4 text-gray-400 hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
            
            <div class="p-5 border-b border-white/10 flex items-center justify-between">
                <h3 class="font-display text-2xl text-white tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-music text-blue-500"></i> Song Library
                </h3>
            </div>
            
            <div class="p-4">
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

        <!-- Main Content Area -->
        <div class="main-wrapper flex-1 relative overflow-y-auto w-full p-4 md:p-8">
            <!-- Mobile Sidebar Trigger Button -->
            <button onclick="toggleSidebar()" class="md:hidden mb-4 inline-flex items-center gap-2 px-4 py-2 bg-zinc-900 border border-white/10 rounded-xl text-xs font-bold text-gray-300">
                <i class="fa-solid fa-list-ul text-blue-400"></i> Browse Songs
            </button>

            <main class="content max-w-4xl mx-auto w-full flex flex-col items-center">
                @php $firstLesson = $lesson; @endphp
                @include('kelas._lesson_content', ['lesson' => $firstLesson])
            </main>
        </div>
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

document.addEventListener('DOMContentLoaded', () => {
    const topicItems = document.querySelectorAll('.topic-item');
    topicItems.forEach(item => {
        item.addEventListener('click', function() {
            topicItems.forEach(t => t.classList.remove('selected'));
            this.classList.add('selected');

            const title = this.querySelector('span')?.innerText || 'Lesson Topic';
            const desc = this.getAttribute('data-description') || '';
            const guid = this.getAttribute('data-bunny-guid') || '';
            const topicId = this.getAttribute('data-topic-id');

            const vTitle = document.getElementById('video-title');
            if(vTitle) vTitle.innerText = title;
            
            const vDesc = document.getElementById('video-description');
            if(vDesc) vDesc.innerText = desc;

            if(guid) {
                createHtml5PlayerAndPlay('https://video.bunnycdn.com/play/' + guid, topicId);
            }
        });
    });
});

function createHtml5PlayerAndPlay(streamUrl, topicId){
    const container = document.getElementById('player'); if(!container) return;
    if(topicId) currentTopicId = String(topicId);
    let v = document.getElementById('html5-player');
    if(v){ try{ v.pause(); }catch(e){} v.remove(); }
    if(window._hlsInstance){ try{ window._hlsInstance.destroy(); }catch(e){} window._hlsInstance = null; }
    
    const placeholder = document.getElementById('video-placeholder');
    if(placeholder) placeholder.style.display = 'none';

    v = document.createElement('video');
    v.id = 'html5-player';
    v.controls = true;
    v.autoplay = true;
    v.className = 'w-full h-full absolute inset-0 object-contain bg-black rounded-2xl';
    container.appendChild(v);

    if (Hls.isSupported() && streamUrl.includes('bunnycdn')) {
        const hls = new Hls();
        hls.loadSource(streamUrl);
        hls.attachMedia(v);
        window._hlsInstance = hls;
    } else {
        v.src = streamUrl;
    }
}
</script>
@endsection