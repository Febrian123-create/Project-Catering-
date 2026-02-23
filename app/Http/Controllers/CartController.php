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
            return $item->subtotal;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function store(Request $request)
    {
        $menuIds = $request->input('menu_ids');
        
        if ($menuIds && is_array($menuIds)) {
            // Bulk add
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
            ->first();

        if ($existing) {
            Cart::where('user_id', Auth::id())
                ->where('menu_id', $menuId)
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
        ]);

        Cart::where('user_id', Auth::id())
            ->where('menu_id', $menu_id)
            ->update(['qty' => $validated['qty']]);

        return redirect()->route('cart.index')
            ->with('success', 'Keranjang berhasil diupdate!');
    }

    public function destroy($menu_id)
    {
        Cart::where('user_id', Auth::id())
            ->where('menu_id', $menu_id)
            ->delete();

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
