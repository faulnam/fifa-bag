<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreLocation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StoreLocationController extends Controller
{
    public function index(Request $request): View
    {
        $query = StoreLocation::query()->latest();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('city', 'like', "%{$search}%")
                ->orWhere('address', 'like', "%{$search}%");
        }

        $stores = $query->paginate(15)->withQueryString();

        return view('admin.stores.index', compact('stores'));
    }

    public function create(): View
    {
        return view('admin.stores.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:50'],
            'opening_hours' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        StoreLocation::create($validated);

        return redirect()->route('admin.stores.index')
            ->with('success', "Toko fisik '{$validated['name']}' berhasil ditambahkan.");
    }

    public function edit(StoreLocation $store): View
    {
        return view('admin.stores.edit', compact('store'));
    }

    public function update(Request $request, StoreLocation $store): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:50'],
            'opening_hours' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $store->update($validated);

        return redirect()->route('admin.stores.index')
            ->with('success', "Toko fisik '{$store->name}' berhasil diperbarui.");
    }

    public function destroy(StoreLocation $store): RedirectResponse
    {
        $name = $store->name;
        $store->delete();

        return redirect()->route('admin.stores.index')
            ->with('success', "Toko fisik '{$name}' berhasil dihapus.");
    }
}
