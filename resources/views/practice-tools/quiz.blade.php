@extends('layouts.app')

@section('title', 'Interactive Guitar Pitch Quiz & Rank Challenge')

@push('head')
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
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
                            card: 'rgba(18, 18, 24, 0.65)',
                            border: 'rgba(255, 255, 255, 0.08)',
                            amber: '#f59e0b',
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
        body > nav { display: none !important; }
        .tw-dash {
            background-color: #08080a !important;
            color: #f3f4f6 !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
        }
        .tw-dash .font-display {
            font-family: 'Bebas Neue', cursive;
            letter-spacing: 1.5px;
        }
        .glass-panel {
            background: rgba(18, 18, 24, 0.65);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 1.5rem;
        }
        .target-note-glow {
            text-shadow: 0 0 30px rgba(245, 158, 11, 0.6);
        }
    </style>
@endpush

@section('content')
@php
    $isEn = (session('app_lang', request('lang', 'id')) === 'en');
@endphp
<div class="tw-dash min-h-screen flex flex-col antialiased bg-[#08080a] text-gray-200 relative overflow-hidden"
     x-data="{ mobileMenuOpen: false }">

    {{-- Ambient Mesh Glow Background --}}
    <div class="absolute -top-32 left-1/3 w-[600px] h-[600px] bg-amber-500/10 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute top-1/2 -right-32 w-[450px] h-[450px] bg-indigo-600/10 rounded-full blur-[120px] pointer-events-none"></div>

    {{-- TOP NAVIGATION BAR --}}
    @include('layouts.lms_header')

    <main class="flex-1 w-full max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative z-10 space-y-8">
        
        <!-- Header & Rank Badge Summary -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <a href="{{ route('practice.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-gray-400 hover:text-white transition mb-2">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>{{ $isEn ? 'Back to Practice Tools' : 'Kembali ke Tools Latihan' }}</span>
                </a>
                <h1 class="font-display text-3xl sm:text-5xl text-white tracking-wider">
                    LIVE GUITAR <span class="text-amber-400">PITCH QUIZ</span> 🎸
                </h1>
                <p class="text-gray-400 text-xs sm:text-sm mt-1">
                    {{ $isEn ? 'Plug in your guitar or use your mic. Play the correct note to earn XP & push your rank!' : 'Tancapkan gitar atau gunakan mikrofon. Mainkan nada yang tepat untuk mendapat XP & naikkan rank!' }}
                </p>
            </div>

            <!-- Current User Rank Pill -->
            <div class="bg-zinc-900/80 border border-amber-500/30 rounded-2xl p-4 flex items-center gap-3 shadow-lg">
                <div class="w-12 h-12 rounded-xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-400 text-2xl">
                    <i class="fa-solid {{ auth()->user()->guitar_rank['icon'] }}"></i>
                </div>
                <div>
                    <span class="text-[10px] text-gray-400 uppercase font-bold tracking-wider block">YOUR RANK</span>
                    <h3 class="font-bold text-white text-sm" id="userRankTitle">{{ auth()->user()->guitar_rank['name'] }}</h3>
                    <span class="text-xs font-semibold text-amber-400 font-mono" id="userXpDisplay">{{ auth()->user()->xp ?? 0 }} XP</span>
                </div>
            </div>
        </div>

        <!-- MAIN QUIZ GAME ARENA CARD -->
        <div class="glass-panel p-6 sm:p-10 text-center relative overflow-hidden space-y-8">
            
            <!-- Audio Input Control Banner -->
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-white/10 pb-4">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-red-500 animate-pulse" id="micStatusDot"></span>
                    <span class="text-xs font-bold text-gray-300" id="micStatusText">Microphone / Line-In Off</span>
                </div>

                <button id="btnToggleMic" onclick="toggleAudioInput()" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-black font-extrabold text-xs transition shadow-lg shadow-amber-500/20 border border-amber-400/40 cursor-pointer">
                    <i class="fa-solid fa-microphone me-1.5"></i>
                    <span>START QUIZ & MIC</span>
                </button>
            </div>

            <!-- Quiz Target Note Challenge Arena -->
            <div class="space-y-4 py-4">
                <span class="inline-block px-3 py-1 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20 text-xs font-bold uppercase tracking-wider">
                    TARGET GUITAR NOTE
                </span>

                <div class="font-display text-8xl sm:text-9xl text-amber-400 target-note-glow font-black tracking-wider transition-transform duration-200" id="targetNoteDisplay">
                    E
                </div>

                <p class="text-sm font-semibold text-gray-300" id="targetNoteHint">
                    Hint: Low E String (6th String Open) — Frequency ~82 Hz
                </p>
            </div>

            <!-- Real-Time Pitch Feedback Gauge -->
            <div class="max-w-md mx-auto bg-zinc-950/80 border border-white/10 rounded-2xl p-5 space-y-3">
                <div class="flex items-center justify-between text-xs font-semibold text-gray-400">
                    <span>DETECTED NOTE: <strong class="text-white text-sm" id="detectedNoteDisplay">-</strong></span>
                    <span>PITCH FREQ: <strong class="text-amber-400 font-mono" id="detectedFreqDisplay">0 Hz</strong></span>
                </div>

                <!-- Dynamic Pitch Bar -->
                <div class="w-full bg-zinc-900 rounded-full h-3 overflow-hidden border border-white/5 relative">
                    <div class="bg-amber-500 h-full w-0 transition-all duration-100 rounded-full" id="pitchMatchBar"></div>
                </div>

                <span class="text-[11px] font-bold text-gray-400 block" id="quizFeedbackMessage">
                    Click "START QUIZ & MIC" or tap any note button below to answer!
                </span>
            </div>

            <!-- Interactive Virtual Note Buttons Grid (Instant Playable Mode) -->
            <div class="space-y-3 pt-2">
                <span class="text-xs font-bold text-amber-400/90 uppercase tracking-wider block">
                    🎸 OR ANSWER BY TAPPING NOTE (VIRTUAL GUITAR MODE)
                </span>
                <div class="grid grid-cols-4 sm:grid-cols-6 gap-2 max-w-md mx-auto">
                    <button onclick="submitVirtualNote('C')" class="p-3 rounded-xl bg-zinc-900/90 hover:bg-amber-500/20 border border-white/10 hover:border-amber-500/40 text-white font-extrabold text-sm transition shadow cursor-pointer">C</button>
                    <button onclick="submitVirtualNote('C#')" class="p-3 rounded-xl bg-zinc-900/90 hover:bg-amber-500/20 border border-white/10 hover:border-amber-500/40 text-white font-extrabold text-sm transition shadow cursor-pointer">C#</button>
                    <button onclick="submitVirtualNote('D')" class="p-3 rounded-xl bg-zinc-900/90 hover:bg-amber-500/20 border border-white/10 hover:border-amber-500/40 text-white font-extrabold text-sm transition shadow cursor-pointer">D</button>
                    <button onclick="submitVirtualNote('D#')" class="p-3 rounded-xl bg-zinc-900/90 hover:bg-amber-500/20 border border-white/10 hover:border-amber-500/40 text-white font-extrabold text-sm transition shadow cursor-pointer">D#</button>
                    <button onclick="submitVirtualNote('E')" class="p-3 rounded-xl bg-zinc-900/90 hover:bg-amber-500/20 border border-white/10 hover:border-amber-500/40 text-white font-extrabold text-sm transition shadow cursor-pointer">E</button>
                    <button onclick="submitVirtualNote('F')" class="p-3 rounded-xl bg-zinc-900/90 hover:bg-amber-500/20 border border-white/10 hover:border-amber-500/40 text-white font-extrabold text-sm transition shadow cursor-pointer">F</button>
                    <button onclick="submitVirtualNote('F#')" class="p-3 rounded-xl bg-zinc-900/90 hover:bg-amber-500/20 border border-white/10 hover:border-amber-500/40 text-white font-extrabold text-sm transition shadow cursor-pointer">F#</button>
                    <button onclick="submitVirtualNote('G')" class="p-3 rounded-xl bg-zinc-900/90 hover:bg-amber-500/20 border border-white/10 hover:border-amber-500/40 text-white font-extrabold text-sm transition shadow cursor-pointer">G</button>
                    <button onclick="submitVirtualNote('G#')" class="p-3 rounded-xl bg-zinc-900/90 hover:bg-amber-500/20 border border-white/10 hover:border-amber-500/40 text-white font-extrabold text-sm transition shadow cursor-pointer">G#</button>
                    <button onclick="submitVirtualNote('A')" class="p-3 rounded-xl bg-zinc-900/90 hover:bg-amber-500/20 border border-white/10 hover:border-amber-500/40 text-white font-extrabold text-sm transition shadow cursor-pointer">A</button>
                    <button onclick="submitVirtualNote('A#')" class="p-3 rounded-xl bg-zinc-900/90 hover:bg-amber-500/20 border border-white/10 hover:border-amber-500/40 text-white font-extrabold text-sm transition shadow cursor-pointer">A#</button>
                    <button onclick="submitVirtualNote('B')" class="p-3 rounded-xl bg-zinc-900/90 hover:bg-amber-500/20 border border-white/10 hover:border-amber-500/40 text-white font-extrabold text-sm transition shadow cursor-pointer">B</button>
                </div>
            </div>

            <!-- Score & Streak Footer -->
            <div class="grid grid-cols-3 gap-4 pt-4 border-t border-white/10 max-w-lg mx-auto">
                <div class="bg-white/5 rounded-xl p-3">
                    <span class="block text-[10px] text-gray-400 font-bold uppercase">SCORE</span>
                    <span class="text-xl font-extrabold text-white" id="quizScore">0</span>
                </div>
                <div class="bg-white/5 rounded-xl p-3">
                    <span class="block text-[10px] text-gray-400 font-bold uppercase">STREAK</span>
                    <span class="text-xl font-extrabold text-amber-400" id="quizStreak">🔥 0</span>
                </div>
                <div class="bg-white/5 rounded-xl p-3">
                    <span class="block text-[10px] text-gray-400 font-bold uppercase">XP EARNED</span>
                    <span class="text-xl font-extrabold text-emerald-400" id="sessionXp">+0 XP</span>
                </div>
            </div>

        </div>

    </main>
