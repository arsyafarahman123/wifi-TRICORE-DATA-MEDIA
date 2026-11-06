<?php

namespace App\Services;

use App\Models\Package;
use Illuminate\Support\Collection;

class PackageRecommendationEngine
{
    /**
     * Budget ranges
     */
    public const BUDGET_LOW = 'low';         // < 130.000
    public const BUDGET_MEDIUM = 'medium';   // 130.000 - 160.000
    public const BUDGET_HIGH = 'high';       // 160.000 - 190.000
    public const BUDGET_UNLIMITED = 'ultra'; // > 190.000

    /**
     * Devices ranges
     */
    public const DEVICES_1_3 = '1_3';
    public const DEVICES_3_5 = '3_5';
    public const DEVICES_5_8 = '5_8';
    public const DEVICES_8_12 = '8_12';
    public const DEVICES_12_PLUS = '12_plus';

    /**
     * Main Activities
     */
    public const ACT_BROWSING = 'browsing';     // Browsing & Medsos
    public const ACT_STUDY = 'study';           // Belajar & Tugas
    public const ACT_STREAMING = 'streaming';   // Streaming HD/4K
    public const ACT_GAMING = 'gaming';         // Gaming Online
    public const ACT_WFX = 'wfx';               // WFH & Video Conference
    public const ACT_INTENSIVE = 'intensive';   // Intensif & Banyak Perangkat

    /**
     * Connection Priority
     */
    public const PRIORITY_STANDARD = 'standard';
    public const PRIORITY_HIGH_SPEED = 'high_speed';

