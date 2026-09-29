@extends('layouts.app')

@section('title', 'Pendaftaran Berhasil - ' . $customer->customer_code . ' | TRICORE DATA MEDIA')

@section('content')
<section class="py-16 sm:py-24 relative">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Success Card -->
        <div class="glass-panel p-8 sm:p-12 rounded-3xl border border-emerald-500/40 shadow-2xl shadow-emerald-950/50 text-center space-y-8 relative overflow-hidden">
            <!-- Background Glow -->
            <div class="absolute -top-24 -left-24 w-64 h-64 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Success Icon -->
            <div class="w-20 h-20 mx-auto rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 flex items-center justify-center">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <!-- Title & Notice -->
            <div class="space-y-3">
                <span class="text-xs font-bold tracking-widest text-emerald-400 uppercase">PENDAFTARAN BERHASIL DICATAT</span>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-white">Selamat Datang di TRICORE DATA MEDIA!</h1>
                <p class="text-sm text-slate-300 max-w-lg mx-auto leading-relaxed">
                    Data pendaftaran Anda telah tersimpan di sistem kami. Tim teknisi kami akan segera melakukan verifikasi jangkauan ODP dan menjadwalkan pemasangan kabel fiber optik ke lokasi Anda.
                </p>
            </div>

            <!-- Code Badge Box -->
            <div class="p-6 rounded-2xl bg-slate-900/90 border border-white/10 space-y-4 max-w-lg mx-auto text-left">
                <div class="flex items-center justify-between border-b border-white/10 pb-3">
                    <span class="text-xs text-slate-400">Nomor Registrasi Pelanggan</span>
                    <span class="font-mono text-xl font-bold text-cyan-400">{{ $customer->customer_code }}</span>
                </div>
                <div class="flex items-center justify-between border-b border-white/10 pb-3 text-xs">
                    <span class="text-slate-400">Paket Terpilih</span>
                    <span class="font-bold text-white">{{ $package->name }} ({{ $package->speed_mbps }} Mbps)</span>
                </div>
                <div class="flex items-center justify-between border-b border-white/10 pb-3 text-xs">
                    <span class="text-slate-400">Tarif Bulanan</span>
                    <span class="font-bold text-emerald-400">{{ $package->formatted_price }} / bulan</span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="text-slate-400">Nama Pelanggan</span>
                    <span class="font-medium text-slate-200">{{ $customer->name }}</span>
                </div>
            </div>

            <!-- Next Step Guide -->
            <div class="p-4 rounded-xl bg-cyan-950/40 border border-cyan-500/20 text-xs text-cyan-200 text-left space-y-2">
                <strong class="block text-cyan-300 text-sm font-bold">Langkah Selanjutnya:</strong>
                <p>Silakan klik tombol hijau di bawah untuk mengirim konfirmasi pendaftaran langsung ke WhatsApp Admin TRICORE agar proses survei & instalasi bisa segera dijadwalkan.</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center pt-2">
                <a href="{{ $waUrl }}" target="_blank" class="px-8 py-4 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-extrabold text-sm flex items-center justify-center gap-2.5 shadow-xl shadow-emerald-500/30 transition transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    <span>Konfirmasi ke WhatsApp Admin</span>
                </a>
                <a href="{{ route('home') }}" class="px-6 py-4 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-sm transition">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
