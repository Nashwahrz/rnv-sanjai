@extends('layouts.main')

@section('title', 'Beranda - R&V Sanjai')

@section('content')
    <div class="hero-section">
        <div class="container-fluid">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-6 col-md-12 hero-content">
                    <div class="hero-text px-4 px-lg-5">
                        <h1 class="hero-title">Rasakan Gurihnya R&V Sanjai</h1>
                        <p class="hero-subtitle">Keripik khas Minangkabau dengan cita rasa otentik.</p>
                        <div class="hero-buttons mt-4">
                            <a href="{{ route('produk') }}" class="btn btn-primary btn-lg me-3">Pesan Sekarang</a>
                            <a href="{{ route('tentang') }}" class="btn btn-outline-primary btn-lg">Tentang Kami</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 hero-image">
                    <div class="product-showcase text-center">
                        <img src="{{ asset('images/keripik.jpg') }}"
                            class="img-fluid hero-product-img"
                            alt="Keripik R&V Sanjai">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="best-seller-section py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">🔥 Produk Best Seller</h2>
                <p class="section-subtitle">Produk terfavorit pelanggan kami</p>
            </div>

            <div class="row">
                @foreach($bestSeller as $item)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="product-card">
                            <div class="product-image">
                                @if($item->foto)
                                    <img src="{{ asset('storage/'.$item->foto) }}" class="card-img-top" alt="{{ $item->nama_produk }}">
                                @else
                                    <img src="{{ asset('images/default.png') }}" class="card-img-top" alt="{{ $item->nama_produk }}">
                                @endif
                                <div class="product-badge">Best Seller</div>
                            </div>
                            <div class="card-body">
                                <h5 class="product-title">{{ $item->nama_produk }}</h5>
                                <p class="product-description">{{ Str::limit($item->deskripsi, 60, '...') }}</p>
                                <div class="product-price">
                                    @if($item->prices->count() > 0)
                                        <span class="price">Rp {{ number_format($item->prices->first()->harga, 0, ',', '.') }}</span>
                                        <span class="price-unit">/{{ $item->prices->first()->berat }} gr</span>
                                    @else
                                        <span class="text-muted">Harga belum tersedia</span>
                                    @endif
                                </div>
                                <a href="{{ route('produk') }}" class="btn btn-warning btn-block">Lihat Produk</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="about-section py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="https://picsum.photos/600/400?random=5" class="img-fluid rounded shadow" alt="Tentang R&V Sanjai">
                </div>
                <div class="col-lg-6">
                    <div class="about-content ps-lg-4">
                        <h3 class="about-title">Tentang R&V Sanjai</h3>
                        <p class="about-text">
                            UMKM Keripik Sanjai yang telah berdiri sejak 2021, menghadirkan cita rasa
                            autentik Minangkabau dengan kualitas terbaik. Kami berkomitmen untuk
                            melestarikan tradisi kuliner Padang dengan inovasi modern.
                        </p>
                        <a href="{{ route('tentang') }}" class="btn btn-primary">Pelajari Lebih Lanjut</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Definisi Variabel untuk Keterbacaan & Reduksi Pengulangan */
        :root {
            --color-primary: #dc6900; /* Oren gelap - Warna utama tombol & harga */
            --color-primary-hover: #b85600;
            --color-brown-dark: #8B4513; /* Coklat - Warna judul */
            --color-text-secondary: #6c757d;
            --shadow-light: rgba(0,0,0,.08);
            --shadow-heavy: rgba(0,0,0,.15);
            --radius-default: 15px;
        }

        /* --- Hero Section --- */
        .hero-section {
            background: linear-gradient(135deg,#f8f9fa 0%,#e9ecef 100%);
            position: relative;
            overflow: hidden
        }
        .hero-title, .section-title, .about-title {
            color: var(--color-brown-dark);
            font-weight: 700;
        }
        .hero-title {
            font-size: 3rem;
            margin-bottom: 1rem;
            line-height: 1.2
        }
        .hero-subtitle {
            font-size: 1.2rem;
            color: var(--color-text-secondary);
            margin-bottom: 2rem
        }
        .hero-buttons .btn {
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 50px;
            transition: all .3s ease
        }
        .btn-primary, .btn-outline-primary:hover {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
        }
        .btn-primary:hover {
            background-color: var(--color-primary-hover);
            border-color: var(--color-primary-hover);
            transform: translateY(-2px)
        }
        .btn-outline-primary {
            color: var(--color-primary);
            border-color: var(--color-primary)
        }
        .btn-outline-primary:hover {
            color: #fff; /* Tambahkan ini agar teks berubah putih saat hover */
            transform: translateY(-2px)
        }
        .hero-product-img {
            max-width: 350px;
            width: 100%;
            height: auto;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,.15);
            transition: transform .3s ease;
        }
        .hero-product-img:hover { transform: scale(1.05) }

        /* --- Best Seller Section & About --- */
        .section-title { margin-bottom: .5rem }
        .section-subtitle, .about-text { color: var(--color-text-secondary); }
        .section-subtitle { font-size: 1.1rem }

        /* --- Product Card --- */
        .product-card {
            background: #fff;
            border-radius: var(--radius-default);
            overflow: hidden;
            box-shadow: 0 5px 15px var(--shadow-light);
            transition: all .3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px var(--shadow-heavy)
        }
        .product-image { position: relative; overflow: hidden }
        .product-image img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform .3s ease
        }
        .product-card:hover .product-image img { transform: scale(1.05) }

        .product-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #ff4757;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: .8rem;
            font-weight: 600
        }
        .product-card .card-body {
            padding: 1.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .product-title {
            color: var(--color-brown-dark);
            font-weight: 600;
            margin-bottom: .75rem;
            font-size: 1.1rem
        }
        .product-description {
            color: var(--color-text-secondary);
            font-size: .9rem;
            margin-bottom: 1rem;
            line-height: 1.5
        }
        .price {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--color-primary)
        }
        .price-unit { color: var(--color-text-secondary); font-size: .9rem }

        /* Button Warning */
        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
            font-weight: 600;
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            transition: all .3s ease
        }
        .btn-warning:hover {
            background-color: #ffb000;
            border-color: #ffb000;
            transform: translateY(-1px)
        }

        /* About Section */
        .about-section { background: #f8f9fa }
        .about-title { margin-bottom: 1rem }
        .about-text { line-height: 1.7; margin-bottom: 1rem }

        /* Media Queries */
        @media (max-width:992px) { /* Tambahan untuk Hero Image di Tablet */
            .hero-image { padding-top: 3rem; }
        }
        @media (max-width:768px){
            .hero-title{font-size:2.2rem}
            .hero-buttons .btn{display:block;width:100%;margin-bottom:1rem}
            .hero-buttons .btn:last-child{margin-bottom:0}
            .hero-product-img { max-width: 320px }
        }
    </style>
@endsection
