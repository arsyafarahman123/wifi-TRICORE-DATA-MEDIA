<?php

namespace App\Http\Controllers;

use App\Models\CoverageArea;
use App\Models\Customer;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $packages = Package::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $coverageAreas = CoverageArea::where('is_active', true)->get();

        $stats = [
            'active_customers' => Customer::where('status', 'active')->count() + 500, // Real base + minimum guaranteed 500+
            'uptime' => '99%',
            'support' => '24/7',
            'areas_count' => $coverageAreas->count() ?: 3,
        ];

        return view('home', compact('packages', 'coverageAreas', 'stats'));
    }
}
