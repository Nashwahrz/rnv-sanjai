@extends('layouts.main')

@section('title', 'Tentang - R&V Sanjai')

@section('content')
    <div class="about-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="hero-badge">
                        <i class="fas fa-leaf me-2"></i>Sejak 2021
                    </div>
                    <h1 class="hero-title">Tentang R&V Sanjai</h1>
                    <p class="hero-subtitle">UMKM Keripik Sanjai dari Bukittinggi yang menghadirkan cita rasa autentik Minangkabau</p>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <div class="stat-number">4+</div>
                            <div class="stat-label">Tahun Berpengalaman</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">1000+</div>
                            <div class="stat-label">Pelanggan Puas</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">5</div>
                            <div class="stat-label">Varian Rasa</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image">
                        <img src="https://picsum.photos/600/400?random=10" alt="R&V Sanjai" class="img-fluid">
                        <div class="floating-card">
                            <div class="card-icon">
                                <i class="fas fa-award"></i>
                            </div>
                            <div class="card-content">
                                <h6>Kualitas Terjamin</h6>
                                <p>100% Halal & Higienis</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="profile-section">
        <div class="container">
            <div class="section-header text-center">
                <div class="section-badge">
                    <i class="fas fa-building me-2"></i>Profil Usaha
                </div>
                <h2 class="section-title">Perjalanan R&V Sanjai</h2>
            </div>

            <div class="content-card">
                <div class="card-icon-large">
                    <i class="fas fa-store"></i>
                </div>
                <div class="card-text">
                    <p class="lead-text">R&V Sanjai adalah usaha kecil menengah yang bergerak di bidang kuliner, khususnya keripik sanjai khas Minang.</p>
                    <p>Usaha ini didirikan oleh <strong class="founder-name">Ibu Marlita Fiseka</strong> pada tahun <strong class="year-badge">2021</strong>, dengan tekad untuk melestarikan cita rasa tradisional Minangkabau dan memberdayakan masyarakat lokal.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="vision-mission-section">
        <div class="container">
            <div class="section-header text-center">
                <div class="section-badge">
                    <i class="fas fa-eye me-2"></i>Visi & Misi
                </div>
                <h2 class="section-title">Komitmen Kami</h2>
            </div>

            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="mission-card">
                        <div class="mission-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div class="mission-content">
                            <h5>Kualitas Terbaik</h5>
                            <p>Menyediakan camilan khas Minang yang lezat, sehat, dan terjangkau untuk semua kalangan.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="mission-card">
                        <div class="mission-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <div class="mission-content">
                            <h5>Pemberdayaan Lokal</h5>
                            <p>Memberdayakan masyarakat lokal melalui produksi keripik tradisional dan menciptakan lapangan kerja.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="mission-card">
                        <div class="mission-icon">
                            <i class="fas fa-expand-arrows-alt"></i>
                        </div>
                        <div class="mission-content">
                            <h5>Jangkauan Luas</h5>
                            <p>Membawa cita rasa keripik Sanjai ke pasar yang lebih luas, baik offline maupun online.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .about-hero{background:linear-gradient(135deg,#f8f9fa 0%,#e9ecef 100%);padding:4rem 0;position:relative;overflow:hidden}
        .about-hero::before{content:'';position:absolute;top:-50%;right:-20%;width:400px;height:400px;background:linear-gradient(135deg,rgba(255,107,53,.1),rgba(255,193,7,.1));border-radius:50%;z-index:1}
        .hero-badge{display:inline-flex;align-items:center;background:linear-gradient(135deg,#ff6b35,#ffc107);color:white;padding:8px 20px;border-radius:25px;font-size:.9rem;font-weight:600;margin-bottom:1.5rem;box-shadow:0 4px 15px rgba(255,107,53,.3);position:relative;z-index:2}
        .hero-title{font-size:3rem;font-weight:800;color:#8B4513;margin-bottom:1.5rem;line-height:1.2;position:relative;z-index:2}
        .hero-subtitle{font-size:1.2rem;color:#6c757d;margin-bottom:2rem;line-height:1.6;position:relative;z-index:2}
        .hero-stats{display:flex;gap:2rem;margin-top:2rem;position:relative;z-index:2}
        .stat-item{text-align:center}
        .stat-number{font-size:2.2rem;font-weight:800;color:#dc6900;line-height:1}
        .stat-label{font-size:.85rem;color:#6c757d;margin-top:.5rem}
        .hero-image{position:relative;z-index:2}
        .hero-image img{border-radius:15px;box-shadow:0 15px 30px rgba(0,0,0,.1);width:100%;height:400px;object-fit:cover}
        .floating-card{position:absolute;bottom:-15px;right:15px;background:white;padding:1rem;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,.15);display:flex;align-items:center;gap:1rem;max-width:220px}
        .card-icon{width:45px;height:45px;background:linear-gradient(135deg,#ff6b35,#ffc107);border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-size:1.1rem;flex-shrink:0}
        .card-content h6{margin:0 0 .2rem 0;font-weight:600;color:#333;font-size:.95rem}
        .card-content p{margin:0;font-size:.8rem;color:#6c757d}
        .profile-section{padding:4rem 0}
        .section-header{margin-bottom:2.5rem}
        .section-badge{display:inline-flex;align-items:center;background:rgba(255,107,53,.1);color:#dc6900;padding:8px 20px;border-radius:25px;font-size:.9rem;font-weight:600;margin-bottom:1rem}
        .section-title{font-size:2.2rem;font-weight:700;color:#8B4513;margin-bottom:1rem}
        .content-card{background:white;padding:2rem;border-radius:15px;box-shadow:0 8px 25px rgba(0,0,0,.1);display:flex;align-items:flex-start;gap:1.5rem;max-width:800px;margin:0 auto}
        .card-icon-large{width:70px;height:70px;background:linear-gradient(135deg,#ff6b35,#ffc107);border-radius:15px;display:flex;align-items:center;justify-content:center;color:white;font-size:1.8rem;flex-shrink:0}
        .lead-text{font-size:1.1rem;font-weight:500;color:#333;margin-bottom:1rem}
        .founder-name{color:#dc6900;font-weight:700}
        .year-badge{background:linear-gradient(135deg,#ff6b35,#ffc107);color:white;padding:2px 8px;border-radius:10px;font-size:.85rem}
        .vision-mission-section{background:#f8f9fa;padding:4rem 0}
        .mission-card{background:white;padding:1.8rem;border-radius:15px;box-shadow:0 4px 15px rgba(0,0,0,.08);text-align:center;height:100%;transition:all .3s ease}
        .mission-card:hover{transform:translateY(-8px);box-shadow:0 15px 30px rgba(0,0,0,.15)}
        .mission-icon{width:70px;height:70px;background:linear-gradient(135deg,#ff6b35,#ffc107);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.2rem;font-size:1.8rem;color:white;box-shadow:0 8px 20px rgba(255,107,53,.3)}
        .mission-content h5{color:#8B4513;font-weight:600;margin-bottom:1rem;font-size:1.1rem}
        .mission-content p{color:#6c757d;line-height:1.6;font-size:.95rem}
        @media (max-width:768px){
            .hero-title{font-size:2.2rem}
            .hero-stats{flex-direction:column;gap:1rem;text-align:center}
            .content-card{flex-direction:column;text-align:center}
            .floating-card{position:static;margin-top:1.5rem;max-width:none}
            .section-title{font-size:1.8rem}
        }
    </style>
@endsection
