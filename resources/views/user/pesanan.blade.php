@extends('layouts.main')

@section('content')
<div class="container py-5">

    {{-- ======================== HEADER ======================== --}}
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold text-dark">
                <span class="text-orange">🛍️</span> Pesanan Saya
            </h2>
            <p class="text-muted">Kelola dan pantau status pesanan & preorder Anda</p>
        </div>
    </div>

    {{-- ======================== ORDER KOSONG ======================== --}}
    @if($orders->isEmpty() && $preorders->isEmpty())
        <div class="text-center py-5">
            <div class="empty-state-icon mb-3">
                <svg width="80" height="80" fill="currentColor" class="text-orange" viewBox="0 0 16 16">
                    <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                </svg>
            </div>
            <h5 class="text-dark fw-bold">Belum Ada Pesanan</h5>
            <p class="text-muted">Kamu belum memiliki pesanan atau preorder.</p>
        </div>
    @endif

    {{-- ======================== ORDER SECTION ======================== --}}
    @if(!$orders->isEmpty())
        <h4 class="fw-bold text-dark mb-3 mt-4">📦 Order</h4>

        @foreach ($orders as $order)
            <div class="card border-0 shadow-sm mb-3 order-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="mb-1 fw-bold text-dark">
                                <span class="text-orange">#{{ $order->id }}</span>
                            </h5>
                            <small class="text-muted">
                                <i class="bi bi-calendar3 text-orange"></i> {{ $order->created_at->format('d M Y, H:i') }}
                            </small>
                        </div>
                        <span class="badge rounded-pill px-3 py-2 status-badge
                            @if($order->status == 'pending') badge-pending
                            @elseif($order->status == 'diproses') badge-proses
                            @elseif($order->status == 'dikirim') badge-kirim
                            @elseif($order->status == 'selesai') badge-selesai
                            @elseif($order->status == 'batal') badge-batal
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>

                    {{-- ITEMS --}}
                    <div class="border-top border-bottom py-3 my-3 order-items">
                        @foreach ($order->items as $item)
                            <div class="d-flex justify-content-between align-items-start mb-3 pb-3 item-row">
                                <div class="flex-grow-1">
                                    <h6 class="mb-2 fw-semibold text-dark">{{ $item->product->nama_produk }}</h6>
                                    <div class="d-flex flex-wrap gap-3 text-muted small">
                                        <span class="item-info">
                                            <i class="bi bi-tag-fill text-orange"></i>
                                            Rp {{ number_format($item->price->harga, 0, ',', '.') }}
                                        </span>

                                        @if(!empty($item->price->berat))
                                        <span class="item-info">
                                            <i class="bi bi-box-seam text-orange"></i>
                                            {{ $item->price->berat }}g
                                        </span>
                                        @endif

                                        <span class="item-info">
                                            <i class="bi bi-cart-check-fill text-orange"></i>
                                            Qty: {{ $item->quantity }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-between align-items-center total-section">
                        <span class="text-muted fw-semibold">Total Pembayaran</span>
                        <h5 class="mb-0 fw-bold text-orange">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </h5>
                    </div>
                </div>
            </div>
        @endforeach
    @endif


    {{-- ======================== PREORDER SECTION ======================== --}}
    @if(!$preorders->isEmpty())
        <h4 class="fw-bold text-dark mb-3 mt-5">📝 Preorder</h4>

        @foreach ($preorders as $po)
            <div class="card border-0 shadow-sm mb-3 order-card">
                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="mb-1 fw-bold text-dark">
                                <span class="text-orange">#PO{{ $po->id }}</span>
                            </h5>
                            <small class="text-muted">
                                <i class="bi bi-calendar3 text-orange"></i> {{ $po->tanggal_preorder }}
                            </small>
                        </div>

                        <span class="badge rounded-pill px-3 py-2 badge-proses">
                            Pre-order
                        </span>
                    </div>

                    {{-- ITEM --}}
                    <div class="border-top border-bottom py-3 my-3 order-items">
                        <div class="d-flex justify-content-between align-items-start mb-3 pb-3 item-row">
                            <div class="flex-grow-1">
                                <h6 class="mb-2 fw-semibold text-dark">
                                    {{ $po->price->product->nama_produk }}
                                </h6>

                                <div class="d-flex flex-wrap gap-3 text-muted small">

                                    <span class="item-info">
                                        <i class="bi bi-tag-fill text-orange"></i>
                                        Variasi: {{ $po->price->variasi }}
                                    </span>

                                    <span class="item-info">
                                        <i class="bi bi-cart-check-fill text-orange"></i>
                                        Qty: {{ $po->qty }}
                                    </span>
                                </div>

                                @if($po->deskripsi)
                                <p class="mt-2 small text-muted">
                                    <i class="bi bi-chat-left-text text-orange"></i> {{ $po->deskripsi }}
                                </p>
                                @endif

                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center total-section">
                        <span class="text-muted fw-semibold">Status</span>
                        <h6 class="mb-0 fw-bold text-orange">Menunggu Diproses</h6>
                    </div>

                </div>
            </div>
        @endforeach
    @endif

</div>

{{-- ======================== CSS ======================== --}}
<style>
:root {
    --orange-primary: #ff6b35;
    --orange-light: #fff4f0;
}

.text-orange { color: var(--orange-primary) !important; }

.order-card {
    transition: all 0.3s ease;
    border-left: 4px solid transparent !important;
}
.order-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 .75rem 2rem rgba(255, 107, 53, 0.15) !important;
    border-left: 4px solid var(--orange-primary) !important;
}

.status-badge {
    font-size: 0.8rem;
    font-weight: 600;
    letter-spacing: 0.3px;
}

.badge-pending { background: #ffc107; color:#000; }
.badge-proses { background: #ff6b35; color:#fff; }
.badge-kirim { background: #17a2b8; color:#fff; }
.badge-selesai { background: #28a745; color:#fff; }
.badge-batal { background: #dc3545; color:#fff; }

.item-row { border-bottom: 1px dashed #e9ecef; }
.item-row:last-child { border-bottom: none !important; }

.item-info { display: inline-flex; align-items: center; gap: 5px; }

.total-section {
    background: var(--orange-light);
    padding: 15px;
    border-radius: 8px;
    margin-top: 10px;
}

.empty-state-icon {
    animation: float 3s ease-in-out infinite;
}
@keyframes float {
    0%,100% { transform: translateY(0); }
    50%     { transform: translateY(-10px); }
}
</style>
@endsection
