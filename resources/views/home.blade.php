@extends('layouts.app')

@section('title', 'TRICORE DATA MEDIA - Internet Fiber Optic Cepat & Stabil Purwokerto')

@section('content')

    <!-- ========================================== -->
    <!-- 1. HERO SECTION                            -->
    <!-- ========================================== -->
    <section id="beranda" class="relative pt-12 pb-20 lg:pt-24 lg:pb-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <!-- Left Content -->
                <div class="lg:col-span-7 space-y-8 text-center lg:text-left">
                    <!-- Live Status Badge -->
                    <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full glass-card border-cyan-500/30 text-xs font-semibold text-cyan-300">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-cyan-500"></span>
                        </span>
                        <span>INTERNET SERVICE PROVIDER</span>
                        <span class="text-slate-500">|</span>
                        <span class="text-emerald-400 flex items-center gap-1 font-mono">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            Latency ~5ms
                        </span>
                    </div>

                    <!-- Main Headline -->
                    <div class="space-y-4">
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white leading-tight">
                            Internet <span class="bg-gradient-to-r from-cyan-400 via-teal-300 to-emerald-400 bg-clip-text text-transparent">Cepat & Stabil</span> untuk Rumah dan Bisnis Anda
                        </h1>
                        <p class="text-base sm:text-lg text-slate-300 max-w-2xl mx-auto lg:mx-0 leading-relaxed font-normal">
                            Nikmati pengalaman berselancar tanpa batas dengan jaringan 100% murni fiber optic dari <strong class="text-white font-semibold">TRICORE DATA MEDIA</strong>. Tanpa FUP, latency super rendah untuk gaming, streaming 4K tanpa buffering, dan dukungan teknisi siaga 24/7 di Purwokerto.
                        </p>
                    </div>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-2">
                        <a href="#paket" class="w-full sm:w-auto px-7 py-3.5 rounded-xl font-bold text-sm bg-gradient-to-r from-cyan-500 via-teal-400 to-emerald-500 text-slate-950 hover:brightness-110 shadow-lg shadow-cyan-500/25 transition transform hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-2 group">
                            <span>Lihat Paket Internet</span>
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                        <a href="https://wa.me/6282138413292?text=Halo%20TRICORE%20DATA%20MEDIA,%20saya%20ingin%20konsultasi%20pemasangan%20WiFi%20di%20Purwokerto." 
                           target="_blank" 
                           class="w-full sm:w-auto px-7 py-3.5 rounded-xl font-bold text-sm bg-slate-800/90 text-white border border-white/10 hover:border-emerald-500/50 hover:bg-slate-800 hover:text-emerald-300 transition flex items-center justify-center gap-2 shadow-sm">
                            <svg class="w-4 h-4 text-emerald-400 fill-current" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                            </svg>
                            <span>Hubungi Kami</span>
                        </a>
                    </div>

                    <!-- Quick Highlights -->
                    <div class="grid grid-cols-3 gap-2 sm:gap-4 pt-4 border-t border-white/5 max-w-lg mx-auto lg:mx-0">
                        <div class="text-left">
                            <span class="block text-[10px] sm:text-xs text-slate-400">Teknologi</span>
                            <span class="text-xs sm:text-sm font-bold text-white flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 shrink-0"></span> 100% Fiber
                            </span>
                        </div>
                        <div class="text-left">
                            <span class="block text-[10px] sm:text-xs text-slate-400">Kecepatan</span>
                            <span class="text-xs sm:text-sm font-bold text-white flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shrink-0"></span> Up to 50 Mbps
                            </span>
                        </div>
                        <div class="text-left">
                            <span class="block text-[10px] sm:text-xs text-slate-400">Biaya Pasang</span>
                            <span class="text-xs sm:text-sm font-bold text-emerald-400">GRATIS Sewa ONT</span>
                        </div>
                    </div>
                </div>

                <!-- Right Tech Card / Speed Visualizer -->
                <div class="lg:col-span-5 relative">
                    <div class="relative mx-auto max-w-md">
                        <!-- Neon Glow Ring -->
                        <div class="absolute -inset-1 rounded-3xl bg-gradient-to-r from-cyan-500 via-blue-600 to-emerald-500 opacity-30 blur-xl"></div>

                        <!-- Card Content -->
                        <div class="relative rounded-2xl glass-panel p-6 border border-white/10 shadow-2xl space-y-6">
                            <!-- Header Card -->
                            <div class="flex items-center justify-between border-b border-white/10 pb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-cyan-500/20 text-cyan-400 flex items-center justify-center font-bold">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-bold text-white">TRICORE LIVE NETWORK</h3>
                                        <span class="text-xs text-slate-400">Purwokerto Backbone Node</span>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">ONLINE</span>
                            </div>

                            <!-- Speed Meter Graphic -->
                            <div class="py-4 text-center space-y-2">
                                <span class="text-xs font-semibold uppercase tracking-wider text-cyan-400">Paket Terfavorit Pelanggan</span>
                                <div class="text-6xl font-black tracking-tight text-white font-mono flex items-baseline justify-center gap-2">
                                    <span>25</span>
                                    <span class="text-2xl text-cyan-400 font-sans font-bold">Mbps</span>
                                </div>
                                <p class="text-xs text-slate-400">Streaming 4K Ultra HD • Gaming Low Ping • Full Unlimited</p>
                                <div class="text-emerald-400 font-bold text-lg pt-1">
                                    Rp150.000 <span class="text-xs text-slate-400 font-normal">/ bulan</span>
                                </div>
                            </div>

                            <!-- Quick Form / Action inside Card -->
                            <div class="space-y-3 pt-2 border-t border-white/10">
                                <div class="grid grid-cols-2 gap-3 text-xs">
                                    <div class="p-2.5 rounded-xl bg-slate-900/80 border border-white/5">
                                        <span class="text-slate-400 block text-[10px]">Ping ke Server Lokal</span>
                                        <span class="font-bold text-emerald-400 text-sm font-mono">4 - 8 ms</span>
                                    </div>
                                    <div class="p-2.5 rounded-xl bg-slate-900/80 border border-white/5">
                                        <span class="text-slate-400 block text-[10px]">Jaminan Uptime</span>
                                        <span class="font-bold text-cyan-400 text-sm font-mono">99.8%</span>
                                    </div>
                                </div>

                                <a href="#daftar" class="w-full py-3 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-sm text-center block transition shadow-md shadow-cyan-500/20">
                                    Daftar Pasang Baru Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- 2. STATISTIK COUNTER                       -->
    <!-- ========================================== -->
    <section class="relative z-10 py-12 border-y border-white/10 bg-[#060a12]/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-y md:divide-y-0 md:divide-x divide-white/10">
                <!-- Stat 1 -->
                <div class="pt-4 md:pt-0 px-4 space-y-1">
                    <div class="text-4xl sm:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-teal-300 font-mono">
                        {{ $stats['active_customers'] }}+
                    </div>
                    <p class="text-sm font-medium text-slate-300">Pelanggan Aktif</p>
                    <span class="text-[11px] text-slate-400 block">Rumah tinggal, bisnis & UMKM</span>
                </div>

                <!-- Stat 2 -->
                <div class="pt-4 md:pt-0 px-4 space-y-1">
                    <div class="text-4xl sm:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-teal-300 to-emerald-400 font-mono">
                        {{ $stats['uptime'] }}
                    </div>
                    <p class="text-sm font-medium text-slate-300">Network Uptime</p>
                    <span class="text-[11px] text-slate-400 block">Koneksi stabil anti putus</span>
                </div>

                <!-- Stat 3 -->
                <div class="pt-4 md:pt-0 px-4 space-y-1">
                    <div class="text-4xl sm:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-cyan-300 font-mono">
                        {{ $stats['support'] }}
                    </div>
                    <p class="text-sm font-medium text-slate-300">Support Siaga</p>
                    <span class="text-[11px] text-slate-400 block">Customer service & dispatch teknisi</span>
                </div>

                <!-- Stat 4 -->
                <div class="pt-4 md:pt-0 px-4 space-y-1">
                    <div class="text-4xl sm:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-cyan-300 to-blue-400 font-mono">
                        {{ $stats['areas_count'] }}+
                    </div>
                    <p class="text-sm font-medium text-slate-300">Area Coverage Utama</p>
                    <span class="text-[11px] text-slate-400 block">Purwokerto Timur, Wetan & Sokaraja</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- 3. TENTANG KAMI                            -->
    <!-- ========================================== -->
    <section id="tentang" class="py-24 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto space-y-4 mb-16">
                <span class="text-xs font-bold tracking-widest text-cyan-400 uppercase">Tentang TRICORE DATA MEDIA</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white">
                    Penyedia Internet Fiber Optic Terpercaya di Purwokerto
                </h2>
                <p class="text-slate-300 text-base leading-relaxed">
                    TRICORE DATA MEDIA adalah perusahaan Internet Service Provider (ISP) yang berkomitmen menghadirkan konektivitas digital berkecepatan tinggi dengan infrastruktur fiber optik modern di wilayah Purwokerto dan sekitarnya. Kami bekerja sama dengan mitra terpercaya untuk memastikan akses internet yang andal, transparan, dan terjangkau untuk seluruh lapisan masyarakat.
                </p>
            </div>

            <!-- 4 Poin Unggulan Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Poin 1: Internet Cepat -->
                <div class="glass-card p-6 rounded-2xl space-y-4 border border-white/5 hover:border-cyan-500/40">
                    <div class="w-12 h-12 rounded-xl bg-cyan-500/10 text-cyan-400 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-white">Internet Super Cepat</h3>
                    <p class="text-sm text-slate-400 leading-relaxed">
                        Kecepatan simetris dengan download dan upload seimbang. Bebas lag untuk streaming kualitas 4K, video conference, dan turnamen gaming online.
                    </p>
                </div>

                <!-- Poin 2: Fiber Optic -->
                <div class="glass-card p-6 rounded-2xl space-y-4 border border-white/5 hover:border-emerald-500/40">
                    <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-white">100% Fiber Optic</h3>
                    <p class="text-sm text-slate-400 leading-relaxed">
                        Infrastruktur kabel serat optik generasi terkini dari ODP hingga ke dalam rumah. Tahan cuaca hujan petir dan minim gangguan elektromagnetik.
                    </p>
                </div>

                <!-- Poin 3: Support 24/7 -->
                <div class="glass-card p-6 rounded-2xl space-y-4 border border-white/5 hover:border-blue-500/40">
                    <div class="w-12 h-12 rounded-xl bg-blue-500/10 text-blue-400 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-white">Support 24/7 Siaga</h3>
                    <p class="text-sm text-slate-400 leading-relaxed">
                        Layanan helpdesk WhatsApp dan tim teknisi lapangan di Purwokerto selalu bersiap sedia menangani kendala teknis dengan respon cepat.
                    </p>
                </div>

                <!-- Poin 4: Unlimited -->
                <div class="glass-card p-6 rounded-2xl space-y-4 border border-white/5 hover:border-purple-500/40">
                    <div class="w-12 h-12 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636a9 9 0 010 12.728m0 0l-2.829-2.829m2.829 2.829L21 21M15.536 8.464a5 5 0 010 7.072m0 0l-2.829-2.829m-4.243 2.829a4.978 4.978 0 01-1.414-2.83m-1.414 5.657a9 9 0 01-2.121-7.071m0 0l2.828 2.828" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-white">Unlimited Tanpa FUP</h3>
                    <p class="text-sm text-slate-400 leading-relaxed">
                        Bebas download, streaming, dan berselancar sepuasnya tanpa batas kuota bulanan. Kecepatan tetap konstan stabil dari awal hingga akhir bulan.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- 4. PAKET INTERNET (PRICING)                -->
    <!-- ========================================== -->
    <section id="paket" class="py-24 relative bg-[#060a12]/60 border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto space-y-4 mb-16">
                <span class="text-xs font-bold tracking-widest text-emerald-400 uppercase">PILIHAN PAKET TERBAIK</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white">
                    Paket Internet Fiber Optic Purwokerto
                </h2>
                <p class="text-slate-300 text-base">
                    Semua paket sudah termasuk <strong class="text-white">Unlimited Kuota</strong>, <strong class="text-white">100% Fiber Optic</strong>, <strong class="text-white">Gratis Modem Wi-Fi</strong>, dan <strong class="text-white">Customer Support 24/7</strong>.
                </p>
            </div>

            <!-- Packages Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6 items-stretch">
                @foreach($packages as $pkg)
                    @php
                        $isPopular = $pkg->is_popular || $pkg->speed_mbps == 25;
                        $waText = urlencode("Halo TRICORE DATA MEDIA, saya ingin berlangganan Paket {$pkg->name} ({$pkg->speed_mbps} Mbps - {$pkg->formatted_price}/bulan). Mohon informasi pemasangan untuk area Purwokerto.");
                        $waPackageUrl = "https://wa.me/6282138413292?text={$waText}";
                    @endphp

                    <div class="relative rounded-2xl transition-all duration-300 flex flex-col justify-between {{ $isPopular ? 'glass-panel border-cyan-400 shadow-xl shadow-cyan-500/20 md:scale-105 z-10' : 'glass-card border-white/10 hover:border-slate-500' }} p-6">
                        
                        <!-- Popular Ribbon -->
                        @if($isPopular)
                            <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full bg-gradient-to-r from-cyan-500 to-emerald-500 text-slate-950 font-extrabold text-[11px] uppercase tracking-wider shadow-md">
                                {{ $pkg->badge ?? 'Paling Diminati' }}
                            </div>
                        @elseif($pkg->badge)
                            <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-3 py-0.5 rounded-full bg-slate-800 text-cyan-300 border border-cyan-500/30 font-semibold text-[10px] uppercase">
                                {{ $pkg->badge }}
                            </div>
                        @endif

                        <div class="space-y-4">
                            <!-- Package Title & Speed -->
                            <div class="text-center pt-2 border-b border-white/10 pb-4">
                                <h3 class="text-lg font-bold text-white">{{ $pkg->name }}</h3>
                                <div class="mt-2 text-4xl font-black text-transparent bg-clip-text {{ $isPopular ? 'bg-gradient-to-r from-cyan-300 to-emerald-300' : 'bg-gradient-to-r from-white to-slate-300' }} font-mono">
                                    {{ $pkg->speed_mbps }} <span class="text-base text-cyan-400 font-sans font-bold">Mbps</span>
                                </div>
                                <span class="text-[11px] text-slate-400 block mt-1">Kecepatan Simetris Upload & Download</span>
                            </div>

                            <!-- Price -->
                            <div class="text-center py-2">
                                <div class="text-2xl font-extrabold text-white">
                                    {{ $pkg->formatted_price }}
                                </div>
                                <span class="text-xs text-slate-400">/ bulan (Flat)</span>
                            </div>

                            <!-- Device recommendation -->
                            @if($pkg->device_recommendation)
                                <div class="px-3 py-1.5 rounded-lg bg-slate-900/60 border border-white/5 text-center text-xs text-slate-300 font-medium">
                                    ⚡ Rekomendasi: <span class="text-cyan-300 font-semibold">{{ $pkg->device_recommendation }}</span>
                                </div>
                            @endif

                            <!-- Features List -->
                            <ul class="space-y-2.5 text-xs text-slate-300 pt-2">
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Unlimited Kuota (Tanpa FUP)</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>100% Full Fiber Optic</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Gratis Sewa Modem Wi-Fi</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Support & Teknisi 24/7</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Action Buttons -->
                        <div class="pt-6 space-y-2 mt-4 border-t border-white/10">
                            <!-- Tombol Berlangganan WhatsApp -->
                            <a href="{{ $waPackageUrl }}" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="w-full py-2.5 px-3 rounded-xl font-bold text-xs flex items-center justify-center gap-2 transition {{ $isPopular ? 'bg-emerald-500 hover:bg-emerald-400 text-slate-950 shadow-md shadow-emerald-500/30' : 'bg-slate-800 hover:bg-emerald-600 hover:text-white text-emerald-400 border border-emerald-500/30' }}">
                                <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24">
                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                </svg>
                                <span>Berlangganan (WA)</span>
                            </a>

                            <!-- Tombol Daftar Online Langsung -->
                            <a href="#daftar" 
                               onclick="selectPackage({{ $pkg->id }})"
                               class="w-full py-2 px-3 rounded-xl font-medium text-xs text-center block text-slate-300 hover:text-white bg-slate-900/80 hover:bg-slate-800 border border-white/5 transition">
                                Daftar di Web &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- 5. COVERAGE AREA & GOOGLE MAPS PURWOKERTO   -->
    <!-- ========================================== -->
    <section id="coverage" class="py-24 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto space-y-4 mb-16">
                <span class="text-xs font-bold tracking-widest text-cyan-400 uppercase">WILAYAH JANGKAUAN</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white">
                    Coverage Area TRICORE DATA MEDIA
                </h2>
                <p class="text-slate-300 text-base">
                    Jaringan fiber optic kami telah aktif dan melayani ribuan titik di wilayah <strong class="text-white">Purwokerto Timur</strong>, <strong class="text-white">Purwokerto Wetan</strong>, dan <strong class="text-white">Sokaraja</strong>.
                </p>
            </div>

            <!-- Coverage Areas List Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                @foreach($coverageAreas as $area)
                    <div class="glass-card p-6 rounded-2xl border border-white/10 space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-white">{{ $area->name }}</h3>
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                                {{ $area->status }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-400">{{ $area->description }}</p>
                        
                        <div class="pt-2 border-t border-white/5">
                            <span class="text-xs font-semibold text-slate-300 block mb-2">Kelurahan / Desa Tercover:</span>
                            <div class="flex flex-wrap gap-1.5">
                                @if(is_array($area->subdistricts))
                                    @foreach($area->subdistricts as $sub)
                                        <span class="px-2.5 py-1 rounded-lg bg-slate-900/80 border border-white/5 text-[11px] text-cyan-300 font-medium">
                                            ✓ {{ $sub }}
                                        </span>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Coverage Checker Box + Google Maps Embed -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
                <!-- Coverage Checker Form -->
                <div class="lg:col-span-5 glass-panel p-8 rounded-2xl border border-white/10 flex flex-col justify-between space-y-6">
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">Cek Ketersediaan Alamat</h3>
                                <p class="text-xs text-slate-400">Ketahui apakah lokasi Anda siap dipasang hari ini</p>
                            </div>
                        </div>

                        <!-- Check Result Alert if session present -->
                        @if(session('coverage_checked'))
                            @if(session('is_covered'))
                                <div class="p-4 rounded-xl bg-emerald-950/80 border border-emerald-500/40 text-emerald-200 text-xs space-y-1">
                                    <div class="font-bold text-sm text-emerald-300 flex items-center gap-1.5">
                                        ✓ Wilayah Terjangkau!
                                    </div>
                                    <p>Lokasi "{{ session('searched_location') }}" berada di area coverage <strong>{{ session('matched_area') }}</strong>. Siap pasang fiber optik 24 jam!</p>
                                    <a href="#daftar" class="inline-block mt-2 font-bold underline text-white">Lanjut Daftar Pasang &rarr;</a>
                                </div>
                            @else
                                <div class="p-4 rounded-xl bg-amber-950/80 border border-amber-500/40 text-amber-200 text-xs space-y-1">
                                    <div class="font-bold text-sm text-amber-300">
                                        ℹ Dalam Tahap Perluasan Jaringan
                                    </div>
                                    <p>Lokasi "{{ session('searched_location') }}" sedang kami prioritaskan untuk perluasan tiang ODP. Hubungi CS WhatsApp kami untuk pre-order pemasangan.</p>
                                    <a href="https://wa.me/6282138413292?text=Halo%20TRICORE,%20saya%20ingin%20cek%20tiang%20ODP%20di%20{{ urlencode(session('searched_location')) }}" target="_blank" class="inline-block mt-2 font-bold underline text-white">Hubungi Kami &rarr;</a>
                                </div>
                            @endif
                        @endif

                        <form action="{{ route('coverage.check') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="coverage-district" class="block text-xs font-semibold text-slate-300 mb-1">Kecamatan di Purwokerto / Sekitar</label>
                                <select id="coverage-district" name="district" required class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-sm focus:border-cyan-500 focus:outline-none">
                                    <option value="Purwokerto Timur">Purwokerto Timur</option>
                                    <option value="Purwokerto Wetan">Purwokerto Wetan</option>
                                    <option value="Sokaraja">Sokaraja</option>
                                    <option value="Purwokerto Barat">Purwokerto Barat (Perluasan)</option>
                                    <option value="Purwokerto Utara">Purwokerto Utara (Perluasan)</option>
                                    <option value="Purwokerto Selatan">Purwokerto Selatan (Perluasan)</option>
                                </select>
                            </div>

                            <div>
                                <label for="coverage-subdistrict" class="block text-xs font-semibold text-slate-300 mb-1">Kelurahan / Desa / Jalan</label>
                                <input type="text" id="coverage-subdistrict" name="subdistrict" placeholder="Contoh: Arcawinangun / Jl. KAV. Gelora Indah" required class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-sm focus:border-cyan-500 focus:outline-none">
                            </div>

                            <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-emerald-500 text-slate-950 font-bold text-sm hover:brightness-110 transition shadow-md shadow-cyan-500/20">
                                Cek Jangkauan Sekarang
                            </button>
                        </form>
                    </div>

                    <div class="text-[11px] text-slate-400 border-t border-white/5 pt-3">
                        Butuh survei lokasi oleh teknisi langsung? Hubungi kami di <a href="https://wa.me/6282138413292" target="_blank" class="text-cyan-400 font-bold hover:underline">+62 821-3841-3292</a>.
                    </div>
                </div>

                <!-- Google Maps Purwokerto Embed -->
                <div class="lg:col-span-7 rounded-2xl overflow-hidden glass-panel border border-white/10 shadow-xl min-h-[350px] relative">
                    <iframe 
                        title="Peta Lokasi TRICORE DATA MEDIA Purwokerto"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15825.96213602111!2d109.24522944358897!3d-7.423984922129532!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e655c3c0e3532f1%3A0x6a0c5c306d866415!2sPurwokerto%20Timur%2C%20Banyumas%20Regency%2C%20Central%20Java!5e0!3m2!1sen!2sid!4v1710000000000!5m2!1sen!2sid" 
                        width="100%" 
                        height="100%" 
                        style="border:0; min-height: 400px;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade"
                        class="w-full h-full filter invert-[0.88] hue-rotate-180 contrast-125">
                    </iframe>
                    <div class="absolute bottom-4 left-4 right-4 bg-slate-950/90 backdrop-blur-md p-3 rounded-xl border border-white/10 text-xs flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                            <span class="text-slate-200">Titik Pusat ODP: <strong>Purwokerto Timur & Sokaraja</strong></span>
                        </div>
                        <a href="https://maps.google.com/?q=Purwokerto+Timur" target="_blank" class="text-cyan-400 font-bold hover:underline">Buka Maps &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- 6. CEK TAGIHAN MANDIRI (CUSTOMER BILLING)  -->
    <!-- ========================================== -->
    <section id="cek-tagihan" class="py-20 relative bg-[#060a12]/80 border-t border-white/10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-semibold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                Portal Mandiri Pelanggan
            </div>
            
            <h2 class="text-3xl sm:text-4xl font-extrabold text-white">
                Cek Tagihan & Status Layanan Internet
            </h2>
            <p class="text-slate-300 text-sm max-w-2xl mx-auto">
                Pelanggan aktif TRICORE DATA MEDIA dapat memeriksa status pembayaran tagihan bulanan (Lunas / Belum Bayar), rincian paket, jatuh tempo, serta konfirmasi pembayaran secara mandiri.
            </p>

            <!-- Search Form Card -->
            <div class="glass-panel p-6 sm:p-8 rounded-2xl border border-white/10 shadow-2xl max-w-2xl mx-auto">
                <form action="{{ route('bill.check') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <input type="text" 
                                   name="search_query" 
                                   value="{{ old('search_query') }}"
                                   placeholder="Masukkan No Pelanggan (misal: TDM-2601) atau No WA" 
                                   required 
                                   class="w-full pl-11 pr-4 py-3 rounded-xl bg-slate-900 border border-white/15 text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400 text-sm">
                        </div>
                        <button type="submit" class="px-7 py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-cyan-500 text-slate-950 font-bold text-sm hover:brightness-110 shadow-lg shadow-emerald-500/20 transition whitespace-nowrap">
                            Cek Tagihan
                        </button>
                    </div>
                </form>

                <!-- Quick Help / Demo hint -->
                <div class="mt-4 pt-4 border-t border-white/5 text-xs text-slate-400 flex flex-wrap items-center justify-center gap-2">
                    <span>Coba nomor demo:</span>
                    <button type="button" onclick="document.querySelector('input[name=search_query]').value='TDM-2601'; document.querySelector('input[name=search_query]').form.submit();" class="px-2 py-1 rounded bg-slate-800 text-cyan-300 border border-cyan-500/20 hover:bg-slate-700">TDM-2601 (Lunas)</button>
                    <button type="button" onclick="document.querySelector('input[name=search_query]').value='TDM-2604'; document.querySelector('input[name=search_query]').form.submit();" class="px-2 py-1 rounded bg-slate-800 text-rose-300 border border-rose-500/20 hover:bg-slate-700">TDM-2604 (Belum Bayar)</button>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- 7. FORM PENDAFTARAN PELANGGAN BARU         -->
    <!-- ========================================== -->
    <section id="daftar" class="py-24 relative">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center space-y-4 mb-12">
                <span class="text-xs font-bold tracking-widest text-cyan-400 uppercase">FORMULIR PENDAFTARAN ONLINE</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white">
                    Daftar Pasang Baru WiFi TRICORE
                </h2>
                <p class="text-slate-300 text-sm max-w-xl mx-auto">
                    Isi data diri Anda di bawah ini. Tim kami akan segera memverifikasi dan menjadwalkan instalasi fiber optik ke rumah atau tempat usaha Anda.
                </p>
            </div>

            <!-- Registration Form Card -->
            <div class="glass-panel p-8 sm:p-10 rounded-2xl border border-white/10 shadow-2xl">
                <form action="{{ route('register.submit') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Lengkap -->
                        <div>
                            <label for="reg-name" class="block text-xs font-semibold text-slate-300 mb-1.5">Nama Lengkap Sesuai KTP <span class="text-rose-400">*</span></label>
                            <input type="text" id="reg-name" name="name" required value="{{ old('name') }}" placeholder="Contoh: Budi Cahyono" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-sm focus:border-cyan-400 focus:outline-none">
                            @error('name')<span class="text-rose-400 text-xs">{{ $message }}</span>@enderror
                        </div>

                        <!-- No WhatsApp -->
                        <div>
                            <label for="reg-phone" class="block text-xs font-semibold text-slate-300 mb-1.5">Nomor WhatsApp Aktif <span class="text-rose-400">*</span></label>
                            <input type="tel" id="reg-phone" name="phone" required value="{{ old('phone') }}" placeholder="Contoh: 082138413292" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-sm focus:border-cyan-400 focus:outline-none">
                            @error('phone')<span class="text-rose-400 text-xs">{{ $message }}</span>@enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="reg-email" class="block text-xs font-semibold text-slate-300 mb-1.5">Alamat Email (Opsional)</label>
                            <input type="email" id="reg-email" name="email" value="{{ old('email') }}" placeholder="Contoh: budi@gmail.com" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-sm focus:border-cyan-400 focus:outline-none">
                        </div>

                        <!-- NIK / KTP -->
                        <div>
                            <label for="reg-identity" class="block text-xs font-semibold text-slate-300 mb-1.5">Nomor NIK / KTP (Opsional)</label>
                            <input type="text" id="reg-identity" name="identity_number" value="{{ old('identity_number') }}" placeholder="16 digit nomor KTP" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-sm focus:border-cyan-400 focus:outline-none">
                        </div>

                        <!-- Pilihan Paket WiFi -->
                        <div class="md:col-span-2">
                            <label for="reg-package" class="block text-xs font-semibold text-slate-300 mb-1.5">Pilih Paket Internet WiFi <span class="text-rose-400">*</span></label>
                            <select id="reg-package" name="package_id" required class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-white/10 text-white text-sm focus:border-cyan-400 focus:outline-none font-medium">
                                @foreach($packages as $pkg)
                                    <option value="{{ $pkg->id }}" {{ ($pkg->is_popular || $pkg->speed_mbps == 25) ? 'selected' : '' }}>
                                        {{ $pkg->name }} — {{ $pkg->speed_mbps }} Mbps ({{ $pkg->formatted_price }}/bulan) {{ $pkg->badge ? '['.$pkg->badge.']' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Wilayah Kecamatan -->
                        <div>
                            <label for="reg-district" class="block text-xs font-semibold text-slate-300 mb-1.5">Kecamatan <span class="text-rose-400">*</span></label>
                            <select id="reg-district" name="district" required class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-sm focus:border-cyan-400 focus:outline-none">
                                <option value="Purwokerto Timur">Purwokerto Timur</option>
                                <option value="Purwokerto Wetan">Purwokerto Wetan</option>
                                <option value="Sokaraja">Sokaraja</option>
                                <option value="Purwokerto Barat">Purwokerto Barat</option>
                                <option value="Purwokerto Utara">Purwokerto Utara</option>
                                <option value="Purwokerto Selatan">Purwokerto Selatan</option>
                            </select>
                        </div>

                        <!-- Kelurahan / Desa -->
                        <div>
                            <label for="reg-subdistrict" class="block text-xs font-semibold text-slate-300 mb-1.5">Kelurahan / Desa</label>
                            <input type="text" id="reg-subdistrict" name="subdistrict" value="{{ old('subdistrict') }}" placeholder="Contoh: Arcawinangun / Mersi / Sokaraja Kulon" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-sm focus:border-cyan-400 focus:outline-none">
                        </div>

                        <!-- Alamat Lengkap Pemasangan -->
                        <div class="md:col-span-2">
                            <label for="reg-address" class="block text-xs font-semibold text-slate-300 mb-1.5">Alamat Lengkap Pemasangan & Patokan Rumah <span class="text-rose-400">*</span></label>
                            <textarea id="reg-address" name="address" rows="3" required placeholder="Jl. KAV. Gelora Indah II, RT 02 / RW 03, rumah pagar hitam dekat musholla" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-sm focus:border-cyan-400 focus:outline-none">{{ old('address') }}</textarea>
                            @error('address')<span class="text-rose-400 text-xs">{{ $message }}</span>@enderror
                        </div>

                        <!-- Catatan Khusus -->
                        <div class="md:col-span-2">
                            <label for="reg-notes" class="block text-xs font-semibold text-slate-300 mb-1.5">Catatan Tambahan untuk Teknisi (Opsional)</label>
                            <input type="text" id="reg-notes" name="notes" value="{{ old('notes') }}" placeholder="Contoh: Pemasangan diharapkan hari Sabtu pagi, mohon hubungi dulu sebelum datang." class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-sm focus:border-cyan-400 focus:outline-none">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4 border-t border-white/10">
                        <button type="submit" class="w-full py-4 rounded-xl bg-gradient-to-r from-cyan-500 via-teal-400 to-emerald-500 text-slate-950 font-extrabold text-base hover:brightness-110 shadow-xl shadow-cyan-500/20 transition transform active:scale-[0.99] flex items-center justify-center gap-2">
                            <span>Kirim Pendaftaran Berlangganan</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                        <p class="text-center text-[11px] text-slate-400 mt-2">
                            Setelah pendaftaran dikirim, Anda akan mendapatkan Nomor Registrasi dan dapat langsung menghubungkan ke WhatsApp Admin TRICORE.
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- 8. KONTAK & HUBUNGI KAMI                   -->
    <!-- ========================================== -->
    <section id="kontak" class="py-24 relative bg-[#060a12]/80 border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <!-- Kontak Information -->
                <div class="lg:col-span-6 space-y-6">
                    <span class="text-xs font-bold tracking-widest text-emerald-400 uppercase">HUBUNGI KAMI</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white">
                        Layanan Pelanggan & Helpdesk 24/7
                    </h2>
                    <p class="text-slate-300 text-sm leading-relaxed">
                        Punya pertanyaan seputar tarif paket WiFi, ingin mengecek ketersediaan tiang ODP di perumahan Anda, atau membutuhkan bantuan teknis? Tim TRICORE DATA MEDIA siap melayani Anda setiap saat.
                    </p>

                    <!-- Contact Details List -->
                    <div class="space-y-4 pt-2">
                        <!-- WA Item -->
                        <div class="glass-card p-4 rounded-xl border border-white/10 flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                </svg>
                            </div>
                            <div>
                                <span class="text-xs text-slate-400 block font-medium">WhatsApp Customer Care:</span>
                                <a href="https://wa.me/6282138413292" target="_blank" class="text-base font-bold text-white hover:text-emerald-400 transition">+62 821-3841-3292</a>
                            </div>
                        </div>

                        <!-- Email Item -->
                        <div class="glass-card p-4 rounded-xl border border-white/10 flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-cyan-500/10 text-cyan-400 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <span class="text-xs text-slate-400 block font-medium">Email Dukungan & Kemitraan:</span>
                                <a href="mailto:support@tricoredatamedia.net" class="text-base font-bold text-white hover:text-cyan-400 transition">support@tricoredatamedia.net</a>
                            </div>
                        </div>

                        <!-- Alamat Item -->
                        <div class="glass-card p-4 rounded-xl border border-white/10 flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-rose-500/10 text-rose-400 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            </div>
                            <div>
                                <span class="text-xs text-slate-400 block font-medium">Kantor Operasional:</span>
                                <p class="text-sm font-semibold text-white">Jl. KAV. Gelora Indah II, Gg. Renang, Purwokerto Timur, Kab. Banyumas, Jawa Tengah</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Quick WhatsApp Message Box -->
                <div class="lg:col-span-6 glass-panel p-8 rounded-2xl border border-white/10 shadow-2xl space-y-6">
                    <h3 class="text-xl font-bold text-white">Kirim Pesan WhatsApp Langsung</h3>
                    <p class="text-xs text-slate-400">
                        Pesan Anda akan otomatis diarahkan ke aplikasi WhatsApp dengan format rapi ke nomor resmi TRICORE DATA MEDIA.
                    </p>

                    <div class="space-y-4">
                        <div>
                            <label for="wa-custom-name" class="block text-xs font-semibold text-slate-300 mb-1">Nama Anda</label>
                            <input type="text" id="wa-custom-name" placeholder="Nama Anda" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-sm focus:border-cyan-400 focus:outline-none">
                        </div>

                        <div>
                            <label for="wa-custom-location" class="block text-xs font-semibold text-slate-300 mb-1">Alamat / Lokasi di Purwokerto</label>
                            <input type="text" id="wa-custom-location" placeholder="Contoh: Sokaraja Kulon RT 02" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-sm focus:border-cyan-400 focus:outline-none">
                        </div>

                        <div>
                            <label for="wa-custom-msg" class="block text-xs font-semibold text-slate-300 mb-1">Pesan / Pertanyaan</label>
                            <textarea id="wa-custom-msg" rows="3" placeholder="Saya ingin tanya tentang pemasangan paket WiFi 25 Mbps..." class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-sm focus:border-cyan-400 focus:outline-none"></textarea>
                        </div>

                        <button type="button" onclick="sendDirectWhatsApp()" class="w-full py-3.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-sm flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/25 transition">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            <span>Chat ke WhatsApp +62 821-3841-3292</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
<script>
    function selectPackage(pkgId) {
        const select = document.getElementById('reg-package');
        if (select) {
            select.value = pkgId;
            const regSection = document.getElementById('daftar');
            if (regSection) {
                regSection.scrollIntoView({ behavior: 'smooth' });
            }
        }
    }

    function sendDirectWhatsApp() {
        const name = document.getElementById('wa-custom-name').value.trim() || 'Pelanggan';
        const location = document.getElementById('wa-custom-location').value.trim() || 'Purwokerto';
        const msg = document.getElementById('wa-custom-msg').value.trim() || 'Saya ingin informasi pemasangan WiFi TRICORE.';

        const fullText = encodeURIComponent(
            `Halo TRICORE DATA MEDIA, saya ${name} dari ${location}.\n\n${msg}`
        );

        window.open(`https://wa.me/6282138413292?text=${fullText}`, '_blank');
    }
</script>
@endpush
