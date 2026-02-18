<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $menus = Menu::with('product')
            ->where('tgl_tersedia', '>=', now()->toDateString())
            ->orderBy('tgl_tersedia')
            ->take(6)
            ->get();

        $products = Product::with('reviews')
            ->take(8)
            ->get();

        $reviews = Review::with('user')
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('home', compact('menus', 'products', 'reviews'));
    }
}
