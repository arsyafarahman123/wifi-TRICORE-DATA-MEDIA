<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Package;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CustomerManagementController extends Controller
{
    public function index(Request $request): View
    {
        $query = Customer::with(['package', 'partner', 'latestInvoice']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('customer_code', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('district', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($packageId = $request->input('package_id')) {
            $query->where('package_id', $packageId);
        }

        $customers = $query->latest()->paginate(15)->withQueryString();
        $packages = Package::where('is_active', true)->get();

        return view('portal.customers.index', compact('customers', 'packages'));
    }

    public function create(): View
    {
        $packages = Package::where('is_active', true)->orderBy('sort_order')->get();

        return view('portal.customers.create', compact('packages'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'identity_number' => 'nullable|string|max:30',
            'address' => 'required|string|max:500',
            'district' => 'required|string|max:100',
            'subdistrict' => 'nullable|string|max:100',
            'package_id' => 'required|exists:packages,id',
            'status' => 'required|in:pending,active,isolated,cancelled',
            'installation_date' => 'nullable|date',
            'odp_code' => 'nullable|string|max:50',
            'ip_address' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:500',
        ]);

        $year = date('y');
        $lastCustomer = Customer::latest('id')->first();
        $nextId = $lastCustomer ? ($lastCustomer->id + 1) : 1;
        $customerCode = 'TDM-'.$year.str_pad($nextId, 3, '0', STR_PAD_LEFT);

        $package = Package::findOrFail($validated['package_id']);

        $customer = Customer::create(array_merge($validated, [
            'customer_code' => $customerCode,
            'registered_by' => Auth::user()->role === 'admin' ? 'admin' : 'mitra',
            'partner_id' => Auth::id(),
        ]));

        // Generate initial invoice
        $invoiceNumber = 'INV-'.date('Ym').'-'.str_pad($customer->id, 3, '0', STR_PAD_LEFT);
        Invoice::create([
            'invoice_number' => $invoiceNumber,
            'customer_id' => $customer->id,
            'package_id' => $package->id,
            'billing_month' => now()->isoFormat('MMMM Y'),
            'period_start' => now(),
            'period_end' => now()->addDays(30),
            'due_date' => now()->addDays(7),
            'amount' => $package->price,
            'status' => $customer->status === 'active' ? 'paid' : 'unpaid',
            'paid_at' => $customer->status === 'active' ? now() : null,
            'payment_method' => $customer->status === 'active' ? 'Tunai ke Mitra' : null,
            'received_by_user_id' => $customer->status === 'active' ? Auth::id() : null,
            'notes' => 'Tagihan awal dibuat saat pendaftaran pelanggan.',
        ]);

        return redirect()->route('portal.customers.show', $customer)
            ->with('success', "Pelanggan {$customer->name} ({$customer->customer_code}) berhasil didaftarkan!");
    }

    public function show(Customer $customer): View
    {
        $customer->load(['package', 'partner', 'invoices' => function ($q) {
            $q->latest();
        }]);

        return view('portal.customers.show', compact('customer'));
    }

    public function edit(Customer $customer): View
    {
        $packages = Package::where('is_active', true)->orderBy('sort_order')->get();

        return view('portal.customers.edit', compact('customer', 'packages'));
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'identity_number' => 'nullable|string|max:30',
            'address' => 'required|string|max:500',
            'district' => 'required|string|max:100',
            'subdistrict' => 'nullable|string|max:100',
            'package_id' => 'required|exists:packages,id',
            'status' => 'required|in:pending,active,isolated,cancelled',
            'installation_date' => 'nullable|date',
            'odp_code' => 'nullable|string|max:50',
            'ip_address' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:500',
        ]);

        $customer->update($validated);

        return redirect()->route('portal.customers.show', $customer)
            ->with('success', "Data pelanggan {$customer->name} berhasil diperbarui.");
    }

    /**
     * Quick toggle customer status (Aktifkan / Isolir / Selesai Pasang)
     */
    public function toggleStatus(Request $request, Customer $customer): RedirectResponse
    {
        $newStatus = $request->validate([
            'status' => 'required|in:active,isolated,pending,cancelled',
        ])['status'];

        $customer->update([
            'status' => $newStatus,
            'installation_date' => ($newStatus === 'active' && ! $customer->installation_date) ? now() : $customer->installation_date,
        ]);

        $statusLabel = match ($newStatus) {
            'active' => 'diaktifkan kembali',
            'isolated' => 'diisolir sementara (belum bayar)',
            'pending' => 'diubah menjadi pending survei',
            'cancelled' => 'dinonaktifkan / berhenti berlangganan',
        };

        return back()->with('success', "Layanan internet pelanggan {$customer->name} berhasil {$statusLabel}.");
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $name = $customer->name;
        $customer->delete();

        return redirect()->route('portal.customers.index')
            ->with('success', "Pelanggan {$name} berhasil dihapus dari sistem.");
    }
}
