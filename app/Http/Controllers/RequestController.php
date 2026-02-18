<?php

namespace App\Http\Controllers;

use App\Models\CateringRequest;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function index()
    {
        if (Auth::user()->isAdmin()) {
            $requests = CateringRequest::with('user')
                ->where('status', 'pending')
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