</div>

<!-- AUDIO & PITCH DETECTION ENGINE JS -->
<script>
    const noteStrings = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"];
    
    // Preset guitar target notes
    const quizTargets = [
        { note: "E", freq: 82.41, hint: "Low E String (6th String Open)" },
        { note: "A", freq: 110.00, hint: "A String (5th String Open)" },
        { note: "D", freq: 146.83, hint: "D String (4th String Open)" },
        { note: "G", freq: 196.00, hint: "G String (3rd String Open)" },
        { note: "B", freq: 246.94, hint: "B String (2nd String Open)" },
        { note: "E", freq: 329.63, hint: "High E String (1st String Open)" },
        { note: "A", freq: 220.00, hint: "A Note (3rd String 2nd Fret)" },
        { note: "C", freq: 130.81, hint: "C Note (5th String 3rd Fret)" },
        { note: "G", freq: 98.00, hint: "G Note (6th String 3rd Fret)" }
    ];

    let audioContext = null;
    let analyser = null;
    let microphone = null;
    let isListening = false;
    let currentTargetIndex = 0;
    let quizScore = 0;
    let quizStreak = 0;
    let sessionXp = 0;
    let successCooldown = false;

    function selectNewTargetNote() {
        const randomIndex = Math.floor(Math.random() * quizTargets.length);
        currentTargetIndex = randomIndex;
        const target = quizTargets[currentTargetIndex];
        
        document.getElementById('targetNoteDisplay').textContent = target.note;
        document.getElementById('targetNoteHint').textContent = 'Hint: ' + target.hint;
        
        // Pulse effect
        const el = document.getElementById('targetNoteDisplay');
        el.classList.add('scale-110');
        setTimeout(() => el.classList.remove('scale-110'), 200);
    }

    async function toggleAudioInput() {
        if (isListening) {
            stopAudioInput();
            return;
        }

        try {
            audioContext = new (window.AudioContext || window.webkitAudioContext)();
            if (audioContext.state === 'suspended') {
                await audioContext.resume();
            }

            let stream;
            try {
                stream = await navigator.mediaDevices.getUserMedia({ audio: { echoCancellation: false, noiseSuppression: false } });
            } catch(e1) {
                stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            }

            microphone = audioContext.createMediaStreamSource(stream);
            analyser = audioContext.createAnalyser();
            analyser.fftSize = 2048;
            microphone.connect(analyser);

            isListening = true;
            closeMicModal();
            document.getElementById('micStatusDot').className = 'w-3 h-3 rounded-full bg-emerald-500 animate-pulse';
            document.getElementById('micStatusText').textContent = 'Microphone / Line-In ACTIVE';
            document.getElementById('btnToggleMic').innerHTML = '<i class="fa-solid fa-stop me-1.5"></i> STOP QUIZ';
            document.getElementById('quizFeedbackMessage').textContent = 'Pluck your guitar string now!';

            selectNewTargetNote();
            updatePitchLoop();
        } catch (err) {
            console.error('Mic Error:', err);
            showMicPermissionModal();
        }
    }

    function showMicPermissionModal() {
        const modal = document.getElementById('micPermissionModal');
        if (modal) modal.classList.remove('hidden');
    }

    function closeMicModal() {
        const modal = document.getElementById('micPermissionModal');
        if (modal) modal.classList.add('hidden');
    }

    function stopAudioInput() {
        isListening = false;
        if (audioContext) {
            audioContext.close();
        }
        document.getElementById('micStatusDot').className = 'w-3 h-3 rounded-full bg-red-500';
        document.getElementById('micStatusText').textContent = 'Microphone / Line-In Off';
        document.getElementById('btnToggleMic').innerHTML = '<i class="fa-solid fa-microphone me-1.5"></i> START QUIZ & MIC';
        document.getElementById('quizFeedbackMessage').textContent = 'Quiz stopped.';
    }

    function autoCorrelate(buf, sampleRate) {
        const SIZE = buf.length;
        let rms = 0;

        for (let i = 0; i < SIZE; i++) {
            const val = buf[i];
            rms += val * val;
        }
        rms = Math.sqrt(rms / SIZE);
        if (rms < 0.015) return -1; // Too quiet signal

        let r1 = 0, r2 = SIZE - 1, thres = 0.2;
        for (let i = 0; i < SIZE / 2; i++) {
            if (Math.abs(buf[i]) < thres) { r1 = i; break; }
        }
        for (let i = 1; i < SIZE / 2; i++) {
            if (Math.abs(buf[SIZE - i]) < thres) { r2 = SIZE - i; break; }
        }

        buf = buf.slice(r1, r2);
        const c = new Array(buf.length).fill(0);
        for (let i = 0; i < buf.length; i++) {
            for (let j = 0; j < buf.length - i; j++) {
                c[i] = c[i] + buf[j] * buf[j + i];
            }
        }

        let d = 0; while (c[d] > c[d + 1]) d++;
        let maxval = -1, maxpos = -1;
        for (let i = d; i < buf.length; i++) {
            if (c[i] > maxval) {
                maxval = c[i];
                maxpos = i;
            }
        }
        let T0 = maxpos;

        const x1 = c[T0 - 1], x2 = c[T0], x3 = c[T0 + 1];
        const a = (x1 + x3 - 2 * x2) / 2;
        const b = (x3 - x1) / 2;
        if (a) T0 = T0 - b / (2 * a);

        return sampleRate / T0;
    }

    function noteFromPitch(frequency) {
        const noteNum = 12 * (Math.log(frequency / 440) / Math.log(2));
        return Math.round(noteNum) + 69;
    }

    function updatePitchLoop() {
        if (!isListening) return;

        const buf = new Float32Array(2048);
        analyser.getFloatTimeDomainData(buf);
        const pitch = autoCorrelate(buf, audioContext.sampleRate);

        if (pitch !== -1 && pitch > 50 && pitch < 1000) {
            const noteNum = noteFromPitch(pitch);
            const detectedNote = noteStrings[noteNum % 12];
            const target = quizTargets[currentTargetIndex];

            document.getElementById('detectedNoteDisplay').textContent = detectedNote;
            document.getElementById('detectedFreqDisplay').textContent = Math.round(pitch) + ' Hz';

            // Calculate pitch accuracy match
            const freqDiff = Math.abs(pitch - target.freq);
            const matchPercentage = Math.max(0, Math.min(100, 100 - (freqDiff * 2)));
            document.getElementById('pitchMatchBar').style.width = matchPercentage + '%';

            // Check if detected note matches target note
            if (detectedNote === target.note && !successCooldown) {
                onCorrectNotePlayed();
            }
        } else {
            document.getElementById('detectedFreqDisplay').textContent = '0 Hz';
            document.getElementById('pitchMatchBar').style.width = '0%';
        }

        requestAnimationFrame(updatePitchLoop);
    }

    function onCorrectNotePlayed() {
        successCooldown = true;
        quizScore += 100;
        quizStreak += 1;
        sessionXp += 25;

        document.getElementById('quizScore').textContent = quizScore;
        document.getElementById('quizStreak').textContent = '🔥 ' + quizStreak;
        document.getElementById('sessionXp').textContent = '+' + sessionXp + ' XP';

        document.getElementById('quizFeedbackMessage').innerHTML = '<span class="text-emerald-400 font-bold">🎉 PERFECT HIT! +25 XP</span>';

        // Fire Confetti
        try {
            confetti({ particleCount: 50, spread: 60, origin: { y: 0.7 } });
        } catch(e){}

        // Send XP to backend
        claimXpToBackend(25);

        // Advance to next target note after 1.5 seconds
        setTimeout(() => {
            selectNewTargetNote();
            successCooldown = false;
            document.getElementById('quizFeedbackMessage').textContent = 'Pluck your guitar string or tap note button!';
        }, 1500);
    }

    function submitVirtualNote(selectedNote) {
        if (successCooldown) return;

        const target = quizTargets[currentTargetIndex];
        document.getElementById('detectedNoteDisplay').textContent = selectedNote;
        document.getElementById('detectedFreqDisplay').textContent = Math.round(target.freq) + ' Hz';
        document.getElementById('pitchMatchBar').style.width = '100%';

        playAudioPitchTone(target.freq);

        if (selectedNote === target.note) {
            onCorrectNotePlayed();
        } else {
            quizStreak = 0;
            document.getElementById('quizStreak').textContent = '🔥 0';
            document.getElementById('quizFeedbackMessage').innerHTML = '<span class="text-red-400 font-bold">❌ WRONG NOTE! Target was ' + target.note + '. Try again!</span>';
        }
    }

    function playAudioPitchTone(freq) {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = 'triangle';
            osc.frequency.setValueAtTime(freq, ctx.currentTime);
            gain.gain.setValueAtTime(0.2, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.5);
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start();
            osc.stop(ctx.currentTime + 0.5);
        } catch(e){}
    }

    function claimXpToBackend(amount) {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        fetch('{{ route("practice.claimXp") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ amount: amount })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('userXpDisplay').textContent = data.xp + ' XP';
                if (data.rank && data.rank.name) {
                    document.getElementById('userRankTitle').textContent = data.rank.name;
                }
            }
        })
        .catch(e => console.error(e));
    }
