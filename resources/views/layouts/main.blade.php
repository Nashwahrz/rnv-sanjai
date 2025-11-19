<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'R&V Sanjai')</title>

    {{-- Memanggil CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* --- CSS TETAP INLINE & DIBERSIHKAN (Tanpa Loading Overlay) --- */

        /* Definisi Variabel untuk Keterbacaan (Opsional tapi direkomendasikan) */
        :root {
            --color-primary-start: #ff6b35;
            --color-primary-end: #ffc107;
            --color-dark-start: #2c3e50;
            --color-dark-end: #34495e;
            --color-light: #fff;
            --color-accent: #ff4757;
        }

        /* Global & Base */
        *{font-family:'Poppins',sans-serif}
        body{background:linear-gradient(135deg,#f5f7fa 0%,#c3cfe2 100%);min-height:100vh}
        main{min-height:calc(100vh - 200px)}

        /* Navbar */
        .navbar{
            background:linear-gradient(135deg,var(--color-primary-start) 0%,#f7931e 50%,var(--color-primary-end) 100%)!important;
            box-shadow:0 4px 15px rgba(255,107,53,.3);
            transition:all .3s ease
        }
        .navbar-brand{font-size:1.8rem!important;font-weight:800!important;color:var(--color-light)!important;text-shadow:2px 2px 4px rgba(0,0,0,.3);transition:transform .3s}
        .navbar-brand::before{content:'🍃';margin-right:8px}
        .navbar-brand:hover{transform:scale(1.05)}
        .navbar-nav .nav-link{color:var(--color-light)!important;font-weight:500!important;margin:0 10px;padding:10px 15px!important;border-radius:25px;transition:all .3s ease;position:relative;overflow:hidden}
        .navbar-nav .nav-link::before{content:'';position:absolute;top:0;left:-100%;width:100%;height:100%;background:linear-gradient(90deg,transparent,rgba(255,255,255,.2),transparent);transition:left .5s}
        .navbar-nav .nav-link:hover::before{left:100%}
        .navbar-nav .nav-link:hover{background:rgba(255,255,255,.2);transform:translateY(-2px);box-shadow:0 4px 8px rgba(0,0,0,.2)}
        .navbar-nav .nav-link.active{background:rgba(255,255,255,.3);box-shadow:inset 0 2px 4px rgba(0,0,0,.2)}

        /* Cart styling improvements */
        .cart-link{position:relative;display:inline-block}
        .cart-badge{position:absolute;top:-8px;right:-8px;background:var(--color-accent);color:white;border-radius:50%;min-width:20px;height:20px;font-size:.75rem;display:none;align-items:center;justify-content:center;animation:pulse 2s infinite;font-weight:600;border:2px solid var(--color-light)}
        @keyframes pulse{0%{transform:scale(1)}50%{transform:scale(1.1)}100%{transform:scale(1)}}

        /* Mobile Nav & Cart */
        .navbar-toggler{border:none;padding:8px;background:rgba(255,255,255,.1);border-radius:8px}
        .navbar-toggler:focus{box-shadow:0 0 0 0.2rem rgba(255,255,255,.3)}
        .mobile-cart-section{display:flex;align-items:center;gap:15px}
        .mobile-cart-wrapper{background:rgba(255,255,255,.15);border-radius:12px;padding:8px 12px;transition:all .3s ease}
        .mobile-cart-wrapper:hover{background:rgba(255,255,255,.25)}
        .mobile-cart-icon{font-size:1.2rem;color:var(--color-light)}

        /* Footer */
        footer{background:linear-gradient(135deg,var(--color-dark-start) 0%,var(--color-dark-end) 100%)!important;box-shadow:0 -4px 15px rgba(0,0,0,.1);margin-top:auto}
        .footer-content{padding:3rem 0 1.5rem 0}
        .footer-brand{font-size:1.5rem;font-weight:700;color:var(--color-primary-end);margin-bottom:1rem}
        .footer-description{color:#bdc3c7;margin-bottom:1.5rem;line-height:1.6}
        .footer-links h5{color:var(--color-primary-end);font-weight:600;margin-bottom:1rem}
        .footer-links a{color:#bdc3c7;text-decoration:none;display:block;margin-bottom:.5rem;transition:all .3s ease}
        .footer-links a:hover{color:var(--color-primary-end);padding-left:5px}
        .social-icons a{display:inline-block;width:45px;height:45px;background:linear-gradient(135deg,var(--color-primary-start),var(--color-primary-end));color:var(--color-light);text-align:center;line-height:45px;border-radius:50%;margin-right:10px;transition:all .3s ease;font-size:1.2rem}
        .social-icons a:hover{transform:translateY(-3px) scale(1.1);box-shadow:0 8px 15px rgba(255,107,53,.4)}
        .footer-bottom{border-top:1px solid var(--color-dark-end);padding:1.5rem 0;text-align:center;color:#95a5a6}

        /* Scroll Top */
        .scroll-top{position:fixed;bottom:20px;right:20px;width:50px;height:50px;background:linear-gradient(135deg,var(--color-primary-start),var(--color-primary-end));color:var(--color-light);border:none;border-radius:50%;font-size:1.2rem;cursor:pointer;opacity:0;visibility:hidden;transition:all .3s ease;z-index:1000;box-shadow:0 4px 15px rgba(255,107,53,.3)}
        .scroll-top.show{opacity:1;visibility:visible}
        .scroll-top:hover{transform:translateY(-3px) scale(1.1);box-shadow:0 8px 25px rgba(255,107,53,.5)}

        /* Media Queries */
        @media (max-width:991.98px){
            .navbar-brand{font-size:1.5rem!important}
            .navbar-nav{background:rgba(255,255,255,.1);border-radius:15px;margin-top:15px;padding:15px}
            .navbar-nav .nav-link{margin:8px 0;text-align:left;padding:12px 20px!important;border-radius:12px}
            .navbar-nav .nav-link:hover{background:rgba(255,255,255,.25)}
            .footer-content{padding:2rem 0 1rem 0}
            .social-icons{text-align:center;margin-top:1rem}
        }
        @media (min-width:992px){
            .mobile-cart-section{display:none}
        }

        /* HAPUS FADE-IN animation dan Keyframes-nya karena terkait loading overlay */
    </style>
</head>
<body>
    {{-- Hapus div class="loading-overlay" dan isinya --}}

    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('beranda') }}">
                R&V Sanjai
                <small style="font-size:.6rem;display:block;font-weight:400;margin-top:-5px">Keripik Khas Minang</small>
            </a>



            <div class="mobile-cart-section">

    <!-- Ikon Profil Mobile -->
    <div class="mobile-cart-wrapper">
        <a href="{{ route('profile') }}" class="text-decoration-none">
            <i class="fas fa-user-circle mobile-cart-icon"></i>
        </a>
    </div>

    <!-- Ikon Keranjang Mobile -->
    <div class="mobile-cart-wrapper">
        <a href="{{ route('keranjang.index') }}" class="cart-link text-decoration-none">
            <i class="fas fa-shopping-cart mobile-cart-icon"></i>
            <span class="cart-badge" id="cartBadgeMobile"></span>
        </a>
    </div>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>
</div>


            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    {{-- Loop Navigasi (Asumsi Anda akan memindahkan ini ke partial nanti) --}}
                    @php
                        $menus = [
                            ['beranda', 'fas fa-home', 'Beranda'],
                            ['tentang', 'fas fa-info-circle', 'Tentang'],
                            ['produk', 'fas fa-box', 'Produk'],
                        ];
                        $currentRoute = Route::currentRouteName();
                    @endphp
                    @foreach ($menus as $menu)
                        @php
                            [$routeName, $icon, $label] = $menu;
                            $isActive = ($routeName == $currentRoute) ? ' active' : '';
                        @endphp
                        <li class="nav-item">
                            <a class="nav-link{{ $isActive }}" href="{{ route($routeName) }}">
                                <i class="{{ $icon }} me-1"></i> {{ $label }}
                            </a>
                        </li>
                    @endforeach
                    <li class="nav-item d-none d-lg-block">
    <a class="nav-link" href="{{ route('profile') }}">
        <i class="fas fa-user-circle me-1"></i> Profil
    </a>
</li>


                    <li class="nav-item d-none d-lg-block">
                        <a class="nav-link" href="{{ route('keranjang.index') }}" id="cartLink">
                            <div class="cart-link">
                                <i class="fas fa-shopping-cart me-1"></i> Keranjang
                                <span class="cart-badge" id="cartBadge"></span>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Hapus class="fade-in" dari main --}}
    <main>
        @yield('content')
    </main>

    <footer class="text-white">
        <div class="container footer-content">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="footer-brand">🍃 R&V Sanjai</div>
                    <p class="footer-description">
                        UMKM Keripik Sanjai sejak 2021, menghadirkan cita rasa autentik Minangkabau dengan kualitas terbaik.
                    </p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                        <a href="#"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 mb-4">
                    <div class="footer-links">
                        <h5>Menu</h5>
                        <a href="{{ route('beranda') }}">Beranda</a>
                        <a href="{{ route('tentang') }}">Tentang</a>
                        <a href="{{ route('produk') }}">Produk</a>
                        <a href="{{ route('keranjang.index') }}">Keranjang</a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="footer-links">
                        <h5>Produk</h5>
                        <a href="#">Keripik Original</a>
                        <a href="#">Keripik Pedas</a>
                        <a href="#">Keripik Manis</a>
                        <a href="#">Paket Hemat</a>
                    </div>
                </div>

                <div class="col-lg-3 mb-4">
                    <div class="footer-links">
                        <h5>Kontak</h5>
                        <a href="#"><i class="fas fa-map-marker-alt me-2"></i> Padang, Sumatera Barat</a>
                        <a href="#"><i class="fas fa-phone me-2"></i> +62 812-3456-7890</a>
                        <a href="#"><i class="fas fa-envelope me-2"></i> info@rvsanjai.com</a>
                        <a href="#"><i class="fas fa-clock me-2"></i> 08:00 - 20:00 WIB</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="container">
                <p class="mb-0">&copy; {{ date('Y') }} R&V Sanjai - UMKM Keripik Sanjai. Dibuat dengan ❤️ di Padang</p>
            </div>
        </div>
    </footer>

    <button class="scroll-top" id="scrollTop">
        <i class="fas fa-chevron-up"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Hapus 'l' dari variabel karena loading overlay sudah dihapus
    const s = document.getElementById('scrollTop');
    const c = document.getElementById('cartBadge');
    const cm = document.getElementById('cartBadgeMobile');
    const n = document.querySelector('.navbar');

    // --- FUNGSI KERANJANG ---
    async function fetchCartCount() {
        try {
            const response = await fetch('{{ route('keranjang.count') }}', {
                method: 'GET',
                headers: { 'Accept': 'application/json' }
            });

            if (!response.ok) throw new Error('Network response was not ok');

            const data = await response.json();
            localStorage.setItem('cartCount', data.count);
        } catch (error) {
            console.warn("Gagal mengambil jumlah keranjang, menggunakan data 0:", error);
            localStorage.setItem('cartCount', 0);
        }
        updateCart(); // Panggil update tampilan setelah mengambil data
    }

    function updateCart(){
        const count = parseInt(localStorage.getItem('cartCount')) || 0;
        const displayStyle = count > 0 ? 'flex' : 'none';

        if(c){c.textContent = count; c.style.display = displayStyle;}
        if(cm){cm.textContent = count; cm.style.display = displayStyle;}
    }

    // --- EVENT LISTENERS ---

    // Hapus window.addEventListener('load', ...) yang lama

    window.addEventListener('scroll', ()=>{
        const y = window.pageYOffset;
        // Scroll Top: Tampilkan jika sudah scroll lebih dari 300px
        y > 300 ? s.classList.add('show') : s.classList.remove('show');

        // Navbar Sticky/Transparansi saat scroll
        const scrollBg = 'linear-gradient(135deg,rgba(255,107,53,.95) 0%,rgba(247,147,30,.95) 50%,rgba(255,193,7,.95) 100%)';
        const defaultBg = 'linear-gradient(135deg,#ff6b35 0%,#f7931e 50%,#ffc107 100%)';
        n.style.background = y > 50 ? scrollBg : defaultBg;
    });

    s.addEventListener('click', ()=>window.scrollTo({top:0,behavior:'smooth'}));

    // Initial Load & Navigasi Aktif
    document.addEventListener('DOMContentLoaded', () => {
        // Panggil untuk mendapatkan jumlah keranjang saat DOM siap
        fetchCartCount();

        // Cek link aktif
        document.querySelectorAll('.nav-link').forEach(link=>{
            // Membandingkan link href dengan pathname (tanpa host)
            if(link.getAttribute('href')===location.pathname)link.classList.add('active');
        });
    });

    // Mendengarkan event dari file produk/show atau controller lain
    window.addEventListener('storage',updateCart);
    document.addEventListener('cartUpdated',updateCart);
</script>
</body>
</html>
