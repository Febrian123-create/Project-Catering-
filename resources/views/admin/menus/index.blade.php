@extends('layouts.app')

@section('title', 'Kelola Menu')

@section('content')
<div class="sticker-container">
    <i class="bi bi-calendar-check sticker sticker-1"></i>
    <i class="bi bi-award sticker sticker-2"></i>
    <i class="bi bi-stars sticker sticker-3"></i>
    <i class="bi bi-hand-thumbs-up sticker sticker-4"></i>
    <i class="bi bi-chat-heart sticker sticker-5"></i>
</div>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="section-title mb-0">Kelola Menu</h2>
        <a href="{{ route('admin.menus.create') }}" class="brand-btn brand-btn-primary text-white text-decoration-none">
            <i class="bi bi-plus-lg me-2"></i>Tambah Menu
        </a>
    </div>

    <div class="brand-card brand-card-green">
        <div class="card-body p-0">
            @if($menus->count() > 0)
                <div class="table-responsive">
                    <table class="table brand-table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ID Menu</th>
                                <th>Tipe</th>
                                <th>Tanggal Tersedia</th>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($menus as $menu)
                                <tr>
                                    <td>
                                        <code class="fw-bold text-dark bg-light px-2 py-1 rounded border border-dark">#{{ $menu->menu_id }}</code>
                                    </td>
                                    <td>
                                        @if($menu->isPaket())
                                            <span class="badge bg-primary text-white border border-dark rounded-pill px-3 py-2 fw-bold">
                                                <i class="bi bi-collection me-1"></i>Paket
                                            </span>
                                        @else
                                            <span class="badge bg-light text-dark border border-dark rounded-pill px-3 py-2 fw-bold">
                                                <i class="bi bi-box me-1"></i>Satuan
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-calendar3 me-2 text-primary"></i>
                                            <span class="fw-bold">{{ $menu->tgl_tersedia->format('l, d M Y') }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($menu->foto_display)
                                                <img src="{{ asset('storage/' . $menu->foto_display) }}" 
                                                    class="rounded-3 border border-2 border-dark me-3" width="50" height="50" style="object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded-3 border border-2 border-dark d-flex align-items-center justify-content-center text-muted me-3" style="width: 50px; height: 50px;">
                                                    <i class="bi bi-image"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <span class="fw-bold fs-6">{{ $menu->nama_display }}</span>
                                                @if($menu->isPaket())
                                                    <br><small class="text-muted">{{ $menu->products->count() }} produk</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-white text-dark border border-dark rounded-pill px-3 py-2 fw-bold shadow-sm">
                                            {{ $menu->formatted_harga }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('admin.menus.edit', $menu) }}" class="brand-btn brand-btn-warning btn-sm text-decoration-none" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" class="d-inline"
                                                onsubmit="return confirm('Yakin ingin menghapus menu ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="brand-btn brand-btn-danger btn-sm" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-top border-2 border-dark">
                    {{ $menus->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-calendar-x display-1 text-muted opacity-25 mb-3 d-block"></i>
                    <p class="text-muted fs-5">Belum ada menu yang dijadwalkan.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
