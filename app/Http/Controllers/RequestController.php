<?php

namespace App\Http\Controllers;

use App\Models\CateringRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function index()
    {
        $requests = CateringRequest::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('requests.index', compact('requests'));
    }

    public function create()
    {
        return view('requests.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'nama_menu' => 'required|string|max:255',
            'jumlah_porsi' => 'required|integer|min:1',
            'tanggal_kebutuhan' => 'required|date|after:today',
            'message' => 'nullable|string',
        ]);

        CateringRequest::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'nama_menu' => $request->nama_menu,
            'jumlah_porsi' => $request->jumlah_porsi,
            'tanggal_kebutuhan' => $request->tanggal_kebutuhan,
            'message' => $request->message ?? '',
            'status' => 'pending',
        ]);

        return redirect()->route('requests.index')->with('success', 'Request Menu berhasil dikirim!');
    }
}
