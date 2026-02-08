@extends('layouts.app')

@section('title', 'Dashboard Seller')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4">Dashboard Seller</h2>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-4 col-lg-2">
            <div class="card h-100 bg-primary text-white">
                <div class="card-body text-center">
                    <h3 class="display-6 fw-bold">{{ $stats['pending_orders'] }}</h3>
                    <p class="mb-0">Pesanan Pending</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-2">
            <div class="card h-100 bg-success text-white">
                <div class="card-body text-center">
                    <h3 class="display-6 fw-bold">{{ $stats['total_orders'] }}</h3>
                    <p class="mb-0">Total Pesanan</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-2">
            <div class="card h-100 bg-info text-white">
                <div class="card-body text-center">
                    <h3 class="display-6 fw-bold">{{ $stats['total_menus'] }}</h3>
                    <p class="mb-0">Menu Aktif</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 bg-warning text-dark">
                <div class="card-body text-center">
                    <h3 class="display-6 fw-bold">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
                    <p class="mb-0">Total Pendapatan</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 bg-secondary text-white">
                <div class="card-body text-center">
                    <h3 class="display-6 fw-bold">{{ $stats['total_buyers'] }}</h3>
                    <p class="mb-0">Total Pelanggan</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Menu Cepat</h5>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('seller.menus.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Buat Menu Baru
                        </a>
                        <a href="{{ route('seller.products.create') }}" class="btn btn-outline-primary">
                            <i class="bi bi-box-seam me-2"></i>Tambah Produk
                        </a>
                        <a href="{{ route('seller.orders.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-list-check me-2"></i>Kelola Pesanan
                        </a>
                        <a href="{{ route('seller.menus.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-calendar-event me-2"></i>Kelola Menu
                        </a>
                        <a href="{{ route('seller.products.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-grid me-2"></i>Kelola Produk
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Pesanan Terbaru</h5>
        </div>
        <div class="card-body">
            @if($recent_orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Pembeli</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th class="text-center">Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recent_orders as $order)
                                <tr>
                                    <td>{{ $order->order_id }}</td>
                                    <td>{{ $order->user->nama ?? 'Guest' }}</td>
                                    <td>{{ $order->tgl_pesan->format('d M Y') }}</td>
                                    <td>{{ $order->formatted_total }}</td>
                                    <td class="text-center">{!! $order->status_badge !!}</td>
                                    <td>
                                        <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-primary">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center text-muted my-4">Belum ada pesanan terbaru.</p>
            @endif
        </div>
    </div>
</div>
@endsection
