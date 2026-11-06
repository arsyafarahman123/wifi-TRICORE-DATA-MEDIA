@extends('layouts.app')

@section('title', '500 - Terjadi Kendala Sistem | TRINET-BILL')

@section('content')
<section class="py-20 sm:py-28 relative flex items-center justify-center min-h-[calc(100vh-250px)]">
    <div class="max-w-md w-full mx-auto px-4 text-center space-y-6">
        <div class="glass-panel p-8 sm:p-10 rounded-3xl border border-white/10 shadow-2xl space-y-6">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-rose-500/10 border border-rose-500/30 flex items-center justify-center text-rose-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            
            <div class="space-y-2">
                <span class="text-4xl sm:text-5xl font-black font-mono text-transparent bg-clip-text bg-gradient-to-r from-rose-400 to-amber-400">500</span>
                <h1 class="text-xl font-extrabold text-white">Terjadi Kendala Sistem</h1>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Sistem sedang memproses pemeliharaan berkala atau terjadi kendala sementara. Silakan coba beberapa saat lagi.
                </p>
            </div>

            <div class="pt-4 border-t border-white/10 flex flex-col sm:flex-row gap-3">
                <a href="{{ route('home') }}" class="flex-1 py-3 px-4 rounded-xl bg-gradient-to-r from-cyan-500 to-emerald-500 text-slate-950 font-extrabold text-xs hover:brightness-110 shadow-lg shadow-cyan-500/20 transition flex items-center justify-center gap-1.5">
                    <span>Ke Beranda</span>
                </a>
                <a href="https://wa.me/6282138413292" target="_blank" class="flex-1 py-3 px-4 rounded-xl bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 border border-emerald-500/30 font-bold text-xs transition flex items-center justify-center gap-1.5">
                    <span>Hubungi CS</span>
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