    /**
     * Run Rule-Based Matching Algorithm
     *
     * @param string $budget
     * @param string $devices
     * @param string $activity
     * @param string $priority
     * @return array
     */
    public function evaluate(string $budget, string $devices, string $activity, string $priority): array
    {
        // 1. Fetch all active packages dynamically from the database
        $packages = Package::where('is_active', true)->orderBy('price', 'asc')->get();

        if ($packages->isEmpty()) {
            return [
                'success' => false,
                'message' => 'Belum ada paket internet aktif di sistem.',
            ];
        }

        // Maximum budget ceiling according to user choice
        $maxBudget = match ($budget) {
            self::BUDGET_LOW => 125000,
            self::BUDGET_MEDIUM => 155000,
            self::BUDGET_HIGH => 185000,
            default => 999999999,
        };

        // Determine recommended minimum speed based on Devices & Activity (Rule-Based IF-ELSE)
        $minSpeedRequirement = 15;

        // Rule for Devices
        if ($devices === self::DEVICES_3_5) {
            $minSpeedRequirement = max($minSpeedRequirement, 20);
        } elseif ($devices === self::DEVICES_5_8) {
            $minSpeedRequirement = max($minSpeedRequirement, 25);
        } elseif ($devices === self::DEVICES_8_12) {
            $minSpeedRequirement = max($minSpeedRequirement, 35);
        } elseif ($devices === self::DEVICES_12_PLUS) {
            $minSpeedRequirement = max($minSpeedRequirement, 50);
        }

        // Rule for Activities
        if ($activity === self::ACT_STREAMING) {
            $minSpeedRequirement = max($minSpeedRequirement, 20);
        } elseif ($activity === self::ACT_GAMING || $activity === self::ACT_WFX) {
            $minSpeedRequirement = max($minSpeedRequirement, 25);
        } elseif ($activity === self::ACT_INTENSIVE) {
            $minSpeedRequirement = max($minSpeedRequirement, 35);
        }

        // Rule for Priority (High Speed / High Stability demands)
        if ($priority === self::PRIORITY_HIGH_SPEED) {
            $minSpeedRequirement = max($minSpeedRequirement, 25);
        }

        // Evaluate each package with explicit Rule Scoring (0 - 100%)
        $scoredPackages = [];

        foreach ($packages as $pkg) {
            $score = 0;
            $reasons = [];

            // OVERRIDE ATURAN KHUSUS: Untuk 12+ perangkat / bisnis, wajib prioritaskan paket berkecepatan tinggi (35 - 50 Mbps)
            // agar pengguna banyak tidak salah direkomendasikan paket rendah hanya karena faktor anggaran
            if ($devices === self::DEVICES_12_PLUS) {
                if ($pkg->speed_mbps >= 50) {
                    $score += 50;
                    $reasons[] = "Kapasitas bandwidth 50 Mbps sangat ideal dan handal untuk menangani 12+ perangkat secara simultan.";
                } elseif ($pkg->speed_mbps >= 35) {
                    $score += 35;
                    $reasons[] = "Kecepatan 35 Mbps sebagai opsi alternatif untuk 12+ perangkat aktif.";
                } else {
                    $score += 10;
                    $reasons[] = "Kecepatan {$pkg->speed_mbps} Mbps kurang memadai untuk beban 12+ perangkat secara bersamaan.";
                }

                // Bobot anggaran disesuaikan untuk skala 12+ perangkat
                if ($pkg->price <= $maxBudget) {
                    $score += 30;
                } else {
                    $score += 15; // Berikan kelonggaran agar paket 50 Mbps tetap unggul
                }

                $deviceFit = $this->evaluateDeviceFit($pkg->speed_mbps, $devices);
                $score += $deviceFit['score'];

                if ($pkg->is_popular) {
                    $score = min(100, $score + 2);
                }

                $scoredPackages[] = [
                    'package' => $pkg,
                    'score' => min(100, max(10, $score)),
                    'is_within_budget' => ($pkg->price <= $maxBudget),
                    'reasons' => $reasons,
                ];
                continue;
            }

            // Condition 1: Budget Fitness (Weight: 40%)
            if ($pkg->price <= $maxBudget) {
                $score += 40;
                $reasons[] = "Harga {$pkg->formatted_price}/bulan pas sesuai alokasi anggaran Anda.";
            } else {
                // If over budget, severely penalize (Rule: Paket melebihi anggaran tidak boleh menjadi rekomendasi utama)
                $score += 5;
                $diff = $pkg->price - $maxBudget;
                $reasons[] = "Harga melebihi anggaran yang dipilih (+Rp " . number_format($diff, 0, ',', '.') . ").";
            }

            // Condition 2: Speed vs Needs Fitness (Weight: 35%)
            if ($pkg->speed_mbps >= $minSpeedRequirement) {
                $score += 35;
                $reasons[] = "Kecepatan {$pkg->speed_mbps} Mbps sangat mencukupi kebutuhan " . $this->getActivityLabel($activity) . ".";
            } else {
                // Partial score if speed is slightly lower
                $speedRatio = $pkg->speed_mbps / max($minSpeedRequirement, 1);
                $partial = round($speedRatio * 20);
                $score += $partial;
                $reasons[] = "Kecepatan {$pkg->speed_mbps} Mbps sedikit di bawah kebutuhan optimal {$minSpeedRequirement} Mbps untuk " . $this->getDeviceLabel($devices) . ".";
            }

            // Condition 3: Device Capacity Fitness (Weight: 25%)
            $deviceFit = $this->evaluateDeviceFit($pkg->speed_mbps, $devices);
            $score += $deviceFit['score'];
            if (!empty($deviceFit['note'])) {
                $reasons[] = $deviceFit['note'];
            }

            // Popular bonus +3%
            if ($pkg->is_popular) {
                $score = min(100, $score + 3);
            }

            $score = min(100, max(10, $score));

            $scoredPackages[] = [
                'package' => $pkg,
                'score' => $score,
                'is_within_budget' => ($pkg->price <= $maxBudget),
                'reasons' => $reasons,
            ];
        }

        // Sort packages by score descending, then by price ascending
        usort($scoredPackages, function ($a, $b) {
            if ($a['is_within_budget'] !== $b['is_within_budget']) {
                return $b['is_within_budget'] <=> $a['is_within_budget'];
            }
            if ($a['score'] === $b['score']) {
                return $a['package']->price <=> $b['package']->price;
            }
            return $b['score'] <=> $a['score'];
        });

        // Top recommendation
        $primary = $scoredPackages[0];
        $alternatives = array_slice($scoredPackages, 1, 2);

        // Check if no package meets budget
        $isOverBudgetConstraint = !$primary['is_within_budget'];

        return [
            'success' => true,
            'criteria' => [
                'budget' => $budget,
                'budget_label' => $this->getBudgetLabel($budget),
                'devices' => $devices,
                'devices_label' => $this->getDeviceLabel($devices),
                'activity' => $activity,
                'activity_label' => $this->getActivityLabel($activity),
                'priority' => $priority,
                'priority_label' => $priority === self::PRIORITY_HIGH_SPEED ? 'Kecepatan & Kestabilan Tinggi' : 'Aktivitas Biasa / Normal',
                'min_speed_requirement' => $minSpeedRequirement,
            ],
            'is_over_budget_constraint' => $isOverBudgetConstraint,
            'primary' => [
                'id' => $primary['package']->id,
                'name' => $primary['package']->name,
                'speed_mbps' => $primary['package']->speed_mbps,
                'price' => $primary['package']->price,
                'formatted_price' => $primary['package']->formatted_price,
                'badge' => $primary['package']->badge,
                'description' => $primary['package']->description,
                'device_recommendation' => $primary['package']->device_recommendation,
                'match_percentage' => $primary['score'],
                'reasons' => $primary['reasons'],
                'order_url' => route('home') . '#daftar',
            ],
            'alternatives' => array_map(function ($item) {
                return [
                    'id' => $item['package']->id,
                    'name' => $item['package']->name,
                    'speed_mbps' => $item['package']->speed_mbps,
                    'price' => $item['package']->price,
                    'formatted_price' => $item['package']->formatted_price,
                    'badge' => $item['package']->badge,
                    'description' => $item['package']->description,
                    'match_percentage' => $item['score'],
                    'order_url' => route('home') . '#daftar',
                ];
            }, $alternatives),
        ];
    }

