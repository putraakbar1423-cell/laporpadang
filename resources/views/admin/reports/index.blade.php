@extends('layouts.admin')

@section('title', 'Kelola Laporan')

@section('breadcrumb')
    <span>Dashboard</span> / <span>Laporan</span>
@endsection

@section('content')
<style>
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
    
    .filter-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        margin-bottom: 20px;
    }
    
    .filter-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 16px;
    }
    
    @media (max-width: 1200px) {
        .filter-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .filter-label {
        font-size: 13px;
        font-weight: 600;
        color: #666;
    }
    
    .filter-input {
        padding: 10px 14px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.2s;
    }
    
    .filter-input:focus {
        outline: none;
        border-color: #2E7D32;
    }
    
    .btn-filter {
        padding: 10px 20px;
        background: linear-gradient(135deg, #2E7D32, #00A86B);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
    }
    
    .btn-reset {
        padding: 10px 20px;
        background: #f0f0f0;
        color: #666;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
    }
    
    .table-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }
    
    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .modern-table thead th {
        background: #f8f9fa;
        padding: 14px 16px;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e0e0e0;
    }
    
    .modern-table tbody td {
        padding: 16px;
        border-bottom: 1px solid #f0f0f0;
        font-size: 14px;
        color: #333;
    }
    
    .modern-table tbody tr:hover {
        background: #f8f9fa;
    }
    
    .badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }
    
    .badge-pending {
        background: rgba(255, 167, 38, 0.15);
        color: #F57C00;
    }
    
    .badge-process {
        background: rgba(66, 165, 245, 0.15);
        color: #1976D2;
    }
    
    .badge-done {
        background: rgba(102, 187, 106, 0.15);
        color: #388E3C;
    }
    
    .badge-rejected {
        background: rgba(239, 83, 80, 0.15);
        color: #D32F2F;
    }
    
    .btn-action {
        padding: 8px 16px;
        background: #f0f0f0;
        color: #333;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }
    
    .btn-action:hover {
        background: #2E7D32;
        color: white;
    }
    
    .pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 24px;
    }
    
    .alert {
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .alert-success {
        background: rgba(102, 187, 106, 0.15);
        color: #388E3C;
        border-left: 4px solid #66BB6A;
    }
</style>

<div class="page-header">
    <h1 class="page-title">📋 Kelola Laporan</h1>
    <p class="page-subtitle">Daftar semua laporan masyarakat</p>
</div>

@if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle" style="font-size: 20px;"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<!-- Filter Card -->
<div class="filter-card">
    <form method="GET" action="{{ route('admin.reports.index') }}">
        <div class="filter-grid">
            <div class="filter-group">
                <label class="filter-label">🔍 Cari Laporan</label>
                <input type="text" name="search" class="filter-input" placeholder="Judul atau deskripsi..." value="{{ request('search') }}">
            </div>
            
            <div class="filter-group">
                <label class="filter-label">📂 Kategori</label>
                <select name="category" class="filter-input">
                    <option value="">Semua Kategori</option>
                    @foreach(\App\Models\Category::all() as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">📊 Status</label>
                <select name="status" class="filter-input">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="process" {{ request('status') == 'process' ? 'selected' : '' }}>Diproses</option>
                    <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Selesai</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">📅 Tanggal</label>
                <input type="date" name="date" class="filter-input" value="{{ request('date') }}">
            </div>
        </div>
        
        <div style="display: flex; gap: 12px;">
            <button type="submit" class="btn-filter">
                <i class="fas fa-search"></i> Filter
            </button>
            <a href="{{ route('admin.reports.index') }}" class="btn-reset">
                <i class="fas fa-redo"></i> Reset
            </a>
        </div>
    </form>
</div>

<!-- Table Card -->
<div class="table-card">
    <table class="modern-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Pelapor</th>
                <th>Lokasi</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $report)
            <tr>
                <td><strong>#{{ $report->id }}</strong></td>
                <td>{{ Str::limit($report->title, 40) }}</td>
                <td>
                    <i class="fas fa-tag" style="color: #2E7D32;"></i>
                    {{ $report->category->name ?? '-' }}
                </td>
                <td>{{ $report->user->name ?? 'Anonymous' }}</td>
                <td>{{ Str::limit($report->location, 30) }}</td>
                <td>
                    <span class="badge badge-{{ $report->status }}">
                        {{ ucfirst($report->status) }}
                    </span>
                </td>
                <td>{{ $report->created_at->format('d M Y') }}</td>
                <td>
                    <a href="{{ route('admin.reports.show', $report->id) }}" class="btn-action">
                        <i class="fas fa-eye"></i> Detail
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 60px; color: #999;">
                    <i class="fas fa-inbox" style="font-size: 48px; opacity: 0.3; display: block; margin-bottom: 16px;"></i>
                    <strong>Belum ada laporan</strong>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    @if($reports->hasPages())
    <div class="pagination">
        {{ $reports->links() }}
    </div>
    @endif
</div>
@endsection
