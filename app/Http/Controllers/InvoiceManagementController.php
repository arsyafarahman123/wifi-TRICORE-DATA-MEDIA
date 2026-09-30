<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InvoiceManagementController extends Controller
{
    public function index(Request $request): View
    {
        $query = Invoice::with(['customer', 'package', 'receivedBy']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($cq) use ($search) {
                        $cq->where('name', 'like', "%{$search}%")
                            ->orWhere('customer_code', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($month = $request->input('month')) {
            $query->where('billing_month', 'like', "%{$month}%");
        }

        $invoices = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total_unpaid' => Invoice::where('status', 'unpaid')->sum('amount'),
            'total_paid' => Invoice::where('status', 'paid')->sum('amount'),
            'unpaid_count' => Invoice::where('status', 'unpaid')->count(),
            'paid_count' => Invoice::where('status', 'paid')->count(),
        ];

        return view('portal.invoices.index', compact('invoices', 'stats'));
    }

    /**
     * Mark an invoice as paid and automatically activate the customer internet service!
     */
    public function markAsPaid(Request $request, Invoice $invoice): RedirectResponse
    {
        $validated = $request->validate([
            'payment_method' => 'required|string|max:100',
            'payment_reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        $invoice->markAsPaid(
            paymentMethod: $validated['payment_method'],
            reference: $validated['payment_reference'] ?? null,
            receivedByUserId: Auth::id()
        );

        if (! empty($validated['notes'])) {
            $invoice->update(['notes' => $validated['notes']]);
        }

        return back()->with('success', "Tagihan {$invoice->invoice_number} berhasil ditandai LUNAS! Status internet pelanggan {$invoice->customer->name} otomatis AKTIF.");
    }

    /**
     * Generate monthly invoices for all active customers
     */
    public function generateMonthly(Request $request): RedirectResponse
    {
        $monthName = trim($request->input('billing_month', now()->isoFormat('MMMM Y')));
        if (empty($monthName)) {
            $monthName = now()->isoFormat('MMMM Y');
        }

        $periodStart = now()->startOfMonth();
        $periodEnd = now()->endOfMonth();
        $dueDate = now()->startOfMonth()->addDays(9); // Default due date tanggal 10

        $activeCustomers = Customer::with('package')
            ->whereIn('status', ['active', 'isolated'])
            ->get();

        $generatedCount = 0;
        $skippedCount = 0;

        foreach ($activeCustomers as $customer) {
            if (! $customer->package) {
                continue;
            }

            // Check if customer already has invoice for this month
            $exists = Invoice::where('customer_id', $customer->id)
                ->where(function ($q) use ($monthName) {
                    $q->where('billing_month', $monthName)
                        ->orWhere('billing_month', 'LIKE', '%'.$monthName.'%');
                })
                ->exists();

            if ($exists) {
                $skippedCount++;

                continue;
            }

            // Ensure unique invoice number without collisions
            $yearMonth = date('Ym');
            $baseNumber = 'INV-'.$yearMonth.'-'.str_pad($customer->id, 3, '0', STR_PAD_LEFT);
            $invoiceNumber = $baseNumber;
            $counter = 1;

            while (Invoice::where('invoice_number', $invoiceNumber)->exists()) {
                $invoiceNumber = $baseNumber.'-'.chr(64 + $counter);
                $counter++;
                if ($counter > 26) {
                    $invoiceNumber = $baseNumber.'-'.substr(uniqid(), -4);
                    break;
                }
            }

            Invoice::create([
                'invoice_number' => $invoiceNumber,
                'customer_id' => $customer->id,
                'package_id' => $customer->package_id,
                'billing_month' => $monthName,
                'period_start' => $periodStart,
                'period_end' => $periodEnd,
                'due_date' => $dueDate,
                'amount' => $customer->package->price,
                'status' => 'unpaid',
                'notes' => "Tagihan bulanan reguler periode {$monthName}.",
            ]);

            $generatedCount++;
        }

        return back()->with('success', "Berhasil menerbitkan {$generatedCount} tagihan untuk periode {$monthName}. ({$skippedCount} pelanggan sudah memiliki tagihan sebelumnya).");
    }

    /**
     * Printable Official Invoice / Receipt
     */
    public function print(Invoice $invoice): View
    {
        $invoice->load(['customer', 'package', 'receivedBy']);

        return view('portal.invoices.print', compact('invoice'));
    }
}
