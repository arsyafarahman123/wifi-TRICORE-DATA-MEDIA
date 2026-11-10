@extends('layouts.app')

@section('title', 'Pembayaran Bank Neo Commerce - ' . $invoice->invoice_number . ' | TRICORE DATA MEDIA')

@section('content')
<section class="py-12 sm:py-20 relative">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Top Breadcrumb / Return Navigation -->
        <div class="flex items-center justify-between text-xs text-slate-400">
            <a href="{{ route('home') }}" class="hover:text-cyan-400 transition flex items-center gap-1.5 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Beranda
            </a>
            <span class="font-mono">ID Pelanggan: <strong class="text-white">{{ $invoice->customer->customer_code }}</strong></span>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-sm flex items-start gap-3">
                <svg class="w-5 h-5 text-emerald-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <div>
                    <strong class="block font-bold">Berhasil!</strong>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-sm flex items-start gap-3">
                <svg class="w-5 h-5 text-rose-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <div>
                    <strong class="block font-bold">Perhatian</strong>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-sm space-y-1">
                <strong class="block font-bold">Terjadi Kesalahan:</strong>
                <ul class="list-disc list-inside text-xs">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Main Invoice & Payment Card -->
        <div class="glass-panel p-6 sm:p-10 rounded-3xl border border-white/10 shadow-2xl space-y-8 relative overflow-hidden">
            <!-- Glow background -->
            <div class="absolute -top-32 -right-32 w-72 h-72 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Header Payment Info -->
            <div class="border-b border-white/10 pb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/10 text-cyan-300 border border-cyan-500/20 text-xs font-semibold mb-2">
                        <span>Tagihan Resmi WiFi TRICORE</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-white">Instruksi Pembayaran</h1>
                    <p class="text-xs text-slate-400 mt-1">Selesaikan transfer bank untuk aktivasi instalasi layanan internet Anda.</p>
                </div>

                <div class="text-left sm:text-right">
                    @php $badge = $invoice->status_badge; @endphp
                    <span class="px-4 py-1.5 rounded-full text-xs font-extrabold uppercase border {{ $badge['class'] }}">
                        Status: {{ $badge['label'] }}
                    </span>
                    <div class="text-[11px] text-slate-400 font-mono mt-1.5">No. Invoice: <strong class="text-white">{{ $invoice->invoice_number }}</strong></div>
                </div>
            </div>

            <!-- Rejection Notice (if rejected) -->
            @if($invoice->status === 'cancelled' && $invoice->rejection_reason)
                <div class="p-5 rounded-2xl bg-rose-950/60 border border-rose-500/40 text-xs space-y-2">
                    <div class="flex items-center gap-2 text-rose-400 font-bold text-sm">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span>Bukti Pembayaran Ditolak Admin</span>
                    </div>
                    <p class="text-rose-200 leading-relaxed">
                        Alasan penolakan: <strong>{{ $invoice->rejection_reason }}</strong>
                    </p>
                    <p class="text-rose-300/80">Silakan periksa kembali transfer Anda dan unggah ulang bukti transfer yang sah dan jelas pada form di bawah ini.</p>
                </div>
            @endif

            <!-- Summary Order Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                <div class="p-4 rounded-2xl bg-slate-900/80 border border-white/5 space-y-1">
                    <span class="text-slate-500 block">Paket Pilihan</span>
                    <strong class="text-white text-sm block">{{ $invoice->package->name }}</strong>
                    <span class="text-cyan-400 font-medium">{{ $invoice->package->speed_mbps }} Mbps Simetris</span>
                </div>
                <div class="p-4 rounded-2xl bg-slate-900/80 border border-white/5 space-y-1">
                    <span class="text-slate-500 block">Nama Pemesan</span>
                    @php
                        // Mask customer name (tampilkan nama depan, nama belakang di-mask)
                        $nameParts = explode(' ', trim($invoice->customer->name));
                        $maskedName = count($nameParts) > 1 
                            ? $nameParts[0] . ' ' . substr($nameParts[1], 0, 1) . '***' 
                            : substr($invoice->customer->name, 0, 3) . '***';
                        
                        // Mask phone number (0821****3292)
                        $phoneClean = preg_replace('/[^0-9]/', '', $invoice->customer->phone);
                        $maskedPhone = strlen($phoneClean) >= 8 
                            ? substr($phoneClean, 0, 4) . '****' . substr($phoneClean, -4) 
                            : substr($phoneClean, 0, 3) . '***';
                    @endphp
                    <strong class="text-white text-sm block">{{ $maskedName }}</strong>
                    <span class="text-slate-400 font-mono">{{ $maskedPhone }}</span>
                </div>
                <div class="p-4 rounded-2xl bg-slate-900/80 border border-white/5 space-y-1">
                    <span class="text-slate-500 block">Batas Waktu Bayar</span>
                    @php
                        $isPayOverdue = $invoice->due_date && now()->startOfDay()->greaterThan($invoice->due_date->startOfDay()) && $invoice->status !== 'paid';
                        $formattedPayDue = $invoice->due_date ? $invoice->due_date->locale('id')->isoFormat('D MMMM Y') : '7 Hari';
                    @endphp
                    <strong class="{{ $isPayOverdue ? 'text-rose-400' : 'text-amber-300' }} text-sm block">
                        {{ $formattedPayDue }}
                        @if($isPayOverdue)
                            <span class="text-[10px] px-1 py-0.5 rounded bg-rose-500/20 text-rose-300 border border-rose-500/30 ml-1">Terlambat</span>
                        @endif
                    </strong>
                    <span class="text-slate-400">Periode: {{ $invoice->billing_month }}</span>
                </div>
            </div>

            <!-- Highlight Payment Amount & Bank Neo Commerce Box -->
            <div class="p-6 sm:p-8 rounded-3xl bg-gradient-to-br from-slate-900 via-slate-900/90 to-cyan-950/40 border-2 border-cyan-500/30 shadow-xl space-y-6">
                <!-- Total Amount with Copy Button -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-white/10">
                    <div>
                        <span class="text-xs uppercase tracking-wider text-slate-400 font-bold block">Total Yang Harus Ditransfer</span>
                        <div class="text-3xl sm:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-cyan-300 to-blue-400 font-mono mt-1">
                            {{ $invoice->formatted_amount }}
                        </div>
                    </div>
                    <button type="button" 
                            onclick="copyText('{{ (int)$invoice->amount }}', 'Nominal Pembayaran')"
                            class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-cyan-300 hover:text-white text-xs font-bold transition flex items-center justify-center gap-2 border border-white/10 active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        <span>Salin Nominal</span>
                    </button>
                </div>

                <!-- Bank Neo Commerce Account Card -->
                <div class="p-5 rounded-2xl bg-slate-950/90 border border-cyan-500/30 space-y-4">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-yellow-400 text-slate-950 font-black flex items-center justify-center text-sm shadow-md">
                                BNC
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-white">{{ $bankInfo['bank_name'] }}</h3>
                                <p class="text-xs text-slate-400">Rekening Resmi Pembayaran WiFi Mitra Tricore</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                            Terverifikasi Mitra
                        </span>
                    </div>

                    <!-- Account Number Big Display -->
                    <div class="p-4 rounded-xl bg-slate-900 border border-white/10 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <span class="text-[11px] text-slate-400 block">Nomor Rekening Tujuan</span>
                            <div id="acc-num" class="font-mono text-2xl sm:text-3xl font-extrabold text-cyan-400 tracking-wider">
                                {{ $bankInfo['account_number'] }}
                            </div>
                            <span class="text-xs text-slate-300 font-medium mt-0.5 block">a.n. <strong class="text-white">{{ $bankInfo['account_holder'] }}</strong></span>
                        </div>
                        <button type="button" 
                                onclick="copyText('{{ $bankInfo['account_number'] }}', 'Nomor Rekening Bank Neo Commerce')"
                                class="px-5 py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-emerald-500 hover:brightness-110 text-slate-950 font-extrabold text-xs shadow-lg shadow-cyan-500/20 transition flex items-center justify-center gap-2 active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            <span>SALIN NOMOR REKENING</span>
                        </button>
                    </div>

                    <!-- Transaction Ref Code with copy -->
                    <div class="flex items-center justify-between text-xs text-slate-400 pt-1">
                        <span>Nomor Referensi Transaksi: <strong class="text-white font-mono">{{ $invoice->invoice_number }}</strong></span>
                        <button type="button" onclick="copyText('{{ $invoice->invoice_number }}', 'Nomor Referensi')" class="text-cyan-400 hover:underline">Salin Ref</button>
                    </div>
                </div>

                <!-- Payment Guidelines -->
                <div class="space-y-2 text-xs text-slate-300">
                    <strong class="text-white font-bold block text-sm">Tata Cara Pembayaran Transfer Bank:</strong>
                    <ol class="list-decimal list-inside space-y-1.5 text-slate-300 leading-relaxed">
                        <li>Buka aplikasi Mobile Banking Anda (BNC Neobank, BCA, Mandiri, BRI, BNI, Dana, GoPay, dsb).</li>
                        <li>Pilih menu <strong>Transfer Antar Bank</strong> dan cari <strong>Bank Neo Commerce (BNC)</strong> / kode bank <strong>490</strong>.</li>
                        <li>Masukkan nomor rekening: <strong class="text-cyan-300 font-mono">5859459401627129</strong>.</li>
                        <li>Masukkan nominal transfer tepat sebesar <strong class="text-emerald-400 font-mono">{{ $invoice->formatted_amount }}</strong>.</li>
                        <li>Pastikan nama penerima adalah <strong>{{ $bankInfo['account_holder'] }}</strong>.</li>
                        <li>Simpan bukti struk / tangkapan layar (screenshot) bukti transfer berhasil.</li>
                        <li>Unggah bukti transfer pada form di bawah ini agar diverifikasi oleh Admin.</li>
                    </ol>
                </div>
            </div>

            <!-- Upload Payment Proof Section -->
            <div class="pt-2 space-y-6">
                <div class="border-b border-white/10 pb-4">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        <span>Konfirmasi & Upload Bukti Pembayaran</span>
                    </h2>
                    <p class="text-xs text-slate-400">Unggah foto atau tangkapan layar bukti transfer Bank Neo Commerce Anda.</p>
                </div>

                @if($invoice->status === 'paid')
                    <div class="p-6 rounded-2xl bg-emerald-950/60 border border-emerald-500/40 text-center space-y-3">
                        <div class="w-12 h-12 mx-auto rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-white">Pembayaran Telah Diverifikasi & Lunas!</h3>
                        <p class="text-xs text-emerald-200 max-w-md mx-auto">
                            Layanan internet Anda telah aktif / dijadwalkan pasang oleh teknisi TRICORE. Terima kasih telah mempercayai kami.
                        </p>
                        @if($invoice->payment_proof)
                            <div class="pt-2">
                                <a href="{{ asset($invoice->payment_proof) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs text-cyan-300 hover:underline">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <span>Lihat Bukti Transfer Tersimpan</span>
                                </a>
                            </div>
                        @endif
                    </div>
                @else
                    @if($invoice->payment_proof)
                        <div class="p-4 rounded-2xl bg-amber-950/40 border border-amber-500/30 text-xs text-amber-200 flex items-start justify-between gap-3">
                            <div>
                                <strong class="block text-amber-300 text-sm font-bold">Bukti Transfer Sedang Ditinjau (Menunggu Verifikasi)</strong>
                                <span>Bukti telah diunggah pada {{ $invoice->proof_uploaded_at ? $invoice->proof_uploaded_at->format('d/m/Y H:i') : '-' }} WIB. Admin / Owner Tricore Data sedang memvalidasi mutasi rekening.</span>
                            </div>
                            <a href="{{ asset($invoice->payment_proof) }}" target="_blank" class="shrink-0 px-3 py-1.5 rounded-lg bg-amber-500/20 text-amber-300 border border-amber-500/30 hover:bg-amber-500/30 font-bold transition">
                                Lihat Bukti
                            </a>
                        </div>
                    @endif

                    <form action="{{ route('payment.upload', ['invoice_number' => $invoice->invoice_number]) }}" 
                          method="POST" 
                          enctype="multipart/form-data" 
                          class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-xs font-bold text-slate-300 mb-1.5">
                                Unggah File Bukti Transfer (JPG, PNG, atau WEBP - Maks. 5MB) <span class="text-rose-400">*</span>
                            </label>
                            <input type="file" 
                                   name="payment_proof" 
                                   required 
                                   accept="image/*"
                                   class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-white/10 text-white text-xs file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-cyan-500 file:text-slate-950 hover:file:bg-cyan-400 cursor-pointer focus:outline-none focus:border-cyan-400">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-300 mb-1.5">
                                    Nomor Referensi Transaksi (Opsional)
                                </label>
                                <input type="text" 
                                       name="payment_reference" 
                                       value="{{ old('payment_reference', $invoice->payment_reference) }}" 
                                       placeholder="Contoh: BNC-12345678 / No Struk" 
                                       class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-xs placeholder-slate-500 focus:outline-none focus:border-cyan-400">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-300 mb-1.5">
                                    Catatan Tambahan (Opsional)
                                </label>
                                <input type="text" 
                                       name="notes" 
                                       value="{{ old('notes') }}" 
                                       placeholder="Misal: Atas nama pengirim Budi..." 
                                       class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-xs placeholder-slate-500 focus:outline-none focus:border-cyan-400">
                            </div>
                        </div>

                        <button type="submit" 
                                class="w-full py-4 rounded-xl bg-gradient-to-r from-cyan-500 via-emerald-500 to-teal-400 hover:brightness-110 text-slate-950 font-extrabold text-sm shadow-xl shadow-cyan-500/20 transition flex items-center justify-center gap-2 active:scale-98">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            <span>{{ $invoice->payment_proof ? 'Kirim Ulang Bukti Pembayaran' : 'Kirim Bukti Pembayaran Sekarang' }}</span>
                        </button>
                    </form>
                @endif
            </div>

            <!-- WhatsApp Direct Confirmation Backup -->
            @php
                $waText = urlencode("Halo TRICORE DATA MEDIA, saya ingin konfirmasi pembayaran WiFi via Bank Neo Commerce:\n\n"
                    . "• No Invoice: {$invoice->invoice_number}\n"
                    . "• No Pelanggan: {$invoice->customer->customer_code}\n"
                    . "• Nama: {$invoice->customer->name}\n"
                    . "• Paket: {$invoice->package->name} ({$invoice->package->speed_mbps} Mbps)\n"
                    . "• Nominal: {$invoice->formatted_amount}\n"
                    . "• Bank Tujuan: Bank Neo Commerce (5859459401627129 a.n TRICORE DATA MEDIA)\n\n"
                    . "Saya telah melakukan transfer. Mohon bantu verifikasi. Terima kasih!");
                $waLink = "https://wa.me/6282138413292?text={$waText}";
            @endphp
            <div class="pt-4 border-t border-white/10 text-center">
                <p class="text-xs text-slate-400 mb-2">Ingin konfirmasi lebih cepat atau ada kendala transfer?</p>
                <a href="{{ $waLink }}" target="_blank" class="inline-flex items-center gap-2 text-xs font-bold text-emerald-400 hover:text-emerald-300 transition">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    <span>Konfirmasi Cepat via WhatsApp CS (+62 821-3841-3292)</span>
                </a>
            </div>

        </div>

    </div>
