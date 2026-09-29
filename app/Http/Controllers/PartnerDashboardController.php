<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PartnerDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $totalCustomers = Customer::count();
        $activeCustomers = Customer::where('status', 'active')->count();
        $pendingCustomers = Customer::where('status', 'pending')->count();
        $isolatedCustomers = Customer::where('status', 'isolated')->count();

        $totalPackages = Package::where('is_active', true)->count();

        // Financial stats
        $totalPaidRevenue = Invoice::where('status', 'paid')->sum('amount');
        $totalUnpaidAmount = Invoice::where('status', 'unpaid')->sum('amount');
        $paidInvoicesCount = Invoice::where('status', 'paid')->count();
        $unpaidInvoicesCount = Invoice::where('status', 'unpaid')->count();

        // Recent customers
        $recentCustomers = Customer::with('package')
            ->latest()
            ->take(5)
            ->get();

        // Recent paid transactions
        $recentTransactions = Invoice::with(['customer', 'package', 'receivedBy'])
            ->where('status', 'paid')
            ->latest('paid_at')
            ->take(5)
            ->get();

        // Pending unpaid bills for quick action
        $pendingInvoices = Invoice::with(['customer', 'package'])
            ->where('status', 'unpaid')
            ->latest()
            ->take(5)
            ->get();

        // Packages summary
        $packages = Package::withCount('customers')->orderBy('sort_order')->get();

        return view('portal.dashboard', compact(
            'totalCustomers',
            'activeCustomers',
            'pendingCustomers',
            'isolatedCustomers',
            'totalPackages',
            'totalPaidRevenue',
            'totalUnpaidAmount',
            'paidInvoicesCount',
            'unpaidInvoicesCount',
            'recentCustomers',
            'recentTransactions',
            'pendingInvoices',
            'packages'
        ));
    }
}
