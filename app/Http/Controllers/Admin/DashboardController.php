<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Stats untuk cards
            $stats = [
                'total_reports' => Report::count(),
                'pending_reports' => Report::where('status', 'pending')->count(),
                'process_reports' => Report::where('status', 'process')->count(),
                'done_reports' => Report::where('status', 'done')->count(),
                'rejected_reports' => Report::where('status', 'rejected')->count(),
                'total_users' => User::where('role', 'user')->count(),
                'total_categories' => Category::count(),
                'reports_today' => Report::whereDate('created_at', Carbon::today())->count(),
                'reports_this_week' => Report::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(),
                'reports_this_month' => Report::whereMonth('created_at', Carbon::now()->month)->count(),
            ];
            
            // Completion rate
            $completedCount = Report::where('status', 'done')->count();
            $totalReports = Report::count();
            $stats['completion_rate'] = $totalReports > 0 ? round(($completedCount / $totalReports) * 100, 1) : 0;
            
            // Average response time (dalam hari)
            $avgResponseTime = Report::where('status', 'done')
                ->whereNotNull('updated_at')
                ->get()
                ->avg(function($report) {
                    return $report->created_at->diffInDays($report->updated_at);
                });
            $stats['avg_response_days'] = round($avgResponseTime ?? 0, 1);
            
            // Data untuk chart kategori - fallback jika tidak ada data
            $categoryStats = Category::leftJoin('reports', 'categories.id', '=', 'reports.category_id')
                ->select('categories.name as category', 'categories.color', DB::raw('count(reports.id) as total'))
                ->groupBy('categories.id', 'categories.name', 'categories.color')
                ->orderBy('total', 'desc')
                ->get();
            
            $categoryData = [
                'labels' => $categoryStats->pluck('category')->toArray(),
                'values' => $categoryStats->pluck('total')->toArray(),
                'colors' => $categoryStats->pluck('color')->toArray(),
            ];
            
            // Jika tidak ada data kategori, beri default
            if (empty($categoryData['labels'])) {
                $categoryData = [
                    'labels' => ['Infrastruktur', 'Kebersihan', 'Penerangan'],
                    'values' => [0, 0, 0],
                    'colors' => ['#2E7D32', '#00A86B', '#81C784'],
                ];
            }
            
            // Data untuk trend chart (30 hari terakhir)
            $trendStats = Report::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('count(*) as total')
                )
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get();
            
            // Fill missing dates dengan 0
            $dates = collect();
            for ($i = 29; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i)->format('Y-m-d');
                $dates->push($date);
            }
            
            $trendData = [
                'labels' => $dates->map(function($date) {
                    return Carbon::parse($date)->format('d M');
                })->toArray(),
                'values' => $dates->map(function($date) use ($trendStats) {
                    $stat = $trendStats->firstWhere('date', $date);
                    return $stat ? $stat->total : 0;
                })->toArray(),
            ];
            
            // Status distribution untuk pie chart
            $statusData = [
                'labels' => ['Pending', 'Diproses', 'Selesai', 'Ditolak'],
                'values' => [
                    $stats['pending_reports'],
                    $stats['process_reports'],
                    $stats['done_reports'],
                    $stats['rejected_reports']
                ],
                'colors' => ['#FFA726', '#42A5F5', '#66BB6A', '#EF5350']
            ];
            
            // Recent reports
            $recentReports = Report::with(['user', 'category'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
            
            // Top reporters
            $topReporters = User::where('role', 'user')
                ->withCount('reports')
                ->orderBy('reports_count', 'desc')
                ->limit(5)
                ->get();
            
            // Pending count untuk badge di sidebar
            $pendingCount = $stats['pending_reports'];
            
            return view('admin.dashboard', compact(
                'stats',
                'categoryData',
                'trendData',
                'statusData',
                'recentReports',
                'topReporters',
                'pendingCount'
            ));
        } catch (\Exception $e) {
            // Jika error, tampilkan pesan error
            return view('admin.dashboard-error', ['error' => $e->getMessage()]);
        }
    }
}
