@extends('layouts.portal')

@section('title', 'Tagihan & Transaksi | Portal Mitra TRICORE')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-white">Tagihan & Transaksi Pelanggan</h1>
            <p class="text-xs text-slate-400">Pencatatan invoice lunas / belum bayar & konfirmasi pembayaran mitra</p>
        </div>

        <form action="{{ route('portal.invoices.generate-monthly') }}" method="POST" onsubmit="return confirm('Terbitkan tagihan baru untuk seluruh pelanggan aktif periode berjalan?')">
            @csrf
            <input type="hidden" name="billing_month" value="{{ now()->isoFormat('MMMM Y') }}">
            <button type="submit" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-emerald-500 text-slate-950 font-bold text-xs hover:brightness-110 shadow-md shadow-cyan-500/20 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                <span>Generate Tagihan {{ now()->isoFormat('MMMM Y') }}</span>
            </button>
        </form>
    </div>

    <!-- Stats Bar -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="glass-card p-4 rounded-xl border border-white/5 space-y-1">
            <span class="text-[11px] text-slate-400">Total Tagihan Lunas</span>
            <div class="text-xl font-black text-emerald-400 font-mono">
                Rp{{ number_format($stats['total_paid'], 0, ',', '.') }}
            </div>
            <span class="text-[10px] text-slate-500">{{ $stats['paid_count'] }} invoice</span>
        </div>

        <div class="glass-card p-4 rounded-xl border border-white/5 space-y-1">
            <span class="text-[11px] text-slate-400">Total Belum Bayar</span>
            <div class="text-xl font-black text-rose-400 font-mono">
                Rp{{ number_format($stats['total_unpaid'], 0, ',', '.') }}
            </div>
            <span class="text-[10px] text-slate-500">{{ $stats['unpaid_count'] }} invoice</span>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="glass-card p-4 rounded-2xl border border-white/10">
        <form action="{{ route('portal.invoices.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3 text-xs">
            <div class="sm:col-span-6 relative">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Cari nomor invoice (INV-...), nama pelanggan, no WA..." 
                       class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400">
            </div>

            <div class="sm:col-span-3">
                <select name="status" class="w-full px-3 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-slate-200 focus:outline-none focus:border-cyan-400">
                    <option value="">Semua Status Bayar</option>
                    <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }}>Belum Bayar</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Lunas</option>
                </select>
            </div>

            <div class="sm:col-span-2">
                <input type="text" 
                       name="month" 
                       value="{{ request('month') }}" 
                       placeholder="Bulan (misal: September)" 
                       class="w-full px-3 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-slate-200 focus:outline-none focus:border-cyan-400">
            </div>

            <div class="sm:col-span-1">
                <button type="submit" class="w-full py-2.5 px-3 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold transition flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-18 0 7 7 0 0114 0z"/></svg>
                </button>
            </div>
        </form>
    </div>

    <!-- Invoices Table -->
    <div class="glass-panel rounded-2xl border border-white/10 overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-950/80 border-b border-white/10 text-slate-400 font-bold uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4">No. Invoice</th>
                        <th class="py-3 px-4">Pelanggan</th>
                        <th class="py-3 px-4">Paket / Periode</th>
                        <th class="py-3 px-4">Batas Jatuh Tempo</th>
                        <th class="py-3 px-4">Nominal</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-slate-300">
                    @forelse($invoices as $inv)
                        <tr class="hover:bg-slate-900/50 transition">
                            <td class="py-3.5 px-4 font-mono font-bold text-white whitespace-nowrap">
                                {{ $inv->invoice_number }}
                            </td>
                            <td class="py-3.5 px-4">
                                <a href="{{ route('portal.customers.show', $inv->customer) }}" class="font-bold text-cyan-400 hover:underline">
                                    {{ $inv->customer->name }}
                                </a>
                                <span class="block text-[11px] text-slate-400 font-mono">{{ $inv->customer->customer_code }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-semibold text-slate-200">{{ $inv->package->name }}</span>
                                <span class="block text-[11px] text-slate-400">{{ $inv->billing_month }}</span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap text-slate-300">
                                {{ $inv->due_date ? $inv->due_date->format('d/m/Y') : '-' }}
                            </td>
                            <td class="py-3.5 px-4 font-mono font-bold text-white whitespace-nowrap">
                                {{ $inv->formatted_amount }}
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @php $badge = $inv->status_badge; @endphp
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $badge['class'] }}">
                                    {{ $badge['label'] }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap space-x-1">
                                <a href="{{ route('portal.invoices.print', $inv) }}" target="_blank" class="px-2.5 py-1.5 rounded-lg bg-slate-800 text-slate-200 hover:bg-slate-700 transition">
                                    Cetak
                                </a>

                                @if($inv->status === 'unpaid')
                                    <form action="{{ route('portal.invoices.pay', $inv) }}" method="POST" class="inline" onsubmit="return confirm('Tandai tagihan {{ $inv->invoice_number }} LUNAS? Layanan internet pelanggan {{ $inv->customer->name }} akan otomatis AKTIF.')">
                                        @csrf
                                        <input type="hidden" name="payment_method" value="Tunai ke Mitra">
                                        <input type="hidden" name="payment_reference" value="TRX-{{ date('YmdHis') }}">
                                        <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold transition">
                                            ✓ Tandai Lunas
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-500">
                                Belum ada catatan tagihan sesuai pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($invoices->hasPages())
            <div class="p-4 border-t border-white/10 bg-slate-950/40">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
