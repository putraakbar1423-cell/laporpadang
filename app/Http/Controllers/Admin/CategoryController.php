<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('reports')
            ->orderBy('name')
            ->get();
        
        return view('admin.categories.index', compact('categories'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:7',
        ]);
        
        Category::create($validated);
        
        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan.');
    }
    
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:7',
        ]);
        
        $category->update($validated);
        
        return redirect()->back()->with('success', 'Kategori berhasil diperbarui.');
    }
    
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        // Check if category has reports
        if ($category->reports()->count() > 0) {
            return redirect()->back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki laporan.');
        }
        
        $category->delete();
        
        return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
    }
}
