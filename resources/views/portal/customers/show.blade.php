@extends('layouts.portal')

@section('title', 'Detail Pelanggan - ' . $customer->customer_code . ' | Portal Mitra TRICORE')

@section('content')
<div class="space-y-6">
    <!-- Top Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('portal.customers.index') }}" class="text-xs text-slate-400 hover:text-white transition">&larr; Kembali</a>
                <span class="text-slate-600">/</span>
                <span class="text-xs font-mono text-cyan-400 font-bold">{{ $customer->customer_code }}</span>
            </div>
            <h1 class="text-2xl font-extrabold text-white mt-1">{{ $customer->name }}</h1>
        </div>

        <div class="flex items-center gap-2">
            <!-- WA Chat Link -->
            <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $customer->phone)) }}" 
               target="_blank" 
               class="px-3.5 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs flex items-center gap-1.5 transition">
                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                <span>WhatsApp Pelanggan</span>
            </a>

            <a href="{{ route('portal.customers.edit', $customer) }}" class="px-3.5 py-2 rounded-xl bg-slate-800 text-slate-200 hover:bg-slate-700 text-xs font-semibold transition">
                Edit Data
            </a>
        </div>
    </div>

    <!-- Quick Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="glass-panel p-6 rounded-2xl border border-white/10 space-y-4">
            <h3 class="text-sm font-bold text-white border-b border-white/10 pb-2">Informasi Kontak & Pemasangan</h3>
            <div class="space-y-2.5 text-xs">
                <div>
                    <span class="text-slate-500 block">No. WhatsApp / HP:</span>
                    <span class="font-bold text-white text-sm">{{ $customer->phone }}</span>
                </div>
                <div>
                    <span class="text-slate-500 block">Email:</span>
                    <span class="text-slate-300">{{ $customer->email ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-slate-500 block">Alamat Pemasangan:</span>
                    <span class="text-slate-200">{{ $customer->address }}</span>
                </div>
                <div>
                    <span class="text-slate-500 block">Wilayah / Kelurahan:</span>
                    <span class="text-cyan-300 font-semibold">{{ $customer->district }} ({{ $customer->subdistrict ?? '-' }})</span>
                </div>
                <div>
                    <span class="text-slate-500 block">Tiang ODP Fiber:</span>
                    <span class="font-mono text-slate-300">{{ $customer->odp_code ?? 'Belum ditentukan' }}</span>
                </div>
                <div>
                    <span class="text-slate-500 block">Terdaftar Lewat:</span>
                    <span class="text-slate-300 capitalize">{{ $customer->registered_by }}</span>
                </div>
            </div>
        </div>

        <!-- Package & Internet Service Card -->
        <div class="glass-panel p-6 rounded-2xl border border-white/10 space-y-4">
            <h3 class="text-sm font-bold text-white border-b border-white/10 pb-2">Paket Internet & Kecepatan</h3>
            <div class="space-y-3 text-xs">
                <div class="p-3.5 rounded-xl bg-slate-900 border border-white/5 space-y-1">
                    <span class="text-slate-400 block text-[11px]">Nama Paket</span>
                    <span class="text-base font-extrabold text-white">{{ $customer->package->name }}</span>
                    <div class="text-2xl font-black text-cyan-400 font-mono">
                        {{ $customer->package->speed_mbps }} <span class="text-xs font-normal">Mbps Simetris</span>
                    </div>
                    <span class="text-emerald-400 font-bold font-mono">{{ $customer->package->formatted_price }} / bulan</span>
                </div>
                <div>
                    <span class="text-slate-500 block">Tanggal Aktif Instalasi:</span>
                    <span class="text-white font-medium">{{ $customer->installation_date ? $customer->installation_date->format('d F Y') : 'Belum diinstalasi' }}</span>
                </div>
            </div>
        </div>

        <!-- Internet Control Panel (Aktif / Isolir) -->
        <div class="glass-panel p-6 rounded-2xl border border-white/10 space-y-4">
            <h3 class="text-sm font-bold text-white border-b border-white/10 pb-2">Kontrol Status Layanan Internet</h3>
            
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-900 border border-white/5">
                    <span class="text-xs text-slate-400">Status Saat Ini:</span>
                    @php $badge = $customer->status_badge; @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $badge['class'] }}">
                        {{ $badge['label'] }}
                    </span>
                </div>

                <!-- Status Action Buttons -->
                <div class="space-y-2 pt-2 text-xs">
                    @if($customer->status !== 'active')
                        <form action="{{ route('portal.customers.toggle-status', $customer) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="active">
                            <button type="submit" class="w-full py-2.5 px-3 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold transition flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Aktifkan Layanan Internet</span>
                            </button>
                        </form>
                    @endif

                    @if($customer->status !== 'isolated')
                        <form action="{{ route('portal.customers.toggle-status', $customer) }}" method="POST" onsubmit="return confirm('Isolir layanan internet pelanggan ini karena belum bayar?')">
                            @csrf
                            <input type="hidden" name="status" value="isolated">
                            <button type="submit" class="w-full py-2 px-3 rounded-xl bg-rose-950/60 hover:bg-rose-900/80 text-rose-300 border border-rose-500/30 font-semibold transition flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                <span>Isolir Layanan (Tunggakan)</span>
                            </button>
                        </form>
                    @endif

                    @if($customer->status !== 'pending')
                        <form action="{{ route('portal.customers.toggle-status', $customer) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="pending">
                            <button type="submit" class="w-full py-2 px-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-300 border border-white/5 transition text-center">
                                Jadikan Pending Survei
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Invoices & Transaction History for this Customer -->
    <div class="glass-panel p-6 rounded-2xl border border-white/10 space-y-4">
        <div class="flex items-center justify-between border-b border-white/10 pb-4">
            <div>
                <h3 class="text-base font-bold text-white">Riwayat Tagihan & Transaksi Pelanggan</h3>
                <span class="text-xs text-slate-400">Pencatatan pembayaran lunas / belum bayar</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="text-slate-400 border-b border-white/10 bg-slate-950/50">
                    <tr>
                        <th class="py-3 px-4">No. Invoice</th>
                        <th class="py-3 px-4">Periode Bulan</th>
                        <th class="py-3 px-4">Jatuh Tempo</th>
                        <th class="py-3 px-4">Jumlah Tagihan</th>
                        <th class="py-3 px-4">Status Bayar</th>
                        <th class="py-3 px-4">Metode Bayar</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-slate-300">
                    @forelse($customer->invoices as $inv)
                        <tr class="hover:bg-slate-900/50 transition">
                            <td class="py-3 px-4 font-mono font-bold text-white">{{ $inv->invoice_number }}</td>
                            <td class="py-3 px-4">{{ $inv->billing_month }}</td>
                            <td class="py-3 px-4 text-slate-400">{{ $inv->due_date ? $inv->due_date->format('d/m/Y') : '-' }}</td>
                            <td class="py-3 px-4 font-mono font-bold text-white">{{ $inv->formatted_amount }}</td>
                            <td class="py-3 px-4">
                                @php $ib = $inv->status_badge; @endphp
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $ib['class'] }}">
                                    {{ $ib['label'] }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-slate-400">
                                {{ $inv->payment_method ?? 'Belum ada pembayaran' }}
                            </td>
                            <td class="py-3 px-4 text-right whitespace-nowrap space-x-1">
                                <a href="{{ route('portal.invoices.print', $inv) }}" target="_blank" class="px-2.5 py-1.5 rounded-lg bg-slate-800 text-slate-200 hover:bg-slate-700 transition">
                                    Cetak Kuitansi
                                </a>

                                @if($inv->status === 'unpaid')
                                    <form action="{{ route('portal.invoices.pay', $inv) }}" method="POST" class="inline" onsubmit="return confirm('Tandai tagihan {{ $inv->invoice_number }} LUNAS? Layanan internet pelanggan akan otomatis AKTIF.')">
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
                            <td colspan="7" class="py-6 text-center text-slate-500">
                                Belum ada tagihan yang diterbitkan untuk pelanggan ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
