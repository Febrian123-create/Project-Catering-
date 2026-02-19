<?php

namespace App\Http\Controllers;

use App\Models\CateringRequest;
use App\Models\Menu;
use App\Models\Notification;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RequestController extends Controller
{
    public function index()
    {
        if (Auth::user()->isAdmin()) {
            $requests = CateringRequest::with('user')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else {
            $requests = CateringRequest::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }
            
        return view('requests.index', compact('requests'));
    }

    public function accept(CateringRequest $cateringRequest)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $cateringRequest->update(['status' => 'accepted']);

        // Notify buyer
        Notification::create([
            'user_id' => $cateringRequest->user_id,
            'title' => 'Request Menu Diterima ✅',
            'message' => 'Request menu "' . $cateringRequest->nama_menu . '" telah diterima! Kami akan segera memprosesnya.',
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', 'Request Menu berhasil diterima!');
    }

    public function reject(CateringRequest $cateringRequest)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $cateringRequest->update(['status' => 'rejected']);

        // Notify buyer
        Notification::create([
            'user_id' => $cateringRequest->user_id,
            'title' => 'Request Menu Ditolak ❌',
            'message' => 'Mohon maaf, request menu "' . $cateringRequest->nama_menu . '" belum bisa kami penuhi saat ini.',
            'is_read' => false,
        ]);

        return redirect()->route('requests.index')->with('success', 'Request Menu berhasil ditolak.');
    }

    public function process(CateringRequest $cateringRequest)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        return view('requests.process', compact('cateringRequest'));
    }

    public function finalize(Request $request, CateringRequest $cateringRequest)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'harga' => 'required|numeric|min:0',
            'kategori' => 'required|string|in:Sayur,Daging',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tgl_tersedia' => 'required|date|after_or_equal:today',
        ]);

        // Upload photo
        $path = $request->file('foto')->store('products', 'public');

        // Create Product
        $productId = Product::generateProductId();
        Product::create([
            'product_id' => $productId,
            'nama' => $cateringRequest->nama_menu,
            'harga' => $request->harga,
            'kategori' => $request->kategori,
            'deskripsi' => $cateringRequest->deskripsi ?? 'Menu request dari buyer.',
            'foto' => $path,
        ]);

        // Create Menu (so it appears in the shop)
        Menu::create([
            'menu_id' => Menu::generateMenuId(),
            'tipe' => 'satuan',
            'tgl_tersedia' => $request->tgl_tersedia,
            'product_id' => $productId,
        ]);

        // Update Request Status
        $cateringRequest->update(['status' => 'accepted']);

        // Notify buyer
        Notification::create([
            'user_id' => $cateringRequest->user_id,
            'title' => 'Request Menu Diterima ✅',
            'message' => 'Hore! Request menu "' . $cateringRequest->nama_menu . '" telah diterima dan sekarang sudah tersedia di daftar produk kami.',
            'is_read' => false,
        ]);

        return redirect()->route('requests.index')->with('success', 'Request berhasil diterima dan produk telah dibuat!');
    }

    public function create()
    {
        return view('requests.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'asal_daerah' => 'nullable|string|max:255',
        ]);

        CateringRequest::create([
            'user_id' => Auth::id(),
            'subject' => $request->nama_menu,
            'nama_menu' => $request->nama_menu,
            'deskripsi' => $request->deskripsi,
            'asal_daerah' => $request->asal_daerah,
            'status' => 'pending',
        ]);

        // Notify buyer
        Notification::create([
            'user_id' => Auth::id(),
            'title' => 'Request Menu Terkirim 📋',
            'message' => 'Request menu "' . $request->nama_menu . '" berhasil dikirim dan sedang ditinjau oleh tim kami.',
            'is_read' => false,
        ]);

        return redirect()->route('requests.index')->with('success', 'Request Menu berhasil dikirim!');
    }
}
