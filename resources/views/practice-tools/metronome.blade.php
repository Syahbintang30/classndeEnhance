@extends('layouts.app')

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
                            card: 'rgba(18, 18, 24, 0.65)',
                            border: 'rgba(255, 255, 255, 0.08)',
                            emerald: '#10b981'
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
        .glass-panel {
            background: rgba(18, 18, 26, 0.55);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 1.5rem;
        }
        body > nav { display: none !important; }
        
        input[type=range] {
            -webkit-appearance: none;
            width: 100%;
            background: transparent;
        }
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none;
            height: 24px;
            width: 24px;
            border-radius: 50%;
            background: #10b981;
            cursor: pointer;
            margin-top: -8px;
            box-shadow: 0 0 15px rgba(16, 185, 129, 0.8);
            border: 2px solid #ffffff;
        }
        input[type=range]::-webkit-slider-runnable-track {
            width: 100%;
            height: 8px;
            cursor: pointer;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }
    </style>
@endpush

@section('content')
@php
    $isEn = (session('app_lang', request('lang', 'id')) === 'en');
@endphp
<div class="tw-dash min-h-screen flex flex-col antialiased bg-[#08080a] text-gray-200 relative overflow-hidden" 
     x-data="metronomeApp()" 
     x-init="initMetronome()">

    {{-- Ambient Mesh Background Glow --}}
    <div class="absolute -top-32 left-1/3 w-[600px] h-[600px] bg-emerald-500/10 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute top-1/2 -right-32 w-[450px] h-[450px] bg-teal-600/10 rounded-full blur-[120px] pointer-events-none"></div>

    {{-- ─── TOP NAVIGATION BAR ──────────────────────────────────────────── --}}
    @include('layouts.lms_header')

    <main class="flex-1 max-w-2xl mx-auto w-full px-4 lg:px-8 py-8 space-y-8 relative z-10">
        
        <!-- BACK & TITLE HEADER -->
        <div class="flex items-center justify-between gap-4">
            <a href="{{ route('practice.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-white bg-zinc-950/60 border border-white/10 px-4 py-2 rounded-xl backdrop-blur-md transition">
                <i class="fa-solid fa-arrow-left"></i> {{ $isEn ? 'Back to Practice Tools' : 'Kembali ke Tools Latihan' }}
            </a>
            
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold uppercase tracking-wider">
                <i class="fa-solid fa-stopwatch"></i> {{ $isEn ? 'Precision Rhythm Engine' : 'Mesin Tempo Ritme Presisi' }}
            </div>
        </div>

        <!-- MAIN METRONOME GLASS CARD -->
        <div class="glass-panel p-6 sm:p-8 relative overflow-hidden text-center space-y-8">
            
            <div class="space-y-1">
                <h1 class="font-display text-4xl sm:text-5xl text-white tracking-wide uppercase">
                    Precision <span class="text-emerald-400">Metronome</span>
                </h1>
                <p class="text-gray-400 text-xs max-w-sm mx-auto">
                    {{ $isEn ? 'Master your timing with Web Audio sample-accurate ticking.' : 'Latih ketukan ritme gitar kamu secara presisi dan teratur.' }}
                </p>
            </div>

            <!-- TEMPO PRESETS PILLS -->
            <div class="space-y-2">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Tempo Marking Presets</span>
                <div class="flex flex-wrap justify-center gap-2">
                    <button @click="setPreset(50, 'Largo')" :class="tempoName === 'Largo' ? 'bg-emerald-500 text-black font-bold border-emerald-400' : 'bg-zinc-950/60 text-gray-400 hover:text-white border-white/5'" class="px-3 py-1.5 rounded-full border text-xs transition">Largo (50)</button>
                    <button @click="setPreset(75, 'Adagio')" :class="tempoName === 'Adagio' ? 'bg-emerald-500 text-black font-bold border-emerald-400' : 'bg-zinc-950/60 text-gray-400 hover:text-white border-white/5'" class="px-3 py-1.5 rounded-full border text-xs transition">Adagio (75)</button>
                    <button @click="setPreset(92, 'Andante')" :class="tempoName === 'Andante' ? 'bg-emerald-500 text-black font-bold border-emerald-400' : 'bg-zinc-950/60 text-gray-400 hover:text-white border-white/5'" class="px-3 py-1.5 rounded-full border text-xs transition">Andante (92)</button>
                    <button @click="setPreset(114, 'Moderato')" :class="tempoName === 'Moderato' ? 'bg-emerald-500 text-black font-bold border-emerald-400' : 'bg-zinc-950/60 text-gray-400 hover:text-white border-white/5'" class="px-3 py-1.5 rounded-full border text-xs transition">Moderato (114)</button>
                    <button @click="setPreset(132, 'Allegro')" :class="tempoName === 'Allegro' ? 'bg-emerald-500 text-black font-bold border-emerald-400' : 'bg-zinc-950/60 text-gray-400 hover:text-white border-white/5'" class="px-3 py-1.5 rounded-full border text-xs transition">Allegro (132)</button>
                    <button @click="setPreset(180, 'Presto')" :class="tempoName === 'Presto' ? 'bg-emerald-500 text-black font-bold border-emerald-400' : 'bg-zinc-950/60 text-gray-400 hover:text-white border-white/5'" class="px-3 py-1.5 rounded-full border text-xs transition">Presto (180)</button>
                </div>
            </div>

            <!-- BPM LARGE DISPLAY & PULSER -->
            <div class="relative py-4 flex flex-col items-center justify-center">
                <div class="relative z-10 transition-all duration-100" :class="{'scale-110 text-emerald-400': isBeatPulse}">
                    <span class="font-display text-7xl sm:text-8xl text-white tracking-tight" x-text="bpm">120</span>
                    <span class="font-display text-3xl text-emerald-400 ml-2">BPM</span>
                </div>
                <div class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1" x-text="tempoName || getTempoLabel(bpm)"></div>
            </div>

            <!-- SLIDER & ADJUSTMENT BUTTONS -->
            <div class="bg-zinc-950/60 border border-white/5 rounded-2xl p-5 space-y-4">
                <div class="flex items-center justify-between gap-4">
                    <button @click="bpm = Math.max(30, bpm - 1); updateTempoLabel()" class="w-11 h-11 rounded-xl bg-zinc-900 border border-white/10 hover:border-emerald-500/50 hover:bg-emerald-500/10 text-white font-bold transition flex items-center justify-center">
                        <i class="fa-solid fa-minus"></i>
                    </button>

                    <div class="flex-1 px-2">
                        <input type="range" min="30" max="240" x-model.number="bpm" @input="updateTempoLabel" class="cursor-pointer">
                    </div>

                    <button @click="bpm = Math.min(240, bpm + 1); updateTempoLabel()" class="w-11 h-11 rounded-xl bg-zinc-900 border border-white/10 hover:border-emerald-500/50 hover:bg-emerald-500/10 text-white font-bold transition flex items-center justify-center">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>

                <!-- TAP TEMPO BUTTON -->
                <div class="pt-2 flex justify-center">
                    <button @click="handleTapTempo()" class="px-5 py-2 rounded-xl bg-zinc-900 hover:bg-emerald-500/20 border border-white/10 hover:border-emerald-500/30 text-xs font-bold text-gray-300 hover:text-emerald-400 transition flex items-center gap-2">
                        <i class="fa-solid fa-hand-pointer text-emerald-400"></i>
                        <span>TAP TEMPO</span>
                    </button>
                </div>
            </div>

            <!-- BEAT VISUALIZER DOTS -->
            <div class="space-y-2">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Beat Indicator</span>
                <div class="flex justify-center gap-3 h-10 items-center">
                    <template x-for="i in beatsPerMeasure">
                        <div class="w-5 h-5 rounded-full transition-all duration-100 border"
                             :class="{
                                 'bg-emerald-400 border-emerald-300 shadow-[0_0_20px_rgba(16,185,129,0.9)] scale-125': currentBeat === i && isPlaying && i === 1,
                                 'bg-emerald-500 border-emerald-400 shadow-[0_0_12px_rgba(16,185,129,0.6)] scale-110': currentBeat === i && isPlaying && i !== 1,
                                 'bg-zinc-950 border-white/10': currentBeat !== i || !isPlaying
                             }">
                        </div>
                    </template>
                </div>
            </div>

            <!-- TIME SIGNATURE & SOUND SELECTION -->
            <div class="grid grid-cols-2 gap-4 max-w-sm mx-auto">
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1">Time Signature</label>
                    <select x-model.number="beatsPerMeasure" class="w-full bg-zinc-950 border border-white/10 text-white text-xs font-bold rounded-xl px-3 py-2.5 outline-none focus:border-emerald-500">
                        <option value="2">2/4 (March)</option>
                        <option value="3">3/4 (Waltz)</option>
                        <option value="4">4/4 (Standard)</option>
                        <option value="5">5/4 (Odd Meter)</option>
                        <option value="6">6/8 (Compound)</option>
                    </select>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1">Click Sound</label>
                    <select x-model="soundMode" class="w-full bg-zinc-950 border border-white/10 text-white text-xs font-bold rounded-xl px-3 py-2.5 outline-none focus:border-emerald-500">
                        <option value="digital">Digital Beep</option>
                        <option value="wood">Woodblock</option>
                        <option value="rimshot">Mechanical Rim</option>
                    </select>
                </div>
            </div>

            <!-- PLAY / STOP BUTTON -->
            <div class="pt-2">
                <button @click="togglePlay()" 
                        class="w-24 h-24 rounded-full text-2xl text-white shadow-xl transition-all duration-300 hover:scale-105 active:scale-95 flex items-center justify-center mx-auto"
                        :class="isPlaying ? 'bg-gradient-to-tr from-red-600 to-rose-500 shadow-red-600/30' : 'bg-gradient-to-tr from-emerald-500 to-teal-400 text-black shadow-emerald-500/30'">
                    <i class="fa-solid" :class="isPlaying ? 'fa-stop' : 'fa-play ml-1.5'"></i>
                </button>
            </div>

        </div>
    </main>
