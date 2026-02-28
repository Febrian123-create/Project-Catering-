<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with('user', 'menu.product')
            ->orderBy('tgl_review', 'desc')
            ->paginate(12);

        return view('ulasan.index', compact('reviews'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'menu_id' => 'required|exists:menu,menu_id',
            'bintang' => 'required|integer|min:1|max:5',
            'isi_review' => 'nullable|string|max:120',
        ]);

        // Check if user has an order for this menu that is both PAID and TERKIRIM
        $isDelivered = OrderDetail::whereHas('order', function ($query) {
            $query->where('user_id', Auth::id())
                  ->where('status_pembayaran', 'paid')
                  ->where('status_pesanan', 'terkirim');
        })->where('menu_id', $validated['menu_id'])
          ->exists();

        if (!$isDelivered) {
            return redirect()->back()
                ->with('error', 'Anda hanya dapat memberikan ulasan setelah menu sampai (Status: Terkirim).');
        }

        $review = new Review($validated);
        $review->review_id = Review::generateReviewId();
        $review->user_id = Auth::id();
        $review->tgl_review = now();
        $review->save();

        return redirect()->back()
            ->with('success', 'Review berhasil ditambahkan!');
    }

    public function adminIndex()
    {
        $reviews = Review::with('user', 'menu.product')
            ->orderBy('tgl_review', 'desc')
            ->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function destroy(Review $review)
    {
        if (Auth::user()->isAdmin() || $review->user_id === Auth::id()) {
            $review->delete();
            return redirect()->back()->with('success', 'Review berhasil dihapus!');
        }

        abort(403);
    }
}