</section>

<!-- Toast & Copy Helper Script -->
<script>
function copyText(text, label) {
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text).then(() => {
            showToast(label + ' berhasil disalin!');
        }).catch(() => {
            fallbackCopy(text, label);
        });
    } else {
        fallbackCopy(text, label);
    }
}

function fallbackCopy(text, label) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.left = '-9999px';
    document.body.appendChild(textArea);
    textArea.select();
    try {
        document.execCommand('copy');
        showToast(label + ' berhasil disalin!');
    } catch (err) {
        alert('Gagal menyalin. Silakan salin manual: ' + text);
    }
    document.body.removeChild(textArea);
}

function showToast(message) {
    let toast = document.getElementById('tricore-copy-toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'tricore-copy-toast';
        toast.className = 'fixed top-5 right-5 z-50 px-4 py-3 rounded-xl bg-slate-900 border border-cyan-500/40 text-cyan-300 font-bold text-xs shadow-2xl shadow-cyan-950/80 flex items-center gap-2 transform transition-all duration-300 opacity-0 translate-y-[-10px] pointer-events-none';
        toast.innerHTML = '<svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg><span id="toast-text"></span>';
        document.body.appendChild(toast);
    }
    document.getElementById('toast-text').innerText = message;
    toast.classList.remove('opacity-0', 'translate-y-[-10px]', 'pointer-events-none');
    toast.classList.add('opacity-100', 'translate-y-0');

    setTimeout(() => {
        toast.classList.remove('opacity-100', 'translate-y-0');
        toast.classList.add('opacity-0', 'translate-y-[-10px]', 'pointer-events-none');
    }, 2800);
}
</script>
@endsection

