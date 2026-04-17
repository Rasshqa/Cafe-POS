<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Kasir Pro</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --sidebar-bg: #2c3e50;
            --sidebar-color: #ecf0f1;
            --topbar-bg: #ffffff;
            --body-bg: #f4f6f9;
            --brand-color: #3498db;
        }
        body {
            background-color: var(--body-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }
        
        /* Layout flexbox */
        .wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
            min-height: 100vh;
        }

        /* Sidebar Styling */
        #sidebar {
            min-width: 250px;
            max-width: 250px;
            background: var(--sidebar-bg);
            color: var(--sidebar-color);
            transition: all 0.3s;
            position: fixed;
            height: 100vh;
            z-index: 100;
        }
        #sidebar .sidebar-header {
            padding: 20px;
            background: rgba(0, 0, 0, 0.1);
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        #sidebar ul.components {
            padding: 20px 0;
        }
        #sidebar ul li a {
            padding: 12px 20px;
            font-size: 1.05em;
            display: block;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: all 0.3s;
        }
        #sidebar ul li a:hover, #sidebar ul li.active > a {
            color: #fff;
            background: var(--brand-color);
            border-left: 4px solid var(--brand-color);
        }
        #sidebar ul li a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        /* Main Content Styling */
        #content {
            width: calc(100% - 250px);
            margin-left: 250px;
            min-height: 100vh;
            transition: all 0.3s;
        }

        /* Topbar Styling */
        .topbar {
            background: var(--topbar-bg);
            padding: 15px 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        /* Cards and Elements */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.03);
            margin-bottom: 25px;
        }

        @media (max-width: 768px) {
            #sidebar {
                margin-left: -250px;
            }
            #sidebar.active {
                margin-left: 0;
            }
            #content {
                width: 100%;
                margin-left: 0;
            }
            #content.active {
                width: calc(100% - 250px);
            }
        }
    </style>
    @stack('styles')
</head>
<body>

<div class="wrapper">
    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <h4><i class="fa-solid fa-store text-warning"></i> Kasir Pro</h4>
        </div>
        <ul class="list-unstyled components">
            <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
            </li>
            <li class="{{ request()->routeIs('pos.*') && !request()->routeIs('pos.history') ? 'active' : '' }}">
                <a href="{{ route('pos.index') }}"><i class="fa-solid fa-cash-register"></i> Transaksi Kasir</a>
            </li>
            <li class="{{ request()->routeIs('products.*') ? 'active' : '' }}">
                <a href="{{ route('products.index') }}"><i class="fa-solid fa-box"></i> Data Produk</a>
            </li>
            <li class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">
                <a href="{{ route('categories.index') }}"><i class="fa-solid fa-tags"></i> Kategori Menu</a>
            </li>
            <li class="{{ request()->routeIs('pos.history') ? 'active' : '' }}">
                <a href="{{ route('pos.history') }}"><i class="fa-solid fa-file-invoice-dollar"></i> Riwayat Transaksi</a>
            </li>
            <li class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <a href="{{ route('reports.index') }}"><i class="fa-solid fa-chart-pie"></i> Laporan & Analitik</a>
            </li>
        </ul>
    </nav>

    <!-- Page Content -->
    <div id="content">
        <!-- Topbar -->
        <div class="topbar">
            <div>
                <button type="button" id="sidebarCollapse" class="btn btn-outline-secondary d-md-none">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <span class="fs-5 fw-bold ms-2 text-dark">{{ $title ?? 'Dashboard Administrator' }}</span>
            </div>
            <div>
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle border" type="button" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-user-circle text-primary"></i> {{ auth()->check() ? auth()->user()->name : 'Guest' }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                        <li><a class="dropdown-item" href="#"><i class="fa-solid fa-user"></i> Profil ({{ auth()->check() ? auth()->user()->role : '' }})</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="{{ route('logout') }}"><i class="fa-solid fa-right-from-bracket"></i> Keluar</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Main Container -->
        <div class="container-fluid px-4">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
                    <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm">
                    <i class="fa-solid fa-circle-exclamation me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Content dinamis -->
            @yield('content')
        </div>
    </div>
</div>

<!-- Bootstrap & Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Toggle Sidebar on mobile
    document.getElementById('sidebarCollapse').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('active');
        document.getElementById('content').classList.toggle('active');
    });
</script>
@stack('scripts')
</body>
</html>
