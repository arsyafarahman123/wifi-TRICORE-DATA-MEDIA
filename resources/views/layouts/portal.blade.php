<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>@yield('title', 'TRINET-BILL | Portal Mitra & Admin - TRICORE DATA MEDIA')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#090d16] text-slate-100 font-sans antialiased min-h-screen flex flex-col md:flex-row overflow-x-hidden selection:bg-cyan-500 selection:text-white">

    <!-- Mobile Top Header (Sticky) -->
    <header class="md:hidden flex items-center justify-between px-4 py-3.5 border-b border-white/10 bg-[#060a12]/95 backdrop-blur-md sticky top-0 z-40">
        <a href="{{ route('portal.dashboard') }}" class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-cyan-500 to-emerald-500 p-0.5 shadow-sm">
                <div class="w-full h-full bg-[#090d16] rounded-[6px] flex items-center justify-center">
                    <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>
            <div>
                <span class="text-sm font-extrabold text-white tracking-wider block leading-tight">TRINET-BILL</span>
                <span class="text-[9px] text-cyan-400 font-bold uppercase tracking-widest block">Portal Mitra & Admin</span>
            </div>
        </a>

        <div class="flex items-center gap-2">
            <!-- User pill on mobile header -->
            <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-900 border border-white/10 text-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-[11px] font-semibold text-slate-300 max-w-[100px] truncate">{{ auth()->user()->name }}</span>
            </div>

            <!-- Hamburger Button -->
            <button type="button" id="sidebar-toggle-btn" aria-label="Buka Menu" class="p-2 rounded-xl bg-slate-800/90 text-slate-200 hover:text-white border border-white/10 active:scale-95 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
                </svg>
            </button>
        </div>
    </header>

    <!-- Mobile Drawer Backdrop -->
    <div id="portal-backdrop" class="fixed inset-0 bg-black/75 backdrop-blur-sm z-40 hidden transition-opacity duration-300 md:hidden"></div>

    <!-- Sidebar Navigation (Desktop Fixed & Mobile Slide Drawer) -->
    <aside id="portal-sidebar" class="fixed inset-y-0 left-0 z-50 w-72 max-w-[85vw] -translate-x-full transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:w-64 md:flex flex-col border-r border-white/10 bg-[#060a12] shrink-0 min-h-screen md:sticky md:top-0 md:h-screen p-5 justify-between overflow-y-auto shadow-2xl md:shadow-none">
        <div class="space-y-6">
            <!-- Brand & Mobile Close Button -->
            <div class="flex items-center justify-between">
                <a href="{{ route('portal.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-500 to-emerald-500 p-0.5 shadow-md">
                        <div class="w-full h-full bg-[#090d16] rounded-[10px] flex items-center justify-center">
                            <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <span class="text-lg font-extrabold text-white block leading-tight tracking-wider">TRINET-BILL</span>
                        <span class="text-[9px] text-cyan-400 font-bold uppercase tracking-wider block">TRICORE NETWORK BILLING</span>
                    </div>
                </a>

                <!-- Mobile Close 'X' Button -->
                <button type="button" id="sidebar-close-btn" class="md:hidden p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- User Badge -->
            <div class="p-3.5 rounded-xl bg-slate-900/90 border border-white/10 flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-cyan-500/30 to-emerald-500/30 text-cyan-300 font-bold flex items-center justify-center text-xs shrink-0 border border-cyan-500/30">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="overflow-hidden min-w-0">
                    <span class="text-xs font-bold text-white block truncate">{{ auth()->user()->name }}</span>
                    <span class="text-[10px] font-semibold text-emerald-400 uppercase block truncate">
                        {{ auth()->user()->role === 'admin' ? 'Super Admin' : (auth()->user()->mitra_name ?? 'Mitra WiFi') }}
                    </span>
                </div>
            </div>

            <!-- Nav Links -->
            <nav class="space-y-1.5 text-xs font-semibold">
                <a href="{{ route('portal.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('portal.dashboard') ? 'bg-cyan-500/15 text-cyan-300 border border-cyan-500/30 shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-900/80' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Dashboard Utama</span>
                </a>

                <a href="{{ route('portal.customers.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('portal.customers.*') ? 'bg-cyan-500/15 text-cyan-300 border border-cyan-500/30 shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-900/80' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span>Kelola Pelanggan</span>
                </a>

                <a href="{{ route('portal.invoices.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('portal.invoices.*') ? 'bg-cyan-500/15 text-cyan-300 border border-cyan-500/30 shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-900/80' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                    <span>Tagihan & Transaksi</span>
                </a>

                <a href="{{ route('portal.packages.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('portal.packages.*') ? 'bg-cyan-500/15 text-cyan-300 border border-cyan-500/30 shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-900/80' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <span>Daftar Paket WiFi</span>
                </a>
            </nav>
        </div>

        <!-- Bottom Actions -->
        <div class="pt-6 border-t border-white/10 space-y-2 mt-6">
            <a href="{{ route('home') }}" target="_blank" class="w-full flex items-center justify-center gap-2 py-2.5 px-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-xs font-semibold text-slate-300 border border-white/5 transition active:scale-98">
                <svg class="w-3.5 h-3.5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span>Lihat Website Publik</span>
            </a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 px-3 rounded-xl bg-rose-950/40 hover:bg-rose-900/60 text-xs font-semibold text-rose-300 border border-rose-500/20 transition active:scale-98">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span>Keluar (Logout)</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full min-w-0">
        <!-- Toast Flash Alerts -->
        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-950/80 border border-emerald-500/40 text-emerald-200 text-xs font-medium flex items-center justify-between shadow-lg">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-white p-1">✕</button>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 rounded-xl bg-rose-950/80 border border-rose-500/40 text-rose-200 text-xs font-medium flex items-center justify-between shadow-lg">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-rose-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-400 hover:text-white p-1">✕</button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Script for mobile drawer toggle -->
    <script>
        const sidebar = document.getElementById('portal-sidebar');
        const backdrop = document.getElementById('portal-backdrop');
        const toggleBtn = document.getElementById('sidebar-toggle-btn');
        const closeBtn = document.getElementById('sidebar-close-btn');

        function openDrawer() {
            if (sidebar && backdrop) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                document.body.classList.add('overflow-hidden', 'md:overflow-auto');
            }
        }

        function closeDrawer() {
            if (sidebar && backdrop) {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
                document.body.classList.remove('overflow-hidden', 'md:overflow-auto');
            }
        }

        if (toggleBtn) toggleBtn.addEventListener('click', openDrawer);
        if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
        if (backdrop) backdrop.addEventListener('click', closeDrawer);

        // Close on Esc key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeDrawer();
        });
    </script>

    @stack('scripts')
</body>
</html>
