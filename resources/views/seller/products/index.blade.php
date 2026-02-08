@extends('layouts.app')

@section('title', 'Kelola Produk')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Kelola Produk</h2>
        <a href="{{ route('seller.products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Tambah Produk
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            @if($products->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Foto</th>
                                <th>Nama Produk</th>
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
                                            <img src="{{ asset('storage/' . $product->foto) }}" class="rounded" width="50" height="50" style="object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width: 50px; height: 50px;">
                                                <i class="bi bi-image"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $product->nama }}</strong>
                                        <div class="small text-muted">{{ Str::limit($product->deskripsi, 50) }}</div>
                                    </td>
                                    <td>{{ $product->formatted_harga }}</td>
                                    <td>
                                        <i class="bi bi-star-fill text-warning"></i> {{ number_format($product->average_rating, 1) }}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('seller.products.edit', $product) }}" class="btn btn-sm btn-warning me-1">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('seller.products.destroy', $product) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Hapus produk ini? Menu yang menggunakan produk ini juga akan terpengaruh.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <p class="text-muted">Belum ada produk.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
