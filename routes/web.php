<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/

// Admin login page
Route::get('admin/login', function () {
    // Jika sudah login, redirect ke dashboard
    if (Auth::check() && Auth::user()->role === 'admin') {
        return redirect('/admin/dashboard');
    }
    return view('admin.login');
})->name('admin.login');

// Admin login handler
Route::post('admin/login', function (\Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);
    
    // Attempt login
    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        
        // Check if user is admin
        if ($user->role === 'admin') {
            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard');
        } else {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Anda tidak memiliki akses admin.',
            ])->onlyInput('email');
        }
    }
    
    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ])->onlyInput('email');
})->name('admin.login.post');

// Admin logout
Route::post('admin/logout', function (\Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/admin/login');
})->name('admin.logout');

// Admin routes
require __DIR__.'/admin.php';
