<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status_pembayaran', 'pending')->count(),
            'total_products' => Product::count(),
            'total_menus' => Menu::count(),
            'total_revenue' => Order::where('status_pembayaran', 'paid')->sum('total_bayar'),
            'total_buyers' => User::where('role', 'buyer')->count(),
        ];

        $recent_orders = Order::with('user')
            ->orderBy('tgl_pesan', 'desc')
            ->take(5)
            ->get();

        return view('seller.dashboard', compact('stats', 'recent_orders'));
    }

    public function menuIndex()
    {
        $menus = Menu::with('product')
            ->orderBy('tgl_tersedia', 'desc')
            ->paginate(10);

        return view('seller.menus.index', compact('menus'));
    }
}
