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

                    {{-- Error --}}
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li><i class="fas fa-exclamation-triangle me-1"></i> {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Session Message --}}
                    @if (session()->has('success') || session()->has('warning') || session()->has('error'))
                        <div class="alert alert-{{ session()->has('success') ? 'success' : (session()->has('warning') ? 'warning' : 'danger') }} mb-4">
                            <i class="fas fa-{{ session()->has('success') ? 'check-circle' : 'exclamation-triangle' }} me-1"></i>
                            {{ session('success') ?? session('warning') ?? session('error') }}
                        </div>
                    @endif

                    @if (!isset($price) || is_null($price))
                        <div class="alert alert-danger">
                            ⚠️ Data variasi produk tidak ditemukan.
                            <br><br>
                            <a href="{{ route('produk') }}" class="btn btn-sm btn-danger mt-2">
                                <i class="fas fa-arrow-left"></i> Kembali ke Produk
                            </a>
                        </div>
                    @else
                        <form action="{{ route('preorder.store') }}" method="POST">
                            @csrf

                            {{-- Hidden untuk price_id --}}
                            <input type="hidden" name="price_id" value="{{ $price->id }}">

                            {{-- Hidden untuk qty --}}
                            <input type="hidden" name="qty" value="{{ $qty }}">

                            {{-- Nama Produk --}}
                            <div class="mb-3">
                                <label class="form-label">Nama Produk</label>
                                <input type="text" class="form-control"
                                       value="{{ $price->product->nama_produk }}"
                                       readonly>
                            </div>

                            {{-- Varian --}}
                            <div class="mb-3">
                                <label class="form-label">Varian</label>
                                <input type="text" class="form-control"
                                       value="{{ $price->berat }} gr - Rp {{ number_format($price->harga, 0, ',', '.') }}"
                                       readonly>
                            </div>

                            {{-- Qty --}}
                            <div class="mb-3">
                                <label class="form-label">Jumlah Pesanan</label>
                                <input type="text" class="form-control"
                                       value="{{ $qty }}" readonly>
                            </div>

                            {{-- Deskripsi --}}
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Catatan Tambahan (Opsional)</label>
                                <textarea id="deskripsi" name="deskripsi" class="form-control" rows="3"
                                          placeholder="Contoh: Kirim akhir bulan, tanpa pedas...">{{ old('deskripsi') }}</textarea>
                            </div>

                            {{-- Metode Pembayaran --}}
                            <div class="mb-3">
                                <label class="form-label">Metode Pembayaran</label>
                                <div class="d-flex gap-3">
                                    <label class="form-check">
                                        <input class="form-check-input" type="radio" name="metode_pembayaran" value="transfer" checked>
                                        Transfer Bank
                                    </label>
                                    <label class="form-check">
                                        <input class="form-check-input" type="radio" name="metode_pembayaran" value="cod">
                                        Bayar di Tempat (COD)
                                    </label>
                                </div>
                            </div>

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
@endsection
