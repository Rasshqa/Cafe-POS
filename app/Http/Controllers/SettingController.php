<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Helpers\StoreHelper;

class SettingController extends Controller
{
    public function index()
    {
        $store = StoreHelper::current();
        if (!$store) {
            abort(403, 'Silakan pilih cabang / toko terlebih dahulu.');
        }

        $settings = [
            'store_name' => $store->name,
            'store_address' => $store->address ?? '',
            'store_phone' => $store->phone ?? '',
            'store_logo' => $store->logo ?? '',
            'default_tax' => $store->default_tax,
        ];
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $store = StoreHelper::current();
        if (!$store) abort(403);

        $request->validate([
            'store_name' => 'required|string|max:255',
            'store_address' => 'nullable|string|max:500',
            'store_phone' => 'nullable|string|max:50',
            'store_logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
            'default_tax' => 'nullable|numeric|min:0|max:100',
        ]);

        $store->name = $request->store_name;
        $store->address = $request->store_address;
        $store->phone = $request->store_phone;
        $store->default_tax = $request->default_tax ?? 0;

        if ($request->hasFile('store_logo')) {
            if ($store->logo) Storage::disk('public')->delete($store->logo);

            $path = $request->file('store_logo')->store('stores', 'public');
            $store->logo = $path;
        }

        $store->save();

        return redirect()->route('settings.index')->with('success', 'Profil toko berhasil disimpan!');
    }
}
