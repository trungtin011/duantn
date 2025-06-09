<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    // Hiển thị giỏ hàng
    public function index()
    {
        $userID = Auth::check() ? Auth::id() : null;
        $sessionID = Session::getId();

        $cartItems = Cart::with(['product', 'variant'])
            ->where(function ($query) use ($userID, $sessionID) {
                if ($userID) {
                    $query->where('userID', $userID);
                } else {
                    $query->where('session_id', $sessionID);
                }
            })
            ->get();

        return view('user.cart', compact('cartItems'));
    }

    // Thêm sản phẩm vào giỏ hàng
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $price = $product->sale_price ?? $product->price;

        if ($request->variant_id) {
            $variant = ProductVariant::findOrFail($request->variant_id);
            $price = $variant->sale_price ?? $price;
        }

        $userID = Auth::check() ? Auth::id() : null;
        $sessionID = Session::getId();
        $quantity = $request->quantity;
        $total = $price * $quantity;

        $cartQuery = Cart::where('productID', $product->id)
            ->where('variantID', $request->variant_id)
            ->where(function ($query) use ($userID, $sessionID) {
                if ($userID) {
                    $query->where('userID', $userID);
                } else {
                    $query->where('session_id', $sessionID);
                }
            });

        $existingCartItem = $cartQuery->first();

        if ($existingCartItem) {
            $existingCartItem->quantity += $quantity;
            $existingCartItem->total_price = $existingCartItem->quantity * $price;
            $existingCartItem->save();
        } else {
            Cart::create([
                'userID' => $userID,
                'productID' => $product->id,
                'variantID' => $request->variant_id,
                'quantity' => $quantity,
                'price' => $price,
                'total_price' => $total,
                'session_id' => $userID ? null : $sessionID,
            ]);
        }

        return response()->json(['message' => 'Đã thêm vào giỏ hàng!'], 200);
    }

    // Xóa sản phẩm khỏi giỏ hàng
    public function remove($id)
    {
        $userID = Auth::check() ? Auth::id() : null;
        $sessionID = Session::getId();

        $cartItem = Cart::where('id', $id)
            ->where(function ($query) use ($userID, $sessionID) {
                if ($userID) {
                    $query->where('userID', $userID);
                } else {
                    $query->where('session_id', $sessionID);
                }
            })
            ->firstOrFail();

        $cartItem->delete();

        return response()->json(['message' => 'Sản phẩm đã được xóa khỏi giỏ hàng!'], 200);
    }

    // Cập nhật số lượng sản phẩm
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $userID = Auth::check() ? Auth::id() : null;
        $sessionID = Session::getId();

        $cartItem = Cart::where('id', $id)
            ->where(function ($query) use ($userID, $sessionID) {
                if ($userID) {
                    $query->where('userID', $userID);
                } else {
                    $query->where('session_id', $sessionID);
                }
            })
            ->firstOrFail();

        $cartItem->quantity = $request->quantity;
        $cartItem->total_price = $cartItem->quantity * $cartItem->price;
        $cartItem->save();

        return response()->json([
            'message' => 'Số lượng đã được cập nhật!',
            'total_price' => number_format($cartItem->total_price, 0, ',', '.'),
        ], 200);
    }
}
?>