</script>

<!-- Mic Permission Instructions Modal -->
<div id="micPermissionModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/90 backdrop-blur-xl transition-all duration-300 hidden">
    <div class="bg-zinc-950 border border-amber-500/30 rounded-3xl p-6 sm:p-8 max-w-lg w-full text-center relative overflow-hidden shadow-[0_0_50px_rgba(245,158,11,0.2)] space-y-6">
        
        <div class="w-16 h-16 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-400 flex items-center justify-center text-3xl mx-auto">
            <i class="fa-solid fa-microphone-slash"></i>
        </div>

        <div class="space-y-2">
            <h2 class="font-display text-3xl text-white tracking-wide">MICROPHONE ACCESS REQUIRED</h2>
            <p class="text-xs text-gray-300 leading-relaxed max-w-sm mx-auto">
                Browser kamu saat ini menolak izin Mikrofon / Line-In. Ikuti 2 langkah mudah ini untuk mengizinkan:
            </p>
        </div>

        <!-- Step-by-Step Instructions Card -->
        <div class="bg-zinc-900/80 border border-white/10 rounded-2xl p-4 text-left space-y-3 text-xs">
            <div class="flex items-start gap-3">
                <span class="w-6 h-6 rounded-full bg-amber-500/20 text-amber-400 font-bold flex items-center justify-center text-xs shrink-0">1</span>
                <p class="text-gray-300">
                    Klik ikon <strong>🔒 Gembok / Tune</strong> di sebelah kiri alamat URL website (<code class="text-amber-400">localhost:8000</code>).
                </p>
            </div>
            <div class="flex items-start gap-3">
                <span class="w-6 h-6 rounded-full bg-amber-500/20 text-amber-400 font-bold flex items-center justify-center text-xs shrink-0">2</span>
                <p class="text-gray-300">
                    Ubah setelan <strong>Microphone</strong> dari <em>Block</em> menjadi <strong>Allow / Izinkan</strong>.
                </p>
            </div>
            <div class="flex items-start gap-3">
                <span class="w-6 h-6 rounded-full bg-amber-500/20 text-amber-400 font-bold flex items-center justify-center text-xs shrink-0">3</span>
                <p class="text-gray-300">
                    Refresh halaman ini dan tekan tombol <strong>START QUIZ</strong> kembali!
                </p>
            </div>
        </div>

        <div class="flex items-center justify-center gap-3">
            <button onclick="location.reload()" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-black font-extrabold text-xs transition shadow-lg cursor-pointer">
                <i class="fa-solid fa-rotate-right me-1"></i> Refresh Halaman
            </button>
            <button onclick="closeMicModal()" class="px-5 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-xs font-semibold text-gray-300 cursor-pointer">
                Tutup
            </button>
        </div>

    </div>
</div>
@endsection
