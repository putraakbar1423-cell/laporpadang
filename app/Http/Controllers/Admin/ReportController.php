<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::with(['user', 'category']);
        
        // Filter by search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }
        
        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }
        
        $reports = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.reports.index', compact('reports'));
    }
    
    public function show($id)
    {
        $report = Report::with(['user', 'category', 'comments.user'])
            ->findOrFail($id);
        
        return view('admin.reports.show', compact('report'));
    }
    
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,process,done,rejected',
            'admin_note' => 'nullable|string|max:1000',
        ]);
        
        $report = Report::findOrFail($id);
        
        // Update status dan admin note
        $report->status = $request->status;
        $report->admin_note = $request->admin_note;
        $report->save();
        
        return redirect()->back()->with('success', 'Status dan catatan laporan berhasil diperbarui.');
    }
    
    public function destroy($id)
    {
        $report = Report::findOrFail($id);
        $report->delete();
        
        return redirect()->route('admin.reports.index')
            ->with('success', 'Laporan berhasil dihapus.');
    }
}
