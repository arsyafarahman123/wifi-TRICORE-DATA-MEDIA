@extends('layouts.app')

@section('title', 'Status Tagihan & Layanan - ' . $customer->customer_code . ' | TRICORE DATA MEDIA')

@section('content')
<section class="py-16 sm:py-20 relative">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header / Back button -->
        <div class="flex items-center justify-between flex-wrap gap-2">
            <div class="flex items-center gap-4">
                <a href="{{ route('bill.check') }}" class="text-xs font-semibold text-emerald-400 hover:text-emerald-300 transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Cek Nomor Lain
                </a>
                <span class="text-slate-600">|</span>
                <a href="{{ route('home') }}" class="text-xs font-semibold text-slate-400 hover:text-cyan-400 transition">
                    Ke Beranda
                </a>
            </div>
            <div class="text-xs font-mono text-slate-400">
                Data diperbarui: <span class="text-slate-200">{{ now()->format('d/m/Y H:i') }} WIB</span>
            </div>
        </div>

        <!-- Main Bill & Connection Status Card -->
        <div class="glass-panel p-6 sm:p-8 rounded-2xl border border-white/10 shadow-2xl space-y-6">
            <!-- Top Status Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-white/10">
                <div class="space-y-1">
                    <span class="text-xs text-slate-400">Nomor Pelanggan (Customer ID)</span>
                    <div class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-white font-mono flex items-center gap-2 sm:gap-3 flex-wrap">
                        <span>{{ $customer->customer_code }}</span>
                        @php $badge = $customer->status_badge; @endphp
                        <span class="px-2.5 sm:px-3 py-1 rounded-full text-xs font-bold border {{ $badge['class'] }}">
                            Internet: {{ $badge['label'] }}
                        </span>
                    </div>
                </div>

                <div class="text-left sm:text-right">
                    <span class="text-xs text-slate-400">Paket Terdaftar</span>
                    <div class="text-base sm:text-lg font-bold text-cyan-400">
                        {{ $customer->package->name }} ({{ $customer->package->speed_mbps }} Mbps)
                    </div>
                    <span class="text-xs text-slate-400">{{ $customer->package->formatted_price }} / bulan</span>
                </div>
            </div>

            <!-- Customer Details Grid (Privacy Masking Protected) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 text-xs">
                <div class="p-3.5 rounded-xl bg-slate-900/60 border border-white/5 space-y-1">
                    <span class="text-slate-500 block">Nama Pelanggan</span>
                    @php
                        // Mask customer name (tampilkan nama depan, nama belakang di-mask)
                        $nameParts = explode(' ', trim($customer->name));
                        $maskedName = count($nameParts) > 1 
                            ? $nameParts[0] . ' ' . substr($nameParts[1], 0, 1) . '***' 
                            : substr($customer->name, 0, 3) . '***';
                    @endphp
                    <span class="font-bold text-white text-sm">{{ $maskedName }}</span>
                </div>
                <div class="p-3.5 rounded-xl bg-slate-900/60 border border-white/5 space-y-1">
                    <span class="text-slate-500 block">Nomor WhatsApp</span>
                    @php
                        // Mask phone number (0821****3292)
                        $phoneClean = preg_replace('/[^0-9]/', '', $customer->phone);
                        $maskedPhone = strlen($phoneClean) >= 8 
                            ? substr($phoneClean, 0, 4) . '****' . substr($phoneClean, -4) 
                            : substr($phoneClean, 0, 3) . '***';
                    @endphp
                    <span class="font-bold text-white text-sm font-mono">{{ $maskedPhone }}</span>
                </div>
                <div class="p-3.5 rounded-xl bg-slate-900/60 border border-white/5 space-y-1">
                    <span class="text-slate-500 block">Wilayah Jangkauan</span>
                    <span class="font-bold text-white text-sm">{{ $customer->district }}</span>
                </div>
                <div class="p-3.5 rounded-xl bg-slate-900/60 border border-white/5 space-y-1 sm:col-span-2 md:col-span-3">
                    <span class="text-slate-500 block">Alamat Pemasangan</span>
                    @php
                        // Mask street address for privacy (Jl. KAV ***, Kelurahan/Kecamatan tetap terlihat)
                        $addrParts = explode(',', $customer->address);
                        $maskedAddress = substr($addrParts[0], 0, min(12, strlen($addrParts[0]))) . ' *** (Data Pribadi Terlindungi)';
                    @endphp
                    <span class="font-medium text-slate-200">{{ $maskedAddress }}</span>
                </div>
            </div>

            <!-- Current Invoice Section -->
            @if($latestInvoice)
                <div class="mt-6 pt-6 border-t border-white/10 space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-bold text-white">Rincian Tagihan Bulan Ini</h3>
                            <span class="text-xs text-slate-400">Periode: {{ $latestInvoice->billing_month }}</span>
                        </div>
                        <div>
                            @php $invBadge = $latestInvoice->status_badge; @endphp
                            <span class="px-4 py-1.5 rounded-full text-xs font-extrabold uppercase border {{ $invBadge['class'] }}">
                                Status: {{ $invBadge['label'] }}
                            </span>
                        </div>
                    </div>

                    <!-- Invoice Details Box -->
                    <div class="p-5 rounded-2xl bg-slate-900/90 border border-white/10 space-y-4">
                        @if(isset($hasArrears) && $hasArrears)
                            <div class="p-3.5 rounded-xl bg-amber-500/15 border border-amber-500/30 text-amber-300 text-xs flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    <span>Terdapat <strong>{{ $unpaidInvoices->count() }} tagihan</strong> belum terbayar (termasuk tunggakan periode sebelumnya).</span>
                                </div>
                                <span class="font-mono font-bold text-amber-200">Total: Rp {{ number_format($totalUnpaidAmount, 0, ',', '.') }}</span>
                            </div>
                        @endif

                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <span class="text-xs text-slate-400 block">No. Invoice</span>
                                <span class="font-mono font-bold text-white text-base">{{ $latestInvoice->invoice_number }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-slate-400 block">Batas Jatuh Tempo</span>
                                <span class="font-medium text-amber-300 text-sm">
                                    {{ $latestInvoice->due_date ? $latestInvoice->due_date->format('d F Y') : '-' }}
                                </span>
                            </div>
                            <div class="text-left sm:text-right">
                                <span class="text-xs text-slate-400 block">Total Jumlah Tagihan</span>
                                <span class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-cyan-300 font-mono">
                                    {{ $latestInvoice->formatted_amount }}
                                </span>
                            </div>
                        </div>

                        @if($latestInvoice->status === 'paid')
                            <div class="p-4 rounded-xl bg-emerald-950/60 border border-emerald-500/30 text-emerald-200 text-xs flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <div>
                                        <span class="font-bold block">Tagihan Sudah Lunas</span>
                                        <span>Dibayar pada {{ $latestInvoice->paid_at ? $latestInvoice->paid_at->format('d/m/Y H:i') : '-' }} WIB via {{ $latestInvoice->payment_method ?? 'Mitra' }}</span>
                                    </div>
                                </div>
                                <span class="font-mono text-emerald-400 font-semibold">{{ $latestInvoice->payment_reference ?? 'REF-VALID' }}</span>
                            </div>
                        @else
                            <!-- Payment Instructions if unpaid -->
                            <div class="p-4 rounded-xl bg-rose-950/40 border border-rose-500/30 text-xs space-y-4">
                                <div class="flex items-start gap-2.5 text-rose-200">
                                    <svg class="w-5 h-5 text-rose-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    <div>
                                        <span class="font-bold block text-sm">Menunggu Pembayaran</span>
                                        <span>Segera lakukan pelunasan sebelum tanggal jatuh tempo agar koneksi internet tetap aktif tanpa gangguan isolir.</span>
                                    </div>
                                </div>

                                <!-- Bank Accounts & Mitra Payment -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                                    <div class="p-4 rounded-xl bg-slate-950/90 border border-cyan-500/30 space-y-2">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs font-bold text-amber-400">Rekening Resmi Pembayaran</span>
                                            <span class="px-2 py-0.5 rounded text-[10px] bg-cyan-500/20 text-cyan-300 font-bold">Utama</span>
                                        </div>
                                        <span class="text-slate-400 block text-[11px]">Bank Neo Commerce (BNC)</span>
                                        <div class="font-mono font-extrabold text-white text-lg tracking-wider">5859459401627129</div>
                                        <span class="text-[11px] text-cyan-300 block">a.n. TRICORE DATA MEDIA</span>
                                    </div>
                                    <div class="p-4 rounded-xl bg-slate-950/80 border border-white/5 space-y-2 flex flex-col justify-between">
                                        <div>
                                            <span class="text-slate-400 block text-[11px]">Pembayaran Bank Lain / Mitra</span>
                                            <span class="text-xs text-slate-300 block mt-1">Bisa ditransfer dari semua bank (BCA, Mandiri, BRI, BNI) atau dompet digital (DANA, OVO, Gopay).</span>
                                        </div>
                                        <a href="{{ route('payment.page', ['invoice_number' => $latestInvoice->invoice_number]) }}" 
                                           class="inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-lg bg-gradient-to-r from-cyan-500 to-emerald-500 text-slate-950 font-bold text-xs hover:brightness-110 transition active:scale-95 shadow">
                                            <span>Buka Halaman Pembayaran & Upload Bukti</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        </a>
                                    </div>
                                </div>

                                <!-- Action Buttons: Halaman Bayar & WhatsApp -->
                                @php
                                    $confirmText = urlencode("Halo TRICORE DATA MEDIA, saya ingin konfirmasi pembayaran tagihan WiFi via Bank Neo Commerce:\n\n"
                                        . "• No Pelanggan: {$customer->customer_code}\n"
                                        . "• Nama: {$customer->name}\n"
                                        . "• No Invoice: {$latestInvoice->invoice_number}\n"
                                        . "• Nominal: {$latestInvoice->formatted_amount}\n"
                                        . "• Rekening Tujuan: Bank Neo Commerce 5859459401627129 a.n TRICORE DATA MEDIA\n\n"
                                        . "Berikut saya lampirkan bukti transfer. Mohon diverifikasi. Terima kasih!");
                                    $confirmWaUrl = "https://wa.me/6282138413292?text={$confirmText}";
                                @endphp

                                <div class="pt-2 flex flex-col sm:flex-row gap-3">
                                    <a href="{{ route('payment.page', ['invoice_number' => $latestInvoice->invoice_number]) }}"
                                       class="flex-1 py-3 px-4 rounded-xl bg-gradient-to-r from-cyan-500 to-emerald-500 hover:brightness-110 text-slate-950 font-extrabold text-sm text-center flex items-center justify-center gap-2 shadow-lg shadow-cyan-500/20 transition active:scale-95">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                        <span>Bayar via Bank Neo Commerce / Upload Bukti</span>
                                    </a>
                                    <a href="{{ $confirmWaUrl }}" 
                                       target="_blank" 
                                       class="py-3 px-4 rounded-xl bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 border border-emerald-500/30 font-bold text-sm text-center flex items-center justify-center gap-2 transition">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                        <span>Konfirmasi via WhatsApp</span>
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Invoice History List -->
            @if($customer->invoices->count() > 1)
                <div class="mt-8 pt-6 border-t border-white/10 space-y-4">
                    <h4 class="text-sm font-bold text-white">Riwayat Tagihan Sebelumnya</h4>
                    
                    <!-- Mobile View (Cards) -->
                    <div class="md:hidden space-y-3">
                        @foreach($customer->invoices->skip(1) as $inv)
                            @php $ib = $inv->status_badge; @endphp
                            <div class="p-3.5 rounded-xl bg-slate-900/70 border border-white/5 space-y-2 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="font-mono font-medium text-white">{{ $inv->invoice_number }}</span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $ib['class'] }}">{{ $ib['label'] }}</span>
                                </div>
                                <div class="flex items-center justify-between text-slate-300">
                                    <span>Periode: {{ $inv->billing_month }}</span>
                                    <span class="font-bold font-mono text-emerald-400">{{ $inv->formatted_amount }}</span>
                                </div>
                                <div class="text-[11px] text-slate-400">
                                    Tanggal Bayar: {{ $inv->paid_at ? $inv->paid_at->format('d/m/Y') : '-' }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Desktop View (Table) -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="text-slate-400 border-b border-white/10">
                                <tr>
                                    <th class="py-2.5">No Invoice</th>
                                    <th class="py-2.5">Periode</th>
                                    <th class="py-2.5">Jumlah</th>
                                    <th class="py-2.5">Status</th>
                                    <th class="py-2.5">Tanggal Bayar</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5 text-slate-300">
                                @foreach($customer->invoices->skip(1) as $inv)
                                    <tr>
                                        <td class="py-3 font-mono font-medium text-white">{{ $inv->invoice_number }}</td>
                                        <td class="py-3">{{ $inv->billing_month }}</td>
                                        <td class="py-3 font-bold">{{ $inv->formatted_amount }}</td>
                                        <td class="py-3">
                                            @php $ib = $inv->status_badge; @endphp
                                            <span class="px-2 py-0.5 rounded text-[11px] font-semibold {{ $ib['class'] }}">{{ $ib['label'] }}</span>
                                        </td>
                                        <td class="py-3 text-slate-400">{{ $inv->paid_at ? $inv->paid_at->format('d/m/Y') : '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>

        <!-- Help Notice -->
        <div class="text-center text-xs text-slate-400">
            Ada kendala pembayaran atau butuh bantuan teknis? Hubungi Customer Service TRICORE DATA MEDIA via WhatsApp <a href="https://wa.me/6282138413292" target="_blank" class="text-cyan-400 font-bold hover:underline">+62 821-3841-3292</a>
        </div>

    </div>
</section>
@endsection
