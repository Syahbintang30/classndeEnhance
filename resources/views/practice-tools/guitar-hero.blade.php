@extends('layouts.app')

@section('title', 'Songsterr-Style Interactive Guitar TAB Player')

@push('head')
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Roboto+Mono:wght@400;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            important: true,
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            black: '#121216',
                            panel: '#1a1a22',
                            border: 'rgba(255, 255, 255, 0.08)',
                            amber: '#f59e0b',
                            blue: '#3b82f6',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        mono: ['"Roboto Mono"', 'monospace'],
                    }
                }
            }
        }
    </script>
    <style>
        body > nav { display: none !important; }
        .tw-dash {
            background-color: #121216 !important;
            color: #f3f4f6 !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
        }
        .songsterr-panel {
            background: #1a1a22;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 1rem;
        }
        .songsterr-btn {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.15s ease;
        }
        .songsterr-btn:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.25);
        }
        .songsterr-btn.active {
            background: #f59e0b;
            color: #000000;
            border-color: #f59e0b;
            font-weight: 800;
        }
    </style>
@endpush

@section('content')
<div class="tw-dash min-h-screen flex flex-col antialiased bg-[#121216] text-gray-200 relative overflow-hidden"
     x-data="{ mobileMenuOpen: false }">

    {{-- TOP LMS HEADER --}}
    @include('layouts.lms_header')

    <!-- SONGSTERR TOP BAR: SONG & ARTIST META -->
    <header class="bg-[#181820] border-b border-white/10 px-4 sm:px-8 py-4">
        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-4">
            
            <div class="flex items-center gap-4">
                <a href="{{ route('practice.index') }}" class="p-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-gray-400 hover:text-white transition">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>

                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-amber-500/20 text-amber-400 border border-amber-500/30">INTERACTIVE TAB PLAYER</span>
                        <span class="text-xs text-gray-400 font-semibold" id="songBpmDisplay">72 BPM • 4/4</span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight mt-0.5" id="songTitleDisplay">
                        Black Label Society - In This River (Live Solo TAB)
                    </h1>
                </div>
            </div>

            <!-- SONG SELECTOR DROPDOWN -->
            <div class="flex items-center gap-3">
                <span class="text-xs font-bold text-gray-400 hidden sm:inline">SELECT SONG:</span>
                <select id="songSelectDropdown" onchange="loadSongsterrTrack(this.value)" class="bg-[#121216] text-amber-400 font-bold text-xs rounded-xl border border-white/15 px-3 py-2 focus:outline-none cursor-pointer">
                    @foreach($songTabs as $index => $tab)
                        <option value="{{ $index }}">🎵 {{ $tab->artist }} - {{ $tab->title }} ({{ $tab->bpm }} BPM)</option>
                    @endforeach
                </select>
            </div>

        </div>
    </header>

    <!-- MAIN PLAYER WORKSPACE -->
    <main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-8 py-6 space-y-4">
        
        <!-- SONGSTERR AUDIO TOOLBAR (PLAY, SPEED, METRONOME, LOOP) -->
        <div class="songsterr-panel p-4 flex flex-wrap items-center justify-between gap-4 shadow-xl">
            
            <!-- Left Controls: Play/Pause Big Button & Track Name -->
            <div class="flex items-center gap-4">
                <button id="btnPlayPause" onclick="toggleSongsterrPlay()" class="w-12 h-12 rounded-xl bg-amber-500 hover:bg-amber-400 text-black font-extrabold text-xl flex items-center justify-center transition shadow-lg shadow-amber-500/20 cursor-pointer">
                    <i class="fa-solid fa-play ml-0.5" id="playIcon"></i>
                </button>

                <div>
                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block">ACTIVE INSTRUMENT TRACK</span>
                    <div class="text-xs font-extrabold text-white flex items-center gap-2">
                        <i class="fa-solid fa-guitar text-amber-400"></i>
                        <span id="activeTrackName">Electric Guitar Clean (Lead Solo)</span>
                    </div>
                </div>
            </div>

            <!-- Middle Controls: Speed Rate Selector -->
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-gray-400 me-1">SPEED:</span>
                <button onclick="setSongsterrSpeed(0.5)" id="speed05" class="songsterr-btn px-3 py-1.5 rounded-lg text-xs font-bold text-gray-300">50%</button>
                <button onclick="setSongsterrSpeed(0.75)" id="speed075" class="songsterr-btn px-3 py-1.5 rounded-lg text-xs font-bold text-gray-300">75%</button>
                <button onclick="setSongsterrSpeed(1.0)" id="speed10" class="songsterr-btn active px-3 py-1.5 rounded-lg text-xs font-bold">100%</button>
                <button onclick="setSongsterrSpeed(1.25)" id="speed125" class="songsterr-btn px-3 py-1.5 rounded-lg text-xs font-bold text-gray-300">125%</button>
            </div>

            <!-- Right Controls: Metronome & Loop Toggles -->
            <div class="flex items-center gap-3">
                <button id="btnMetronome" onclick="toggleMetronome()" class="songsterr-btn px-3 py-1.5 rounded-lg text-xs font-bold text-gray-300 flex items-center gap-1.5">
                    <i class="fa-solid fa-clock"></i>
                    <span>Metronome</span>
                </button>

                <button id="btnLoopRegion" onclick="toggleLoop()" class="songsterr-btn px-3 py-1.5 rounded-lg text-xs font-bold text-gray-300 flex items-center gap-1.5">
                    <i class="fa-solid fa-rotate"></i>
                    <span>Loop Solo</span>
                </button>
            </div>

        </div>

        <!-- CANVAS SONGSTERR TAB SHEET MUSIC VIEWER -->
        <div class="songsterr-panel p-4 sm:p-6 relative overflow-hidden bg-[#0d0d11] border border-white/10 shadow-2xl">
            
            <!-- Measure Header Bar -->
            <div class="flex items-center justify-between text-xs font-mono font-bold text-gray-400 pb-3 border-b border-white/10 mb-4">
                <span id="measureCounter">MEASURE 1 / 4</span>
                <span class="text-amber-400" id="currentNoteFrequencyText">FRET NOTATION TARGET</span>
            </div>

            <!-- 6-STRING SONGSTERR CANVASTAB SHEET MUSIC -->
            <div class="relative w-full overflow-x-auto">
                <canvas id="songsterrCanvas" width="1100" height="320" class="w-full h-auto block min-w-[900px]"></canvas>
            </div>

        </div>

        <!-- FOOTER TIP & REAL GUITAR AUDIO LISTENER STATUS -->
        <div class="bg-zinc-950/80 border border-white/10 rounded-2xl p-4 flex flex-wrap items-center justify-between gap-4 text-xs">
            <div class="flex items-center gap-3 text-gray-300 font-medium">
                <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-400 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-headphones"></i>
                </div>
                <span>Pluck along with the playhead cursor on your real guitar string by string!</span>
            </div>

            <div class="flex items-center gap-2 font-mono font-bold text-amber-400">
                <i class="fa-solid fa-circle text-[8px] animate-pulse"></i>
                <span>SONGSTERR PLAYHEAD ENGINE READY</span>
            </div>
        </div>

    </main>
