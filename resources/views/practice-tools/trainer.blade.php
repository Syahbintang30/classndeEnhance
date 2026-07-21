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
                            rose: '#f43f5e',
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
        
        /* Dark Rosewood Fretboard Styles */
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

        /* Quiz Target Pulsing Dot */
        .quiz-target-dot {
            width: 28px;
            height: 28px;
            background: linear-gradient(135deg, #f43f5e, #e11d48);
            border-radius: 50%;
            z-index: 12;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 900;
            color: white;
            box-shadow: 0 0 20px rgba(244, 63, 94, 0.9), inset 0 0 4px rgba(255,255,255,0.8);
            border: 2.5px solid #ffffff;
            animation: pulse-quiz 1.2s infinite ease-in-out;
        }

        @keyframes pulse-quiz {
            0%, 100% { transform: scale(1); box-shadow: 0 0 15px rgba(244, 63, 94, 0.8); }
            50% { transform: scale(1.15); box-shadow: 0 0 25px rgba(244, 63, 94, 1); }
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
     x-data="trainerQuizApp()" 
     x-init="initQuiz()">

    {{-- Ambient Mesh Background Glow --}}
    <div class="absolute -top-32 left-1/3 w-[600px] h-[600px] bg-rose-600/10 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute top-1/2 -right-32 w-[450px] h-[450px] bg-amber-600/10 rounded-full blur-[120px] pointer-events-none"></div>

    {{-- ─── TOP NAVIGATION BAR ──────────────────────────────────────────── --}}
    @include('layouts.lms_header')

    <main class="flex-1 max-w-5xl mx-auto w-full px-4 lg:px-8 py-8 space-y-8 relative z-10">
        
        <!-- BACK & TITLE HEADER -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <a href="{{ route('practice.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-white bg-zinc-950/60 border border-white/10 px-4 py-2 rounded-xl backdrop-blur-md transition self-start">
                <i class="fa-solid fa-arrow-left"></i> Back to Practice Tools
            </a>
            
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs font-bold uppercase tracking-wider self-start sm:self-auto">
                <i class="fa-solid fa-gamepad"></i> Interactive Ear & Fretboard Trainer
            </div>
        </div>

        <!-- MAIN QUIZ GLASS CARD -->
        <div class="glass-panel p-6 sm:p-8 relative overflow-hidden space-y-8">
            
            <div class="text-center space-y-2">
                <h1 class="font-display text-4xl sm:text-5xl text-white tracking-wide uppercase">
                    Ear & Fretboard <span class="text-rose-400">Trainer Quiz</span>
                </h1>
                <p class="text-gray-400 text-xs max-w-md mx-auto">
                    Challenge your ears and fretboard note memory. Earn score points and build your streak!
                </p>
            </div>

            <!-- SCORE & STREAK DASHBOARD -->
            <div class="grid grid-cols-3 gap-4 max-w-xl mx-auto bg-zinc-950/70 border border-white/10 rounded-2xl p-4 text-center">
                <div>
                    <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest block">Total Score</span>
                    <span class="font-display text-3xl text-amber-400" x-text="score">0</span>
                </div>
                <div>
                    <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest block">Current Streak</span>
                    <span class="font-display text-3xl text-rose-400 flex items-center justify-center gap-1">
                        <span x-text="streak">0</span>
                        <i class="fa-solid fa-fire text-amber-500 text-lg"></i>
                    </span>
                </div>
                <div>
                    <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest block">Accuracy</span>
                    <span class="font-display text-3xl text-emerald-400" x-text="getAccuracy() + '%'">100%</span>
                </div>
            </div>

            <!-- QUIZ MODE SELECTOR PILLS -->
            <div class="space-y-2">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block text-center">Choose Challenge Mode</span>
                <div class="flex justify-center gap-3">
                    <button @click="setMode('fretboard')" :class="mode === 'fretboard' ? 'bg-rose-600 text-white font-bold border-rose-400' : 'bg-zinc-950/60 text-gray-400 border-white/5'" class="px-5 py-2 rounded-xl border text-xs transition flex items-center gap-2">
                        <i class="fa-solid fa-bullseye"></i> Fretboard Note Finder
                    </button>
                    <button @click="setMode('ear')" :class="mode === 'ear' ? 'bg-rose-600 text-white font-bold border-rose-400' : 'bg-zinc-950/60 text-gray-400 border-white/5'" class="px-5 py-2 rounded-xl border text-xs transition flex items-center gap-2">
                        <i class="fa-solid fa-ear-listen"></i> Ear Training Pitch Quiz
                    </button>
                </div>
            </div>

            <!-- MODE 1: FRETBOARD NOTE FINDER -->
            <div x-show="mode === 'fretboard'" class="space-y-6">
                <div class="text-center space-y-1">
                    <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">Question</div>
                    <h3 class="text-xl font-bold text-white">What note is located at the glowing red dot?</h3>
                    <p class="text-xs text-gray-400">Look at the fretboard below and select the correct note name.</p>
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
                                    <div class="string-label" x-text="stringNames[sIdx]"></div>
                                    <div class="string-wire"></div>

                                    <template x-for="(fret, fIdx) in 13" :key="fIdx">
                                        <div class="fret-cell">
                                            <template x-if="sIdx === 2 && (fIdx === 3 || fIdx === 5 || fIdx === 7 || fIdx === 9 || fIdx === 12)">
                                                <div class="fret-marker-dot"></div>
                                            </template>

                                            <!-- Glowing target dot -->
                                            <template x-if="targetStringIdx === sIdx && targetFretIdx === fIdx">
                                                <div class="quiz-target-dot">?</div>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MODE 2: EAR TRAINING PITCH QUIZ -->
            <div x-show="mode === 'ear'" class="space-y-6 text-center">
                <div class="space-y-2">
                    <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">Ear Training Question</div>
                    <h3 class="text-2xl font-bold text-white">Listen to the guitar pitch note played:</h3>
                    <p class="text-xs text-gray-400">Click the button below to re-listen to the pitch sound, then guess the note.</p>
                </div>

                <div class="py-4">
                    <button @click="playTargetPitchAudio()" class="w-24 h-24 rounded-full bg-gradient-to-tr from-rose-600 to-amber-500 hover:scale-105 text-white text-3xl font-bold shadow-xl shadow-rose-600/30 transition-all flex items-center justify-center mx-auto">
                        <i class="fa-solid fa-volume-high"></i>
                    </button>
                    <span class="text-xs font-bold text-rose-400 uppercase tracking-widest mt-3 block">Click to Play Pitch</span>
                </div>
            </div>

            <!-- ANSWER OPTIONS (12 NOTE BUTTONS) -->
            <div class="space-y-3 pt-4 border-t border-white/5">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block text-center">Select Your Answer</span>
                <div class="grid grid-cols-4 sm:grid-cols-6 gap-3 max-w-2xl mx-auto">
                    <template x-for="n in notes" :key="n">
                        <button @click="submitAnswer(n)"
                                class="py-3.5 rounded-xl bg-zinc-950/70 border border-white/10 hover:border-rose-500 hover:bg-rose-500/10 text-white font-display text-2xl tracking-wider transition group cursor-pointer active:scale-95 shadow-md">
                            <span x-text="n" class="group-hover:text-rose-400"></span>
                        </button>
                    </template>
                </div>
            </div>

            <!-- FEEDBACK BANNER -->
            <div x-show="feedbackMsg" x-transition.opacity class="p-4 rounded-xl text-center text-xs font-bold uppercase tracking-wider"
                 :class="isCorrect ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-red-500/20 text-red-300 border border-red-500/30'">
                <span x-text="feedbackMsg"></span>
            </div>

        </div>
    </main>
</div>

<script>
function trainerQuizApp() {
    return {
        notes: ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B'],
        stringNames: ['1st - E4', '2nd - B3', '3rd - G3', '4th - D3', '5th - A2', '6th - E2'],
        stringOpenNoteIndices: [4, 11, 7, 2, 9, 4],
        baseFrequencies: [329.63, 246.94, 196.00, 146.83, 110.00, 82.41],

        mode: 'fretboard', // 'fretboard' or 'ear'
        score: 0,
        streak: 0,
        totalAttempts: 0,
        correctAttempts: 0,

        targetStringIdx: 0,
        targetFretIdx: 0,
        correctNoteName: '',
        correctFreq: 0,

        feedbackMsg: '',
        isCorrect: false,
        audioCtx: null,

        initQuiz() {
            this.nextQuestion();
        },

        setMode(newMode) {
            this.mode = newMode;
            this.feedbackMsg = '';
            this.nextQuestion();
        },

        getAccuracy() {
            if (this.totalAttempts === 0) return 100;
            return Math.round((this.correctAttempts / this.totalAttempts) * 100);
        },

        nextQuestion() {
            this.targetStringIdx = Math.floor(Math.random() * 6);
            this.targetFretIdx = Math.floor(Math.random() * 12) + 1; // fret 1-12
            
            const openNoteIdx = this.stringOpenNoteIndices[this.targetStringIdx];
            this.correctNoteName = this.notes[(openNoteIdx + this.targetFretIdx) % 12];
            
            const baseFreq = this.baseFrequencies[this.targetStringIdx];
            this.correctFreq = baseFreq * Math.pow(2, this.targetFretIdx / 12);

            if (this.mode === 'ear') {
                setTimeout(() => {
                    this.playTargetPitchAudio();
                }, 300);
            }
        },

        playTargetPitchAudio() {
            this.playTone(this.correctFreq, 1.2);
        },

        submitAnswer(selectedNote) {
            this.totalAttempts++;
            if (selectedNote === this.correctNoteName) {
                this.isCorrect = true;
                this.score += 10 + (this.streak * 2);
                this.streak++;
                this.correctAttempts++;
                this.feedbackMsg = `CORRECT! Nada ${this.correctNoteName} (+10 Poin)`;
                this.playChime(true);
            } else {
                this.isCorrect = false;
                this.streak = 0;
                this.feedbackMsg = `SALAH! Jawabannya adalah ${this.correctNoteName}`;
                this.playChime(false);
            }

            setTimeout(() => {
                this.feedbackMsg = '';
                this.nextQuestion();
            }, 1400);
        },

        playTone(freq, duration = 1.0) {
            if (!this.audioCtx) {
                this.audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            }
            if (this.audioCtx.state === 'suspended') {
                this.audioCtx.resume();
            }

            const osc = this.audioCtx.createOscillator();
            const gain = this.audioCtx.createGain();
            
            osc.type = 'triangle';
            osc.frequency.setValueAtTime(freq, this.audioCtx.currentTime);
            
            gain.gain.setValueAtTime(0.4, this.audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, this.audioCtx.currentTime + duration);
            
            osc.connect(gain);
            gain.connect(this.audioCtx.destination);
            
            osc.start();
            osc.stop(this.audioCtx.currentTime + duration);
        },

        playChime(success) {
            if (!this.audioCtx) return;
            const now = this.audioCtx.currentTime;
            const osc = this.audioCtx.createOscillator();
            const gain = this.audioCtx.createGain();
            osc.connect(gain);
            gain.connect(this.audioCtx.destination);

            if (success) {
                osc.frequency.setValueAtTime(523.25, now); // C5
                osc.frequency.setValueAtTime(659.25, now + 0.1); // E5
                gain.gain.setValueAtTime(0.3, now);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.4);
                osc.start(now);
                osc.stop(now + 0.4);
            } else {
                osc.frequency.setValueAtTime(200, now);
                gain.gain.setValueAtTime(0.3, now);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.3);
                osc.start(now);
                osc.stop(now + 0.3);
            }
        }
    }
}
</script>
@endsection
