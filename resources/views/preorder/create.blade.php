@extends('layouts.main')

@section('title', 'Form Pre-Order')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-lg rounded-3 preorder-card">
                <div class="card-header bg-custom-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-box-open me-2"></i> Form Pre-Order
                    </h4>
                </div>

                <div class="card-body">

                    {{-- Tampilkan error validasi Laravel ($errors) --}}
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li><i class="fas fa-exclamation-triangle me-1"></i> {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Tampilkan pesan session (success, warning, error dari Controller) --}}
                    @if (session()->has('success') || session()->has('warning') || session()->has('error'))
                        <div class="alert alert-{{ session()->has('success') ? 'success' : (session()->has('warning') ? 'warning' : 'danger') }} mb-4">
                            <i class="fas fa-{{ session()->has('success') ? 'check-circle' : 'exclamation-triangle' }} me-1"></i>
                            {{ session('success') ?? session('warning') ?? session('error') }}
                        </div>
                    @endif

                    {{-- Peringatan jika data produk tidak ada --}}
                    @if (!isset($price) || is_null($price))
                        <div class="alert alert-danger" role="alert">
                            ⚠️ Data variasi produk tidak ditemukan atau tidak valid. Silakan kembali ke halaman produk.
                            <br><br>
                            <a href="{{ route('produk') }}" class="btn btn-sm btn-danger mt-2">
                                <i class="fas fa-arrow-left"></i> Kembali ke Produk
                            </a>
                        </div>
                    @else
                        <form action="{{ route('preorder.store') }}" method="POST">
                            @csrf

                            {{-- Produk Info --}}
                            <div class="mb-3">
                                <label class="form-label">Nama Produk</label>
                                <input type="text"
                                       class="form-control"
                                       value="{{ $price->product->nama_produk ?? 'Produk Sanjai' }}"
                                       readonly>
                            </div>

                            {{-- Varian --}}
                            <div class="mb-3">
                                <label class="form-label">Varian</label>
                                {{-- Hidden input untuk Price ID yang dikirim ke store method --}}
                                <input type="hidden" name="price_id" value="{{ $price->id ?? '' }}">
                                <input type="text"
                                       class="form-control"
                                       value="{{ ($price->berat ?? 'N/A') }} gr - Rp {{ number_format($price->harga ?? 0, 0, ',', '.') }}"
                                       readonly>
                            </div>

                            {{-- Deskripsi atau Catatan --}}
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Catatan Tambahan (Opsional)</label>
                                <textarea id="deskripsi" name="deskripsi" class="form-control" rows="3"
                                    placeholder="Contoh: Kirim akhir bulan, tanpa pedas...">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Metode Pembayaran (UI saja - Anda mungkin perlu menambahkan field ini di Controller/Model) --}}
                            <div class="mb-3">
                                <label class="form-label">Metode Pembayaran</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="metode_pembayaran" id="transfer" value="transfer" checked>
                                        <label class="form-check-label" for="transfer">Transfer Bank</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="metode_pembayaran" id="cod" value="cod">
                                        <label class="form-check-label" for="cod">Bayar di Tempat (COD)</label>
                                    </div>
                                </div>
                            </div>

                            {{-- Tombol --}}
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-custom-outline">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary btn-custom-submit">
                                    <i class="fas fa-paper-plane me-1"></i> Kirim Pre-Order
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Menggunakan skema warna yang sudah ada */
    :root {
        --color-primary-orange: #ff6b35; /* Oren utama */
        --color-primary-yellow: #ffc107; /* Kuning */
        --color-blue: #007bff; /* Biru Preorder standar */
        --color-detail: #6c757d;
    }

    .preorder-card {
        border: none;
        border-top: 5px solid var(--color-primary-orange); /* Garis atas untuk visual */
    }

    /* Ganti bg-primary default Bootstrap dengan gradien tema */
    .bg-custom-primary {
        background: linear-gradient(90deg, var(--color-primary-orange) 0%, var(--color-primary-yellow) 100%) !important;
    }

    /* Gaya untuk tombol submit (Kirim Pre-Order) */
    .btn-custom-submit {
        background: var(--color-blue);
        border-color: var(--color-blue);
        transition: all 0.3s ease;
    }
    .btn-custom-submit:hover {
        background: #0056b3;
        border-color: #0056b3;
        transform: translateY(-1px);
    }

    /* Gaya untuk tombol outline (Kembali) */
    .btn-custom-outline {
        color: var(--color-detail);
        border-color: var(--color-detail);
    }
    .btn-custom-outline:hover {
        background: var(--color-detail);
        color: white;
    }

    /* Input Readonly terlihat lebih jelas */
    .form-control[readonly] {
        background-color: #e9ecef;
        font-weight: 500;
    }
</style>
@endsection
