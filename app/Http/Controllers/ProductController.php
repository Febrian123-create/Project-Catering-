<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('reviews')->paginate(12);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'kategori' => 'nullable|string|max:30',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('products', 'public');
        }

        $product = new Product($validated);
        $product->product_id = Product::generateProductId();
        $product->save();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function show(Product $product)
    {
        $product->load('reviews.user');
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'kategori' => 'nullable|string|max:30',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            // Delete old photo
            if ($product->foto) {
                Storage::disk('public')->delete($product->foto);
            }
            $validated['foto'] = $request->file('foto')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diupdate!');
    }

    public function destroy(Product $product)
    {
        if ($product->foto) {
            Storage::disk('public')->delete($product->foto);
        }
        
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }
}
