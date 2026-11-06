@extends('layouts.app')

@section('title', 'Cek Tagihan & Status Layanan Internet - TRINET-BILL (TRICORE DATA MEDIA)')

@section('content')
<section class="py-16 sm:py-24 relative flex items-center justify-center min-h-[calc(100vh-220px)]">
    <!-- Glow background accents -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden flex items-center justify-center">
        <div class="w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl -top-10 -left-10"></div>
        <div class="w-96 h-96 bg-cyan-500/10 rounded-full blur-3xl -bottom-10 -right-10"></div>
    </div>

    <div class="max-w-2xl w-full mx-auto px-4 sm:px-6 relative z-10 space-y-6">
        
        <!-- Header & Breadcrumb -->
        <div class="flex items-center justify-between text-xs text-slate-400">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 hover:text-cyan-400 transition font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Beranda
            </a>
            <span class="font-mono text-emerald-400 font-semibold flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Portal Mandiri Aktif
            </span>
        </div>

        <!-- Main Card -->
        <div class="glass-panel p-6 sm:p-10 rounded-3xl border border-white/10 shadow-2xl space-y-6 bg-slate-900/80 backdrop-blur-xl">
            
            <!-- Badge & Title -->
            <div class="text-center space-y-3">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                    Cek Tagihan Pelanggan WiFi TRICORE
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                    Cek Status & Rincian Tagihan
                </h1>
                <p class="text-xs sm:text-sm text-slate-400 max-w-lg mx-auto">
                    Masukkan Nomor ID Pelanggan (11 digit, contoh: <span class="text-cyan-300 font-mono font-bold">26000000001</span>) atau Nomor WhatsApp yang terdaftar untuk melihat tagihan bulan ini.
                </p>
            </div>

            <!-- Error Notification Alert if any -->
            @if(isset($errorMessage) || session('error'))
                <div class="p-4 rounded-2xl bg-rose-950/60 border border-rose-500/30 text-rose-200 text-xs flex items-start gap-3">
                    <svg class="w-5 h-5 text-rose-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <div class="space-y-1">
                        <span class="font-bold block">Pencarian Tidak Ditemukan</span>
                        <p class="text-slate-300">{{ $errorMessage ?? session('error') }}</p>
                        <a href="https://wa.me/6282138413292?text=Halo%20TRICORE,%20saya%20ingin%20cek%20nomor%20pelanggan%20WiFi%20saya" target="_blank" class="inline-flex items-center gap-1 text-emerald-400 hover:underline font-semibold mt-1">
                            Hubungi Bantuan CS WhatsApp &rarr;
                        </a>
                    </div>
                </div>
            @endif

            <!-- Search Form -->
            <form action="{{ route('bill.check') }}" method="GET" class="space-y-4">
                <div class="space-y-1.5">
                    <label for="search-input" class="block text-xs font-semibold text-slate-300">
                        Nomor Pelanggan (11 Digit) atau WhatsApp
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-18 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" 
                               id="search-input"
                               name="search_query" 
                               value="{{ $searchQuery ?? old('search_query', request('search_query', '')) }}"
                               placeholder="Contoh: 26000000001 atau 082199887766" 
                               required 
                               autocomplete="off"
                               class="w-full pl-11 pr-4 py-3.5 rounded-xl bg-slate-950/80 border border-white/15 text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 text-sm font-medium transition">
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 text-slate-950 font-extrabold text-sm hover:brightness-110 shadow-lg shadow-emerald-500/25 transition transform active:scale-[0.99] flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span>Periksa Tagihan Sekarang</span>
                </button>
            </form>



            <!-- Features Information Strip -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-3 border-t border-white/5 text-[11px] text-slate-400 text-center">
                <div class="p-2.5 rounded-lg bg-slate-950/40 border border-white/5 space-y-1">
                    <span class="font-bold text-slate-200 block">Status Realtime</span>
                    <span>Koneksi & Tagihan Bulan Berjalan</span>
                </div>
                <div class="p-2.5 rounded-lg bg-slate-950/40 border border-white/5 space-y-1">
                    <span class="font-bold text-slate-200 block">Instruksi Bayar</span>
                    <span>Transfer BCA / BRI / Mandiri</span>
                </div>
                <div class="p-2.5 rounded-lg bg-slate-950/40 border border-white/5 space-y-1">
                    <span class="font-bold text-slate-200 block">Konfirmasi Otomatis</span>
                    <span>1-Klik WhatsApp Customer Service</span>
                </div>
            </div>

            <!-- Link to Login for Mitra / Admin -->
            <div class="pt-2 text-center text-xs text-slate-400">
                Anda pengelola / mitra TRICORE? <a href="{{ route('login') }}" class="text-cyan-400 font-bold hover:underline">Masuk ke Portal Mitra</a>
            </div>

        </div>
    </div>
</section>
@endsection
