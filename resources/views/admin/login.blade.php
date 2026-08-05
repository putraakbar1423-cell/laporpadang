<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Sistem KKM</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #2E7D32 0%, #1B5E20 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 420px;
            padding: 40px;
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo h1 {
            color: #2E7D32;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .logo p {
            color: #666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #333;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #2E7D32;
            box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.1);
        }

        .error-message {
            background: #ffebee;
            color: #c62828;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            border-left: 4px solid #c62828;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #2E7D32 0%, #1B5E20 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(46, 125, 50, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .info-box {
            margin-top: 30px;
            padding: 16px;
            background: #f5f5f5;
            border-radius: 8px;
            border-left: 4px solid #2E7D32;
        }

        .info-box h4 {
            color: #2E7D32;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .info-box p {
            color: #666;
            font-size: 13px;
            line-height: 1.6;
        }

        .info-box code {
            background: white;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            color: #2E7D32;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #2E7D32;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <div style="display: flex; align-items: center; justify-content: center; gap: 12px; margin-bottom: 12px;">
                <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #D4AF37, #FFD700); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);">
                    <img src="{{ asset('assets/images/laporpadang.png') }}" alt="Sistem KKM" style="width: 32px; height: 32px; object-fit: contain;">
                </div>
                <h1 style="color: #2E7D32; font-size: 28px; font-weight: 700; margin: 0;">
                    Sistem <span style="color: #D4AF37;">KKM</span>
                </h1>
            </div>
            <p>Admin Dashboard</p>
        </div>

        @if ($errors->any())
            <div class="error-message">
                <strong>❌ Login Gagal!</strong><br>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email', 'admin@sistemkkm.id') }}" 
                    required 
                    autofocus
                    placeholder="admin@sistemkkm.id"
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required
                    placeholder="••••••••"
                >
            </div>

            <button type="submit" class="btn-login">
                🔐 Login ke Dashboard
            </button>
        </form>

        <div class="info-box">
            <h4>ℹ️ Default Admin Login:</h4>
            <p>
                <strong>Email:</strong> <code>admin@laporpadang.id</code><br>
                <strong>Password:</strong> <code>admin123</code>
            </p>
            <p style="margin-top: 8px; font-size: 12px; color: #999;">
                Jika belum ada admin, jalankan seeder:<br>
                <code>php artisan db:seed --class=AdminSeeder</code>
            </p>
        </div>

        <div class="back-link">
            <a href="/">← Kembali ke Beranda</a>
        </div>
    </div>
</body>
</html>
