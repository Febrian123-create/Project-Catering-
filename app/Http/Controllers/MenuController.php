<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Product;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::with('product');

        // Filter by date
        if ($request->has('date')) {
            $query->whereDate('tgl_tersedia', $request->date);
        } else {
            $query->where('tgl_tersedia', '>=', now()->toDateString());
        }

        $menus = $query->orderBy('tgl_tersedia')->paginate(12);

        return view('menus.index', compact('menus'));
    }

    public function show(Menu $menu)
    {
        // Eager load product and reviews with user
        $menu->load(['product', 'reviews.user']);
        
        return view('menus.show', compact('menu'));
    }

    // Seller methods
    public function create()
    {
        $products = Product::all();
        return view('admin.menus.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tgl_tersedia' => 'required|date|after_or_equal:today',
            'product_id' => 'required|exists:product,product_id',
        ]);

        $menu = new Menu($validated);
        $menu->menu_id = Menu::generateMenuId();
        $menu->save();

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu berhasil dibuat!');
    }

    public function edit(Menu $menu)
    {
        $products = Product::all();
        return view('admin.menus.edit', compact('menu', 'products'));
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'tgl_tersedia' => 'required|date',
            'product_id' => 'required|exists:product,product_id',
        ]);

        $menu->update($validated);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu berhasil diupdate!');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu berhasil dihapus!');
    }

    public function manage()
    {
        $menus = Menu::with('product')
            ->orderBy('tgl_tersedia', 'desc')
            ->paginate(10);

        return view('admin.menus.index', compact('menus'));
    }
}
