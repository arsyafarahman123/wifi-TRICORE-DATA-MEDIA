<?php

namespace Database\Seeders;

use App\Models\CoverageArea;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Package;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@tricoredatamedia.net'],
            [
                'name' => 'Admin TriCore',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '082138413292',
                'mitra_name' => 'Kantor Pusat Purwokerto',
            ]
        );

        // 2. Create Mitra Partner User
        $mitra = User::firstOrCreate(
            ['email' => 'mitra@tricoredatamedia.net'],
            [
                'name' => 'Budi Santoso (Mitra Sokaraja)',
                'password' => Hash::make('password123'),
                'role' => 'mitra',
                'phone' => '081234567890',
                'mitra_name' => 'Mitra WiFi Sokaraja Indah',
            ]
        );

        // 3. Create Packages according to TRICORE DATA MEDIA Specs
        $packagesData = [
            [
                'name' => 'Paket Hemat 15 Mbps',
                'speed_mbps' => 15,
                'price' => 115000,
                'badge' => null,
                'description' => 'Ideal untuk penggunaan harian ringan, browsing media sosial, chatting WhatsApp, dan sekolah online.',
                'features' => [
                    'Kecepatan hingga 15 Mbps Simetris',
                    '100% Kabel Fiber Optic Murni',
                    'Unlimited Kuota (Tanpa FUP)',
                    'Gratis Sewa Modem Wi-Fi Fiber',
                    'Layanan Bantuan & Support 24/7',
                    'Cocok untuk 1 - 3 Perangkat Sekaligus',
                ],
                'device_recommendation' => '1 - 3 Perangkat',
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Paket Family 20 Mbps',
                'speed_mbps' => 20,
                'price' => 135000,
                'badge' => null,
                'description' => 'Pilihan tepat untuk keluarga kecil dengan aktivitas streaming video HD dan bekerja dari rumah.',
                'features' => [
                    'Kecepatan hingga 20 Mbps Simetris',
                    '100% Kabel Fiber Optic Murni',
                    'Unlimited Kuota (Tanpa FUP)',
                    'Gratis Sewa Modem Wi-Fi Dual Band',
                    'Layanan Bantuan & Support 24/7',
                    'Cocok untuk 3 - 5 Perangkat Sekaligus',
                ],
                'device_recommendation' => '3 - 5 Perangkat',
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Paket Favorit 25 Mbps',
                'speed_mbps' => 25,
                'price' => 150000,
                'badge' => 'Paling Diminati',
                'description' => 'Paket paling banyak dipilih warga Purwokerto! Streaming lancar 4K UHD, game online bebas lag, meeting Zoom lancar.',
                'features' => [
                    'Kecepatan hingga 25 Mbps Simetris',
                    '100% Kabel Fiber Optic Murni',
                    'Unlimited Kuota (Tanpa FUP)',
                    'Gratis Sewa Modem Wi-Fi AC Dual Band Gigabit',
                    'Layanan Bantuan & Support Prioritas 24/7',
                    'Latency Rendah Khusus Gaming',
                    'Cocok untuk 5 - 8 Perangkat',
                ],
                'device_recommendation' => '5 - 8 Perangkat',
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Paket Turbo 35 Mbps',
                'speed_mbps' => 35,
                'price' => 170000,
                'badge' => 'Kecepatan Tinggi',
                'description' => 'Performa kencang untuk rumah besar, konten kreator, live streaming tanpa buffering sedikitpun.',
                'features' => [
                    'Kecepatan hingga 35 Mbps Simetris',
                    '100% Kabel Fiber Optic Murni',
                    'Unlimited Kuota (Tanpa FUP)',
                    'Gratis Modem Wi-Fi Canggih Jangkauan Luas',
                    'Layanan Bantuan & Support 24/7 Siaga',
                    'Optimal untuk Upload Konten & Video HD',
                    'Cocok untuk 8 - 12 Perangkat',
                ],
                'device_recommendation' => '8 - 12 Perangkat',
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Paket Ultimate 50 Mbps',
                'speed_mbps' => 50,
                'price' => 200000,
                'badge' => 'Pro & Bisnis',
                'description' => 'Solusi internet kelas bisnis untuk kantor, cafe, kos-kosan, dan pengguna profesional dengan transfer data intensif.',
                'features' => [
                    'Kecepatan hingga 50 Mbps Simetris Real-Time',
                    '100% Kabel Fiber Optic Murni Dedicated',
                    'Unlimited Kuota (Tanpa FUP)',
                    'Gratis Router High-Performance Multi-User',
                    'SLA Jaminan Uptime 99.5% & Teknisi VIP 24/7',
                    'Sangat Cocok untuk Cafe, Kantor & Kosan (12+ Perangkat)',
                ],
                'device_recommendation' => '12+ Perangkat / Bisnis',
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        $packages = [];
        foreach ($packagesData as $pkg) {
            $packages[$pkg['speed_mbps']] = Package::firstOrCreate(
                ['speed_mbps' => $pkg['speed_mbps']],
                $pkg
            );
        }

        // 4. Create Coverage Areas
        $areasData = [
            [
                'name' => 'Purwokerto Timur',
                'city' => 'Purwokerto',
                'description' => 'Jaringan fiber optic terpasang merata di pusat Purwokerto Timur dengan kapasitas ODP prima.',
                'subdistricts' => ['Arcawinangun', 'Kranji', 'Mersi', 'Purwokerto Lor', 'Sokanegara', 'Kranji Kulon', 'Gelora Indah'],
                'status' => 'Tersedia 100% Fiber Optic',
                'total_odp' => 88,
                'is_active' => true,
            ],
            [
                'name' => 'Purwokerto Wetan',
                'city' => 'Purwokerto',
                'description' => 'Cakupan internet cepat untuk area pemukiman padat dan ruko usaha di sekitar Purwokerto Wetan.',
                'subdistricts' => ['Purwokerto Wetan', 'Karangpucung', 'Tanjung', 'Teluk', 'Mersi Wetan'],
                'status' => 'Tersedia 100% Fiber Optic',
                'total_odp' => 64,
                'is_active' => true,
            ],
            [
                'name' => 'Sokaraja',
                'city' => 'Banyumas / Purwokerto',
                'description' => 'Ekspansi jaringan fiber optik aktif di koridor utama dan perumahan wilayah Sokaraja.',
                'subdistricts' => ['Sokaraja Kidul', 'Sokaraja Lor', 'Sokaraja Kulon', 'Sokaraja Wetan', 'Banjaranyar', 'Karangduren', 'Lemberang'],
                'status' => 'Tersedia 100% Fiber Optic',
                'total_odp' => 52,
                'is_active' => true,
            ],
        ];

        foreach ($areasData as $area) {
            CoverageArea::firstOrCreate(['name' => $area['name']], $area);
        }

        // 5. Seed Realistic Customers
        $customersData = [
            [
                'customer_code' => 'TDM-2601',
                'name' => 'Rizky Pratama',
                'phone' => '082199887766',
                'email' => 'rizky.pratama@gmail.com',
                'identity_number' => '3302101234560001',
                'address' => 'Jl. KAV. Gelora Indah II No. 14, RT 02 / RW 04',
                'district' => 'Purwokerto Timur',
                'subdistrict' => 'Arcawinangun',
                'postal_code' => '53113',
                'package_id' => $packages[25]->id,
                'status' => 'active',
                'installation_date' => '2026-08-01',
                'odp_code' => 'ODP-PKT-012',
                'ip_address' => '10.20.14.55',
                'registered_by' => 'online',
                'partner_id' => $mitra->id,
                'notes' => 'Pemasangan lancar di tiang ODP depan rumah.',
            ],
            [
                'customer_code' => 'TDM-2602',
                'name' => 'Siti Nurhaliza',
                'phone' => '085711223344',
                'email' => 'siti.nurhaliza@yahoo.com',
                'identity_number' => '3302102345670002',
                'address' => 'Gg. Renang No. 8B, RT 01 / RW 03',
                'district' => 'Purwokerto Timur',
                'subdistrict' => 'Mersi',
                'postal_code' => '53112',
                'package_id' => $packages[20]->id,
                'status' => 'active',
                'installation_date' => '2026-08-15',
                'odp_code' => 'ODP-PKT-018',
                'ip_address' => '10.20.14.89',
                'registered_by' => 'mitra',
                'partner_id' => $mitra->id,
                'notes' => 'Paket keluarga 20 Mbps.',
            ],
            [
                'customer_code' => 'TDM-2603',
                'name' => 'Agus Setiawan (Kopi Sokaraja)',
                'phone' => '081344556677',
                'email' => 'agus.kopisokaraja@gmail.com',
                'identity_number' => '3302103456780003',
                'address' => 'Jl. Suparjo Rustam No. 88, Ruko Kopi Sokaraja',
                'district' => 'Sokaraja',
                'subdistrict' => 'Sokaraja Kulon',
                'postal_code' => '53181',
                'package_id' => $packages[50]->id,
                'status' => 'active',
                'installation_date' => '2026-07-20',
                'odp_code' => 'ODP-SKR-005',
                'ip_address' => '10.30.10.12',
                'registered_by' => 'mitra',
                'partner_id' => $mitra->id,
                'notes' => 'Usaha Kafe Kopi Sokaraja (Paket Bisnis 50 Mbps).',
            ],
            [
                'customer_code' => 'TDM-2604',
                'name' => 'Dwi Wahyuni',
                'phone' => '087811992288',
                'email' => 'dwi.wahyuni@gmail.com',
                'identity_number' => '3302104567890004',
                'address' => 'Perumahan Griya Satria Mandalatama Blok C-12',
                'district' => 'Purwokerto Wetan',
                'subdistrict' => 'Purwokerto Wetan',
                'postal_code' => '53111',
                'package_id' => $packages[15]->id,
                'status' => 'isolated',
                'installation_date' => '2026-06-10',
                'odp_code' => 'ODP-PKW-021',
                'ip_address' => '10.25.08.33',
                'registered_by' => 'online',
                'partner_id' => null,
                'notes' => 'Menunggu pelunasan tagihan September 2026.',
            ],
            [
                'customer_code' => 'TDM-2605',
                'name' => 'Hendro Wijaya',
                'phone' => '089622334455',
                'email' => 'hendro.w@gmail.com',
                'identity_number' => '3302105678900005',
                'address' => 'Jl. Jenderal Soedirman Gg. Melati No. 4',
                'district' => 'Purwokerto Timur',
                'subdistrict' => 'Kranji',
                'postal_code' => '53116',
                'package_id' => $packages[35]->id,
                'status' => 'pending',
                'installation_date' => null,
                'odp_code' => 'ODP-PKT-009',
                'ip_address' => null,
                'registered_by' => 'online',
                'partner_id' => null,
                'notes' => 'Pendaftaran baru lewat website, jadwal teknisi survey besok.',
            ],
        ];

        foreach ($customersData as $cData) {
            $customer = Customer::firstOrCreate(
                ['customer_code' => $cData['customer_code']],
                $cData
            );

            // Create initial invoices for demo
            if ($customer->status === 'active') {
                Invoice::firstOrCreate(
                    [
                        'customer_id' => $customer->id,
                        'billing_month' => 'September 2026',
                    ],
                    [
                        'invoice_number' => 'INV-202609-'.str_pad($customer->id, 3, '0', STR_PAD_LEFT),
                        'package_id' => $customer->package_id,
                        'period_start' => '2026-09-01',
                        'period_end' => '2026-09-30',
                        'due_date' => '2026-09-10',
                        'amount' => $customer->package->price,
                        'status' => 'paid',
                        'paid_at' => '2026-09-05 14:30:00',
                        'payment_method' => 'Transfer Bank BCA',
                        'payment_reference' => 'TRF-BCA-98124',
                        'received_by_user_id' => $mitra->id,
                        'notes' => 'Pembayaran via m-BCA lunas.',
                    ]
                );
            } elseif ($customer->status === 'isolated') {
                Invoice::firstOrCreate(
                    [
                        'customer_id' => $customer->id,
                        'billing_month' => 'September 2026',
                    ],
                    [
                        'invoice_number' => 'INV-202609-'.str_pad($customer->id, 3, '0', STR_PAD_LEFT),
                        'package_id' => $customer->package_id,
                        'period_start' => '2026-09-01',
                        'period_end' => '2026-09-30',
                        'due_date' => '2026-09-10',
                        'amount' => $customer->package->price,
                        'status' => 'unpaid',
                        'paid_at' => null,
                        'payment_method' => null,
                        'payment_reference' => null,
                        'received_by_user_id' => null,
                        'notes' => 'Tagihan terlewat jatuh tempo. Layanan terisolir sementara sampai konfirmasi pembayaran.',
                    ]
                );
            } elseif ($customer->status === 'pending') {
                Invoice::firstOrCreate(
                    [
                        'customer_id' => $customer->id,
                        'billing_month' => 'Tagihan Awal Pemasangan (September 2026)',
                    ],
                    [
                        'invoice_number' => 'INV-202609-'.str_pad($customer->id, 3, '0', STR_PAD_LEFT),
                        'package_id' => $customer->package_id,
                        'period_start' => '2026-09-29',
                        'period_end' => '2026-10-28',
                        'due_date' => '2026-10-05',
                        'amount' => $customer->package->price,
                        'status' => 'unpaid',
                        'paid_at' => null,
                        'payment_method' => null,
                        'payment_reference' => null,
                        'received_by_user_id' => null,
                        'notes' => 'Tagihan bulan pertama paket baru.',
                    ]
                );
            }
        }
    }
}
