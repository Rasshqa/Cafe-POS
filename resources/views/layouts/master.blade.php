<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} — Kasir Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-w: 260px;
            --topbar-h: 64px;
            --brand-primary: #6366f1;
            --brand-primary-dark: #4f46e5;
            --brand-gradient: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            --sidebar-bg: #0f172a;
            --sidebar-text: #94a3b8;
            --sidebar-active: #6366f1;
            --body-bg: #f1f5f9;
            --card-shadow: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
            --card-shadow-hover: 0 10px 25px rgba(0,0,0,.08);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: var(--body-bg);
            color: #1e293b;
            overflow-x: hidden;
        }

        /* ═══════════ SIDEBAR ═══════════ */
        .sidebar {
            position: fixed; top: 0; left: 0; bottom: 0;
            width: var(--sidebar-w);
            background: var(--sidebar-bg);
            z-index: 1040;
            transition: transform .3s cubic-bezier(.4,0,.2,1);
            display: flex; flex-direction: column;
            overflow-y: auto;
        }
        .sidebar-brand {
            padding: 24px 20px;
            display: flex; align-items: center; gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,.06);
        }
        .sidebar-brand .brand-icon {
            width: 40px; height: 40px; border-radius: 10px;
            background: var(--brand-gradient);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 18px; flex-shrink: 0;
        }
        .sidebar-brand .brand-text {
            color: #f8fafc; font-weight: 700; font-size: 1.15rem; letter-spacing: -.3px;
        }
        .sidebar-brand .brand-sub {
            color: #64748b; font-size: .72rem; font-weight: 500; text-transform: uppercase; letter-spacing: .5px;
        }

        .sidebar-nav { padding: 16px 12px; flex: 1; }
        .sidebar-nav .nav-label {
            font-size: .68rem; font-weight: 600; text-transform: uppercase;
            letter-spacing: .8px; color: #475569; padding: 12px 12px 8px;
        }
        .sidebar-nav .nav-item {
            margin-bottom: 2px;
        }
        .sidebar-nav .nav-link {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 14px; border-radius: 8px;
            color: var(--sidebar-text); font-size: .88rem; font-weight: 500;
            text-decoration: none; transition: all .2s;
        }
        .sidebar-nav .nav-link:hover {
            background: rgba(99,102,241,.08); color: #e2e8f0;
        }
        .sidebar-nav .nav-link.active {
            background: var(--brand-gradient); color: #fff;
            box-shadow: 0 4px 12px rgba(99,102,241,.35);
        }
        .sidebar-nav .nav-link i {
            width: 20px; text-align: center; font-size: .95rem; flex-shrink: 0;
        }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(255,255,255,.06);
        }

        /* ═══════════ MAIN AREA ═══════════ */
        .main-wrapper {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            transition: margin-left .3s cubic-bezier(.4,0,.2,1);
        }

        /* ═══════════ TOPBAR ═══════════ */
        .topbar {
            height: var(--topbar-h);
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 24px;
            position: sticky; top: 0; z-index: 1030;
        }
        .topbar .page-title {
            font-size: 1.1rem; font-weight: 700; color: #0f172a;
        }

        /* ═══════════ CONTENT ═══════════ */
        .main-content { padding: 24px; }

        /* ═══════════ CARDS ═══════════ */
        .card {
            border: 1px solid #e2e8f0; border-radius: 12px;
            box-shadow: var(--card-shadow);
            transition: box-shadow .2s, transform .2s;
            background: #fff;
        }
        .card:hover { box-shadow: var(--card-shadow-hover); }

        .stat-card { border: none; overflow: hidden; }
        .stat-card .stat-icon {
            width: 48px; height: 48px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
        }
        .stat-card .stat-value { font-size: 1.6rem; font-weight: 800; letter-spacing: -.5px; }
        .stat-card .stat-label { font-size: .78rem; font-weight: 500; color: #64748b; text-transform: uppercase; letter-spacing: .3px; }

        /* ═══════════ TABLES ═══════════ */
        .table { font-size: .88rem; }
        .table thead th {
            font-size: .75rem; font-weight: 600; text-transform: uppercase;
            letter-spacing: .5px; color: #64748b; border-bottom: 2px solid #e2e8f0;
            padding: 12px 16px; background: #f8fafc;
        }
        .table tbody td { padding: 12px 16px; vertical-align: middle; border-color: #f1f5f9; }
        .table-hover tbody tr:hover { background: #f8fafc; }

        /* ═══════════ BUTTONS ═══════════ */
        .btn { font-weight: 600; font-size: .88rem; border-radius: 8px; transition: all .2s; }
        .btn-primary {
            background: var(--brand-gradient); border: none;
            box-shadow: 0 2px 8px rgba(99,102,241,.25);
        }
        .btn-primary:hover {
            background: var(--brand-primary-dark);
            box-shadow: 0 4px 12px rgba(99,102,241,.35);
            transform: translateY(-1px);
        }
        .btn-ghost {
            background: transparent; border: 1px solid #e2e8f0; color: #475569;
        }
        .btn-ghost:hover { background: #f1f5f9; border-color: #cbd5e1; }

        /* ═══════════ BADGE ═══════════ */
        .badge { font-weight: 600; font-size: .72rem; padding: 4px 10px; border-radius: 6px; }

        /* ═══════════ MODAL ═══════════ */
        .modal-content { border: none; border-radius: 16px; box-shadow: 0 25px 50px rgba(0,0,0,.15); }
        .modal-header { border-bottom: 1px solid #f1f5f9; padding: 20px 24px; }
        .modal-body { padding: 24px; }
        .modal-footer { border-top: 1px solid #f1f5f9; padding: 16px 24px; }

        /* ═══════════ FORM ═══════════ */
        .form-control, .form-select {
            border-radius: 8px; border: 1px solid #e2e8f0; font-size: .88rem;
            padding: 10px 14px; transition: border-color .15s, box-shadow .15s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--brand-primary);
            box-shadow: 0 0 0 3px rgba(99,102,241,.15);
        }
        .form-label { font-size: .82rem; font-weight: 600; color: #374151; margin-bottom: 6px; }

        /* ═══════════ TOAST ═══════════ */
        .toast-container { position: fixed; top: 80px; right: 24px; z-index: 9999; }

        /* ═══════════ ANIMATIONS ═══════════ */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-10px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .animate-in { animation: fadeInUp .4s ease-out; }
        .animate-slide { animation: slideIn .3s ease-out; }

        /* Skeleton loader */
        .skeleton {
            background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
            border-radius: 6px;
        }
        @keyframes shimmer { to { background-position: -200% 0; } }

        /* ═══════════ RESPONSIVE ═══════════ */
        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
            .sidebar-backdrop {
                position: fixed; inset: 0; background: rgba(0,0,0,.5);
                z-index: 1035; display: none;
            }
            .sidebar-backdrop.show { display: block; }
        }

        /* ═══════════ SCROLLBAR ═══════════ */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        /* ═══════════ DARK MODE OVERRIDES ═══════════ */
        [data-bs-theme="dark"] {
            --body-bg: #0f172a;
            --sidebar-bg: #020617;
            --card-shadow: 0 1px 3px rgba(0,0,0,.5);
        }
        [data-bs-theme="dark"] body { color: #e2e8f0; }
        [data-bs-theme="dark"] .card { background: #1e293b; border-color: rgba(255,255,255,.08); }
        [data-bs-theme="dark"] .topbar { background: #1e293b; border-color: rgba(255,255,255,.08); }
        [data-bs-theme="dark"] .topbar .page-title { color: #f8fafc; }
        [data-bs-theme="dark"] .table thead th { background: #0f172a; border-color: rgba(255,255,255,.08); color: #94a3b8; }
        [data-bs-theme="dark"] .table tbody td { border-color: rgba(255,255,255,.05); color: #e2e8f0; }
        [data-bs-theme="dark"] .table-hover tbody tr:hover { background: rgba(255,255,255,.05); }
        [data-bs-theme="dark"] .btn-ghost { color: #cbd5e1; border-color: rgba(255,255,255,.1); }
        [data-bs-theme="dark"] .btn-ghost:hover { background: rgba(255,255,255,.05); border-color: rgba(255,255,255,.2); color: #f8fafc; }
        [data-bs-theme="dark"] .text-muted { color: #94a3b8 !important; }
        [data-bs-theme="dark"] .form-control, [data-bs-theme="dark"] .form-select { background-color: #0f172a; border-color: rgba(255,255,255,.1); color: #f8fafc; }
        [data-bs-theme="dark"] .modal-content { background: #1e293b; }
        [data-bs-theme="dark"] .modal-header, [data-bs-theme="dark"] .modal-footer { border-color: rgba(255,255,255,.08); }
        [data-bs-theme="dark"] .skeleton { background: linear-gradient(90deg, #1e293b 25%, #334155 50%, #1e293b 75%); background-size: 200% 100%; }

    </style>
    @stack('styles')
</head>
<body>

<!-- Sidebar Backdrop (mobile) -->
<div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleSidebar()"></div>

<!-- ═══════════ SIDEBAR ═══════════ -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon"><i class="fa-solid fa-mug-hot"></i></div>
        <div>
            <div class="brand-text">Kasir Pro</div>
            <div class="brand-sub">Point of Sale</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Menu Utama</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="fa-solid fa-grid-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('pos.index') ? 'active' : '' }}" href="{{ route('pos.index') }}">
                    <i class="fa-solid fa-cash-register"></i> Kasir
                </a>
            </li>
        </ul>

        @if(auth()->check() && auth()->user()->role === 'admin')
        <div class="nav-label mt-3">Manajemen</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                    <i class="fa-solid fa-box-open"></i> Produk
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                    <i class="fa-solid fa-tags"></i> Kategori
                </a>
            </li>
        </ul>

        <div class="nav-label mt-3">Inventori</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}" href="{{ route('suppliers.index') }}">
                    <i class="fa-solid fa-truck-field"></i> Supplier
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('purchases.*') ? 'active' : '' }}" href="{{ route('purchases.index') }}">
                    <i class="fa-solid fa-file-invoice"></i> Pembelian
                </a>
            </li>
        </ul>

        <div class="nav-label mt-3">Laporan</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('pos.history') ? 'active' : '' }}" href="{{ route('pos.history') }}">
                    <i class="fa-solid fa-clock-rotate-left"></i> Riwayat
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                    <i class="fa-solid fa-chart-column"></i> Analitik
                </a>
            </li>
        </ul>

        <div class="nav-label mt-3">Sistem</div>
        <ul class="nav flex-column">
            @if(auth()->user()->role === 'owner')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('stores.*') ? 'active' : '' }}" href="{{ route('stores.index') }}">
                    <i class="fa-solid fa-code-branch"></i> Cabang Toko
                </a>
            </li>
            @endif
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                    <i class="fa-solid fa-users"></i> Pegawai
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}" href="{{ route('settings.index') }}">
                    <i class="fa-solid fa-gear"></i> Pengaturan
                </a>
            </li>
        </ul>
        @endif
    </nav>

    <div class="sidebar-footer">
        @auth
        <div class="d-flex align-items-center gap-10" style="gap:10px;">
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px;background:var(--brand-gradient);color:#fff;font-weight:700;font-size:.82rem;flex-shrink:0;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="flex-grow-1" style="min-width:0;">
                <div style="color:#e2e8f0;font-size:.82rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ auth()->user()->name }}</div>
                <div style="color:#64748b;font-size:.7rem;text-transform:capitalize;">{{ auth()->user()->role }}</div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm p-1" style="color:#64748b;" title="Logout">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </button>
            </form>
        </div>
        @endauth
    </div>
</aside>

<!-- ═══════════ MAIN ═══════════ -->
<div class="main-wrapper">
    <!-- Topbar -->
    <header class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-ghost btn-sm d-lg-none" onclick="toggleSidebar()" id="sidebarToggle">
                <i class="fa-solid fa-bars"></i>
            </button>
            <span class="page-title">{{ $title ?? 'Dashboard' }}</span>
        </div>
        <div class="d-flex align-items-center gap-3">
            @if(auth()->check() && auth()->user()->role === 'owner')
            @php $allStores = \App\Models\Store::all(); $currentContext = \App\Helpers\StoreHelper::current(); @endphp
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-store me-1"></i> {{ $currentContext ? $currentContext->name : 'Pilih Cabang' }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    @foreach($allStores as $s)
                    <li><a class="dropdown-item {{ session('current_store_id') == $s->id ? 'active' : '' }}" href="{{ route('stores.switch', $s->id) }}">{{ $s->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            @endif

            <button class="btn btn-ghost btn-sm" id="themeToggle" onclick="toggleTheme()" title="Toggle Dark/Light Mode">
                <i class="fa-solid fa-sun"></i>
            </button>
            <span class="text-muted" style="font-size:.82rem;">
                <i class="fa-regular fa-calendar me-1"></i>{{ now()->translatedFormat('l, d M Y') }}
            </span>
        </div>
    </header>

    <!-- Content -->
    <main class="main-content">
        <!-- Toast Container -->
        <div class="toast-container" id="toastContainer">
            @if(session('success'))
            <div class="toast align-items-center text-bg-success border-0 show animate-slide" role="alert" data-bs-autohide="true" data-bs-delay="4000">
                <div class="d-flex">
                    <div class="toast-body"><i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
            @endif
            @if(session('error'))
            <div class="toast align-items-center text-bg-danger border-0 show animate-slide" role="alert" data-bs-autohide="true" data-bs-delay="5000">
                <div class="d-flex">
                    <div class="toast-body"><i class="fa-solid fa-circle-xmark me-2"></i>{{ session('error') }}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
            @endif
        </div>

        @if($errors->any())
        <div class="alert alert-danger border-0 rounded-3 mb-4 animate-in" style="background:#fef2f2;color:#991b1b;">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Dark mode logic
    const themeToggle = document.getElementById('themeToggle');
    const htmlTheme = document.documentElement;
    
    // Load theme from local storage
    if (localStorage.getItem('theme') === 'dark') {
        htmlTheme.setAttribute('data-bs-theme', 'dark');
        if (themeToggle) themeToggle.innerHTML = '<i class="fa-solid fa-moon"></i>';
    }

    function toggleTheme() {
        if (htmlTheme.getAttribute('data-bs-theme') === 'light') {
            htmlTheme.setAttribute('data-bs-theme', 'dark');
            localStorage.setItem('theme', 'dark');
            if (themeToggle) themeToggle.innerHTML = '<i class="fa-solid fa-moon"></i>';
        } else {
            htmlTheme.setAttribute('data-bs-theme', 'light');
            localStorage.setItem('theme', 'light');
            if (themeToggle) themeToggle.innerHTML = '<i class="fa-solid fa-sun"></i>';
        }
    }

    // Sidebar toggle
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('show');
        document.getElementById('sidebarBackdrop').classList.toggle('show');
    }

    // Auto-init toasts
    document.querySelectorAll('.toast').forEach(el => {
        const toast = bootstrap.Toast.getOrCreateInstance(el);
        toast.show();
    });
</script>
@stack('scripts')
</body>
</html>
