<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use App\Models\PlatformRevenueModel;
use App\Helpers\DashboardHelper;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $dashboardService = new DashboardService();
        
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        $data = $dashboardService->getDashboardData($startDate, $endDate);

        return view('admin.dashboard', $data);
    }


}
