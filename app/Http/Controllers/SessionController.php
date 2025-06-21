<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SessionController extends Controller
{
    public function updateSession(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        if (isset($data['user_id'])) {
            DB::table('sessions')
                ->where('user_id', $data['user_id'])
                ->update(['last_activity' => time()]);
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'error'], 400);
    }
}