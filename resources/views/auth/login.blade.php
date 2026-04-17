<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Kasir Pro</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            background: #0f172a;
            overflow: hidden;
        }
        /* Left Panel – Branding */
        .login-left {
            flex: 1; display: none;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #a78bfa 100%);
            padding: 60px;
            flex-direction: column; justify-content: center;
            position: relative; overflow: hidden;
        }
        .login-left::before {
            content: '';
            position: absolute; inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .login-left h1 { color: #fff; font-weight: 800; font-size: 2.4rem; margin-bottom: 16px; position: relative; }
        .login-left p { color: rgba(255,255,255,.8); font-size: 1.05rem; line-height: 1.7; max-width: 420px; position: relative; }
        .login-left .feature-list { list-style: none; padding: 0; margin-top: 32px; position: relative; }
        .login-left .feature-list li {
            color: rgba(255,255,255,.9); padding: 8px 0; font-size: .95rem;
            display: flex; align-items: center; gap: 12px;
        }
        .login-left .feature-list li i { color: #a5f3fc; }

        /* Right Panel – Form */
        .login-right {
            flex: 1; display: flex; align-items: center; justify-content: center;
            padding: 40px;
            background: #fff;
        }
        .login-box { width: 100%; max-width: 400px; }
        .login-box .brand-badge {
            width: 56px; height: 56px; border-radius: 14px;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 24px; margin-bottom: 24px;
            box-shadow: 0 8px 20px rgba(99,102,241,.3);
        }
        .login-box h2 { font-weight: 800; font-size: 1.5rem; color: #0f172a; margin-bottom: 6px; }
        .login-box .subtitle { color: #64748b; font-size: .9rem; margin-bottom: 32px; }

        .form-control {
            border-radius: 10px; border: 1.5px solid #e2e8f0; padding: 12px 16px;
            font-size: .9rem; transition: border-color .15s, box-shadow .15s;
        }
        .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99,102,241,.12);
        }
        .form-label { font-size: .82rem; font-weight: 600; color: #374151; }

        .btn-login {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            border: none; padding: 12px; border-radius: 10px;
            font-weight: 700; font-size: .95rem; color: #fff;
            box-shadow: 0 4px 14px rgba(99,102,241,.3);
            transition: all .2s;
        }
        .btn-login:hover {
            transform: translateY(-2px); color: #fff;
            box-shadow: 0 8px 20px rgba(99,102,241,.4);
        }
        .btn-login:active { transform: translateY(0); }

        .demo-creds {
            margin-top: 28px; padding: 16px; border-radius: 10px;
            background: #f8fafc; border: 1px solid #e2e8f0; font-size: .8rem;
        }
        .demo-creds strong { color: #6366f1; }

        @media (min-width: 992px) { .login-left { display: flex; } }
        @media (max-width: 991.98px) { .login-right { flex: 1; } }
    </style>
</head>
<body>

<div class="login-left">
    <h1>☕ Kasir Pro</h1>
    <p>Sistem Point of Sale modern untuk mengelola transaksi, stok, dan laporan cafe Anda dengan mudah.</p>
    <ul class="feature-list">
        <li><i class="fa-solid fa-circle-check"></i> Transaksi cepat & akurat</li>
        <li><i class="fa-solid fa-circle-check"></i> Manajemen produk & stok</li>
        <li><i class="fa-solid fa-circle-check"></i> Laporan penjualan realtime</li>
        <li><i class="fa-solid fa-circle-check"></i> Multi metode pembayaran</li>
    </ul>
</div>

<div class="login-right">
    <div class="login-box">
        <div class="brand-badge"><i class="fa-solid fa-mug-hot"></i></div>
        <h2>Selamat Datang!</h2>
        <p class="subtitle">Masuk ke dashboard untuk memulai.</p>

        @if(session('success'))
            <div class="alert alert-success border-0 rounded-3 py-2 px-3" style="font-size:.85rem;background:#f0fdf4;color:#166534;">
                <i class="fa-solid fa-circle-check me-1"></i> {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" placeholder="nama@email.com" required autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <div class="mb-4 form-check">
                <input type="checkbox" class="form-check-input" name="remember" id="remember">
                <label class="form-check-label" for="remember" style="font-size:.85rem;color:#64748b;">Ingat Saya</label>
            </div>
            <button type="submit" class="btn btn-login w-100">Masuk</button>
        </form>

        <div class="demo-creds">
            <div class="mb-1"><strong>Admin:</strong> admin@pos.com / password</div>
            <div><strong>Kasir:</strong> kasir@pos.com / password</div>
        </div>
    </div>
</div>

</body>
</html>
