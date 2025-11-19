@extends('layouts.main')

@section('title', 'Produk - R&V Sanjai')

@section('content')
<div class="bg-light pt-4 pb-3">
    <div class="container text-center px-3">
        <h1 class="display-6 fw-bold text-dark mb-2">Katalog Produk Kami</h1>
        <p class="lead text-muted mb-0 fs-6">Pilih ukuran sesuai selera dan kebutuhan kamu</p>
    </div>
</div>

<div class="container py-3 px-3">
    @if(session('error'))
        <div class="alert alert-danger mb-3">{{ session('error') }}</div>
    @endif

    <div class="row g-3">
        @foreach ($produk as $item)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 shadow-sm border-0 product-card">
                    <div class="position-relative overflow-hidden" style="height:160px;">
                        @if ($item->jenis_produk == 'manis')
                            <span class="badge bg-warning position-absolute top-0 end-0 m-2 shadow-sm" style="font-size:0.7rem;">Manis</span>
                        @elseif($item->jenis_produk == 'pedas')
                            <span class="badge bg-danger position-absolute top-0 end-0 m-2 shadow-sm" style="font-size:0.7rem;">Pedas</span>
                        @endif

                        <img src="{{ $item->foto ? asset('storage/' . $item->foto) : asset('images/default.png') }}"
                             class="card-img-top w-100 h-100 object-fit-cover" alt="{{ $item->nama_produk }}">
                    </div>

                    <div class="card-body d-flex flex-column p-2 p-md-3">
                        <h5 class="card-title fw-bold text-dark mb-1 fs-6">{{ $item->nama_produk }}</h5>
                        <p class="card-text text-muted mb-2" style="font-size:0.75rem;">{{ Str::limit($item->deskripsi, 40) }}</p>

                        <div class="d-grid gap-1 mt-auto">
                            <a href="{{ route('produk.show', $item->id) }}" class="btn btn-sm btn-warning fw-bold" style="font-size:0.8rem; padding:0.45rem;">
                                <i class="fas fa-shopping-cart me-1"></i> Checkout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<style>
    @media (max-width: 576px) {
        .display-6 {
            font-size: 1.5rem;
        }
        .lead {
            font-size: 0.9rem;
        }
        .card-body {
            padding: 0.75rem !important;
        }
        .product-card .card-img-top {
            height: 140px !important;
        }
    }

    .product-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .product-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
    }

    .badge {
        z-index: 10;
    }

    .btn {
        border-radius: 0.375rem;
        transition: all 0.2s ease;
    }

    .btn:active {
        transform: scale(0.98);
    }
</style>
@endsection
