<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; display: flex; align-items: center; min-height: 100vh; }
        .card { border-radius: 10px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="text-center mb-4">
                <h3 class="fw-bold text-primary"><i class="fa-solid fa-store"></i> KASIR PRO</h3>
                <p class="text-muted">Login untuk masuk ke sistem dashboard</p>
            </div>
            
            <div class="card p-4">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email Address</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" name="remember" id="remember">
                        <label class="form-check-label" for="remember">Remember Me</label>
                    </div>
                    <button class="btn btn-primary w-100 fw-bold py-2">LOGIN</button>
                </form>
                
                <div class="mt-4 text-center text-muted" style="font-size: 13px;">
                    Login Default:<br>
                    Admin: admin@pos.com (password)<br>
                    Kasir: kasir@pos.com (password)
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
