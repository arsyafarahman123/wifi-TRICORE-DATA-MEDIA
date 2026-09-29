# ⚡ TRICORE DATA MEDIA - Sistem Manajemen ISP WiFi Fiber Optic

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.5-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-8-646CFF?style=for-the-badge&logo=vite&logoColor=white)

## 📖 Tentang Proyek

**TRICORE DATA MEDIA** adalah sistem web all-in-one untuk perusahaan penyedia internet fiber optic (ISP) di Purwokerto, Jawa Tengah. Aplikasi ini mencakup:

- 🌐 **Landing Page & Company Profile** — Website promosi profesional untuk menjaring pelanggan baru
- 📦 **Manajemen Paket Internet** — Kelola paket WiFi (15–50 Mbps) dengan harga & fitur
- 👥 **Manajemen Pelanggan** — Data lengkap pelanggan, status layanan, ODP, dan IP
- 💳 **Sistem Billing & Tagihan** — Invoice bulanan, pembayaran, dan riwayat transaksi
- 🗺️ **Coverage Area** — Informasi wilayah jangkauan fiber optic dengan peta interaktif
- 📱 **Integrasi WhatsApp** — Semua interaksi pelanggan terintegrasi dengan WhatsApp bisnis
- 🔒 **Portal Mitra & Admin** — Dashboard terproteksi untuk mitra dan admin

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
