<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Thêm trait này
use Illuminate\Support\Facades\Log; // Thêm thư viện 

class AdminReportController extends Controller
{
    use AuthorizesRequests; // Sử dụng trait

    /**
     * Hiển thị danh sách báo cáo với tìm kiếm và lọc.
     */
    public function index(Request $request)
    {
        $query = Report::query();

        // Tìm kiếm
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhereHas('product', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('reporter', function ($q) use ($search) {
                        $q->where('fullname', 'like', "%{$search}%");
                    });
            });
        }

        // Lọc theo trạng thái
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $reports = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.reports.index', compact('reports'));
    }

    /**
     * Hiển thị chi tiết báo cáo.
     */
    public function show(Report $report)
    {
        return view('admin.reports.show', compact('report'));
    }

    /**
     * Cập nhật trạng thái báo cáo.
     */
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
