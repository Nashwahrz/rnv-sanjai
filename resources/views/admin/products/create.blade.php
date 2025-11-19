@extends('layouts.template')

@section('title', 'Tambah Produk')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Tambah Produk Baru</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Nama Produk --}}
                <div class="mb-3">
                    <label for="nama_produk" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" id="nama_produk" name="nama_produk" class="form-control" placeholder="Contoh: Keripik Singkong" required>
                </div>

                {{-- Jenis Produk --}}
                <div class="mb-3">
                    <label for="jenis_produk" class="form-label">Jenis Produk <span class="text-danger">*</span></label>
                    <select id="jenis_produk" name="jenis_produk" class="form-select" required>
                        <option value="manis">Manis</option>
                        <option value="pedas">Pedas</option>
                    </select>
                </div>

                {{-- Deskripsi --}}
                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" class="form-control" rows="4" placeholder="Jelaskan detail produk..."></textarea>
                </div>

                {{-- Foto Produk --}}
                <div class="mb-3">
                    <label for="foto" class="form-label">Foto Produk</label>
                    <input type="file" id="foto" name="foto" class="form-control">
                </div>

                {{-- Variasi Harga + Stok Section --}}
                <h5 class="mt-4 mb-3 d-flex align-items-center"><i class="fas fa-boxes me-2"></i> Variasi Harga & Stok</h5>
                <div id="variasi-container">
                    {{-- Initial variation row --}}
                    <div class="variasi-item row g-2 mb-3 align-items-center">
                        <div class="col-12 col-sm-4">
                            <input type="number" name="variasi[0][berat]" class="form-control" placeholder="Berat (gram)" required>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="variasi[0][harga]" class="form-control" placeholder="Harga" required>
                            </div>
                        </div>
                        <div class="col-12 col-sm-3">
                            <input type="number" name="variasi[0][stok]" class="form-control" placeholder="Stok" required>
                        </div>
                        <div class="col-12 col-sm-1 d-flex justify-content-center">
                            <button type="button" class="btn btn-danger btn-sm w-100 remove-variasi-btn" style="display:none;"><i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-outline-primary" id="tambahVariasiBtn"><i class="fas fa-plus me-2"></i> Tambah Varian</button>

                <hr class="mt-4">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save me-2"></i> Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script for dynamic variation fields --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let index = 1;
        const container = document.getElementById('variasi-container');
        const tambahBtn = document.getElementById('tambahVariasiBtn');

        function updateRemoveButtons() {
            const variasiItems = container.querySelectorAll('.variasi-item');
            if (variasiItems.length > 1) {
                variasiItems.forEach(item => {
                    item.querySelector('.remove-variasi-btn').style.display = 'block';
                });
            } else {
                variasiItems[0].querySelector('.remove-variasi-btn').style.display = 'none';
            }
        }

        tambahBtn.addEventListener('click', function() {
            const html = `
                <div class="variasi-item row g-2 mb-3 align-items-center">
                    <div class="col-12 col-sm-4">
                        <input type="number" name="variasi[${index}][berat]" class="form-control" placeholder="Berat (gram)" required>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="variasi[${index}][harga]" class="form-control" placeholder="Harga" required>
                        </div>
                    </div>
                    <div class="col-12 col-sm-3">
                        <input type="number" name="variasi[${index}][stok]" class="form-control" placeholder="Stok" required>
                    </div>
                    <div class="col-12 col-sm-1 d-flex justify-content-center">
                        <button type="button" class="btn btn-danger btn-sm w-100 remove-variasi-btn"><i class="fas fa-minus"></i></button>
                    </div>
                </div>`;
            container.insertAdjacentHTML('beforeend', html);
            index++;
            updateRemoveButtons();
        });

        container.addEventListener('click', function(event) {
            if (event.target.closest('.remove-variasi-btn')) {
                const item = event.target.closest('.variasi-item');
                item.remove();
                updateRemoveButtons();
            }
        });

        updateRemoveButtons(); // Initial call to hide remove button for the first row
    });
</script>
@endsection
