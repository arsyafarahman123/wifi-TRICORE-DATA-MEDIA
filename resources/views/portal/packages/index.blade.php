@extends('layouts.portal')

@section('title', 'Daftar Paket Internet | Portal Mitra TRICORE')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-white">Daftar Paket Internet WiFi</h1>
            <p class="text-xs text-slate-400">Paket resmi TRICORE DATA MEDIA untuk wilayah Purwokerto dan sekitarnya</p>
        </div>
    </div>

    <!-- Package Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($packages as $pkg)
            <div class="glass-panel p-6 rounded-2xl border border-white/10 space-y-5 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-mono font-bold text-cyan-400">Paket #{{ $pkg->sort_order }}</span>
                        @if($pkg->badge)
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-cyan-500/20 text-cyan-300 border border-cyan-500/30">
                                {{ $pkg->badge }}
                            </span>
                        @endif
                    </div>

                    <div>
                        <h3 class="text-lg font-bold text-white">{{ $pkg->name }}</h3>
                        <div class="text-3xl font-black text-white font-mono mt-1">
                            {{ $pkg->speed_mbps }} <span class="text-base text-cyan-400 font-sans font-bold">Mbps</span>
                        </div>
                    </div>

                    <div class="text-xl font-bold text-emerald-400 font-mono">
                        {{ $pkg->formatted_price }} <span class="text-xs text-slate-400 font-normal">/ bulan</span>
                    </div>

                    <p class="text-xs text-slate-400 leading-relaxed">{{ $pkg->description }}</p>

                    <div class="p-3 rounded-xl bg-slate-900 border border-white/5 text-xs text-slate-300 flex items-center justify-between">
                        <span>Pelanggan Aktif:</span>
                        <strong class="text-cyan-300 font-mono text-sm">{{ $pkg->customers_count }} Pelanggan</strong>
                    </div>
                </div>

                <div class="pt-4 border-t border-white/10 flex items-center justify-between gap-2">
                    <span class="text-xs font-semibold {{ $pkg->is_active ? 'text-emerald-400' : 'text-slate-500' }}">
                        {{ $pkg->is_active ? '● Aktif Dijual' : '○ Dinonaktifkan' }}
                    </span>

                    <form action="{{ route('portal.packages.toggle', $pkg) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ $pkg->is_active ? 'bg-slate-800 text-rose-300 hover:bg-slate-700' : 'bg-emerald-500 text-slate-950 hover:bg-emerald-400' }}">
                            {{ $pkg->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
