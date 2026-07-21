@extends('layouts.app')

@push('head')
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            important: true,
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            black: '#0a0a0c',
                            card: '#121218',
                            border: '#222230',
                            accent: '#0066ff',
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
        .tw-dash { font-family: 'Plus Jakarta Sans', sans-serif !important; }
        .tw-dash .font-display { font-family: 'Bebas Neue', cursive; letter-spacing: 1px; }
        body > nav { display: none !important; }
        
        .needle-container {
            width: 100%;
            height: 150px;
            position: relative;
            overflow: hidden;
        }
        .needle {
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 4px;
            height: 140px;
            background-color: #fff;
            transform-origin: bottom center;
            border-radius: 4px;
            transition: transform 0.1s ease-out, background-color 0.2s;
            z-index: 10;
        }
        .tuner-dial {
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 300px;
            height: 150px;
            border-top-left-radius: 150px;
            border-top-right-radius: 150px;
            border: 4px solid #333;
            border-bottom: none;
        }
        .tick {
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 2px;
            height: 15px;
            background: #555;
            transform-origin: bottom center;
        }
        .tick-center { height: 25px; background: #3b82f6; width: 4px; }
    </style>
@endpush

@section('content')
<div class="tw-dash min-h-screen flex flex-col antialiased text-gray-200">
    @include('layouts.lms_header')

    <main class="flex-1 w-full flex items-center justify-center p-4">
        <div class="w-full max-w-lg bg-zinc-900/80 backdrop-blur-md rounded-[32px] border border-zinc-800 p-8 shadow-2xl relative overflow-hidden text-center"
             x-data="tunerApp()" x-init="initTuner()">
            
            <a href="{{ route('practice.index') }}" class="absolute top-6 left-6 text-gray-400 hover:text-white transition">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>

            <h1 class="font-display text-4xl text-white mt-4 mb-2">Guitar <span class="text-blue-500">Tuner</span></h1>
            <p class="text-gray-400 text-sm mb-8">Allow microphone access and pluck a string.</p>
            
            <div x-show="!isListening" class="py-10">
                <button @click="startListening()" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-4 px-10 rounded-full text-lg shadow-[0_0_20px_rgba(37,99,235,0.4)] transition hover:scale-105 active:scale-95">
                    <i class="fa-solid fa-microphone mr-2"></i> Start Tuner
                </button>
                <p class="text-xs text-gray-500 mt-4" x-text="errorMsg"></p>
            </div>

            <div x-show="isListening" style="display: none;" class="w-full">
                <!-- The Dial -->
                <div class="needle-container mb-4">
                    <div class="tuner-dial"></div>
                    <!-- Ticks -->
                    <template x-for="i in 9">
                        <div class="tick" :class="{'tick-center': i === 5}" :style="`height: 150px; transform: translateX(-50%) rotate(${(i-5) * 15}deg)`"></div>
                    </template>
                    <div class="needle" :style="`transform: translateX(-50%) rotate(${needleAngle}deg); background-color: ${needleColor};`" style="transform: translateX(-50%) rotate(0deg);"></div>
                </div>

                <!-- Note Display -->
                <div class="flex items-center justify-center gap-4 mb-4">
                    <div class="text-7xl font-display font-bold text-white transition-colors" :style="`color: ${needleColor}`" x-text="currentNote">--</div>
                    <div class="text-3xl font-display text-gray-400" x-text="currentCents > 0 ? '+' + currentCents : currentCents">0</div>
                </div>
                
                <div class="text-sm font-semibold tracking-widest uppercase mb-8" :style="`color: ${needleColor}`" x-text="tuneStatus">Waiting...</div>
                
                <!-- Frequency -->
                <div class="bg-black/50 rounded-lg p-3 inline-block border border-zinc-800">
                    <span class="text-gray-400 text-xs uppercase tracking-wider">Frequency</span>
                    <div class="font-mono text-lg text-white mt-1"><span x-text="frequency">0.00</span> Hz</div>
                </div>

                <div class="mt-8">
                    <button @click="stopListening()" class="text-gray-400 hover:text-white text-sm underline decoration-zinc-700 underline-offset-4">Stop Listening</button>
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
                // Check if the browser supports mediaDevices (it gets disabled on non-localhost HTTP)
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    throw new Error("Browser API for microphone is disabled. Ini biasanya terjadi jika Anda tidak mengakses web lewat 'http://localhost:8000' (menggunakan IP biasa seperti 192.168.x.x memblokir fitur ini).");
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
                    this.errorMsg = 'Akses diblokir oleh browser! Anda harus mengklik icon Kamera/Mikrofon (atau icon gembok) di kolom URL/Address Bar di atas, lalu ubah izin Microphone menjadi "Allow", kemudian refresh halaman ini.';
                } else if (err.name === 'NotFoundError') {
                    this.errorMsg = 'Microphone tidak terdeteksi di perangkat Anda.';
                } else {
                    this.errorMsg = 'Error: ' + err.message + ' (Pastikan klik "Allow" dan akses via localhost!)';
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
                
                // Map -50 to +50 cents to -60 to +60 degrees
                this.needleAngle = (cents / 50) * 60;
                
                if (Math.abs(cents) < 5) {
                    this.needleColor = '#10b981'; // emerald
                    this.tuneStatus = 'IN TUNE';
                } else if (cents < 0) {
                    this.needleColor = '#f59e0b'; // amber
                    this.tuneStatus = 'FLAT (TUNE UP)';
                } else {
                    this.needleColor = '#ef4444'; // red
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
            if (rms < 0.01) return -1; // Not enough signal

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
