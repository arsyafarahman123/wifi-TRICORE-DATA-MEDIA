<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PackageManagementController extends Controller
{
    public function index(): View
    {
        $packages = Package::withCount('customers')
            ->orderBy('sort_order')
            ->get();

        return view('portal.packages.index', compact('packages'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'speed_mbps' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'badge' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'device_recommendation' => 'nullable|string|max:255',
            'is_popular' => 'nullable|boolean',
        ]);

        $maxSortOrder = Package::max('sort_order') ?? 0;

        Package::create([
            'name' => $validated['name'],
            'speed_mbps' => $validated['speed_mbps'],
            'price' => $validated['price'],
            'badge' => $validated['badge'] ?? null,
            'description' => $validated['description'] ?? null,
            'device_recommendation' => $validated['device_recommendation'] ?? null,
            'is_popular' => $request->boolean('is_popular'),
            'is_active' => true,
            'sort_order' => $maxSortOrder + 1,
        ]);

        return back()->with('success', "Paket {$validated['name']} berhasil ditambahkan.");
    }

    public function toggle(Package $package): RedirectResponse
    {
        $package->update([
            'is_active' => ! $package->is_active,
        ]);

        $status = $package->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Paket {$package->name} berhasil {$status}.");
    }

    public function update(Request $request, Package $package): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'speed_mbps' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'badge' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'device_recommendation' => 'nullable|string|max:255',
            'is_popular' => 'nullable|boolean',
        ]);

        $package->update([
            'name' => $validated['name'],
            'speed_mbps' => $validated['speed_mbps'],
            'price' => $validated['price'],
            'badge' => $validated['badge'] ?? null,
            'description' => $validated['description'] ?? null,
            'device_recommendation' => $validated['device_recommendation'] ?? null,
            'is_popular' => $request->boolean('is_popular'),
        ]);

        return back()->with('success', "Paket {$package->name} berhasil diperbarui.");
    }

    public function destroy(Package $package): RedirectResponse
    {
        if ($package->customers_count > 0) {
            return back()->with('error', "Paket {$package->name} tidak bisa dihapus karena masih memiliki {$package->customers_count} pelanggan aktif.");
        }

        $name = $package->name;
        $package->delete();

        return back()->with('success', "Paket {$name} berhasil dihapus.");
    }
}
