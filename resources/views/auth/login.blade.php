@extends('layouts.app')

@section('title', 'Login TRINET-BILL - Portal Mitra & Admin (TRICORE DATA MEDIA)')

@section('content')
<section class="py-16 sm:py-24 relative flex items-center justify-center min-h-[calc(100vh-200px)]">
    <div class="max-w-md w-full mx-auto px-4 sm:px-6">
        <!-- Login Card -->
        <div class="glass-panel p-8 sm:p-10 rounded-3xl border border-white/10 shadow-2xl space-y-6">
            
            <!-- Brand & Heading -->
            <div class="text-center space-y-3">
                <div class="flex justify-center mb-2">
                    <img src="{{ asset('tricorenet-logo.png') }}" alt="TRICORE.NET" class="h-12 w-auto object-contain drop-shadow-[0_0_15px_rgba(6,182,212,0.4)]">
                </div>
                <h1 class="text-2xl font-extrabold text-white">TRINET-BILL Portal</h1>
                <p class="text-xs text-slate-400">Masuk untuk mengelola pelanggan, ODP, tagihan & transaksi</p>
            </div>

            <!-- Login Form -->
            <form action="{{ route('login.submit') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="login-email" class="block text-xs font-semibold text-slate-300 mb-1.5">Alamat Email</label>
                    <input type="email" 
                           id="login-email"
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autocomplete="off"
                           placeholder="nama@tricoredatamedia.net" 
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-sm focus:border-cyan-400 focus:outline-none">
                    @error('email')
                        <span class="text-xs text-rose-400 block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="login-password" class="block text-xs font-semibold text-slate-300">Password</label>
                    </div>
                    <input type="password" 
                           id="login-password"
                           name="password" 
                           value=""
                           required 
                           autocomplete="off"
                           placeholder="••••••••" 
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-sm focus:border-cyan-400 focus:outline-none">
                </div>

                <div class="flex items-center justify-between text-xs text-slate-400">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded bg-slate-900 border-white/20 text-cyan-500 focus:ring-0">
                        <span>Ingat saya</span>
                    </label>
                    <a href="https://wa.me/6282138413292?text=Lupa%20password%20portal%20mitra%20TRICORE" target="_blank" class="hover:text-cyan-400 transition">Bantuan Akun?</a>
                </div>

                <button type="submit" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-cyan-500 to-emerald-500 text-slate-950 font-extrabold text-sm hover:brightness-110 shadow-lg shadow-cyan-500/25 transition">
                    Masuk ke Dashboard
                </button>
            </form>

            <!-- Customer Self-Service Bill Link -->
            <div class="pt-4 border-t border-white/10 text-center space-y-2">
                <span class="text-xs text-slate-400 block">Bukan Mitra? Ingin Cek Tagihan WiFi Anda?</span>
                <a href="{{ route('bill.check') }}" class="w-full py-2.5 px-4 rounded-xl bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 text-xs font-bold transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                    <span>Cek Tagihan Mandiri Pelanggan &rarr;</span>
                </a>
            </div>

        </div>
    </div>
</section>
@endsection
