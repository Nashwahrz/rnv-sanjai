@extends('layouts.main')
@section('title', 'Checkout - R&V Sanjai')

@section('content')
<div class="checkout-hero">
    <div class="container text-center">
        <div class="hero-badge"><i class="fas fa-credit-card me-2"></i>Checkout</div>
        <h1 class="hero-title">Checkout</h1>
        <p class="hero-subtitle">Isi data diri untuk melanjutkan pesanan</p>
    </div>
</div>

<div class="container py-5">
    @if (count($keranjang) > 0)
    <div class="row g-4">

        {{-- Order Summary --}}
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-list-alt me-2"></i>Ringkasan Pesanan</h5>
                </div>
                <div class="card-body">
                    @foreach ($keranjang as $item)
                    <div class="d-flex justify-content-between align-items-start mb-3 pb-3 border-bottom">
                        <div>
                            <h6 class="mb-1">{{ $item['produk'] }}</h6>
                            <small class="text-muted">{{ $item['gram'] }}</small>
                        </div>
                        <strong class="text-primary">
                            Rp {{ number_format($item['harga'], 0, ',', '.') }}
                        </strong>
                    </div>
                    @endforeach

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <h5 class="mb-0">Total</h5>
                        <h4 class="mb-0 text-primary">
                            Rp {{ number_format(array_sum(array_column($keranjang, 'total')), 0, ',', '.') }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <div class="col-lg-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Data Pembeli</h5>
                </div>
                <div class="card-body p-4">

                    {{-- ===== FORM FIX ===== --}}
                    <form action="{{ route('checkout.simpan') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="keranjang" value="{{ base64_encode(json_encode($keranjang)) }}">
                        <input type="hidden" name="total_amount" value="{{ array_sum(array_column($keranjang, 'total')) }}">

                        <div class="mb-3">
                            <label class="form-label fw-semibold"><i class="fas fa-user text-primary me-2"></i>Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="{{ Auth::user()->name }}" readonly required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold"><i class="fas fa-map-marker-alt text-danger me-2"></i>Alamat Lengkap</label>
                            <textarea name="alamat" class="form-control" rows="3" required>{{ Auth::user()->alamat }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold"><i class="fas fa-phone text-success me-2"></i>No. Telepon / WhatsApp</label>
                            <input type="text" name="telepon" id="no_hp" class="form-control" value="{{ Auth::user()->no_hp }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold"><i class="fas fa-money-bill text-warning me-2"></i>Metode Pembayaran</label>
                            <select name="metode" class="form-select" required>
                                <option value="">-- Pilih Metode --</option>
                                <option value="transfer">Transfer Bank</option>
                                <option value="cod">COD (Bayar di Tempat)</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold"><i class="fas fa-image text-info me-2"></i>Bukti Pembayaran (Opsional)</label>
                            <input type="file" name="bukti" class="form-control" accept="image/*">
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-check-circle me-2"></i>Proses Pesanan
                        </button>
                    </form>

                </div>
            </div>
        </div>

    </div>

    @else
    {{-- Jika Keranjang Kosong --}}
    <div class="text-center py-5">
        <i class="fas fa-shopping-cart fa-5x text-muted mb-4"></i>
        <h4>Keranjang Masih Kosong</h4>
        <p class="text-muted mb-4">Silakan pilih produk terlebih dahulu sebelum checkout</p>
        <a href="{{ route('produk') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-box-open me-2"></i>Pilih Produk
        </a>
    </div>
    @endif
</div>

<style>
.checkout-hero { background: linear-gradient(135deg, #667eea, #764ba2); color:white; padding:60px 0 }
.hero-badge { background:rgba(255,255,255,0.2); padding:8px 20px; border-radius:50px; display:inline-block }
.hero-title { font-size:2.5rem; font-weight:700 }
.card { border-radius:12px }
</style>

<script>
document.querySelector('form')?.addEventListener('submit', function() {
    const b = this.querySelector('button[type="submit"]');
    b.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
    b.disabled = true;
});

document.getElementById('no_hp')?.addEventListener('input', function() {
    this.value = this.value.replace(/[^0-9+]/g,'');
});
</script>
@endsection
