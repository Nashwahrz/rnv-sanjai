@extends('layouts.main')

@section('title', $product->nama_produk)

@section('content')
<div class="container my-5 fade-in">
    <div class="card shadow-lg border-0">
        <div class="card-body p-md-5">
            <div class="row g-4">

                {{-- GAMBAR PRODUK --}}
                <div class="col-md-5 d-flex justify-content-center align-items-center">
                    @if($product->foto)
                        <img src="{{ asset('storage/' . $product->foto) }}" class="img-fluid rounded product-image-detail">
                    @else
                        <img src="{{ asset('images/no-image.png') }}" class="img-fluid rounded">
                    @endif
                </div>

                <div class="col-md-7">

                    <h1 class="fw-bolder mb-3 text-rvsanjai-primary">{{ $product->nama_produk }}</h1>
                    <p class="text-muted border-bottom pb-3">{{ $product->deskripsi }}</p>
                    <p><strong><i class="fas fa-tags me-1"></i> Jenis:</strong> {{ ucfirst($product->jenis_produk) }}</p>

                    {{-- VARIAN --}}
                    <h5 class="mb-2 fw-semibold">Pilih Varian:</h5>

                    <select id="price-selector"
                            class="form-select mb-3"
                            data-produk="{{ $product->id }}">
                        @foreach ($product->prices as $p)
                            <option value="{{ $p->id }}"
                                    data-harga="{{ $p->harga }}"
                                    data-stok="{{ $p->stok }}"
                                    @if($loop->first) selected @endif>
                                {{ $p->berat }} gram - Rp {{ number_format($p->harga) }}
                            </option>
                        @endforeach
                    </select>

                    {{-- DISPLAY STOK --}}
                    <p>
                        <strong id="stok-display-{{ $product->id }}" class="text-secondary">
                            <i class="fas fa-spinner fa-spin"></i> Memuat stok...
                        </strong>
                    </p>

                    {{-- QTY GLOBAL --}}
                    <label class="fw-semibold">Jumlah:</label>
                    <input type="number" id="qty-input" value="1" min="1"
                           class="form-control mb-3" style="max-width:150px">

                    {{-- ========================= --}}
                    {{-- FORM TAMBAH KERANJANG --}}
                    {{-- ========================= --}}
                    <form action="{{ route('keranjang.tambah') }}" method="POST" id="form-add-cart">
                        @csrf
                        <input type="hidden" name="produk_id" value="{{ $product->id }}">
                        <input type="hidden" name="variasi_id" id="addcart-variasi-id" value="{{ $product->prices->first()->id }}">
                        <input type="hidden" name="qty" id="addcart-qty" value="1">
                        <button type="submit" id="btn-add-cart"
                                class="btn btn-lg w-100 btn-pesan-sekarang mb-2">
                            <i class="fas fa-shopping-cart me-2"></i> Tambah ke Keranjang
                        </button>
                    </form>

                    {{-- ========================= --}}
                    {{-- FORM BELI SEKARANG --}}
                    {{-- ========================= --}}
                    <form action="{{ route('checkout.proses') }}" method="POST" id="form-buy-now">
                        @csrf
                        <input type="hidden" name="buy_now" value="1">
                        <input type="hidden" name="produk_id" value="{{ $product->id }}">
                        <input type="hidden" name="variasi_id" id="buynow-variasi-id" value="{{ $product->prices->first()->id }}">
                        <input type="hidden" name="qty" id="buynow-qty" value="1">
                        <button type="submit" class="btn btn-lg btn-danger w-100">
                            <i class="fas fa-bolt me-2"></i> Beli Sekarang
                        </button>
                    </form>

                    {{-- KEMBALI --}}
                    <a href="{{ route('produk') }}" class="btn btn-outline-secondary w-100 mt-3">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </a>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- STYLE --}}
<style>
.product-image-detail {
    border:5px solid #ff6b35;
    border-radius:15px;
    box-shadow:0 10px 20px rgba(0,0,0,0.15);
}
.text-rvsanjai-primary { color:#ff6b35; }
.btn-pesan-sekarang { background:#ff6b35; color:white; }
.btn-pesan-sekarang:hover { background:#e55a2b; }
</style>

{{-- SCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', () => {

    const select = document.getElementById('price-selector');
    const qty = document.getElementById('qty-input');

    const stokDisplay = document.getElementById('stok-display-{{ $product->id }}');

    const addCartVariasi = document.getElementById('addcart-variasi-id');
    const addCartQty = document.getElementById('addcart-qty');

    const buyNowVariasi = document.getElementById('buynow-variasi-id');
    const buyNowQty = document.getElementById('buynow-qty');

    const updateUI = () => {
        const opt = select.options[select.selectedIndex];
        const stok = Number(opt.dataset.stok);

        // stok label
        if (stok > 0) {
            stokDisplay.className = "text-success fw-semibold";
            stokDisplay.innerHTML = `<i class="fas fa-check-circle me-1"></i> Stok tersedia (${stok})`;
        } else {
            stokDisplay.className = "text-primary fw-semibold";
            stokDisplay.innerHTML = `<i class="fas fa-clock me-1"></i> Pre Order`;
        }

        // sync variasi
        addCartVariasi.value = opt.value;
        buyNowVariasi.value = opt.value;

        // sync qty (PENTING)
        addCartQty.value = qty.value;
        buyNowQty.value = qty.value;
    };

    // qty berubah
    qty.addEventListener('input', () => {
        const val = Math.max(1, Number(qty.value) || 1);
        qty.value = val;

        addCartQty.value = val;
        buyNowQty.value = val;
    });

    // variasi berubah
    select.addEventListener('change', updateUI);

    updateUI();
});
</script>

@endsection