    private function evaluateDeviceFit(int $speed, string $devices): array
    {
        return match ($devices) {
            self::DEVICES_1_3 => [
                'score' => 25,
                'note' => 'Kapasitas bandwidth sangat ideal untuk 1 - 3 perangkat tanpa berebut koneksi.',
            ],
            self::DEVICES_3_5 => ($speed >= 20) ? [
                'score' => 25,
                'note' => 'Cocok mendukung koneksi bersama 3 - 5 perangkat sekaligus.',
            ] : [
                'score' => 15,
                'note' => 'Bisa untuk 3 - 5 perangkat dengan prioritas penggunaan.',
            ],
            self::DEVICES_5_8 => ($speed >= 25) ? [
                'score' => 25,
                'note' => 'Dual-band router siap melayani 5 - 8 perangkat serentak.',
            ] : [
                'score' => 10,
                'note' => 'Disarankan kecepatan lebih tinggi untuk kelancaran 5 - 8 perangkat.',
            ],
            self::DEVICES_8_12 => ($speed >= 35) ? [
                'score' => 25,
                'note' => 'Bandwidth besar 35+ Mbps sangat aman untuk 8 - 12 perangkat aktif.',
            ] : [
                'score' => 8,
                'note' => 'Mungkin terjadi antrian traffic jika 8 - 12 perangkat streaming serentak.',
            ],
            self::DEVICES_12_PLUS => ($speed >= 50) ? [
                'score' => 25,
                'note' => 'Dedicated 50 Mbps untuk multi-user 12+ perangkat / kantor / kafe.',
            ] : [
                'score' => 5,
                'note' => 'Kurang direkomendasikan untuk lebih dari 12 perangkat aktif bersamaan.',
            ],
            default => [
                'score' => 15,
                'note' => '',
            ],
        };
    }

    public function getBudgetLabel(string $budget): string
    {
        return match ($budget) {
            self::BUDGET_LOW => 'Hemat (< Rp 130.000/bln)',
            self::BUDGET_MEDIUM => 'Standar (Rp 130.000 - Rp 160.000/bln)',
            self::BUDGET_HIGH => 'Menengah (Rp 160.000 - Rp 190.000/bln)',
            self::BUDGET_UNLIMITED => 'Bisnis / Pro (> Rp 190.000/bln)',
            default => 'Fleksibel',
        };
    }

    public function getDeviceLabel(string $devices): string
    {
        return match ($devices) {
            self::DEVICES_1_3 => '1 - 3 Perangkat (Pribadi / Kos)',
            self::DEVICES_3_5 => '3 - 5 Perangkat (Keluarga Kecil)',
            self::DEVICES_5_8 => '5 - 8 Perangkat (Keluarga Sedang)',
            self::DEVICES_8_12 => '8 - 12 Perangkat (Rumah Besar)',
            self::DEVICES_12_PLUS => '12+ Perangkat (Kafe / Kantor / Kosan)',
            default => 'Beberapa Perangkat',
        };
    }

    public function getActivityLabel(string $activity): string
    {
        return match ($activity) {
            self::ACT_BROWSING => 'Browsing & Media Sosial',
            self::ACT_STUDY => 'Belajar & Tugas Sekolah/Kuliah',
            self::ACT_STREAMING => 'Streaming Film HD & Video 4K',
            self::ACT_GAMING => 'Gaming Online Kompetitif (Low Latency)',
            self::ACT_WFX => 'Video Conference (Zoom/Meet) & WFH',
            self::ACT_INTENSIVE => 'Penggunaan Intensif & Download File Besar',
            default => 'Aktivitas Internet Harian',
        };
    }
}

