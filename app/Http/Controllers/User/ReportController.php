<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Report::where('reporter_id', Auth::id());

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhereHas('product', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $reports = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('user.report.index', compact('reports', 'user')); // Sử dụng view 'report.index'
    }

    /**
     * Hiển thị chi tiết báo cáo.
     */
    public function show(Report $report)
    {
        $user = Auth::user();
        return view('user.report.show', compact('report', 'user'));
    }

    public function create()
    {
        $user = Auth::user();
        return view('user.report', compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'report_type' => 'required',
            'report_content' => 'required|string',
            'priority' => 'in:low,medium,high,urgent',
            'evidence.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        $evidences = [];

        if ($request->hasFile('evidence')) {
            foreach ($request->file('evidence') as $file) {
                $path = $file->store('evidence', 'public'); // Should store in storage/app/public/evidence/
                if ($path) {
                    $evidences[] = $path;
                    Log::info('File stored at: ' . $path); // Log the path for debugging
                } else {
                    Log::error('Failed to store file: ' . $file->getClientOriginalName());
                }
            }
        }

        Report::create([
            'reporter_id' => Auth::id(),
            'product_id' => $request->product_id,
            'shop_id' => $request->shop_id,
            'order_id' => $request->order_id,
            'user_id' => $request->user_id,
            'report_type' => $request->report_type,
            'report_content' => $request->report_content,
            'priority' => $request->priority ?? 'medium',
            'is_anonymous' => $request->has('is_anonymous'),
            'evidence' => json_encode($evidences), // Chỉ encode một lần
        ]);

        return redirect()->route('report.index')->with('success', 'Báo cáo đã được gửi.');
    }
}
