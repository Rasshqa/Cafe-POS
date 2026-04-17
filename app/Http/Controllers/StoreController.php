<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::all();
        return view('stores.index', compact('stores'));
    }

    public function create()
    {
        return view('stores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
            'default_tax' => 'nullable|numeric|min:0|max:100',
        ]);

        $data = $request->except('logo');
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('stores', 'public');
        }

        Store::create($data);

        return redirect()->route('stores.index')->with('success', 'Cabang baru berhasil ditambahkan!');
    }

    public function edit(Store $store)
    {
        return view('stores.edit', compact('store'));
    }

    public function update(Request $request, Store $store)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
            'default_tax' => 'nullable|numeric|min:0|max:100',
        ]);

        $data = $request->except('logo');
        if ($request->hasFile('logo')) {
            if ($store->logo) Storage::disk('public')->delete($store->logo);
            $data['logo'] = $request->file('logo')->store('stores', 'public');
        }

        $store->update($data);

        return redirect()->route('stores.index')->with('success', 'Toko diperbarui!');
    }

    public function destroy(Store $store)
    {
        if ($store->logo) Storage::disk('public')->delete($store->logo);
        $store->delete();
        return redirect()->route('stores.index')->with('success', 'Toko dihapus!');
    }

    public function switchStore($id)
    {
        $store = Store::findOrFail($id);
        session(['current_store_id' => $store->id]);
        return redirect()->back()->with('success', 'Berpindah ke ' . $store->name);
    }
}
