<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\District;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function districts()
    {
        // Get all districts dengan jumlah laporan
        $districts = District::withCount('reports')
            ->orderBy('reports_count', 'desc')
            ->get();
        
        // Stats per district dengan detail status
        $districtStats = District::select('districts.*')
            ->withCount([
                'reports as total_reports',
                'reports as pending_reports' => function($q) {
                    $q->where('status', 'pending');
                },
                'reports as process_reports' => function($q) {
                    $q->where('status', 'process');
                },
                'reports as done_reports' => function($q) {
                    $q->where('status', 'done');
                },
                'reports as rejected_reports' => function($q) {
                    $q->where('status', 'rejected');
                }
            ])
            ->get()
            ->map(function($district) {
                $total = $district->total_reports;
                $district->completion_rate = $total > 0 ? round(($district->done_reports / $total) * 100, 1) : 0;
                return $district;
            });
        
        // Top 5 districts dengan laporan terbanyak
        $topDistricts = $districtStats->sortByDesc('total_reports')->take(5);
        
        // Districts dengan completion rate tertinggi
        $bestPerformingDistricts = $districtStats->sortByDesc('completion_rate')->take(5);
        
        // Data untuk map chart (bubble map)
        $mapData = $districtStats->map(function($district) {
            return [
                'name' => $district->name,
                'total' => $district->total_reports,
                'completion_rate' => $district->completion_rate,
            ];
        });
        
        // Trend per district (3 bulan terakhir)
        $trendData = [];
        $months = collect();
        for ($i = 2; $i >= 0; $i--) {
            $months->push(Carbon::now()->subMonths($i));
        }
        
        foreach ($districts as $district) {
            $trendData[$district->name] = $months->map(function($month) use ($district) {
                return Report::where('district_id', $district->id)
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count();
            })->toArray();
        }
        
        // Category breakdown per district
        $categoryBreakdown = District::select('districts.name as district_name', 'categories.name as category_name', DB::raw('count(reports.id) as total'))
            ->join('reports', 'districts.id', '=', 'reports.district_id')
            ->join('categories', 'reports.category_id', '=', 'categories.id')
            ->groupBy('districts.id', 'districts.name', 'categories.id', 'categories.name')
            ->get()
            ->groupBy('district_name');
        
        return view('admin.analytics.districts', compact(
            'districts',
            'districtStats',
            'topDistricts',
            'bestPerformingDistricts',
            'mapData',
            'trendData',
            'months',
            'categoryBreakdown'
        ));
    }
}
