@extends('layouts.app')

@section('title', 'Kelola Produk')

@section('content')
<div class="sticker-container">
    <i class="bi bi-egg-fried sticker sticker-1"></i>
    <i class="bi bi-box-seam sticker sticker-2"></i>
    <i class="bi bi-star sticker sticker-3"></i>
    <i class="bi bi-heart sticker sticker-4"></i>
    <i class="bi bi-cup-hot sticker sticker-5"></i>
</div>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="section-title mb-0">Kelola Produk</h2>
        <a href="{{ route('admin.products.create') }}" class="brand-btn brand-btn-primary text-white text-decoration-none">
            <i class="bi bi-plus-lg me-2"></i>Tambah Produk
        </a>
    </div>

    <div class="brand-card">
        <div class="card-body p-0">
            @if($products->count() > 0)
                <div class="table-responsive">
                    <table class="table brand-table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Foto</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Rating</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td>
                                        @if($product->foto)
                                            <img src="{{ asset('storage/' . $product->foto) }}" class="rounded-4 border border-2 border-dark" width="60" height="60" style="object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded-4 border border-2 border-dark d-flex align-items-center justify-content-center text-muted" style="width: 60px; height: 60px;">
                                                <i class="bi bi-image fs-4"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold fs-5">{{ $product->nama }}</div>
                                        <div class="small text-muted">{{ Str::limit($product->deskripsi, 50) }}</div>
                                    </td>
                                    <td>
                                        @if($product->kategori)
                                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-3 py-2 fw-bold">
                                                {{ $product->kategori }}
                                            </span>
                                        @else
                                            <span class="text-muted small fst-italic">Tanpa Kategori</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border border-dark rounded-pill px-3 py-2 fw-bold">
                                            {{ $product->formatted_harga }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-star-fill text-warning me-1"></i>
                                            <span class="fw-bold">{{ number_format($product->average_rating, 1) }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('admin.products.edit', $product) }}" class="brand-btn brand-btn-warning btn-sm text-decoration-none" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline"
                                                onsubmit="return confirm('Hapus produk ini? Menu yang menggunakan produk ini juga akan terpengaruh.')">
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
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-box-seam display-1 text-muted opacity-25 mb-3 d-block"></i>
                    <p class="text-muted fs-5">Belum ada produk yang ditambahkan.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
