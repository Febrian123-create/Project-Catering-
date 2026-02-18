<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::with(['product', 'products']);

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
        $menu->load(['product', 'products', 'reviews.user']);
        
        return view('menus.show', compact('menu'));
    }

    // Admin methods
    public function create()
    {
        $products = Product::all();
        return view('admin.menus.create', compact('products'));
    }

    public function store(Request $request)
    {
        $tipe = $request->input('tipe', 'satuan');

        if ($tipe === 'satuan') {
            $validated = $request->validate([
                'tgl_tersedia' => 'required|date|after_or_equal:today',
                'product_id' => 'required|exists:product,product_id',
            ]);

            $menu = new Menu();
            $menu->menu_id = Menu::generateMenuId();
            $menu->tipe = 'satuan';
            $menu->tgl_tersedia = $validated['tgl_tersedia'];
            $menu->product_id = $validated['product_id'];
            $menu->save();

        } else {
            // Paket
            $validated = $request->validate([
                'tgl_tersedia' => 'required|date|after_or_equal:today',
                'nama_paket' => 'required|string|max:80',
                'product_ids' => 'required|array|min:2',
                'product_ids.*' => 'exists:product,product_id',
                'foto_paket' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            // Get selected products
            $selectedProducts = Product::whereIn('product_id', $validated['product_ids'])->get();

            // Auto-sum harga
            $totalHarga = $selectedProducts->sum('harga');

            // Merge deskripsi
            $mergedDeskripsi = $selectedProducts->map(function ($p) {
                return $p->nama . ': ' . ($p->deskripsi ?? '-');
            })->implode(' | ');

            // Upload foto baru
            $fotoPath = $request->file('foto_paket')->store('menus', 'public');

            $menu = new Menu();
            $menu->menu_id = Menu::generateMenuId();
            $menu->tipe = 'paket';
            $menu->nama_paket = $validated['nama_paket'];
            $menu->harga_paket = $totalHarga;
            $menu->deskripsi_paket = $mergedDeskripsi;
            $menu->foto_paket = $fotoPath;
            $menu->tgl_tersedia = $validated['tgl_tersedia'];
            $menu->product_id = null;
            $menu->save();

            // Attach products to junction table
            $menu->products()->attach($validated['product_ids']);
        }

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu berhasil dibuat!');
    }

    public function edit(Menu $menu)
    {
        $products = Product::all();
        $menu->load('products');
        return view('admin.menus.edit', compact('menu', 'products'));
    }

    public function update(Request $request, Menu $menu)
    {
        $tipe = $request->input('tipe', 'satuan');

        if ($tipe === 'satuan') {
            $validated = $request->validate([
                'tgl_tersedia' => 'required|date',
                'product_id' => 'required|exists:product,product_id',
            ]);

            // If switching from paket to satuan, clean up
            if ($menu->isPaket()) {
                if ($menu->foto_paket) {
                    Storage::disk('public')->delete($menu->foto_paket);
                }
                $menu->products()->detach();
            }

            $menu->tipe = 'satuan';
            $menu->tgl_tersedia = $validated['tgl_tersedia'];
            $menu->product_id = $validated['product_id'];
            $menu->nama_paket = null;
            $menu->harga_paket = null;
            $menu->deskripsi_paket = null;
            $menu->foto_paket = null;
            $menu->save();

        } else {
            // Paket
            $validated = $request->validate([
                'tgl_tersedia' => 'required|date',
                'nama_paket' => 'required|string|max:80',
                'product_ids' => 'required|array|min:2',
                'product_ids.*' => 'exists:product,product_id',
                'foto_paket' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            $selectedProducts = Product::whereIn('product_id', $validated['product_ids'])->get();
            $totalHarga = $selectedProducts->sum('harga');
            $mergedDeskripsi = $selectedProducts->map(function ($p) {
                return $p->nama . ': ' . ($p->deskripsi ?? '-');
            })->implode(' | ');

            // Upload new foto if provided
            if ($request->hasFile('foto_paket')) {
                if ($menu->foto_paket) {
                    Storage::disk('public')->delete($menu->foto_paket);
                }
                $menu->foto_paket = $request->file('foto_paket')->store('menus', 'public');
            }

            $menu->tipe = 'paket';
            $menu->nama_paket = $validated['nama_paket'];
            $menu->harga_paket = $totalHarga;
            $menu->deskripsi_paket = $mergedDeskripsi;
            $menu->tgl_tersedia = $validated['tgl_tersedia'];
            $menu->product_id = null;
            $menu->save();

            // Sync products
            $menu->products()->sync($validated['product_ids']);
        }

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu berhasil diupdate!');
    }

    public function destroy(Menu $menu)
    {
        // Clean up paket foto
        if ($menu->isPaket() && $menu->foto_paket) {
            Storage::disk('public')->delete($menu->foto_paket);
        }

        // Detach products jika paket
        if ($menu->isPaket()) {
            $menu->products()->detach();
        }

        $menu->delete();

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu berhasil dihapus!');
    }

    public function manage()
    {
        $menus = Menu::with(['product', 'products'])
            ->orderBy('tgl_tersedia', 'desc')
            ->paginate(10);

        return view('admin.menus.index', compact('menus'));
    }
}
