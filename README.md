# ⚡ TRINET-BILL: TRIcore Network Billing — Sistem Manajemen ISP & Billing WiFi Fiber Optic

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.5-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-8-646CFF?style=for-the-badge&logo=vite&logoColor=white)

## 📖 Tentang Proyek

**TRINET-BILL (TRIcore Network Billing)** adalah platform web terintegrasi all-in-one untuk manajemen operasional, monitoring teknis jaringan (ODP & IP), dan sistem penagihan otomatis (*automated billing*) pada **TRICORE DATA MEDIA**, perusahaan penyedia jasa internet fiber optic (ISP) di Purwokerto, Jawa Tengah. Aplikasi ini mencakup:

- 🌐 **Landing Page & Company Profile TRINET-BILL** — Website promosi dan portal publik profesional untuk menjaring pelanggan baru
- 📦 **Manajemen Paket Internet** — Kelola katalog paket WiFi (15–50 Mbps) dengan tarif dinamis
- 👥 **Manajemen Siklus Pelanggan** — Data lengkap pelanggan, status layanan (Aktif/Pending/Terisolir), port ODP, dan alokasi IP
- 💳 **Sistem Billing & Faktur Digital** — Generator tagihan bulanan otomatis, rekonsiliasi pembayaran, dan ekspor invoice PDF
- 🗺️ **Coverage Area Interaktif** — Informasi dan verifikasi wilayah jangkauan kabel fiber optic
- 📱 **Integrasi Notifikasi WhatsApp** — Jembatan notifikasi status tagihan dan pendaftaran langsung ke WhatsApp
- 🔒 **Portal Mitra & Admin TRINET-BILL** — Dashboard terproteksi untuk mitra teknisi dan manajemen
- 🤖 **Virtual Assistant AI Helpdesk** — Chatbot pintar 24 jam untuk panduan teknis dan cek tagihan mandiri

## 🚀 Fitur Utama

### Landing Page (Public)
- Hero section dengan informasi layanan ISP
- Statistik pelanggan aktif, uptime, dan coverage
- Profil perusahaan & keunggulan (fiber optic, unlimited, 24/7)
- Daftar paket internet lengkap dengan tombol "Berlangganan" via WhatsApp
- Coverage area checker & Google Maps Purwokerto
- Form cek tagihan mandiri pelanggan
- Form pendaftaran pelanggan baru online
- Section kontak dengan WhatsApp direct message

### Portal Mitra/Admin (Protected)
- Dashboard statistik (pelanggan, pendapatan, invoice)
- CRUD pelanggan (tambah, edit, lihat detail, hapus)
- Manajemen status pelanggan (aktif/pending/terisolir)
- Generate tagihan bulanan otomatis
- Konfirmasi pembayaran & cetak invoice
- Manajemen paket internet

## 📋 Paket Internet

| Paket | Kecepatan | Harga/Bulan | Keterangan |
|-------|-----------|-------------|------------|
| Hemat | 15 Mbps | Rp115.000 | 1-3 Perangkat |
| Family | 20 Mbps | Rp135.000 | 3-5 Perangkat |
| Favorit | 25 Mbps | Rp150.000 | ⭐ Paling Diminati |
| Turbo | 35 Mbps | Rp170.000 | 8-12 Perangkat |
| Ultimate | 50 Mbps | Rp200.000 | Bisnis & Pro |

*Semua paket: Unlimited, 100% Fiber Optic, Support 24/7, Gratis Modem WiFi*

## 🗺️ Coverage Area

- **Purwokerto Timur** — Arcawinangun, Kranji, Mersi, Purwokerto Lor, Sokanegara
- **Purwokerto Wetan** — Karangpucung, Tanjung, Teluk
- **Sokaraja** — Sokaraja Kidul, Sokaraja Lor, Sokaraja Kulon, Sokaraja Wetan

## ⚙️ Instalasi & Setup

