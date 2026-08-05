@extends('layouts.admin')

@section('title', 'Detail Laporan #' . $report->id)

@section('breadcrumb')
    <span>Dashboard</span> / <span>Laporan</span> / <span>Detail</span>
@endsection

@section('content')
<style>
    .detail-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }
    
    @media (max-width: 1024px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        margin-bottom: 20px;
    }
    
    .card-title {
        font-size: 18px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .report-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 24px;
    }
    
    .report-title {
        font-size: 24px;
        font-weight: 800;
        color: #1a1a1a;
        margin-bottom: 8px;
    }
    
    .report-meta {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: #666;
    }
    
    .badge {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
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
    
    .info-group {
        margin-bottom: 20px;
    }
    
    .info-label {
        font-size: 12px;
        font-weight: 600;
        color: #666;
        text-transform: uppercase;
        margin-bottom: 8px;
    }
    
    .info-value {
        font-size: 15px;
        color: #1a1a1a;
        line-height: 1.6;
    }
    
    .images-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 12px;
        margin-top: 12px;
    }
    
    .image-item {
        aspect-ratio: 1;
        border-radius: 12px;
        overflow: hidden;
        cursor: pointer;
        transition: transform 0.2s;
    }
    
    .image-item:hover {
        transform: scale(1.05);
    }
    
    .image-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .status-form {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 12px;
    }
    
    .form-group {
        margin-bottom: 16px;
    }
    
    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #666;
        margin-bottom: 8px;
    }
    
    .form-select, .form-textarea {
        width: 100%;
        padding: 10px 14px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.2s;
    }
    
    .form-select:focus, .form-textarea:focus {
        outline: none;
        border-color: #2E7D32;
    }
    
    .form-textarea {
        min-height: 120px;
        resize: vertical;
        font-family: inherit;
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
        width: 100%;
        padding: 12px 20px;
        background: #EF5350;
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-danger:hover {
        background: #D32F2F;
    }
    
    .btn-back {
        padding: 10px 20px;
        background: #f0f0f0;
        color: #666;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }
    
    .btn-back:hover {
        background: #e0e0e0;
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
    
    .map-container {
        height: 300px;
        border-radius: 12px;
        overflow: hidden;
        margin-top: 12px;
    }
    
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 11px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e0e0e0;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 24px;
    }
    
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    
    .timeline-dot {
        position: absolute;
        left: -30px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: white;
        border: 3px solid #2E7D32;
        z-index: 1;
    }
    
    .timeline-content {
        background: #f8f9fa;
        padding: 12px 16px;
        border-radius: 10px;
    }
    
    .timeline-date {
        font-size: 12px;
        color: #999;
        margin-bottom: 4px;
    }
    
    .timeline-text {
        font-size: 14px;
        color: #1a1a1a;
        font-weight: 500;
    }
</style>

<div style="margin-bottom: 20px;">
    <a href="{{ route('admin.reports.index') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle" style="font-size: 20px;"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<div class="detail-grid">
    <!-- Main Content -->
    <div>
        <!-- Report Details Card -->
        <div class="card">
            <div class="report-header">
                <div>
                    <div style="font-size: 12px; color: #999; margin-bottom: 4px;">#{{ $report->id }}</div>
                    <h1 class="report-title">{{ $report->title }}</h1>
                </div>
                <span class="badge badge-{{ $report->status }}">
                    {{ ucfirst($report->status) }}
                </span>
            </div>
            
            <div class="report-meta">
                <div class="meta-item">
                    <i class="fas fa-user" style="color: #2E7D32;"></i>
                    <span>{{ $report->user->name ?? 'Anonymous' }}</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-tag" style="color: #2E7D32;"></i>
                    <span>{{ $report->category->name ?? '-' }}</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-calendar" style="color: #2E7D32;"></i>
                    <span>{{ $report->created_at->format('d M Y, H:i') }}</span>
                </div>
            </div>
            
            <div class="info-group">
                <div class="info-label">📍 Lokasi</div>
                <div class="info-value">{{ $report->location }}</div>
                @if($report->latitude && $report->longitude)
                <div class="map-container">
                    <iframe 
                        src="https://www.openstreetmap.org/export/embed.html?bbox={{ $report->longitude-0.01 }},{{ $report->latitude-0.01 }},{{ $report->longitude+0.01 }},{{ $report->latitude+0.01 }}&layer=mapnik&marker={{ $report->latitude }},{{ $report->longitude }}"
                        style="border:0; width: 100%; height: 100%;"
                        loading="lazy">
                    </iframe>
                </div>
                @endif
            </div>
            
            <div class="info-group">
                <div class="info-label">📝 Deskripsi</div>
                <div class="info-value">{{ $report->description }}</div>
            </div>
            
            @php
                // Cek apakah ada images dari JSON atau dari relasi
                $reportImages = [];
                
                // Coba dari JSON array
                if ($report->images && is_array($report->images) && count($report->images) > 0) {
                    $reportImages = $report->images;
                }
                
                // Atau coba dari relasi report_images table
                if (empty($reportImages) && $report->images()->exists()) {
                    $reportImages = $report->images()->pluck('image_path')->toArray();
                }
            @endphp
            
            @if(count($reportImages) > 0)
            <div class="info-group">
                <div class="info-label">📷 Foto Laporan ({{ count($reportImages) }})</div>
                <div class="images-grid">
                    @foreach($reportImages as $image)
                    <div class="image-item">
                        @php
                            // Handle different image path formats
                            // Images are stored as 'storage/reports/filename.jpg'
                            if (str_starts_with($image, 'storage/')) {
                                // Already has 'storage/' prefix, use as is
                                $imageUrl = asset($image);
                            } elseif (str_starts_with($image, 'reports/')) {
                                // Missing 'storage/' prefix
                                $imageUrl = asset('storage/' . $image);
                            } elseif (str_starts_with($image, 'http')) {
                                // Full URL
                                $imageUrl = $image;
                            } else {
                                // Fallback: assume it's just filename
                                $imageUrl = asset('storage/reports/' . $image);
                            }
                        @endphp
                        <img src="{{ $imageUrl }}" alt="Foto Laporan" 
                             onclick="window.open(this.src, '_blank')" 
                             onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 300%22%3E%3Crect fill=%22%23eee%22 width=%22400%22 height=%22300%22/%3E%3Ctext fill=%22%23999%22 font-size=%2220%22 x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22%3EFoto tidak tersedia%3C/text%3E%3C/svg%3E'; this.style.cursor='not-allowed';">
                    </div>
                    @endforeach
                </div>
                <p style="font-size: 12px; color: #999; margin-top: 8px;">Klik foto untuk memperbesar</p>
            </div>
            @else
            <div class="info-group">
                <div class="info-label">📷 Foto Laporan</div>
                <div style="text-align: center; padding: 40px; background: #f8f9fa; border-radius: 12px;">
                    <i class="fas fa-camera" style="font-size: 48px; color: #ddd; margin-bottom: 12px;"></i>
                    <p style="color: #999; font-size: 14px;">Tidak ada foto dilampirkan</p>
                </div>
            </div>
            @endif
            
            @if($report->admin_note)
            <div class="info-group">
                <div class="info-label">📋 Catatan Admin</div>
                <div class="info-value" style="background: #fff3e0; padding: 16px; border-radius: 10px; border-left: 4px solid #FFA726;">
                    {{ $report->admin_note }}
                </div>
            </div>
            @endif
        </div>
        
        <!-- Timeline Card -->
        @if($report->timeline && count($report->timeline) > 0)
        <div class="card">
            <div class="card-title">
                <i class="fas fa-history" style="color: #2E7D32;"></i>
                Timeline
            </div>
            
            <div class="timeline">
                @foreach($report->timeline as $item)
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <div class="timeline-date">{{ \Carbon\Carbon::parse($item['timestamp'])->format('d M Y, H:i') }}</div>
                        <div class="timeline-text">{{ $item['description'] }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    
    <!-- Sidebar -->
    <div>
        <!-- User Info Card -->
        <div class="card">
            <div class="card-title">
                <i class="fas fa-user" style="color: #2E7D32;"></i>
                Info Pelapor
            </div>
            
            <div style="text-align: center; margin-bottom: 20px;">
                <div style="width: 80px; height: 80px; border-radius: 50%; background: #f0f0f0; margin: 0 auto 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-user" style="font-size: 32px; color: #999;"></i>
                </div>
                <div style="font-size: 16px; font-weight: 700; color: #1a1a1a;">{{ $report->user->name ?? 'Anonymous' }}</div>
                <div style="font-size: 13px; color: #666;">{{ $report->user->email ?? '-' }}</div>
            </div>
            
            <div style="background: #f8f9fa; padding: 12px; border-radius: 10px; font-size: 13px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span style="color: #666;">📞 Telepon:</span>
                    <span style="font-weight: 600;">{{ $report->user->phone ?? '-' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #666;">📊 Total Laporan:</span>
                    <span style="font-weight: 600; color: #2E7D32;">{{ $report->user->reports->count() }}</span>
                </div>
            </div>
        </div>
        
        <!-- Update Status Card -->
        <div class="card">
            <div class="card-title">
                <i class="fas fa-edit" style="color: #2E7D32;"></i>
                Update Status
            </div>
            
            <form action="{{ route('admin.reports.update-status', $report->id) }}" method="POST" class="status-form">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label class="form-label">Status Laporan</label>
                    <select name="status" class="form-select" required>
                        <option value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="process" {{ $report->status == 'process' ? 'selected' : '' }}>Diproses</option>
                        <option value="done" {{ $report->status == 'done' ? 'selected' : '' }}>Selesai</option>
                        <option value="rejected" {{ $report->status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Catatan Admin</label>
                    <textarea name="admin_note" class="form-textarea" placeholder="Tambahkan catatan untuk pelapor...">{{ $report->admin_note }}</textarea>
                    <small style="color: #999; font-size: 12px; margin-top: 4px; display: block;">
                        Catatan ini akan terlihat oleh pelapor
                    </small>
                </div>
                
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </form>
        </div>
        
        <!-- Delete Card -->
        <div class="card">
            <div class="card-title" style="color: #EF5350;">
                <i class="fas fa-trash"></i>
                Zona Bahaya
            </div>
            
            <p style="font-size: 13px; color: #666; margin-bottom: 16px;">
                Tindakan ini tidak dapat dibatalkan. Laporan akan dihapus permanen.
            </p>
            
            <form action="{{ route('admin.reports.destroy', $report->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus laporan ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">
                    <i class="fas fa-trash"></i> Hapus Laporan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
