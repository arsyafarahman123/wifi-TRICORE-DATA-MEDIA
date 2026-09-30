<?php

namespace App\Http\Controllers;

use App\Models\CoverageArea;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Package;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    /**
     * Process incoming message or intent from chatbot widget
     */
    public function sendMessage(Request $request): JsonResponse
    {
        $message = trim($request->input('message', ''));
        $intent = $request->input('intent', null);

        // 1. Process explicit intent from button click
        if ($intent) {
            return response()->json($this->handleIntent($intent, $message));
        }

        // 2. If message is empty, return default menu
        if (empty($message)) {
            return response()->json($this->getWelcomeResponse());
        }

        // 3. Detect intent from message text (NLP & Pattern Matching)
        return response()->json($this->analyzeAndRespond($message));
    }

    /**
     * Handle predefined intent triggers
     */
    private function handleIntent(string $intent, string $message = ''): array
    {
        return match ($intent) {
            'menu' => $this->getWelcomeResponse(),
            'check_bill_prompt' => [
                'type' => 'text',
                'text' => "💳 **Cek Tagihan Mandiri**\n\nSilakan ketik **Nomor ID Pelanggan** Anda (contoh: `TDM-2601`) atau **Nomor WhatsApp** yang terdaftar.\n\nContoh ketik: `TDM-2601` atau `082138413292`",
                'quick_replies' => [
                    ['label' => '🔙 Kembali ke Menu', 'intent' => 'menu'],
                    ['label' => '💬 Chat CS WhatsApp', 'intent' => 'contact_cs'],
                ],
            ],
            'packages' => $this->getPackagesResponse(),
            'coverage' => $this->getCoverageResponse(),
            'troubleshoot_menu' => $this->getTroubleshootMenu(),
            'troubleshoot_los' => $this->getTroubleshootLOS(),
            'troubleshoot_pon' => $this->getTroubleshootPON(),
            'troubleshoot_slow' => $this->getTroubleshootSlow(),
            'troubleshoot_ping' => $this->getTroubleshootPing(),
            'troubleshoot_password' => $this->getTroubleshootPassword(),
            'troubleshoot_no_internet' => $this->getTroubleshootNoInternet(),
            'troubleshoot_dns' => $this->getTroubleshootDNS(),
            'troubleshoot_speedtest' => $this->getTroubleshootSpeedtest(),
            'troubleshoot_fup' => $this->getTroubleshootFUP(),
            'troubleshoot_extender' => $this->getTroubleshootExtender(),
            'payment_info' => $this->getPaymentInfoResponse(),
            'register_info' => $this->getRegisterInfoResponse(),
            'contact_cs' => $this->getContactCSResponse(),
            default => $this->analyzeAndRespond($message ?: $intent),
        };
    }

    /**
     * Analyze message text with NLP / regex keyword matching
     */
    private function analyzeAndRespond(string $message): array
    {
        $normalized = strtolower($message);

        // Pattern 1: Check if input is Customer Code (TDM-XXXX) or Indonesian Phone Number (08xxx / 628xxx)
        if (preg_match('/^(tdm-?[0-9a-zA-Z]+)$/i', $message) || preg_match('/^(08[0-9]{8,13}|628[0-9]{8,13})$/', str_replace([' ', '-', '+'], '', $message))) {
            return $this->lookupBill($message);
        }

        // Pattern 2: Greetings
        if (preg_match('/\b(halo|hai|hay|hello|hi|pagi|siang|sore|malam|assalamu[\w]*|assalamualaikum|tes|test|p)\b/', $normalized)) {
            return [
                'type' => 'text',
                'text' => "Halo! 👋 Selamat datang di Layanan Pelanggan **TRICORE DATA MEDIA**.\n\nSaya asisten virtual cerdas yang siap membantu Anda 24/7 seputar tagihan, kendala teknis WiFi, paket, dan jaringan. Ada yang bisa saya bantu hari ini?",
                'quick_replies' => $this->getMainMenuQuickReplies(),
            ];
        }

        // Pattern 3: Cek Tagihan / Billing
        if (preg_match('/\b(tagihan|bayar|iuran|cek tagihan|tunggakan|invoice|rekening wifi|belum bayar|status bayar|biaya bulanan)\b/', $normalized)) {
            if (preg_match('/(tdm-?[0-9a-zA-Z]+)/i', $message, $matches)) {
                return $this->lookupBill($matches[1]);
            }
            if (preg_match('/(08[0-9]{8,13})/', str_replace([' ', '-', '+'], '', $message), $matches)) {
                return $this->lookupBill($matches[1]);
            }

            return [
                'type' => 'text',
                'text' => "💳 Untuk mengecek rincian tagihan dan status koneksi internet Anda, silakan ketik **Nomor ID Pelanggan** (contoh: `TDM-2601`) atau **Nomor WhatsApp** yang terdaftar.",
                'quick_replies' => [
                    ['label' => '💳 Buka Form Cek Tagihan', 'url' => route('home').'#cek-tagihan'],
                    ['label' => '🏦 Info Cara Pembayaran', 'intent' => 'payment_info'],
                    ['label' => '🔙 Menu Utama', 'intent' => 'menu'],
                ],
            ];
        }

        // Pattern 4: FUP / Kuota / Batasan
        if (preg_match('/\b(fup|kuota|unlimited|batasan|apakah ada fup|apakah unlimited|habis kuota)\b/', $normalized)) {
            return $this->getTroubleshootFUP();
        }

        // Pattern 5: Speedtest / Uji Kecepatan
        if (preg_match('/\b(speedtest|cek kecepatan|uji kecepatan|speed test|kecepatan asli|tes speed)\b/', $normalized)) {
            return $this->getTroubleshootSpeedtest();
        }

        // Pattern 6: DNS / IP / Setting Router
        if (preg_match('/\b(dns|ip router|192\.168|gateway|setting router|akses router|ip 192)\b/', $normalized)) {
            return $this->getTroubleshootDNS();
        }

        // Pattern 7: Ping / Game Online / RTO / Lag
        if (preg_match('/\b(ping|rto|lag|game|mobile legends|ml|valorant|pubg|free fire|ff|ping tinggi|patah-patah|packet loss)\b/', $normalized)) {
            return $this->getTroubleshootPing();
        }

        // Pattern 8: Lampu LOS Merah
        if (preg_match('/\b(los|lampu merah|merah kedip|los merah|kedip merah|merah terus)\b/', $normalized)) {
            return $this->getTroubleshootLOS();
        }

        // Pattern 9: Lampu PON
        if (preg_match('/\b(pon|lampu pon|pon kedip|pon mati)\b/', $normalized)) {
            return $this->getTroubleshootPON();
        }

        // Pattern 10: Ganti Password / Sandi WiFi / SSID
        if (preg_match('/\b(ganti password|ganti sandi|password wifi|ubah sandi|lupa password|nama wifi|ssid|ubah nama wifi)\b/', $normalized)) {
            return $this->getTroubleshootPassword();
        }

        // Pattern 11: Extender / Tambah Router / Jangkauan Luas
        if (preg_match('/\b(extender|mesh|repeater|router tambahan|tambah wifi|lantai 2|jangkauan wifi|sinyal lemah di kamar)\b/', $normalized)) {
            return $this->getTroubleshootExtender();
        }

        // Pattern 12: WiFi Terhubung tapi Tidak Ada Internet
        if (preg_match('/\b(tidak ada internet|no internet|tanda seru|terhubung tanpa internet|connected without internet|gak ada koneksi)\b/', $normalized)) {
            return $this->getTroubleshootNoInternet();
        }

        // Pattern 13: Internet Lemot / Lambat / Gangguan Umum
        if (preg_match('/\b(lemot|lambat|gangguan|rusak|mati|putus|down|trouble|error|lelet|kecepatan turun|putus nyambung)\b/', $normalized)) {
            return $this->getTroubleshootSlow();
        }

        // Pattern 14: Paket Internet, Harga, Kecepatan, Promo
        if (preg_match('/\b(paket|harga|biaya|kecepatan|speed|mbps|tarif|promo|pricelist|langganan berapa|murah|15 mbps|25 mbps|50 mbps)\b/', $normalized)) {
            return $this->getPackagesResponse();
        }

        // Pattern 15: Jangkauan Area / Coverage
        if (preg_match('/\b(coverage|jangkauan|area|lokasi|daerah|wilayah|purwokerto|sokaraja|kranji|mersi|bisa pasang|tersedia|banyumas)\b/', $normalized)) {
            return $this->getCoverageResponse();
        }

        // Pattern 16: Cara Bayar / Rekening / Transfer
        if (preg_match('/\b(cara bayar|transfer|rekening|bca|bri|mandiri|qris|metode bayar|nomor rekening|virtual account)\b/', $normalized)) {
            return $this->getPaymentInfoResponse();
        }

        // Pattern 17: Pasang Baru / Daftar
        if (preg_match('/\b(daftar|pasang|pasang baru|registrasi|pasang wifi|instalasi|mau langganan|syarat pasang|biaya pasang)\b/', $normalized)) {
            return $this->getRegisterInfoResponse();
        }

        // Pattern 18: Kontak CS / Admin / WhatsApp
        if (preg_match('/\b(admin|cs|customer service|wa|whatsapp|manusia|operator|telepon|hubungi|call center|teknisi)\b/', $normalized)) {
            return $this->getContactCSResponse();
        }

        // Default Guardrail Fallback Response (Anti-Hallucination)
        return [
            'type' => 'text',
            'text' => "Saya adalah asisten virtual resmi **TRICORE DATA MEDIA**.\n\nPertanyaan Anda *\"".e($message)."\"* belum ada dalam database panduan standar kami. Untuk menghindari kesalahan informasi, silakan pilih topik bantuan di bawah atau langsung konsultasikan dengan CS Teknisi kami:",
            'quick_replies' => $this->getMainMenuQuickReplies(),
        ];
    }

    /**
     * Lookup customer bill from database
     */
    private function lookupBill(string $query): array
    {
        $cleanQuery = trim($query);
        $cleanPhone = str_replace([' ', '-', '+'], '', $cleanQuery);

        $customer = Customer::with(['package', 'invoices' => function ($q) {
            $q->latest();
        }])
            ->where('customer_code', 'LIKE', $cleanQuery)
            ->orWhere('phone', $cleanQuery)
            ->orWhere('phone', $cleanPhone)
            ->first();

        if (! $customer) {
            return [
                'type' => 'text',
                'text' => "❌ Data pelanggan untuk **\"".e($cleanQuery)."\"** tidak ditemukan di sistem database kami.\n\n**Tips:**\n• Pastikan ID Pelanggan sesuai format (contoh: `TDM-2601`).\n• Atau gunakan nomor WhatsApp aktif yang didaftarkan saat instalasi.",
                'quick_replies' => [
                    ['label' => '🔁 Coba Masukkan Ulang', 'intent' => 'check_bill_prompt'],
                    ['label' => '💬 Konfirmasi ke CS WhatsApp', 'intent' => 'contact_cs'],
                    ['label' => '🔙 Menu Utama', 'intent' => 'menu'],
                ],
            ];
        }

        $latestInvoice = $customer->invoices->first();
        $isPaid = $latestInvoice && $latestInvoice->status === 'paid';
        $statusBadge = match ($customer->status) {
            'active' => '🟢 Aktif',
            'isolated' => '🔴 Terisolir',
            'pending' => '🟡 Menunggu Aktivasi',
            'inactive' => '⚪ Non-Aktif',
            default => $customer->status,
        };

        $invStatusText = $latestInvoice ? match ($latestInvoice->status) {
            'paid' => '✅ LUNAS',
            'unpaid' => '⏳ BELUM DIBAYAR',
            'overdue' => '⚠️ JATUH TEMPO',
            default => strtoupper($latestInvoice->status),
        } : 'Belum Ada Invoice';

        $billText = "📋 **Informasi Pelanggan & Tagihan**\n\n";
        $billText .= "👤 **Nama:** {$customer->name}\n";
        $billText .= "🆔 **ID Pelanggan:** `{$customer->customer_code}`\n";
        $billText .= "📦 **Paket:** {$customer->package?->name} ({$customer->package?->speed_mbps} Mbps)\n";
        $billText .= "📶 **Status Koneksi:** {$statusBadge}\n";
        $billText .= "📍 **Alamat:** {$customer->district}, {$customer->address}\n\n";

        if ($latestInvoice) {
            $billText .= "─── **Tagihan Terakhir** ───\n";
            $billText .= "🧾 **No. Invoice:** `{$latestInvoice->invoice_number}`\n";
            $billText .= "📅 **Periode:** {$latestInvoice->billing_month}\n";
            $billText .= "💰 **Total Tagihan:** **{$latestInvoice->formatted_amount}**\n";
            $billText .= "📌 **Status Pembayaran:** **{$invStatusText}**\n";
            if ($latestInvoice->due_date) {
                $billText .= "⏰ **Jatuh Tempo:** {$latestInvoice->due_date->format('d M Y')}\n";
            }
        }

        $quickReplies = [];
        if ($latestInvoice && ! $isPaid) {
            $quickReplies[] = ['label' => '💳 Cara Pembayaran', 'intent' => 'payment_info'];
            $quickReplies[] = ['label' => '🧾 Lihat Detail Invoice', 'url' => route('bill.view', ['code' => $customer->customer_code])];
        } else {
            $quickReplies[] = ['label' => '🧾 Cetak / Unduh Invoice', 'url' => route('bill.view', ['code' => $customer->customer_code])];
        }

        $quickReplies[] = ['label' => '💬 Konfirmasi Pembayaran ke CS', 'intent' => 'contact_cs'];
        $quickReplies[] = ['label' => '🔙 Menu Utama', 'intent' => 'menu'];

        return [
            'type' => 'card',
            'text' => $billText,
            'quick_replies' => $quickReplies,
        ];
    }

    /**
     * Response for welcome / greeting
     */
    private function getWelcomeResponse(): array
    {
        return [
            'type' => 'text',
            'text' => "Halo! 👋 Selamat datang di Layanan Bantuan **TRICORE DATA MEDIA**.\n\nSaya asisten virtual cerdas yang siap melayani Anda 24/7. Silakan pilih menu di bawah atau ketik langsung kendala Anda:",
            'quick_replies' => $this->getMainMenuQuickReplies(),
        ];
    }

    /**
     * Response with packages list
     */
    private function getPackagesResponse(): array
    {
        $packages = Package::where('is_active', true)->orderBy('price', 'asc')->get();

        $text = "⚡ **Daftar Paket Internet Fiber Optic TRICORE**\n";
        $text .= "_Semua paket: 100% Fiber Optic, Murni Tanpa FUP, & Gratis Modem WiFi:_\n\n";

        foreach ($packages as $pkg) {
            $star = $pkg->is_popular ? ' ⭐ *(Paling Diminati)*' : '';
            $badge = $pkg->badge ? " [{$pkg->badge}]" : '';
            $price = 'Rp'.number_format($pkg->price, 0, ',', '.');
            $text .= "• **{$pkg->name} {$pkg->speed_mbps} Mbps**{$star}{$badge}\n";
            $text .= "  💰 **{$price}**/bulan\n";
            if ($pkg->device_recommendation) {
                $text .= "  📱 Ideal untuk: {$pkg->device_recommendation}\n";
            }
            $text .= "\n";
        }

        return [
            'type' => 'text',
            'text' => $text,
            'quick_replies' => [
                ['label' => '📝 Daftar Pasang Baru', 'url' => route('home').'#daftar'],
                ['label' => '📍 Cek Coverage Area', 'intent' => 'coverage'],
                ['label' => '💬 Konsultasi via WhatsApp', 'intent' => 'contact_cs'],
                ['label' => '🔙 Menu Utama', 'intent' => 'menu'],
            ],
        ];
    }

    /**
     * Response for coverage areas
     */
    private function getCoverageResponse(): array
    {
        $coverages = CoverageArea::where('is_active', true)->get();

        $text = "🗺️ **Wilayah Jangkauan (Coverage Area Fiber Optic)**\n\n";
        $text .= "Jaringan kabel Fiber Optic **TRICORE DATA MEDIA** saat ini mencakup wilayah Purwokerto dan sekitarnya:\n\n";

        if ($coverages->isNotEmpty()) {
            foreach ($coverages as $area) {
                $subdistricts = is_array($area->subdistricts) ? implode(', ', $area->subdistricts) : $area->subdistricts;
                $text .= "📍 **{$area->district_name}**\n";
                $text .= "   Kelurahan/Desa: {$subdistricts}\n\n";
            }
        } else {
            $text .= "📍 **Purwokerto Timur:** Arcawinangun, Kranji, Mersi, Purwokerto Lor, Sokanegara\n";
            $text .= "📍 **Purwokerto Wetan:** Karangpucung, Tanjung, Teluk\n";
            $text .= "📍 **Sokaraja:** Sokaraja Kidul, Sokaraja Lor, Sokaraja Kulon, Sokaraja Wetan\n\n";
        }

        $text .= "Ingin memastikan jarak tiang ODP ke rumah Anda?";

        return [
            'type' => 'text',
            'text' => $text,
            'quick_replies' => [
                ['label' => '📍 Cek Peta di Web', 'url' => route('home').'#coverage'],
                ['label' => '📝 Daftar Pasang Baru', 'url' => route('home').'#daftar'],
                ['label' => '💬 Cek Titik via WhatsApp', 'intent' => 'contact_cs'],
                ['label' => '🔙 Menu Utama', 'intent' => 'menu'],
            ],
        ];
    }

    /**
     * Troubleshooting menu
     */
    private function getTroubleshootMenu(): array
    {
        return [
            'type' => 'text',
            'text' => "🛠️ **Pusat Kendala & Troubleshooting Teknis WiFi**\n\nSilakan pilih kendala yang sedang Anda alami untuk solusi mandiri langkah demi langkah:",
            'quick_replies' => [
                ['label' => '🔴 Lampu LOS Kedip Merah', 'intent' => 'troubleshoot_los'],
                ['label' => '🟢 Lampu PON Kedip Hijau', 'intent' => 'troubleshoot_pon'],
                ['label' => '🐢 Internet Lemot / Lambat', 'intent' => 'troubleshoot_slow'],
                ['label' => '🎮 Ping Tinggi / Game RTO', 'intent' => 'troubleshoot_ping'],
                ['label' => '⚠️ Tersambung Tanpa Internet', 'intent' => 'troubleshoot_no_internet'],
                ['label' => '🔑 Cara Ganti Sandi WiFi', 'intent' => 'troubleshoot_password'],
                ['label' => '🌐 Pengaturan DNS Cepat', 'intent' => 'troubleshoot_dns'],
                ['label' => '📶 WiFi Extender / Tambah Jangkauan', 'intent' => 'troubleshoot_extender'],
                ['label' => '💬 Panggil Bantuan Teknisi WA', 'intent' => 'contact_cs'],
                ['label' => '🔙 Menu Utama', 'intent' => 'menu'],
            ],
        ];
    }

    /**
     * Troubleshooting: LOS Red Light
     */
    private function getTroubleshootLOS(): array
    {
        $text = "🔴 **Panduan Penanganan Lampu LOS Berkedip Merah**\n\n";
        $text .= "Lampu LOS merah menandakan modem (ONU/ONT) tidak mendeteksi sinyal optik dari kabel fiber optic.\n\n";
        $text .= "**Langkah Perbaikan Mandiri:**\n";
        $text .= "1. Periksa kabel fiber hitam tipis (dengan kepala konektor biru/hijau) di bagian belakang/bawah modem.\n";
        $text .= "2. Pastikan kabel tidak tertekuk tajam (minimal radius tekukan selebar kaleng minuman), tidak terjepit pintu, dan tidak rusak.\n";
        $text .= "3. Matikan modem dengan mencabut adaptor listrik selama **15 detik**, lalu pasang kembali (Power Cycle).\n";
        $text .= "4. Tunggu 2-3 menit hingga lampu PON kembali menyala hijau stabil.\n\n";
        $text .= "⚠️ **Jika tetap berkedip merah:** Kemungkinan besar terjadi kabel putus di tiang ODP luar rumah (misal terkena ranting pohon/kendaraan). Tim teknisi kami akan segera meluncur ke lokasi!";

        return [
            'type' => 'text',
            'text' => $text,
            'quick_replies' => [
                ['label' => '💬 Buat Tiket Lapor ke CS WA', 'intent' => 'contact_cs'],
                ['label' => '🔙 Menu Kendala Lain', 'intent' => 'troubleshoot_menu'],
                ['label' => '🔙 Menu Utama', 'intent' => 'menu'],
            ],
        ];
    }

    /**
     * Troubleshooting: PON Light Blinking
     */
    private function getTroubleshootPON(): array
    {
        $text = "🟢 **Panduan Lampu PON Berkedip Hijau**\n\n";
        $text .= "Lampu PON yang berkedip hijau menandakan modem sedang berusaha menghubungkan dan mengotentikasi diri ke server pusat (OLT TRICORE).\n\n";
        $text .= "**Langkah Penanganan:**\n";
        $text .= "1. Tunggu 3–5 menit saat modem baru saja dinyalakan.\n";
        $text .= "2. Jika lebih dari 10 menit masih berkedip (tidak mau menyala diam), silakan restart modem 1 kali.\n";
        $text .= "3. Jika tetap berkedip, kemungkinan ada proses maintenance server di wilayah Anda atau perlu reset profil konfigurasi.";

        return [
            'type' => 'text',
            'text' => $text,
            'quick_replies' => [
                ['label' => '💬 Cek Status Server ke CS', 'intent' => 'contact_cs'],
                ['label' => '🔙 Menu Kendala', 'intent' => 'troubleshoot_menu'],
            ],
        ];
    }

    /**
     * Troubleshooting: Slow Internet
     */
    private function getTroubleshootSlow(): array
    {
        $text = "🐢 **Panduan Optimasi Internet Lambat / Lemot**\n\n";
        $text .= "**Langkah Perbaikan:**\n";
        $text .= "1. **Restart Modem:** Cabut kabel power modem selama 10 detik lalu colokkan kembali untuk menyegarkan cache memori router.\n";
        $text .= "2. **Cek Perangkat Terhubung:** Pastikan tidak ada HP/laptop lain yang sedang mengunduh game, streaming 4K, atau update Windows secara bersamaan.\n";
        $text .= "3. **Pindah ke Frekuensi 5 GHz:** Jika modem Anda dual-band, sambungkan ke nama WiFi berakhiran `_5G` untuk kecepatan maksimal tanpa gangguan frekuensi.\n";
        $text .= "4. **Cek Jarak Sinyal:** Penghalang tembok tebal dapat menurunkan sinyal. Pastikan Anda berada dalam jarak optimal dari router.\n";
        $text .= "5. **Cek Status Tagihan:** Pastikan pembayaran tagihan tidak dalam masa isolir.";

        return [
            'type' => 'text',
            'text' => $text,
            'quick_replies' => [
                ['label' => '🚀 Cara Cek Speedtest', 'intent' => 'troubleshoot_speedtest'],
                ['label' => '💳 Cek Status Tagihan', 'intent' => 'check_bill_prompt'],
                ['label' => '💬 Hubungi CS', 'intent' => 'contact_cs'],
                ['label' => '🔙 Menu Kendala', 'intent' => 'troubleshoot_menu'],
            ],
        ];
    }

    /**
     * Troubleshooting: High Ping & Gaming Lag
     */
    private function getTroubleshootPing(): array
    {
        $text = "🎮 **Panduan Mengatasi Ping Tinggi / RTO / Game Lag**\n\n";
        $text .= "**Tips Khusus Gamer (MLBB, Valorant, PUBG, FF):**\n";
        $text .= "1. **Gunakan Kabel LAN:** Sambungkan PC/Laptop langsung menggunakan kabel LAN RJ-45 untuk latensi paling stabil (0% jitter).\n";
        $text .= "2. **Gunakan WiFi 5 GHz:** Jika menggunakan HP, hindari WiFi 2.4 GHz karena sering terganggu frekuensi Bluetooth dan microwave.\n";
        $text .= "3. **Ganti DNS ke Cloudflare:** Ubah DNS HP ke `1.1.1.1` & `1.0.0.1` untuk rute game terpendek.\n";
        $text .= "4. **Matikan Auto-Sync Cloud:** Nonaktifkan sementara backup Google Photos / iCloud di perangkat keluarga saat bermain game.\n";
        $text .= "5. **Restart Modem:** Membersihkan routing table OLT.";

        return [
            'type' => 'text',
            'text' => $text,
            'quick_replies' => [
                ['label' => '🌐 Cara Ganti DNS', 'intent' => 'troubleshoot_dns'],
                ['label' => '💬 Bantuan CS / Routing Game', 'intent' => 'contact_cs'],
                ['label' => '🔙 Menu Kendala', 'intent' => 'troubleshoot_menu'],
            ],
        ];
    }

    /**
     * Troubleshooting: Connected without internet
     */
    private function getTroubleshootNoInternet(): array
    {
        $text = "⚠️ **Panduan Tersambung WiFi tapi 'No Internet Access'**\n\n";
        $text .= "1. **Forget Network:** Di pengaturan WiFi HP/Laptop Anda, pilih *Lupakan Jaringan (Forget)* lalu sambungkan dan ketik sandi kembali.\n";
        $text .= "2. **Restart Modem:** Cabut listrik modem selama 15 detik lalu colokkan kembali.\n";
        $text .= "3. **Periksa Lampu Modem:**\n";
        $text .= "   • Lampu **PON** harus berwarna **HIJAU** (diam/solid).\n";
        $text .= "   • Lampu **INTERNET / WAN** harus menyala hijau berkedip cepat.\n";
        $text .= "   • Lampu **LOS** tidak boleh menyala merah.\n";
        $text .= "4. **Cek Status Tagihan:** Pastikan layanan tidak terisolir otomatis oleh sistem karena melewati tanggal jatuh tempo.";

        return [
            'type' => 'text',
            'text' => $text,
            'quick_replies' => [
                ['label' => '💳 Cek Tagihan Saya', 'intent' => 'check_bill_prompt'],
                ['label' => '💬 Bantuan CS WhatsApp', 'intent' => 'contact_cs'],
                ['label' => '🔙 Menu Kendala', 'intent' => 'troubleshoot_menu'],
            ],
        ];
    }

    /**
     * Troubleshooting: Change WiFi Password & SSID
     */
    private function getTroubleshootPassword(): array
    {
        $text = "🔑 **Cara Mengganti Nama (SSID) & Password WiFi Sendiri**\n\n";
        $text .= "1. Hubungkan HP/Laptop ke jaringan WiFi TRICORE Anda.\n";
        $text .= "2. Buka browser (Chrome/Safari) dan ketik IP Router: `http://192.168.1.1` atau `http://192.168.100.1`.\n";
        $text .= "3. Masukkan Username & Password admin router (tertera pada stiker di bagian bawah fisik modem).\n";
        $text .= "4. Masuk ke menu **Network** ➔ **WLAN / Wireless (2.4G / 5G)** ➔ **Security**.\n";
        $text .= "5. Ganti kolom **SSID Name** (Nama WiFi) atau **WPA Passphrase / Key** (Sandi minimal 8 karakter).\n";
        $text .= "6. Klik **Apply / Save**, lalu sambungkan ulang HP Anda dengan sandi baru.\n\n";
        $text .= "_Catatan: Jika lupa password login modem, tim CS kami dapat mengganti sandi dari jauh tanpa Anda perlu ribet setting router!_";

        return [
            'type' => 'text',
            'text' => $text,
            'quick_replies' => [
                ['label' => '💬 Minta CS Gantikan Password', 'intent' => 'contact_cs'],
                ['label' => '🔙 Menu Utama', 'intent' => 'menu'],
            ],
        ];
    }

    /**
     * Troubleshooting: DNS Settings
     */
    private function getTroubleshootDNS(): array
    {
        $text = "🌐 **Panduan Setting DNS Cepat & Stabil**\n\n";
        $text .= "Menggunakan DNS publik yang andal dapat mempercepat loading website dan mengurangi ping game:\n\n";
        $text .= "**Pilihan DNS Terbaik:**\n";
        $text .= "• **Cloudflare DNS (Paling Cepat & Privasi):**\n";
        $text .= "  - Primary: `1.1.1.1`\n";
        $text .= "  - Secondary: `1.0.0.1`\n\n";
        $text .= "• **Google Public DNS (Paling Stabil):**\n";
        $text .= "  - Primary: `8.8.8.8`\n";
        $text .= "  - Secondary: `8.8.4.4`\n\n";
        $text .= "**Cara Pasang di HP Android / iPhone:**\n";
        $text .= "Masuk ke Pengaturan HP ➔ Koneksi / Jaringan ➔ Private DNS ➔ Masukkan: `one.one.one.one` atau `dns.google`.";

        return [
            'type' => 'text',
            'text' => $text,
            'quick_replies' => [
                ['label' => '🎮 Tips Ping Game', 'intent' => 'troubleshoot_ping'],
                ['label' => '🔙 Menu Kendala', 'intent' => 'troubleshoot_menu'],
            ],
        ];
    }

    /**
     * Troubleshooting: Speedtest Official
     */
    private function getTroubleshootSpeedtest(): array
    {
        $text = "🚀 **Panduan Uji Kecepatan (Speedtest) yang Akurat**\n\n";
        $text .= "Agar hasil tes kecepatan akurat dan sesuai paket:\n\n";
        $text .= "1. Matikan sementara streaming video, download, dan VPN di semua perangkat lain.\n";
        $text .= "2. Buka situs resmi: **[Speedtest by Ookla](https://www.speedtest.net)**.\n";
        $text .= "3. Pilih server terdekat (Purwokerto / Semarang / Jakarta).\n";
        $text .= "4. Untuk hasil 100% akurat, gunakan kabel LAN ke laptop atau sambungkan ke WiFi 5 GHz tepat di samping modem.\n\n";
        $text .= "Jika hasil speedtest di bawah 70% dari kapasitas paket langganan Anda, segera infokan ke CS kami untuk dicek redaman optik tiang ODP.";

        return [
            'type' => 'text',
            'text' => $text,
            'quick_replies' => [
                ['label' => '📦 Cek Kapasitas Paket', 'intent' => 'packages'],
                ['label' => '💬 Laporkan Hasil Speedtest ke CS', 'intent' => 'contact_cs'],
                ['label' => '🔙 Menu Kendala', 'intent' => 'troubleshoot_menu'],
            ],
        ];
    }

    /**
     * Policy: FUP / Unlimited
     */
    private function getTroubleshootFUP(): array
    {
        $text = "✨ **Kebijakan Kuota & FUP TRICORE DATA MEDIA**\n\n";
        $text .= "Semua paket internet **TRICORE DATA MEDIA** bersifat **100% UNLIMITED MURNI TANPA FUP (Fair Usage Policy)**:\n\n";
        $text .= "• ❌ Tidak ada penurunan kecepatan di tengah bulan.\n";
        $text .= "• ❌ Tidak ada batasan gigabyte (GB).\n";
        $text .= "• ✅ Bebas unduh, nonton streaming 4K, CCTV, dan gaming sepuasnya 24 jam non-stop dengan kecepatan penuh sesuai paket langganan Anda!";

        return [
            'type' => 'text',
            'text' => $text,
            'quick_replies' => [
                ['label' => '⚡ Lihat Pilihan Paket', 'intent' => 'packages'],
                ['label' => '📝 Daftar Pasang Baru', 'url' => route('home').'#daftar'],
                ['label' => '🔙 Menu Utama', 'intent' => 'menu'],
            ],
        ];
    }

    /**
     * Troubleshooting: WiFi Extender / Mesh
     */
    private function getTroubleshootExtender(): array
    {
        $text = "📶 **Panduan Memperluas Sinyal WiFi (Rumah Bertingkat / Luas)**\n\n";
        $text .= "Jika sinyal WiFi tidak menjangkau kamar lantai 2 atau halaman belakang:\n\n";
        $text .= "1. **Pasang Router Tambahan / Access Point:** Solusi terbaik menggunakan kabel LAN dari modem utama ke router kedua di lantai 2 (kecepatan 100% utuh).\n";
        $text .= "2. **WiFi Extender / Repeater:** Solusi praktis tanpa kabel, dicolok di titik tengah antara modem dan area sinyal lemah.\n";
        $text .= "3. **Mesh WiFi System:** Memberikan 1 nama WiFi (SSID) yang sama di seluruh rumah dengan perpindahan koneksi otomatis (*seamless roaming*).\n\n";
        $text .= "Ingin bantuan tim teknisi kami untuk penarikan kabel LAN atau pemasangan router tambahan di rumah Anda?";

        return [
            'type' => 'text',
            'text' => $text,
            'quick_replies' => [
                ['label' => '💬 Konsultasi Pasang Router Tambahan', 'intent' => 'contact_cs'],
                ['label' => '🔙 Menu Kendala', 'intent' => 'troubleshoot_menu'],
            ],
        ];
    }

    /**
     * Payment Information
     */
    private function getPaymentInfoResponse(): array
    {
        $text = "💳 **Informasi Rekening & Cara Pembayaran Tagihan**\n\n";
        $text .= "Pembayaran tagihan bulanan TRICORE DATA MEDIA dapat dilakukan melalui:\n\n";
        $text .= "🏦 **Transfer Bank:**\n";
        $text .= "• **Bank BCA:** `046-1234-567` (a.n TRICORE DATA MEDIA)\n";
        $text .= "• **Bank BRI:** `0022-01-001234-53-1` (a.n TRICORE DATA MEDIA)\n\n";
        $text .= "📲 **QRIS & E-Wallet:**\n";
        $text .= "• Mendukung semua aplikasi perbankan & e-wallet (GoPay, OVO, DANA, ShopeePay, LinkAja, BCA Mobile, Livin).\n\n";
        $text .= "⏰ **Jatuh Tempo:** Tanggal 20 setiap bulannya.\n\n";
        $text .= "⚠️ **Catatan Penting:**\n";
        $text .= "Sertakan **ID Pelanggan (contoh: TDM-2601)** pada berita transfer, lalu kirim bukti transfer ke CS WhatsApp kami.";

        return [
            'type' => 'text',
            'text' => $text,
            'quick_replies' => [
                ['label' => '💳 Cek Tagihan Saya', 'intent' => 'check_bill_prompt'],
                ['label' => '💬 Kirim Bukti Transfer ke WA', 'intent' => 'contact_cs'],
                ['label' => '🔙 Menu Utama', 'intent' => 'menu'],
            ],
        ];
    }

    /**
     * Registration Information
     */
    private function getRegisterInfoResponse(): array
    {
        return [
            'type' => 'text',
            'text' => "📝 **Pendaftaran Pelanggan Baru (Pasang WiFi Fiber Optic)**\n\nProses pendaftaran sangat mudah dan cepat:\n\n1. Pilih paket internet yang sesuai (15 - 50 Mbps).\n2. Isi formulir pendaftaran online di website atau langsung chat ke WhatsApp kami.\n3. Tim teknisi akan survei tiang ODP dan melakukan instalasi kabel fiber optic ke rumah Anda.\n4. Pembayaran dilakukan setelah instalasi selesai dan internet aktif dites di rumah Anda!\n\n✨ **Keuntungan:** Gratis biaya sewa modem WiFi & Unlimited tanpa batasan kuota (No FUP)!",
            'quick_replies' => [
                ['label' => '📝 Buka Formulir Pendaftaran', 'url' => route('home').'#daftar'],
                ['label' => '📦 Lihat Pilihan Paket', 'intent' => 'packages'],
                ['label' => '💬 Daftar Cepat via WhatsApp', 'intent' => 'contact_cs'],
                ['label' => '🔙 Menu Utama', 'intent' => 'menu'],
            ],
        ];
    }

    /**
     * WhatsApp CS Handover
     */
    private function getContactCSResponse(): array
    {
        $waUrl = 'https://wa.me/6282138413292?text='.urlencode('Halo CS TRICORE DATA MEDIA, saya ingin menanyakan layanan internet WiFi...');

        return [
            'type' => 'text',
            'text' => "📞 **Hubungi Tim Customer Service & Teknisi**\n\nTim kami siap melayani Anda setiap hari:\n\n• 📱 **WhatsApp CS:** +62 821-3841-3292\n• 📧 **Email:** support@tricoredatamedia.net\n• 🏢 **Kantor:** Jl. KAV. Gelora Indah II, Gg. Renang, Purwokerto Timur\n• ⏰ **Jam Layanan:** 08.00 - 21.00 WIB (Support Darurat Jaringan 24/7)\n\nSilakan klik tombol di bawah untuk langsung terhubung dengan WhatsApp CS:",
            'quick_replies' => [
                ['label' => '💬 Buka WhatsApp CS Sekarang', 'url' => $waUrl],
                ['label' => '🔙 Menu Utama', 'intent' => 'menu'],
            ],
        ];
    }

    /**
     * Main Menu Quick Replies
     */
    private function getMainMenuQuickReplies(): array
    {
        return [
            ['label' => '💳 Cek Tagihan', 'intent' => 'check_bill_prompt'],
            ['label' => '⚡ Paket & Kecepatan', 'intent' => 'packages'],
            ['label' => '🛠️ Bantuan Gangguan WiFi', 'intent' => 'troubleshoot_menu'],
            ['label' => '🎮 Tips Ping Game & RTO', 'intent' => 'troubleshoot_ping'],
            ['label' => '🔑 Cara Ganti Sandi WiFi', 'intent' => 'troubleshoot_password'],
            ['label' => '📍 Coverage Area', 'intent' => 'coverage'],
            ['label' => '📝 Daftar Pasang Baru', 'intent' => 'register_info'],
            ['label' => '💬 Hubungi CS WhatsApp', 'intent' => 'contact_cs'],
        ];
    }
}
