@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('breadcrumb')
    <span>Admin</span> / <span>Dashboard</span>
@endsection

@section('content')
<style>
    /* Stats Grid - 4 columns */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }
    
    @media (max-width: 1400px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(180deg, #2E7D32, #00A86B);
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
    }
    
    .stat-card.warning::before {
        background: linear-gradient(180deg, #FFA726, #FFB74D);
    }
    
    .stat-card.info::before {
        background: linear-gradient(180deg, #42A5F5, #64B5F6);
    }
    
    .stat-card.success::before {
        background: linear-gradient(180deg, #66BB6A, #81C784);
    }
    
    .stat-card.error::before {
        background: linear-gradient(180deg, #EF5350, #E57373);
    }
    
    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }
    
    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        background: linear-gradient(135deg, #2E7D32, #00A86B);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
    }
    
    .stat-card.warning .stat-icon {
        background: linear-gradient(135deg, #FFA726, #FFB74D);
        box-shadow: 0 4px 12px rgba(255, 167, 38, 0.3);
    }
    
    .stat-card.info .stat-icon {
        background: linear-gradient(135deg, #42A5F5, #64B5F6);
        box-shadow: 0 4px 12px rgba(66, 165, 245, 0.3);
    }
    
    .stat-card.success .stat-icon {
        background: linear-gradient(135deg, #66BB6A, #81C784);
        box-shadow: 0 4px 12px rgba(102, 187, 106, 0.3);
    }
    
    .stat-card.error .stat-icon {
        background: linear-gradient(135deg, #EF5350, #E57373);
        box-shadow: 0 4px 12px rgba(239, 83, 80, 0.3);
    }
    
    .stat-info {
        flex: 1;
        text-align: right;
    }
    
    .stat-label {
        font-size: 13px;
        font-weight: 600;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }
    
    .stat-value {
        font-size: 32px;
        font-weight: 800;
        color: #1a1a1a;
        line-height: 1;
    }
    
    .stat-footer {
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .stat-change {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 600;
        color: #66BB6A;
    }
    
    .stat-change.negative {
        color: #EF5350;
    }
    
    .stat-detail {
        font-size: 12px;
        color: #999;
    }
    
    /* Charts Section */
    .charts-container {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
        margin-bottom: 30px;
    }
    
    @media (max-width: 1200px) {
        .charts-container {
            grid-template-columns: 1fr;
        }
    }
    
    .chart-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }
    
    .chart-header {
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .chart-title {
        font-size: 18px;
        font-weight: 700;
        color: #1a1a1a;
    }
    
    .chart-subtitle {
        font-size: 13px;
        color: #666;
        margin-top: 4px;
    }
    
    /* Fixed Height Chart Containers */
    .chart-wrapper {
        position: relative;
        height: 280px; /* Fixed height untuk trend chart */
        width: 100%;
    }
    
    .chart-wrapper-small {
        position: relative;
        height: 280px; /* Fixed height untuk status chart */
        width: 100%;
    }
    
    .chart-wrapper-category {
        position: relative;
        height: 350px; /* Fixed height untuk category chart */
        width: 100%;
    }
    
    /* Table Modern */
    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .modern-table thead th {
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
    
    .modern-table thead th:first-child {
        border-top-left-radius: 12px;
    }
    
    .modern-table thead th:last-child {
        border-top-right-radius: 12px;
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
    
    .modern-table tbody tr:last-child td:first-child {
        border-bottom-left-radius: 12px;
    }
    
    .modern-table tbody tr:last-child td:last-child {
        border-bottom-right-radius: 12px;
    }
    
    .badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
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
    
    .btn {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .btn-view {
        background: #f0f0f0;
        color: #333;
    }
    
    .btn-view:hover {
        background: #2E7D32;
        color: white;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #2E7D32, #00A86B);
        color: white;
        box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(46, 125, 50, 0.4);
    }
    
    /* Grid 2 columns */
    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 30px;
    }
    
    @media (max-width: 1200px) {
        .grid-2 {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Total Laporan</div>
                <div class="stat-value">{{ number_format($stats['total_reports']) }}</div>
            </div>
        </div>
        <div class="stat-footer">
            <div class="stat-change">
                <i class="fas fa-arrow-up"></i>
                <span>{{ $stats['reports_this_month'] }} bulan ini</span>
            </div>
        </div>
    </div>
    
    <div class="stat-card warning">
        <div class="stat-header">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Menunggu</div>
                <div class="stat-value">{{ number_format($stats['pending_reports']) }}</div>
            </div>
        </div>
        <div class="stat-footer">
            <div class="stat-change negative">
                <i class="fas fa-exclamation-circle"></i>
                <span>Perlu tindakan</span>
            </div>
        </div>
    </div>
    
    <div class="stat-card info">
        <div class="stat-header">
            <div class="stat-icon">
                <i class="fas fa-spinner fa-spin"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Diproses</div>
                <div class="stat-value">{{ number_format($stats['process_reports']) }}</div>
            </div>
        </div>
        <div class="stat-footer">
            <div class="stat-change">
                <i class="fas fa-check"></i>
                <span>Dalam penanganan</span>
            </div>
        </div>
    </div>
    
    <div class="stat-card success">
        <div class="stat-header">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Selesai</div>
                <div class="stat-value">{{ number_format($stats['done_reports']) }}</div>
            </div>
        </div>
        <div class="stat-footer">
            <div class="stat-change">
                <i class="fas fa-arrow-up"></i>
                <span>{{ $stats['completion_rate'] }}% completion rate</span>
            </div>
        </div>
    </div>
</div>

<!-- Additional Stats -->
<div class="grid-2">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Total Pengguna</div>
                <div class="stat-value">{{ number_format($stats['total_users']) }}</div>
            </div>
        </div>
        <div class="stat-footer">
            <div class="stat-detail">Pengguna aktif</div>
        </div>
    </div>
    
    <div class="stat-card error">
        <div class="stat-header">
            <div class="stat-icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Ditolak</div>
                <div class="stat-value">{{ number_format($stats['rejected_reports']) }}</div>
            </div>
        </div>
        <div class="stat-footer">
            <div class="stat-detail">Laporan tidak valid</div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="charts-container">
    <!-- Trend Chart -->
    <div class="chart-card">
        <div class="chart-header">
            <div>
                <div class="chart-title">📈 Trend Laporan</div>
                <div class="chart-subtitle">30 hari terakhir</div>
            </div>
        </div>
        <div class="chart-wrapper">
            <canvas id="trendChart"></canvas>
        </div>
    </div>
    
    <!-- Status Distribution -->
    <div class="chart-card">
        <div class="chart-header">
            <div>
                <div class="chart-title">📊 Status Laporan</div>
                <div class="chart-subtitle">Distribusi saat ini</div>
            </div>
        </div>
        <div class="chart-wrapper-small">
            <canvas id="statusChart"></canvas>
        </div>
    </div>
</div>

<!-- Category Chart -->
<div class="chart-card" style="margin-bottom: 30px;">
    <div class="chart-header">
        <div>
            <div class="chart-title">🏷️ Laporan per Kategori</div>
            <div class="chart-subtitle">Semua waktu</div>
        </div>
    </div>
    <div class="chart-wrapper-category">
        <canvas id="categoryChart"></canvas>
    </div>
</div>

<!-- Recent Reports -->
<div class="chart-card">
    <div class="chart-header">
        <div>
            <div class="chart-title">🆕 Laporan Terbaru</div>
            <div class="chart-subtitle">10 laporan terakhir</div>
        </div>
        <a href="{{ route('admin.reports.index') }}" class="btn btn-primary">
            <i class="fas fa-eye"></i> Lihat Semua
        </a>
    </div>
    
    <table class="modern-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Pelapor</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentReports as $report)
            <tr>
                <td><strong>#{{ $report->id }}</strong></td>
                <td>{{ Str::limit($report->title, 45) }}</td>
                <td>
                    <i class="fas fa-tag" style="color: #2E7D32;"></i>
                    {{ $report->category->name ?? '-' }}
                </td>
                <td>{{ $report->user->name ?? 'Anonymous' }}</td>
                <td>
                    <span class="badge badge-{{ $report->status }}">
                        {{ ucfirst($report->status) }}
                    </span>
                </td>
                <td>{{ $report->created_at->format('d M Y, H:i') }}</td>
                <td>
                    <a href="{{ route('admin.reports.show', $report->id) }}" class="btn btn-view">
                        <i class="fas fa-eye"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 60px; color: #999;">
                    <i class="fas fa-inbox" style="font-size: 64px; opacity: 0.3; display: block; margin-bottom: 16px;"></i>
                    <strong>Belum ada laporan</strong>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    Chart.defaults.font.family = "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif";
    
    // Trend Chart
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    const gradient = trendCtx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(46, 125, 50, 0.2)');
    gradient.addColorStop(1, 'rgba(46, 125, 50, 0)');
    
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($trendData['labels']) !!},
            datasets: [{
                label: 'Laporan',
                data: {!! json_encode($trendData['values']) !!},
                borderColor: '#2E7D32',
                backgroundColor: gradient,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#2E7D32',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1a1a1a',
                    padding: 12,
                    titleFont: { size: 13, weight: '600' },
                    bodyFont: { size: 12 },
                    borderColor: '#2E7D32',
                    borderWidth: 2,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        color: '#666',
                        stepSize: 1 // Langkah 1 agar tidak membesar
                    },
                    grid: {
                        color: '#f0f0f0',
                        drawBorder: false
                    }
                },
                x: {
                    ticks: { color: '#666' },
                    grid: { display: false }
                }
            }
        }
    });
    
    // Status Chart
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($statusData['labels']) !!},
            datasets: [{
                data: {!! json_encode($statusData['values']) !!},
                backgroundColor: {!! json_encode($statusData['colors']) !!},
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        font: { size: 12, weight: '600' },
                        usePointStyle: true
                    }
                }
            }
        }
    });
    
    // Category Chart
    const categoryColors = {!! json_encode($categoryData['colors'] ?? []) !!};
    const defaultColors = ['#2E7D32', '#00A86B', '#81C784', '#FFA726', '#42A5F5', '#66BB6A', '#FFB74D', '#64B5F6'];
    
    new Chart(document.getElementById('categoryChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($categoryData['labels']) !!},
            datasets: [{
                label: 'Jumlah Laporan',
                data: {!! json_encode($categoryData['values']) !!},
                backgroundColor: categoryColors.length > 0 ? categoryColors : defaultColors,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1a1a1a',
                    padding: 12,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { 
                        precision: 0, 
                        color: '#666',
                        stepSize: 1 // Langkah 1 agar tidak membesar
                    },
                    grid: { color: '#f0f0f0', drawBorder: false }
                },
                x: {
                    ticks: { color: '#666' },
                    grid: { display: false }
                }
            }
        }
    });
</script>
@endpush
