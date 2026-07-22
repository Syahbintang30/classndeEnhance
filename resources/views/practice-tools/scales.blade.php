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
                            cyan: '#06b6d4',
                            amber: '#f59e0b'
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
        
        /* Premium Dark Rosewood Fretboard Styles */
        .fretboard-container {
            width: 100%;
            overflow-x: auto;
            padding: 10px 0;
        }
        .fretboard-wrapper {
            display: inline-block;
            min-width: 860px;
        }
        .fretboard {
            display: flex;
            flex-direction: column;
            background: linear-gradient(180deg, #1c1917 0%, #292524 50%, #1c1917 100%);
            border: 2px solid #44403c;
            border-radius: 12px;
            position: relative;
            box-shadow: inset 0 0 30px rgba(0,0,0,0.9), 0 10px 30px rgba(0,0,0,0.5);
        }
        .string-row {
            display: flex;
            height: 36px;
            position: relative;
            align-items: center;
        }
        .string-wire {
            position: absolute;
            top: 50%;
            left: 50px;
            right: 0;
            background: linear-gradient(to bottom, #f5f5f5, #a3a3a3, #525252);
            transform: translateY(-50%);
            z-index: 1;
            box-shadow: 0 2px 4px rgba(0,0,0,0.8);
        }
        .string-row:nth-child(1) .string-wire { height: 1.5px; }
        .string-row:nth-child(2) .string-wire { height: 2px; }
        .string-row:nth-child(3) .string-wire { height: 2.5px; }
        .string-row:nth-child(4) .string-wire { height: 3px; }
        .string-row:nth-child(5) .string-wire { height: 3.5px; }
        .string-row:nth-child(6) .string-wire { height: 4.5px; }

        .string-label {
            width: 50px;
            font-size: 10px;
            font-weight: 800;
            color: #a1a1aa;
            text-align: center;
            z-index: 5;
            flex-shrink: 0;
        }

        .fret-cell {
            flex: 1;
            height: 100%;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            border-right: 2px solid #78716c;
            box-shadow: inset -1px 0 2px rgba(255,255,255,0.1);
        }
        .fret-cell:first-child {
            border-right: 8px solid #e7e5e4;
            flex: 0 0 50px;
            background: rgba(0,0,0,0.3);
        }

        /* Scale Note Dots */
        .scale-note-dot {
            width: 24px;
            height: 24px;
            background: linear-gradient(135deg, #06b6d4, #0284c7);
            border-radius: 50%;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 800;
            color: white;
            box-shadow: 0 0 12px rgba(6, 182, 212, 0.8), inset 0 0 4px rgba(255,255,255,0.6);
            border: 2px solid #ffffff;
        }
        .root-note-dot {
            width: 26px;
            height: 26px;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            border-radius: 50%;
            z-index: 12;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 900;
            color: black;
            box-shadow: 0 0 18px rgba(245, 158, 11, 0.9), inset 0 0 4px rgba(255,255,255,0.8);
            border: 2px solid #ffffff;
            animation: pulse-root 1.8s infinite ease-in-out;
        }

        @keyframes pulse-root {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .fret-marker-dot {
            width: 14px;
            height: 14px;
            background: radial-gradient(circle, rgba(255,255,255,0.8) 0%, rgba(200,200,200,0.4) 100%);
            border-radius: 50%;
            position: absolute;
            z-index: 0;
            box-shadow: inset 0 0 4px rgba(0,0,0,0.5);
        }
    </style>
@endpush

@section('content')
<div class="tw-dash min-h-screen flex flex-col antialiased bg-[#08080a] text-gray-200 relative overflow-hidden" 
     x-data="scaleLibrary()" 
     x-init="initScales()">

    {{-- Ambient Mesh Background Glow --}}
    <div class="absolute -top-32 left-1/3 w-[600px] h-[600px] bg-cyan-600/10 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute top-1/2 -right-32 w-[450px] h-[450px] bg-blue-600/10 rounded-full blur-[120px] pointer-events-none"></div>

    {{-- ─── TOP NAVIGATION BAR ──────────────────────────────────────────── --}}
    @include('layouts.lms_header')

    <main class="flex-1 max-w-6xl mx-auto w-full px-4 lg:px-8 py-8 space-y-8 relative z-10">
        
        <!-- BACK & TITLE HEADER -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <a href="{{ route('practice.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-white bg-zinc-950/60 border border-white/10 px-4 py-2 rounded-xl backdrop-blur-md transition self-start">
                <i class="fa-solid fa-arrow-left"></i> Back to Practice Tools
            </a>
            
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-xs font-bold uppercase tracking-wider self-start sm:self-auto">
                <i class="fa-solid fa-graduation-cap"></i> Solo & Improvisation Suite
            </div>
        </div>

        <!-- MAIN DISPLAY GLASS CARD -->
        <div class="glass-panel p-6 sm:p-8 relative overflow-hidden space-y-8">
            
            <div class="text-center space-y-2">
                <h1 class="font-display text-4xl sm:text-5xl text-white tracking-wide uppercase">
                    Scale <span class="text-cyan-400">Visualizer</span>
                </h1>
                <p class="text-gray-400 text-xs max-w-md mx-auto">
                    Visualize guitar scale patterns across all 12 frets. Essential for solos, improvising, and lead playing.
                </p>
            </div>

            <!-- ROOT NOTE SELECTOR PILLS -->
            <div class="space-y-2">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block text-center">1. Select Key / Root Note</span>
                <div class="flex flex-wrap justify-center gap-2 max-w-3xl mx-auto">
                    <template x-for="note in notes" :key="note">
                        <button @click="selectedRoot = note; updateScale()" 
                                :class="selectedRoot === note ? 'bg-gradient-to-r from-amber-500 to-amber-600 text-black shadow-lg shadow-amber-500/30 scale-105 border-amber-400 font-extrabold' : 'bg-zinc-950/60 border-white/5 text-gray-400 hover:text-white hover:bg-white/5 font-semibold'"
                                class="w-10 h-10 rounded-xl border text-xs transition-all flex items-center justify-center">
                            <span x-text="note"></span>
                        </button>
                    </template>
                </div>
            </div>

            <!-- SCALE TYPE SELECTOR PILLS -->
            <div class="space-y-2">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block text-center">2. Select Scale Formula</span>
                <div class="flex flex-wrap justify-center gap-2 max-w-3xl mx-auto">
                    <template x-for="(sData, sKey) in scaleTypes" :key="sKey">
                        <button @click="selectedScale = sKey; updateScale()" 
                                :class="selectedScale === sKey ? 'bg-gradient-to-r from-cyan-600 to-blue-600 text-white shadow-lg shadow-cyan-600/30 border-cyan-400 font-bold' : 'bg-zinc-950/60 border-white/5 text-gray-400 hover:text-white hover:bg-white/5 font-semibold'"
                                class="px-4 py-2 rounded-xl border text-xs transition-all flex items-center gap-1.5">
                            <span x-text="sData.name"></span>
                        </button>
                    </template>
                </div>
            </div>

            <!-- SCALE NAME & PLAY AUDIO BUTTON -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-white/5 bg-zinc-950/40 rounded-2xl p-4 sm:px-6">
                <div>
                    <div class="text-[10px] font-bold text-cyan-400 uppercase tracking-widest">Active Scale</div>
                    <h2 class="font-display text-4xl sm:text-5xl text-white tracking-wide">
                        <span class="text-amber-400" x-text="selectedRoot"></span> <span class="text-cyan-400" x-text="scaleTypes[selectedScale]?.name"></span>
                    </h2>
                    <p class="text-xs text-gray-400">Notes in Scale: <span class="font-mono text-cyan-300 font-bold" x-text="scaleNoteNames.join(' - ')"></span></p>
                </div>

                <!-- PLAY SCALE AUDIO ARPEGGIO -->
                <button @click="playScaleAudio()" class="py-3 px-6 rounded-xl bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white font-bold text-xs shadow-lg shadow-cyan-600/25 transition-all hover:scale-105 active:scale-95 flex items-center gap-2">
                    <i class="fa-solid fa-circle-play"></i>
                    <span>Play Ascending Scale</span>
                </button>
            </div>

            <!-- FRETBOARD DIAGRAM -->
            <div class="fretboard-container">
                <div class="fretboard-wrapper">
                    
                    <!-- FRET NUMBERS HEADER BAR -->
                    <div class="flex text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2 pl-[50px]">
                        <div class="w-[50px] text-center text-emerald-400 font-bold">Open</div>
                        <template x-for="f in 12" :key="f">
                            <div class="flex-1 text-center" x-text="'Fret ' + f"></div>
                        </template>
                    </div>

                    <!-- FRETBOARD BODY -->
                    <div class="fretboard" id="fretboard">
                        <template x-for="(string, sIdx) in 6" :key="sIdx">
                            <div class="string-row">
                                <!-- String Name Label -->
                                <div class="string-label" x-text="stringNames[sIdx]"></div>
                                
                                <!-- String Wire -->
                                <div class="string-wire"></div>

                                <!-- Fret Cells (0 to 12) -->
                                <template x-for="(fret, fIdx) in 13" :key="fIdx">
                                    <div class="fret-cell">
                                        <!-- Inlay Markers -->
                                        <template x-if="sIdx === 2 && (fIdx === 3 || fIdx === 5 || fIdx === 7 || fIdx === 9 || fIdx === 12)">
                                            <div class="fret-marker-dot"></div>
                                        </template>

                                        <!-- Note Dot (Root vs Scale Note) -->
                                        <template x-if="isNoteInScale(sIdx, fIdx)">
                                            <div :class="isRootNote(sIdx, fIdx) ? 'root-note-dot' : 'scale-note-dot'"
                                                 @click.stop="playSingleFretNote(sIdx, fIdx)"
                                                 class="cursor-pointer hover:scale-125 transition-transform"
                                                 title="Click to play note"
                                                 x-text="getNoteNameAt(sIdx, fIdx)"></div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>

                </div>
            </div>
            
            <div class="flex items-center justify-between text-xs text-gray-400 pt-2 border-t border-white/5">
                <span class="flex items-center gap-2"><span class="w-3.5 h-3.5 rounded-full bg-amber-500 inline-block border border-white"></span> Amber Gold = Root Note</span>
                <span class="flex items-center gap-2"><i class="fa-solid fa-guitar text-cyan-400"></i> Click any note on fretboard to listen</span>
                <span class="flex items-center gap-2"><span class="w-3.5 h-3.5 rounded-full bg-cyan-500 inline-block border border-white"></span> Cyan Blue = Scale Notes</span>
            </div>

        </div>

    </main>
</div>

<script>
function scaleLibrary() {
    return {
        notes: ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B'],
        stringNames: ['1st - E4', '2nd - B3', '3rd - G3', '4th - D3', '5th - A2', '6th - E2'],
        stringOpenNoteIndices: [4, 11, 7, 2, 9, 4], // E4, B3, G3, D3, A2, E2
        baseFrequencies: [329.63, 246.94, 196.00, 146.83, 110.00, 82.41],

        selectedRoot: 'A',
        selectedScale: 'minor_pentatonic',
        scaleNoteNames: [],

        scaleTypes: {
            'minor_pentatonic': { name: 'Minor Pentatonic', intervals: [0, 3, 5, 7, 10] },
            'major_pentatonic': { name: 'Major Pentatonic', intervals: [0, 2, 4, 7, 9] },
            'blues':            { name: 'Blues Scale',      intervals: [0, 3, 5, 6, 7, 10] },
            'major':            { name: 'Major (Ionian)',    intervals: [0, 2, 4, 5, 7, 9, 11] },
            'natural_minor':    { name: 'Natural Minor',    intervals: [0, 2, 3, 5, 7, 8, 10] },
            'dorian':           { name: 'Dorian Mode',      intervals: [0, 2, 3, 5, 7, 9, 10] },
            'mixolydian':       { name: 'Mixolydian Mode',  intervals: [0, 2, 4, 5, 7, 9, 10] }
        },

        audioCtx: null,

        initScales() {
            this.updateScale();
        },

        updateScale() {
            const rootIdx = this.notes.indexOf(this.selectedRoot);
            const intervals = this.scaleTypes[this.selectedScale].intervals;
            this.scaleNoteNames = intervals.map(i => this.notes[(rootIdx + i) % 12]);
        },

        getNoteNameAt(sIdx, fIdx) {
            const openNoteIdx = this.stringOpenNoteIndices[sIdx];
            return this.notes[(openNoteIdx + fIdx) % 12];
        },

        isNoteInScale(sIdx, fIdx) {
            const noteName = this.getNoteNameAt(sIdx, fIdx);
            return this.scaleNoteNames.includes(noteName);
        },

        isRootNote(sIdx, fIdx) {
            const noteName = this.getNoteNameAt(sIdx, fIdx);
            return noteName === this.selectedRoot;
        },

        playSingleFretNote(sIdx, fIdx) {
            if (!this.audioCtx) {
                this.audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            }
            if (this.audioCtx.state === 'suspended') {
                this.audioCtx.resume();
            }
            let baseFreq = this.baseFrequencies[sIdx];
            let noteFreq = baseFreq * Math.pow(2, fIdx / 12);
            this.playElectricGuitarNote(noteFreq);
        },

        playScaleAudio() {
            if (!this.audioCtx) {
                this.audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            }
            if (this.audioCtx.state === 'suspended') {
                this.audioCtx.resume();
            }

            // Ascending notes from lowest fret on string 6 to highest on string 1
            let notesToPlay = [];
            for (let sIdx = 5; sIdx >= 0; sIdx--) {
                for (let fIdx = 0; fIdx <= 12; fIdx++) {
                    if (this.isNoteInScale(sIdx, fIdx)) {
                        let baseFreq = this.baseFrequencies[sIdx];
                        let noteFreq = baseFreq * Math.pow(2, fIdx / 12);
                        notesToPlay.push(noteFreq);
                    }
                }
            }

            // Play ascending frequencies
            let delay = 0;
            let playedCount = 0;
            let lastFreq = 0;
            for (let freq of notesToPlay) {
                if (freq > lastFreq + 5 && playedCount < 12) {
                    lastFreq = freq;
                    setTimeout(() => {
                        this.playElectricGuitarNote(freq);
                    }, delay * 170);
                    delay++;
                    playedCount++;
                }
            }
        },

        // Synthesize authentic Electric Guitar tone (Pick attack + Dual Oscillators + Warm Tube Distortion + Amp Cabinet Filter)
        playElectricGuitarNote(freq) {
            if (!this.audioCtx) return;
            const now = this.audioCtx.currentTime;

            // 1. Dual Pickup Oscillators (Sawtooth for bite + Triangle for warm body)
            const osc1 = this.audioCtx.createOscillator();
            const osc2 = this.audioCtx.createOscillator();
            const oscHarmonic = this.audioCtx.createOscillator();

            osc1.type = 'sawtooth';
            osc1.frequency.setValueAtTime(freq, now);

            osc2.type = 'triangle';
            osc2.frequency.setValueAtTime(freq * 1.0015, now); // Micro-detune for string thickness

            oscHarmonic.type = 'sine';
            oscHarmonic.frequency.setValueAtTime(freq * 2.0, now); // 2nd harmonic overtone

            // 2. Envelope Generator (Pick Attack -> Pluck Decay -> Ring out)
            const noteGain = this.audioCtx.createGain();
            noteGain.gain.setValueAtTime(0.0001, now);
            noteGain.gain.exponentialRampToValueAtTime(0.42, now + 0.005); // Fast Pick Attack
            noteGain.gain.exponentialRampToValueAtTime(0.18, now + 0.12);  // Pluck Decay
            noteGain.gain.exponentialRampToValueAtTime(0.0001, now + 1.4); // String Ring-out Fade

            // 3. Overdrive / Distortion Waveshaper (Warm Tube Amp Saturation)
            const distortion = this.audioCtx.createWaveShaper();
            distortion.curve = this.makeDistortionCurve(16); // Warm overdrive
            distortion.oversample = '4x';

            // 4. Amp Cabinet Filter (Guitar Speaker High-cut & Resonance)
            const cabFilter = this.audioCtx.createBiquadFilter();
            cabFilter.type = 'lowpass';
            cabFilter.frequency.setValueAtTime(3800, now); // Cut harsh treble above 3.8kHz
            cabFilter.Q.setValueAtTime(1.8, now);         // Cabinet resonance peak

            const bodyFilter = this.audioCtx.createBiquadFilter();
            bodyFilter.type = 'peaking';
            bodyFilter.frequency.setValueAtTime(750, now); // Midrange amp punch
            bodyFilter.gain.setValueAtTime(3, now);

            // Connect Nodes: Oscillators -> Gain Envelope -> Overdrive -> EQ -> Cabinet -> Speakers
            osc1.connect(noteGain);
            osc2.connect(noteGain);
            oscHarmonic.connect(noteGain);

            noteGain.connect(distortion);
            distortion.connect(bodyFilter);
            bodyFilter.connect(cabFilter);
            cabFilter.connect(this.audioCtx.destination);

            // Start & Stop Oscillators
            osc1.start(now);
            osc2.start(now);
            oscHarmonic.start(now);

            osc1.stop(now + 1.4);
            osc2.stop(now + 1.4);
            oscHarmonic.stop(now + 1.4);
        },

        makeDistortionCurve(k = 16) {
            const n_samples = 44100;
            const curve = new Float32Array(n_samples);
            const deg = Math.PI / 180;
            for (let i = 0; i < n_samples; ++i) {
                let x = (i * 2) / n_samples - 1;
                curve[i] = ((3 + k) * x * 20 * deg) / (Math.PI + k * Math.abs(x));
            }
            return curve;
        }
    }
}
</script>

@endsection
