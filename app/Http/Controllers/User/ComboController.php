<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Combo;
use Illuminate\Http\Request;

class ComboController extends Controller
{
    /**
     * Display a listing of combos with their products.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $perPage = 12;
        $query = Combo::with(['products.product.images', 'products.product.variants', 'shop'])
            ->where('status', 'active')
            ->whereNotNull('shopID')
            ->has('shop');

        // Apply search filter
        if ($request->filled('search')) {
            $query->where('combo_name', 'like', '%' . $request->search . '%');
        }

        // Apply price filter
        if ($request->filled('min_price') || $request->filled('max_price')) {
            $minPrice = $request->input('min_price', 0);
            $maxPrice = $request->input('max_price', PHP_INT_MAX);
            $query->whereBetween('total_price', [$minPrice, $maxPrice]);
        }

        // Apply discount percentage filter
        if ($request->filled('min_discount') || $request->filled('max_discount')) {
            $minDiscount = $request->input('min_discount', 0);
            $maxDiscount = $request->input('max_discount', 100);
            $query->where(function ($q) use ($minDiscount, $maxDiscount) {
                $q->where('discount_type', 'percentage')
                  ->where('discount_value', '>=', $minDiscount)
                  ->where('discount_value', '<=', $maxDiscount);
            });
        }

        $combos = $query->paginate($perPage);

        return view('user.combo.index', compact('combos'));
    }

    /**
     * Display the specified combo with its products.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $combo = Combo::with([
            'products' => function ($query) {
                $query->with(['product.images']); // Truy vấn qua combo_products -> product -> product_images
            },
            'shop'
        ])
            ->where('status', 'active')
            ->whereNotNull('shopID')
            ->has('shop')
            ->findOrFail($id);

        return view('user.combo.show', compact('combo'));
    }
}
