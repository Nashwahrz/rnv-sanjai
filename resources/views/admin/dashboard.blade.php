@extends('layouts.template')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container py-4">

    {{-- Card Statistik --}}
    <div class="row mb-4">

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5>Total Pesanan Masuk</h5>
                    <h2 class="fw-bold">{{ $totalOrders }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5>Total Pendapatan</h5>
                    <h2 class="fw-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5>Pesanan Belum Diproses</h5>
                    <h2 class="fw-bold">{{ $pendingOrders }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5>Produk Terlaris</h5>
                    <h4 class="fw-bold">
                       {{ $bestProduct && $bestProduct->product ? $bestProduct->product->nama_produk : 'Belum ada data' }}

                    </h4>
                    @if($bestProduct)
                        <small>Terjual: {{ $bestProduct->total_sold }} pcs</small>
                    @endif
                </div>
            </div>
        </div>

    </div>

    {{-- Pesanan terbaru --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h5 class="mb-0">Pesanan Terbaru</h5>
        </div>
        <div class="card-body">

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Pemesan</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latestOrders as $order)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $order->user->name }}</td>
                        <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge
                                @if($order->status == 'pending') bg-warning
                                @elseif($order->status == 'proses') bg-primary
                                @elseif($order->status == 'selesai') bg-success
                                @else bg-secondary
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>{{ $order->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada pesanan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

</div>
@endsection
