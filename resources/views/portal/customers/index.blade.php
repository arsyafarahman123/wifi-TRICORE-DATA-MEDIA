@extends('layouts.portal')

@section('title', 'Kelola Pelanggan | Portal Mitra TRICORE')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-white">Data Pelanggan WiFi</h1>
            <p class="text-xs text-slate-400">Total data pelanggan terdaftar di seluruh area coverage TRICORE</p>
        </div>
        <a href="{{ route('portal.customers.create') }}" class="w-full sm:w-auto justify-center px-4 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-emerald-500 text-slate-950 font-bold text-xs hover:brightness-110 shadow-md shadow-cyan-500/20 transition flex items-center gap-2 active:scale-95">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
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
                       placeholder="Cari nama, ID (TDM-...), WhatsApp, alamat..." 
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
                <button type="submit" class="w-full py-2.5 px-3 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold transition flex items-center justify-center active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-18 0 7 7 0 0114 0z"/></svg>
                </button>
            </div>
        </form>
    </div>

    <!-- Customers View: Dual Mode (Mobile Cards + Desktop Table) -->
    <div class="glass-panel rounded-2xl border border-white/10 overflow-hidden shadow-xl">
        
        <!-- Mobile Card List View (Phones) -->
        <div class="md:hidden divide-y divide-white/5">
            @forelse($customers as $c)
                @php $badge = $c->status_badge; @endphp
                <div class="p-4 space-y-3">
                    <div class="flex items-center justify-between gap-2">
                        <a href="{{ route('portal.customers.show', $c) }}" class="font-mono text-xs font-bold text-cyan-400 hover:underline">
                            {{ $c->customer_code }}
                        </a>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $badge['class'] }}">
                            {{ $badge['label'] }}
                        </span>
                    </div>

                    <div>
                        <a href="{{ route('portal.customers.show', $c) }}" class="font-bold text-white text-sm block">
                            {{ $c->name }}
                        </a>
                        <div class="flex items-center gap-2 text-xs text-slate-400 mt-0.5">
                            <span>{{ $c->district }}</span>
                            <span>•</span>
                            <span class="text-cyan-300">{{ $c->package->name }} ({{ $c->package->speed_mbps }} Mbps)</span>
                        </div>
                        <div class="text-[11px] text-slate-400 mt-1 line-clamp-1">
                            {{ $c->address }}
                        </div>
                    </div>

                    <div class="pt-2 border-t border-white/5 flex items-center justify-between gap-2">
                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $c->phone)) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 text-xs font-medium border border-emerald-500/30">
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            <span>{{ $c->phone }}</span>
                        </a>

                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('portal.customers.show', $c) }}" class="px-3 py-1.5 rounded-lg bg-slate-800 text-cyan-300 text-xs font-semibold hover:bg-slate-700 transition">
                                Detail
                            </a>
                            <a href="{{ route('portal.customers.edit', $c) }}" class="px-3 py-1.5 rounded-lg bg-slate-800 text-slate-300 text-xs font-semibold hover:bg-slate-700 transition">
                                Edit
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-500 text-xs">
                    Tidak ada data pelanggan yang sesuai dengan pencarian.
                </div>
            @endforelse
        </div>

        <!-- Desktop Table View (Laptops & Desktops) -->
        <div class="hidden md:block overflow-x-auto">
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

