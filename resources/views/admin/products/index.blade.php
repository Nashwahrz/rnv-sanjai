@extends('layouts.template')

@section('title', 'Kelola Produk')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Daftar Produk</h2>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Tambah Produk</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Desktop View --}}
    <div class="table-responsive d-none d-md-block">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th width="100">Foto</th>
                    <th>Nama Produk</th>
                    <th width="150">Jenis Produk</th>
                    <th width="250">Variasi Harga & Stok</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $p)
                    <tr>
                        <td class="text-center">
                            @if($p->foto)
                                <img src="{{ asset('storage/'.$p->foto) }}" alt="{{ $p->nama_produk }}" class="img-thumbnail" width="80">
                            @else
                                <img src="{{ asset('images/default.png') }}" alt="Default" class="img-thumbnail" width="80">
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold">{{ $p->nama_produk }}</div>
                            <small class="text-muted">{{ ucfirst($p->jenis_produk) }}</small>
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ ucfirst($p->jenis_produk) }}</span>
                        </td>
                        <td>
                            @if($p->prices->count() > 0)
                                <ul class="list-unstyled mb-0 ps-0">
                                    @foreach($p->prices as $price)
                                        <li class="d-flex justify-content-between align-items-center py-1">
                                            <span>{{ $price->berat }} gr: <strong class="text-primary">Rp {{ number_format($price->harga, 0, ',', '.') }}</strong></span>
                                            <span class="badge bg-secondary ms-2">Stok: {{ $price->stok }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-muted fst-italic">Belum ada variasi</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.products.edit', $p->id) }}" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.products.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin hapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Belum ada produk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile View --}}
    <div class="d-md-none">
        @forelse($products as $p)
            <div class="card mb-3 shadow-sm rounded-3 overflow-hidden">
                <div class="row g-0">
                    <div class="col-4 d-flex align-items-center justify-content-center p-2">
                        @if($p->foto)
                            <img src="{{ asset('storage/'.$p->foto) }}" alt="{{ $p->nama_produk }}" class="img-fluid rounded-3">
                        @else
                            <img src="{{ asset('images/default.png') }}" alt="Default" class="img-fluid rounded-3">
                        @endif
                    </div>
                    <div class="col-8">
                        <div class="card-body py-2">
                            <h6 class="card-title fw-bold mb-1">{{ $p->nama_produk }}</h6>
                            <span class="badge bg-primary mb-2">{{ ucfirst($p->jenis_produk) }}</span>
                            <div class="product-info-mobile">
                                @if($p->prices->count() > 0)
                                    <ul class="list-unstyled mb-0 ps-0">
                                        @foreach($p->prices as $price)
                                            <li class="text-sm">
                                                <span>{{ $price->berat }} gr: </span>
                                                <strong class="text-dark">Rp {{ number_format($price->harga, 0, ',', '.') }}</strong>
                                                <span class="badge bg-secondary ms-1">Stok: {{ $price->stok }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="card-text text-muted fst-italic mb-2"><small>Belum ada variasi</small></p>
                                @endif
                            </div>
                            <div class="d-flex gap-2 mt-2">
                                <a href="{{ route('admin.products.edit', $p->id) }}" class="btn btn-warning btn-sm flex-fill"><i class="fas fa-edit"></i> Edit</a>
                                <form action="{{ route('admin.products.destroy', $p->id) }}" method="POST" class="flex-fill" onsubmit="return confirm('Yakin hapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm w-100"><i class="fas fa-trash-alt"></i> Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info text-center">Belum ada produk.</div>
        @endforelse
    </div>
</div>
@endsection
