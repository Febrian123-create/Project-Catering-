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
        $tab = $request->input('tab', 'harian');
        $query = Menu::with(['product', 'products']);

        if ($tab === 'harian') {
            // Harian: Show single items (satuan) AND daily packages (paket_harian)
            $query->whereIn('tipe', ['satuan', 'paket_harian']);
            $date = $request->input('date', now()->toDateString());
            $query->whereDate('tgl_tersedia', $date);
        } else {
            // Mingguan: Show only packages (paket)
            $query->where('tipe', 'paket');
            $startDate = now()->toDateString();
            $endDate = now()->addDays(7)->toDateString();
            $query->whereBetween('tgl_tersedia', [$startDate, $endDate]);
        }

        $menus = $query->orderBy('tgl_tersedia')->paginate(12);
        
        $allHarianMenus = [];
        if ($tab === 'harian') {
            $allHarianMenus = Menu::with('product')
                ->whereIn('tipe', ['satuan', 'paket_harian'])
                ->whereDate('tgl_tersedia', $request->input('date', now()->toDateString()))
                ->get();
        }

        return view('menus.index', compact('menus', 'tab', 'allHarianMenus'));
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

        } elseif ($tipe === 'mingguan_batch') {
            $validated = $request->validate([
                'batch_start_date' => 'required|date|after_or_equal:today',
                'batch_end_date' => 'required|date|after_or_equal:batch_start_date',
                'batch' => 'required|array|min:2|max:14',
                'batch.*.tanggal' => 'required|date',
                'batch.*.nama_paket' => 'required|string|max:80',
                'batch.*.product_ids' => 'required|array|min:2',
                'batch.*.product_ids.*' => 'exists:product,product_id',
                'batch.*.foto_paket' => 'required|image|mimes:jpeg,png,jpg|max:10240',
            ]);

            foreach ($validated['batch'] as $index => $dayData) {
                // Get selected products
                $selectedProducts = Product::whereIn('product_id', $dayData['product_ids'])->get();

                // Auto-sum harga
                $totalHarga = $selectedProducts->sum('harga');

                // Merge deskripsi
                $mergedDeskripsi = $selectedProducts->map(function ($p) {
                    return $p->nama . ': ' . ($p->deskripsi ?? '-');
                })->implode(' | ');

                // Upload foto baru (need to fetch from the file array correctly)
                $fotoPath = $request->file("batch.{$index}.foto_paket")->store('menus', 'public');

                $menu = new Menu();
                $menu->menu_id = Menu::generateMenuId();
                $menu->tipe = 'paket'; // internally store as paket for the weekly bundle
                $menu->nama_paket = $dayData['nama_paket'];
                $menu->harga_paket = $totalHarga;
                $menu->deskripsi_paket = $mergedDeskripsi;
                $menu->foto_paket = $fotoPath;
                $menu->tgl_tersedia = $dayData['tanggal'];
                $menu->product_id = null;
                $menu->save();

                // Attach products to weekly package
                $menu->products()->attach($dayData['product_ids']);

                // Create Daily Clone (harga flat 12k)
                $dailyMenu = new Menu();
                $dailyMenu->menu_id = Menu::generateMenuId();
                $dailyMenu->tipe = 'paket_harian'; // special type for daily tab
                $dailyMenu->nama_paket = $dayData['nama_paket'] . ' (Harian)';
                
                // Calculate dynamic price based on rules
                $dailyMenu->harga_paket = $this->calculateDynamicPrice($selectedProducts);
                
                $dailyMenu->deskripsi_paket = $mergedDeskripsi;
                $dailyMenu->foto_paket = $fotoPath; // share the same exact photo file
                $dailyMenu->tgl_tersedia = $dayData['tanggal'];
                $dailyMenu->product_id = null;
                $dailyMenu->save();

                // Attach products to daily clone
                $dailyMenu->products()->attach($dayData['product_ids']);
            }
        } else {
            // Paket
            $validated = $request->validate([
                'tgl_tersedia' => 'required|date|after_or_equal:today',
                'nama_paket' => 'required|string|max:80',
                'product_ids' => 'required|array|min:2',
                'product_ids.*' => 'exists:product,product_id',
                'foto_paket' => 'required|image|mimes:jpeg,png,jpg|max:10240',
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
                'foto_paket' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
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

    /**
     * Calculate dynamic pricing for a daily package based on its components.
     * 
     * Rules:
     * - Nasi + Sayur + Daging = 12k
     * - Nasi + Daging + Daging = 17k
     * - Nasi + Sayur + Sayur = 10k
     * - Nasi + Sayur + Adds on (telor rebus) = 12k
     * - Nasi + Sayur = 9k
     * - Nasi + Daging utuh (2) = 10k
     * - Telor rebus = 4k
     * - Nasi = 5k
     * - Sambel saset = 2k
     */
    private function calculateDynamicPrice($products)
    {
        $nasiCount = 0;
        $sayurCount = 0;
        $dagingCount = 0;
        $telorCount = 0;
        $dagingUtuhCount = 0;

        foreach ($products as $p) {
            $name = strtolower($p->nama);
            $cat = strtolower($p->kategori ?? '');

            if (str_contains($name, 'nasi')) {
                $nasiCount++;
            } elseif (str_contains($name, 'telor') || str_contains($name, 'telur')) {
                $telorCount++;
            } elseif ($cat === 'sayur' || str_contains($name, 'sayur')) {
                $sayurCount++;
            } elseif (str_contains($name, 'daging utuh')) {
                // Specific keyword match if provided
                $dagingUtuhCount++;
            } elseif ($cat === 'daging' || str_contains($name, 'daging') || str_contains($name, 'ayam') || str_contains($name, 'sapi') || str_contains($name, 'katsu') || str_contains($name, 'ikan')) {
                $dagingCount++;
            }
        }

        // Apply rules (highest matching combo first to prevent partial matches)
        if ($nasiCount >= 1 && $dagingCount >= 2) {
            return 17000; // Nasi + Daging + Daging
        }
        if ($nasiCount >= 1 && $sayurCount >= 1 && $dagingCount >= 1) {
            return 12000; // Nasi + Sayur + Daging
        }
        if ($nasiCount >= 1 && $sayurCount >= 1 && $telorCount >= 1) {
            return 12000; // Nasi + Sayur + Adds on (telor rebus)
        }
        if ($nasiCount >= 1 && $sayurCount >= 2) {
            return 10000; // Nasi + Sayur + Sayur
        }
        if ($nasiCount >= 1 && $dagingUtuhCount >= 2) {
            return 10000; // Nasi + Daging utuh (2)
        }
        if ($nasiCount >= 1 && $sayurCount == 1 && $dagingCount == 0 && $telorCount == 0) {
            return 9000; // Nasi + Sayur
        }

        // In case of standalone items or unrecognized combos, sum them up normally using base fallback rules or the DB raw prices
        // Let's use the DB sum as a fallback so we don't return 0 for random stuff.
        return $products->sum('harga');
    }
}
