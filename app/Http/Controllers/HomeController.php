<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $menus = Menu::with('products')
            ->where('tgl_tersedia', '>=', now()->toDateString())
            ->orderBy('tgl_tersedia')
            ->take(6)
            ->get();

        $products = Product::with('reviews')
            ->take(8)
            ->get();

        return view('home', compact('menus', 'products'));
    }
}
