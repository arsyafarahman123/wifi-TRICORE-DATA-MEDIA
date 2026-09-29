@extends('layouts.portal')

@section('title', 'Kelola Pelanggan | Portal Mitra TRICORE')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-white">Data Pelanggan WiFi</h1>
            <p class="text-xs text-slate-400">Total data pelanggan terdaftar di seluruh area coverage</p>
        </div>
        <a href="{{ route('portal.customers.create') }}" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-emerald-500 text-slate-950 font-bold text-xs hover:brightness-110 shadow-md shadow-cyan-500/20 transition flex items-center gap-2 self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Daftarkan Pelanggan Baru</span>
        </a>
    </div>

    <!-- Search & Filter Form -->
    <div class="glass-card p-4 rounded-2xl border border-white/10">
        <form action="{{ route('portal.customers.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3 text-xs">
            <div class="sm:col-span-5 relative">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Cari nama, ID Pelanggan (TDM-...), No WhatsApp, alamat..." 
                       class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400">
            </div>

            <div class="sm:col-span-3">
                <select name="status" class="w-full px-3 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-slate-200 focus:outline-none focus:border-cyan-400">
                    <option value="">Semua Status Layanan</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Pemasangan</option>
                    <option value="isolated" {{ request('status') === 'isolated' ? 'selected' : '' }}>Terisolir (Belum Bayar)</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Berhenti Berlangganan</option>
                </select>
            </div>

            <div class="sm:col-span-3">
                <select name="package_id" class="w-full px-3 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-slate-200 focus:outline-none focus:border-cyan-400">
                    <option value="">Semua Paket WiFi</option>
                    @foreach($packages as $pkg)
                        <option value="{{ $pkg->id }}" {{ request('package_id') == $pkg->id ? 'selected' : '' }}>
                            {{ $pkg->name }} ({{ $pkg->speed_mbps }} Mbps)
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-1 flex gap-2">
                <button type="submit" class="w-full py-2.5 px-3 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold transition flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-18 0 7 7 0 0114 0z"/></svg>
                </button>
            </div>
        </form>
    </div>

    <!-- Customers Table -->
    <div class="glass-panel rounded-2xl border border-white/10 overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-950/80 border-b border-white/10 text-slate-400 font-bold uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4">No. Pelanggan</th>
                        <th class="py-3 px-4">Nama Pelanggan</th>
                        <th class="py-3 px-4">Paket Internet</th>
                        <th class="py-3 px-4">Wilayah / Alamat</th>
                        <th class="py-3 px-4">Status Internet</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-slate-300">
                    @forelse($customers as $c)
                        <tr class="hover:bg-slate-900/50 transition">
                            <td class="py-3.5 px-4 font-mono font-bold text-white whitespace-nowrap">
                                <a href="{{ route('portal.customers.show', $c) }}" class="text-cyan-400 hover:underline">
                                    {{ $c->customer_code }}
                                </a>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-white">{{ $c->name }}</div>
                                <div class="text-[11px] text-slate-400">{{ $c->phone }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-semibold text-slate-200">{{ $c->package->name }}</span>
                                <span class="block text-[11px] text-emerald-400 font-mono">{{ $c->package->formatted_price }}/bln</span>
                            </td>
                            <td class="py-3.5 px-4 max-w-xs truncate">
                                <span class="font-medium text-white">{{ $c->district }}</span>
                                <span class="block text-[11px] text-slate-400 truncate">{{ $c->address }}</span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @php $badge = $c->status_badge; @endphp
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $badge['class'] }}">
                                    {{ $badge['label'] }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap space-x-1">
                                <a href="{{ route('portal.customers.show', $c) }}" class="px-2.5 py-1.5 rounded-lg bg-slate-800 text-cyan-300 hover:bg-slate-700 transition">
                                    Detail
                                </a>
                                <a href="{{ route('portal.customers.edit', $c) }}" class="px-2.5 py-1.5 rounded-lg bg-slate-800 text-slate-300 hover:bg-slate-700 transition">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-500">
                                Tidak ada data pelanggan yang sesuai dengan pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($customers->hasPages())
            <div class="p-4 border-t border-white/10 bg-slate-950/40">
                {{ $customers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
