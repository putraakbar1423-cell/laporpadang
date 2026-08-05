@extends('layouts.admin')

@section('title', 'Analytics Overview')

@section('breadcrumb')
    <span>Dashboard</span> / <span>Analytics</span> / <span>Overview</span>
@endsection

@section('content')
<style>
    .analytics-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }
    
    @media (max-width: 1200px) {
        .analytics-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .analytics-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .metric-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        border-left: 4px solid #2E7D32;
    }
    
    .metric-card.blue { border-left-color: #42A5F5; }
    .metric-card.orange { border-left-color: #FFA726; }
    .metric-card.purple { border-left-color: #AB47BC; }
    .metric-card.teal { border-left-color: #26A69A; }
    .metric-card.red { border-left-color: #EF5350; }
    
    .metric-label {
        font-size: 13px;
        font-weight: 600;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
    }
    
    .metric-value {
        font-size: 36px;
        font-weight: 800;
        color: #1a1a1a;
        margin-bottom: 8px;
    }
    
    .metric-desc {
        font-size: 13px;
        color: #999;
    }
    
    .chart-section {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        margin-bottom: 30px;
    }
    
    .section-title {
        font-size: 20px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 8px;
    }
    
    .section-subtitle {
        font-size: 14px;
        color: #666;
        margin-bottom: 24px;
    }
    
    /* Fixed Height Chart Containers */
    .chart-wrapper-trend {
        position: relative;
        height: 300px; /* Fixed height untuk trend */
        width: 100%;
    }
    
    .chart-wrapper-medium {
        position: relative;
        height: 280px; /* Fixed height untuk chart medium */
        width: 100%;
    }
    
    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    
    @media (max-width: 1024px) {
        .grid-2 {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="page-header" style="margin-bottom: 30px;">
    <h1 style="font-size: 28px; font-weight: 800; color: #1a1a1a; margin-bottom: 8px;">
        📊 Analytics Overview
    </h1>
    <p style="font-size: 15px; color: #666;">
        Statistik lengkap dan analisis mendalam sistem LaporPadang
    </p>
</div>

<!-- Key Metrics -->
<div class="analytics-grid">
    <div class="metric-card">
        <div class="metric-label">📈 Total Laporan</div>
        <div class="metric-value">1,234</div>
        <div class="metric-desc">+15% dari bulan lalu</div>
    </div>
    
    <div class="metric-card blue">
        <div class="metric-label">⏱️ Avg Response Time</div>
        <div class="metric-value">2.4 <span style="font-size: 18px;">hari</span></div>
        <div class="metric-desc">-0.6 hari lebih cepat</div>
    </div>
    
    <div class="metric-card orange">
        <div class="metric-label">✅ Completion Rate</div>
        <div class="metric-value">87%</div>
        <div class="metric-desc">+5% dari bulan lalu</div>
    </div>
    
    <div class="metric-card purple">
        <div class="metric-label">👥 Active Users</div>
        <div class="metric-value">456</div>
        <div class="metric-desc">Pengguna aktif bulan ini</div>
    </div>
    
    <div class="metric-card teal">
        <div class="metric-label">⭐ User Satisfaction</div>
        <div class="metric-value">4.5/5</div>
        <div class="metric-desc">Dari 234 reviews</div>
    </div>
    
    <div class="metric-card red">
        <div class="metric-label">⏰ Pending Reports</div>
        <div class="metric-value">42</div>
        <div class="metric-desc">Perlu tindakan segera</div>
    </div>
</div>

<!-- Trend Analysis -->
<div class="chart-section">
    <div class="section-title">📈 Trend Analysis</div>
    <div class="section-subtitle">Perkembangan laporan 6 bulan terakhir</div>
    <div class="chart-wrapper-trend">
        <canvas id="trendAnalysisChart"></canvas>
    </div>
</div>

<!-- Category Performance & Peak Hours -->
<div class="grid-2">
    <div class="chart-section">
        <div class="section-title">🏆 Top Categories</div>
        <div class="section-subtitle">Kategori dengan laporan terbanyak</div>
        <div class="chart-wrapper-medium">
            <canvas id="topCategoriesChart"></canvas>
        </div>
    </div>
    
    <div class="chart-section">
        <div class="section-title">📊 Status Distribution</div>
        <div class="section-subtitle">Distribusi status laporan</div>
        <div class="chart-wrapper-medium">
            <canvas id="statusDistChart"></canvas>
        </div>
    </div>
</div>

<!-- Performance Indicators -->
<div class="chart-section">
    <div class="section-title">⚡ Performance Indicators</div>
    <div class="section-subtitle">KPI sistem LaporPadang</div>
    
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-top: 20px;">
        <div style="text-align: center; padding: 20px; background: #f8f9fa; border-radius: 12px;">
            <div style="font-size: 32px; font-weight: 800; color: #2E7D32;">98%</div>
            <div style="font-size: 13px; color: #666; margin-top: 8px;">Uptime</div>
        </div>
        <div style="text-align: center; padding: 20px; background: #f8f9fa; border-radius: 12px;">
            <div style="font-size: 32px; font-weight: 800; color: #42A5F5;">1.2s</div>
            <div style="font-size: 13px; color: #666; margin-top: 8px;">Avg Load Time</div>
        </div>
        <div style="text-align: center; padding: 20px; background: #f8f9fa; border-radius: 12px;">
            <div style="font-size: 32px; font-weight: 800; color: #FFA726;">85%</div>
            <div style="font-size: 13px; color: #666; margin-top: 8px;">Mobile Users</div>
        </div>
        <div style="text-align: center; padding: 20px; background: #f8f9fa; border-radius: 12px;">
            <div style="font-size: 32px; font-weight: 800; color: #66BB6A;">92%</div>
            <div style="font-size: 13px; color: #666; margin-top: 8px;">First Contact Resolution</div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    Chart.defaults.font.family = "'Inter', sans-serif";
    
    // Trend Analysis (6 months)
    new Chart(document.getElementById('trendAnalysisChart'), {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
            datasets: [{
                label: 'Laporan Masuk',
                data: [156, 189, 234, 278, 312, 345],
                borderColor: '#2E7D32',
                backgroundColor: 'rgba(46, 125, 50, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }, {
                label: 'Laporan Selesai',
                data: [142, 176, 218, 256, 287, 312],
                borderColor: '#66BB6A',
                backgroundColor: 'rgba(102, 187, 106, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: { font: { size: 12, weight: '600' }, usePointStyle: true }
                }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f0f0f0' } },
                x: { grid: { display: false } }
            }
        }
    });
    
    // Top Categories
    new Chart(document.getElementById('topCategoriesChart'), {
        type: 'bar',
        data: {
            labels: ['Infrastruktur', 'Kebersihan', 'Penerangan', 'Lalu Lintas', 'Banjir'],
            datasets: [{
                label: 'Jumlah Laporan',
                data: [345, 287, 234, 198, 156],
                backgroundColor: ['#2E7D32', '#00A86B', '#81C784', '#FFA726', '#42A5F5'],
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true, grid: { color: '#f0f0f0' } },
                y: { grid: { display: false } }
            }
        }
    });
    
    // Status Distribution Chart
    new Chart(document.getElementById('statusDistChart'), {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Diproses', 'Selesai', 'Ditolak'],
            datasets: [{
                data: [42, 87, 234, 15],
                backgroundColor: ['#FFA726', '#42A5F5', '#66BB6A', '#EF5350'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { font: { size: 11 }, padding: 10, usePointStyle: true }
                }
            }
        }
    });
</script>
@endpush
