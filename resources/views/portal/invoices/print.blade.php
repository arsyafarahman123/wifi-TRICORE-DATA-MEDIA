<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice & Kuitansi Resmi - {{ $invoice->invoice_number }} | TRICORE DATA MEDIA</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1e293b;
            background: #f8fafc;
            margin: 0;
            padding: 24px;
        }
        .invoice-card {
            max-width: 750px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #06b6d4;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .brand h1 {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
            letter-spacing: 1px;
        }
        .brand span {
            color: #0891b2;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            display: block;
        }
        .company-info {
            font-size: 12px;
            color: #64748b;
            line-height: 1.5;
            margin-top: 6px;
        }
        .invoice-title {
            text-align: right;
        }
        .invoice-title h2 {
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
        }
        .invoice-title p {
            font-family: monospace;
            font-size: 14px;
            color: #0891b2;
            font-weight: 700;
            margin: 4px 0 0 0;
        }
        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 30px;
            font-size: 12px;
        }
        .details-box h3 {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: #94a3b8;
            letter-spacing: 1px;
            margin: 0 0 8px 0;
        }
        .details-box p {
            margin: 2px 0;
            line-height: 1.5;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            margin-bottom: 30px;
        }
        th {
            background: #f1f5f9;
            color: #475569;
            text-align: left;
            padding: 12px;
            font-weight: 700;
            border-top: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
        }
        td {
            padding: 14px 12px;
            border-bottom: 1px solid #f1f5f9;
        }
        .total-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 30px;
        }
        .total-box {
            width: 280px;
            border-top: 2px solid #e2e8f0;
            padding-top: 10px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            padding: 4px 0;
        }
        .total-grand {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            border-top: 1px solid #cbd5e1;
            padding-top: 8px;
            margin-top: 6px;
        }
        .stamp {
            display: inline-block;
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .stamp-paid {
            background: #ecfdf5;
            color: #059669;
            border: 2px solid #10b981;
        }
        .stamp-unpaid {
            background: #fff1f2;
            color: #e11d48;
            border: 2px solid #f43f5e;
        }
        .footer-note {
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
            font-size: 11px;
            color: #64748b;
            text-align: center;
            line-height: 1.6;
        }
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-bottom: 30px;
        }
        @media (max-width: 640px) {
            body {
                padding: 12px;
            }
            .invoice-card {
                padding: 20px 16px;
                border-radius: 12px;
            }
            .header {
                flex-direction: column;
                gap: 16px;
                align-items: flex-start;
            }
            .invoice-title {
                text-align: left;
            }
            .details-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            .details-box-right {
                text-align: left !important;
            }
            .total-section {
                justify-content: stretch;
            }
            .total-box {
                width: 100%;
            }
        }
        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .invoice-card {
                border: none;
                box-shadow: none;
                padding: 20px;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div class="no-print" style="max-width: 750px; margin: 0 auto 16px auto; display: flex; justify-content: space-between; align-items: center; gap: 8px; flex-wrap: wrap;">
        <button onclick="window.history.back()" style="padding: 10px 16px; background: #334155; color: #fff; font-weight: 700; border: none; border-radius: 8px; cursor: pointer; font-size: 13px;">
            &larr; Kembali
        </button>
        <button onclick="window.print()" style="padding: 10px 20px; background: #0891b2; color: #fff; font-weight: 700; border: none; border-radius: 8px; cursor: pointer; font-size: 13px;">
            🖨️ Cetak / Simpan PDF
        </button>
    </div>

    <div class="invoice-card">
        <!-- Header -->
        <div class="header">
            <div class="brand">
                <h1>TRICORE</h1>
                <span>DATA MEDIA</span>
                <div class="company-info">
                    Internet Fiber Optic ISP Purwokerto<br>
                    Jl. KAV. Gelora Indah II, Gg. Renang, Purwokerto Timur<br>
                    WhatsApp: +62 821-3841-3292 | support@tricoredatamedia.net
                </div>
            </div>
            <div class="invoice-title">
                <h2>INVOICE & KUITANSI</h2>
                <p>{{ $invoice->invoice_number }}</p>
                <div style="margin-top: 10px;">
                    @if($invoice->status === 'paid')
                        <span class="stamp stamp-paid">LUNAS</span>
                    @else
                        <span class="stamp stamp-unpaid">BELUM BAYAR</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Details -->
        <div class="details-grid">
            <div class="details-box">
                <h3>Ditagihkan Kepada:</h3>
                <p><strong>{{ $invoice->customer->name }}</strong></p>
                <p>No. Pelanggan: <strong style="font-family: monospace;">{{ $invoice->customer->customer_code }}</strong></p>
                <p>WhatsApp: {{ $invoice->customer->phone }}</p>
                <p>{{ $invoice->customer->address }}</p>
                <p>{{ $invoice->customer->district }}, Purwokerto</p>
            </div>
            <div class="details-box details-box-right" style="text-align: right;">
                <h3>Informasi Tagihan:</h3>
                <p>Periode: <strong>{{ $invoice->billing_month }}</strong></p>
                <p>Tanggal Tagihan: {{ $invoice->created_at->format('d/m/Y') }}</p>
                <p>Jatuh Tempo: <strong>{{ $invoice->due_date ? $invoice->due_date->format('d/m/Y') : '-' }}</strong></p>
                @if($invoice->status === 'paid')
                    <p>Tanggal Pembayaran: <strong>{{ $invoice->paid_at ? $invoice->paid_at->format('d/m/Y H:i') : '-' }} WIB</strong></p>
                    <p>Metode: <strong>{{ $invoice->payment_method ?? 'Mitra WiFi' }}</strong></p>
                @endif
            </div>
        </div>

        <!-- Table of Items -->
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Deskripsi Layanan</th>
                        <th>Kecepatan</th>
                        <th>Periode</th>
                        <th style="text-align: right;">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <strong>Langganan WiFi Fiber Optic TRICORE</strong><br>
                            <span style="font-size: 11px; color: #64748b;">{{ $invoice->package->name }} (Unlimited Kuota, Tanpa FUP)</span>
                        </td>
                        <td>{{ $invoice->package->speed_mbps }} Mbps Simetris</td>
                        <td>{{ $invoice->billing_month }}</td>
                        <td style="text-align: right; font-weight: 700; font-family: monospace;">{{ $invoice->formatted_amount }}</td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Sewa Modem Router Wi-Fi Fiber</strong><br>
                            <span style="font-size: 11px; color: #64748b;">Fasilitas perangkat ONT aktif</span>
                        </td>
                        <td>-</td>
                        <td>1 Bulan</td>
                        <td style="text-align: right; font-weight: 700; font-family: monospace; color: #059669;">GRATIS</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Total Section -->
        <div class="total-section">
            <div class="total-box">
                <div class="total-row">
                    <span style="color: #64748b;">Subtotal:</span>
                    <span style="font-family: monospace;">{{ $invoice->formatted_amount }}</span>
                </div>
                <div class="total-row">
                    <span style="color: #64748b;">Biaya Pemasangan / Sewa:</span>
                    <span style="font-family: monospace; color: #059669;">Rp0</span>
                </div>
                <div class="total-row total-grand">
                    <span>Total Pembayaran:</span>
                    <span style="font-family: monospace; color: #0891b2;">{{ $invoice->formatted_amount }}</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer-note">
            Terima kasih telah mempercayakan kebutuhan internet Anda kepada <strong>TRICORE DATA MEDIA</strong>.<br>
            Kuitansi ini adalah bukti pembayaran sah yang dihasilkan secara elektronik oleh sistem TRICORE DATA MEDIA.<br>
            Bantuan & Layanan Pelanggan 24 Jam: <strong>+62 821-3841-3292</strong> | <strong>support@tricoredatamedia.net</strong>
        </div>
    </div>

</body>
</html>
