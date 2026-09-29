@extends('layouts.portal')

@section('title', 'Tambah Pelanggan Baru | Portal Mitra TRICORE')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-white">Daftarkan Pelanggan Baru</h1>
            <p class="text-xs text-slate-400">Pendaftaran pelanggan baru oleh Mitra WiFi TRICORE DATA MEDIA</p>
        </div>
        <a href="{{ route('portal.customers.index') }}" class="px-3.5 py-2 rounded-xl bg-slate-800 text-slate-300 text-xs font-semibold hover:bg-slate-700 transition">
            &larr; Kembali
        </a>
    </div>

    <div class="glass-panel p-6 sm:p-8 rounded-2xl border border-white/10 shadow-2xl">
        <form action="{{ route('portal.customers.store') }}" method="POST" class="space-y-6 text-xs">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Nama -->
                <div>
                    <label for="cust-name" class="block font-semibold text-slate-300 mb-1.5">Nama Lengkap Pelanggan <span class="text-rose-400">*</span></label>
                    <input type="text" id="cust-name" name="name" required value="{{ old('name') }}" placeholder="Contoh: Ahmad Fauzi" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:border-cyan-400 focus:outline-none">
                    @error('name')<span class="text-rose-400 block mt-1">{{ $message }}</span>@enderror
                </div>

                <!-- No WhatsApp -->
                <div>
                    <label for="cust-phone" class="block font-semibold text-slate-300 mb-1.5">Nomor WhatsApp Aktif <span class="text-rose-400">*</span></label>
                    <input type="text" id="cust-phone" name="phone" required value="{{ old('phone') }}" placeholder="08..." class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:border-cyan-400 focus:outline-none">
                    @error('phone')<span class="text-rose-400 block mt-1">{{ $message }}</span>@enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="cust-email" class="block font-semibold text-slate-300 mb-1.5">Email (Opsional)</label>
                    <input type="email" id="cust-email" name="email" value="{{ old('email') }}" placeholder="email@gmail.com" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:border-cyan-400 focus:outline-none">
                </div>

                <!-- NIK -->
                <div>
                    <label for="cust-identity" class="block font-semibold text-slate-300 mb-1.5">Nomor KTP / NIK (Opsional)</label>
                    <input type="text" id="cust-identity" name="identity_number" value="{{ old('identity_number') }}" placeholder="16 digit NIK" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:border-cyan-400 focus:outline-none">
                </div>

                <!-- Paket WiFi -->
                <div class="sm:col-span-2">
                    <label for="cust-package" class="block font-semibold text-slate-300 mb-1.5">Pilihan Paket WiFi <span class="text-rose-400">*</span></label>
                    <select id="cust-package" name="package_id" required class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:border-cyan-400 focus:outline-none font-bold">
                        @foreach($packages as $pkg)
                            <option value="{{ $pkg->id }}" {{ $pkg->speed_mbps == 25 ? 'selected' : '' }}>
                                {{ $pkg->name }} — {{ $pkg->speed_mbps }} Mbps ({{ $pkg->formatted_price }} / bulan)
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Kecamatan -->
                <div>
                    <label for="cust-district" class="block font-semibold text-slate-300 mb-1.5">Wilayah Kecamatan <span class="text-rose-400">*</span></label>
                    <select id="cust-district" name="district" required class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:border-cyan-400 focus:outline-none">
                        <option value="Purwokerto Timur">Purwokerto Timur</option>
                        <option value="Purwokerto Wetan">Purwokerto Wetan</option>
                        <option value="Sokaraja">Sokaraja</option>
                        <option value="Purwokerto Barat">Purwokerto Barat</option>
                        <option value="Purwokerto Utara">Purwokerto Utara</option>
                        <option value="Purwokerto Selatan">Purwokerto Selatan</option>
                    </select>
                </div>

                <!-- Kelurahan -->
                <div>
                    <label for="cust-subdistrict" class="block font-semibold text-slate-300 mb-1.5">Kelurahan / Desa</label>
                    <input type="text" id="cust-subdistrict" name="subdistrict" value="{{ old('subdistrict') }}" placeholder="Contoh: Arcawinangun / Sokaraja Lor" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:border-cyan-400 focus:outline-none">
                </div>

                <!-- Alamat Lengkap -->
                <div class="sm:col-span-2">
                    <label for="cust-address" class="block font-semibold text-slate-300 mb-1.5">Alamat Lengkap Pemasangan <span class="text-rose-400">*</span></label>
                    <textarea id="cust-address" name="address" rows="3" required placeholder="Jl. ..., RT ... / RW ..." class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:border-cyan-400 focus:outline-none">{{ old('address') }}</textarea>
                </div>

                <!-- Status Awal -->
                <div>
                    <label for="cust-status" class="block font-semibold text-slate-300 mb-1.5">Status Layanan Awal <span class="text-rose-400">*</span></label>
                    <select id="cust-status" name="status" required class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:border-cyan-400 focus:outline-none">
                        <option value="active">Langsung Aktif (Sudah Terpasang)</option>
                        <option value="pending" selected>Menunggu Pemasangan (Pending Survei)</option>
                        <option value="isolated">Terisolir</option>
                    </select>
                </div>

                <!-- Kode ODP -->
                <div>
                    <label for="cust-odp" class="block font-semibold text-slate-300 mb-1.5">Kode Tiang ODP Fiber (Opsional)</label>
                    <input type="text" id="cust-odp" name="odp_code" value="{{ old('odp_code') }}" placeholder="Contoh: ODP-PKT-024" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:border-cyan-400 focus:outline-none">
                </div>

                <!-- Catatan -->
                <div class="sm:col-span-2">
                    <label for="cust-notes" class="block font-semibold text-slate-300 mb-1.5">Catatan Tambahan</label>
                    <input type="text" id="cust-notes" name="notes" value="{{ old('notes') }}" placeholder="Catatan teknis / koordinat tiang" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:border-cyan-400 focus:outline-none">
                </div>
            </div>

            <div class="pt-4 border-t border-white/10 flex justify-end gap-3">
                <a href="{{ route('portal.customers.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-800 text-slate-300 font-bold hover:bg-slate-700 transition">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-emerald-500 text-slate-950 font-bold hover:brightness-110 shadow-lg shadow-cyan-500/20 transition">
                    Simpan & Daftarkan Pelanggan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
