@extends('layouts.admin')

@section('title', 'Dashboard Error')

@section('content')
<div style="padding: 40px; background: white; border-radius: 16px; margin: 20px;">
    <h1 style="color: #EF5350; margin-bottom: 20px;">
        <i class="fas fa-exclamation-triangle"></i> Dashboard Error
    </h1>
    
    <div style="background: #ffebee; padding: 20px; border-radius: 12px; margin-bottom: 20px;">
        <h3 style="color: #c62828; margin-bottom: 10px;">Error Message:</h3>
        <pre style="color: #d32f2f; font-size: 14px; white-space: pre-wrap;">{{ $error }}</pre>
    </div>
    
    <div style="background: #fff3e0; padding: 20px; border-radius: 12px;">
        <h3 style="color: #ef6c00; margin-bottom: 10px;">💡 Troubleshooting:</h3>
        <ul style="color: #e65100;">
            <li>Pastikan database sudah di-migrate: <code>php artisan migrate</code></li>
            <li>Pastikan seeder sudah dijalankan: <code>php artisan db:seed</code></li>
            <li>Cek koneksi database di file <code>.env</code></li>
            <li>Clear cache: <code>php artisan config:clear</code></li>
        </ul>
    </div>
</div>
@endsection
