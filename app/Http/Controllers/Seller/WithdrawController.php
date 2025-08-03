<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\WithdrawRequest;
use App\Models\ShopWallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{
    public function index()
    {
        $shop = Auth::user()->shop;
        $withdraws = WithdrawRequest::where('shop_id', $shop->id)->orderByDesc('created_at')->paginate(10);
        $wallet = $shop->wallet;

        return view('seller.wallet.withdraw', compact('withdraws', 'wallet'));
    }

    public function requestWithdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10000',
            'bank_account' => 'required|string',
        ]);

        $shop = Auth::user()->shop;
        $wallet = $shop->wallet;

        if ($wallet->balance < $request->amount) {
            return back()->with('error', 'Số dư không đủ để rút tiền.');
        }

        WithdrawRequest::create([
            'shop_id' => $shop->id,
            'amount' => $request->amount,
            'bank_account' => $request->bank_account,
        ]);

        $wallet->decrement('balance', $request->amount);

        return back()->with('success', 'Yêu cầu rút tiền đã được gửi.');
    }
}
