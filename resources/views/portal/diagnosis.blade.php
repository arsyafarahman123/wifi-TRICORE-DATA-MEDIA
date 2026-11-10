@extends('layouts.app')

@section('title', 'Sistem Cerdas Diagnosis Gangguan Jaringan (Rule-Based Reasoning) - TRINET-BILL')

@section('content')
<section class="py-10 sm:py-16 relative min-h-[calc(100vh-180px)]">
    <!-- Ambient background glow -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden flex items-center justify-center">
        <div class="w-96 h-96 bg-cyan-500/10 rounded-full blur-3xl -top-10 -left-10"></div>
        <div class="w-96 h-96 bg-purple-500/10 rounded-full blur-3xl -bottom-10 -right-10"></div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-6">
        
        <!-- Navigation Breadcrumb & Back to Home Button -->
        <div class="flex items-center justify-between flex-wrap gap-3 pb-2 border-b border-white/5">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-slate-900/90 hover:bg-slate-800 text-cyan-300 border border-cyan-500/30 text-xs font-bold transition shadow-sm hover:border-cyan-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>&larr; Kembali ke Beranda</span>
            </a>
            <div class="flex items-center gap-2 text-xs text-slate-400">
                <a href="{{ route('home') }}" class="hover:text-cyan-400 transition">Beranda</a>
                <span>/</span>
                <span class="text-cyan-400 font-semibold">Diagnosis Gangguan (RBR)</span>
            </div>
        </div>

        <!-- Header -->
        <div class="text-center space-y-2">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 text-[11px] font-bold">
                <svg class="w-3.5 h-3.5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Expert System • Rule-Based Reasoning (Forward Chaining)
            </div>
            <h1 class="text-xl sm:text-3xl font-extrabold text-white tracking-tight">
                Sistem Cerdas Diagnosis Gangguan Jaringan
            </h1>
            <p class="text-xs text-slate-300 max-w-2xl mx-auto">
                Diagnosis otomatis kendala WiFi dan fiber optik pelanggan TRICORE DATA MEDIA menggunakan penalaran berbasis aturan (Rule-Based Reasoning).
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Column: Interactive Indicator & Symptom Selector Form -->
            <div class="lg:col-span-5 space-y-6">
                <div class="glass-panel p-6 sm:p-8 rounded-3xl border border-white/10 shadow-2xl space-y-6 bg-slate-900/80 backdrop-blur-xl">
                    <div class="flex items-center justify-between pb-3 border-b border-white/10">
                        <span class="text-xs font-bold text-white uppercase tracking-wider flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-cyan-400"></span>
                            Parameter Indikator ONT
                        </span>
                        <span class="text-[11px] text-slate-400 font-mono">Panel Modem</span>
                    </div>

                    <form action="{{ route('diagnosis.analyze') }}" method="POST" class="space-y-5">
                        @csrf

                        <!-- Customer Code Optional -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-slate-300">
                                Nomor ID Pelanggan (Opsional)
                            </label>
                            <input type="text" 
                                   name="customer_code" 
                                   value="{{ $customerCode ?? old('customer_code', '') }}" 
                                   placeholder="Contoh: 89900000001 atau kosongkan" 
                                   class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950/80 border border-white/15 text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400 text-xs font-mono">
                        </div>

                        <!-- LED Status Simulators -->
                        <div class="space-y-3">
                            <label class="block text-xs font-semibold text-slate-300">
                                Kondisi Lampu Indikator Modem / Router
                            </label>

                            <!-- Lampu LOS -->
                            <div class="p-3 rounded-xl bg-slate-950/60 border border-white/5 space-y-1.5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-bold text-slate-200">Lampu LOS (Optical Signal)</span>
                                    <span class="text-[10px] text-slate-400">Sinyal Kabel FO</span>
                                </div>
                                <select name="los_light" class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-white/10 text-xs text-white focus:outline-none focus:border-cyan-400">
                                    <option value="off" {{ ($inputState['los_light'] ?? '') === 'off' ? 'selected' : '' }}>Mati Normal (Kabel Optik Terhubung)</option>
                                    <option value="red_blinking" {{ ($inputState['los_light'] ?? '') === 'red_blinking' ? 'selected' : '' }}>Berkedip Merah (Sinyal Optik Hilang)</option>
                                    <option value="red_solid" {{ ($inputState['los_light'] ?? '') === 'red_solid' ? 'selected' : '' }}>Merah Menyala Solid (Redaman Kritis)</option>
                                </select>
                            </div>

                            <!-- Lampu PON -->
                            <div class="p-3 rounded-xl bg-slate-950/60 border border-white/5 space-y-1.5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-bold text-slate-200">Lampu PON (OLT Sync)</span>
                                    <span class="text-[10px] text-slate-400">Sinkronisasi Pusat</span>
                                </div>
                                <select name="pon_light" class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-white/10 text-xs text-white focus:outline-none focus:border-cyan-400">
                                    <option value="solid_green" {{ ($inputState['pon_light'] ?? '') === 'solid_green' ? 'selected' : '' }}>Hijau Menyala Diam / Solid (Terhubung Normal)</option>
                                    <option value="blinking_green" {{ ($inputState['pon_light'] ?? '') === 'blinking_green' ? 'selected' : '' }}>Hijau Berkedip Cepat (Proses Registrasi Gagal)</option>
                                    <option value="off" {{ ($inputState['pon_light'] ?? '') === 'off' ? 'selected' : '' }}>Mati Total</option>
                                </select>
                            </div>

                            <!-- Lampu Power -->
                            <div class="p-3 rounded-xl bg-slate-950/60 border border-white/5 space-y-1.5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-bold text-slate-200">Lampu POWER (Listrik)</span>
                                    <span class="text-[10px] text-slate-400">Suplai Adaptor</span>
                                </div>
                                <select name="power_light" class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-white/10 text-xs text-white focus:outline-none focus:border-cyan-400">
                                    <option value="on" {{ ($inputState['power_light'] ?? '') === 'on' ? 'selected' : '' }}>Menyala Hijau (Normal)</option>
                                    <option value="off" {{ ($inputState['power_light'] ?? '') === 'off' ? 'selected' : '' }}>Mati / Tidak Ada Lampu (Listrik Mati)</option>
                                </select>
                            </div>

                            <!-- Gejala Gangguan yang Dirasakan -->
                            <div class="p-3 rounded-xl bg-slate-950/60 border border-white/5 space-y-1.5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-bold text-slate-200">Gejala Utama yang Dirasakan</span>
                                    <span class="text-[10px] text-slate-400">Kendala User</span>
                                </div>
                                <select name="issue_type" class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-white/10 text-xs text-white focus:outline-none focus:border-cyan-400">
                                    <option value="general" {{ ($inputState['issue_type'] ?? '') === 'general' ? 'selected' : '' }}>Pemeriksaan Rutin / Umum</option>
                                    <option value="slow" {{ ($inputState['issue_type'] ?? '') === 'slow' ? 'selected' : '' }}>Internet Lambat / Lemot / Kecepatan Drop</option>
                                    <option value="lag" {{ ($inputState['issue_type'] ?? '') === 'lag' ? 'selected' : '' }}>Ping Tinggi / Lag Game Online / RTO</option>
                                    <option value="no_internet" {{ ($inputState['issue_type'] ?? '') === 'no_internet' ? 'selected' : '' }}>Tersambung WiFi tapi "No Internet Access"</option>
                                    <option value="dns" {{ ($inputState['issue_type'] ?? '') === 'dns' ? 'selected' : '' }}>Hanya Bisa WhatsApp, Browsing Website Macet</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-cyan-500 via-teal-500 to-emerald-500 text-slate-950 font-extrabold text-sm hover:brightness-110 shadow-lg shadow-cyan-500/25 transition transform active:scale-95 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            <span>Jalankan Penalaran Aturan (RBR)</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Column: Rule-Based Inference Engine Results -->
            <div class="lg:col-span-7 space-y-6">
                @if(isset($diagnosisResult))
                    <div class="glass-panel p-6 sm:p-8 rounded-3xl border border-white/10 shadow-2xl space-y-6 bg-slate-900/90 backdrop-blur-xl animate-fade-in">
                        
                        <!-- Top Header with Rule Code & Badge -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-white/10">
                            <div>
                                <span class="text-xs text-slate-400 font-mono block">Kode Penalaran Pakar:</span>
                                <span class="px-2.5 py-1 rounded bg-cyan-950 text-cyan-300 border border-cyan-500/30 font-mono text-xs font-bold inline-block mt-1">
                                    {{ $diagnosisResult['rule_id'] }}
                                </span>
                            </div>
                            <div>
                                @php
                                    $severityClass = match($diagnosisResult['severity']) {
                                        'critical' => 'bg-rose-500/20 text-rose-300 border-rose-500/40',
                                        'high' => 'bg-amber-500/20 text-amber-300 border-amber-500/40',
                                        'medium' => 'bg-blue-500/20 text-blue-300 border-blue-500/40',
                                        default => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40'
                                    };
                                @endphp
                                <span class="px-3.5 py-1.5 rounded-full text-xs font-bold border {{ $severityClass }} uppercase tracking-wider inline-block">
                                    {{ $diagnosisResult['badge'] }}
                                </span>
                            </div>
                        </div>

                        <!-- Rule Logic Box -->
                        <div class="p-3.5 rounded-xl bg-slate-950/70 border border-cyan-500/20 text-xs font-mono text-cyan-300">
                            <span class="text-slate-500 block text-[10px] uppercase font-sans font-bold">Aturan (Production Rule) yang Terpicu:</span>
                            {{ $diagnosisResult['rule_name'] }}
                        </div>

                        <!-- Diagnosis Title & Summary -->
                        <div class="space-y-2">
                            <h3 class="text-xl sm:text-2xl font-black text-white">
                                {{ $diagnosisResult['diagnosis_title'] }}
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                                {{ $diagnosisResult['summary'] }}
                            </p>
                        </div>

                        <!-- Root Causes Box -->
                        <div class="space-y-2.5">
                            <span class="text-xs font-bold text-slate-200 uppercase tracking-wider flex items-center gap-2">
                                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                Indikasi Akar Masalah (Root Causes):
                            </span>
                            <ul class="space-y-1.5 text-xs text-slate-300 pl-4 list-disc">
                                @foreach($diagnosisResult['root_causes'] as $cause)
                                    <li>{{ $cause }}</li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Actionable Recommendation Steps -->
                        <div class="space-y-3 pt-2 border-t border-white/5">
                            <span class="text-xs font-bold text-emerald-400 uppercase tracking-wider flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Rekomendasi Solusi & Tindakan Teknis:
                            </span>
                            <div class="space-y-2">
                                @foreach($diagnosisResult['action_steps'] as $idx => $step)
                                    <div class="p-3 rounded-xl bg-slate-950/60 border border-white/5 text-xs text-slate-200 flex items-start gap-2.5">
                                        <span class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-400 font-bold flex items-center justify-center shrink-0 text-[11px] font-mono">
                                            {{ $idx + 1 }}
                                        </span>
                                        <span class="leading-relaxed">{{ $step }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Technician Dispatch / Escalation Action -->
                        @php
                            $waMessage = urlencode("Halo Tim Teknisi TRICORE DATA MEDIA, saya telah melakukan diagnosis cerdas sistem:\n\n"
                                . "• Kode Aturan: {$diagnosisResult['rule_id']}\n"
                                . "• Diagnosis: {$diagnosisResult['diagnosis_title']}\n"
                                . "• Tingkat: {$diagnosisResult['badge']}\n"
                                . ($customer ? "• ID Pelanggan: {$customer->customer_code} ({$customer->name})\n" : "")
                                . "• Mohon bantuan penanganan teknisi. Terima kasih!");
                            $waUrl = "https://wa.me/6282138413292?text={$waMessage}";
                        @endphp

                        <div class="pt-4 border-t border-white/10 flex flex-col sm:flex-row gap-3 items-center justify-between">
                            <div class="text-xs text-slate-400">
                                Estimasi Waktu Penanganan: <span class="text-cyan-300 font-semibold">{{ $diagnosisResult['estimated_fix_time'] }}</span>
                            </div>

                            @if($diagnosisResult['need_technician'])
                                <a href="{{ $waUrl }}" target="_blank" class="w-full sm:w-auto py-2.5 px-5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/25 transition">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                    <span>Buat Tiket Bantuan Teknisi ke WA</span>
                                </a>
                            @else
                                <span class="text-xs text-emerald-400 font-bold flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Dapat Ditangani Mandiri Tanpa Kunjungan
                                </span>
                            @endif
                        </div>

                    </div>
                @else
                    <!-- Placeholder State before submitting -->
                    <div class="glass-panel p-8 sm:p-12 rounded-3xl border border-white/10 shadow-2xl text-center space-y-4 bg-slate-900/60 backdrop-blur-xl">
                        <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br from-cyan-500/20 to-purple-500/20 border border-cyan-500/30 flex items-center justify-center">
                            <svg class="w-8 h-8 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-white">Pilih Kondisi Lampu Modem Anda</h3>
                        <p class="text-xs text-slate-400 max-w-md mx-auto leading-relaxed">
                            Sesuaikan lampu indikator di sebelah kiri sesuai kondisi fisik router WiFi Anda saat ini, lalu klik tombol <strong>Jalankan Penalaran Aturan</strong> untuk melihat diagnosis dan solusi otomatis.
                        </p>
                        <div class="pt-4 flex flex-wrap justify-center gap-2 text-xs">
                            <span class="px-3 py-1 rounded-full bg-slate-800 text-slate-300 border border-white/5">Rule 1: LOS Merah (Fiber Cut)</span>
                            <span class="px-3 py-1 rounded-full bg-slate-800 text-slate-300 border border-white/5">Rule 2: Power Off</span>
                            <span class="px-3 py-1 rounded-full bg-slate-800 text-slate-300 border border-white/5">Rule 3: PON Kedip</span>
                            <span class="px-3 py-1 rounded-full bg-slate-800 text-slate-300 border border-white/5">Rule 4: WiFi Lemot (2.4GHz)</span>
                        </div>
                    </div>
                @endif
            </div>

        </div>

    </div>
</section>
@endsection
