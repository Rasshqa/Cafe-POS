<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Helpers\StoreHelper;

class UserController extends Controller
{
    public function index()
    {
        $role = auth()->user()->role;
        if ($role === 'owner') {
            $users = User::with('store')->get();
        } else {
            $users = User::where('store_id', auth()->user()->store_id)->get();
        }
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $stores = auth()->user()->role === 'owner' ? Store::all() : null;
        return view('users.create', compact('stores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:4|confirmed',
            'role' => 'required|in:admin,kasir',
            'store_id' => 'nullable|exists:stores,id',
        ]);

        $storeId = auth()->user()->role === 'owner' ? $request->store_id : auth()->user()->store_id;

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'store_id' => $storeId,
        ]);

        return redirect()->route('users.index')->with('success', 'Pegawai berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        $stores = auth()->user()->role === 'owner' ? Store::all() : null;
        return view('users.edit', compact('user', 'stores'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:4|confirmed',
            'role' => 'required|in:admin,kasir',
            'store_id' => 'nullable|exists:stores,id',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if (auth()->user()->role === 'owner' && $request->has('store_id')) {
            $data['store_id'] = $request->store_id;
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Data pegawai diperbarui!');
    }

    public function destroy(User $user)
    {
        if ($user->role === 'owner') abort(403, 'Tidak bisa menghapus owner.');
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Pegawai dihapus!');
    }
}
