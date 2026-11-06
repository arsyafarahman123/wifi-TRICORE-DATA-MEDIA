<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DiagnosisController extends Controller
{
    /**
     * Display interactive Rule-Based Reasoning Diagnosis Portal
     */
    public function index(Request $request): View
    {
        $customerCode = trim($request->query('customer_code', ''));
        $customer = null;

        if (! empty($customerCode)) {
            $customer = Customer::with('package')->where('customer_code', $customerCode)->first();
        }

        return view('portal.diagnosis', compact('customer', 'customerCode'));
    }

    /**
     * Process Rule-Based Reasoning Inference Engine
     */
    public function analyze(Request $request): JsonResponse|View
    {
        $powerLight = $request->input('power_light', 'on');
        $ponLight = $request->input('pon_light', 'solid_green');
        $losLight = $request->input('los_light', 'off');
        $lanLight = $request->input('lan_light', 'on');
        $wifiLight = $request->input('wifi_light', 'on');
        $issueType = $request->input('issue_type', 'general');
        $customerCode = trim($request->input('customer_code', ''));

        $customer = null;
        if (! empty($customerCode)) {
            $customer = Customer::with('package')->where('customer_code', $customerCode)->first();
        }

        // Run Rule-Based Reasoning Inference Engine (Forward Chaining)
        $result = $this->runRuleBasedEngine(
            $powerLight,
            $ponLight,
            $losLight,
            $lanLight,
            $wifiLight,
            $issueType,
            $customer
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($result);
        }

        return view('portal.diagnosis', [
            'customer' => $customer,
            'customerCode' => $customerCode,
            'diagnosisResult' => $result,
            'inputState' => [
                'power_light' => $powerLight,
                'pon_light' => $ponLight,
                'los_light' => $losLight,
                'lan_light' => $lanLight,
                'wifi_light' => $wifiLight,
                'issue_type' => $issueType,
            ],
        ]);
    }

    /**
     * Rule-Based Reasoning (RBR) Inference Engine for Network Diagnosis
     */
    public function runRuleBasedEngine(
        string $power,
        string $pon,
        string $los,
        string $lan,
        string $wifi,
        string $issueType,
        ?Customer $customer = null
    ): array {
        // VALIDASI ANOMALI / KOMBINASI INDIKATOR YANG TIDAK MASUK AKAL SECARA TEKNIS
        // 1. Daya mati (Power = OFF), tapi lampu lain menyala (PON, LOS, LAN, WiFi)
        if ($power === 'off' && ($pon !== 'off' || $los !== 'off' || $lan !== 'off' || $wifi !== 'off')) {
            return [
                'rule_id' => 'RBR-INVALID-01',
                'rule_name' => 'IF Power = OFF AND Other Lights = ON THEN Anomali Indikator',
                'diagnosis_title' => 'Kombinasi Indikator Tidak Valid (Anomali Fisik)',
                'severity' => 'medium',
                'badge' => 'Kombinasi Tidak Masuk Akal',
                'summary' => 'Perangkat tidak mungkin menyalakan indikator optik/jaringan jika kabel power atau daya listrik sedang padam.',
                'root_causes' => [
                    'Salah mengamati indikator lampu pada bodi modem.',
                    'Unit modem mengalami arus bocor balik atau malfungsi papan PCB.',
                ],
                'action_steps' => [
                    'Periksa kembali lampu POWER (biasanya terletak paling kiri dengan simbol saklar/daya).',
                    'Jika power benar-benar mati, pastikan lampu lain juga padam.',
                ],
                'need_technician' => false,
                'estimated_fix_time' => 'Periksa Ulang Fisik Modem',
            ];
        }

        // 2. Lampu LOS merah (sinyal putus), tapi PON menyala stabil hijau (sinkron OLT)
        if (($los === 'red_blinking' || $los === 'red_solid') && $pon === 'solid_green') {
            return [
                'rule_id' => 'RBR-INVALID-02',
                'rule_name' => 'IF LOS = Red AND PON = Solid Green THEN Konflik Status Fisik Optik',
                'diagnosis_title' => 'Konflik Indikator Optik (LOS Merah & PON Hijau Bersamaan)',
                'severity' => 'medium',
                'badge' => 'Kombinasi Tidak Masuk Akal',
                'summary' => 'Secara protokol GPON standar, modem tidak dapat terotentikasi penuh ke OLT (PON Hijau Solid) apabila sedang terjadi putus serat optik (LOS Merah).',
                'root_causes' => [
                    'Kesalahan pemilihan indikator saat mengisi form diagnosis.',
                    'Modem mendeteksi false alarm pada modul BOSA/receiver optik.',
                ],
                'action_steps' => [
                    'Pastikan apakah yang menyala merah adalah lampu LOS atau lampu INTERNET/WLAN.',
                    'Lakukan power cycle (cabut adaptor 15 detik lalu nyalakan kembali).',
                ],
                'need_technician' => false,
                'estimated_fix_time' => 'Periksa Ulang Fisik Modem',
            ];
        }

        // RULE 1: Kabel Fiber Optik Putus / Redaman Loss (LOS Merah)
        if ($los === 'red_blinking' || $los === 'red_solid') {
            return [
                'rule_id' => 'RBR-01',
                'rule_name' => 'IF Lampu LOS = Berkedip/Menyala Merah THEN Dropcore Fiber Putus / Loss Signal',
                'diagnosis_title' => 'Sinyal Fiber Optik Terputus (Optical Loss / Redaman Drop)',
                'severity' => 'critical',
                'badge' => 'Gangguan Fisik Optik (Kritis)',
                'summary' => 'Modem ONT tidak menerima sinyal laser optik dari ODP tiang (redaman lebih buruk dari -27 dBm atau 0 dBm).',
                'root_causes' => [
                    'Kabel dropcore fiber optik di luar rumah tertimpa dahan pohon, tersangkut kendaraan, atau putus.',
                    'Konektor biru SC-UPC pada roset / belakang modem terlepas atau kotor terkena debu.',
                    'Port splitter di ODP pusat mengalami pemadaman jalur optik.',
                ],
                'action_steps' => [
                    'Periksa kabel optik kuning/hitam dari roset ke belakang modem, pastikan konektor biru tertancap erat.',
                    'Pastikan kabel tidak tertekuk tajam (bending radius minimal 3 cm) atau terjepit pintu.',
                    'Jangan mencoba menyambung serat kaca sendiri karena membutuhkan alat Optical Fusion Splicer.',
                    'Teknisi TRICORE perlu mengukur nilai redaman menggunakan Optical Power Meter (OPM) dan penyambungan ulang.',
                ],
                'need_technician' => true,
                'estimated_fix_time' => '1 - 3 Jam Kerja',
            ];
        }

        // RULE 2: Perangkat Mati Daya / Adaptor Rusak (Power Mati)
        if ($power === 'off') {
            return [
                'rule_id' => 'RBR-02',
                'rule_name' => 'IF Lampu Power = Mati THEN Gangguan Suplai Daya / Adaptor Rusak',
                'diagnosis_title' => 'Modem ONT Mati Total (Power Failure)',
                'severity' => 'high',
                'badge' => 'Kendala Daya Listrik',
                'summary' => 'Unit modem ONT tidak menerima arus daya DC dari adaptor listrik.',
                'root_causes' => [
                    'Steker adaptor listrik longgar pada stopkontak PLN.',
                    'Adaptor daya 12V 1A / 1.5A mengalami kerusakan komponen (korsleting/overheat).',
                    'Tombol ON/OFF di bagian samping/belakang modem dalam posisi tertekan OFF.',
                ],
                'action_steps' => [
                    'Cek apakah stopkontak PLN berfungsi normal (coba colokkan charger HP).',
                    'Pastikan saklar tombol power di belakang router ditekan ke posisi ON.',
                    'Periksa ujung jack adaptor terpasang kencang di port POWER modem.',
                    'Jika adaptor panas berlebih dan lampu indikator di adaptor mati, hubungi teknisi untuk penggantian adaptor original.',
                ],
                'need_technician' => false,
                'estimated_fix_time' => '10 Menit (Mandiri) / Penggantian Adaptor',
            ];
        }

        // RULE 2B: Lampu PON Padam / Mati (Saat Power ON dan LOS Tidak Merah) -> ONT Tidak Terhubung ke OLT
        if ($power !== 'off' && ($pon === 'off' || $pon === 'none')) {
            return [
                'rule_id' => 'RBR-02B',
                'rule_name' => 'IF Power = ON AND Lampu PON = Mati THEN ONT Tidak Terdaftar di OLT / Redaman Terputus Total',
                'diagnosis_title' => 'Lampu PON Mati – Modem Tidak Terhubung / Terdaftar ke OLT',
                'severity' => 'high',
                'badge' => 'Koneksi OLT Terputus (Tinggi)',
                'summary' => 'Lampu PON padam menandakan modem tidak mendeteksi sinyal sinkronisasi GPON dari sentral OLT TRICORE DATA MEDIA.',
                'root_causes' => [
                    'Konektor optik di port ONT roset terlepas atau tidak terkunci dengan benar.',
                    'Port ODP atau splitter optik di tiang luar sedang dalam kondisi pemutusan/perbaikan.',
                    'Profil Serial Number (GPON SN) modem belum di-provisioning atau dinonaktifkan di sisi OLT.',
                ],
                'action_steps' => [
                    'Pastikan kabel fiber kuning/hitam di belakang modem tertancap erat hingga berbunyi "klik".',
                    'Lakukan restart modem dengan mencabut adaptor selama 15 detik.',
                    'Jika setelah 5 menit lampu PON tetap mati, segera hubungi Helpdesk TRICORE untuk pengecekan jalur distribusi tiang.',
                ],
                'need_technician' => true,
                'estimated_fix_time' => '1 - 2 Jam Kerja',
            ];
        }

        // RULE 3: OLT Registration Failure / Sinyal Optik Lemah (PON Berkedip)
        if ($pon === 'blinking_green' && $los === 'off') {
            return [
                'rule_id' => 'RBR-03',
                'rule_name' => 'IF Lampu PON = Berkedip Hijau AND Lampu LOS = Mati THEN Registrasi OLT Gagal / Redaman Marginal',
                'diagnosis_title' => 'Modem Gagal Melakukan Autentikasi ke OLT (PON Syncing)',
                'severity' => 'high',
                'badge' => 'Kendala Autentikasi Jaringan',
                'summary' => 'Modem menerima cahaya optik, namun gagal handshake atau sinkronisasi dengan OLT TRICORE karena redaman mendekati batas toleransi (-25 dBm s/d -28 dBm) atau Serial Number (GPON SN) belum terotorisasi.',
                'root_causes' => [
                    'Konektor optik kotor atau mikro-bending pada kabel dropcore.',
                    'Perubahan konfigurasi VLAN/GPON profile pada OLT TRICORE.',
                    'Perangkat ONT baru belum di-whitelist oleh sistem manajemen OLT.',
                ],
                'action_steps' => [
                    'Restart modem dengan mencabut adaptor selama 15 detik.',
                    'Bersihkan ujung konektor SC-UPC menggunakan alkohol optik jika memiliki perlengkapan.',
                    'Laporkan nomor Serial Number (SN) yang tertera di stiker bawah modem ke helpdesk TRICORE.',
                ],
                'need_technician' => true,
                'estimated_fix_time' => '30 Menit - 1 Jam',
            ];
        }

        // RULE 4: Layanan Terisolir Billing (PON Hijau Solid, tapi Status Customer Isolated / No Internet)
        if ($customer && $customer->status === 'isolated') {
            return [
                'rule_id' => 'RBR-04',
                'rule_name' => 'IF Status Pelanggan = Isolated THEN Layanan Terisolir Billing',
                'diagnosis_title' => 'Layanan Internet Terisolir Otomatis (Tunggakan Tagihan)',
                'severity' => 'high',
                'badge' => 'Isolir Finansial / Tagihan',
                'summary' => 'Jaringan fisik dan fiber optik dalam kondisi 100% normal, namun akses internet dinonaktifkan otomatis oleh sistem billing TRINET-BILL karena melewati batas jatuh tempo.',
                'root_causes' => [
                    'Tagihan bulanan belum terverifikasi lunas di sistem billing.',
                    'Pembayaran telah ditransfer namun belum mengirimkan bukti konfirmasi pembayaran ke CS.',
                ],
                'action_steps' => [
                    'Cek rincian invoice melalui menu Cek Tagihan di website.',
                    'Lakukan pembayaran via transfer Bank BCA: 046-889-2311 / BRI a.n TRICORE DATA MEDIA.',
                    'Kirimkan foto bukti transfer ke WhatsApp Helpdesk TRICORE untuk pembukaan isolir instan (kurang dari 5 menit).',
                ],
                'need_technician' => false,
                'estimated_fix_time' => 'Instan (< 5 Menit setelah Konfirmasi Bayar)',
            ];
        }

        // RULE 5: Interferensi WiFi Kanal 2.4 GHz (Internet Lambat / Speed Drop)
        if ($issueType === 'slow' && $wifi === 'on') {
            return [
                'rule_id' => 'RBR-05',
                'rule_name' => 'IF Gejala = Internet Lambat AND Koneksi = WiFi THEN Interferensi Frekuensi 2.4 GHz',
                'diagnosis_title' => 'Interferensi Frekuensi Gelombang WiFi 2.4 GHz',
                'severity' => 'medium',
                'badge' => 'Optimasi Sinyal Nirkabel',
                'summary' => 'Jalur fiber optik normal, namun transmisi udara WiFi mengalami tabrakan frekuensi (co-channel interference) dengan router tetangga atau perangkat Bluetooth/Microwave.',
                'root_causes' => [
                    'Saluran (Channel) WiFi router tumpang-tindih dengan sinyal WiFi sekitar.',
                    'Posisi router terhalang tembok beton tebal, cermin, atau dekat barang elektronik besar.',
                    'Terlalu banyak perangkat aktif yang melakukan streaming 4K / download secara simultan.',
                ],
                'action_steps' => [
                    'Beralih ke SSID WiFi frekuensi 5 GHz (biasanya berakhiran nama _5G) untuk kecepatan maksimal bebas hambatan.',
                    'Buka IP router 192.168.1.1, ubah pengaturan WiFi Channel ke Channel 1, 6, atau 11 dengan Channel Width 20 MHz.',
                    'Posisikan router di tempat terbuka dengan ketinggian minimal 1,5 meter dari lantai.',
                    'Lakukan restart modem untuk membersihkan memori NAT router.',
                ],
                'need_technician' => false,
                'estimated_fix_time' => '10 Menit (Panduan Mandiri)',
            ];
        }

        // RULE 6: Latensi Ping Tinggi & Packet Loss (Game Lag / RTO)
        if ($issueType === 'lag') {
            return [
                'rule_id' => 'RBR-06',
                'rule_name' => 'IF Gejala = Ping Tinggi / RTO / Game Lag THEN Latensi Routing / Jitter WiFi',
                'diagnosis_title' => 'Jitter Latensi WiFi & Packet Loss pada Sesi Gaming',
                'severity' => 'medium',
                'badge' => 'Optimasi Gaming / Latensi',
                'summary' => 'Sinyal nirkabel mengalami fluktuasi paket data (jitter) yang menyebabkan lonjakan ping (spike) saat bermain game online (MLBB, Valorant, PUBG, FF).',
                'root_causes' => [
                    'Fluktuasi sinyal nirkabel WiFi 2.4 GHz pada perangkat seluler.',
                    'Adanya aplikasi latar belakang (Google Photos, iCloud, Windows Update) yang mengunggah data.',
                    'Cache DNS pada perangkat lokal belum diperbarui.',
                ],
                'action_steps' => [
                    'Gunakan kabel LAN RJ-45 langsung ke PC/Laptop untuk latensi 0% packet loss.',
                    'Ubah DNS di perangkat ke Cloudflare (1.1.1.1 & 1.0.0.1) untuk rute server game tercepat.',
                    'Gunakan frekuensi WiFi 5 GHz jika menggunakan smartphone.',
                    'Aktifkan mode QoS di router untuk memprioritaskan paket game.',
                ],
                'need_technician' => false,
                'estimated_fix_time' => '5 Menit (Mandiri)',
            ];
        }

        // RULE 7: DNS Resolver Error / Tersambung Tanpa Internet
        if ($issueType === 'dns' || $issueType === 'no_internet') {
            return [
                'rule_id' => 'RBR-07',
                'rule_name' => 'IF Indikator PON/LAN = Normal AND Gejala = No Internet Access THEN DNS Cache Corrupted',
                'diagnosis_title' => 'Kegagalan Resolusi DNS Lokal / IP Lease Conflict',
                'severity' => 'medium',
                'badge' => 'Konfigurasi IP & DNS',
                'summary' => 'Perangkat tersambung ke WiFi namun sistem operasi menampilkan ikon tanda seru / "No Internet Access".',
                'root_causes' => [
                    'Cache DNS di smartphone atau laptop macet/korup.',
                    'Konfigurasi IP address lokal mengalami konflik dengan perangkat lain.',
                ],
                'action_steps' => [
                    'Lakukan "Lupakan Jaringan" (Forget Network) pada WiFi di HP, lalu sambungkan kembali.',
                    'Restart router ONT selama 10 detik untuk memperbarui tabel DHCP.',
                    'Di laptop, buka Command Prompt dan ketik: ipconfig /flushdns lalu tekan Enter.',
                    'Atur DNS manual perangkat ke 1.1.1.1 atau 8.8.8.8.',
                ],
                'need_technician' => false,
                'estimated_fix_time' => '5 - 10 Menit (Mandiri)',
            ];
        }

        // DEFAULT RULE: Kondisi Jaringan Normal / Optimasi Rutin
        return [
            'rule_id' => 'RBR-00',
            'rule_name' => 'IF Semua Lampu Indikator = Normal THEN Jaringan Prima',
            'diagnosis_title' => 'Jaringan Fiber Optik & Perangkat ONT Beroperasi Normal',
            'severity' => 'low',
            'badge' => 'Jaringan Berstatus Prima',
            'summary' => 'Seluruh parameter indikator fisik modem ONT (Power, PON, WLAN, LAN) menunjukkan status optimal dan terhubung ke OLT TRICORE DATA MEDIA.',
            'root_causes' => [
                'Tidak terdeteksi adanya gangguan transmisi optik atau kendala suplai daya.',
            ],
            'action_steps' => [
                'Lakukan restart modem secara berkala 1 minggu sekali agar kinerja routing tetap segar.',
                'Ganti kata sandi WiFi secara rutin setiap 3 bulan untuk mencegah penggunaan tanpa izin.',
                'Hubungi Customer Service jika merasakan kendala kecepatan yang tidak biasa.',
            ],
            'need_technician' => false,
            'estimated_fix_time' => 'Kondisi Normal',
        ];
    }
}
