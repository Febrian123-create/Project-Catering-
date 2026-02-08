@extends('layouts.app')

@section('title', 'Kelola Menu')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Kelola Menu</h2>
        <a href="{{ route('seller.menus.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Tambah Menu
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            @if($menus->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID Menu</th>
                                <th>Tanggal Tersedia</th>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($menus as $menu)
                                <tr>
                                    <td>{{ $menu->menu_id }}</td>
                                    <td>
                                        <span class="badge bg-info text-dark">
                                            {{ $menu->tgl_tersedia->format('l, d M Y') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($menu->product->foto)
                                                <img src="{{ asset('storage/' . $menu->product->foto) }}" 
                                                    class="rounded me-2" width="40" height="40" style="object-fit: cover;">
                                            @endif
                                            {{ $menu->product->nama }}
                                        </div>
                                    </td>
                                    <td>{{ $menu->product->formatted_harga }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('seller.menus.edit', $menu) }}" class="btn btn-sm btn-warning me-1">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('seller.menus.destroy', $menu) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Yakin ingin menghapus menu ini?')">
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
                    {{ $menus->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <p class="text-muted">Belum ada menu yang dibuat.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
