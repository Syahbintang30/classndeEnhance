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
                            accent: '#0066ff',
                            purple: '#a855f7'
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
            min-width: 820px;
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
        /* Variable gauge strings */
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
            border-right: 2px solid #78716c; /* Metallic fret wire */
            box-shadow: inset -1px 0 2px rgba(255,255,255,0.1);
        }
        .fret-cell:first-child {
            border-right: 8px solid #e7e5e4; /* Bone nut */
            flex: 0 0 50px;
            background: rgba(0,0,0,0.3);
        }

        .note-dot {
            width: 24px;
            height: 24px;
            background: linear-gradient(135deg, #c084fc, #9333ea);
            border-radius: 50%;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 800;
            color: white;
            box-shadow: 0 0 15px rgba(168, 85, 247, 0.9), inset 0 0 4px rgba(255,255,255,0.6);
            border: 2px solid #ffffff;
            animation: pulse-ring 2s infinite;
        }
        .muted-cross {
            color: #ef4444;
            font-size: 18px;
            font-weight: 900;
            z-index: 10;
        }
        .open-circle {
            width: 16px;
            height: 16px;
            border: 2px solid #10b981;
            border-radius: 50%;
            z-index: 10;
            background: rgba(16, 185, 129, 0.2);
            box-shadow: 0 0 10px rgba(16, 185, 129, 0.5);
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
@php
    $isEn = (session('app_lang', request('lang', 'id')) === 'en');
@endphp
<div class="tw-dash min-h-screen flex flex-col antialiased bg-[#08080a] text-gray-200 relative overflow-hidden" 
     x-data="chordLibrary()" 
     x-init="drawFretboard()">

    {{-- Ambient Mesh Background Glow --}}
    <div class="absolute -top-32 left-1/3 w-[600px] h-[600px] bg-purple-600/10 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute top-1/2 -right-32 w-[450px] h-[450px] bg-indigo-600/10 rounded-full blur-[120px] pointer-events-none"></div>

    {{-- ─── TOP NAVIGATION BAR ──────────────────────────────────────────── --}}
    @include('layouts.lms_header')

    <main class="flex-1 max-w-6xl mx-auto w-full px-4 lg:px-8 py-8 space-y-8 relative z-10">
        
        <!-- BACK & TITLE HEADER -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <a href="{{ route('practice.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-white bg-zinc-950/60 border border-white/10 px-4 py-2 rounded-xl backdrop-blur-md transition self-start">
                <i class="fa-solid fa-arrow-left"></i> {{ $isEn ? 'Back to Practice Tools' : 'Kembali ke Tools Latihan' }}
            </a>
            
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-500/10 border border-purple-500/20 text-purple-400 text-xs font-bold uppercase tracking-wider self-start sm:self-auto">
                <i class="fa-solid fa-guitar"></i> {{ $isEn ? 'Interactive Fretboard Visualizer' : 'Visualizer Fretboard Interaktif' }}
            </div>
        </div>

        <!-- MAIN DISPLAY GLASS CARD -->
        <div class="glass-panel p-6 sm:p-8 relative overflow-hidden space-y-8">
            
            <div class="text-center space-y-2">
                <h1 class="font-display text-4xl sm:text-5xl text-white tracking-wide uppercase">
                    Chord <span class="text-purple-400">Library</span>
                </h1>
                <p class="text-gray-400 text-xs max-w-md mx-auto">
                    {{ $isEn ? 'Select a root note and chord formula to visualize finger positions and play reference arpeggios on the neck.' : 'Pilih nada dasar dan bentuk chord untuk melihat posisi jari dan mendengarkan suara chord.' }}
                </p>
            </div>

            <!-- ROOT NOTE SELECTOR PILLS -->
            <div class="space-y-2">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block text-center">1. Select Root Note</span>
                <div class="flex flex-wrap justify-center gap-2 max-w-3xl mx-auto">
                    <template x-for="note in rootNotes" :key="note">
                        <button @click="selectedRoot = note; updateChord()" 
                                :class="selectedRoot === note ? 'bg-gradient-to-r from-purple-600 to-indigo-600 text-white shadow-lg shadow-purple-600/30 scale-105 border-purple-400' : 'bg-zinc-950/60 border-white/5 text-gray-400 hover:text-white hover:bg-white/5'"
                                class="w-10 h-10 rounded-xl border text-xs font-bold transition-all flex items-center justify-center">
                            <span x-text="note"></span>
                        </button>
                    </template>
                </div>
            </div>

            <!-- CHORD TYPE SELECTOR PILLS -->
            <div class="space-y-2">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block text-center">2. Select Chord Type</span>
                <div class="flex flex-wrap justify-center gap-2 max-w-3xl mx-auto">
                    <template x-for="(type, key) in chordTypes" :key="key">
                        <button @click="selectedType = key; updateChord()" 
                                :class="selectedType === key ? 'bg-gradient-to-r from-purple-600 to-indigo-600 text-white shadow-lg shadow-purple-600/30 border-purple-400 font-bold' : 'bg-zinc-950/60 border-white/5 text-gray-400 hover:text-white hover:bg-white/5 font-semibold'"
                                class="px-4 py-2 rounded-xl border text-xs transition-all flex items-center gap-1.5">
                            <span x-text="type.name"></span>
                        </button>
                    </template>
                </div>
            </div>

            <!-- CHORD NAME & STRUM AUDIO BUTTON -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-white/5 bg-zinc-950/40 rounded-2xl p-4 sm:px-6">
                <div>
                    <div class="text-[10px] font-bold text-purple-400 uppercase tracking-widest">Active Chord</div>
                    <h2 class="font-display text-4xl sm:text-5xl text-white tracking-wide">
                        <span x-text="selectedRoot"></span><span class="text-purple-400" x-text="selectedType"></span>
                    </h2>
                    <p class="text-xs text-gray-400" x-text="getChordFormula()"></p>
                </div>

                <!-- STRUM CHORD AUDIO BUTTON -->
                <button @click="strumChordAudio()" class="py-3 px-6 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-bold text-xs shadow-lg shadow-purple-600/25 transition-all hover:scale-105 active:scale-95 flex items-center gap-2">
                    <i class="fa-solid fa-volume-high"></i>
                    <span>Strum Chord Audio</span>
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
                                <div class="string-label" x-text="getStringName(sIdx)"></div>
                                
                                <!-- String Wire -->
                                <div class="string-wire"></div>

                                <!-- Fret Cells (0 to 12) -->
                                <template x-for="(fret, fIdx) in 13" :key="fIdx">
                                    <div class="fret-cell">
                                        <!-- Inlay Markers on 3rd, 5th, 7th, 9th, 12th frets -->
                                        <template x-if="sIdx === 2 && (fIdx === 3 || fIdx === 5 || fIdx === 7 || fIdx === 9 || fIdx === 12)">
                                            <div class="fret-marker-dot"></div>
                                        </template>

                                        <!-- Note Dot -->
                                        <template x-if="hasNote(sIdx, fIdx)">
                                            <div class="note-dot" x-text="getFretNoteName(sIdx, fIdx)"></div>
                                        </template>
                                        
                                        <!-- Muted (X) or Open (O) on Fret 0 -->
                                        <template x-if="fIdx === 0 && getMuted(sIdx)">
                                            <div class="muted-cross">×</div>
                                        </template>
                                        <template x-if="fIdx === 0 && isOpen(sIdx)">
                                            <div class="open-circle"></div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>

                </div>
            </div>
            
            <div class="flex items-center justify-between text-xs text-gray-400 pt-2 border-t border-white/5">
                <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-purple-500 inline-block"></span> Purple dots represent note locations</span>
                <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full border border-emerald-400 inline-block"></span> Green circles = Open strings</span>
                <span class="flex items-center gap-2"><span class="text-red-400 font-bold">×</span> Red X = Muted strings</span>
            </div>

        </div>

    </main>
</div>

<script>
function chordLibrary() {
    return {
        rootNotes: ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B'],
        selectedRoot: 'C',
        selectedType: 'Major',
        
        // Frequencies for strings E2, A2, D3, G3, B3, E4
        baseFrequencies: [329.63, 246.94, 196.00, 146.83, 110.00, 82.41],
        stringNames: ['1st - E4', '2nd - B3', '3rd - G3', '4th - D3', '5th - A2', '6th - E2'],
        
        chordDict: {
            'C': {
                'Major': [0, 1, 0, 2, 3, -1],
                'Minor': [3, 4, 5, 5, 3, -1],
                '7':     [0, 1, 3, 2, 3, -1],
                'm7':    [3, 4, 3, 5, 3, -1],
                'maj7':  [0, 0, 0, 2, 3, -1],
                'sus4':  [1, 1, 0, 3, 3, -1]
            },
            'D': {
                'Major': [2, 3, 2, 0, -1, -1],
                'Minor': [1, 3, 2, 0, -1, -1],
                '7':     [2, 1, 2, 0, -1, -1],
                'm7':    [1, 1, 2, 0, -1, -1],
                'maj7':  [2, 2, 2, 0, -1, -1],
                'sus4':  [3, 3, 2, 0, -1, -1]
            },
            'E': {
                'Major': [0, 0, 1, 2, 2, 0],
                'Minor': [0, 0, 0, 2, 2, 0],
                '7':     [0, 0, 1, 0, 2, 0],
                'm7':    [0, 0, 0, 0, 2, 0],
                'maj7':  [0, 0, 1, 1, 2, 0],
                'sus4':  [0, 0, 2, 2, 2, 0]
            },
            'F': {
                'Major': [1, 1, 2, 3, 3, 1],
                'Minor': [1, 1, 1, 3, 3, 1],
                '7':     [1, 1, 2, 1, 3, 1],
                'm7':    [1, 1, 1, 1, 3, 1],
                'maj7':  [0, 1, 2, 3, -1, -1],
                'sus4':  [1, 1, 3, 3, 3, 1]
            },
            'G': {
                'Major': [3, 0, 0, 0, 2, 3],
                'Minor': [3, 3, 3, 5, 5, 3],
                '7':     [1, 0, 0, 0, 2, 3],
                'm7':    [3, 3, 3, 3, 5, 3],
                'maj7':  [2, 0, 0, 0, 2, 3],
                'sus4':  [3, 1, 0, 0, 3, 3]
            },
            'A': {
                'Major': [0, 2, 2, 2, 0, -1],
                'Minor': [0, 1, 2, 2, 0, -1],
                '7':     [0, 2, 0, 2, 0, -1],
                'm7':    [0, 1, 0, 2, 0, -1],
                'maj7':  [0, 2, 1, 2, 0, -1],
                'sus4':  [0, 3, 2, 2, 0, -1]
            },
            'B': {
                'Major': [2, 4, 4, 4, 2, -1],
                'Minor': [2, 3, 4, 4, 2, -1],
                '7':     [2, 4, 2, 4, 2, -1],
                'm7':    [2, 3, 2, 4, 2, -1],
                'maj7':  [2, 4, 3, 4, 2, -1],
                'sus4':  [2, 5, 4, 4, 2, -1]
            }
        },
        
        chordTypes: {
            'Major': { name: 'Major', formula: 'Root • Major 3rd • Perfect 5th' },
            'Minor': { name: 'Minor (m)', formula: 'Root • Minor 3rd • Perfect 5th' },
            '7': { name: 'Dominant 7', formula: 'Root • Major 3rd • Perfect 5th • Minor 7th' },
            'm7': { name: 'Minor 7 (m7)', formula: 'Root • Minor 3rd • Perfect 5th • Minor 7th' },
            'maj7': { name: 'Major 7 (maj7)', formula: 'Root • Major 3rd • Perfect 5th • Major 7th' },
            'sus4': { name: 'Suspended 4 (sus4)', formula: 'Root • Perfect 4th • Perfect 5th' }
        },

        currentPositions: [],
        audioCtx: null,

        drawFretboard() {
            this.updateChord();
        },

        getStringName(sIdx) {
            return this.stringNames[sIdx] || '';
        },

        getChordFormula() {
            return this.chordTypes[this.selectedType]?.formula || '';
        },

        updateChord() {
            if (this.chordDict[this.selectedRoot] && this.chordDict[this.selectedRoot][this.selectedType]) {
                this.currentPositions = this.chordDict[this.selectedRoot][this.selectedType];
            } else {
                let eStringRoot = ['E','F','F#','G','G#','A','A#','B','C','C#','D','D#'];
                let rootFret = eStringRoot.indexOf(this.selectedRoot);
                
                if (rootFret !== -1) {
                    if (this.selectedType === 'Major') this.currentPositions = [rootFret, rootFret, rootFret+1, rootFret+2, rootFret+2, rootFret];
                    else if (this.selectedType === 'Minor') this.currentPositions = [rootFret, rootFret, rootFret, rootFret+2, rootFret+2, rootFret];
                    else if (this.selectedType === '7')     this.currentPositions = [rootFret, rootFret, rootFret+1, rootFret, rootFret+2, rootFret];
                    else if (this.selectedType === 'm7')    this.currentPositions = [rootFret, rootFret, rootFret, rootFret, rootFret+2, rootFret];
                    else if (this.selectedType === 'maj7')  this.currentPositions = [rootFret, rootFret, rootFret+1, rootFret+1, rootFret+2, rootFret];
                    else this.currentPositions = [rootFret, rootFret, rootFret+1, rootFret+2, rootFret+2, rootFret];
                } else {
                    this.currentPositions = [-1,-1,-1,-1,-1,-1];
                }
            }
        },

        hasNote(sIdx, fIdx) {
            if (fIdx === 0) return false;
            return this.currentPositions[sIdx] === fIdx;
        },
        
        getFretNoteName(sIdx, fIdx) {
            return fIdx;
        },

        getMuted(sIdx) {
            return this.currentPositions[sIdx] === -1;
        },

        isOpen(sIdx) {
            return this.currentPositions[sIdx] === 0;
        },

        strumChordAudio() {
            if (!this.audioCtx) {
                this.audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            }
            if (this.audioCtx.state === 'suspended') {
                this.audioCtx.resume();
            }

            // Strum from lowest string (sIdx 5) to highest string (sIdx 0)
            let delay = 0;
            for (let sIdx = 5; sIdx >= 0; sIdx--) {
                let fret = this.currentPositions[sIdx];
                if (fret !== -1) {
                    let baseFreq = this.baseFrequencies[sIdx];
                    let noteFreq = baseFreq * Math.pow(2, fret / 12);
                    
                    setTimeout(() => {
                        this.playNoteTone(noteFreq);
                    }, delay * 80);
                    delay++;
                }
            }
        },

        playNoteTone(freq) {
            const osc = this.audioCtx.createOscillator();
            const gain = this.audioCtx.createGain();
            
            osc.type = 'triangle';
            osc.frequency.setValueAtTime(freq, this.audioCtx.currentTime);
            
            gain.gain.setValueAtTime(0.4, this.audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, this.audioCtx.currentTime + 1.6);
            
            osc.connect(gain);
            gain.connect(this.audioCtx.destination);
            
            osc.start();
            osc.stop(this.audioCtx.currentTime + 1.6);
        }
    }
}
</script>
@endsection
