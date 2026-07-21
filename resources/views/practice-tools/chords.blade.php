@extends('layouts.app')

@push('head')
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            important: true,
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        display: ['"Bebas Neue"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        .tw-dash { font-family: 'Plus Jakarta Sans', sans-serif !important; }
        .tw-dash .font-display { font-family: 'Bebas Neue', cursive; letter-spacing: 1px; }
        body > nav { display: none !important; }
        
        /* Fretboard Styles */
        .fretboard-container {
            width: 100%;
            overflow-x: auto;
            padding: 20px 0;
        }
        .fretboard {
            display: flex;
            flex-direction: column;
            width: max-content;
            background-color: #3f2e1a; /* wood color */
            border: 2px solid #2b1f11;
            border-radius: 4px;
            position: relative;
            background-image: linear-gradient(90deg, transparent 96%, #a3a3a3 96%, #a3a3a3 100%);
            background-size: 80px 100%;
        }
        .string {
            display: flex;
            height: 30px;
            position: relative;
        }
        .string::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(to bottom, #eee, #999);
            transform: translateY(-50%);
            z-index: 1;
            box-shadow: 0 1px 2px rgba(0,0,0,0.5);
        }
        /* Thick strings at the bottom */
        .string:nth-child(5)::before, .string:nth-child(6)::before { height: 3px; }
        .string:nth-child(6)::before { height: 4px; }
        
        .fret {
            width: 80px;
            height: 100%;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .fret:first-child { border-left: 8px solid #ddd; width: 60px; } /* Nut */

        .note-dot {
            width: 22px;
            height: 22px;
            background: #a855f7; /* purple-500 */
            border-radius: 50%;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: bold;
            color: white;
            box-shadow: 0 0 10px rgba(168, 85, 247, 0.8);
            border: 2px solid #fff;
        }
        .muted-cross {
            color: #ef4444;
            font-size: 20px;
            font-weight: bold;
            z-index: 10;
            text-shadow: 0 0 5px #000;
        }
        .open-circle {
            width: 14px;
            height: 14px;
            border: 2px solid #10b981;
            border-radius: 50%;
            z-index: 10;
            background: rgba(0,0,0,0.5);
        }
        
        /* Inlays */
        .inlay {
            position: absolute;
            width: 16px;
            height: 16px;
            background: rgba(255,255,255,0.7);
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 0;
            box-shadow: inset 0 0 5px rgba(0,0,0,0.3);
        }
    </style>
@endpush

@section('content')
<div class="tw-dash min-h-screen flex flex-col antialiased text-gray-200">
    @include('layouts.lms_header')

    <main class="flex-1 w-full flex flex-col items-center p-4 py-10" x-data="chordLibrary()" x-init="drawFretboard()">
        
        <div class="w-full max-w-5xl bg-zinc-900/80 backdrop-blur-md rounded-[32px] border border-zinc-800 p-8 shadow-2xl relative">
            <a href="{{ route('practice.index') }}" class="absolute top-6 left-6 text-gray-400 hover:text-white transition">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>

            <div class="text-center mb-10 mt-4">
                <h1 class="font-display text-4xl text-white mb-2">Chord <span class="text-purple-500">Library</span></h1>
                <p class="text-gray-400 text-sm">Select a root note and chord type to visualize it on the fretboard.</p>
            </div>

            <!-- Controls -->
            <div class="flex flex-wrap justify-center gap-4 mb-10">
                <select x-model="selectedRoot" @change="updateChord" class="bg-zinc-800 border border-zinc-700 text-white rounded-xl px-6 py-3 font-bold outline-none focus:border-purple-500 text-lg">
                    <template x-for="note in rootNotes" :key="note">
                        <option :value="note" x-text="note"></option>
                    </template>
                </select>

                <select x-model="selectedType" @change="updateChord" class="bg-zinc-800 border border-zinc-700 text-white rounded-xl px-6 py-3 font-bold outline-none focus:border-purple-500 text-lg">
                    <template x-for="(type, key) in chordTypes" :key="key">
                        <option :value="key" x-text="type.name"></option>
                    </template>
                </select>
            </div>

            <div class="text-center mb-6">
                <h2 class="text-5xl font-display text-white transition-all"><span x-text="selectedRoot"></span><span class="text-purple-400" x-text="selectedType"></span></h2>
            </div>

            <!-- Fretboard -->
            <div class="fretboard-container bg-black/50 rounded-2xl p-4 border border-zinc-800 shadow-inner">
                <div class="fretboard" id="fretboard">
                    <!-- Strings generated by Alpine/JS -->
                    <template x-for="(string, sIdx) in 6" :key="sIdx">
                        <div class="string">
                            <template x-for="(fret, fIdx) in 13" :key="fIdx">
                                <div class="fret relative">
                                    <!-- Nut indicator -->
                                    <template x-if="sIdx === 5 && (fIdx === 3 || fIdx === 5 || fIdx === 7 || fIdx === 9)">
                                        <div class="inlay" style="top: -45px;"></div>
                                    </template>
                                    <template x-if="sIdx === 5 && fIdx === 12">
                                        <div class="inlay" style="top: -30px;"></div>
                                    </template>
                                    <template x-if="sIdx === 5 && fIdx === 12">
                                        <div class="inlay" style="top: -60px;"></div>
                                    </template>

                                    <!-- Dots for notes -->
                                    <template x-if="hasNote(sIdx, fIdx)">
                                        <div class="note-dot" x-text="getNoteFinger(sIdx, fIdx)"></div>
                                    </template>
                                    
                                    <!-- Muted / Open string indicators on fret 0 -->
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
            
            <div class="text-center mt-6 text-gray-400 text-sm">
                Fret 0 represents open strings.
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
        
        // Dictionary of positions: [String1, String2, String3, String4, String5, String6]
        // -1 means muted, 0 means open, >0 means fret number.
        // Array index 0 is high E string, index 5 is low E string.
        chordDict: {
            'C': {
                'Major': [0, 1, 0, 2, 3, -1],
                'Minor': [3, 4, 5, 5, 3, -1], // barre
                '7':     [0, 1, 3, 2, 3, -1],
                'm7':    [3, 4, 3, 5, 3, -1],
                'maj7':  [0, 0, 0, 2, 3, -1]
            },
            'D': {
                'Major': [2, 3, 2, 0, -1, -1],
                'Minor': [1, 3, 2, 0, -1, -1],
                '7':     [2, 1, 2, 0, -1, -1],
                'm7':    [1, 1, 2, 0, -1, -1],
                'maj7':  [2, 2, 2, 0, -1, -1]
            },
            'E': {
                'Major': [0, 0, 1, 2, 2, 0],
                'Minor': [0, 0, 0, 2, 2, 0],
                '7':     [0, 0, 1, 0, 2, 0],
                'm7':    [0, 0, 0, 0, 2, 0],
                'maj7':  [0, 0, 1, 1, 2, 0]
            },
            'F': {
                'Major': [1, 1, 2, 3, 3, 1], // barre
                'Minor': [1, 1, 1, 3, 3, 1],
                '7':     [1, 1, 2, 1, 3, 1],
                'm7':    [1, 1, 1, 1, 3, 1],
                'maj7':  [0, 1, 2, 3, -1, -1]
            },
            'G': {
                'Major': [3, 0, 0, 0, 2, 3],
                'Minor': [3, 3, 3, 5, 5, 3], // barre
                '7':     [1, 0, 0, 0, 2, 3],
                'm7':    [3, 3, 3, 3, 5, 3],
                'maj7':  [2, 0, 0, 0, 2, 3]
            },
            'A': {
                'Major': [0, 2, 2, 2, 0, -1],
                'Minor': [0, 1, 2, 2, 0, -1],
                '7':     [0, 2, 0, 2, 0, -1],
                'm7':    [0, 1, 0, 2, 0, -1],
                'maj7':  [0, 2, 1, 2, 0, -1]
            },
            'B': {
                'Major': [2, 4, 4, 4, 2, -1], // barre
                'Minor': [2, 3, 4, 4, 2, -1],
                '7':     [2, 4, 2, 4, 2, -1],
                'm7':    [2, 3, 2, 4, 2, -1],
                'maj7':  [2, 4, 3, 4, 2, -1]
            },
            // Fallback for others (just a basic barre shape based on E or A shape)
        },
        
        chordTypes: {
            'Major': { name: 'Major' },
            'Minor': { name: 'Minor (m)' },
            '7': { name: 'Dominant 7' },
            'm7': { name: 'Minor 7 (m7)' },
            'maj7': { name: 'Major 7 (maj7)' }
        },

        currentPositions: [],

        drawFretboard() {
            this.updateChord();
        },

        updateChord() {
            // Very simplified logic for demo: If explicit chord exists, use it.
            // Otherwise, calculate a barre chord position.
            if (this.chordDict[this.selectedRoot] && this.chordDict[this.selectedRoot][this.selectedType]) {
                this.currentPositions = this.chordDict[this.selectedRoot][this.selectedType];
            } else {
                // Calculate based on E shape or A shape barre
                let eStringRoot = this.notesOnString(this.rootNotes, 'E');
                let rootFret = eStringRoot.indexOf(this.selectedRoot);
                
                if (rootFret !== -1) {
                    // E Shape barre
                    if (this.selectedType === 'Major') this.currentPositions = [rootFret, rootFret, rootFret+1, rootFret+2, rootFret+2, rootFret];
                    if (this.selectedType === 'Minor') this.currentPositions = [rootFret, rootFret, rootFret, rootFret+2, rootFret+2, rootFret];
                    if (this.selectedType === '7')     this.currentPositions = [rootFret, rootFret, rootFret+1, rootFret, rootFret+2, rootFret];
                    if (this.selectedType === 'm7')    this.currentPositions = [rootFret, rootFret, rootFret, rootFret, rootFret+2, rootFret];
                    if (this.selectedType === 'maj7')  this.currentPositions = [rootFret, rootFret, rootFret+1, rootFret+1, rootFret+2, rootFret];
                } else {
                    this.currentPositions = [-1,-1,-1,-1,-1,-1]; // Not found
                }
            }
        },

        notesOnString(notes, openNote) {
            let startIndex = notes.indexOf(openNote);
            let stringNotes = [];
            for (let i = 0; i <= 12; i++) {
                stringNotes.push(notes[(startIndex + i) % 12]);
            }
            return stringNotes;
        },

        hasNote(sIdx, fIdx) {
            if (fIdx === 0) return false; // Open handled separately
            return this.currentPositions[sIdx] === fIdx;
        },
        
        getNoteFinger(sIdx, fIdx) {
            return ''; // could return finger numbering if we added it to data
        },

        getMuted(sIdx) {
            return this.currentPositions[sIdx] === -1;
        },

        isOpen(sIdx) {
            return this.currentPositions[sIdx] === 0;
        }
    }
}
</script>
@endsection
