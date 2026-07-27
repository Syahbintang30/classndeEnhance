@extends('layouts.app')

@section('title', 'Frequently Asked Questions - Guitarclassbynde')

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
        body { background-color: #08080a !important; color: #ffffff !important; font-family: 'Plus Jakarta Sans', sans-serif !important; }
        .font-display { font-family: 'Bebas Neue', cursive !important; letter-spacing: 1px; }
        body > nav, .global-nav { display: none !important; }
        .glass-panel {
            background: rgba(12, 12, 18, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
@endpush

@section('content')
@php
    $isEn = (session('app_lang', request('lang', 'id')) === 'en');
    $defaultFaqs = $isEn ? [
        [
            'category' => 'Courses & Access',
            'question' => 'What is Guitarclassbynde?',
            'answer' => 'Guitarclassbynde is an elite, structured online guitar learning platform founded by Nde. It combines step-by-step video courses, interactive practice tools (Tuner, Metronome, Chord & Scale visualizers), song library tutorials, and live 1-on-1 coaching sessions.'
        ],
        [
            'category' => 'Courses & Access',
            'question' => 'How long do I get access to my purchased courses?',
            'answer' => 'Once enrolled, you get lifetime access to all course modules, materials, and practice suite tools included in your package. You can learn at your own pace whenever you want.'
        ],
        [
            'category' => 'Courses & Access',
            'question' => 'Do I need prior guitar experience to get started?',
            'answer' => 'No experience needed! Our courses start from absolute beginner fundamentals (holding the guitar, open chords, basic strumming) all the way up to advanced soloing, speed picking, and fretboard theory.'
        ],
        [
            'category' => 'Coaching',
            'question' => 'How do 1-on-1 Coaching Sessions work?',
            'answer' => 'Coaching sessions are conducted live directly inside our platform’s built-in interactive Video Call Room! After purchasing a package or coaching ticket, pick an open date & time slot in your dashboard. When your session time arrives, simply click "Join Video Session" to enter the live room with Nde!'
        ],
        [
            'category' => 'Payments & Pricing',
            'question' => 'What payment channels are supported?',
            'answer' => 'We accept instant, automated payments via Midtrans including Bank Transfer (Virtual Accounts for BCA, Mandiri, BNI, BRI), QRIS, GoPay, ShopeePay, and major Credit Cards.'
        ],
    ] : [
        [
            'category' => 'Kelas & Akses',
            'question' => 'Apa itu Guitarclassbynde?',
            'answer' => 'Guitarclassbynde adalah platform belajar gitar online elit terstruktur yang didirikan oleh Nde. Menggabungkan kelas video bertahap, tools latihan interaktif (Tuner, Metronom, Chord & Scale visualizer), tutorial lagu, dan sesi coaching live 1-on-1.'
        ],
        [
            'category' => 'Kelas & Akses',
            'question' => 'Berapa lama saya mendapatkan akses kelas yang sudah dibeli?',
            'answer' => 'Setelah mendaftar, kamu mendapatkan akses seumur hidup (lifetime access) ke seluruh modul kelas, materi, dan tools latihan yang ada pada paketmu. Kamu bisa belajar kapan saja sesuai kecepatanmu sendiri.'
        ],
        [
            'category' => 'Kelas & Akses',
            'question' => 'Apakah saya butuh pengalaman main gitar sebelumnya?',
            'answer' => 'Tidak perlu pengalaman sama sekali! Kelas kami dimulai dari dasar paling awal (cara memegang gitar, chord open, strumming dasar) hingga teknik solo, picking cepat, dan teori fretboard.'
        ],
        [
            'category' => 'Coaching',
            'question' => 'Bagaimana cara kerja Sesi Coaching 1-on-1?',
            'answer' => 'Sesi coaching dilakukan secara live langsung di dalam website kami via Video Call Room interaktif! Kamu tinggal pilih jadwal yang tersedia di dashboard, dan saat sesi dimulai, klik "Masuk Sesi Video" untuk bertatap muka langsung dengan Nde.'
        ],
        [
            'category' => 'Pembayaran',
            'question' => 'Metode pembayaran apa saja yang didukung?',
            'answer' => 'Kami menerima pembayaran otomatis serba instan via Midtrans meliputi Transfer Bank (Virtual Account BCA, Mandiri, BNI, BRI), QRIS, GoPay, ShopeePay, dan Kartu Kredit.'
        ],
    ];

    $allFaqs = [];
    if (isset($faq_items) && count($faq_items) > 0) {
        foreach ($faq_items as $item) {
            $q = method_exists($item, 'getQuestionForLocale') ? $item->getQuestionForLocale($isEn ? 'en' : 'id') : ($item->question ?? '');
            $a = method_exists($item, 'getAnswerForLocale') ? $item->getAnswerForLocale($isEn ? 'en' : 'id') : ($item->answer ?? '');
            $allFaqs[] = [
                'category' => $item->category ?? ($isEn ? 'General' : 'Umum'),
                'question' => $q,
                'answer' => $a,
            ];
        }
    } else {
        $allFaqs = $defaultFaqs;
    }
@endphp

<div class="min-h-screen bg-[#08080a] text-white flex flex-col relative overflow-hidden pb-16 selection:bg-blue-600 selection:text-white"
     x-data="{ 
        activeCategory: 'All', 
        searchQuery: '',
        activeAccordion: 0,
        faqs: {{ json_encode($allFaqs) }},
        get filteredFaqs() {
            return this.faqs.filter(item => {
                const matchesCategory = this.activeCategory === 'All' || item.category === this.activeCategory;
                const matchesSearch = !this.searchQuery || 
                    item.question.toLowerCase().includes(this.searchQuery.toLowerCase()) || 
                    item.answer.toLowerCase().includes(this.searchQuery.toLowerCase());
                return matchesCategory && matchesSearch;
            });
        }
     }">
    
    {{-- Ambient Mesh Background Glows --}}
    <div class="absolute top-1/4 left-1/3 w-[600px] h-[600px] bg-blue-600/15 rounded-full blur-[150px] pointer-events-none z-0 mix-blend-screen"></div>
    <div class="absolute bottom-1/3 right-1/4 w-[500px] h-[500px] bg-purple-600/15 rounded-full blur-[150px] pointer-events-none z-0 mix-blend-screen"></div>

    {{-- LMS Floating Glass Pill Header --}}
    <div class="relative z-20">
        @include('layouts.lms_header')
    </div>

    {{-- Main FAQ Content --}}
    <main class="flex-1 w-full max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10 space-y-10">
        
        <!-- Header Title & Subtitle -->
        <div class="text-center space-y-4">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-bold uppercase tracking-widest">
                Help Center &amp; Support
            </div>
            <h1 class="font-display text-4xl sm:text-6xl text-white tracking-wide uppercase leading-none">
                Frequently Asked <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">Questions</span>
            </h1>
            <p class="text-gray-400 text-xs sm:text-base max-w-2xl mx-auto leading-relaxed">
                Find answers to everything you need to know about Guitarclassbynde, course access, practice suite tools, and 1-on-1 coaching sessions.
            </p>
        </div>

        <!-- Search Bar & Category Filter Pills -->
        <div class="space-y-6 max-w-3xl mx-auto">
            <!-- Search Bar -->
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" 
                       x-model="searchQuery" 
                       placeholder="Search questions (e.g. access, coaching, tuner, payment)..." 
                       class="w-full pl-11 pr-4 py-3.5 rounded-2xl bg-zinc-950/70 border border-white/10 text-white text-xs sm:text-sm placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition backdrop-blur-xl shadow-lg">
            </div>

            <!-- Filter Pills -->
            <div class="flex items-center justify-center flex-wrap gap-2 text-xs">
                <button @click="activeCategory = 'All'" 
                        :class="activeCategory === 'All' ? 'bg-blue-600 text-white font-bold shadow-lg shadow-blue-600/30 border-blue-500' : 'bg-white/5 text-gray-400 border-white/10 hover:text-white hover:bg-white/10'" 
                        class="px-4 py-2 rounded-full border transition-all cursor-pointer">
                    All Questions
                </button>
                <button @click="activeCategory = 'Courses & Access'" 
                        :class="activeCategory === 'Courses & Access' ? 'bg-blue-600 text-white font-bold shadow-lg shadow-blue-600/30 border-blue-500' : 'bg-white/5 text-gray-400 border-white/10 hover:text-white hover:bg-white/10'" 
                        class="px-4 py-2 rounded-full border transition-all cursor-pointer">
                    Courses &amp; Access
                </button>
                <button @click="activeCategory = 'Coaching'" 
                        :class="activeCategory === 'Coaching' ? 'bg-blue-600 text-white font-bold shadow-lg shadow-blue-600/30 border-blue-500' : 'bg-white/5 text-gray-400 border-white/10 hover:text-white hover:bg-white/10'" 
                        class="px-4 py-2 rounded-full border transition-all cursor-pointer">
                    1-on-1 Coaching
                </button>
                <button @click="activeCategory = 'Payments & Pricing'" 
                        :class="activeCategory === 'Payments & Pricing' ? 'bg-blue-600 text-white font-bold shadow-lg shadow-blue-600/30 border-blue-500' : 'bg-white/5 text-gray-400 border-white/10 hover:text-white hover:bg-white/10'" 
                        class="px-4 py-2 rounded-full border transition-all cursor-pointer">
                    Payments &amp; Pricing
                </button>
                <button @click="activeCategory = 'Technical'" 
                        :class="activeCategory === 'Technical' ? 'bg-blue-600 text-white font-bold shadow-lg shadow-blue-600/30 border-blue-500' : 'bg-white/5 text-gray-400 border-white/10 hover:text-white hover:bg-white/10'" 
                        class="px-4 py-2 rounded-full border transition-all cursor-pointer">
                    Technical
                </button>
            </div>
        </div>

        <!-- FAQ Accordion List -->
        <div class="space-y-4 max-w-4xl mx-auto">
            <template x-for="(faq, idx) in filteredFaqs" :key="idx">
                <div class="glass-panel rounded-2xl border border-white/10 overflow-hidden transition-all duration-300"
                     :class="{ 'border-blue-500/40 bg-zinc-950/80 shadow-[0_0_30px_rgba(59,130,246,0.15)]': activeAccordion === idx }">
                    <button @click="activeAccordion = (activeAccordion === idx ? null : idx)" 
                            class="w-full p-5 sm:p-6 text-left flex items-center justify-between gap-4 cursor-pointer hover:bg-white/5 transition-colors focus:outline-none">
                        <span class="font-bold text-white text-sm sm:text-base flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs flex items-center justify-center font-mono font-bold shrink-0 shadow-sm transition-all"
                                  :class="{ 'bg-blue-600 text-white border-blue-500 shadow-blue-600/30': activeAccordion === idx }"
                                  x-text="(idx + 1) < 10 ? '0' + (idx + 1) : (idx + 1)">
                            </span>
                            <span x-text="faq.question" class="transition-colors" :class="{ 'text-blue-300': activeAccordion === idx }"></span>
                        </span>
                        <div class="w-8 h-8 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 shrink-0 transition-all duration-300"
                             :class="{ 'rotate-180 text-blue-400 bg-blue-500/10 border-blue-500/20 shadow-md': activeAccordion === idx }">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </div>
                    </button>
                    <div x-show="activeAccordion === idx" 
                         x-collapse
                         class="px-5 sm:px-6 pt-2 pb-6 sm:pb-8 text-xs sm:text-sm text-gray-300 leading-relaxed">
                        <div class="p-5 sm:p-6 rounded-2xl bg-white/[0.03] border border-white/5 text-gray-300 leading-relaxed shadow-inner my-1"
                             x-text="faq.answer">
                        </div>
                    </div>

                </div>
            </template>

            <!-- Empty State if no FAQs match search -->
            <div x-show="filteredFaqs.length === 0" class="glass-panel p-12 text-center rounded-2xl space-y-3">
                <i class="fa-solid fa-circle-question text-3xl text-gray-500"></i>
                <h3 class="text-white font-bold text-base">No matching questions found</h3>
                <p class="text-gray-400 text-xs max-w-sm mx-auto">Try clearing your search query or switching category filters.</p>
                <button @click="searchQuery = ''; activeCategory = 'All';" class="px-4 py-2 rounded-xl bg-blue-600 text-white text-xs font-bold transition">Clear Filters</button>
            </div>
        </div>

        <!-- Support CTA Footer Box -->
        <div class="glass-panel p-8 sm:p-12 text-center rounded-3xl max-w-3xl mx-auto space-y-4 border border-blue-500/20 bg-gradient-to-br from-blue-950/20 via-zinc-950 to-indigo-950/20">
            <div class="w-12 h-12 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center text-xl mx-auto shadow-lg">
                <i class="fa-solid fa-headset"></i>
            </div>
            <h3 class="font-display text-3xl text-white tracking-wide uppercase">Still Have Questions?</h3>
            <p class="text-gray-300 text-xs sm:text-sm max-w-md mx-auto leading-relaxed">
                Can’t find the answer you’re looking for? Reach out to Nde &amp; the team and we will be happy to assist you.
            </p>
            <div class="pt-2">
                <a href="{{ route('compro') }}#packages" class="px-7 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold rounded-xl text-xs shadow-lg shadow-blue-600/30 transition-all hover:scale-105 inline-flex items-center gap-2">
                    <span>Explore Course Packages</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

    </main>

</div>
@endsection
