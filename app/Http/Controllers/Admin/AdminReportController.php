<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
    public function index()
    {
        $reports = Report::all();
        return view('admin.reports.index' , compact('reports'));
    }

    public function show($id)
    {
        $report = Report::with(['reporter', 'reportedUser', 'product', 'shop', 'order', 'resolvedBy'])->findOrFail($id);
        return view('admin.reports.show', compact('report'));
    }

    public function destroy(Report $report)
    {
        $report->delete();
        return redirect()->back();
    }

    public function updateStatus(Request $request, $id)
    {
        $report = Report::findOrFail($id);
        $status = $request->input('status');

        $report->status = $status;

        if (!$report->resolved_by && in_array($status, ['under_review', 'processing', 'resolved', 'rejected'])) {
            $report->resolved_by = Auth::id();
        }

        $statusToResolution = [
            'resolved' => 'accepted',
            'rejected' => 'rejected',
            'under_review' => null,
            'processing' => null,
            'pending' => null,
        ];

        if (in_array($status, ['resolved', 'rejected'])) {
            $report->resolved_at = now();
            $report->resolution = $statusToResolution[$status];
        }

        $report->save();

        return back()->with('success', 'Cập nhật trạng thái thành công');
    }

}
