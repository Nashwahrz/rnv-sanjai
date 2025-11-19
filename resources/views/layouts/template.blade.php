<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin R&V Sanjai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root {
            --primary-color: #4A90E2;
            --primary-dark: #357ABD;
            --secondary-color: #F0F4F8;
            --text-color: #333;
            --light-bg: #FFFFFF;
            --border-color: #E0E0E0;
        }

        body {
            background-color: var(--secondary-color);
            color: var(--text-color);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            min-height: 100vh;
        }

        /* Layout Structure */
        .page-container {
            display: flex;
            min-height: 100vh;
        }

        /* Desktop Sidebar */
        .sidebar {
            width: 250px;
            background: var(--light-bg);
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.05);
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100%;
            z-index: 1000;
        }

        .sidebar .logo {
            font-size: 1.3rem;
            font-weight: 700;
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid var(--border-color);
            color: var(--primary-dark);
        }

        .sidebar a {
            color: var(--text-color);
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 15px 20px;
            transition: background 0.2s ease-in-out, color 0.2s ease-in-out;
        }

        .sidebar a i {
            margin-right: 12px;
            font-size: 1.2rem;
            color: var(--primary-color);
        }

        .sidebar a:hover {
            background: rgba(74, 144, 226, 0.1);
            color: var(--primary-color);
        }

        .sidebar a.active {
            background: rgba(74, 144, 226, 0.2);
            color: var(--primary-dark);
            border-left: 4px solid var(--primary-dark);
        }

        /* Main Content & Top Bar */
        .main-content-area {
            flex-grow: 1;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--light-bg);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            padding: 10px 20px;
            z-index: 999;
        }

        .content-wrapper {
            padding: 20px;
            margin-left: 250px;
        }

        .content {
            background: var(--light-bg);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        /* Dropdown Styles */
        .dropdown-menu {
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .dropdown-item i {
            margin-right: 8px;
        }

        /* Mobile Bottom Nav */
        .bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: var(--light-bg);
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .bottom-nav ul {
            display: flex;
            justify-content: space-around;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .bottom-nav a {
            flex-grow: 1;
            text-align: center;
            padding: 10px 0;
            color: var(--text-color);
            text-decoration: none;
            font-size: 0.75rem;
            font-weight: 500;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .bottom-nav a i {
            font-size: 1.4rem;
            margin-bottom: 4px;
            color: var(--primary-color);
        }

        .bottom-nav a.active i,
        .bottom-nav a.active {
            color: var(--primary-dark);
        }

        /* Responsive Breakpoints */
        @media (min-width: 769px) {
            .bottom-nav {
                display: none;
            }
            .top-bar {
                position: fixed;
                width: calc(100% - 250px);
                margin-left: 250px;
                top: 0;
            }
            .content-wrapper {
                padding-top: 70px; /* Adjust for fixed top-bar height */
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }
            .top-bar {
                position: sticky;
                top: 0;
            }
            .content-wrapper {
                margin-left: 0;
                padding-bottom: 60px; /* Adjust for fixed bottom-nav height */
            }
            .bottom-nav {
                display: block;
            }
        }
    </style>
</head>
<body>

    <div class="page-container">
        <div class="d-none d-md-flex sidebar">
            <div class="logo">
                Admin R&V Sanjai
            </div>
            <a href="{{ route('admin.products.index') }}" class="active"><i class="fas fa-box"></i> Kelola Produk</a>
            <a href="#"><i class="fas fa-shopping-cart"></i> Pesanan</a>
            <a href="#"><i class="fas fa-chart-line"></i> Laporan</a>
            {{-- <a href="#"><i class="fas fa-cog"></i> Pengaturan</a> --}}
        </div>

        <div class="main-content-area">
            <div class="top-bar">
                <div class="d-none d-md-block">
                    <div class="logo-desktop" style="font-size: 1.1rem; font-weight: 700; color: var(--primary-dark);">Admin R&V Sanjai</div>
                </div>
                <div class="d-md-none">
                    <div class="logo-mobile" style="font-size: 1.1rem; font-weight: 700; color: var(--primary-dark);">Admin R&V Sanjai</div>
                </div>

                <div class="dropdown d-none d-md-block">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-cog me-2"></i> Pengaturan
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user-circle"></i> Profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    </ul>
                </div>

            </div>

            <div class="content-wrapper">
                <div class="content">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <nav class="bottom-nav d-md-none">
        <ul>
            <li>
                <a href="{{ route('admin.products.index') }}" class="active">
                    <i class="fas fa-box"></i>
                    <span>Produk</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Pesanan</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fas fa-chart-line"></i>
                    <span>Laporan</span>
                </a>
            </li>
            <li>
                <a href="#" data-bs-toggle="dropdown">
                    <i class="fas fa-cog"></i>
                    <span>Pengaturan</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user-circle"></i> Profil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
