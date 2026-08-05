@extends('layouts.admin')

@section('title', 'Kelola Kategori')

@section('breadcrumb')
    <span>Dashboard</span> / <span>Kategori</span>
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
    
    .categories-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
    }
    
    @media (max-width: 1024px) {
        .categories-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }
    
    .card-title {
        font-size: 18px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 20px;
    }
    
    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .modern-table thead th {
        background: #f8f9fa;
        padding: 12px 14px;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        color: #666;
        text-transform: uppercase;
        border-bottom: 2px solid #e0e0e0;
    }
    
    .modern-table tbody td {
        padding: 14px;
        border-bottom: 1px solid #f0f0f0;
        font-size: 14px;
    }
    
    .modern-table tbody tr:hover {
        background: #f8f9fa;
    }
    
    .color-preview {
        width: 40px;
        height: 24px;
        border-radius: 6px;
        border: 2px solid #e0e0e0;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #666;
        margin-bottom: 8px;
    }
    
    .form-input {
        width: 100%;
        padding: 10px 14px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.2s;
    }
    
    .form-input:focus {
        outline: none;
        border-color: #2E7D32;
    }
    
    .btn-primary {
        width: 100%;
        padding: 12px 20px;
        background: linear-gradient(135deg, #2E7D32, #00A86B);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
    }
    
    .btn-danger {
        padding: 6px 12px;
        background: #EF5350;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-danger:hover {
        background: #D32F2F;
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
    
    .alert-danger {
        background: rgba(239, 83, 80, 0.15);
        color: #D32F2F;
        border-left: 4px solid #EF5350;
    }
</style>

<div class="page-header">
    <h1 class="page-title">📂 Kelola Kategori</h1>
    <p class="page-subtitle">Kategori laporan masyarakat</p>
</div>

@if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle" style="font-size: 20px;"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle" style="font-size: 20px;"></i>
        <span>{{ session('error') }}</span>
    </div>
@endif

<div class="categories-grid">
    <!-- List Kategori -->
    <div class="card">
        <h3 class="card-title">📋 Daftar Kategori</h3>
        
        <table class="modern-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Icon</th>
                    <th>Warna</th>
                    <th>Laporan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td><strong>#{{ $category->id }}</strong></td>
                    <td><strong>{{ $category->name }}</strong></td>
                    <td>
                        @if($category->icon)
                        <i class="fas fa-{{ $category->icon }}" style="color: {{ $category->color ?? '#2E7D32' }}; font-size: 18px;"></i>
                        @else
                        -
                        @endif
                    </td>
                    <td>
                        @if($category->color)
                        <div class="color-preview" style="background: {{ $category->color }};"></div>
                        @else
                        -
                        @endif
                    </td>
                    <td>{{ $category->reports_count ?? 0 }}</td>
                    <td>
                        <button class="btn-danger" onclick="if(confirm('Yakin hapus kategori {{ $category->name }}?')) document.getElementById('delete-{{ $category->id }}').submit();">
                            <i class="fas fa-trash"></i>
                        </button>
                        <form id="delete-{{ $category->id }}" action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px; color: #999;">
                        <i class="fas fa-folder-open" style="font-size: 48px; opacity: 0.3; display: block; margin-bottom: 12px;"></i>
                        Belum ada kategori
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Form Tambah Kategori -->
    <div class="card">
        <h3 class="card-title">➕ Tambah Kategori Baru</h3>
        
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Nama Kategori *</label>
                <input type="text" name="name" class="form-input" placeholder="Contoh: Infrastruktur" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Icon (Font Awesome)</label>
                <input type="text" name="icon" class="form-input" placeholder="Contoh: road, trash-alt, lightbulb">
                <small style="color: #999; font-size: 12px;">Cari icon di: <a href="https://fontawesome.com/icons" target="_blank" style="color: #2E7D32;">fontawesome.com/icons</a></small>
            </div>
            
            <div class="form-group">
                <label class="form-label">Warna</label>
                <input type="color" name="color" class="form-input" value="#2E7D32">
            </div>
            
            <button type="submit" class="btn-primary">
                <i class="fas fa-plus-circle"></i> Tambah Kategori
            </button>
        </form>
    </div>
</div>
@endsection
