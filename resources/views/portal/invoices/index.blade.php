@extends('layouts.portal')

@section('title', 'Billing & Invoice Report - TRINET-BILL | TRICORE DATA MEDIA')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-white">Billing & Invoice Report</h1>
            <p class="text-xs text-slate-400">Pencatatan invoice lunas / belum bayar & konfirmasi pembayaran TRINET-BILL</p>
        </div>

        <form action="{{ route('portal.invoices.generate-monthly') }}" method="POST" onsubmit="return confirm('Terbitkan tagihan baru untuk seluruh pelanggan aktif periode berjalan?')" class="w-full sm:w-auto">
            @csrf
            <input type="hidden" name="billing_month" value="{{ now()->isoFormat('MMMM Y') }}">
            <button type="submit" class="w-full sm:w-auto justify-center px-4 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-emerald-500 text-slate-950 font-bold text-xs hover:brightness-110 shadow-md shadow-cyan-500/20 transition flex items-center gap-2 active:scale-95">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                <span>Generate Tagihan {{ now()->isoFormat('MMMM Y') }}</span>
            </button>
        </form>
    </div>

    <!-- Stats Bar -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
        <div class="glass-card p-4 rounded-xl border border-white/5 space-y-1">
            <span class="text-[11px] text-slate-400">Total Tagihan Lunas</span>
            <div class="text-lg sm:text-xl font-black text-emerald-400 font-mono truncate">
                Rp{{ number_format($stats['total_paid'], 0, ',', '.') }}
            </div>
            <span class="text-[10px] text-slate-500">{{ $stats['paid_count'] }} invoice</span>
        </div>

        <div class="glass-card p-4 rounded-xl border border-white/5 space-y-1">
            <span class="text-[11px] text-slate-400">Total Belum Bayar</span>
            <div class="text-lg sm:text-xl font-black text-rose-400 font-mono truncate">
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
                       placeholder="Cari invoice (INV-...), nama pelanggan, WA..." 
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
                <button type="submit" class="w-full py-2.5 px-3 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold transition flex items-center justify-center active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-18 0 7 7 0 0114 0z"/></svg>
                </button>
            </div>
        </form>
    </div>

    <!-- Invoices View: Dual Mode (Mobile Cards + Desktop Table) -->
    <div class="glass-panel rounded-2xl border border-white/10 overflow-hidden shadow-xl">
        
        <!-- Mobile Card List View (Phones) -->
        <div class="md:hidden divide-y divide-white/5">
            @forelse($invoices as $inv)
                @php $badge = $inv->status_badge; @endphp
                <div class="p-4 space-y-3">
                    <div class="flex items-center justify-between gap-2">
                        <span class="font-mono text-xs font-bold text-white">{{ $inv->invoice_number }}</span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $badge['class'] }}">
                            {{ $badge['label'] }}
                        </span>
                    </div>

                    <div class="space-y-1">
                        <a href="{{ route('portal.customers.show', $inv->customer) }}" class="font-bold text-cyan-400 hover:underline text-sm block">
                            {{ $inv->customer->name }}
                        </a>
                        <div class="text-xs text-slate-400 flex items-center justify-between">
                            <span>{{ $inv->package->name }} • {{ $inv->billing_month }}</span>
                            <span class="font-mono font-bold text-emerald-400 text-sm">{{ $inv->formatted_amount }}</span>
                        </div>
                        <div class="text-[11px] text-slate-400">
                            Jatuh tempo: {{ $inv->due_date ? $inv->due_date->format('d/m/Y') : '-' }}
                        </div>
                        @if($inv->payment_proof)
                            <div class="pt-1 flex items-center gap-2">
                                <a href="{{ asset($inv->payment_proof) }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] font-bold text-amber-400 hover:underline">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <span>Lihat Bukti Transfer</span>
                                </a>
                                @if($inv->proof_uploaded_at)
                                    <span class="text-[10px] text-slate-500">({{ $inv->proof_uploaded_at->format('d/m H:i') }})</span>
                                @endif
                            </div>
                        @endif
                        @if($inv->rejection_reason)
                            <div class="text-[11px] text-rose-400">
                                Alasan Ditolak: {{ $inv->rejection_reason }}
                            </div>
                        @endif
                    </div>

                    <div class="pt-2 border-t border-white/5 flex flex-col gap-2">
                        <div class="flex items-center justify-between gap-2">
                            <a href="{{ route('portal.invoices.print', $inv) }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-slate-800 text-slate-200 hover:bg-slate-700 text-xs font-medium transition">
                                Cetak
                            </a>
                            <a href="{{ route('payment.page', ['invoice_number' => $inv->invoice_number]) }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-cyan-950/60 text-cyan-300 border border-cyan-500/30 text-xs font-medium transition">
                                Link Bayar BNC
                            </a>
                        </div>

                        @if($inv->status === 'unpaid')
                            <div class="space-y-2 pt-1 border-t border-white/5">
                                <form action="{{ route('portal.invoices.pay', $inv) }}" method="POST" class="w-full" onsubmit="return confirm('Verifikasi LUNAS tagihan {{ $inv->invoice_number }}? Layanan pelanggan {{ $inv->customer->name }} akan otomatis AKTIF.')">
                                    @csrf
                                    <div class="flex items-center gap-1.5 w-full">
                                        <select name="payment_method" class="flex-1 px-2 py-1.5 rounded-lg bg-slate-900 border border-white/10 text-white text-[10px] focus:outline-none focus:border-cyan-400">
                                            <option value="Transfer Bank Neo Commerce">Transfer Bank Neo Commerce</option>
                                            <option value="Tunai ke Mitra">Tunai ke Mitra</option>
                                            <option value="Transfer Bank BCA">Transfer Bank BCA</option>
                                            <option value="Transfer Bank BRI">Transfer Bank BRI</option>
                                            <option value="Transfer Bank Mandiri">Transfer Bank Mandiri</option>
                                            <option value="QRIS">QRIS</option>
                                        </select>
                                        <input type="hidden" name="payment_reference" value="{{ $inv->payment_reference ?: ('VERIF-' . date('YmdHis')) }}">
                                        <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs shadow-sm transition active:scale-95 whitespace-nowrap">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            <span>Verifikasi Lunas</span>
                                        </button>
                                    </div>
                                </form>

                                @if($inv->payment_proof)
                                    <!-- Reject Form for Mobile -->
                                    <form action="{{ route('portal.invoices.reject', $inv) }}" method="POST" class="flex items-center gap-1.5" onsubmit="return confirm('Tolak bukti transfer {{ $inv->invoice_number }}?')">
                                        @csrf
                                        <input type="text" name="rejection_reason" required placeholder="Alasan tolak bukti..." class="flex-1 px-2 py-1 rounded bg-slate-900 border border-rose-500/30 text-rose-300 text-[10px] placeholder-slate-500 focus:outline-none">
                                        <button type="submit" class="px-2 py-1 rounded bg-rose-500/20 text-rose-400 border border-rose-500/30 text-[10px] font-bold hover:bg-rose-500/30">
                                            Tolak
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-500 text-xs">
                    Belum ada catatan tagihan sesuai pencarian.
                </div>
            @endforelse
        </div>

        <!-- Desktop Table View (Laptops & Desktops) -->
        <div class="hidden md:block overflow-x-auto">
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
                                @if($inv->payment_proof)
                                    <div class="mt-1">
                                        <a href="{{ asset($inv->payment_proof) }}" target="_blank" class="inline-flex items-center gap-1 text-[10px] text-amber-400 hover:underline">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <span>Bukti Transfer</span>
                                        </a>
                                    </div>
                                @endif
                                @if($inv->rejection_reason)
                                    <div class="text-[9px] text-rose-400 max-w-[130px] truncate" title="{{ $inv->rejection_reason }}">
                                        Ditolak: {{ $inv->rejection_reason }}
                                    </div>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap space-x-1">
                                <a href="{{ route('payment.page', ['invoice_number' => $inv->invoice_number]) }}" target="_blank" class="px-2 py-1 rounded-lg bg-cyan-950/60 text-cyan-300 border border-cyan-500/30 text-[10px] font-medium transition hover:bg-cyan-900/60">
                                    Link Bayar
                                </a>
                                <a href="{{ route('portal.invoices.print', $inv) }}" target="_blank" class="px-2 py-1 rounded-lg bg-slate-800 text-slate-200 hover:bg-slate-700 transition text-[10px]">
                                    Cetak
                                </a>

                                @if($inv->status === 'unpaid')
                                    <form action="{{ route('portal.invoices.pay', $inv) }}" method="POST" class="inline" onsubmit="return confirm('Verifikasi LUNAS tagihan {{ $inv->invoice_number }}? Layanan pelanggan {{ $inv->customer->name }} akan otomatis AKTIF.')">
                                        @csrf
                                        <div class="inline-flex items-center gap-1">
                                            <select name="payment_method" class="px-1.5 py-1 rounded-lg bg-slate-900 border border-white/10 text-white text-[10px] focus:outline-none focus:border-cyan-400">
                                                <option value="Transfer Bank Neo Commerce">Transfer BNC</option>
                                                <option value="Tunai ke Mitra">Tunai</option>
                                                <option value="Transfer Bank BCA">BCA</option>
                                                <option value="Transfer Bank BRI">BRI</option>
                                                <option value="Transfer Bank Mandiri">Mandiri</option>
                                                <option value="QRIS">QRIS</option>
                                            </select>
                                            <input type="hidden" name="payment_reference" value="{{ $inv->payment_reference ?: ('VERIF-' . date('YmdHis')) }}">
                                            <button type="submit" class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold transition active:scale-95 whitespace-nowrap text-[10px]">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                <span>Verifikasi</span>
                                            </button>
                                        </div>
                                    </form>

                                    @if($inv->payment_proof)
                                        <button type="button" 
                                                onclick="const r = prompt('Masukkan alasan penolakan bukti transfer:'); if(r){ document.getElementById('reject-reason-{{ $inv->id }}').value = r; document.getElementById('reject-form-{{ $inv->id }}').submit(); }" 
                                                class="px-2 py-1 rounded-lg bg-rose-500/20 text-rose-400 border border-rose-500/30 text-[10px] font-bold hover:bg-rose-500/30">
                                            Tolak
                                        </button>
                                        <form id="reject-form-{{ $inv->id }}" action="{{ route('portal.invoices.reject', $inv) }}" method="POST" class="hidden">
                                            @csrf
                                            <input type="hidden" name="rejection_reason" id="reject-reason-{{ $inv->id }}">
                                        </form>
                                    @endif
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

