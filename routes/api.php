<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| LaporPadang API v1. All routes are grouped under the `api/v1` prefix and
| authenticated routes require a Sanctum bearer token.
|
*/

Route::prefix('v1')->group(function () {
    // ── Auth (public) ───────────────────────────────────────────────
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/refresh', [AuthController::class, 'refresh']);

    // ── Categories (public) ───────────────────────────────────────
    Route::get('/categories', [CategoryController::class, 'index']);

    Route::middleware('auth:sanctum')->group(function () {
        // ── Auth (protected) ─────────────────────────────────────────
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // ── User ─────────────────────────────────────────────────────
        Route::get('/users/profile', [UserController::class, 'profile']);
        Route::put('/users/profile', [UserController::class, 'updateProfile']);

        // ── Reports ──────────────────────────────────────────────────
        Route::apiResource('reports', ReportController::class)
            ->only(['index', 'show', 'store', 'update', 'destroy']);

        // ── Notifications ────────────────────────────────────────────
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
        Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);

        // ── Admin ────────────────────────────────────────────────────
        Route::middleware('admin')->group(function () {
            Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
            Route::put('/admin/reports/{report}/status', [AdminController::class, 'updateStatus']);
        });
    });
});
