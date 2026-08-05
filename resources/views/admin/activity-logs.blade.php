@extends('layouts.admin')

@section('title', 'Log Aktivitas')

@section('breadcrumb')
    <span>Admin</span> / <span>Log Aktivitas</span>
@endsection

@section('content')
<style>
    .logs-container {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }
    
    .page-header {
        margin-bottom: 30px;
    }
    
    .page-title {
        font-size: 28px;
        font-weight: 800;
        color: #1a1a1a;
        margin-bottom: 8px;
    }
    
    .page-subtitle {
        font-size: 15px;
        color: #666;
    }
    
    .filter-bar {
        display: flex;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }
    
    .filter-item {
        flex: 1;
        min-width: 200px;
    }
    
    .filter-item label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #666;
        margin-bottom: 8px;
    }
    
    .filter-item select,
    .filter-item input {
        width: 100%;
        padding: 10px 14px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s;
    }
    
    .filter-item select:focus,
    .filter-item input:focus {
        outline: none;
        border-color: #2E7D32;
    }
    
    .logs-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .logs-table thead th {
        background: #f8f9fa;
        padding: 16px;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e0e0e0;
    }
    
    .logs-table thead th:first-child {
        border-top-left-radius: 12px;
    }
    
    .logs-table thead th:last-child {
        border-top-right-radius: 12px;
    }
    
    .logs-table tbody td {
        padding: 16px;
        border-bottom: 1px solid #f0f0f0;
        font-size: 14px;
        color: #333;
    }
    
    .logs-table tbody tr:hover {
        background: #f8f9fa;
    }
    
    .log-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }
    
    .log-badge.create {
        background: rgba(102, 187, 106, 0.15);
        color: #388E3C;
    }
    
    .log-badge.update {
        background: rgba(66, 165, 245, 0.15);
        color: #1976D2;
    }
    
    .log-badge.delete {
        background: rgba(239, 83, 80, 0.15);
        color: #D32F2F;
    }
    
    .log-badge.login {
        background: rgba(212, 175, 55, 0.15);
        color: #F57F17;
    }
    
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        color: #999;
    }
    
    .empty-state i {
        font-size: 64px;
        opacity: 0.3;
        display: block;
        margin-bottom: 16px;
    }
    
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        margin-top: 24px;
    }
    
    .pagination button {
        padding: 8px 16px;
        border: 1px solid #e0e0e0;
        background: white;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .pagination button:hover {
        background: #2E7D32;
        color: white;
        border-color: #2E7D32;
    }
    
    .pagination button.active {
        background: #2E7D32;
        color: white;
        border-color: #2E7D32;
    }
</style>

<div class="page-header">
    <h1 class="page-title">📜 Log Aktivitas</h1>
    <p class="page-subtitle">Riwayat aktivitas pengguna dan admin di sistem</p>
</div>

<div class="logs-container">
    <!-- Filter Bar -->
    <div class="filter-bar">
        <div class="filter-item">
            <label for="filter-action">Tipe Aktivitas</label>
            <select id="filter-action">
                <option value="">Semua Aktivitas</option>
                <option value="create">Create</option>
                <option value="update">Update</option>
                <option value="delete">Delete</option>
                <option value="login">Login</option>
            </select>
        </div>
        
        <div class="filter-item">
            <label for="filter-user">Pengguna</label>
            <select id="filter-user">
                <option value="">Semua Pengguna</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
        </div>
        
        <div class="filter-item">
            <label for="filter-date">Tanggal</label>
            <input type="date" id="filter-date">
        </div>
        
        <div class="filter-item" style="display: flex; align-items: flex-end;">
            <button onclick="applyFilters()" style="width: 100%; padding: 10px 14px; background: linear-gradient(135deg, #2E7D32, #00A86B); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                <i class="fas fa-filter"></i> Filter
            </button>
        </div>
    </div>
    
    <!-- Logs Table -->
    <table class="logs-table">
        <thead>
            <tr>
                <th>Waktu</th>
                <th>Pengguna</th>
                <th>Aktivitas</th>
                <th>Deskripsi</th>
                <th>IP Address</th>
            </tr>
        </thead>
        <tbody>
            @forelse($activityLogs ?? [] as $log)
            <tr>
                <td>
                    <div style="font-weight: 600;">{{ $log->created_at->format('d M Y') }}</div>
                    <div style="font-size: 12px; color: #999;">{{ $log->created_at->format('H:i:s') }}</div>
                </td>
                <td>
                    <div style="font-weight: 600;">{{ $log->user->name ?? 'System' }}</div>
                    <div style="font-size: 12px; color: #999;">{{ $log->user->email ?? '-' }}</div>
                </td>
                <td>
                    <span class="log-badge {{ strtolower($log->action) }}">
                        {{ ucfirst($log->action) }}
                    </span>
                </td>
                <td>{{ $log->description }}</td>
                <td style="font-family: monospace; font-size: 13px; color: #666;">
                    {{ $log->ip_address ?? '-' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5">
                    <div class="empty-state">
                        <i class="fas fa-history"></i>
                        <strong style="display: block; margin-bottom: 8px;">Belum ada log aktivitas</strong>
                        <span>Log aktivitas akan muncul di sini</span>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <!-- Pagination -->
    @if(isset($activityLogs) && $activityLogs->hasPages())
    <div class="pagination">
        @if($activityLogs->onFirstPage())
            <button disabled style="opacity: 0.5; cursor: not-allowed;">
                <i class="fas fa-chevron-left"></i>
            </button>
        @else
            <a href="{{ $activityLogs->previousPageUrl() }}">
                <button><i class="fas fa-chevron-left"></i></button>
            </a>
        @endif
        
        @foreach($activityLogs->getUrlRange(1, $activityLogs->lastPage()) as $page => $url)
            <a href="{{ $url }}">
                <button class="{{ $page == $activityLogs->currentPage() ? 'active' : '' }}">
                    {{ $page }}
                </button>
            </a>
        @endforeach
        
        @if($activityLogs->hasMorePages())
            <a href="{{ $activityLogs->nextPageUrl() }}">
                <button><i class="fas fa-chevron-right"></i></button>
            </a>
        @else
            <button disabled style="opacity: 0.5; cursor: not-allowed;">
                <i class="fas fa-chevron-right"></i>
            </button>
        @endif
    </div>
    @endif
</div>

<script>
function applyFilters() {
    const action = document.getElementById('filter-action').value;
    const user = document.getElementById('filter-user').value;
    const date = document.getElementById('filter-date').value;
    
    const params = new URLSearchParams();
    if (action) params.append('action', action);
    if (user) params.append('user', user);
    if (date) params.append('date', date);
    
    window.location.href = '{{ route("admin.activity-logs") }}?' + params.toString();
}
</script>
@endsection
