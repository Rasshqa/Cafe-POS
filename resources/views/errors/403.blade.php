<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Akses Ditolak</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh; display: flex;
            align-items: center; justify-content: center;
            background: #f1f5f9; color: #0f172a;
        }
        .container { text-align: center; max-width: 420px; padding: 40px; }
        .code { font-size: 6rem; font-weight: 800; color: #e2e8f0; line-height: 1; }
        h1 { font-size: 1.5rem; font-weight: 700; margin: 16px 0 8px; }
        p { color: #64748b; font-size: .9rem; margin-bottom: 32px; line-height: 1.6; }
        a {
            display: inline-block; padding: 12px 28px; border-radius: 10px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #fff;
            text-decoration: none; font-weight: 600; font-size: .9rem;
            transition: transform .2s, box-shadow .2s;
        }
        a:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(99,102,241,.3); }
    </style>
</head>
<body>
    <div class="container">
        <div class="code">403</div>
        <h1>Akses Ditolak</h1>
        <p>Maaf, akun Anda tidak memiliki izin untuk mengakses halaman ini. Hubungi administrator jika Anda merasa ini adalah kesalahan.</p>
        <a href="{{ url('/dashboard') }}"><i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard</a>
    </div>
</body>
</html>