</div>

<script>
function metronomeApp() {
    return {
        bpm: 120,
        isPlaying: false,
        beatsPerMeasure: 4,
        currentBeat: 1,
        isBeatPulse: false,
        tempoName: 'Moderato',
        soundMode: 'digital',
        
        tapTimes: [],
        audioContext: null,
        nextNoteTime: 0,
        timerID: null,
        lookahead: 25.0,
        scheduleAheadTime: 0.1,
        
        initMetronome() {
            this.updateTempoLabel();
        },

        setPreset(val, name) {
            this.bpm = val;
            this.tempoName = name;
        },

        getTempoLabel(b) {
            if (b < 60) return 'Largo (Very Slow)';
            if (b < 76) return 'Adagio (Slow)';
            if (b < 108) return 'Andante (Walking Speed)';
            if (b < 120) return 'Moderato (Medium)';
            if (b < 156) return 'Allegro (Fast)';
            if (b < 200) return 'Presto (Very Fast)';
            return 'Prestissimo (Extremely Fast)';
        },

        updateTempoLabel() {
            this.tempoName = this.getTempoLabel(this.bpm);
        },

        handleTapTempo() {
            const now = Date.now();
            if (this.tapTimes.length > 0 && (now - this.tapTimes[this.tapTimes.length - 1] > 2000)) {
                this.tapTimes = [];
            }
            this.tapTimes.push(now);
            if (this.tapTimes.length > 1) {
                const intervals = [];
                for (let i = 1; i < this.tapTimes.length; i++) {
                    intervals.push(this.tapTimes[i] - this.tapTimes[i - 1]);
                }
                const avgInterval = intervals.reduce((a, b) => a + b, 0) / intervals.length;
                const calculatedBpm = Math.round(60000 / avgInterval);
                this.bpm = Math.max(30, Math.min(240, calculatedBpm));
                this.updateTempoLabel();
            }
        },

        nextNote() {
            const secondsPerBeat = 60.0 / this.bpm;
            this.nextNoteTime += secondsPerBeat;
            this.currentBeat++;
            if (this.currentBeat > this.beatsPerMeasure) {
                this.currentBeat = 1;
            }
        },

        playNote(time, isFirstBeat) {
            const osc = this.audioContext.createOscillator();
            const envelope = this.audioContext.createGain();

            osc.connect(envelope);
            envelope.connect(this.audioContext.destination);

            let freq = 800;
            if (this.soundMode === 'digital') {
                freq = isFirstBeat ? 1200 : 800;
                osc.type = 'sine';
            } else if (this.soundMode === 'wood') {
                freq = isFirstBeat ? 600 : 400;
                osc.type = 'triangle';
            } else {
                freq = isFirstBeat ? 1500 : 1000;
                osc.type = 'square';
            }

            osc.frequency.value = freq;
            envelope.gain.setValueAtTime(0.8, time);
            envelope.gain.exponentialRampToValueAtTime(0.001, time + 0.03);

            osc.start(time);
            osc.stop(time + 0.04);

            const timeUntilNote = time - this.audioContext.currentTime;
            setTimeout(() => {
                this.isBeatPulse = true;
                setTimeout(() => { this.isBeatPulse = false; }, 80);
            }, timeUntilNote * 1000);
        },

        scheduler() {
            while (this.nextNoteTime < this.audioContext.currentTime + this.scheduleAheadTime) {
                this.playNote(this.nextNoteTime, this.currentBeat === 1);
                this.nextNote();
            }
            this.timerID = setTimeout(() => this.scheduler(), this.lookahead);
        },

        togglePlay() {
            if (this.isPlaying) {
                clearTimeout(this.timerID);
                this.isPlaying = false;
                this.currentBeat = 1;
            } else {
                if (!this.audioContext) {
                    this.audioContext = new (window.AudioContext || window.webkitAudioContext)();
                }
                if (this.audioContext.state === 'suspended') {
                    this.audioContext.resume();
                }
                
                this.isPlaying = true;
                this.currentBeat = 1;
                this.nextNoteTime = this.audioContext.currentTime + 0.05;
                this.scheduler();
            }
        }
    }
}
</script>
@endsection
