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
        // Handle Package/Bundle Addition
        if ($request->has('bundle_name')) {
            $validated = $request->validate([
                'bundle_name' => 'required|string',
                'bundle_price' => 'required|integer',
                'menu_ids' => 'required|array|min:1',
                'menu_ids.*' => 'exists:menu,menu_id',
                'qty' => 'required|integer|min:1',
            ]);

            $bundleId = \Illuminate\Support\Str::uuid()->toString();
            
            foreach ($validated['menu_ids'] as $index => $menuId) {
                Cart::create([
                    'user_id' => Auth::id(),
                    'menu_id' => $menuId,
                    'qty' => $validated['qty'],
                    'bundle_id' => $bundleId,
                    'bundle_name' => $validated['bundle_name'],
                    // Store the price only on the first item of the bundle
                    'bundle_price' => ($index === 0) ? $validated['bundle_price'] : 0,
                ]);
            }

            return redirect()->route('cart.index')->with('success', 'Paket ' . $validated['bundle_name'] . ' berhasil ditambahkan!');
        }

        $menuIds = $request->input('menu_ids');
        
        if ($menuIds && is_array($menuIds)) {
            // Bulk add (existing logic for weekly packages)
            foreach ($menuIds as $menuId) {
                $this->addToCart($menuId, 1);
            }
            return redirect()->route('cart.index')->with('success', 'Seluruh paket mingguan berhasil ditambahkan ke keranjang!');
        }

        $validated = $request->validate([
            'menu_id' => 'required|exists:menu,menu_id',
            'qty' => 'required|integer|min:1',
        ]);

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

        return redirect()->route('cart.index')
            ->with('success', 'Keranjang berhasil diupdate!');
    }

    public function destroy(Request $request, $menu_id)
    {
        $query = Cart::where('user_id', Auth::id())
            ->where('menu_id', $menu_id);
            
        if ($request->filled('bundle_id')) {
            $query->where('bundle_id', $request->bundle_id);
        } else {
            $query->whereNull('bundle_id');
        }

        $query->delete();

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
