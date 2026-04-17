<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->get();
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['name' => 'required|string|max:255']);
        Category::create($validated);
        return redirect()->route('categories.index')->with('success', 'Kategori ditambahkan!');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate(['name' => 'required|string|max:255']);
        $category->update($validated);
        return redirect()->route('categories.index')->with('success', 'Kategori diupdate!');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return redirect()->route('categories.index')->with('error', 'Kategori masih memiliki produk!');
        }
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Kategori dihapus!');
    }
}
