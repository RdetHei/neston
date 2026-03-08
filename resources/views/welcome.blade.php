<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NESTON - Modern Parking Ecosystem</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/neston.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/neston.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        
        :root {
            --zinc-50: #fafafa;
            --zinc-100: #f4f4f5;
            --zinc-200: #e4e4e7;
            --zinc-300: #d4d4d8;
            --zinc-400: #a1a1aa;
            --zinc-500: #71717a;
            --zinc-600: #52525b;
            --zinc-700: #3f3f46;
            --zinc-800: #27272a;
            --zinc-900: #18181b;
        }

        body {
            font-family: 'Inter', sans-serif;
            scroll-behavior: smooth;
        }

        .glass-nav {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--zinc-100);
        }

        .hero-gradient {
            background: radial-gradient(circle at top right, var(--zinc-100), transparent),
                        radial-gradient(circle at bottom left, var(--zinc-50), transparent);
        }

        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
        }
    </style>
</head>
<body class="bg-white text-zinc-900 antialiased selection:bg-zinc-900 selection:text-white">
    <!-- Navbar -->
    <nav class="fixed w-full top-0 z-50 glass-nav">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="#" class="flex items-center space-x-3 group">
                    <img src="{{ asset('images/neston.png') }}" alt="NESTON" class="w-10 h-10 rounded-xl shadow-sm grayscale group-hover:grayscale-0 transition-all duration-500">
                    <span class="text-xl font-black tracking-tighter text-zinc-900 uppercase">NESTON</span>
                </a>

                <!-- Desktop Navigation -->
                <ul class="hidden md:flex space-x-10">
                    <li><a href="#fitur" class="text-sm font-bold text-zinc-500 hover:text-zinc-900 transition-colors tracking-tight">FITUR</a></li>
                    <li><a href="#teknologi" class="text-sm font-bold text-zinc-500 hover:text-zinc-900 transition-colors tracking-tight">TEKNOLOGI</a></li>
                    <li><a href="#faq" class="text-sm font-bold text-zinc-500 hover:text-zinc-900 transition-colors tracking-tight">FAQ</a></li>
                </ul>

                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('login.create') }}" class="text-sm font-bold text-zinc-600 hover:text-zinc-900 transition-all px-4 py-2">
                        Masuk
                    </a>
                    <a href="{{ route('register.create') }}" class="bg-zinc-900 text-white px-6 py-3 rounded-xl text-sm font-bold hover:bg-zinc-800 transition-all shadow-xl shadow-zinc-200 active:scale-95">
                        Mulai Sekarang
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button class="md:hidden p-2 text-zinc-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="beranda" class="pt-44 pb-32 hero-gradient overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-20 items-center">
                <!-- Hero Content -->
                <div class="relative z-10">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-zinc-100 border border-zinc-200 mb-8">
                        <span class="flex h-2 w-2 rounded-full bg-zinc-900 animate-pulse"></span>
                        <span class="text-[10px] font-black text-zinc-600 uppercase tracking-widest">Neston v2.0 Released</span>
                    </div>
                    <h1 class="text-6xl lg:text-7xl font-black text-zinc-900 leading-[1.05] tracking-tighter mb-8">
                        Parkir Lebih <br/>
                        <span class="text-zinc-400 italic">Cerdas.</span>
                    </h1>
                    <p class="text-xl text-zinc-500 leading-relaxed mb-10 max-w-lg font-medium">
                        Platform manajemen parkir modern yang menggabungkan AI, analitik real-time, dan sistem pembayaran digital NestonPay dalam satu ekosistem yang bersih.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('register.create') }}" class="bg-zinc-900 text-white px-10 py-5 rounded-2xl font-bold text-lg hover:bg-zinc-800 transition-all shadow-2xl shadow-zinc-300 flex items-center justify-center gap-3 active:scale-95">
                            Coba Gratis
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                        <a href="#fitur" class="bg-white text-zinc-900 border border-zinc-200 px-10 py-5 rounded-2xl font-bold text-lg hover:bg-zinc-50 transition-all flex items-center justify-center">
                            Pelajari Fitur
                        </a>
                    </div>
                    
                    <div class="mt-16 flex items-center gap-8">
                        <div>
                            <p class="text-2xl font-black text-zinc-900 tracking-tighter">99.9%</p>
                            <p class="text-xs font-bold text-zinc-400 uppercase tracking-widest">Uptime AI</p>
                        </div>
                        <div class="w-px h-8 bg-zinc-200"></div>
                        <div>
                            <p class="text-2xl font-black text-zinc-900 tracking-tighter">5k+</p>
                            <p class="text-xs font-bold text-zinc-400 uppercase tracking-widest">Daily Slots</p>
                        </div>
                    </div>
                </div>

                <!-- Hero Image/Visual -->
                <div class="relative lg:h-[600px] flex items-center justify-center">
                    <div class="absolute w-[500px] h-[500px] bg-zinc-100 rounded-full blur-3xl opacity-50"></div>
                    <div class="relative bg-zinc-900 rounded-[3rem] p-4 shadow-2xl shadow-zinc-400 transform rotate-3 hover:rotate-0 transition-transform duration-700">
                        <div class="bg-white rounded-[2.5rem] overflow-hidden border border-zinc-800/10">
                            <img src="{{ asset('images/floor1.png') }}" alt="Dashboard Preview" class="w-full h-auto opacity-80 hover:opacity-100 transition-opacity">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="fitur" class="py-32 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-24">
                <h2 class="text-[10px] font-black text-zinc-400 uppercase tracking-[0.3em] mb-4">Core Ecosystem</h2>
                <h3 class="text-4xl lg:text-5xl font-black text-zinc-900 tracking-tighter leading-tight">
                    Segala yang Anda butuhkan untuk <span class="text-zinc-300 italic">skala bisnis.</span>
                </h3>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="p-10 rounded-[2.5rem] bg-zinc-50 border border-zinc-100 card-hover">
                    <div class="w-14 h-14 bg-zinc-900 rounded-2xl flex items-center justify-center text-white mb-8 shadow-lg shadow-zinc-200">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h4 class="text-xl font-black text-zinc-900 mb-4 tracking-tight">Advanced Analytics</h4>
                    <p class="text-zinc-500 font-medium leading-relaxed">
                        Heatmap pendapatan per jam dan analisis okupansi real-time untuk pengambilan keputusan yang lebih akurat.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="p-10 rounded-[2.5rem] bg-zinc-50 border border-zinc-100 card-hover">
                    <div class="w-14 h-14 bg-zinc-900 rounded-2xl flex items-center justify-center text-white mb-8 shadow-lg shadow-zinc-200">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                    <h4 class="text-xl font-black text-zinc-900 mb-4 tracking-tight">NestonPay Wallet</h4>
                    <p class="text-zinc-500 font-medium leading-relaxed">
                        Sistem pembayaran digital tanpa gesek. Top up mudah dan pembayaran instan untuk pengalaman parkir yang mulus.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="p-10 rounded-[2.5rem] bg-zinc-50 border border-zinc-100 card-hover">
                    <div class="w-14 h-14 bg-zinc-900 rounded-2xl flex items-center justify-center text-white mb-8 shadow-lg shadow-zinc-200">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <h4 class="text-xl font-black text-zinc-900 mb-4 tracking-tight">Find My Car</h4>
                    <p class="text-zinc-500 font-medium leading-relaxed">
                        Peta interaktif cerdas yang secara otomatis menandai lokasi kendaraan Anda dengan presisi tinggi.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-zinc-900 py-20 text-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center">
            <div class="flex items-center justify-center space-x-3 mb-12">
                <img src="{{ asset('images/neston.png') }}" alt="NESTON" class="w-8 h-8 rounded-lg grayscale invert">
                <span class="text-lg font-black tracking-tighter uppercase">NESTON</span>
            </div>
            <p class="text-zinc-500 text-sm font-bold tracking-widest uppercase mb-4">Modern Parking Ecosystem</p>
            <p class="text-zinc-600 text-xs font-medium">© 2026 Neston. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