</div>

<!-- SONGSTERR TAB PLAYER ENGINE JS -->
<script>
    const canvas = document.getElementById('songsterrCanvas');
    const ctx = canvas.getContext('2d');

    const stringNames = ["e", "B", "G", "D", "A", "E"];

    const dbSongTabs = @json($songTabs ?? []);
    const songsDatabase = (dbSongTabs && dbSongTabs.length > 0) ? dbSongTabs.map(item => ({
        title: item.artist + ' - ' + item.title,
        artist: item.artist,
        bpm: item.bpm,
        trackName: item.track_name,
        audioUrl: item.audio_url,
        measures: (typeof item.tab_data === 'string' ? JSON.parse(item.tab_data) : item.tab_data) || [
            [
                { string: 3, fret: "12", note: "D", freq: 146.83, beat: 0 },
                { string: 1, fret: "15", note: "D", freq: 293.66, beat: 1 },
                { string: 2, fret: "14", note: "A", freq: 220.00, beat: 2 },
                { string: 2, fret: "12", note: "G", freq: 196.00, beat: 3 }
            ]
        ]
    })) : [
        {
            title: "Black Label Society - In This River (Live Lead Solo)",
            artist: "Black Label Society",
            bpm: 72,
            trackName: "Electric Guitar Clean (Zakk Wylde Solo)",
            audioUrl: null,
            measures: [
                [
                    { string: 2, fret: "9", note: "E", freq: 164.81, beat: 0 },
                    { string: 2, fret: "11", note: "F#", freq: 185.00, beat: 1 },
                    { string: 1, fret: "9", note: "C#", freq: 277.18, beat: 2 },
                    { string: 1, fret: "12", note: "E", freq: 329.63, beat: 3 }
                ]
            ]
        }
    ];

    let currentSongIndex = 0;
    let isPlaying = false;
    let speedRate = 1.0;
    let isMetronomeOn = false;
    let isLoopOn = false;

    let currentMeasure = 0;
    let currentBeat = 0;
    let playheadX = 140;
    let animationTimer = null;

    function loadSongsterrTrack(index) {
        currentSongIndex = parseInt(index);
        const song = songsDatabase[currentSongIndex];
        
        document.getElementById('songTitleDisplay').textContent = song.title;
        document.getElementById('songBpmDisplay').textContent = song.bpm + ' BPM • 4/4';
        document.getElementById('activeTrackName').textContent = song.trackName;
        
        currentMeasure = 0;
        currentBeat = 0;
        playheadX = 140;
        drawSongsterrSheet();
    }

    function setSongsterrSpeed(speed) {
        speedRate = speed;
        document.getElementById('speed05').className = speed === 0.5 ? 'songsterr-btn active px-3 py-1.5 rounded-lg text-xs font-bold' : 'songsterr-btn px-3 py-1.5 rounded-lg text-xs font-bold text-gray-300';
        document.getElementById('speed075').className = speed === 0.75 ? 'songsterr-btn active px-3 py-1.5 rounded-lg text-xs font-bold' : 'songsterr-btn px-3 py-1.5 rounded-lg text-xs font-bold text-gray-300';
        document.getElementById('speed10').className = speed === 1.0 ? 'songsterr-btn active px-3 py-1.5 rounded-lg text-xs font-bold' : 'songsterr-btn px-3 py-1.5 rounded-lg text-xs font-bold text-gray-300';
        document.getElementById('speed125').className = speed === 1.25 ? 'songsterr-btn active px-3 py-1.5 rounded-lg text-xs font-bold' : 'songsterr-btn px-3 py-1.5 rounded-lg text-xs font-bold text-gray-300';
    }

    function toggleMetronome() {
        isMetronomeOn = !isMetronomeOn;
        document.getElementById('btnMetronome').className = isMetronomeOn ? 'songsterr-btn active px-3 py-1.5 rounded-lg text-xs font-bold flex items-center gap-1.5' : 'songsterr-btn px-3 py-1.5 rounded-lg text-xs font-bold text-gray-300 flex items-center gap-1.5';
    }

    function toggleLoop() {
        isLoopOn = !isLoopOn;
        document.getElementById('btnLoopRegion').className = isLoopOn ? 'songsterr-btn active px-3 py-1.5 rounded-lg text-xs font-bold flex items-center gap-1.5' : 'songsterr-btn px-3 py-1.5 rounded-lg text-xs font-bold text-gray-300 flex items-center gap-1.5';
    }

    let activeAudioElement = null;

    function toggleSongsterrPlay() {
        isPlaying = !isPlaying;
        const icon = document.getElementById('playIcon');
        const btn = document.getElementById('btnPlayPause');
        const song = songsDatabase[currentSongIndex];

        if (isPlaying) {
            icon.className = 'fa-solid fa-pause ml-0';
            btn.className = 'w-12 h-12 rounded-xl bg-amber-400 hover:bg-amber-300 text-black font-extrabold text-xl flex items-center justify-center transition shadow-lg shadow-amber-500/30 cursor-pointer';

            if (song && song.audioUrl) {
                if (!activeAudioElement || activeAudioElement.src !== song.audioUrl) {
                    activeAudioElement = new Audio(song.audioUrl);
                }
                activeAudioElement.playbackRate = speedRate;
                activeAudioElement.play().catch(e => console.log('Audio play error:', e));
            }

            startPlaybackLoop();
        } else {
            icon.className = 'fa-solid fa-play ml-0.5';
            btn.className = 'w-12 h-12 rounded-xl bg-amber-500 hover:bg-amber-400 text-black font-extrabold text-xl flex items-center justify-center transition shadow-lg shadow-amber-500/20 cursor-pointer';
            
            if (activeAudioElement) {
                activeAudioElement.pause();
            }
            if (animationTimer) clearInterval(animationTimer);
        }
    }

    function startPlaybackLoop() {
        if (animationTimer) clearInterval(animationTimer);
        const song = songsDatabase[currentSongIndex];
        const stepInterval = Math.round((60000 / song.bpm) / (4 * speedRate));

        animationTimer = setInterval(() => {
            if (!isPlaying) return;

            advancePlayhead();
            drawSongsterrSheet();
        }, stepInterval);
    }

    function advancePlayhead() {
        const song = songsDatabase[currentSongIndex];
        const measureData = song.measures[currentMeasure];

        if (!measureData) return;

        playheadX += 18;

        // Check if playhead reached end of current measure (measure width = 220px)
        const measureStartX = 140 + currentMeasure * 220;
        if (playheadX > measureStartX + 200) {
            currentMeasure++;
            if (currentMeasure >= song.measures.length) {
                if (isLoopOn) {
                    currentMeasure = 0;
                    playheadX = 140;
                } else {
                    toggleSongsterrPlay();
                    currentMeasure = 0;
                    playheadX = 140;
                }
            }
        }

        // Metronome Click Effect
        if (isMetronomeOn && Math.floor((playheadX - measureStartX) / 50) % 1 === 0) {
            playClickSound();
        }

        // Play Synth Note Audio Sound when playhead hits a fret badge
        const activeNotes = song.measures[currentMeasure] || [];
        activeNotes.forEach(noteItem => {
            const targetX = measureStartX + noteItem.beat * 50 + 20;
            if (Math.abs(playheadX - targetX) < 10) {
                playAudioSynthPitch(noteItem.freq);
                document.getElementById('currentNoteFrequencyText').textContent = 'CURRENT NOTE: ' + noteItem.note + ' (STRING ' + (noteItem.string + 1) + ' FRET ' + noteItem.fret + ')';
            }
        });

        document.getElementById('measureCounter').textContent = 'MEASURE ' + (currentMeasure + 1) + ' / ' + song.measures.length;
    }

    function drawSongsterrSheet() {
        const W = canvas.width;
        const H = canvas.height;

        // Songsterr Clean Dark Background
        ctx.fillStyle = '#0d0d11';
        ctx.fillRect(0, 0, W, H);

        const lineSpacing = 36;
        const startY = 60;
        const startX = 120;
        const measureW = 220;
        const song = songsDatabase[currentSongIndex];

        // Draw 6 Horizontal TAB Strings (e, B, G, D, A, E)
        for (let i = 0; i < 6; i++) {
            const y = startY + i * lineSpacing;

            // String line
            ctx.strokeStyle = 'rgba(255, 255, 255, 0.22)';
            ctx.lineWidth = i >= 3 ? 2.5 : 1.5;
            ctx.beginPath();
            ctx.moveTo(startX - 40, y);
            ctx.lineTo(W - 40, y);
            ctx.stroke();

            // String Names (Songsterr Font Style)
            ctx.fillStyle = '#94A3B8';
            ctx.font = 'bold 14px "Roboto Mono", monospace';
            ctx.textAlign = 'right';
            ctx.fillText(stringNames[i], startX - 55, y + 4);
        }

        // Draw Measure Bar Lines (Vertical)
        for (let m = 0; m <= song.measures.length; m++) {
            const mx = startX + m * measureW;
            ctx.strokeStyle = 'rgba(255, 255, 255, 0.4)';
            ctx.lineWidth = m === 0 ? 4 : 2;
            ctx.beginPath();
            ctx.moveTo(mx, startY - 10);
            ctx.lineTo(mx, startY + 5 * lineSpacing + 10);
            ctx.stroke();

            if (m < song.measures.length) {
                ctx.fillStyle = '#64748B';
                ctx.font = 'bold 11px "Plus Jakarta Sans", sans-serif';
                ctx.textAlign = 'left';
                ctx.fillText('M' + (m + 1), mx + 10, startY - 20);
            }
        }

        // Draw Fret Numbers per Measure
        song.measures.forEach((measureNotes, mIdx) => {
            const mX = startX + mIdx * measureW;

            measureNotes.forEach(item => {
                const x = mX + item.beat * 50 + 25;
                const y = startY + item.string * lineSpacing;

                // Songsterr Fret Number Circle Badge
                ctx.beginPath();
                ctx.arc(x, y, 15, 0, Math.PI * 2);
                ctx.fillStyle = '#181822';
                ctx.fill();
                ctx.lineWidth = 2;
                ctx.strokeStyle = '#F59E0B';
                ctx.stroke();

                // Fret Number Text
                ctx.fillStyle = '#FFFFFF';
                ctx.font = 'black 14px "Roboto Mono", monospace';
                ctx.textAlign = 'center';
                ctx.fillText(item.fret, x, y + 4.5);
            });
        });

        // Draw Moving Red/Amber Songsterr Playhead Bar
        ctx.strokeStyle = '#F59E0B';
        ctx.lineWidth = 3;
        ctx.beginPath();
        ctx.moveTo(playheadX, startY - 15);
        ctx.lineTo(playheadX, startY + 5 * lineSpacing + 15);
        ctx.stroke();

        // Top Playhead Diamond/Arrow Indicator
        ctx.fillStyle = '#F59E0B';
        ctx.beginPath();
        ctx.moveTo(playheadX - 6, startY - 18);
        ctx.lineTo(playheadX + 6, startY - 18);
        ctx.lineTo(playheadX, startY - 10);
        ctx.closePath();
        ctx.fill();
    }

    function playAudioSynthPitch(freq) {
        try {
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.type = 'triangle';
            osc.frequency.setValueAtTime(freq, audioCtx.currentTime);
            gain.gain.setValueAtTime(0.2, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.35);
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.start();
            osc.stop(audioCtx.currentTime + 0.35);
        } catch(e){}
    }

    function playClickSound() {
        try {
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(1000, audioCtx.currentTime);
            gain.gain.setValueAtTime(0.1, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.05);
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.start();
            osc.stop(audioCtx.currentTime + 0.05);
        } catch(e){}
    }

    // Initial load
    drawSongsterrSheet();
</script>
@endsection
