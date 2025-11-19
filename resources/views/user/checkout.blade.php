@extends('layouts.main')

@section('title', 'Checkout - R&V Sanjai')

@section('content')
<div class="checkout-hero">
    <div class="container text-center">
        <div class="hero-badge">
            <i class="fas fa-credit-card me-2"></i>Checkout
        </div>
        <h1 class="hero-title">Checkout</h1>
        <p class="hero-subtitle">Isi data diri untuk melanjutkan pesanan</p>
    </div>
</div>

<div class="container py-4">
    @if (count($keranjang) > 0)
    <div class="checkout-container">
        <!-- Ringkasan Pesanan -->
        <div class="order-summary">
            <div class="summary-card">
                <h4 class="summary-title">
                    <i class="fas fa-list-alt me-2"></i>Ringkasan Pesanan
                </h4>

                <div class="order-items">
                    @foreach ($keranjang as $index => $item)
                    <div class="order-item">
                        <div class="item-info">
                            <h6>{{ $item['produk'] }}</h6>
                            <span class="item-size">{{ $item['gram'] }}</span>
                        </div>
                        <div class="item-price">
                            <strong>Rp {{ number_format($item['harga'], 0, ',', '.') }}</strong>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="order-total">
                    <div class="total-row">
                        <span>Total Pesanan</span>
                        <strong>Rp {{ number_format(array_sum(array_column($keranjang, 'harga')), 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
        </div>

       <!-- Form Data Pembeli -->
<div class="checkout-form">
    <div class="form-card">
        <h4 class="form-title">
            <i class="fas fa-user me-2"></i>Data Pembeli
        </h4>

        <form action="{{ route('checkout.proses') }}" method="POST">
            @csrf

            <input type="hidden" name="keranjang" value="{{ base64_encode(json_encode($keranjang)) }}">

            <div class="form-group">
                <label for="nama" class="form-label">
                    <i class="fas fa-user me-1"></i>Nama Lengkap
                </label>
                <input type="text" name="nama" id="nama" class="form-control"
                    value="{{ Auth::check() ? Auth::user()->name : '' }}"
                    @if(Auth::check()) readonly @endif
                    required>
            </div>

            <div class="form-group">
                <label for="alamat" class="form-label">
                    <i class="fas fa-map-marker-alt me-1"></i>Alamat Lengkap
                </label>
                <textarea name="alamat" id="alamat" class="form-control" rows="3" required></textarea>
            </div>

            <div class="form-group">
                <label for="telepon" class="form-label">
                    <i class="fas fa-phone me-1"></i>No. Telepon / WhatsApp
                </label>
                <input type="text" name="telepon" id="telepon" class="form-control" required>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-check-circle me-2"></i>
                Proses Pesanan
            </button>
        </form>
    </div>
</div>

    </div>
    @else
    <div class="empty-checkout">
        <div class="empty-icon">
            <i class="fas fa-shopping-cart"></i>
        </div>
        <h4>Keranjang Masih Kosong</h4>
        <p>Silakan pilih produk terlebih dahulu sebelum checkout</p>
        <a href="{{ route('produk') }}" class="btn-shop">
            <i class="fas fa-box-open me-2"></i>
            Pilih Produk
        </a>
    </div>
    @endif
</div>

 <style>
        .checkout-hero{background:linear-gradient(135deg,#f8f9fa 0%,#e9ecef 100%);padding:2.5rem 0;position:relative}
        .checkout-hero::before{content:'';position:absolute;top:-50%;left:-20%;width:300px;height:300px;background:linear-gradient(135deg,rgba(255,107,53,.1),rgba(255,193,7,.1));border-radius:50%;z-index:1}
        .hero-badge{display:inline-flex;align-items:center;background:linear-gradient(135deg,#ff6b35,#ffc107);color:white;padding:8px 20px;border-radius:25px;font-size:.9rem;font-weight:600;margin-bottom:1rem;box-shadow:0 4px 15px rgba(255,107,53,.3);position:relative;z-index:2}
        .hero-title{font-size:2.2rem;font-weight:800;color:#8B4513;margin-bottom:.5rem;position:relative;z-index:2}
        .hero-subtitle{font-size:1.1rem;color:#6c757d;margin:0;position:relative;z-index:2}
        .checkout-container{display:grid;grid-template-columns:1fr 1fr;gap:2rem;margin-top:2rem}
        .summary-card,.form-card{background:white;padding:1.5rem;border-radius:15px;box-shadow:0 4px 12px rgba(0,0,0,.08);height:fit-content}
        .summary-title,.form-title{color:#333;margin-bottom:1.5rem;display:flex;align-items:center;font-size:1.3rem;font-weight:600}
        .order-items{margin-bottom:1.5rem}
        .order-item{display:flex;justify-content:space-between;align-items:center;padding:1rem;background:#f8f9fa;border-radius:10px;margin-bottom:.75rem;border-left:4px solid #ff6b35}
        .item-info h6{margin:0;color:#333;font-weight:600}
        .item-size{color:#6c757d;font-size:.9rem}
        .item-price strong{color:#dc6900;font-size:1.1rem}
        .order-total{border-top:2px solid #e9ecef;padding-top:1rem}
        .total-row{display:flex;justify-content:space-between;align-items:center;font-size:1.2rem;color:#333}
        .total-row strong{color:#dc6900}
        .form-group{margin-bottom:1.5rem}
        .form-label{color:#333;font-weight:500;margin-bottom:.5rem;display:flex;align-items:center}
        .form-control{border-radius:10px;border:2px solid #e9ecef;padding:10px 15px;transition:all .3s ease}
        .form-control:focus{border-color:#ff6b35;box-shadow:0 0 0 .2rem rgba(255,107,53,.25)}
        .btn-submit{width:100%;background:linear-gradient(135deg,#ff6b35,#ffc107);color:white;border:none;padding:12px;border-radius:10px;font-weight:600;display:flex;align-items:center;justify-content:center;transition:all .3s ease;font-size:1.1rem}
        .btn-submit:hover{background:linear-gradient(135deg,#e55a2b,#e6ac00);transform:translateY(-1px);box-shadow:0 6px 15px rgba(255,107,53,.3)}
        .empty-checkout{text-align:center;padding:3rem 1rem;background:white;border-radius:15px;box-shadow:0 4px 12px rgba(0,0,0,.08)}
        .empty-icon{font-size:4rem;color:#6c757d;margin-bottom:1rem}
        .empty-checkout h4{color:#333;margin-bottom:.5rem}
        .empty-checkout p{color:#6c757d;margin-bottom:2rem}
        .btn-shop{background:linear-gradient(135deg,#ff6b35,#ffc107);color:white;border:none;padding:12px 30px;border-radius:25px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;transition:all .3s ease}
        .btn-shop:hover{background:linear-gradient(135deg,#e55a2b,#e6ac00);transform:translateY(-2px);box-shadow:0 6px 15px rgba(255,107,53,.3);color:white}
        @media (max-width:768px){
            .hero-title{font-size:1.8rem}
            .checkout-container{grid-template-columns:1fr;gap:1rem}
            .order-item{flex-direction:column;gap:.5rem;text-align:center}
        }
    </style>

<script>
document.querySelector('form').addEventListener('submit', function(e) {
    const b = this.querySelector('.btn-submit');
    b.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
    b.disabled = true;
});

document.getElementById('telepon').addEventListener('input', function(e){
    this.value = this.value.replace(/[^0-9+]/g,'');
});
</script>
@endsection
