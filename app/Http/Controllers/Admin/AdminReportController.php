<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminReportController extends Controller
{
    public function index()
    {
        $reports = Report::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.reports.index', compact('reports'));
    }

    public function show(Report $report)
    {
        return view('admin.reports.show', compact('report'));
    }

    public function updateStatus(Request $request, Report $report)
    {
        $request->validate([
            'status' => 'required|in:pending,under_review,processing,resolved,rejected',
            'resolution_note' => 'nullable|string|max:1000',
        ]);

        $report->status = $request->status;
        if ($request->status == 'resolved' || $request->status == 'rejected') {
            $report->resolved_by = Auth::id();
            $report->resolved_at = now();
        } else {
            $report->resolved_by = null;
            $report->resolved_at = null;
        }
        $report->resolution_note = $request->resolution_note;
        $report->save();

        return redirect()->back()->with('success', 'Trạng thái báo cáo đã được cập nhật.');
    }
} 