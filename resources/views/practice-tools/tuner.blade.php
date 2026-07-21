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
        .glass-panel {
            background: rgba(18, 18, 26, 0.55);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 1.5rem;
        }
        body > nav { display: none !important; }
        
        .needle-container {
            width: 100%;
            height: 160px;
            position: relative;
            overflow: hidden;
        }
        .needle {
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 4px;
            height: 140px;
            background-color: #ffffff;
            transform-origin: bottom center;
            border-radius: 4px;
            transition: transform 0.12s ease-out, background-color 0.2s;
            z-index: 10;
            box-shadow: 0 0 15px currentColor;
        }
        .tuner-dial {
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 320px;
            height: 160px;
            border-top-left-radius: 160px;
            border-top-right-radius: 160px;
            border: 4px solid rgba(255,255,255,0.1);
            border-bottom: none;
            background: radial-gradient(circle at bottom, rgba(59, 130, 246, 0.08), transparent 70%);
        }
        .tick {
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 2px;
            height: 16px;
            background: rgba(255,255,255,0.2);
            transform-origin: bottom center;
        }
        .tick-center {
            height: 28px;
            background: #10b981;
            width: 4px;
            box-shadow: 0 0 10px #10b981;
        }
    </style>
@endpush

@section('content')
<div class="tw-dash min-h-screen flex flex-col antialiased bg-[#08080a] text-gray-200 relative overflow-hidden" 
     x-data="tunerApp()" 
     x-init="initTuner()">

    {{-- Ambient Mesh Background Glow --}}
    <div class="absolute -top-32 left-1/3 w-[600px] h-[600px] bg-blue-600/10 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute top-1/2 -right-32 w-[450px] h-[450px] bg-indigo-600/10 rounded-full blur-[120px] pointer-events-none"></div>

    {{-- ─── TOP NAVIGATION BAR ──────────────────────────────────────────── --}}
    @include('layouts.lms_header')

    <main class="flex-1 max-w-2xl mx-auto w-full px-4 lg:px-8 py-8 space-y-8 relative z-10">
        
        <!-- BACK & TITLE HEADER -->
        <div class="flex items-center justify-between gap-4">
            <a href="{{ route('practice.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-white bg-zinc-950/60 border border-white/10 px-4 py-2 rounded-xl backdrop-blur-md transition">
                <i class="fa-solid fa-arrow-left"></i> Back to Practice Tools
            </a>
            
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-bold uppercase tracking-wider">
                <i class="fa-solid fa-microphone text-blue-400"></i> Realtime Mic Pitch Detection
            </div>
        </div>

        <!-- MAIN TUNER GLASS CARD -->
        <div class="glass-panel p-6 sm:p-8 relative overflow-hidden text-center space-y-8">
            
            <div class="space-y-1">
                <h1 class="font-display text-4xl sm:text-5xl text-white tracking-wide uppercase">
                    Guitar <span class="text-blue-400">Tuner</span>
                </h1>
                <p class="text-gray-400 text-xs max-w-sm mx-auto">
                    Allow microphone access and pluck any guitar string to tune in real-time.
                </p>
            </div>

            <!-- STRING TARGET SELECTOR PILLS -->
            <div class="space-y-2">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Standard Tuning Reference</span>
                <div class="flex flex-wrap justify-center gap-2">
                    <button @click="targetString = 'AUTO'" :class="targetString === 'AUTO' ? 'bg-blue-600 text-white font-bold border-blue-400' : 'bg-zinc-950/60 text-gray-400 border-white/5'" class="px-3.5 py-1.5 rounded-full border text-xs transition">Auto Detect</button>
                    <button @click="targetString = 'E2'" :class="targetString === 'E2' ? 'bg-blue-600 text-white font-bold border-blue-400' : 'bg-zinc-950/60 text-gray-400 border-white/5'" class="px-3 py-1.5 rounded-full border text-xs transition">6E (82.4Hz)</button>
                    <button @click="targetString = 'A2'" :class="targetString === 'A2' ? 'bg-blue-600 text-white font-bold border-blue-400' : 'bg-zinc-950/60 text-gray-400 border-white/5'" class="px-3 py-1.5 rounded-full border text-xs transition">5A (110.0Hz)</button>
                    <button @click="targetString = 'D3'" :class="targetString === 'D3' ? 'bg-blue-600 text-white font-bold border-blue-400' : 'bg-zinc-950/60 text-gray-400 border-white/5'" class="px-3 py-1.5 rounded-full border text-xs transition">4D (146.8Hz)</button>
                    <button @click="targetString = 'G3'" :class="targetString === 'G3' ? 'bg-blue-600 text-white font-bold border-blue-400' : 'bg-zinc-950/60 text-gray-400 border-white/5'" class="px-3 py-1.5 rounded-full border text-xs transition">3G (196.0Hz)</button>
                    <button @click="targetString = 'B3'" :class="targetString === 'B3' ? 'bg-blue-600 text-white font-bold border-blue-400' : 'bg-zinc-950/60 text-gray-400 border-white/5'" class="px-3 py-1.5 rounded-full border text-xs transition">2B (246.9Hz)</button>
                    <button @click="targetString = 'E4'" :class="targetString === 'E4' ? 'bg-blue-600 text-white font-bold border-blue-400' : 'bg-zinc-950/60 text-gray-400 border-white/5'" class="px-3 py-1.5 rounded-full border text-xs transition">1E (329.6Hz)</button>
                </div>
            </div>

            <!-- START TUNER BUTTON (OFF STATE) -->
            <div x-show="!isListening" class="py-8 space-y-4">
                <button @click="startListening()" class="w-24 h-24 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-3xl shadow-lg shadow-blue-600/40 transition-all hover:scale-105 active:scale-95 flex items-center justify-center mx-auto">
                    <i class="fa-solid fa-microphone"></i>
                </button>
                <div class="text-xs font-bold text-blue-400 uppercase tracking-widest">Click to Start Mic Listener</div>
                <p class="text-xs text-red-400 max-w-sm mx-auto" x-text="errorMsg"></p>
            </div>

            <!-- ACTIVE TUNER DIAL & NEEDLE (ON STATE) -->
            <div x-show="isListening" style="display: none;" class="w-full space-y-6">
                
                <!-- TUNER DIAL ARC -->
                <div class="needle-container">
                    <div class="tuner-dial"></div>
                    <template x-for="i in 9">
                        <div class="tick" :class="{'tick-center': i === 5}" :style="`height: 160px; transform: translateX(-50%) rotate(${(i-5) * 15}deg)`"></div>
                    </template>
                    <div class="needle" :style="`transform: translateX(-50%) rotate(${needleAngle}deg); background-color: ${needleColor}; color: ${needleColor};`" style="transform: translateX(-50%) rotate(0deg);"></div>
                </div>

                <!-- NOTE NAME & CENTS DISPLAY -->
                <div class="space-y-1">
                    <div class="flex items-baseline justify-center gap-3">
                        <div class="text-7xl sm:text-8xl font-display font-bold transition-colors" :style="`color: ${needleColor}`" x-text="currentNote">--</div>
                        <div class="text-2xl font-bold font-mono text-gray-400" x-text="currentCents > 0 ? '+' + currentCents + ' cents' : currentCents + ' cents'">0</div>
                    </div>
                    <div class="text-xs font-bold tracking-widest uppercase py-1 px-3 rounded-full inline-block border" :style="`color: ${needleColor}; border-color: ${needleColor}40; background-color: ${needleColor}10`" x-text="tuneStatus">Waiting...</div>
                </div>
                
                <!-- FREQUENCY STATS BOX -->
                <div class="bg-zinc-950/70 border border-white/5 rounded-2xl p-4 inline-flex items-center gap-6">
                    <div class="text-center">
                        <span class="text-[10px] text-gray-500 uppercase font-bold tracking-wider block">Pitch Frequency</span>
                        <div class="font-mono text-lg font-bold text-white mt-0.5"><span x-text="frequency">0.00</span> <span class="text-xs text-gray-500">Hz</span></div>
                    </div>
                    <div class="w-px h-8 bg-white/5"></div>
                    <div class="text-center">
                        <span class="text-[10px] text-gray-500 uppercase font-bold tracking-wider block">Target Standard</span>
                        <div class="font-mono text-lg font-bold text-blue-400 mt-0.5" x-text="targetString">AUTO</div>
                    </div>
                </div>

                <div class="pt-2">
                    <button @click="stopListening()" class="py-2.5 px-6 rounded-xl bg-zinc-900 hover:bg-zinc-800 border border-white/10 text-xs font-bold text-gray-300 hover:text-white transition">
                        Stop Mic Listener
                    </button>
                </div>
            </div>

        </div>
    </main>
