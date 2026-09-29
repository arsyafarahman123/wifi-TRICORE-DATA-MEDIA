<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TRICORE DATA MEDIA - Internet Fiber Optic Cepat & Stabil Purwokerto')</title>
    <meta name="description" content="TRICORE DATA MEDIA penyedia internet fiber optic (ISP) berkecepatan tinggi, unlimited tanpa FUP untuk rumah dan bisnis di Purwokerto Timur, Purwokerto Wetan, dan Sokaraja.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#090d16] text-slate-100 font-sans antialiased min-h-screen selection:bg-cyan-500 selection:text-white flex flex-col justify-between">

    <!-- Ambient background glows -->
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-cyan-500/15 rounded-full blur-3xl"></div>
        <div class="absolute top-1/3 -right-40 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 left-1/4 w-[500px] h-96 bg-blue-600/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Navigation Header -->
    <header class="sticky top-0 z-50 glass-panel border-b border-white/10 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-cyan-500 via-blue-600 to-emerald-500 p-0.5 shadow-lg shadow-cyan-500/20 group-hover:shadow-cyan-500/40 transition">
                        <div class="w-full h-full bg-[#090d16] rounded-[10px] flex items-center justify-center">
                            <svg class="w-6 h-6 text-cyan-400 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <span class="text-xl font-extrabold tracking-wider bg-gradient-to-r from-white via-slate-100 to-cyan-300 bg-clip-text text-transparent block leading-tight">
                            TRICORE
                        </span>
                        <span class="text-[10px] tracking-[0.25em] font-semibold text-cyan-400 uppercase block">
                            DATA MEDIA
                        </span>
                    </div>
                </a>

                <!-- Desktop Menu -->
                <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-300">
                    <a href="{{ route('home') }}#beranda" class="hover:text-cyan-400 transition">Home</a>
                    <a href="{{ route('home') }}#tentang" class="hover:text-cyan-400 transition">Tentang</a>
                    <a href="{{ route('home') }}#paket" class="hover:text-cyan-400 transition">Paket</a>
                    <a href="{{ route('home') }}#coverage" class="hover:text-cyan-400 transition">Coverage</a>
                    <a href="{{ route('home') }}#cek-tagihan" class="hover:text-cyan-400 transition flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Cek Tagihan
                    </a>
                    <a href="{{ route('home') }}#kontak" class="hover:text-cyan-400 transition">Kontak</a>
                </nav>

                <!-- Actions -->
                <div class="hidden md:flex items-center gap-3">
                    <a href="{{ route('home') }}#daftar" class="px-4 py-2 text-sm font-semibold rounded-lg bg-gradient-to-r from-cyan-500 to-emerald-500 text-slate-950 hover:brightness-110 shadow-md shadow-cyan-500/20 transition transform active:scale-95">
                        Daftar Online
                    </a>
                    @auth
                        <a href="{{ route('portal.dashboard') }}" class="px-3.5 py-2 text-xs font-semibold rounded-lg bg-slate-800 text-cyan-300 border border-cyan-500/30 hover:bg-slate-700 transition">
                            Portal Mitra
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-3.5 py-2 text-xs font-semibold rounded-lg bg-slate-800/80 text-slate-300 border border-white/10 hover:text-white hover:border-slate-600 transition flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            Mitra Login
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center gap-2">
                    <a href="{{ route('home') }}#daftar" class="px-3 py-1.5 text-xs font-bold rounded-lg bg-gradient-to-r from-cyan-400 to-emerald-400 text-slate-950 shadow-sm">
                        Daftar
                    </a>
                    <button type="button" id="mobile-menu-btn" aria-label="Buka Menu Navigasi" class="p-2 rounded-xl text-slate-300 hover:text-white bg-slate-800/80 border border-white/10 focus:outline-none active:scale-95 transition">
                        <svg id="hamburger-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                        <svg id="close-icon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Dropdown -->
            <div id="mobile-menu" class="hidden md:hidden pb-5 pt-3 border-t border-white/10 space-y-2 text-sm font-medium">
                <a href="{{ route('home') }}#beranda" class="mobile-nav-link block px-3.5 py-2.5 rounded-xl hover:bg-slate-800 text-slate-200 transition">Home</a>
                <a href="{{ route('home') }}#tentang" class="mobile-nav-link block px-3.5 py-2.5 rounded-xl hover:bg-slate-800 text-slate-200 transition">Tentang Kami</a>
                <a href="{{ route('home') }}#paket" class="mobile-nav-link block px-3.5 py-2.5 rounded-xl hover:bg-slate-800 text-slate-200 transition">Paket Internet</a>
                <a href="{{ route('home') }}#coverage" class="mobile-nav-link block px-3.5 py-2.5 rounded-xl hover:bg-slate-800 text-slate-200 transition">Coverage Area</a>
                <a href="{{ route('home') }}#cek-tagihan" class="mobile-nav-link block px-3.5 py-2.5 rounded-xl hover:bg-slate-800 text-emerald-400 font-semibold transition">Cek Tagihan Pelanggan</a>
                <a href="{{ route('home') }}#kontak" class="mobile-nav-link block px-3.5 py-2.5 rounded-xl hover:bg-slate-800 text-slate-200 transition">Kontak & Helpdesk</a>
                <div class="pt-3 border-t border-slate-800 flex gap-2">
                    <a href="{{ route('home') }}#daftar" class="mobile-nav-link flex-1 text-center py-2.5 text-xs font-bold rounded-xl bg-gradient-to-r from-cyan-500 to-emerald-500 text-slate-950">Daftar Online</a>
                    @auth
                        <a href="{{ route('portal.dashboard') }}" class="mobile-nav-link flex-1 text-center py-2.5 text-xs font-bold rounded-xl bg-slate-800 text-cyan-300 border border-cyan-500/30">Portal Mitra</a>
                    @else
                        <a href="{{ route('login') }}" class="mobile-nav-link flex-1 text-center py-2.5 text-xs font-bold rounded-xl bg-slate-800 text-slate-200 border border-slate-700">Login Mitra</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Global Alert Banners -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 z-40 relative">
            <div class="p-4 rounded-xl bg-emerald-950/80 border border-emerald-500/40 text-emerald-200 flex items-center justify-between shadow-lg shadow-emerald-950/50">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-white">✕</button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 z-40 relative">
            <div class="p-4 rounded-xl bg-rose-950/80 border border-rose-500/40 text-rose-200 flex items-center justify-between shadow-lg shadow-rose-950/50">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-rose-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-400 hover:text-white">✕</button>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="relative z-10 flex-grow">
        @yield('content')
    </main>

    <!-- Floating WhatsApp CTA -->
    <aside aria-label="Kontak WhatsApp" class="fixed bottom-6 right-6 z-50 flex items-center group">
        <a href="https://wa.me/6282138413292?text=Halo%20TRICORE%20DATA%20MEDIA,%20saya%20tertarik%20dengan%20layanan%20WiFi%20Fiber%20Optic%20Purwokerto.%20Mohon%20informasinya." 
           target="_blank" 
           rel="noopener noreferrer"
           class="flex items-center gap-3 px-4 py-3 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold rounded-full shadow-xl shadow-emerald-500/30 transition transform hover:scale-105 active:scale-95">
            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
            </svg>
            <span class="text-sm tracking-wide hidden sm:inline">Chat WhatsApp</span>
        </a>
    </aside>

    <!-- Footer -->
    <footer class="relative z-10 border-t border-white/10 bg-[#060a12] pt-16 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-12">
                <!-- Company Info -->
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-500 to-emerald-500 p-0.5 shadow-md">
                            <div class="w-full h-full bg-[#090d16] rounded-[10px] flex items-center justify-center">
                                <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <span class="text-xl font-extrabold tracking-wider text-white">TRICORE</span>
                            <span class="text-xs tracking-[0.2em] font-semibold text-cyan-400 uppercase block">DATA MEDIA</span>
                        </div>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed max-w-md">
                        TRICORE DATA MEDIA adalah perusahaan penyedia internet fiber optic (ISP) terpercaya di Purwokerto. Kami menghadirkan koneksi internet super cepat, stabil, tanpa batas kuota (unlimited FUP) dengan layanan pelanggan dan tim teknisi siaga 24/7.
                    </p>
                    <div class="flex items-center gap-3 pt-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                            Jaringan 100% Fiber Optic
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-cyan-500/10 text-cyan-400 border border-cyan-500/20">
                            Support 24/7
                        </span>
                    </div>
                </div>

                <!-- Navigasi Cepat -->
                <div class="space-y-3">
                    <h4 class="text-sm font-bold uppercase tracking-wider text-white">Navigasi Layanan</h4>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li><a href="{{ route('home') }}#paket" class="hover:text-cyan-400 transition">Pilihan Paket Internet</a></li>
                        <li><a href="{{ route('home') }}#coverage" class="hover:text-cyan-400 transition">Coverage Area Purwokerto</a></li>
                        <li><a href="{{ route('home') }}#cek-tagihan" class="hover:text-cyan-400 transition">Cek Tagihan Mandiri</a></li>
                        <li><a href="{{ route('home') }}#daftar" class="hover:text-cyan-400 transition">Pendaftaran Pasang Baru</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-cyan-400 transition">Portal Mitra & Admin</a></li>
                    </ul>
                </div>

                <!-- Kontak Resmi -->
                <div class="space-y-3">
                    <h4 class="text-sm font-bold uppercase tracking-wider text-white">Hubungi Kami</h4>
                    <ul class="space-y-3 text-sm text-slate-400">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <div>
                                <span class="block text-xs text-slate-500">WhatsApp / Telp:</span>
                                <a href="https://wa.me/6282138413292" target="_blank" class="text-white hover:text-cyan-400 transition font-medium">+62 821-3841-3292</a>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-cyan-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <div>
                                <span class="block text-xs text-slate-500">Email:</span>
                                <a href="mailto:support@tricoredatamedia.net" class="text-white hover:text-cyan-400 transition font-medium">support@tricoredatamedia.net</a>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-rose-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <div>
                                <span class="block text-xs text-slate-500">Alamat Kantor:</span>
                                <span class="text-slate-300">Jl. KAV. Gelora Indah II, Gg. Renang, Purwokerto Timur, Jawa Tengah</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Copyright Bar -->
            <div class="pt-8 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
                <p>© 2026 TRICORE DATA MEDIA. All rights reserved.</p>
                <div class="flex items-center gap-6">
                    <span class="text-slate-400">ISP Fiber Optic Purwokerto & Sokaraja</span>
                    <a href="{{ route('home') }}#beranda" class="text-cyan-400 hover:underline">Kembali ke Atas ↑</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle & behavior
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const hamburgerIcon = document.getElementById('hamburger-icon');
        const closeIcon = document.getElementById('close-icon');

        function toggleMobileMenu() {
            if (!mobileMenu) return;
            const isOpen = !mobileMenu.classList.contains('hidden');
            if (isOpen) {
                mobileMenu.classList.add('hidden');
                hamburgerIcon?.classList.remove('hidden');
                closeIcon?.classList.add('hidden');
            } else {
                mobileMenu.classList.remove('hidden');
                hamburgerIcon?.classList.add('hidden');
                closeIcon?.classList.remove('hidden');
            }
        }

        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', toggleMobileMenu);
        }

        // Close mobile menu when clicking any navigation link
        document.querySelectorAll('.mobile-nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
                    toggleMobileMenu();
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
