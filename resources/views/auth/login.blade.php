@extends('layouts.app')

@section('title', 'Login TRINET-BILL - Portal Mitra & Admin (TRICORE DATA MEDIA)')

@section('content')
<section class="py-16 sm:py-24 relative flex items-center justify-center min-h-[calc(100vh-200px)]">
    <div class="max-w-md w-full mx-auto px-4 sm:px-6">
        <!-- Login Card -->
        <div class="glass-panel p-8 sm:p-10 rounded-3xl border border-white/10 shadow-2xl space-y-6">
            
            <!-- Brand & Heading -->
            <div class="text-center space-y-2">
                <div class="w-12 h-12 mx-auto rounded-xl bg-gradient-to-br from-cyan-500 to-emerald-500 p-0.5 shadow-lg shadow-cyan-500/20">
                    <div class="w-full h-full bg-[#090d16] rounded-[10px] flex items-center justify-center">
                        <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
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
                           value="{{ old('email', 'mitra@tricoredatamedia.net') }}" 
                           required 
                           autocomplete="email"
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
                           value="password123"
                           required 
                           placeholder="••••••••" 
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-sm focus:border-cyan-400 focus:outline-none">
                </div>

                <div class="flex items-center justify-between text-xs text-slate-400">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" checked class="rounded bg-slate-900 border-white/20 text-cyan-500 focus:ring-0">
                        <span>Ingat saya</span>
                    </label>
                    <a href="https://wa.me/6282138413292?text=Lupa%20password%20portal%20mitra%20TRICORE" target="_blank" class="hover:text-cyan-400 transition">Bantuan Akun?</a>
                </div>

                <button type="submit" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-cyan-500 to-emerald-500 text-slate-950 font-extrabold text-sm hover:brightness-110 shadow-lg shadow-cyan-500/25 transition">
                    Masuk ke Dashboard
                </button>
            </form>

            <!-- Quick Demo Accounts -->
            <div class="pt-4 border-t border-white/10 space-y-2">
                <span class="text-[11px] text-slate-500 block text-center font-medium">Akun Pengujian Demo (Sekali Klik):</span>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 text-xs">
                    <button type="button" 
                            onclick="document.getElementById('login-email').value='mitra@tricoredatamedia.net'; document.getElementById('login-password').value='password123';"
                            class="p-3 rounded-xl bg-slate-900/80 hover:bg-slate-800 border border-cyan-500/20 text-left transition active:scale-95">
                        <span class="font-bold text-cyan-300 block">Akun Mitra WiFi</span>
                        <span class="text-[11px] text-slate-400 truncate block">mitra@tricoredatamedia.net</span>
                    </button>
                    <button type="button" 
                            onclick="document.getElementById('login-email').value='admin@tricoredatamedia.net'; document.getElementById('login-password').value='password123';"
                            class="p-3 rounded-xl bg-slate-900/80 hover:bg-slate-800 border border-emerald-500/20 text-left transition active:scale-95">
                        <span class="font-bold text-emerald-300 block">Akun Administrator</span>
                        <span class="text-[11px] text-slate-400 truncate block">admin@tricoredatamedia.net</span>
                    </button>
                </div>
                <p class="text-[10px] text-slate-500 text-center">Password default: <code class="text-slate-300">password123</code></p>
            </div>

        </div>
    </div>
</section>
@endsection
