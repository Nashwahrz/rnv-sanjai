@extends('layouts.main')

@section('title', 'Keranjang - R&V Sanjai')

@section('content')
    <div class="keranjang-hero">
        <div class="container text-center">
            <div class="hero-badge">
                <i class="fas fa-shopping-cart me-2"></i>Keranjang Belanja
            </div>
            <h1 class="hero-title">Keranjang Anda</h1>
        </div>
    </div>

    <div class="container py-4">
        @if (session('success'))
            <div class="alert alert-success alert-modern">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            </div>
        @endif

        @if (count($keranjang) > 0)
            <div class="cart-container">
                <div class="cart-items">
                    @foreach ($keranjang as $index => $item)
                        <div class="cart-item">
                            <div class="item-image">
                                {{-- Kalau ada foto produk di DB --}}
                                @if (!empty($item['foto']))
                                    <img src="{{ asset('storage/' . $item['foto']) }}" alt="{{ $item['produk'] }}">
                                @else
                                    <img src="{{ asset('images/keripik.jpg') }}" alt="{{ $item['produk'] }}">
                                @endif
                            </div>
                            <div class="item-details">
                                <h6 class="item-name">{{ $item['produk'] }}</h6>
                                <p class="item-size">
                                    <i class="fas fa-weight-hanging me-1"></i>{{ $item['gram'] }}
                                </p>
                            </div>
                            <div class="item-price">
                                <strong>Rp {{ number_format($item['harga'], 0, ',', '.') }}</strong>
                            </div>
                            <div class="item-actions">
                                <form action="{{ route('keranjang.remove', $index) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-remove" type="submit">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="cart-summary">
                    <div class="summary-card">
                        <h5 class="summary-title">
                            <i class="fas fa-receipt me-2"></i>Ringkasan Pesanan
                        </h5>

                        <div class="summary-items">
                            <div class="summary-row">
                                <span>Total Item</span>
                                <span>{{ count($keranjang) }} produk</span>
                            </div>
                            <div class="summary-row">
                                <span>Subtotal</span>
                                <span>Rp {{ number_format(array_sum(array_column($keranjang, 'harga')), 0, ',', '.') }}</span>
                            </div>
                            <div class="summary-row total">
                                <span>Total</span>
                                <strong>Rp {{ number_format(array_sum(array_column($keranjang, 'harga')), 0, ',', '.') }}</strong>
                            </div>
                        </div>

                        <div class="checkout-section">
                            <a href="{{ route('checkout') }}" class="btn-checkout">
                                <i class="fas fa-credit-card me-2"></i>
                                Checkout Sekarang
                            </a>
                            <a href="{{ route('produk') }}" class="btn-continue">
                                <i class="fas fa-arrow-left me-2"></i>
                                Lanjut Belanja
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="empty-cart">
                <div class="empty-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h4>Keranjang Masih Kosong</h4>
                <p>Belum ada produk yang ditambahkan ke keranjang</p>
                <a href="{{ route('produk') }}" class="btn-shop">
                    <i class="fas fa-box-open me-2"></i>
                    Mulai Belanja
                </a>
            </div>
        @endif
    </div>

    <style>
        .keranjang-hero{background:linear-gradient(135deg,#f8f9fa 0%,#e9ecef 100%);padding:2.5rem 0;position:relative}
        .keranjang-hero::before{content:'';position:absolute;top:-50%;right:-20%;width:300px;height:300px;background:linear-gradient(135deg,rgba(255,107,53,.1),rgba(255,193,7,.1));border-radius:50%;z-index:1}
        .hero-badge{display:inline-flex;align-items:center;background:linear-gradient(135deg,#ff6b35,#ffc107);color:white;padding:8px 20px;border-radius:25px;font-size:.9rem;font-weight:600;margin-bottom:1rem;box-shadow:0 4px 15px rgba(255,107,53,.3);position:relative;z-index:2}
        .hero-title{font-size:2.2rem;font-weight:800;color:#8B4513;margin:0;position:relative;z-index:2}
        .alert-modern{border:none;border-radius:12px;padding:1rem 1.5rem;background:#d4edda;color:#155724;border-left:4px solid #28a745;box-shadow:0 4px 12px rgba(40,167,69,.2)}
        .cart-container{display:grid;grid-template-columns:2fr 1fr;gap:2rem;margin-top:2rem}
        .cart-items{display:flex;flex-direction:column;gap:1rem}
        .cart-item{background:white;padding:1.5rem;border-radius:15px;box-shadow:0 4px 12px rgba(0,0,0,.08);display:flex;align-items:center;gap:1rem;transition:all .3s ease}
        .cart-item:hover{transform:translateY(-2px);box-shadow:0 8px 20px rgba(0,0,0,.15)}
        .item-image{width:80px;height:80px;border-radius:10px;overflow:hidden;flex-shrink:0}
        .item-image img{width:100%;height:100%;object-fit:cover}
        .item-details{flex:1}
        .item-name{color:#333;font-weight:600;margin:0 0 .25rem 0;font-size:1.1rem}
        .item-size{color:#6c757d;margin:0;font-size:.9rem}
        .item-price{color:#dc6900;font-size:1.1rem;margin:0 1rem}
        .item-actions .btn-remove{background:#ff4757;color:white;border:none;width:35px;height:35px;border-radius:8px;display:flex;align-items:center;justify-content:center;transition:all .3s ease;cursor:pointer}
        .btn-remove:hover{background:#ff3742;transform:scale(1.05)}
        .summary-card{background:white;padding:1.5rem;border-radius:15px;box-shadow:0 4px 12px rgba(0,0,0,.08);position:sticky;top:20px}
        .summary-title{color:#333;margin-bottom:1rem;display:flex;align-items:center}
        .summary-items{border-top:1px solid #e9ecef;padding-top:1rem}
        .summary-row{display:flex;justify-content:space-between;margin-bottom:.75rem;color:#6c757d}
        .summary-row.total{border-top:1px solid #e9ecef;padding-top:.75rem;color:#333;font-size:1.1rem}
        .checkout-section{margin-top:1.5rem}
        .btn-checkout{width:100%;background:linear-gradient(135deg,#ff6b35,#ffc107);color:white;border:none;padding:12px;border-radius:10px;font-weight:600;display:flex;align-items:center;justify-content:center;text-decoration:none;margin-bottom:.75rem;transition:all .3s ease}
        .btn-checkout:hover{background:linear-gradient(135deg,#e55a2b,#e6ac00);transform:translateY(-1px);box-shadow:0 6px 15px rgba(255,107,53,.3);color:white}
        .btn-continue{width:100%;background:white;color:#6c757d;border:2px solid #e9ecef;padding:10px;border-radius:10px;font-weight:500;display:flex;align-items:center;justify-content:center;text-decoration:none;transition:all .3s ease}
        .btn-continue:hover{border-color:#ff6b35;color:#ff6b35}
        .empty-cart{text-align:center;padding:3rem 1rem;background:white;border-radius:15px;box-shadow:0 4px 12px rgba(0,0,0,.08)}
        .empty-icon{font-size:4rem;color:#6c757d;margin-bottom:1rem}
        .empty-cart h4{color:#333;margin-bottom:.5rem}
        .empty-cart p{color:#6c757d;margin-bottom:2rem}
        .btn-shop{background:linear-gradient(135deg,#ff6b35,#ffc107);color:white;border:none;padding:12px 30px;border-radius:25px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;transition:all .3s ease}
        .btn-shop:hover{background:linear-gradient(135deg,#e55a2b,#e6ac00);transform:translateY(-2px);box-shadow:0 6px 15px rgba(255,107,53,.3);color:white}
        @media (max-width:768px){
            .hero-title{font-size:1.8rem}
            .cart-container{grid-template-columns:1fr;gap:1rem}
            .cart-item{flex-direction:column;text-align:center;gap:.75rem}
            .item-image{width:60px;height:60px}
            .item-price{margin:0}
            .summary-card{position:relative}
        }
    </style>
@endsection
