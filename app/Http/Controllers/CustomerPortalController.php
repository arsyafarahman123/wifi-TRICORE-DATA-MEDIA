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
        $query = trim($request->input('search_query', ''));

        if (empty($query)) {
            return redirect()->route('home', ['#cek-tagihan'])
                ->with('error', 'Silakan masukkan Nomor Pelanggan (contoh: TDM-2601) atau Nomor WhatsApp.');
        }

        $customer = Customer::with(['package', 'invoices' => function ($q) {
            $q->latest();
        }])
            ->where('customer_code', $query)
            ->orWhere('phone', $query)
            ->first();

        if (! $customer) {
            return redirect()->route('home', ['#cek-tagihan'])
                ->with('error', 'Data pelanggan dengan ID / Nomor WA "'.e($query).'" tidak ditemukan. Pastikan nomor yang dimasukkan sudah terdaftar atau hubungi CS WhatsApp kami.')
                ->withInput();
        }

        $latestInvoice = $customer->invoices->first();

        return view('portal.bill-result', compact('customer', 'latestInvoice'));
    }

    /**
     * View specific invoice by code
     */
    public function viewBill(string $code): View|RedirectResponse
    {
        $customer = Customer::with(['package', 'invoices' => function ($q) {
            $q->latest();
        }])
            ->where('customer_code', $code)
            ->firstOrFail();

        $latestInvoice = $customer->invoices->first();

        return view('portal.bill-result', compact('customer', 'latestInvoice'));
    }

    /**
     * Handle new customer online registration
     */
    public function register(Request $request): View|RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'identity_number' => 'nullable|string|max:30',
            'package_id' => 'required|exists:packages,id',
            'district' => 'required|string|max:100',
            'subdistrict' => 'nullable|string|max:100',
            'address' => 'required|string|max:500',
            'notes' => 'nullable|string|max:500',
        ]);

        // Generate customer code: TDM-YY-XXXX
        $year = date('y');
        $lastCustomer = Customer::latest('id')->first();
        $nextId = $lastCustomer ? ($lastCustomer->id + 1) : 1;
        $customerCode = 'TDM-'.$year.str_pad($nextId, 3, '0', STR_PAD_LEFT);

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

        return view('portal.registration-success', compact('customer', 'invoice', 'package', 'waUrl'));
    }

    /**
     * Check coverage address
     */
    public function checkCoverage(Request $request): View|RedirectResponse
    {
        $areaName = trim($request->input('district', ''));
        $subdistrict = trim($request->input('subdistrict', ''));

        $areas = CoverageArea::where('is_active', true)->get();
        $isCovered = false;
        $matchedArea = null;

        foreach ($areas as $area) {
            if (stripos($area->name, $areaName) !== false || stripos($areaName, $area->name) !== false) {
                $isCovered = true;
                $matchedArea = $area;
                break;
            }

            if (! empty($area->subdistricts) && is_array($area->subdistricts)) {
                foreach ($area->subdistricts as $sub) {
                    if (stripos($sub, $subdistrict) !== false || stripos($sub, $areaName) !== false) {
                        $isCovered = true;
                        $matchedArea = $area;
                        break 2;
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