### Prasyarat
- PHP >= 8.2
- Composer
- Node.js & NPM
- SQLite (default) atau MySQL/PostgreSQL

### Langkah Instalasi

```bash
# 1. Clone repositori
git clone https://github.com/arsyafarahman123/wifi-TRICORE-DATA-MEDIA.git
cd wifi-TRICORE-DATA-MEDIA

# 2. Install dependensi PHP
composer install

# 3. Install dependensi JavaScript
npm install

# 4. Salin file environment
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Jalankan migrasi & seeder database
php artisan migrate:fresh --seed

# 7. Build asset frontend
npm run build

# 8. Jalankan development server
php artisan serve
```

Buka **http://localhost:8000** di browser.

### Akun Demo

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@tricoredatamedia.net | password123 |
| Mitra | mitra@tricoredatamedia.net | password123 |

### Nomor Pelanggan Demo
- `TDM-2601` — Rizky Pratama (Aktif, Lunas)
- `TDM-2604` — Dwi Wahyuni (Terisolir, Belum Bayar)
- `TDM-2605` — Hendro Wijaya (Pending)

## ☁️ Panduan Hosting Online (Bebas Iklan & Tanpa InfinityFree)

Proyek ini telah dilengkapi dengan `Dockerfile`, `render.yaml`, dan `nixpacks.toml` sehingga **siap di-deploy langsung dari GitHub** ke platform cloud modern:

### Opsi 1: Deploy di Render.com (Sangat Direkomendasikan ⭐)
1. Buka [render.com](https://render.com) dan login menggunakan akun GitHub (`arsyafarahman123`).
2. Klik tombol **New +** > pilih **Web Service**.
3. Hubungkan repository: `arsyafarahman123/wifi-TRICORE-DATA-MEDIA`.
4. Render akan otomatis mendeteksi `Dockerfile` dan konfigurasi `render.yaml`.
5. Pilih Region: **Singapore** (agar akses dari Indonesia sangat cepat).
6. Di bagian Environment Variables, tambahkan:
   - `APP_KEY`: *(bisa dibuat otomatis atau copy dari artisan key:generate)*
   - `APP_NAME`: `TRICORE DATA MEDIA`
   - `APP_ENV`: `production`
   - `APP_DEBUG`: `false`
7. Klik **Deploy Web Service**. Website akan live dengan domain seperti: `https://tricore-data-media.onrender.com`.

### Opsi 2: Deploy di Koyeb.com
1. Buka [koyeb.com](https://koyeb.com) dan Sign in with GitHub.
2. Klik **Create App** > pilih **GitHub**.
3. Pilih repo `wifi-TRICORE-DATA-MEDIA` branch `main`.
4. Pilih builder **Dockerfile** dan region **Singapore**.
5. Klik **Deploy**. Website akan aktif di `https://<nama-app>.koyeb.app`.

### Opsi 3: Deploy di Railway.app
1. Buka [railway.app](https://railway.app) dan login dengan GitHub.
2. Klik **New Project** > **Deploy from GitHub repo**.
3. Pilih `wifi-TRICORE-DATA-MEDIA`.
4. Railway akan otomatis build dan memberikan domain `*.up.railway.app`.

---

## 🛠️ Tech Stack

- **Backend:** Laravel 12 (PHP 8.5)
- **Frontend:** Blade Templates + Tailwind CSS 4 + Vite 8
- **Database:** SQLite (default), MySQL/PostgreSQL compatible
- **Design:** Dark theme, Glassmorphism, Modern UI
- **Font:** Plus Jakarta Sans (Google Fonts)

## 📞 Kontak

- **WhatsApp:** +62 821-3841-3292
- **Email:** support@tricoredatamedia.net
- **Alamat:** Jl. KAV. Gelora Indah II, Gg. Renang, Purwokerto Timur, Jawa Tengah

## 📄 Lisensi

© 2026 TRICORE DATA MEDIA. All rights reserved.

