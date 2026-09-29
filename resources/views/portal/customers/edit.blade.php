@extends('layouts.portal')

@section('title', 'Edit Pelanggan - ' . $customer->customer_code . ' | Portal Mitra TRICORE')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-white">Edit Data Pelanggan</h1>
            <p class="text-xs text-slate-400">Kode Pelanggan: <strong class="text-cyan-400 font-mono">{{ $customer->customer_code }}</strong></p>
        </div>
        <a href="{{ route('portal.customers.show', $customer) }}" class="self-start sm:self-auto px-3.5 py-2 rounded-xl bg-slate-800 text-slate-300 text-xs font-semibold hover:bg-slate-700 transition">
            &larr; Batal & Kembali
        </a>
    </div>

    <div class="glass-panel p-5 sm:p-8 rounded-2xl border border-white/10 shadow-2xl">
        <form action="{{ route('portal.customers.update', $customer) }}" method="POST" class="space-y-6 text-xs">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                <!-- Nama -->
                <div>
                    <label for="edit-name" class="block font-semibold text-slate-300 mb-1.5">Nama Lengkap <span class="text-rose-400">*</span></label>
                    <input type="text" id="edit-name" name="name" required value="{{ old('name', $customer->name) }}" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:border-cyan-400 focus:outline-none">
                    @error('name')<span class="text-rose-400 block mt-1">{{ $message }}</span>@enderror
                </div>

                <!-- No WhatsApp -->
                <div>
                    <label for="edit-phone" class="block font-semibold text-slate-300 mb-1.5">Nomor WhatsApp <span class="text-rose-400">*</span></label>
                    <input type="text" id="edit-phone" name="phone" required value="{{ old('phone', $customer->phone) }}" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:border-cyan-400 focus:outline-none">
                    @error('phone')<span class="text-rose-400 block mt-1">{{ $message }}</span>@enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="edit-email" class="block font-semibold text-slate-300 mb-1.5">Email</label>
                    <input type="email" id="edit-email" name="email" value="{{ old('email', $customer->email) }}" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:border-cyan-400 focus:outline-none">
                </div>

                <!-- NIK -->
                <div>
                    <label for="edit-identity" class="block font-semibold text-slate-300 mb-1.5">Nomor KTP / NIK</label>
                    <input type="text" id="edit-identity" name="identity_number" value="{{ old('identity_number', $customer->identity_number) }}" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:border-cyan-400 focus:outline-none">
                </div>

                <!-- Paket WiFi -->
                <div class="sm:col-span-2">
                    <label for="edit-package" class="block font-semibold text-slate-300 mb-1.5">Pilihan Paket WiFi <span class="text-rose-400">*</span></label>
                    <select id="edit-package" name="package_id" required class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:border-cyan-400 focus:outline-none font-bold">
                        @foreach($packages as $pkg)
                            <option value="{{ $pkg->id }}" {{ old('package_id', $customer->package_id) == $pkg->id ? 'selected' : '' }}>
                                {{ $pkg->name }} — {{ $pkg->speed_mbps }} Mbps ({{ $pkg->formatted_price }} / bulan)
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Kecamatan -->
                <div>
                    <label for="edit-district" class="block font-semibold text-slate-300 mb-1.5">Wilayah Kecamatan <span class="text-rose-400">*</span></label>
                    <select id="edit-district" name="district" required class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:border-cyan-400 focus:outline-none">
                        <option value="Purwokerto Timur" {{ $customer->district === 'Purwokerto Timur' ? 'selected' : '' }}>Purwokerto Timur</option>
                        <option value="Purwokerto Wetan" {{ $customer->district === 'Purwokerto Wetan' ? 'selected' : '' }}>Purwokerto Wetan</option>
                        <option value="Sokaraja" {{ $customer->district === 'Sokaraja' ? 'selected' : '' }}>Sokaraja</option>
                        <option value="Purwokerto Barat" {{ $customer->district === 'Purwokerto Barat' ? 'selected' : '' }}>Purwokerto Barat</option>
                        <option value="Purwokerto Utara" {{ $customer->district === 'Purwokerto Utara' ? 'selected' : '' }}>Purwokerto Utara</option>
                        <option value="Purwokerto Selatan" {{ $customer->district === 'Purwokerto Selatan' ? 'selected' : '' }}>Purwokerto Selatan</option>
                    </select>
                </div>

                <!-- Kelurahan -->
                <div>
                    <label for="edit-subdistrict" class="block font-semibold text-slate-300 mb-1.5">Kelurahan / Desa</label>
                    <input type="text" id="edit-subdistrict" name="subdistrict" value="{{ old('subdistrict', $customer->subdistrict) }}" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:border-cyan-400 focus:outline-none">
                </div>

                <!-- Alamat Lengkap -->
                <div class="sm:col-span-2">
                    <label for="edit-address" class="block font-semibold text-slate-300 mb-1.5">Alamat Lengkap Pemasangan <span class="text-rose-400">*</span></label>
                    <textarea id="edit-address" name="address" rows="3" required class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:border-cyan-400 focus:outline-none">{{ old('address', $customer->address) }}</textarea>
                </div>

                <!-- Status Layanan -->
                <div>
                    <label for="edit-status" class="block font-semibold text-slate-300 mb-1.5">Status Layanan <span class="text-rose-400">*</span></label>
                    <select id="edit-status" name="status" required class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:border-cyan-400 focus:outline-none">
                        <option value="active" {{ $customer->status === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="pending" {{ $customer->status === 'pending' ? 'selected' : '' }}>Menunggu Pemasangan</option>
                        <option value="isolated" {{ $customer->status === 'isolated' ? 'selected' : '' }}>Terisolir</option>
                        <option value="cancelled" {{ $customer->status === 'cancelled' ? 'selected' : '' }}>Berhenti Berlangganan</option>
                    </select>
                </div>

                <!-- Tiang ODP -->
                <div>
                    <label for="edit-odp" class="block font-semibold text-slate-300 mb-1.5">Kode ODP Fiber</label>
                    <input type="text" id="edit-odp" name="odp_code" value="{{ old('odp_code', $customer->odp_code) }}" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:border-cyan-400 focus:outline-none">
                </div>

                <!-- Catatan -->
                <div class="sm:col-span-2">
                    <label for="edit-notes" class="block font-semibold text-slate-300 mb-1.5">Catatan Tambahan</label>
                    <input type="text" id="edit-notes" name="notes" value="{{ old('notes', $customer->notes) }}" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:border-cyan-400 focus:outline-none">
                </div>
            </div>

            <div class="pt-4 border-t border-white/10 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                <a href="{{ route('portal.customers.show', $customer) }}" class="w-full sm:w-auto text-center px-5 py-2.5 rounded-xl bg-slate-800 text-slate-300 font-bold hover:bg-slate-700 transition active:scale-95">Batal</a>
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-emerald-500 text-slate-950 font-bold hover:brightness-110 shadow-lg shadow-cyan-500/20 transition active:scale-95">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
