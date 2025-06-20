<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PointTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Carbon\CarbonPeriod;

class CheckinController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();

        $checkinToday = PointTransaction::where('userID', $user->id)
            ->where('type', 'checkin')
            ->whereDate('created_at', $today)
            ->exists();

        $checkins = PointTransaction::where('userID', $user->id)
            ->where('type', 'checkin')
            ->orderByDesc('created_at')
            ->paginate(10);

        $monday = Carbon::now()->startOfWeek(); // Thứ 2
        $saturday = Carbon::now()->startOfWeek()->addDays(5); // Thứ 7
        $weekDates = collect(CarbonPeriod::create($monday, $saturday))
            ->map(fn($date) => $date->copy());

        // Danh sách ngày đã điểm danh
        $checkinDates = $checkins->pluck('created_at')->map->format('Y-m-d')->toArray();

        return view('user.account.points.checkin', compact('checkinToday', 'checkins', 'weekDates', 'checkinDates', 'user'));
    }

    public function store()
    {
        $user = Auth::user();
        $today = Carbon::today();

        $alreadyCheckedIn = PointTransaction::where('userID', $user->id)
            ->where('type', 'checkin')
            ->whereDate('created_at', $today)
            ->exists();

        if ($alreadyCheckedIn) {
            return back()->with('error', 'Bạn đã điểm danh hôm nay rồi!');
        }

        PointTransaction::create([
            'userID' => $user->id,
            'points' => 100,
            'type' => 'checkin',
            'description' => 'Điểm danh hàng ngày',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Điểm danh thành công! Bạn nhận được 10 điểm.');
    }
}