</div>

<script>
function tunerApp() {
    return {
        isListening: false,
        errorMsg: '',
        targetString: 'AUTO',
        audioContext: null,
        analyser: null,
        mediaStreamSource: null,
        animationId: null,
        
        currentNote: '--',
        currentCents: 0,
        frequency: '0.00',
        needleAngle: 0,
        needleColor: '#ffffff',
        tuneStatus: 'Waiting...',

        notes: ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B'],

        initTuner() {},

        async startListening() {
            try {
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    throw new Error("Browser API for microphone is disabled. Pastikan Anda mengakses web lewat 'http://localhost:8000'.");
                }

                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                this.audioContext = new (window.AudioContext || window.webkitAudioContext)();
                this.analyser = this.audioContext.createAnalyser();
                this.analyser.fftSize = 2048;
                this.mediaStreamSource = this.audioContext.createMediaStreamSource(stream);
                this.mediaStreamSource.connect(this.analyser);
                this.isListening = true;
                this.errorMsg = '';
                this.updatePitch();
            } catch (err) {
                console.error("Mic Error:", err);
                if (err.name === 'NotAllowedError' || err.message.includes('Permission denied')) {
                    this.errorMsg = 'Akses mikrofon diblokir oleh browser! Klik icon kamera/gembok di address bar untuk mengizinkan (Allow) Microphone.';
                } else if (err.name === 'NotFoundError') {
                    this.errorMsg = 'Microphone tidak terdeteksi di perangkat Anda.';
                } else {
                    this.errorMsg = 'Error: ' + err.message;
                }
            }
        },

        stopListening() {
            this.isListening = false;
            if (this.animationId) cancelAnimationFrame(this.animationId);
            if (this.mediaStreamSource) this.mediaStreamSource.disconnect();
            if (this.audioContext) this.audioContext.close();
            this.currentNote = '--';
            this.currentCents = 0;
            this.frequency = '0.00';
            this.needleAngle = 0;
            this.tuneStatus = 'Waiting...';
        },

        updatePitch() {
            if (!this.isListening) return;

            const buffer = new Float32Array(this.analyser.fftSize);
            this.analyser.getFloatTimeDomainData(buffer);
            
            const pitch = this.autoCorrelate(buffer, this.audioContext.sampleRate);
            
            if (pitch !== -1) {
                const note = this.noteFromPitch(pitch);
                const noteName = this.notes[note % 12];
                const cents = this.centsOffFromPitch(pitch, note);
                
                this.frequency = pitch.toFixed(2);
                this.currentNote = noteName;
                this.currentCents = cents;
                
                this.needleAngle = (cents / 50) * 60;
                
                if (Math.abs(cents) < 5) {
                    this.needleColor = '#10b981'; // emerald in tune
                    this.tuneStatus = 'PERFECT - IN TUNE';
                } else if (cents < 0) {
                    this.needleColor = '#f59e0b'; // amber flat
                    this.tuneStatus = 'FLAT (TUNE UP)';
                } else {
                    this.needleColor = '#ef4444'; // red sharp
                    this.tuneStatus = 'SHARP (TUNE DOWN)';
                }
            }

            this.animationId = requestAnimationFrame(this.updatePitch.bind(this));
        },

        autoCorrelate(buffer, sampleRate) {
            let SIZE = buffer.length;
            let rms = 0;
            for (let i = 0; i < SIZE; i++) {
                let val = buffer[i];
                rms += val * val;
            }
            rms = Math.sqrt(rms / SIZE);
            if (rms < 0.01) return -1;

            let r1 = 0, r2 = SIZE - 1, thres = 0.2;
            for (let i = 0; i < SIZE / 2; i++)
                if (Math.abs(buffer[i]) < thres) { r1 = i; break; }
            for (let i = 1; i < SIZE / 2; i++)
                if (Math.abs(buffer[SIZE - i]) < thres) { r2 = SIZE - i; break; }

            buffer = buffer.slice(r1, r2);
            SIZE = buffer.length;

            let c = new Array(SIZE).fill(0);
            for (let i = 0; i < SIZE; i++)
                for (let j = 0; j < SIZE - i; j++)
                    c[i] = c[i] + buffer[j] * buffer[j + i];

            let d = 0;
            while (c[d] > c[d + 1]) d++;
            let maxval = -1, maxpos = -1;
            for (let i = d; i < SIZE; i++) {
                if (c[i] > maxval) { maxval = c[i]; maxpos = i; }
            }
            let T0 = maxpos;

            let x1 = c[T0 - 1], x2 = c[T0], x3 = c[T0 + 1];
            let a = (x1 + x3 - 2 * x2) / 2;
            let b = (x3 - x1) / 2;
            if (a) T0 = T0 - b / (2 * a);

            return sampleRate / T0;
        },

        noteFromPitch(frequency) {
            let noteNum = 12 * (Math.log(frequency / 440) / Math.log(2));
            return Math.round(noteNum) + 69;
        },

        frequencyFromNoteNumber(note) {
            return 440 * Math.pow(2, (note - 69) / 12);
        },

        centsOffFromPitch(frequency, note) {
            return Math.floor(1200 * Math.log(frequency / this.frequencyFromNoteNumber(note)) / Math.log(2));
        }
    }
}
</script>
@endsection
