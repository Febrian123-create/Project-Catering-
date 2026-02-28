<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    /**
     * Display a listing of reviews.
     */
    public function index()
    {
        $reviews = Review::with(['user', 'menu.product'])
            ->orderBy('tgl_review', 'desc')
            ->paginate(12);

        return view('reviews.index', compact('reviews'));
    }

    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'menu_id' => 'required|exists:menu,menu_id',
            'bintang' => 'required|integer|min:1|max:5',
            'isi_review' => 'nullable|string|max:255',
        ]);

        // Authorization check: User must have a PAID and DELIVERED order for this menu
        $hasValidOrder = OrderDetail::whereHas('order', function ($query) {
            $query->where('user_id', Auth::id())
                  ->whereIn('status_pembayaran', ['paid', 'Complete'])
                  ->where('status_pesanan', 'terkirim');
        })
        ->where('menu_id', $validated['menu_id'])
        ->exists();

        if (!$hasValidOrder) {
            return back()->with('error', 'Ops! Kamu cuma bisa kasih review kalau pesanan sudah sampai dan lunas.');
        }

        try {
            DB::beginTransaction();

            $review = new Review();
            $review->review_id = Review::generateReviewId();
            $review->user_id = Auth::id();
            $review->menu_id = $validated['menu_id'];
            $review->bintang = $validated['bintang'];
            $review->isi_review = $validated['isi_review'];
            $review->tgl_review = now();
            $review->save();

            DB::commit();

            return back()->with('success', 'Yey! Review kamu berhasil terkirim. Makasih ya!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Waduh, ada masalah pas kirim review: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource for admin.
     */
    public function adminIndex()
    {
        $reviews = Review::with(['user', 'menu.product'])
            ->orderBy('tgl_review', 'desc')
            ->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        if (Auth::user()->isAdmin() || $review->user_id === Auth::id()) {
            $review->delete();
            return back()->with('success', 'Review sudah dihapus!');
        }

        abort(403);
    }
}
