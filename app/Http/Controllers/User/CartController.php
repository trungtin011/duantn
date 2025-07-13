<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    // Hiển thị giỏ hàng
    public function index()
    {
        $user = Auth::user();
        $userID = Auth::check() ? Auth::id() : null;
        $sessionID = Session::getId();

        $cartItems = Cart::with(['product.shop', 'variant'])
            ->where(function ($query) use ($userID, $sessionID) {
                if ($userID) {
                    $query->where('userID', $userID);
                } else {
                    $query->where('session_id', $sessionID);
                }
            })
            ->get();

        return view('user.cart', compact('cartItems', 'user'));
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
        $stock = $product->stock_total;
        $price = $product->sale_price ?? $product->price;

        if ($request->variant_id) {
            $variant = ProductVariant::findOrFail($request->variant_id);
            $stock = $variant->stock ?? $stock; // Sử dụng stock của variant nếu có
            $price = $variant->sale_price ?? $price;
        }

        if ($request->quantity > $stock) {
            return response()->json([
                'message' => 'Số lượng vượt quá tồn kho!',
                'available' => $stock,
            ], 422);
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
            $newQuantity = $existingCartItem->quantity + $quantity;
            if ($newQuantity > $stock) {
                return response()->json([
                    'message' => 'Số lượng vượt quá tồn kho sau khi cộng thêm!',
                    'available' => $stock,
                ], 422);
            }
            $existingCartItem->quantity = $newQuantity;
            $existingCartItem->total_price = $newQuantity * $price;
            $existingCartItem->save();
        } else {
            Cart::create([
                'userID' => $userID,
                'productID' => $product->id,
                'variantID' => $request->variant_id,
                'quantity' => $quantity,
                'price' => $price,
                'total_price' => $total,
                'session_id' => $sessionID,
                'buying_flag' => false
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

        $cartItem = Cart::with(['product', 'variant'])->where('id', $id)
            ->where(function ($query) use ($userID, $sessionID) {
                if ($userID) {
                    $query->where('userID', $userID);
                } else {
                    $query->where('session_id', $sessionID);
                }
            })
            ->firstOrFail();

        $stock = $cartItem->variant ? $cartItem->variant->stock : $cartItem->product->stock_total;

        if ($request->quantity > $stock) {
            return response()->json([
                'message' => 'Số lượng vượt quá tồn kho hiện tại!',
                'available' => $stock,
            ], 422);
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->total_price = $cartItem->quantity * $cartItem->price;
        $cartItem->save();

        return response()->json([
            'message' => 'Đã cập nhật số lượng!',
            'total_price' => number_format($cartItem->total_price, 0, ',', '.'),
        ]);
    }

    public function addMultiToCart(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.variant_id' => 'required|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $results = [];
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            $variant = \App\Models\ProductVariant::find($item['variant_id']);
            $stock = $variant ? $variant->stock : ($product ? $product->stock_total : 0);
            $price = $variant ? ($variant->sale_price ?? $variant->price) : ($product ? ($product->sale_price ?? $product->price) : 0);

            if (!$product || !$variant) {
                $results[] = [
                    'variant_id' => $item['variant_id'],
                    'status' => 'error',
                    'message' => 'Sản phẩm hoặc biến thể không tồn tại.'
                ];
                continue;
            }

            if ($item['quantity'] > $stock) {
                $results[] = [
                    'variant_id' => $item['variant_id'],
                    'status' => 'error',
                    'message' => 'Số lượng vượt quá tồn kho!',
                    'available' => $stock
                ];
                continue;
            }

            $userID = Auth::check() ? Auth::id() : null;
            $sessionID = Session::getId();
            $quantity = $item['quantity'];
            $total = $price * $quantity;

            $cartQuery = Cart::where('productID', $product->id)
                ->where('variantID', $variant->id)
                ->where(function ($query) use ($userID, $sessionID) {
                    if ($userID) {
                        $query->where('userID', $userID);
                    } else {
                        $query->where('session_id', $sessionID);
                    }
                });

            $existingCartItem = $cartQuery->first();

            if ($existingCartItem) {
                $newQuantity = $existingCartItem->quantity + $quantity;
                if ($newQuantity > $stock) {
                    $results[] = [
                        'variant_id' => $item['variant_id'],
                        'status' => 'error',
                        'message' => 'Số lượng vượt quá tồn kho sau khi cộng thêm!',
                        'available' => $stock
                    ];
                    continue;
                }
                $existingCartItem->quantity = $newQuantity;
                $existingCartItem->total_price = $newQuantity * $price;
                $existingCartItem->save();
            } else {
                Cart::create([
                    'userID' => $userID,
                    'productID' => $product->id,
                    'variantID' => $variant->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total_price' => $total,
                    'session_id' => $sessionID,
                    'buying_flag' => false
                ]);
            }

            $results[] = [
                'variant_id' => $item['variant_id'],
                'status' => 'success',
                'message' => 'Đã thêm vào giỏ hàng!'
            ];
        }

        // Nếu tất cả thành công
        if (collect($results)->every(fn($r) => $r['status'] === 'success')) {
            return response()->json(['message' => 'Đã thêm vào giỏ hàng!', 'results' => $results], 200);
        }
        // Nếu có lỗi
        return response()->json(['message' => 'Một số sản phẩm không thể thêm vào giỏ hàng!', 'results' => $results], 422);
    }

    public function updateSelectedProducts(Request $request)
    {
        $selectedIds = $request->input('selected');
        session(['selected_products' => $selectedIds]);
        return response()->json(['message' => 'Đã cập nhật sản phẩm đã chọn!']);
    }
}
