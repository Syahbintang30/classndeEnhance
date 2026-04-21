<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sesi Berakhir - Nde</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;1,400;1,600&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    },
                    colors: {
                        dark: {
                            900: '#000000',
                            800: '#0A0A0A',
                            700: '#1A1A1A',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #000000;
            background-image: radial-gradient(circle at 50% 0%, rgba(255, 255, 255, 0.07) 0%, transparent 60%);
            background-attachment: fixed;
        }
    </style>
</head>
<body class="text-gray-300 min-h-screen flex flex-col font-sans antialiased selection:bg-white selection:text-black">

    <header class="w-full px-8 py-6 flex items-center justify-between z-10">
        <a href="{{ url('/ndeofficial') }}" class="flex items-center cursor-pointer" aria-label="NDE Home">
            <img src="{{ asset('compro/img/ndelogo.png') }}" alt="NDE logo" class="h-[52px] w-auto" />
        </a>

        <nav class="hidden md:flex items-center gap-8 text-sm">
            <a href="{{ url('/ndeofficial#tentang') }}" class="text-gray-400 hover:text-white transition-colors">Tentang Kelas</a>
            <a href="{{ url('/ndeofficial#sistem-belajar') }}" class="text-gray-400 hover:text-white transition-colors">Sistem Belajar</a>
            <a href="{{ url('/ndeofficial#harga') }}" class="text-gray-400 hover:text-white transition-colors">Harga</a>
        </nav>

        <div class="hidden md:block">
            <a href="{{ route('login') }}" class="text-white text-sm font-medium hover:text-gray-300 transition-colors">
                Masuk LMS
            </a>
        </div>
    </header>

    <main class="flex-1 flex flex-col items-center justify-center p-6 -mt-10">
        <div class="w-full max-w-4xl mx-auto flex flex-col items-center text-center">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-gray-800 bg-dark-800/50 text-[10px] tracking-[0.15em] text-gray-400 uppercase mb-8">
                <span class="w-1.5 h-1.5 rounded-full bg-red-500/80"></span>
                Sesi Keamanan Dihentikan
            </div>

            <div class="space-y-6 max-w-3xl">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-serif text-white leading-tight tracking-wide">
                    Sesi Anda berakhir atau terdeteksi <br class="hidden md:block" />
                    <span class="italic text-gray-300">masalah keamanan.</span>
                </h1>
                <p class="text-gray-400/80 text-base md:text-lg leading-relaxed max-w-2xl mx-auto font-light">
                    Kami mendeteksi perubahan yang membuat sesi Anda tidak lagi aman (mis. perubahan jaringan/IP atau login dari perangkat lain). Untuk melindungi akun Anda, Anda telah dikeluarkan.
                </p>
            </div>

            <div class="mt-12 text-left max-w-2xl mx-auto space-y-5">
                <div class="flex items-start gap-4">
                    <i data-lucide="log-in" class="w-5 h-5 text-gray-500 mt-0.5 shrink-0"></i>
                    <p class="text-gray-400 font-light"><strong class="text-white font-medium">Masuk kembali</strong> untuk melanjutkan aktivitas belajar Anda di LMS.</p>
                </div>
                <div class="flex items-start gap-4">
                    <i data-lucide="wifi" class="w-5 h-5 text-gray-500 mt-0.5 shrink-0"></i>
                    <p class="text-gray-400 font-light">Jika menggunakan VPN/jaringan publik, coba beralih ke <strong class="text-white font-medium">jaringan pribadi</strong>.</p>
                </div>
                <div class="flex items-start gap-4">
                    <i data-lucide="users" class="w-5 h-5 text-gray-500 mt-0.5 shrink-0"></i>
                    <p class="text-gray-400 font-light">Pastikan Anda <strong class="text-white font-medium">tidak saling berbagi akun</strong> dengan pengguna lain secara bersamaan.</p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 w-full justify-center mt-12">
                <a href="{{ route('login') }}" class="px-8 py-3.5 bg-white hover:bg-gray-100 text-black text-sm font-semibold rounded-full transition-all flex items-center justify-center gap-2">
                    Masuk LMS &rarr;
                </a>
                <a href="{{ url('/') }}" class="px-8 py-3.5 bg-transparent hover:bg-dark-800 border border-gray-700 text-white text-sm font-medium rounded-full transition-all flex items-center justify-center gap-2">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </main>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
