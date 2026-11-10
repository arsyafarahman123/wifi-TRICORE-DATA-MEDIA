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
        // Handle dynamic recommendation intents
        if (str_starts_with($intent, 'recom_')) {
            return $this->handleRecommendationIntent($intent, $message);
        }

        return match ($intent) {
            'menu' => $this->getWelcomeResponse(),
            'check_bill_prompt' => [
                'type' => 'text',
                'text' => "**Pengecekan Tagihan Pelanggan TRICORE**\n\nSilakan ketik **Nomor ID Pelanggan** 11 digit Anda (contoh: `89900000001`) atau nomor WhatsApp yang terdaftar untuk memeriksa tagihan dan status koneksi secara otomatis.",
                'quick_replies' => [
                    ['label' => 'Buka Form Cek Tagihan', 'url' => route('bill.check')],
                    ['label' => 'Menu Utama', 'intent' => 'menu'],
                ],
            ],
            'diagnosis_menu' => $this->getDiagnosisMenuResponse(),
            'diagnosis_los' => $this->getDiagnosisRuleResponse('RBR-01'),
            'diagnosis_power' => $this->getDiagnosisRuleResponse('RBR-02'),
            'diagnosis_pon' => $this->getDiagnosisRuleResponse('RBR-03'),
            'diagnosis_slow' => $this->getDiagnosisRuleResponse('RBR-05'),
            'diagnosis_lag' => $this->getDiagnosisRuleResponse('RBR-06'),
            'diagnosis_dns' => $this->getDiagnosisRuleResponse('RBR-07'),
            'recom_start' => $this->getRecommendationStepBudget(),
            'recom_budget' => $this->getRecommendationStepBudget(),
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

        // Pattern 1: Check if input is Customer Code (Prefix 899 11 digit atau TDM-XXXX) or Phone Number (08xxx / 628xxx)
        if (preg_match('/^(899[0-9]{8}|tdm-?[0-9a-zA-Z]+)$/i', $message) || preg_match('/^(08[0-9]{8,13}|628[0-9]{8,13})$/', str_replace([' ', '-', '+'], '', $message))) {
            return $this->lookupBill($message);
        }

        // Pattern 2: Greetings
        if (preg_match('/\b(halo|hai|hay|hello|hi|pagi|siang|sore|malam|assalamu[\w]*|assalamualaikum|tes|test|p)\b/', $normalized)) {
            return [
                'type' => 'text',
                'text' => "Halo! Selamat datang di WiFi Tricore Data. Saya asisten virtual yang siap membantu menemukan paket internet yang sesuai dengan kebutuhan dan anggaran Anda. Mari kita cari paket yang paling cocok!\n\nAnda dapat memulai konsultasi rekomendasi cerdas atau memilih layanan kami di bawah:",
                'quick_replies' => $this->getMainMenuQuickReplies(),
            ];
        }

        // Pattern: Kendala Koneksi / Internet Lemot / Lambat / Putus / Mati / Gangguan
        if (preg_match('/\b(lemot|lambat|lelet|kecepatan turun|putus|putus nyambung|down|mati total|mati lampu|bisa wa tapi web macet|tidak bisa browsing|wifi lelet|koneksi lemot|internet lemot)\b/', $normalized)) {
            return $this->getTroubleshootSlow();
        }

        // Pattern: Lampu LOS Merah
        if (preg_match('/\b(los|lampu merah|merah kedip|los merah|kedip merah|merah terus)\b/', $normalized)) {
            return $this->getTroubleshootLOS();
        }

        // Pattern: Lampu PON
        if (preg_match('/\b(pon|lampu pon|pon kedip|pon mati)\b/', $normalized)) {
            return $this->getTroubleshootPON();
        }

        // Pattern: WiFi Terhubung tapi Tidak Ada Internet
        if (preg_match('/\b(tidak ada internet|no internet|tanda seru|terhubung tanpa internet|connected without internet|gak ada koneksi)\b/', $normalized)) {
            return $this->getTroubleshootNoInternet();
        }

        // Pattern: Ping / Game Online / RTO / Lag
        if (preg_match('/\b(ping|rto|lag|game|mobile legends|ml|valorant|pubg|free fire|ff|ping tinggi|patah-patah|packet loss)\b/', $normalized)) {
            return $this->getTroubleshootPing();
        }

        // Pattern: Rekomendasi Paket Internet (Rule-Based)
        if (preg_match('/\b(rekomendasi|konsultasi|pilih paket|pilihan paket|cocok|saran paket|bingung pilih|paket apa|cari paket|daftar paket|lihat paket|paket wifi|paket internet)\b/', $normalized)) {
            return $this->getPackagesResponse();
        }

        // Pattern 3: Cek Tagihan / Billing
        if (preg_match('/\b(tagihan|bayar|iuran|cek tagihan|tunggakan|invoice|rekening wifi|belum bayar|status bayar|biaya bulanan)\b/', $normalized)) {
            if (preg_match('/(899[0-9]{8}|tdm-?[0-9a-zA-Z]+)/i', $message, $matches)) {
                return $this->lookupBill($matches[1]);
            }
            if (preg_match('/(08[0-9]{8,13})/', str_replace([' ', '-', '+'], '', $message), $matches)) {
                return $this->lookupBill($matches[1]);
            }

            return $this->handleIntent('check_bill_prompt');
        }

        // Pattern 4: Sistem Cerdas Diagnosis Gangguan Jaringan (Rule-Based Reasoning)
        if (preg_match('/\b(diagnosis|rusak|mati|gangguan|kendala|troubleshoot|rbr|cek kerusakan|kenapa wifi|analisis gangguan)\b/', $normalized)) {
            return $this->getDiagnosisMenuResponse();
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

        // Default Guardrail Fallback Response
        return [
            'type' => 'text',
            'text' => "Terima kasih telah menghubungi **Pusat Layanan TRINET-BILL** (TRICORE DATA MEDIA).\n\nPertanyaan terkait *\"".e($message)."\"* dapat langsung dikonsultasikan dengan teknisi kami, atau silakan pilih topik bantuan cepat berikut:",
            'quick_replies' => $this->getMainMenuQuickReplies(),
        ];
    }

    /**
     * Lookup customer bill from database
     */
    private function lookupBill(string $query): array
    {
        $cleanQuery = trim($query);
        $upperQuery = strtoupper($cleanQuery);
        $cleanPhone = preg_replace('/[^0-9]/', '', $cleanQuery);

        $customer = Customer::with(['package', 'invoices' => function ($q) {
            $q->latest();
        }])
            ->where('customer_code', $cleanQuery)
            ->orWhere('customer_code', $upperQuery)
            ->orWhere('phone', $cleanQuery)
            ->when(!empty($cleanPhone), function ($q) use ($cleanPhone) {
                $q->orWhere('phone', $cleanPhone);
            })
            ->first();

        if (! $customer) {
            return [
                'type' => 'text',
                'text' => "Data pelanggan untuk **\"".e($cleanQuery)."\"** tidak ditemukan di database TRINET-BILL.\n\n**Tips:**\n• Pastikan Nomor Pelanggan 11 digit sesuai (contoh: `89900000001`)\n• Atau gunakan nomor WhatsApp yang didaftarkan saat pasang.",
                'quick_replies' => [
                    ['label' => 'Buka Form Cek Tagihan', 'url' => route('bill.check')],
                    ['label' => 'Bantuan CS WhatsApp', 'intent' => 'contact_cs'],
                    ['label' => 'Menu Utama', 'intent' => 'menu'],
                ],
            ];
        }

        $latestInvoice = $customer->invoices->first();
        $isPaid = $latestInvoice && $latestInvoice->status === 'paid';
        $statusBadge = match ($customer->status) {
            'active' => 'Aktif (Normal)',
            'isolated' => 'Terisolir (Pending Bayar)',
            'pending' => 'Menunggu Aktivasi',
            'inactive' => 'Non-Aktif',
            default => $customer->status,
        };

        $invStatusText = $latestInvoice ? match ($latestInvoice->status) {
            'paid' => 'LUNAS',
            'unpaid' => 'BELUM DIBAYAR',
            'overdue' => 'JATUH TEMPO',
            default => strtoupper($latestInvoice->status),
        } : 'Belum Ada Invoice';

        $billText = "📊 **Rincian Tagihan Resmi TRINET-BILL**\n\n";
        $billText .= "• **Nama Pelanggan:** **{$customer->name}**\n";
        $billText .= "• **ID Pelanggan:** `{$customer->customer_code}`\n";
        $billText .= "• **Paket Internet:** {$customer->package?->name} ({$customer->package?->speed_mbps} Mbps)\n";
        $billText .= "• **Status Koneksi:** [ {$statusBadge} ]\n";
        $billText .= "• **Wilayah:** {$customer->district}\n\n";

        if ($latestInvoice) {
            $billText .= "💳 **Status Tagihan Bulan Berjalan:**\n";
            $billText .= "• **No. Invoice:** `{$latestInvoice->invoice_number}`\n";
            $billText .= "• **Periode:** {$latestInvoice->billing_month}\n";
            $billText .= "• **Total Tagihan:** **{$latestInvoice->formatted_amount}**\n";
            $billText .= "• **Status Pembayaran:** **[ {$invStatusText} ]**\n";
            if ($latestInvoice->due_date) {
                $billText .= "• **Jatuh Tempo:** {$latestInvoice->due_date->locale('id')->isoFormat('D MMMM Y')}\n";
            }
            if ($isPaid && $latestInvoice->paid_at) {
                $billText .= "• **Lunas Dibayar:** {$latestInvoice->paid_at->locale('id')->isoFormat('D MMMM Y, HH:mm')} WIB\n";
            }
        }

        $quickReplies = [];
        if ($latestInvoice && ! $isPaid) {
            $confirmText = urlencode("Halo TRICORE, saya ingin konfirmasi pembayaran tagihan WiFi:\n• ID: {$customer->customer_code}\n• Nama: {$customer->name}\n• Invoice: {$latestInvoice->invoice_number}\n• Total: {$latestInvoice->formatted_amount}");
            $quickReplies[] = ['label' => 'Konfirmasi via WhatsApp', 'url' => "https://wa.me/6282138413292?text={$confirmText}"];
            $quickReplies[] = ['label' => 'Rekening Pembayaran', 'intent' => 'payment_info'];
            $quickReplies[] = ['label' => 'Buka Halaman Tagihan', 'url' => route('bill.view', ['code' => $customer->customer_code])];
        } else {
            $quickReplies[] = ['label' => 'Lihat Bukti Lunas', 'url' => route('bill.view', ['code' => $customer->customer_code])];
        }

        $quickReplies[] = ['label' => 'Cek Nomor Lain', 'intent' => 'check_bill_prompt'];
        $quickReplies[] = ['label' => 'Diagnosis Gangguan (RBR)', 'intent' => 'diagnosis_menu'];
        $quickReplies[] = ['label' => 'Menu Utama', 'intent' => 'menu'];

        return [
            'type' => 'card',
            'text' => $billText,
            'quick_replies' => $quickReplies,
        ];
    }

    /**
     * Response with packages list & instant rule-based recommendation
     */
    private function getPackagesResponse(): array
    {
        $packages = Package::where('is_active', true)->orderBy('price', 'asc')->get();

        if ($packages->isEmpty()) {
            return [
                'type' => 'text',
                'text' => "Mohon maaf, saat ini data paket internet belum tersedia di sistem kami.",
                'quick_replies' => [
                    ['label' => 'Menu Utama', 'intent' => 'menu'],
                ],
            ];
        }

        // Rule-Based Selection for Primary Recommendation:
        // Prioritas 1: Paket yang ditandai is_popular (Paket Favorit 25 Mbps)
        // Prioritas 2: Paket kecepatan 25 Mbps
        // Prioritas 3: Paket median dari daftar aktif
        $bestPkg = $packages->firstWhere('is_popular', true)
            ?: ($packages->firstWhere('speed_mbps', 25)
            ?: $packages->get(floor($packages->count() / 2)));

        // Alternatif paket (paket lainnya selain paket utama)
        $alternatives = $packages->reject(fn ($p) => $p->id === $bestPkg->id)->values();

        $priceFormatted = $bestPkg->formatted_price ?: ('Rp' . number_format($bestPkg->price, 0, ',', '.'));

        $text = "Rekomendasi Paket Terbaik untuk Anda!\n\n"
            . "Berdasarkan paket internet yang tersedia, kami merekomendasikan:\n\n"
            . "**{$bestPkg->name}**" . ($bestPkg->badge ? " [{$bestPkg->badge}]" : "") . "\n\n"
            . "• **Harga:** **{$priceFormatted}** / bulan (Flat)\n"
            . "• **Kecepatan:** **{$bestPkg->speed_mbps} Mbps** (100% Simetris Fiber Optic)\n"
            . "• **Deskripsi:** {$bestPkg->description}\n\n"
            . "Mengapa kami merekomendasikan paket ini?\n"
            . "Paket ini memiliki rasio nilai dan kestabilan terbaik (*best value*) dengan bandwidth 25 Mbps murni simetris yang sangat optimal untuk multitasking 5-8 perangkat (streaming 4K lancar, game bebas lag, dan meeting online tanpa kendala).\n\n"
            . "Paket ini merupakan rekomendasi utama kami untuk Anda.";

        $orderUrl = route('home') . "?package_id={$bestPkg->id}#daftar";

        return [
            'type' => 'card',
            'text' => $text,
            'package_id' => $bestPkg->id,
            'action' => 'highlight_package',
            'quick_replies' => [
                ['label' => 'PILIH PAKET INI', 'url' => $orderUrl],
                ['label' => 'LIHAT PAKET LAINNYA', 'intent' => 'recom_cycle:' . $bestPkg->id],
                ['label' => 'Lihat Detail Spesifikasi', 'intent' => "recom_detail:{$bestPkg->id}"],
                ['label' => 'Menu Utama', 'intent' => 'menu'],
            ],
        ];
    }

    /**
     * Response for coverage areas
     */
    private function getCoverageResponse(): array
    {
        $coverages = CoverageArea::where('is_active', true)->get();

        $text = "**Wilayah Jangkauan (Coverage Area Fiber Optic)**\n\n";
        $text .= "Jaringan kabel Fiber Optic **TRICORE DATA MEDIA** saat ini mencakup wilayah Purwokerto dan sekitarnya:\n\n";

        if ($coverages->isNotEmpty()) {
            foreach ($coverages as $area) {
                $subdistricts = is_array($area->subdistricts) ? implode(', ', $area->subdistricts) : $area->subdistricts;
                $text .= "**{$area->district_name}**\n";
                $text .= "   Kelurahan/Desa: {$subdistricts}\n\n";
            }
        } else {
            $text .= "**Purwokerto Timur:** Arcawinangun, Kranji, Mersi, Purwokerto Lor, Sokanegara\n";
            $text .= "**Purwokerto Wetan:** Karangpucung, Tanjung, Teluk\n";
            $text .= "**Sokaraja:** Sokaraja Kidul, Sokaraja Lor, Sokaraja Kulon, Sokaraja Wetan\n\n";
        }

        $text .= "Ingin memastikan jarak tiang ODP ke rumah Anda?";

        return [
            'type' => 'text',
            'text' => $text,
            'quick_replies' => [
                ['label' => 'Cek Peta di Web', 'url' => route('home').'#coverage'],
                ['label' => 'Daftar Pasang Baru', 'url' => route('home').'#daftar'],
                ['label' => 'Cek Titik via WhatsApp', 'intent' => 'contact_cs'],
                ['label' => 'Menu Utama', 'intent' => 'menu'],
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
            'text' => "**Pusat Kendala & Troubleshooting Teknis WiFi**\n\nSilakan pilih kendala yang sedang Anda alami untuk solusi mandiri langkah demi langkah:",
            'quick_replies' => [
                ['label' => 'Lampu LOS Kedip Merah', 'intent' => 'troubleshoot_los'],
                ['label' => 'Lampu PON Kedip Hijau', 'intent' => 'troubleshoot_pon'],
                ['label' => 'Internet Lemot / Lambat', 'intent' => 'troubleshoot_slow'],
                ['label' => 'Ping Tinggi / Game RTO', 'intent' => 'troubleshoot_ping'],
                ['label' => 'Tersambung Tanpa Internet', 'intent' => 'troubleshoot_no_internet'],
                ['label' => 'Cara Ganti Sandi WiFi', 'intent' => 'troubleshoot_password'],
                ['label' => 'Pengaturan DNS Cepat', 'intent' => 'troubleshoot_dns'],
                ['label' => 'WiFi Extender / Tambah Jangkauan', 'intent' => 'troubleshoot_extender'],
                ['label' => 'Panggil Bantuan Teknisi WA', 'intent' => 'contact_cs'],
                ['label' => 'Menu Utama', 'intent' => 'menu'],
            ],
        ];
    }

    /**
     * Troubleshooting: LOS Red Light
     */
    private function getTroubleshootLOS(): array
    {
        $text = "**Panduan Penanganan Lampu LOS Berkedip Merah**\n\n";
        $text .= "Lampu LOS merah menandakan modem (ONU/ONT) tidak mendeteksi sinyal optik dari kabel fiber optic.\n\n";
        $text .= "**Langkah Perbaikan Mandiri:**\n";
        $text .= "1. Periksa kabel fiber hitam tipis (dengan kepala konektor biru/hijau) di bagian belakang/bawah modem.\n";
        $text .= "2. Pastikan kabel tidak tertekuk tajam (minimal radius tekukan selebar kaleng minuman), tidak terjepit pintu, dan tidak rusak.\n";
        $text .= "3. Matikan modem dengan mencabut adaptor listrik selama **15 detik**, lalu pasang kembali (Power Cycle).\n";
        $text .= "4. Tunggu 2-3 menit hingga lampu PON kembali menyala hijau stabil.\n\n";
        $text .= "**Jika tetap berkedip merah:** Kemungkinan besar terjadi kabel putus di tiang ODP luar rumah (misal terkena ranting pohon/kendaraan). Tim teknisi kami akan segera meluncur ke lokasi!";

        return [
            'type' => 'text',
            'text' => $text,
            'quick_replies' => [
                ['label' => 'Buat Tiket Lapor ke CS WA', 'intent' => 'contact_cs'],
                ['label' => 'Menu Kendala Lain', 'intent' => 'troubleshoot_menu'],
                ['label' => 'Menu Utama', 'intent' => 'menu'],
            ],
        ];
    }

    /**
     * Troubleshooting: PON Light Blinking
     */
    private function getTroubleshootPON(): array
    {
        $text = "**Panduan Lampu PON Berkedip Hijau**\n\n";
        $text .= "Lampu PON yang berkedip hijau menandakan modem sedang berusaha menghubungkan dan mengotentikasi diri ke server pusat (OLT TRICORE).\n\n";
        $text .= "**Langkah Penanganan:**\n";
        $text .= "1. Tunggu 3-5 menit saat modem baru saja dinyalakan.\n";
        $text .= "2. Jika lebih dari 10 menit masih berkedip (tidak mau menyala diam), silakan restart modem 1 kali.\n";
        $text .= "3. Jika tetap berkedip, kemungkinan ada proses maintenance server di wilayah Anda atau perlu reset profil konfigurasi.";

        return [
            'type' => 'text',
            'text' => $text,
            'quick_replies' => [
                ['label' => 'Cek Status Server ke CS', 'intent' => 'contact_cs'],
                ['label' => 'Menu Kendala', 'intent' => 'troubleshoot_menu'],
            ],
        ];
    }

    /**
     * Troubleshooting: Slow Internet
     */
    private function getTroubleshootSlow(): array
    {
        $text = "**Panduan Optimasi Internet Lambat / Lemot**\n\n";
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
                ['label' => 'Cara Cek Speedtest', 'intent' => 'troubleshoot_speedtest'],
                ['label' => 'Cek Status Tagihan', 'intent' => 'check_bill_prompt'],
                ['label' => 'Hubungi CS', 'intent' => 'contact_cs'],
                ['label' => 'Menu Kendala', 'intent' => 'troubleshoot_menu'],
            ],
        ];
    }

    /**
     * Troubleshooting: High Ping & Gaming Lag
     */
    private function getTroubleshootPing(): array
    {
        $text = "**Panduan Mengatasi Ping Tinggi / RTO / Game Lag**\n\n";
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
                ['label' => 'Cara Ganti DNS', 'intent' => 'troubleshoot_dns'],
                ['label' => 'Bantuan CS / Routing Game', 'intent' => 'contact_cs'],
                ['label' => 'Menu Kendala', 'intent' => 'troubleshoot_menu'],
            ],
        ];
    }

    /**
     * Troubleshooting: Connected without internet
     */
    private function getTroubleshootNoInternet(): array
    {
        $text = "**Panduan Tersambung WiFi tapi 'No Internet Access'**\n\n";
        $text .= "1. **Forget Network:** Di pengaturan WiFi HP/Laptop Anda, pilih *Lupakan Jaringan (Forget)* lalu sambungkan dan ketik sandi kembali.\n";
        $text .= "2. **Restart Modem:** Cabut listrik modem selama 15 detik lalu colokkan kembali.\n";
        $text .= "3. **Periksa Lampu Modem:**\n";
        $text .= "   - Lampu **PON** harus berwarna **HIJAU** (diam/solid).\n";
        $text .= "   - Lampu **INTERNET / WAN** harus menyala hijau berkedip cepat.\n";
        $text .= "   - Lampu **LOS** tidak boleh menyala merah.\n";
        $text .= "4. **Cek Status Tagihan:** Pastikan layanan tidak terisolir otomatis oleh sistem karena melewati tanggal jatuh tempo.";

        return [
            'type' => 'text',
            'text' => $text,
            'quick_replies' => [
                ['label' => 'Cek Tagihan Saya', 'intent' => 'check_bill_prompt'],
                ['label' => 'Bantuan CS WhatsApp', 'intent' => 'contact_cs'],
                ['label' => 'Menu Kendala', 'intent' => 'troubleshoot_menu'],
            ],
        ];
    }

    /**
     * Troubleshooting: Change WiFi Password & SSID
     */
    private function getTroubleshootPassword(): array
    {
        $text = "**Cara Mengganti Nama (SSID) & Password WiFi Sendiri**\n\n";
        $text .= "1. Hubungkan HP/Laptop ke jaringan WiFi TRICORE Anda.\n";
        $text .= "2. Buka browser (Chrome/Safari) dan ketik IP Router: `http://192.168.1.1` atau `http://192.168.100.1`.\n";
        $text .= "3. Masukkan Username & Password admin router (tertera pada stiker di bagian bawah fisik modem).\n";
        $text .= "4. Masuk ke menu **Network** > **WLAN / Wireless (2.4G / 5G)** > **Security**.\n";
        $text .= "5. Ganti kolom **SSID Name** (Nama WiFi) atau **WPA Passphrase / Key** (Sandi minimal 8 karakter).\n";
        $text .= "6. Klik **Apply / Save**, lalu sambungkan ulang HP Anda dengan sandi baru.\n\n";
        $text .= "_Catatan: Jika lupa password login modem, tim CS kami dapat mengganti sandi dari jauh tanpa Anda perlu ribet setting router!_";

        return [
            'type' => 'text',
            'text' => $text,
            'quick_replies' => [
                ['label' => 'Minta CS Gantikan Password', 'intent' => 'contact_cs'],
                ['label' => 'Menu Utama', 'intent' => 'menu'],
            ],
        ];
    }

    /**
     * Troubleshooting: DNS Settings
     */
    private function getTroubleshootDNS(): array
    {
        $text = "**Panduan Setting DNS Cepat & Stabil**\n\n";
        $text .= "Menggunakan DNS publik yang andal dapat mempercepat loading website dan mengurangi ping game:\n\n";
        $text .= "**Pilihan DNS Terbaik:**\n";
        $text .= "- **Cloudflare DNS (Paling Cepat & Privasi):**\n";
        $text .= "  Primary: `1.1.1.1`\n";
        $text .= "  Secondary: `1.0.0.1`\n\n";
        $text .= "- **Google Public DNS (Paling Stabil):**\n";
        $text .= "  Primary: `8.8.8.8`\n";
        $text .= "  Secondary: `8.8.4.4`\n\n";
        $text .= "**Cara Pasang di HP Android / iPhone:**\n";
        $text .= "Masuk ke Pengaturan HP > Koneksi / Jaringan > Private DNS > Masukkan: `one.one.one.one` atau `dns.google`.";

        return [
            'type' => 'text',
            'text' => $text,
            'quick_replies' => [
                ['label' => 'Tips Ping Game', 'intent' => 'troubleshoot_ping'],
                ['label' => 'Menu Kendala', 'intent' => 'troubleshoot_menu'],
            ],
        ];
    }

    /**
     * Troubleshooting: Speedtest Official
     */
    private function getTroubleshootSpeedtest(): array
    {
        $text = "**Panduan Uji Kecepatan (Speedtest) yang Akurat**\n\n";
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
                ['label' => 'Cek Kapasitas Paket', 'intent' => 'packages'],
                ['label' => 'Laporkan Hasil Speedtest ke CS', 'intent' => 'contact_cs'],
                ['label' => 'Menu Kendala', 'intent' => 'troubleshoot_menu'],
            ],
        ];
    }

    /**
     * Policy: FUP / Unlimited
     */
    private function getTroubleshootFUP(): array
    {
        $text = "**Kebijakan Kuota & FUP TRICORE DATA MEDIA**\n\n";
        $text .= "Semua paket internet **TRICORE DATA MEDIA** bersifat **100% UNLIMITED MURNI TANPA FUP (Fair Usage Policy)**:\n\n";
        $text .= "- Tidak ada penurunan kecepatan di tengah bulan.\n";
        $text .= "- Tidak ada batasan gigabyte (GB).\n";
        $text .= "- Bebas unduh, nonton streaming 4K, CCTV, dan gaming sepuasnya 24 jam non-stop dengan kecepatan penuh sesuai paket langganan Anda!";

        return [
            'type' => 'text',
            'text' => $text,
            'quick_replies' => [
                ['label' => 'Lihat Pilihan Paket', 'intent' => 'packages'],
                ['label' => 'Daftar Pasang Baru', 'url' => route('home').'#daftar'],
                ['label' => 'Menu Utama', 'intent' => 'menu'],
            ],
        ];
    }

    /**
     * Troubleshooting: WiFi Extender / Mesh
     */
    private function getTroubleshootExtender(): array
    {
        $text = "**Panduan Memperluas Sinyal WiFi (Rumah Bertingkat / Luas)**\n\n";
        $text .= "Jika sinyal WiFi tidak menjangkau kamar lantai 2 atau halaman belakang:\n\n";
        $text .= "1. **Pasang Router Tambahan / Access Point:** Solusi terbaik menggunakan kabel LAN dari modem utama ke router kedua di lantai 2 (kecepatan 100% utuh).\n";
        $text .= "2. **WiFi Extender / Repeater:** Solusi praktis tanpa kabel, dicolok di titik tengah antara modem dan area sinyal lemah.\n";
        $text .= "3. **Mesh WiFi System:** Memberikan 1 nama WiFi (SSID) yang sama di seluruh rumah dengan perpindahan koneksi otomatis (*seamless roaming*).\n\n";
        $text .= "Ingin bantuan tim teknisi kami untuk penarikan kabel LAN atau pemasangan router tambahan di rumah Anda?";

        return [
            'type' => 'text',
            'text' => $text,
            'quick_replies' => [
                ['label' => 'Konsultasi Pasang Router Tambahan', 'intent' => 'contact_cs'],
                ['label' => 'Menu Kendala', 'intent' => 'troubleshoot_menu'],
            ],
        ];
    }

    /**
     * Payment Information
     */
    private function getPaymentInfoResponse(): array
    {
        $text = "**Informasi Rekening & Cara Pembayaran Tagihan**\n\n";
        $text .= "Pembayaran tagihan resmi WiFi TRICORE DATA MEDIA dapat ditransfer ke rekening tujuan:\n\n";
        $text .= "🏦 **Bank Neo Commerce (BNC)**\n";
        $text .= "• **Nomor Rekening:** `5859459401627129`\n";
        $text .= "• **Nama Pemilik:** TRICORE DATA MEDIA\n\n";
        $text .= "Dapat ditransfer dari seluruh bank nasional (BCA, Mandiri, BRI, BNI) maupun dompet digital (DANA, OVO, GoPay, ShopeePay).\n\n";
        $text .= "**Jatuh Tempo:** Tanggal 10 setiap bulannya.\n\n";
        $text .= "**Langkah Setelah Transfer:**\n";
        $text .= "Unggah foto struk/screenshot transfer Anda pada halaman pembayaran website atau kirim konfirmasi ke WhatsApp Admin agar diverifikasi dan koneksi internet tetap aktif lancar.";

        return [
            'type' => 'text',
            'text' => $text,
            'quick_replies' => [
                ['label' => 'Cek Tagihan & Buka Halaman Bayar', 'intent' => 'check_bill_prompt'],
                ['label' => 'Kirim Bukti Transfer ke WA', 'intent' => 'contact_cs'],
                ['label' => 'Menu Utama', 'intent' => 'menu'],
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
            'text' => "**Pendaftaran Pelanggan Baru (Pasang WiFi Fiber Optic)**\n\nProses pendaftaran sangat mudah dan cepat:\n\n1. Pilih paket internet yang sesuai (15 - 50 Mbps).\n2. Isi formulir pendaftaran online di website atau langsung chat ke WhatsApp kami.\n3. Tim teknisi akan survei tiang ODP dan melakukan instalasi kabel fiber optic ke rumah Anda.\n4. Pembayaran dilakukan setelah instalasi selesai dan internet aktif dites di rumah Anda!\n\n**Keuntungan:** Gratis biaya sewa modem WiFi & Unlimited tanpa batasan kuota (No FUP)!",
            'quick_replies' => [
                ['label' => 'Buka Formulir Pendaftaran', 'url' => route('home').'#daftar'],
                ['label' => 'Lihat Pilihan Paket', 'intent' => 'packages'],
                ['label' => 'Daftar Cepat via WhatsApp', 'intent' => 'contact_cs'],
                ['label' => 'Menu Utama', 'intent' => 'menu'],
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
            'text' => "**Hubungi Tim Customer Service & Teknisi**\n\nTim kami siap melayani Anda setiap hari:\n\n- **WhatsApp CS:** +62 821-3841-3292\n- **Email:** support@tricoredatamedia.net\n- **Kantor:** Jl. KAV. Gelora Indah II, Gg. Renang, Purwokerto Timur\n- **Jam Layanan:** 08.00 - 21.00 WIB (Support Darurat Jaringan 24/7)\n\nSilakan klik tombol di bawah untuk langsung terhubung dengan WhatsApp CS:",
            'quick_replies' => [
                ['label' => 'Buka WhatsApp CS Sekarang', 'url' => $waUrl],
                ['label' => 'Menu Utama', 'intent' => 'menu'],
            ],
        ];
    }

    /**
     * Welcome response / Menu Utama
     */
    private function getWelcomeResponse(): array
    {
        return [
            'type' => 'text',
            'text' => "Halo! Selamat datang di WiFi Tricore Data. Saya asisten virtual yang siap membantu menemukan paket internet yang sesuai dengan kebutuhan dan anggaran Anda. Mari kita cari paket yang paling cocok!\n\nSilakan pilih menu bantuan di bawah ini atau mulai konsultasi rekomendasi:",
            'quick_replies' => $this->getMainMenuQuickReplies(),
        ];
    }

    /**
     * Main Menu Quick Replies
     */
    /**
     * Main Menu Quick Replies
     */
    private function getMainMenuQuickReplies(): array
    {
        return [
            ['label' => 'Rekomendasi Paket (Rule-Based)', 'intent' => 'recom_start'],
            ['label' => 'Cek Tagihan Saya', 'intent' => 'check_bill_prompt'],
            ['label' => 'Diagnosis Gangguan (RBR)', 'intent' => 'diagnosis_menu'],
            ['label' => 'Paket & Kecepatan', 'intent' => 'packages'],
            ['label' => 'Bantuan Lampu LOS Merah', 'intent' => 'troubleshoot_los'],
            ['label' => 'Tips Ping Game & RTO', 'intent' => 'troubleshoot_ping'],
            ['label' => 'Cara Ganti Sandi WiFi', 'intent' => 'troubleshoot_password'],
            ['label' => 'Coverage Area', 'intent' => 'coverage'],
            ['label' => 'Hubungi CS WhatsApp', 'intent' => 'contact_cs'],
        ];
    }

    /**
     * Rule-Based Reasoning Diagnosis Menu in Chatbot
     */
    private function getDiagnosisMenuResponse(): array
    {
        return [
            'type' => 'text',
            'text' => "**Sistem Cerdas Diagnosis Gangguan Jaringan (Rule-Based Reasoning)**\n\nSistem pakar TRINET-BILL dapat mendiagnosis akar masalah kendala internet Anda berdasarkan indikator fisik modem ONT secara deterministik.\n\nPilih gejala atau kondisi indikator lampu modem Anda saat ini:",
            'quick_replies' => [
                ['label' => 'Lampu LOS Berkedip Merah', 'intent' => 'diagnosis_los'],
                ['label' => 'Modem Mati / Power Off', 'intent' => 'diagnosis_power'],
                ['label' => 'Lampu PON Berkedip Hijau', 'intent' => 'diagnosis_pon'],
                ['label' => 'WiFi Lemot / Speed Drop', 'intent' => 'diagnosis_slow'],
                ['label' => 'Game Lag / Ping RTO', 'intent' => 'diagnosis_lag'],
                ['label' => 'Tersambung Tanpa Internet', 'intent' => 'diagnosis_dns'],
                ['label' => 'Buka Panel Diagnosis Web', 'url' => route('diagnosis')],
                ['label' => 'Menu Utama', 'intent' => 'menu'],
            ],
        ];
    }

    /**
     * Get Rule-Based Reasoning result for specific rule
     */
    private function getDiagnosisRuleResponse(string $ruleId): array
    {
        $controller = new DiagnosisController();
        $params = match ($ruleId) {
            'RBR-01' => ['power' => 'on', 'pon' => 'off', 'los' => 'red_blinking', 'lan' => 'on', 'wifi' => 'on', 'issue' => 'los'],
            'RBR-02' => ['power' => 'off', 'pon' => 'off', 'los' => 'off', 'lan' => 'off', 'wifi' => 'off', 'issue' => 'power'],
            'RBR-03' => ['power' => 'on', 'pon' => 'blinking_green', 'los' => 'off', 'lan' => 'on', 'wifi' => 'on', 'issue' => 'pon'],
            'RBR-05' => ['power' => 'on', 'pon' => 'solid_green', 'los' => 'off', 'lan' => 'on', 'wifi' => 'on', 'issue' => 'slow'],
            'RBR-06' => ['power' => 'on', 'pon' => 'solid_green', 'los' => 'off', 'lan' => 'on', 'wifi' => 'on', 'issue' => 'lag'],
            default => ['power' => 'on', 'pon' => 'solid_green', 'los' => 'off', 'lan' => 'on', 'wifi' => 'on', 'issue' => 'dns'],
        };

        $result = $controller->runRuleBasedEngine(
            $params['power'],
            $params['pon'],
            $params['los'],
            $params['lan'],
            $params['wifi'],
            $params['issue']
        );

        $text = "🧠 **Hasil Diagnosis Pakar ({$result['rule_id']})**\n\n";
        $text .= "**Aturan:** `{$result['rule_name']}`\n";
        $text .= "**Hasil:** **{$result['diagnosis_title']}**\n";
        $text .= "**Tingkat:** [ {$result['badge']} ]\n\n";
        $text .= "**Analisis:** {$result['summary']}\n\n";
        $text .= "**Rekomendasi Langkah Penanganan:**\n";
        foreach ($result['action_steps'] as $idx => $step) {
            $text .= ($idx + 1) . ". {$step}\n";
        }

        $waMsg = urlencode("Halo Tim Teknisi TRICORE, saya telah melakukan diagnosis cerdas: {$result['rule_id']} - {$result['diagnosis_title']}. Mohon bantuan tindak lanjut.");
        $waUrl = "https://wa.me/6282138413292?text={$waMsg}";

        $quickReplies = [];
        if ($result['need_technician']) {
            $quickReplies[] = ['label' => 'Kirim Tiket ke Teknisi WA', 'url' => $waUrl];
        }
        $quickReplies[] = ['label' => 'Buka Panel Diagnosis Lengkap', 'url' => route('diagnosis')];
        $quickReplies[] = ['label' => 'Diagnosis Kendala Lain', 'intent' => 'diagnosis_menu'];
        $quickReplies[] = ['label' => 'Menu Utama', 'intent' => 'menu'];

        return [
            'type' => 'text',
            'text' => $text,
            'quick_replies' => $quickReplies,
        ];
    }

    /**
     * Handle recommendation workflow intents
     */
    private function handleRecommendationIntent(string $intent, string $message = ''): array
    {
        // 1. Start consultation
        if ($intent === 'recom_start' || $intent === 'recom_budget') {
            return $this->getRecommendationStepBudget();
        }

        // 2. Budget chosen -> Ask Devices
        // Format: recom_devices:{budget}
        if (str_starts_with($intent, 'recom_devices:')) {
            $budget = substr($intent, strlen('recom_devices:'));
            return $this->getRecommendationStepDevices($budget);
        }

        // 3. Devices chosen -> Ask Activity
        // Format: recom_activity:{budget}:{devices}
        if (str_starts_with($intent, 'recom_activity:')) {
            $parts = explode(':', $intent);
            $budget = $parts[1] ?? 'medium';
            $devices = $parts[2] ?? '3_5';
            return $this->getRecommendationStepActivity($budget, $devices);
        }

        // 4. Activity chosen -> Ask Priority
        // Format: recom_priority:{budget}:{devices}:{activity}
        if (str_starts_with($intent, 'recom_priority:')) {
            $parts = explode(':', $intent);
            $budget = $parts[1] ?? 'medium';
            $devices = $parts[2] ?? '3_5';
            $activity = $parts[3] ?? 'browsing';
            return $this->getRecommendationStepPriority($budget, $devices, $activity);
        }

        // 5. Priority chosen -> Evaluate Rule-Based Algorithm & Present Result
        // Format: recom_eval:{budget}:{devices}:{activity}:{priority}
        if (str_starts_with($intent, 'recom_eval:')) {
            $parts = explode(':', $intent);
            $budget = $parts[1] ?? 'medium';
            $devices = $parts[2] ?? '3_5';
            $activity = $parts[3] ?? 'browsing';
            $priority = $parts[4] ?? 'standard';
            return $this->evaluateRecommendation($budget, $devices, $activity, $priority);
        }

        // 6. View package details inside chatbot
        if (str_starts_with($intent, 'recom_detail:')) {
            $packageId = (int) substr($intent, strlen('recom_detail:'));
            return $this->getPackageDetailResponse($packageId);
        }

        // 7. Cycle through other recommendations
        if (str_starts_with($intent, 'recom_cycle:')) {
            $currentPkgId = (int) substr($intent, strlen('recom_cycle:'));
            return $this->cycleNextRecommendation($currentPkgId);
        }

        return $this->getPackagesResponse();
    }

    /**
     * Step 1: Pertanyaan Anggaran Bulanan
     */
    private function getRecommendationStepBudget(): array
    {
        $text = "Halo! Selamat datang di WiFi Tricore Data. Saya asisten virtual yang siap membantu menemukan paket internet yang sesuai dengan kebutuhan dan anggaran Anda. Mari kita cari paket yang paling cocok!\n\n"
            . "**Langkah 1 dari 4: Anggaran Bulanan**\n\n"
            . "Berapa anggaran bulanan yang disiapkan untuk berlangganan WiFi?";

        return [
            'type' => 'text',
            'text' => $text,
            'quick_replies' => [
                ['label' => 'Hemat (< Rp 130.000)', 'intent' => 'recom_devices:low'],
                ['label' => 'Standar (Rp 130rb - 160rb)', 'intent' => 'recom_devices:medium'],
                ['label' => 'Menengah (Rp 160rb - 190rb)', 'intent' => 'recom_devices:high'],
                ['label' => 'Bisnis / Pro (> Rp 190.000)', 'intent' => 'recom_devices:ultra'],
                ['label' => 'Batal / Menu Utama', 'intent' => 'menu'],
            ],
        ];
    }

    /**
     * Step 2: Pertanyaan Jumlah Perangkat / Pengguna
     */
    private function getRecommendationStepDevices(string $budget): array
    {
        $text = "**Langkah 2 dari 4: Jumlah Perangkat & Pengguna**\n\n"
            . "Berapa jumlah orang atau perangkat (HP, laptop, Smart TV, dsb) yang akan terhubung ke WiFi?";

        return [
            'type' => 'text',
            'text' => $text,
            'quick_replies' => [
                ['label' => '1 - 3 Perangkat', 'intent' => "recom_activity:{$budget}:1_3"],
                ['label' => '3 - 5 Perangkat', 'intent' => "recom_activity:{$budget}:3_5"],
                ['label' => '5 - 8 Perangkat', 'intent' => "recom_activity:{$budget}:5_8"],
                ['label' => '8 - 12 Perangkat', 'intent' => "recom_activity:{$budget}:8_12"],
                ['label' => '12+ Perangkat / Bisnis', 'intent' => "recom_activity:{$budget}:12_plus"],
                ['label' => 'Ubah Anggaran', 'intent' => 'recom_start'],
            ],
        ];
    }

    /**
     * Step 3: Pertanyaan Kebutuhan Utama
     */
    private function getRecommendationStepActivity(string $budget, string $devices): array
    {
        $text = "**Langkah 3 dari 4: Kebutuhan Utama Penggunaan Internet**\n\n"
            . "Apa aktivitas utama yang paling sering dilakukan saat berinternet?";

        return [
            'type' => 'text',
            'text' => $text,
            'quick_replies' => [
                ['label' => 'Browsing & Media Sosial', 'intent' => "recom_priority:{$budget}:{$devices}:browsing"],
                ['label' => 'Belajar & Kerjakan Tugas', 'intent' => "recom_priority:{$budget}:{$devices}:study"],
                ['label' => 'Streaming Film & Video HD/4K', 'intent' => "recom_priority:{$budget}:{$devices}:streaming"],
                ['label' => 'Gaming Online Bebas Lag', 'intent' => "recom_priority:{$budget}:{$devices}:gaming"],
                ['label' => 'Video Conference & WFH', 'intent' => "recom_priority:{$budget}:{$devices}:wfx"],
                ['label' => 'Penggunaan Intensif Banyak User', 'intent' => "recom_priority:{$budget}:{$devices}:intensive"],
                ['label' => 'Ubah Pilihan Sebelumnya', 'intent' => "recom_devices:{$budget}"],
            ],
        ];
    }

    /**
     * Step 4: Pertanyaan Prioritas Kestabilan & Kecepatan
     */
    private function getRecommendationStepPriority(string $budget, string $devices, string $activity): array
    {
        $text = "**Langkah 4 dari 4: Prioritas Koneksi**\n\n"
            . "Apakah koneksi digunakan untuk aktivitas biasa atau aktivitas yang membutuhkan kecepatan dan kestabilan lebih tinggi?";

        return [
            'type' => 'text',
            'text' => $text,
            'quick_replies' => [
                ['label' => 'Aktivitas Biasa (Standar)', 'intent' => "recom_eval:{$budget}:{$devices}:{$activity}:standard"],
                ['label' => 'Kecepatan & Kestabilan Tinggi (Prioritas)', 'intent' => "recom_eval:{$budget}:{$devices}:{$activity}:high_speed"],
                ['label' => 'Ubah Kebutuhan Internet', 'intent' => "recom_activity:{$budget}:{$devices}"],
            ],
        ];
    }

    /**
     * Step 5: Menjalankan Rule-Based Engine & Menampilkan Hasil Rekomendasi
     */
    private function evaluateRecommendation(string $budget, string $devices, string $activity, string $priority): array
    {
        $engine = new \App\Services\PackageRecommendationEngine();
        $res = $engine->evaluate($budget, $devices, $activity, $priority);

        if (!$res['success']) {
            return [
                'type' => 'text',
                'text' => "Mohon maaf, saat ini data paket internet belum tersedia di sistem kami.",
                'quick_replies' => [
                    ['label' => 'Hubungi CS WhatsApp', 'intent' => 'contact_cs'],
                    ['label' => 'Menu Utama', 'intent' => 'menu'],
                ],
            ];
        }

        $p = $res['primary'];
        $c = $res['criteria'];

        $text = "Terima kasih sudah memberikan informasi!\n\n"
            . "Berdasarkan anggaran, jumlah pengguna, dan kebutuhan internet Anda, berikut rekomendasi paket yang paling sesuai.\n\n"
            . "━━━━━━━━━━━━━━━━━━━━\n"
            . "⭐ **REKOMENDASI UTAMA** ⭐\n"
            . "━━━━━━━━━━━━━━━━━━━━\n\n"
            . "• **Nama Paket:** **{$p['name']}**" . ($p['badge'] ? " [{$p['badge']}]" : "") . "\n"
            . "• **Harga:** **{$p['formatted_price']}** / bulan\n"
            . "• **Kecepatan:** **{$p['speed_mbps']} Mbps** (Simetris 100% Fiber Optic)\n"
            . "• **Tingkat Kecocokan:** **{$p['match_percentage']}%**\n\n"
            . "📝 **Deskripsi Paket:**\n"
            . "_{$p['description']}_\n\n"
            . "💡 **Alasan rekomendasi:**\n";

        foreach ($p['reasons'] as $idx => $r) {
            $text .= ($idx + 1) . ". {$r}\n";
        }

        if ($res['is_over_budget_constraint']) {
            $text .= "\n⚠️ _Catatan: Anggaran yang dipilih berada di bawah paket termurah kami, paket ini adalah alternatif terdekat yang paling ekonomis._\n";
        }

        // Tampilkan Alternatif Paket
        if (!empty($res['alternatives'])) {
            $text .= "\n━━━━━━━━━━━━━━━━━━━━\n"
                . "📌 **ALTERNATIF PAKET LAINNYA:**\n";
            foreach ($res['alternatives'] as $alt) {
                $text .= "• **{$alt['name']}** ({$alt['speed_mbps']} Mbps) - **{$alt['formatted_price']}**/bln (Kecocokan: {$alt['match_percentage']}%)\n";
            }
        }

        $text .= "\nApakah Anda ingin melihat detail paket atau langsung melanjutkan pemesanan?";

        // Direct order URL automatically binds package_id
        $orderUrl = route('home') . "?package_id={$p['id']}#daftar";

        $quickReplies = [
            ['label' => "Pesan Paket Ini Sekarang", 'url' => $orderUrl],
            ['label' => "Lihat Detail {$p['name']}", 'intent' => "recom_detail:{$p['id']}"],
            ['label' => 'Ubah Anggaran', 'intent' => 'recom_start'],
            ['label' => 'Ubah Kebutuhan Internet', 'intent' => "recom_activity:{$budget}:{$devices}"],
            ['label' => 'Lihat Semua Daftar Paket', 'intent' => 'packages'],
            ['label' => 'Konsultasi Ulang Dari Awal', 'intent' => 'recom_start'],
            ['label' => 'Menu Utama', 'intent' => 'menu'],
        ];

        return [
            'type' => 'card',
            'text' => $text,
            'quick_replies' => $quickReplies,
        ];
    }

    /**
     * Tampilkan Detail Spesifikasi Paket dari Database
     */
    private function getPackageDetailResponse(int $packageId): array
    {
        $pkg = Package::find($packageId);
        if (!$pkg) {
            return [
                'type' => 'text',
                'text' => "Data paket tidak ditemukan di database.",
                'quick_replies' => [
                    ['label' => 'Mulai Konsultasi Baru', 'intent' => 'recom_start'],
                    ['label' => 'Menu Utama', 'intent' => 'menu'],
                ],
            ];
        }

        $text = "📋 **DETAIL LENGKAP PAKET INTERNET**\n\n"
            . "• **Nama Paket:** **{$pkg->name}**\n"
            . "• **Kecepatan:** **{$pkg->speed_mbps} Mbps** Murni Simetris (Upload = Download)\n"
            . "• **Harga Bulanan:** **{$pkg->formatted_price}** (Tanpa Biaya Tersembunyi)\n"
            . "• **Rekomendasi Kapasitas:** {$pkg->device_recommendation}\n"
            . "• **Deskripsi:** {$pkg->description}\n\n"
            . "✨ **Fitur & Keunggulan Resmi:**\n"
            . "• 100% Kabel Fiber Optic Murni Dedicated\n"
            . "• Unlimited Kuota Murni (Tanpa Batasan FUP)\n"
            . "• Gratis Sewa Modem Wi-Fi Fiber Optic Dual-Band\n"
            . "• Latency Rendah (~5ms) Stabil untuk Gaming & Video Call\n"
            . "• Dukungan Bantuan & Teknisi Lapangan 24/7 Siaga\n\n"
            . "Siap memasang paket ini di rumah atau lokasi Anda?";

        $orderUrl = route('home') . "?package_id={$pkg->id}#daftar";

        return [
            'type' => 'card',
            'text' => $text,
            'package_id' => $pkg->id,
            'action' => 'highlight_package',
            'quick_replies' => [
                ['label' => "Pilih Paket ({$pkg->name})", 'url' => $orderUrl],
                ['label' => 'Lihat Rekomendasi Lainnya', 'intent' => 'recom_cycle:' . $pkg->id],
                ['label' => 'Konsultasi Bertahap (4 Langkah)', 'intent' => 'recom_start'],
                ['label' => 'Menu Utama', 'intent' => 'menu'],
            ],
        ];
    }

    /**
     * Cycle through alternative recommendations
     */
    private function cycleNextRecommendation(int $currentPkgId): array
    {
        $packages = Package::where('is_active', true)->orderBy('price', 'asc')->get();
        if ($packages->isEmpty()) {
            return $this->getPackagesResponse();
        }

        $currentIndex = $packages->search(fn ($p) => $p->id === $currentPkgId);
        $nextIndex = ($currentIndex === false || $currentIndex + 1 >= $packages->count()) ? 0 : ($currentIndex + 1);
        $nextPkg = $packages->get($nextIndex);

        $priceFormatted = $nextPkg->formatted_price ?: ('Rp' . number_format($nextPkg->price, 0, ',', '.'));

        $reasons = [
            15 => 'Pilihan paling hemat dan ekonomis untuk pengguna harian (1-3 HP/Laptop).',
            20 => 'Sangat cocok untuk keluarga kecil dengan kebutuhan streaming film HD dan bekerja santai.',
            25 => 'Paket terfavorit dengan kecepatan seimbang 25 Mbps, pas untuk 5-8 perangkat serentak.',
            35 => 'Bandwidth super kencang 35 Mbps untuk live streaming, konten kreator, dan download cepat.',
            50 => 'Solusi kapasitas maksimal 50 Mbps untuk kafe, kantor, kos, dan multi-user intensif.',
        ];

        $reason = $reasons[$nextPkg->speed_mbps] ?? "Paket andalan TRICORE dengan stabilitas serat optik 100% murni.";

        $text = "Rekomendasi Paket Terbaik untuk Anda!\n\n"
            . "Berdasarkan paket internet yang tersedia, kami merekomendasikan:\n\n"
            . "**{$nextPkg->name}**" . ($nextPkg->badge ? " [{$nextPkg->badge}]" : "") . "\n\n"
            . "• **Harga:** **{$priceFormatted}** / bulan (Flat)\n"
            . "• **Kecepatan:** **{$nextPkg->speed_mbps} Mbps** (100% Simetris Fiber Optic)\n"
            . "• **Deskripsi:** {$nextPkg->description}\n\n"
            . "Mengapa kami merekomendasikan paket ini?\n"
            . "{$reason}\n\n"
            . "Paket ini merupakan salah satu rekomendasi terbaik kami untuk Anda.";

        $orderUrl = route('home') . "?package_id={$nextPkg->id}#daftar";

        return [
            'type' => 'card',
            'text' => $text,
            'package_id' => $nextPkg->id,
            'action' => 'highlight_package',
            'quick_replies' => [
                ['label' => 'PILIH PAKET INI', 'url' => $orderUrl],
                ['label' => 'LIHAT PAKET LAINNYA', 'intent' => 'recom_cycle:' . $nextPkg->id],
                ['label' => 'Lihat Detail Spesifikasi', 'intent' => "recom_detail:{$nextPkg->id}"],
                ['label' => 'Menu Utama', 'intent' => 'menu'],
            ],
        ];
    }
}
