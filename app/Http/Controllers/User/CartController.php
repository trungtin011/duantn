<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Combo;
use App\Models\ComboProduct;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    /**
     * Display the user's cart.
     */
    public function index()
    {
        
        $user = Auth::user();
        $userID = Auth::check() ? Auth::id() : null;
        $sessionID = Session::getId();

        $cartItems = Cart::with(['product.shop', 'variant', 'combo.products.product', 'combo.products.variant'])
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


    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $stock = $product->stock_total ?? 0;
        $price = $product->sale_price ?? $product->price;

        if ($request->variant_id) {
            $variant = ProductVariant::findOrFail($request->variant_id);
            $stock = $variant->stock ?? $stock;
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
            ->whereNull('combo_id')
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
                'buying_flag' => false,
                'combo_id' => null,
            ]);
        }

        return response()->json(['message' => 'Đã thêm vào giỏ hàng!'], 200);
    }

    /**
     * Calculate discounted price for combo products
     */
    private function calculateComboDiscountedPrice($combo, $basePrice, $comboProduct)
    {
        $discountedPrice = $basePrice;
        
        if ($combo->discount_type === 'percentage' && $combo->discount_value > 0) {
            // Giảm giá theo phần trăm
            $discountMultiplier = 1 - ($combo->discount_value / 100);
            $discountedPrice = $basePrice * $discountMultiplier;
        } elseif ($combo->discount_type === 'fixed' && $combo->discount_value > 0) {
            // Giảm giá cố định - phân bổ theo tỷ lệ giá sản phẩm trong combo
            $totalComboBasePrice = 0;
            foreach ($combo->products as $cp) {
                $productPrice = $cp->variant ? ($cp->variant->sale_price ?? $cp->variant->price) : ($cp->product->sale_price ?? $cp->product->price);
                $totalComboBasePrice += $productPrice * $cp->quantity;
            }
            
            if ($totalComboBasePrice > 0) {
                // Tính tỷ lệ giá của sản phẩm này trong combo
                $productBasePrice = $basePrice * $comboProduct->quantity;
                $discountRatio = $productBasePrice / $totalComboBasePrice;
                $productDiscount = $combo->discount_value * $discountRatio;
                $discountedPrice = $basePrice - ($productDiscount / $comboProduct->quantity);
            } else {
                // Fallback: chia đều giảm giá cho tất cả sản phẩm
                $discountedPrice = $basePrice - ($combo->discount_value / count($combo->products));
            }
        }
        
        // Đảm bảo giá không âm
        return max(0, $discountedPrice);
    }

    public function addComboToCart(Request $request)
{
    // Kiểm tra xem người dùng đã đăng nhập chưa
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để thêm combo vào giỏ hàng!');
    }

    $request->validate([
        'combo_id' => 'required|exists:combo,id',
        'quantity' => 'required|integer|min:1',
    ]);

    $combo = Combo::with(['products.product', 'products.variant'])->findOrFail($request->combo_id);
    $userID = Auth::check() ? Auth::id() : null;
    $sessionID = Session::getId();
    $quantity = $request->quantity;

    // Calculate minimum stock across all combo products
    $minStock = PHP_INT_MAX;
    foreach ($combo->products as $comboProduct) {
        $product = $comboProduct->product;
        if (!$product) {
            Log::error('Product not found for combo product', ['combo_id' => $combo->id, 'combo_product_id' => $comboProduct->id]);
            return response()->json(['message' => 'Sản phẩm trong combo không tồn tại!'], 422);
        }
        $variant = $comboProduct->variant;
        $stock = $variant ? ($variant->stock ?? 0) : ($product->stock_total ?? 0);
        $availableStock = floor($stock / $comboProduct->quantity);
        $minStock = min($minStock, $availableStock);
        Log::info('Combo stock calculation', [
            'product_id' => $product->id,
            'variant_id' => $variant ? $variant->id : null,
            'stock' => $stock,
            'required_per_combo' => $comboProduct->quantity,
            'available_combo_units' => $availableStock,
        ]);
    }

    if ($quantity > $minStock) {
        return response()->json([
            'message' => 'Số lượng combo vượt quá tồn kho hiện tại!',
            'available' => $minStock,
        ], 422);
    }

    $results = [];
    foreach ($combo->products as $comboProduct) {
        $product = $comboProduct->product;
        $variant = $comboProduct->variant;
        $productQuantity = $comboProduct->quantity * $quantity;
        $stock = $variant ? ($variant->stock ?? 0) : ($product->stock_total ?? 0);
        $basePrice = $variant ? ($variant->sale_price ?? $variant->price) : ($product->sale_price ?? $product->price);
        
        // Tính giá sau khi áp dụng giảm giá combo
        $discountedPrice = $this->calculateComboDiscountedPrice($combo, $basePrice, $comboProduct);

        $totalPrice = $discountedPrice * $productQuantity;

        if ($productQuantity > $stock) {
            $results[] = [
                'product_id' => $product->id,
                'variant_id' => $variant ? $variant->id : null,
                'status' => 'error',
                'message' => 'Số lượng sản phẩm vượt quá tồn kho!',
                'available' => $stock,
            ];
            continue;
        }

        $cartQuery = Cart::where('productID', $product->id)
            ->where('variantID', $variant ? $variant->id : null)
            ->where('combo_id', $combo->id)
            ->where(function ($query) use ($userID, $sessionID) {
                if ($userID) {
                    $query->where('userID', $userID);
                } else {
                    $query->where('session_id', $sessionID);
                }
            });

        $existingCartItem = $cartQuery->first();

        if ($existingCartItem) {
            $newQuantity = $existingCartItem->quantity + $productQuantity;
            if ($newQuantity > $stock) {
                $results[] = [
                    'product_id' => $product->id,
                    'variant_id' => $variant ? $variant->id : null,
                    'status' => 'error',
                    'message' => 'Số lượng sản phẩm vượt quá tồn kho sau khi cộng thêm!',
                    'available' => $stock,
                ];
                continue;
            }
            $existingCartItem->quantity = $newQuantity;
            $existingCartItem->total_price = $newQuantity * $discountedPrice;
            if (!$existingCartItem->save()) {
                Log::error('Failed to save existing cart item', ['cart_id' => $existingCartItem->id, 'combo_id' => $combo->id]);
                $results[] = [
                    'product_id' => $product->id,
                    'variant_id' => $variant ? $variant->id : null,
                    'status' => 'error',
                    'message' => 'Lỗi khi cập nhật số lượng sản phẩm!',
                ];
                continue;
            }
        } else {
            $cartData = [
                'userID' => $userID,
                'productID' => $product->id,
                'variantID' => $variant ? $variant->id : null,
                'quantity' => $productQuantity,
                'price' => $discountedPrice,
                'total_price' => $totalPrice,
                'session_id' => $sessionID,
                'buying_flag' => false,
                'combo_id' => $combo->id,
            ];
            Log::info('Creating cart item', $cartData);
            $cartItem = Cart::create($cartData);
            if (!$cartItem) {
                Log::error('Failed to create new cart item', $cartData);
                $results[] = [
                    'product_id' => $product->id,
                    'variant_id' => $variant ? $variant->id : null,
                    'status' => 'error',
                    'message' => 'Lỗi khi thêm sản phẩm vào giỏ hàng!',
                ];
                continue;
            }
        }

        $results[] = [
            'product_id' => $product->id,
            'variant_id' => $variant ? $variant->id : null,
            'status' => 'success',
            'message' => 'Đã thêm sản phẩm vào giỏ hàng!',
        ];
    }

    if (collect($results)->every(fn($r) => $r['status'] === 'success')) {
        return redirect()->route('cart')->with('success', 'Đã thêm combo vào giỏ hàng!');
    }

    // Rollback if any product fails to add
    Cart::where('combo_id', $combo->id)
        ->where(function ($query) use ($userID, $sessionID) {
            if ($userID) {
                $query->where('userID', $userID);
            } else {
                $query->where('session_id', $sessionID);
            }
        })
        ->delete();

    return response()->json(['message' => 'Một số sản phẩm không thể thêm vào giỏ hàng!', 'results' => $results], 422);
}

   
    public function remove($id)
    {
        $userID = Auth::check() ? Auth::id() : null;
        $sessionID = Session::getId();

        $cartItem = Cart::with('combo')
            ->where('id', $id)
            ->where(function ($query) use ($userID, $sessionID) {
                if ($userID) {
                    $query->where('userID', $userID);
                } else {
                    $query->where('session_id', $sessionID);
                }
            })
            ->firstOrFail();

        try {
            // If part of a combo, delete all items with the same combo_id
            if ($cartItem->combo_id) {
                Cart::where('combo_id', $cartItem->combo_id)
                    ->where(function ($query) use ($userID, $sessionID) {
                        if ($userID) {
                            $query->where('userID', $userID);
                        } else {
                            $query->where('session_id', $sessionID);
                        }
                    })
                    ->delete();
                return response()->json(['message' => 'Đã xóa toàn bộ combo khỏi giỏ hàng!'], 200);
            } else {
                $cartItem->delete();
                return response()->json(['message' => 'Sản phẩm đã được xóa khỏi giỏ hàng!'], 200);
            }
        } catch (\Exception $e) {
            Log::error('Error removing cart item: ' . $e->getMessage(), ['cart_id' => $id]);
            return response()->json(['message' => 'Có lỗi xảy ra khi xóa sản phẩm!'], 500);
        }
    }

    /**
     * Update quantity of a cart item and synchronize combo items.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $userID = Auth::check() ? Auth::id() : null;
        $sessionID = Session::getId();

        $cartItem = Cart::with(['product', 'variant', 'combo.products.product', 'combo.products.variant'])
            ->where('id', $id)
            ->where(function ($query) use ($userID, $sessionID) {
                if ($userID) {
                    $query->where('userID', $userID);
                } else {
                    $query->where('session_id', $sessionID);
                }
            })
            ->firstOrFail();

        try {
            Log::info('Updating cart item', ['cart_id' => $id, 'requested_quantity' => $request->quantity]);

            if ($cartItem->combo_id) {
                $combo = $cartItem->combo;
                if (!$combo) {
                    Log::error('Combo not found for cart item', ['cart_id' => $id, 'combo_id' => $cartItem->combo_id]);
                    return response()->json(['message' => 'Combo không tồn tại!'], 404);
                }

                // Calculate the combo quantity multiplier
                $currentComboProduct = $combo->products->firstWhere('productID', $cartItem->productID);
                if (!$currentComboProduct || ($cartItem->variantID && $currentComboProduct->variantID != $cartItem->variantID)) {
                    Log::error('Combo product mismatch', ['cart_id' => $id, 'product_id' => $cartItem->productID, 'variant_id' => $cartItem->variantID]);
                    return response()->json(['message' => 'Sản phẩm không thuộc combo!'], 422);
                }

                $comboQuantity = $request->quantity / $currentComboProduct->quantity; // Requested combo units
                if (floor($comboQuantity) != $comboQuantity) {
                    Log::warning('Invalid combo quantity', ['cart_id' => $id, 'requested_quantity' => $request->quantity, 'combo_product_quantity' => $currentComboProduct->quantity]);
                    return response()->json([
                        'message' => 'Số lượng phải là bội số của ' . $currentComboProduct->quantity . ' cho sản phẩm này trong combo!',
                    ], 422);
                }

                // Calculate minimum stock for the combo
                $minStock = PHP_INT_MAX;
                foreach ($combo->products as $comboProduct) {
                    $stock = $comboProduct->variant ? ($comboProduct->variant->stock ?? 0) : ($comboProduct->product->stock_total ?? 0);
                    $availableStock = floor($stock / $comboProduct->quantity);
                    $minStock = min($minStock, $availableStock);
                    Log::info('Combo product stock check', [
                        'product_id' => $comboProduct->productID,
                        'variant_id' => $comboProduct->variantID,
                        'stock' => $stock,
                        'required_per_combo' => $comboProduct->quantity,
                        'available_combo_units' => $availableStock,
                    ]);
                }

                if ($comboQuantity > $minStock) {
                    return response()->json([
                        'message' => 'Số lượng combo vượt quá tồn kho hiện tại!',
                        'available' => $minStock,
                    ], 422);
                }

                // Calculate discount multiplier
                $discountMultiplier = $combo->discount_type === 'percentage' && $combo->discount_value > 0
                    ? (1 - $combo->discount_value / 100)
                    : 1;

                // Update all cart items with the same combo_id
                $cartItems = Cart::with(['product', 'variant'])
                    ->where('combo_id', $combo->id)
                    ->where(function ($query) use ($userID, $sessionID) {
                        if ($userID) {
                            $query->where('userID', $userID);
                        } else {
                            $query->where('session_id', $sessionID);
                        }
                    })
                    ->get();

                foreach ($cartItems as $item) {
                    $comboProduct = $combo->products->firstWhere('productID', $item->productID);
                    if ($comboProduct && (!$item->variantID && !$comboProduct->variantID || $comboProduct->variantID == $item->variantID)) {
                        $newQuantity = $comboQuantity * $comboProduct->quantity;
                        $basePrice = $item->variant ? ($item->variant->sale_price ?? $item->variant->price) : ($item->product->sale_price ?? $item->product->price);
                        
                        // Tính giá sau khi áp dụng giảm giá combo
                        $discountedPrice = $this->calculateComboDiscountedPrice($combo, $basePrice, $comboProduct);

                        $item->quantity = $newQuantity;
                        $item->price = $discountedPrice;
                        $item->total_price = $newQuantity * $discountedPrice;
                        $item->save();

                        Log::info('Updated combo cart item', [
                            'cart_id' => $item->id,
                            'product_id' => $item->productID,
                            'variant_id' => $item->variantID,
                            'new_quantity' => $newQuantity,
                            'new_price' => $discountedPrice,
                            'new_total_price' => $item->total_price,
                        ]);
                    } else {
                        Log::warning('Combo product not matched for update', [
                            'cart_id' => $item->id,
                            'product_id' => $item->productID,
                            'variant_id' => $item->variantID,
                            'combo_product_id' => $comboProduct ? $comboProduct->productID : null,
                            'combo_variant_id' => $comboProduct ? $comboProduct->variantID : null,
                        ]);
                    }
                }

                return response()->json([
                    'message' => 'Đã cập nhật số lượng combo!',
                    'total_price' => number_format($cartItem->fresh()->total_price, 0, ',', '.'),
                ], 200);
            } else {
                // Handle single product update
                $stock = $cartItem->variant ? ($cartItem->variant->stock ?? 0) : ($cartItem->product->stock_total ?? 0);

                if ($request->quantity > $stock) {
                    return response()->json([
                        'message' => 'Số lượng vượt quá tồn kho hiện tại!',
                        'available' => $stock,
                    ], 422);
                }

                $cartItem->quantity = $request->quantity;
                $cartItem->total_price = $request->quantity * $cartItem->price;
                $cartItem->save();

                Log::info('Updated non-combo cart item', [
                    'cart_id' => $cartItem->id,
                    'product_id' => $cartItem->productID,
                    'variant_id' => $cartItem->variantID,
                    'new_quantity' => $cartItem->quantity,
                    'new_total_price' => $cartItem->total_price,
                ]);

                return response()->json([
                    'message' => 'Đã cập nhật số lượng!',
                    'total_price' => number_format($cartItem->total_price, 0, ',', '.'),
                ], 200);
            }
        } catch (\Exception $e) {
            Log::error('Error updating cart item: ' . $e->getMessage(), ['cart_id' => $id, 'trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Có lỗi xảy ra khi cập nhật số lượng!'], 500);
        }
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
            $variant = ProductVariant::find($item['variant_id']);
            $stock = $variant ? ($variant->stock ?? 0) : ($product ? ($product->stock_total ?? 0) : 0);
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
                ->whereNull('combo_id')
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
                    'buying_flag' => false,
                    'combo_id' => null,
                ]);
            }

            $results[] = [
                'variant_id' => $item['variant_id'],
                'status' => 'success',
                'message' => 'Đã thêm vào giỏ hàng!'
            ];
        }

        if (collect($results)->every(fn($r) => $r['status'] === 'success')) {
            return response()->json(['message' => 'Đã thêm vào giỏ hàng!', 'results' => $results], 200);
        }

        return response()->json(['message' => 'Một số sản phẩm không thể thêm vào giỏ hàng!', 'results' => $results], 422);
    }

    public function updateSelectedProducts(Request $request)
    {
        $selectedIds = $request->input('selected');
        session(['selected_products' => $selectedIds]);
        return response()->json(['message' => 'Đã cập nhật sản phẩm đã chọn!']);
    }
}