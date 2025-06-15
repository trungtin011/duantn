<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function create()
    {
        return view('user.report');
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
                $path = $file->store('evidence', 'public');
                $evidences[] = $path;
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
            'evidence' => json_encode($evidences),
        ]);

        return redirect()->route('user.report')->with('success', 'Báo cáo đã được gửi.');
    }
}
