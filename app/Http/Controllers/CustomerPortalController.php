<?php

namespace App\Http\Controllers;

use App\Models\CoverageArea;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Package;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerPortalController extends Controller
{
    /**
     * Check billing status by customer code or phone number
     */
    public function checkBill(Request $request): View|RedirectResponse
    {
        $query = trim($request->input('search_query', $request->query('search_query', $request->query('q', ''))));

        if (empty($query)) {
            if ($request->isMethod('GET')) {
                return view('portal.cek-tagihan');
            }

            return redirect()->route('bill.check')
                ->with('error', 'Silakan masukkan Nomor Pelanggan (contoh: 89900000001) atau Nomor WhatsApp.');
        }

        $cleanPhone = preg_replace('/[^0-9]/', '', $query);
        $upperCode = strtoupper($query);

        $customer = Customer::with(['package', 'invoices' => function ($q) {
            $q->latest();
        }])
            ->where('customer_code', $query)
            ->orWhere('customer_code', $upperCode)
            ->orWhere('phone', $query)
            ->when(!empty($cleanPhone), function ($q) use ($cleanPhone) {
                $q->orWhere('phone', $cleanPhone);
            })
            ->first();

        if (! $customer) {
            $errorMsg = 'Data pelanggan dengan ID / Nomor WA "'.$query.'" tidak ditemukan. Pastikan nomor yang dimasukkan sudah terdaftar atau hubungi CS WhatsApp kami.';

            if ($request->isMethod('GET') && ! $request->has('from_home')) {
                return view('portal.cek-tagihan', [
                    'searchQuery' => $query,
                    'errorMessage' => $errorMsg,
                ]);
            }

            return redirect()->route('home', ['#cek-tagihan'])
                ->with('error', $errorMsg)
                ->withInput();
        }

        $latestInvoice = $customer->invoices->first();

        // Calculate unpaid bills including previous arrears
        $unpaidInvoices = $customer->invoices->whereIn('status', ['unpaid', 'overdue', 'cancelled']);
        $totalUnpaidAmount = $unpaidInvoices->sum('amount');
        $hasArrears = $unpaidInvoices->count() > 1;

        return view('portal.bill-result', compact('customer', 'latestInvoice', 'unpaidInvoices', 'totalUnpaidAmount', 'hasArrears'));
    }

    /**
     * View specific invoice by code
     */
    public function viewBill(string $code): View|RedirectResponse
    {
        $targetCode = $code;

        $customer = Customer::with(['package', 'invoices' => function ($q) {
            $q->latest();
        }])
            ->where('customer_code', $targetCode)
            ->firstOrFail();

        $latestInvoice = $customer->invoices->first();

        // Calculate unpaid bills including previous arrears
        $unpaidInvoices = $customer->invoices->whereIn('status', ['unpaid', 'overdue', 'cancelled']);
        $totalUnpaidAmount = $unpaidInvoices->sum('amount');
        $hasArrears = $unpaidInvoices->count() > 1;

        return view('portal.bill-result', compact('customer', 'latestInvoice', 'unpaidInvoices', 'totalUnpaidAmount', 'hasArrears'));
    }

    /**
     * Handle new customer online registration
     */
    public function register(Request $request): View|RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => ['required', 'string', 'regex:/^[0-9]{10,15}$/'],
            'email' => 'nullable|email|max:255',
            'identity_number' => ['nullable', 'string', 'regex:/^[0-9]{16}$/'],
            'package_id' => 'required|exists:packages,id',
            'district' => 'required|string|max:100',
            'subdistrict' => 'nullable|string|max:100',
            'address' => 'required|string|max:500',
            'notes' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
            'phone.regex' => 'Nomor WhatsApp hanya boleh berupa angka (10-15 digit) tanpa huruf atau simbol.',
            'identity_number.regex' => 'NIK (Nomor Induk Kependudukan) harus tepat 16 digit angka.',
            'package_id.required' => 'Silakan pilih paket internet yang diinginkan.',
            'district.required' => 'Kecamatan wajib dipilih.',
            'address.required' => 'Alamat lengkap pemasangan wajib diisi.',
        ]);

        // Generate customer code: 11 digit angka (3 digit spesial 899 + 8 digit urut/bebas)
        $lastCustomer = Customer::latest('id')->first();
        $nextId = $lastCustomer ? ($lastCustomer->id + 1) : 1;
        $customerCode = '899'.str_pad($nextId, 8, '0', STR_PAD_LEFT);

        $package = Package::findOrFail($validated['package_id']);

        $customer = Customer::create([
            'customer_code' => $customerCode,
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'identity_number' => $validated['identity_number'] ?? null,
            'address' => $validated['address'],
            'district' => $validated['district'],
            'subdistrict' => $validated['subdistrict'] ?? null,
            'package_id' => $package->id,
            'status' => 'pending',
            'registered_by' => 'online',
            'notes' => $validated['notes'] ?? null,
        ]);

        // Generate initial registration bill
        $invoiceNumber = 'INV-'.date('Ym').'-'.str_pad($customer->id, 3, '0', STR_PAD_LEFT);
        $periodStart = now();
        $periodEnd = now()->addDays(30);
        $dueDate = now()->addDays(7);

        $invoice = Invoice::create([
            'invoice_number' => $invoiceNumber,
            'customer_id' => $customer->id,
            'package_id' => $package->id,
            'billing_month' => 'Pemasangan Baru - '.now()->isoFormat('MMMM Y'),
            'period_start' => $periodStart,
            'period_end' => $periodEnd,
            'due_date' => $dueDate,
            'amount' => $package->price,
            'status' => 'unpaid',
            'notes' => 'Tagihan awal berlangganan paket '.$package->name.' ('.$package->speed_mbps.' Mbps).',
        ]);

        // Pre-build WhatsApp notification message
        $waMessage = 'Halo TRICORE DATA MEDIA, saya telah melakukan pendaftaran online pasang baru WiFi:%0A%0A'
            .'• No Pendaftaran: '.$customer->customer_code.'%0A'
            .'• Nama: '.urlencode($customer->name).'%0A'
            .'• Paket: '.urlencode($package->name.' ('.$package->speed_mbps.' Mbps - '.$package->formatted_price.'/bln)').'%0A'
            .'• Alamat: '.urlencode($customer->address.', '.($customer->subdistrict ? $customer->subdistrict.', ' : '').$customer->district).'%0A'
            .'• No WhatsApp: '.urlencode($customer->phone).'%0A%0A'
            .'Mohon info jadwal survei teknisi dan instalasi. Terima kasih!';

        $waUrl = 'https://wa.me/6282138413292?text='.$waMessage;

        return redirect()->route('payment.page', ['invoice_number' => $invoice->invoice_number]);
    }

    /**
     * Show payment page with Bank Neo Commerce details & proof upload
     */
    public function showPaymentPage(string $invoiceNumber): View|RedirectResponse
    {
        $invoice = Invoice::with(['customer', 'package'])
            ->where('invoice_number', $invoiceNumber)
            ->firstOrFail();

        $bankInfo = [
            'bank_name' => 'Bank Neo Commerce',
            'account_number' => '5859459401627129',
            'account_holder' => 'TRICORE DATA MEDIA',
        ];

        return view('portal.payment', compact('invoice', 'bankInfo'));
    }

    /**
     * Handle payment proof upload by customer
     */
    public function uploadPaymentProof(Request $request, string $invoiceNumber): RedirectResponse
    {
        $invoice = Invoice::where('invoice_number', $invoiceNumber)->firstOrFail();

        // Security check: cannot upload to an invoice that is already paid
        if ($invoice->status === 'paid') {
            return back()->with('error', 'Tagihan ini sudah berstatus LUNAS. Tidak perlu mengunggah bukti pembayaran kembali.');
        }

        $request->validate([
            'payment_proof' => 'required|file|mimes:jpeg,png,jpg,webp|max:5120',
            'payment_reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');

            // Additional Security: Validate real MIME type and extension to prevent polyglot / webshell upload
            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
            $fileMime = $file->getMimeType();
            $extension = strtolower($file->getClientOriginalExtension());
            $allowedExtensions = ['jpeg', 'jpg', 'png', 'webp'];

            if (!in_array($fileMime, $allowedMimes) || !in_array($extension, $allowedExtensions)) {
                return back()->with('error', 'Format file tidak diizinkan! Hanya diperbolehkan file gambar JPG, PNG, atau WEBP.');
            }

            // Secure randomized filename
            $filename = 'proof_' . preg_replace('/[^A-Za-z0-9_-]/', '', $invoice->invoice_number) . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
            $destinationPath = public_path('uploads/proofs');
            
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            $file->move($destinationPath, $filename);

            $invoice->update([
                'payment_proof' => 'uploads/proofs/' . $filename,
                'proof_uploaded_at' => now(),
                'payment_reference' => strip_tags($request->input('payment_reference') ?: ('BNC-' . date('YmdHis'))),
                'payment_method' => 'Transfer Bank Neo Commerce',
                'notes' => strip_tags($request->input('notes') ?: $invoice->notes),
                'rejection_reason' => null, // Reset previous rejection if any
            ]);

            return back()->with('success', 'Bukti transfer Bank Neo Commerce berhasil diunggah! Pembayaran Anda sedang menunggu verifikasi oleh Admin/Owner TRICORE.');
        }

        return back()->with('error', 'Gagal mengunggah bukti transfer. Pastikan format file sesuai.');
    }

    /**
     * Check coverage address
     */
    public function checkCoverage(Request $request): View|RedirectResponse
    {
        $city = trim($request->input('city', ''));
        $areaName = trim($request->input('district', ''));
        $subdistrict = trim($request->input('subdistrict', ''));
        $fullAddress = trim($request->input('address', ''));

        // Kombinasi teks input untuk validasi wilayah kota/kabupaten
        $allText = strtolower("{$city} {$areaName} {$subdistrict} {$fullAddress}");

        // Daftar wilayah yang secara geografis berada di luar area operasional TRICORE DATA MEDIA
        $outOfRegionKeywords = ['jakarta', 'bandung', 'surabaya', 'semarang', 'yogyakarta', 'jogja', 'solo', 'surakarta', 'bogor', 'depok', 'tangerang', 'bekasi', 'cirebon', 'tegal', 'brebes', 'cilacap', 'kebumen', 'purbalingga', 'banjarnegara'];

        $isOutOfRegion = false;
        foreach ($outOfRegionKeywords as $kw) {
            // Jika user menyebutkan nama kota luar (dan bukan sekadar nama jalan)
            if (preg_match('/\b' . preg_quote($kw, '/') . '\b/i', $allText)) {
                $isOutOfRegion = true;
                break;
            }
        }

        if ($isOutOfRegion) {
            return redirect()->route('home', ['#coverage'])
                ->with('coverage_checked', true)
                ->with('is_covered', false)
                ->with('coverage_status', 'out_of_area')
                ->with('coverage_message', 'Alamat berada di luar wilayah operasional TRICORE (Banyumas / Purwokerto & Sokaraja).')
                ->with('searched_location', ($subdistrict ? $subdistrict.', ' : '').$areaName);
        }

        $areas = CoverageArea::where('is_active', true)->get();
        $isCovered = false;
        $matchedArea = null;

        if (!empty($areaName) || !empty($subdistrict)) {
            foreach ($areas as $area) {
                if (!empty($areaName) && (stripos($area->name, $areaName) !== false || stripos($areaName, $area->name) !== false)) {
                    $isCovered = true;
                    $matchedArea = $area;
                    break;
                }

                if (! empty($area->subdistricts) && is_array($area->subdistricts)) {
                    foreach ($area->subdistricts as $sub) {
                        if (!empty($subdistrict) && (stripos($sub, $subdistrict) !== false || stripos($sub, $areaName) !== false)) {
                            $isCovered = true;
                            $matchedArea = $area;
                            break 2;
                        }
                    }
                }
            }
        }

        return redirect()->route('home', ['#coverage'])
            ->with('coverage_checked', true)
            ->with('is_covered', $isCovered)
            ->with('matched_area', $matchedArea ? $matchedArea->name : null)
            ->with('searched_location', ($subdistrict ? $subdistrict.', ' : '').$areaName);
    }
}
