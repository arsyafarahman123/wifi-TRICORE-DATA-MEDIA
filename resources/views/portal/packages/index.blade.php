@extends('layouts.portal')

@section('title', 'Daftar Paket Internet | Portal Mitra TRICORE')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-white">Daftar Paket Internet WiFi</h1>
            <p class="text-xs text-slate-400">Paket resmi TRICORE DATA MEDIA untuk wilayah Purwokerto dan sekitarnya</p>
        </div>
        <!-- Tombol Tambah Paket -->
        <button type="button" onclick="openAddModal()" class="w-full sm:w-auto justify-center px-4 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-emerald-500 text-slate-950 font-bold text-xs hover:brightness-110 shadow-md shadow-cyan-500/20 transition flex items-center gap-2 active:scale-95">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Tambah Paket Baru</span>
        </button>
    </div>

    <!-- Package Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        @foreach($packages as $pkg)
            <div class="glass-panel p-5 sm:p-6 rounded-2xl border border-white/10 space-y-5 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-mono font-bold text-cyan-400">Paket #{{ $pkg->sort_order }}</span>
                        @if($pkg->badge)
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-cyan-500/20 text-cyan-300 border border-cyan-500/30">
                                {{ $pkg->badge }}
                            </span>
                        @endif
                    </div>

                    <div>
                        <h3 class="text-lg font-bold text-white">{{ $pkg->name }}</h3>
                        <div class="text-3xl font-black text-white font-mono mt-1">
                            {{ $pkg->speed_mbps }} <span class="text-base text-cyan-400 font-sans font-bold">Mbps</span>
                        </div>
                    </div>

                    <div class="text-xl font-bold text-emerald-400 font-mono">
                        {{ $pkg->formatted_price }} <span class="text-xs text-slate-400 font-normal">/ bulan</span>
                    </div>

                    <p class="text-xs text-slate-400 leading-relaxed">{{ $pkg->description }}</p>

                    <div class="p-3 rounded-xl bg-slate-900 border border-white/5 text-xs text-slate-300 flex items-center justify-between">
                        <span>Pelanggan Aktif:</span>
                        <strong class="text-cyan-300 font-mono text-sm">{{ $pkg->customers_count }} Pelanggan</strong>
                    </div>
                </div>

                <div class="pt-4 border-t border-white/10 space-y-2">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-xs font-semibold {{ $pkg->is_active ? 'text-emerald-400' : 'text-slate-500' }}">
                            {{ $pkg->is_active ? '● Aktif Dijual' : '○ Dinonaktifkan' }}
                        </span>

                        <div class="flex items-center gap-1.5">
                            <!-- Edit Button -->
                            <button type="button"
                                onclick="openEditModal({{ $pkg->id }}, '{{ addslashes($pkg->name) }}', {{ $pkg->speed_mbps }}, {{ $pkg->price }}, '{{ addslashes($pkg->badge ?? '') }}', '{{ addslashes($pkg->description ?? '') }}', '{{ addslashes($pkg->device_recommendation ?? '') }}', {{ $pkg->is_popular ? 'true' : 'false' }})"
                                class="px-2.5 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-cyan-300 text-xs font-bold transition active:scale-95 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit
                            </button>

                            <!-- Toggle Active -->
                            <form action="{{ route('portal.packages.toggle', $pkg) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-2.5 py-1.5 rounded-lg text-xs font-bold transition {{ $pkg->is_active ? 'bg-slate-800 text-rose-300 hover:bg-slate-700' : 'bg-emerald-500 text-slate-950 hover:bg-emerald-400' }} active:scale-95">
                                    {{ $pkg->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>

                            <!-- Delete Button -->
                            @if($pkg->customers_count == 0)
                            <form action="{{ route('portal.packages.destroy', $pkg) }}" method="POST" onsubmit="return confirm('Hapus paket {{ addslashes($pkg->name) }}? Tindakan ini tidak bisa dibatalkan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-rose-950/60 hover:bg-rose-900/80 text-rose-400 text-xs font-bold transition active:scale-95">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Empty Card Placeholder when no packages -->
        @if($packages->isEmpty())
            <div class="md:col-span-2 lg:col-span-3 text-center py-16 text-slate-500 text-xs">
                <svg class="w-12 h-12 mx-auto mb-3 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Belum ada paket. Klik "Tambah Paket Baru" untuk menambahkan.
            </div>
        @endif
    </div>
</div>

<!-- ======== MODAL TAMBAH PAKET ======== -->
<div id="add-pkg-modal" class="fixed inset-0 z-[60] flex items-center justify-center p-4 hidden">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeAddModal()"></div>
    <div class="relative w-full max-w-lg bg-[#0d1424] border border-white/10 rounded-2xl shadow-2xl p-6 space-y-5 overflow-y-auto max-h-[90vh]">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-white">Tambah Paket Internet Baru</h2>
            <button type="button" onclick="closeAddModal()" class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form action="{{ route('portal.packages.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block font-semibold text-slate-300 mb-1.5">Nama Paket <span class="text-rose-400">*</span></label>
                    <input type="text" name="name" required placeholder="Contoh: Paket Turbo 25 Mbps" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:border-cyan-400 focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold text-slate-300 mb-1.5">Kecepatan (Mbps) <span class="text-rose-400">*</span></label>
                    <input type="number" name="speed_mbps" required min="1" placeholder="25" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:border-cyan-400 focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold text-slate-300 mb-1.5">Harga / Bulan (Rp) <span class="text-rose-400">*</span></label>
                    <input type="number" name="price" required min="0" placeholder="150000" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:border-cyan-400 focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold text-slate-300 mb-1.5">Label Badge (Opsional)</label>
                    <input type="text" name="badge" placeholder="Contoh: TERLARIS" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:border-cyan-400 focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold text-slate-300 mb-1.5">Rekomendasi Perangkat</label>
                    <input type="text" name="device_recommendation" placeholder="Contoh: 3-5 pengguna" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:border-cyan-400 focus:outline-none">
                </div>
                <div class="sm:col-span-2">
                    <label class="block font-semibold text-slate-300 mb-1.5">Deskripsi Paket</label>
                    <textarea name="description" rows="2" placeholder="Deskripsi singkat paket..." class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:border-cyan-400 focus:outline-none"></textarea>
                </div>
                <div class="sm:col-span-2 flex items-center gap-2">
                    <input type="checkbox" name="is_popular" value="1" id="add-is-popular" class="w-4 h-4 rounded text-cyan-500">
                    <label for="add-is-popular" class="text-slate-300 font-semibold cursor-pointer">Tandai sebagai Paket Populer/Unggulan</label>
                </div>
            </div>

            <div class="pt-4 border-t border-white/10 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                <button type="button" onclick="closeAddModal()" class="w-full sm:w-auto text-center px-5 py-2.5 rounded-xl bg-slate-800 text-slate-300 font-bold hover:bg-slate-700 transition active:scale-95">Batal</button>
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-emerald-500 text-slate-950 font-bold hover:brightness-110 shadow-lg shadow-cyan-500/20 transition active:scale-95">
                    Simpan Paket Baru
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ======== MODAL EDIT PAKET ======== -->
<div id="edit-pkg-modal" class="fixed inset-0 z-[60] flex items-center justify-center p-4 hidden">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeEditModal()"></div>
    <div class="relative w-full max-w-lg bg-[#0d1424] border border-white/10 rounded-2xl shadow-2xl p-6 space-y-5 overflow-y-auto max-h-[90vh]">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-white">Edit Paket Internet</h2>
            <button type="button" onclick="closeEditModal()" class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="edit-pkg-form" action="" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block font-semibold text-slate-300 mb-1.5">Nama Paket <span class="text-rose-400">*</span></label>
                    <input type="text" id="edit-name" name="name" required class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:border-cyan-400 focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold text-slate-300 mb-1.5">Kecepatan (Mbps) <span class="text-rose-400">*</span></label>
                    <input type="number" id="edit-speed" name="speed_mbps" required min="1" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:border-cyan-400 focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold text-slate-300 mb-1.5">Harga / Bulan (Rp) <span class="text-rose-400">*</span></label>
                    <input type="number" id="edit-price" name="price" required min="0" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:border-cyan-400 focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold text-slate-300 mb-1.5">Label Badge</label>
                    <input type="text" id="edit-badge" name="badge" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:border-cyan-400 focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold text-slate-300 mb-1.5">Rekomendasi Perangkat</label>
                    <input type="text" id="edit-device" name="device_recommendation" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:border-cyan-400 focus:outline-none">
                </div>
                <div class="sm:col-span-2">
                    <label class="block font-semibold text-slate-300 mb-1.5">Deskripsi Paket</label>
                    <textarea id="edit-desc" name="description" rows="2" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:border-cyan-400 focus:outline-none"></textarea>
                </div>
                <div class="sm:col-span-2 flex items-center gap-2">
                    <input type="checkbox" name="is_popular" value="1" id="edit-is-popular" class="w-4 h-4 rounded text-cyan-500">
                    <label for="edit-is-popular" class="text-slate-300 font-semibold cursor-pointer">Tandai sebagai Paket Populer/Unggulan</label>
                </div>
            </div>

            <div class="pt-4 border-t border-white/10 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                <button type="button" onclick="closeEditModal()" class="w-full sm:w-auto text-center px-5 py-2.5 rounded-xl bg-slate-800 text-slate-300 font-bold hover:bg-slate-700 transition active:scale-95">Batal</button>
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-emerald-500 text-slate-950 font-bold hover:brightness-110 shadow-lg shadow-cyan-500/20 transition active:scale-95">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const BASE_UPDATE_URL = '{{ url("portal/packages") }}';

    function openAddModal() {
        document.getElementById('add-pkg-modal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeAddModal() {
        document.getElementById('add-pkg-modal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function openEditModal(id, name, speed, price, badge, description, device, isPopular) {
        document.getElementById('edit-pkg-form').action = BASE_UPDATE_URL + '/' + id;
        document.getElementById('edit-name').value = name;
        document.getElementById('edit-speed').value = speed;
        document.getElementById('edit-price').value = price;
        document.getElementById('edit-badge').value = badge;
        document.getElementById('edit-desc').value = description;
        document.getElementById('edit-device').value = device;
        document.getElementById('edit-is-popular').checked = isPopular;
        document.getElementById('edit-pkg-modal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeEditModal() {
        document.getElementById('edit-pkg-modal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeAddModal();
            closeEditModal();
        }
    });
</script>
@endpush
@endsection
