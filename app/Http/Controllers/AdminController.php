<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReportResource;
use App\Models\Category;
use App\Models\Report;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    /**
     * GET /api/v1/admin/dashboard
     * Aggregate statistics for the admin panel.
     */
    public function dashboard(Request $request = null): JsonResponse
    {
        $statistics = [
            'total_reports' => (int) Report::count(),
            'new_reports' => (int) Report::where('status', Report::STATUS_PENDING)->count(),
            'in_progress' => (int) Report::where('status', Report::STATUS_PROCESS)->count(),
            'completed' => (int) Report::where('status', Report::STATUS_DONE)->count(),
            'rejected' => (int) Report::where('status', Report::STATUS_REJECTED)->count(),
        ];

        $byCategory = Category::query()
            ->withCount('reports')
            ->orderByDesc('reports_count')
            ->get()
            ->map(fn ($c) => ['category' => $c->name, 'count' => (int) $c->reports_count])
            ->toArray();

        $recentReports = Report::query()
            ->with(['user', 'category', 'images'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // ReportResource already includes `user` as a proper object
        // (id, name, email, phone) via its `whenLoaded('user')` mapping, so
        // we must NOT overwrite it with the plain name string — doing so
        // previously broke the Flutter `ReportModel` parser.
        $recent = $recentReports->map(
            fn (Report $report) => (new ReportResource($report))->toArray($request)
        );

        return response()->json([
            'success' => true,
            'data' => [
                'statistics' => $statistics,
                'by_category' => $byCategory,
                'recent_reports' => $recent,
            ],
        ]);
    }

    /**
     * PUT /api/v1/admin/reports/{id}/status
     * Update the status (and optional notes) of a report.
     */
    public function updateStatus(Request $request, string $id): JsonResponse
    {
        $report = Report::find($id);

        if (! $report) {
            return response()->json([
                'success' => false,
                'error' => ['code' => 'REPORT_001', 'message' => 'Laporan tidak ditemukan.'],
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => ['required', 'in:pending,process,done,rejected'],
            'notes' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        $report->update([
            'status' => $request->status,
            'admin_note' => $request->notes ?? $report->admin_note,
        ]);

        $report->load(['user', 'category', 'images']);

        return response()->json([
            'success' => true,
            'message' => 'Status laporan diperbarui.',
            'data' => (new ReportResource($report))->toArray($request),
        ]);
    }
}
