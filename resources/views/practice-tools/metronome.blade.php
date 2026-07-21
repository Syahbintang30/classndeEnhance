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
            box-shadow: 0 0 10px rgba(16, 185, 129, 0.5);
        }
        input[type=range]::-webkit-slider-runnable-track {
            width: 100%;
            height: 8px;
            cursor: pointer;
            background: #27272a;
            border-radius: 4px;
        }
    </style>
@endpush

@section('content')
<div class="tw-dash min-h-screen flex flex-col antialiased text-gray-200">
    @include('layouts.lms_header')

    <main class="flex-1 w-full flex items-center justify-center p-4">
        <div class="w-full max-w-lg bg-zinc-900/80 backdrop-blur-md rounded-[32px] border border-zinc-800 p-8 shadow-2xl relative text-center"
             x-data="metronomeApp()" x-init="initMetronome()">
            
            <a href="{{ route('practice.index') }}" class="absolute top-6 left-6 text-gray-400 hover:text-white transition">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>

            <h1 class="font-display text-4xl text-white mt-4 mb-2"><span class="text-emerald-500">Metronome</span></h1>
            
            <div class="py-8">
                <div class="text-7xl font-display font-bold text-white mb-2 transition-all duration-75" :class="{'scale-110 text-emerald-400': isBeatPulse}">
                    <span x-text="bpm">120</span> <span class="text-3xl text-gray-500">BPM</span>
                </div>
                
                <div class="flex items-center justify-center gap-4 mb-8">
                    <button @click="bpm = Math.max(30, bpm - 1)" class="w-10 h-10 rounded-full bg-zinc-800 hover:bg-zinc-700 flex items-center justify-center text-white"><i class="fa-solid fa-minus"></i></button>
                    <input type="range" min="30" max="240" x-model.number="bpm" class="w-48" @input="updateInterval">
                    <button @click="bpm = Math.min(240, bpm + 1)" class="w-10 h-10 rounded-full bg-zinc-800 hover:bg-zinc-700 flex items-center justify-center text-white"><i class="fa-solid fa-plus"></i></button>
                </div>

                <!-- Beat Indicators -->
                <div class="flex justify-center gap-3 mb-8 h-10">
                    <template x-for="i in beatsPerMeasure">
                        <div class="w-4 h-4 rounded-full transition-all duration-100"
                             :class="{
                                 'bg-emerald-500 shadow-[0_0_15px_rgba(16,185,129,0.8)] scale-125': currentBeat === i && isPlaying,
                                 'bg-emerald-300 shadow-[0_0_10px_rgba(110,231,183,0.5)]': currentBeat === i && i !== 1 && isPlaying,
                                 'bg-zinc-800': currentBeat !== i || !isPlaying
                             }">
                        </div>
                    </template>
                </div>

                <div class="flex justify-center gap-4 mb-8">
                    <select x-model.number="beatsPerMeasure" class="bg-zinc-800 border border-zinc-700 text-white rounded-lg px-4 py-2 outline-none focus:border-emerald-500">
                        <option value="2">2/4</option>
                        <option value="3">3/4</option>
                        <option value="4">4/4</option>
                        <option value="5">5/4</option>
                        <option value="6">6/8</option>
                    </select>
                </div>

                <button @click="togglePlay()" class="w-20 h-20 rounded-full text-2xl text-white shadow-[0_0_20px_rgba(16,185,129,0.4)] transition hover:scale-105 active:scale-95 flex items-center justify-center mx-auto"
                        :class="isPlaying ? 'bg-red-500 hover:bg-red-400 shadow-[0_0_20px_rgba(239,68,68,0.4)]' : 'bg-emerald-500 hover:bg-emerald-400'">
                    <i class="fa-solid" :class="isPlaying ? 'fa-stop' : 'fa-play ml-1'"></i>
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
        
        audioContext: null,
        nextNoteTime: 0,
        timerID: null,
        lookahead: 25.0,
        scheduleAheadTime: 0.1,
        
        initMetronome() {
            // Watchers not strictly needed due to @input, but nice for programatic changes
            this.$watch('bpm', () => { this.bpm = parseInt(this.bpm); });
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

            if (isFirstBeat) {
                osc.frequency.value = 1000.0;
            } else {
                osc.frequency.value = 800.0;
            }

            envelope.gain.value = 1;
            envelope.gain.exponentialRampToValueAtTime(1, time + 0.001);
            envelope.gain.exponentialRampToValueAtTime(0.001, time + 0.02);

            osc.start(time);
            osc.stop(time + 0.03);

            // Visual Sync using Timeout matching AudioContext time
            const timeUntilNote = time - this.audioContext.currentTime;
            setTimeout(() => {
                this.isBeatPulse = true;
                setTimeout(() => { this.isBeatPulse = false; }, 100);
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
        },
        
        updateInterval() {
            // Handled automatically via $watch or the nextNote calculation
        }
    }
}
</script>
@endsection
