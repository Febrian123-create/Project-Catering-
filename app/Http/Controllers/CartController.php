<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('menu.product')
            ->where('user_id', Auth::id())
            ->get();

        $total = $cartItems->sum(function ($item) {
            // If it's a bundle item, use bundle_price if set, otherwise 0 for subsequent items in the same bundle
            if ($item->bundle_id) {
                return $item->bundle_price * $item->qty;
            }
            return $item->subtotal;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function store(Request $request)
    {
        // Handle Package/Bundle Addition (via Package Composer)
        if ($request->input('is_bundle') == '1') {
            $validated = $request->validate([
                'bundle_name'   => 'required|string',
                'bundle_price'  => 'required|numeric|min:0',
                'menu_ids'      => 'required|array|min:1',
                'menu_ids.*'    => 'exists:menu,menu_id',
                'qty'           => 'required|integer|min:1',
            ]);

            // For packages, ensure all selected menus are for upcoming days
            foreach ($validated['menu_ids'] as $menuId) {
                $menu = \App\Models\Menu::find($menuId);
                if ($menu && $menu->tgl_tersedia->startOfDay()->lte(now()->startOfDay())) {
                    return redirect()->back()->with('error', 'Salah satu menu dalam paket ini sudah lewat atau hari ini, tidak dapat dipesan lagi.');
                }
            }

            $bundleId = \Illuminate\Support\Str::uuid()->toString();

            foreach ($validated['menu_ids'] as $index => $menuId) {
                Cart::create([
                    'user_id'      => Auth::id(),
                    'menu_id'      => $menuId,
                    'qty'          => $validated['qty'],
                    'bundle_id'    => $bundleId,
                    'bundle_name'  => $validated['bundle_name'],
                    // Only the first item carries the bundle price
                    'bundle_price' => ($index === 0) ? $validated['bundle_price'] : 0,
                ]);
            }

            return redirect()->route('cart.index')->with('success', 'Paket ' . $validated['bundle_name'] . ' berhasil ditambahkan!');
        }

        // Handle Weekly Package bulk-add (uses menu_ids[] and is_weekly_package flag)
        $menuIds = $request->input('menu_ids');
        if ($menuIds && is_array($menuIds) && $request->has('is_weekly_package')) {
            $bundleId = \Illuminate\Support\Str::uuid()->toString();
            $bundleName = 'Paket Mingguan';
            $bundlePrice = 60000;
            
            // Validate all menus are eligible
            foreach ($menuIds as $menuId) {
                $menu = \App\Models\Menu::find($menuId);
                if ($menu && $menu->tgl_tersedia->startOfDay()->lte(now()->startOfDay())) {
                    return redirect()->back()->with('error', 'Salah satu menu mingguan sudah lewat atau hari ini, tidak dapat dipesan lagi.');
                }
            }

            foreach ($menuIds as $index => $menuId) {
                Cart::create([
                    'user_id'      => Auth::id(),
                    'menu_id'      => $menuId,
                    'qty'          => 1, // Weekly package defaults to 1 bundle initially
                    'bundle_id'    => $bundleId,
                    'bundle_name'  => $bundleName,
                    'bundle_price' => ($index === 0) ? $bundlePrice : 0, // Only first item carries the price
                ]);
            }
            return redirect()->route('cart.index')->with('success', 'Paket hemat mingguan (Rp 60.000) berhasil ditambahkan ke keranjang!');
        }

        $validated = $request->validate([
            'menu_id' => 'required|exists:menu,menu_id',
            'qty' => 'required|integer|min:1',
        ]);

        $menu = \App\Models\Menu::find($validated['menu_id']);
        if ($menu && $menu->tgl_tersedia->startOfDay()->lte(now()->startOfDay())) {
            return redirect()->back()->with('error', 'Menu ini sudah lewat tanggalnya atau untuk hari ini, sudah tidak bisa dipesan lagi. Silakan pesan untuk besok atau hari-hari berikutnya.');
        }

        $this->addToCart($validated['menu_id'], $validated['qty']);

        if ($request->input('action') === 'buy_now') {
            return redirect()->route('cart.index')->with('success', 'Berhasil ditambahkan, siap untuk checkout!');
        }

        return redirect()->back()->with('success', 'Berhasil ditambahkan ke keranjang!');
    }

    private function addToCart($menuId, $qty)
    {
        $existing = Cart::where('user_id', Auth::id())
            ->where('menu_id', $menuId)
            ->whereNull('bundle_id')
            ->first();

        if ($existing) {
            Cart::where('user_id', Auth::id())
                ->where('menu_id', $menuId)
                ->whereNull('bundle_id')
                ->update(['qty' => $existing->qty + $qty]);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'menu_id' => $menuId,
                'qty' => $qty,
            ]);
        }
    }

    public function update(Request $request, $menu_id)
    {
        $validated = $request->validate([
            'qty' => 'required|integer|min:1',
            'bundle_id' => 'nullable|string',
        ]);

        $query = Cart::where('user_id', Auth::id())
            ->where('menu_id', $menu_id);
            
        if ($request->filled('bundle_id')) {
            $query->where('bundle_id', $request->bundle_id);
        } else {
            $query->whereNull('bundle_id');
        }

        $query->update(['qty' => $validated['qty']]);

        if ($request->input('redirect_to') === 'checkout') {
            return redirect()->route('orders.create')->with('success', 'Jumlah pesanan berhasil diupdate!');
        }

        return redirect()->route('cart.index')
            ->with('success', 'Keranjang berhasil diupdate!');
    }

    public function destroy(Request $request, $menu_id)
    {
        $bundleId = $request->input('bundle_id');

        if ($bundleId) {
            // Delete all items belonging to this bundle for the current user
            Cart::where('user_id', Auth::id())
                ->where('bundle_id', $bundleId)
                ->delete();
        } else {
            // Delete a single item not in a bundle
            Cart::where('user_id', Auth::id())
                ->where('menu_id', $menu_id)
                ->whereNull('bundle_id')
                ->delete();
        }

        if ($request->input('redirect_to') === 'checkout') {
            return redirect()->route('orders.create')->with('success', 'Menu berhasil dihapus dari pesanan!');
        }

        return redirect()->route('cart.index')
            ->with('success', 'Item berhasil dihapus dari keranjang!');
    }

    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('cart.index')
            ->with('success', 'Keranjang berhasil dikosongkan!');
    }
}
