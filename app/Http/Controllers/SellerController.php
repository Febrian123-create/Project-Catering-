<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Menu;
use App\Models\User;
use App\Models\Review;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function dashboard()
    {
        $products = Product::all();
        
        $reviews = Review::with('user')
            ->inRandomOrder()
            ->take(4)
            ->get();

        $recent_orders = Order::with('user')
            ->orderBy('tgl_pesan', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('recent_orders', 'products', 'reviews'));
    }
}
