<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
    public function index()
    {
        $reports = Report::all();
        return view('admin.reports.index' , compact('reports'));
    }

    public function destroy(Report $report)
    {
        $report->delete();
        return redirect()->back();
    }
}
