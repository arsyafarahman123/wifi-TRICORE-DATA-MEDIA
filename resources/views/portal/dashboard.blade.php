@extends('layouts.portal')

@section('title', 'Dashboard Mitra TRICORE DATA MEDIA')

@section('content')
<div class="space-y-8">
    
    <!-- Top Greeting & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-white">Dashboard Operasional Mitra</h1>
            <p class="text-xs text-slate-400 mt-1">
                Selamat bekerja, <strong class="text-slate-200">{{ auth()->user()->name }}</strong> ({{ auth()->user()->mitra_name ?? 'Mitra WiFi' }}). Pantau pelanggan, paket & tagihan secara real-time.
            </p>
        </div>

        <div class="flex items-center gap-3 w-full sm:w-auto">
            <a href="{{ route('portal.customers.create') }}" class="w-full sm:w-auto justify-center px-4 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-emerald-500 text-slate-950 font-bold text-xs hover:brightness-110 shadow-md shadow-cyan-500/20 transition flex items-center gap-2 active:scale-95">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Pelanggan Baru</span>
            </a>
        </div>
    </div>

    <!-- KPI Metric Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <!-- KPI 1: Pelanggan Aktif -->
        <div class="glass-card p-5 rounded-2xl border border-white/10 space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-400">Pelanggan Aktif</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="text-3xl font-black text-white font-mono">
                {{ $activeCustomers }}
            </div>
            <span class="text-[11px] text-emerald-400 block font-medium">Internet menyala lancar</span>
        </div>

        <!-- KPI 2: Pending / Isolir -->
        <div class="glass-card p-5 rounded-2xl border border-white/10 space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-400">Perlu Tindakan</span>
                <div class="w-8 h-8 rounded-lg bg-rose-500/10 text-rose-400 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
            </div>
            <div class="text-3xl font-black text-rose-400 font-mono">
                {{ $pendingCustomers + $isolatedCustomers }}
            </div>
            <span class="text-[11px] text-slate-400 block font-medium">
                {{ $pendingCustomers }} pasang baru, {{ $isolatedCustomers }} terisolir
            </span>
        </div>

        <!-- KPI 3: Pendapatan Lunas -->
        <div class="glass-card p-5 rounded-2xl border border-white/10 space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-400">Total Uang Masuk (Lunas)</span>
                <div class="w-8 h-8 rounded-lg bg-cyan-500/10 text-cyan-400 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
            <div class="text-2xl font-black text-cyan-300 font-mono truncate">
                Rp{{ number_format($totalPaidRevenue, 0, ',', '.') }}
            </div>
            <span class="text-[11px] text-slate-400 block font-medium">{{ $paidInvoicesCount }} transaksi berhasil</span>
        </div>

        <!-- KPI 4: Tagihan Belum Dibayar -->
        <div class="glass-card p-5 rounded-2xl border border-white/10 space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-400">Belum Bayar (Pending)</span>
                <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-400 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="text-2xl font-black text-amber-300 font-mono truncate">
                Rp{{ number_format($totalUnpaidAmount, 0, ',', '.') }}
            </div>
            <span class="text-[11px] text-slate-400 block font-medium">{{ $unpaidInvoicesCount }} tagihan menunggu</span>
        </div>
    </div>

    <!-- Quick Action: Monthly Invoice Generator Banner -->
    <div class="glass-panel p-5 rounded-2xl border border-white/10 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-white">Generate Tagihan Bulanan Otomatis</h3>
                <p class="text-xs text-slate-400">Terbitkan invoice otomatis untuk semua pelanggan aktif periode berjalan (jatuh tempo tanggal 10).</p>
            </div>
        </div>

        <form action="{{ route('portal.invoices.generate-monthly') }}" method="POST" onsubmit="return confirm('Terbitkan tagihan baru untuk seluruh pelanggan aktif?')" class="w-full md:w-auto">
            @csrf
            <input type="hidden" name="billing_month" value="{{ now()->isoFormat('MMMM Y') }}">
            <button type="submit" class="w-full md:w-auto px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-cyan-600 hover:text-white text-cyan-300 border border-cyan-500/30 text-xs font-bold transition flex items-center justify-center gap-2 whitespace-nowrap active:scale-95">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                <span>Terbitkan Tagihan {{ now()->isoFormat('MMMM Y') }}</span>
            </button>
        </form>
    </div>

    <!-- Two Columns: Pending Bills & Recent Customers -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-start">
        
        <!-- Left: Tagihan Belum Dibayar & Cepat Tandai Lunas -->
        <div class="lg:col-span-7 glass-panel p-5 sm:p-6 rounded-2xl border border-white/10 space-y-4">
            <div class="flex items-center justify-between border-b border-white/10 pb-4">
                <div>
                    <h3 class="text-sm sm:text-base font-bold text-white">Tagihan Belum Bayar (Menunggu Konfirmasi)</h3>
                    <span class="text-xs text-slate-400">Tandai lunas untuk otomatis mengaktifkan internet pelanggan</span>
                </div>
                <a href="{{ route('portal.invoices.index', ['status' => 'unpaid']) }}" class="text-xs text-cyan-400 hover:underline shrink-0">Semua &rarr;</a>
            </div>

            @if($pendingInvoices->isEmpty())
                <div class="text-center py-8 text-xs text-slate-400">
                    Semua tagihan pelanggan saat ini telah lunas! 🎉
                </div>
            @else
                <div class="space-y-3">
                    @foreach($pendingInvoices as $inv)
                        <div class="p-4 rounded-xl bg-slate-900/80 border border-white/5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-mono text-xs font-bold text-white">{{ $inv->invoice_number }}</span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/10 text-rose-400 border border-rose-500/30">BELUM BAYAR</span>
                                </div>
                                <div class="text-xs text-slate-300">
                                    <strong class="text-white">{{ $inv->customer->name }}</strong> <span class="text-slate-400 font-mono">({{ $inv->customer->customer_code }})</span>
                                </div>
                                <div class="text-[11px] text-slate-400">
                                    Paket: {{ $inv->package->name }} ({{ $inv->package->speed_mbps }} Mbps) • Jatuh tempo: {{ $inv->due_date ? $inv->due_date->format('d/m/Y') : '-' }}
                                </div>
                            </div>

                            <div class="flex flex-row sm:flex-col items-center sm:items-end justify-between gap-2.5 pt-2 sm:pt-0 border-t border-white/5 sm:border-t-0">
                                <span class="font-mono font-bold text-emerald-400 text-sm sm:text-base">{{ $inv->formatted_amount }}</span>
                                
                                <!-- Form Tandai Lunas -->
                                <form action="{{ route('portal.invoices.pay', $inv) }}" method="POST" onsubmit="return confirm('Tandai tagihan {{ $inv->invoice_number }} LUNAS? Internet pelanggan akan otomatis diaktifkan.')" class="w-full sm:w-auto">
                                    @csrf
                                    <div class="flex items-center gap-1.5">
                                        <select name="payment_method" class="flex-1 sm:flex-initial px-2 py-1.5 rounded-lg bg-slate-900 border border-white/10 text-white text-[10px] focus:outline-none focus:border-cyan-400">
                                            <option value="Tunai ke Mitra">Tunai ke Mitra</option>
                                            <option value="SendIt / Transfer Digital">SendIt / Transfer Digital</option>
                                            <option value="Transfer Bank BCA">Transfer Bank BCA</option>
                                            <option value="Transfer Bank BRI">Transfer Bank BRI</option>
                                            <option value="Transfer Bank Mandiri">Transfer Bank Mandiri</option>
                                            <option value="QRIS">QRIS</option>
                                        </select>
                                        <input type="hidden" name="payment_reference" value="TRX-MITRA-{{ date('YmdHis') }}">
                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs shadow-sm transition active:scale-95 whitespace-nowrap">
                                            ✓ Lunas
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Right: Pendaftaran & Pelanggan Terbaru -->
        <div class="lg:col-span-5 glass-panel p-6 rounded-2xl border border-white/10 space-y-4">
            <div class="flex items-center justify-between border-b border-white/10 pb-4">
                <div>
                    <h3 class="text-base font-bold text-white">Pelanggan Terbaru</h3>
                    <span class="text-xs text-slate-400">Pendaftaran online & mitra</span>
                </div>
                <a href="{{ route('portal.customers.index') }}" class="text-xs text-cyan-400 hover:underline">Semua &rarr;</a>
            </div>

            <div class="space-y-3">
                @foreach($recentCustomers as $customer)
                    <a href="{{ route('portal.customers.show', $customer) }}" class="block p-3.5 rounded-xl bg-slate-900/60 hover:bg-slate-900 border border-white/5 transition">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-mono text-xs font-bold text-cyan-300">{{ $customer->customer_code }}</span>
                            @php $cb = $customer->status_badge; @endphp
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $cb['class'] }}">
                                {{ $cb['label'] }}
                            </span>
                        </div>
                        <div class="text-xs font-bold text-white">{{ $customer->name }}</div>
                        <div class="text-[11px] text-slate-400 flex items-center justify-between mt-1">
                            <span>{{ $customer->package->name }}</span>
                            <span>{{ $customer->district }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

    </div>

    <!-- Package Distribution Stats -->
    <div class="glass-panel p-6 rounded-2xl border border-white/10 space-y-4">
        <h3 class="text-base font-bold text-white">Daftar Paket Internet Aktif di Sistem TRICORE</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            @foreach($packages as $pkg)
                <div class="p-4 rounded-xl bg-slate-900/80 border border-white/5 space-y-2 text-center">
                    <span class="text-xs font-bold text-white block">{{ $pkg->name }}</span>
                    <div class="text-2xl font-black text-cyan-400 font-mono">{{ $pkg->speed_mbps }} Mbps</div>
                    <div class="text-xs font-semibold text-emerald-400">{{ $pkg->formatted_price }}/bln</div>
                    <span class="text-[11px] text-slate-400 block pt-1 border-t border-white/5">{{ $pkg->customers_count }} Pelanggan Terdaftar</span>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
