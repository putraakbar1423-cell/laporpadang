@extends('layouts.admin')

@section('title', 'Analytics per Kecamatan')

@section('breadcrumb')
    <span>Dashboard</span> / <span>Analytics</span> / <span>Kecamatan</span>
@endsection

@section('content')
<style>
    .district-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .district-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        transition: all 0.3s;
        border-top: 4px solid #2E7D32;
    }
    
    .district-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }
    
    .district-name {
        font-size: 16px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 16px;
    }
    
    .district-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    .district-stat {
        background: #f8f9fa;
        padding: 12px;
        border-radius: 10px;
    }
    
    .stat-label {
        font-size: 11px;
        color: #666;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }
    
    .stat-value {
        font-size: 20px;
        font-weight: 800;
        color: #1a1a1a;
    }
    
    .completion-bar {
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid #f0f0f0;
    }
    
    .completion-label {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 12px;
    }
    
    .progress-bar {
        height: 8px;
        background: #e0e0e0;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #2E7D32, #66BB6A);
        border-radius: 10px;
        transition: width 1s ease;
    }
    
    .top-performers {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        margin-bottom: 30px;
    }
    
    .performer-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .performer-item:last-child {
        border-bottom: none;
    }
    
    .rank-badge {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: 800;
        color: white;
    }
    
    .rank-1 { background: linear-gradient(135deg, #FFD700, #FFA500); }
    .rank-2 { background: linear-gradient(135deg, #C0C0C0, #A8A8A8); }
    .rank-3 { background: linear-gradient(135deg, #CD7F32, #B8860B); }
    .rank-other { background: linear-gradient(135deg, #78909C, #546E7A); }
    
    .performer-info {
        flex: 1;
    }
    
    .performer-name {
        font-size: 16px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 4px;
    }
    
    .performer-desc {
        font-size: 13px;
        color: #666;
    }
    
    .performer-value {
        font-size: 24px;
        font-weight: 800;
        color: #2E7D32;
    }
    
    .section-card {
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
    .chart-wrapper-district {
        position: relative;
        height: 350px; /* Fixed height untuk district chart */
        width: 100%;
    }
    
    .chart-wrapper-trend {
        position: relative;
        height: 320px; /* Fixed height untuk trend chart */
        width: 100%;
    }
    
    .comparison-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .comparison-table thead th {
        background: #f8f9fa;
        padding: 14px;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        color: #666;
        text-transform: uppercase;
        border-bottom: 2px solid #e0e0e0;
    }
    
    .comparison-table tbody td {
        padding: 16px 14px;
        border-bottom: 1px solid #f0f0f0;
        font-size: 14px;
    }
    
    .comparison-table tbody tr:hover {
        background: #f8f9fa;
    }
    
    .status-mini {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 6px;
    }
    
    .status-mini.pending { background: #FFA726; }
    .status-mini.process { background: #42A5F5; }
    .status-mini.done { background: #66BB6A; }
    .status-mini.rejected { background: #EF5350; }
</style>

<div class="page-header" style="margin-bottom: 30px;">
    <h1 style="font-size: 28px; font-weight: 800; color: #1a1a1a; margin-bottom: 8px;">
        🗺️ Analytics per Kecamatan
    </h1>
    <p style="font-size: 15px; color: #666;">
        Analisis detail laporan berdasarkan kecamatan di Kota Padang
    </p>
</div>

<!-- Top Performers -->
<div class="top-performers">
    <div class="section-title">🏆 Top 5 Kecamatan</div>
    <div class="section-subtitle">Kecamatan dengan laporan terbanyak</div>
    
    @forelse($topDistricts as $index => $district)
    <div class="performer-item">
        <div class="rank-badge rank-{{ $index + 1 <= 3 ? $index + 1 : 'other' }}">
            #{{ $index + 1 }}
        </div>
        <div class="performer-info">
            <div class="performer-name">{{ $district->name }}</div>
            <div class="performer-desc">
                {{ $district->done_reports }} selesai dari {{ $district->total_reports }} laporan
            </div>
        </div>
        <div class="performer-value">
            {{ $district->total_reports }}
        </div>
    </div>
    @empty
    <div style="text-align: center; padding: 40px; color: #999;">
        Belum ada data
    </div>
    @endforelse
</div>

<!-- All Districts Cards -->
<div class="section-title" style="margin-bottom: 20px;">📍 Semua Kecamatan</div>
<div class="district-grid">
    @forelse($districtStats as $district)
    <div class="district-card">
        <div class="district-name">{{ $district->name }}</div>
        
        <div class="district-stats">
            <div class="district-stat">
                <div class="stat-label">Total</div>
                <div class="stat-value">{{ $district->total_reports }}</div>
            </div>
            <div class="district-stat">
                <div class="stat-label">Selesai</div>
                <div class="stat-value" style="color: #66BB6A;">{{ $district->done_reports }}</div>
            </div>
            <div class="district-stat">
                <div class="stat-label">Pending</div>
                <div class="stat-value" style="color: #FFA726;">{{ $district->pending_reports }}</div>
            </div>
            <div class="district-stat">
                <div class="stat-label">Proses</div>
                <div class="stat-value" style="color: #42A5F5;">{{ $district->process_reports }}</div>
            </div>
        </div>
        
        <div class="completion-bar">
            <div class="completion-label">
                <span style="font-weight: 600; color: #666;">Completion Rate</span>
                <span style="font-weight: 800; color: #2E7D32;">{{ $district->completion_rate }}%</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ $district->completion_rate }}%;"></div>
            </div>
        </div>
    </div>
    @empty
    <div style="grid-column: 1 / -1; text-align: center; padding: 60px; color: #999;">
        <i class="fas fa-map-marker-alt" style="font-size: 48px; opacity: 0.3; display: block; margin-bottom: 16px;"></i>
        Belum ada data kecamatan
    </div>
    @endforelse
</div>

<!-- Comparison Chart -->
<div class="section-card">
    <div class="section-title">📊 Perbandingan Kecamatan</div>
    <div class="section-subtitle">Grafik perbandingan antar kecamatan</div>
    <div class="chart-wrapper-district">
        <canvas id="districtComparisonChart"></canvas>
    </div>
</div>

<!-- Trend Over Time -->
<div class="section-card">
    <div class="section-title">📈 Trend 3 Bulan Terakhir</div>
    <div class="section-subtitle">Perkembangan laporan per kecamatan</div>
    <div class="chart-wrapper-trend">
        <canvas id="districtTrendChart"></canvas>
    </div>
</div>

<!-- Detailed Comparison Table -->
<div class="section-card">
    <div class="section-title">📋 Tabel Perbandingan Detail</div>
    <div class="section-subtitle">Statistik lengkap per kecamatan</div>
    
    <table class="comparison-table">
        <thead>
            <tr>
                <th>Kecamatan</th>
                <th>Total</th>
                <th>Pending</th>
                <th>Proses</th>
                <th>Selesai</th>
                <th>Ditolak</th>
                <th>Completion</th>
            </tr>
        </thead>
        <tbody>
            @forelse($districtStats->sortByDesc('total_reports') as $district)
            <tr>
                <td><strong>{{ $district->name }}</strong></td>
                <td>{{ $district->total_reports }}</td>
                <td>
                    <span class="status-mini pending"></span>
                    {{ $district->pending_reports }}
                </td>
                <td>
                    <span class="status-mini process"></span>
                    {{ $district->process_reports }}
                </td>
                <td>
                    <span class="status-mini done"></span>
                    {{ $district->done_reports }}
                </td>
                <td>
                    <span class="status-mini rejected"></span>
                    {{ $district->rejected_reports }}
                </td>
                <td>
                    <strong style="color: #2E7D32;">{{ $district->completion_rate }}%</strong>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 40px; color: #999;">
                    Belum ada data
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
    Chart.defaults.font.family = "'Inter', sans-serif";
    
    // District Comparison Chart
    const districtNames = {!! json_encode($districtStats->pluck('name')->toArray()) !!};
    const districtTotals = {!! json_encode($districtStats->pluck('total_reports')->toArray()) !!};
    const districtDone = {!! json_encode($districtStats->pluck('done_reports')->toArray()) !!};
    const districtPending = {!! json_encode($districtStats->pluck('pending_reports')->toArray()) !!};
    
    new Chart(document.getElementById('districtComparisonChart'), {
        type: 'bar',
        data: {
            labels: districtNames,
            datasets: [{
                label: 'Total Laporan',
                data: districtTotals,
                backgroundColor: '#2E7D32',
                borderRadius: 8
            }, {
                label: 'Selesai',
                data: districtDone,
                backgroundColor: '#66BB6A',
                borderRadius: 8
            }, {
                label: 'Pending',
                data: districtPending,
                backgroundColor: '#FFA726',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: { font: { size: 12, weight: '600' }, padding: 15, usePointStyle: true }
                }
            },
            scales: {
                y: { 
                    beginAtZero: true, 
                    ticks: { stepSize: 1 }, // Fixed step untuk tidak membesar
                    grid: { color: '#f0f0f0' } 
                },
                x: { grid: { display: false } }
            }
        }
    });
    
    // Trend Chart
    const monthLabels = {!! json_encode($months->map(fn($m) => $m->format('M Y'))->toArray()) !!};
    const trendData = {!! json_encode($trendData) !!};
    
    const trendDatasets = Object.keys(trendData).slice(0, 5).map((districtName, index) => {
        const colors = ['#2E7D32', '#00A86B', '#42A5F5', '#FFA726', '#66BB6A'];
        return {
            label: districtName,
            data: trendData[districtName],
            borderColor: colors[index],
            backgroundColor: colors[index] + '20',
            borderWidth: 3,
            fill: false,
            tension: 0.4
        };
    });
    
    new Chart(document.getElementById('districtTrendChart'), {
        type: 'line',
        data: {
            labels: monthLabels,
            datasets: trendDatasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: { font: { size: 12, weight: '600' }, padding: 15, usePointStyle: true }
                }
            },
            scales: {
                y: { 
                    beginAtZero: true, 
                    ticks: { stepSize: 1 }, // Fixed step untuk tidak membesar
                    grid: { color: '#f0f0f0' } 
                },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endpush
