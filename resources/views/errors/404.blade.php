@extends('layouts.app')

@section('title', '404 - Halaman Tidak Ditemukan | TRINET-BILL')

@section('content')
<section class="py-20 sm:py-28 relative flex items-center justify-center min-h-[calc(100vh-250px)]">
    <div class="max-w-md w-full mx-auto px-4 text-center space-y-6">
        <div class="glass-panel p-8 sm:p-10 rounded-3xl border border-white/10 shadow-2xl space-y-6">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center text-cyan-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            
            <div class="space-y-2">
                <span class="text-4xl sm:text-5xl font-black font-mono text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-emerald-400">404</span>
                <h1 class="text-xl font-extrabold text-white">Halaman Tidak Ditemukan</h1>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Halaman atau tautan yang Anda tuju tidak tersedia atau telah dipindahkan.
                </p>
            </div>

            <div class="pt-4 border-t border-white/10 flex flex-col sm:flex-row gap-3">
                <a href="{{ route('home') }}" class="flex-1 py-3 px-4 rounded-xl bg-gradient-to-r from-cyan-500 to-emerald-500 text-slate-950 font-extrabold text-xs hover:brightness-110 shadow-lg shadow-cyan-500/20 transition flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Ke Beranda</span>
                </a>
                <a href="{{ route('bill.check') }}" class="flex-1 py-3 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 border border-white/10 font-bold text-xs transition flex items-center justify-center gap-1.5">
                    <span>Cek Tagihan</span>
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

