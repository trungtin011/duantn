<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class BrandController extends Controller
{
    public function index()
    {
        Log::info('BrandController@index called');
        $brands = Brand::where('status', '!=', 'deleted')->paginate(10);
        Log::debug('Fetched brands for index', ['brands_count' => $brands->count()]);
        return view('admin.brands.index', compact('brands'));
    }

    public function store(Request $request)
    {
        Log::info('BrandController@store called', ['request' => $request->all()]);
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:brand,name',
            'slug' => 'nullable|string|max:100|unique:brand,slug',
            'description' => 'nullable|string',
            'image_path' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ], [
            'name.required' => 'Tên thương hiệu là bắt buộc.',
            'name.string' => 'Tên thương hiệu phải là chuỗi.',
            'name.max' => 'Tên thương hiệu không được vượt quá 100 ký tự.',
            'name.unique' => 'Tên thương hiệu đã tồn tại.',
            'slug.string' => 'Slug phải là chuỗi.',
            'slug.max' => 'Slug không được vượt quá 100 ký tự.',
            'slug.unique' => 'Slug đã tồn tại.',
            'description.string' => 'Mô tả phải là chuỗi.',
            'image_path.required' => 'Hình ảnh là bắt buộc.',
            'image_path.image' => 'File phải là hình ảnh.',
            'image_path.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, webp.',
            'image_path.max' => 'Hình ảnh không được vượt quá 5MB.',
            'meta_title.string' => 'Tiêu đề Meta phải là chuỗi.',
            'meta_title.max' => 'Tiêu đề Meta không được vượt quá 255 ký tự.',
            'meta_description.string' => 'Mô tả Meta phải là chuỗi.',
            'meta_keywords.string' => 'Từ khóa Meta phải là chuỗi.',
            'meta_keywords.max' => 'Từ khóa Meta không được vượt quá 255 ký tự.',
            'status.required' => 'Trạng thái là bắt buộc.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ]);

        if ($validator->fails()) {
            Log::warning('BrandController@store validation failed', ['errors' => $validator->errors()]);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        
        // Tự động tạo slug nếu không có
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($request->name);
        }

        if ($request->hasFile('image_path')) {
            $data['image_path'] = $request->file('image_path')->store('brands', 'public');
            Log::info('Brand image uploaded', ['image_path' => $data['image_path']]);
        }

        $brand = Brand::create($data);
        Log::info('Brand created', ['brand_id' => $brand->id]);

        return redirect()->route('admin.brands.index')->with('success', 'Thương hiệu đã được tạo thành công.');
    }

    public function edit(Brand $brand)
    {
        Log::info('BrandController@edit called', ['brand_id' => $brand->id]);
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        Log::info('BrandController@update called', ['brand_id' => $brand->id, 'request' => $request->all()]);
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:brand,name,' . $brand->id,
            'slug' => 'nullable|string|max:100|unique:brand,slug,' . $brand->id,
            'description' => 'nullable|string',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ], [
            'name.required' => 'Tên thương hiệu là bắt buộc.',
            'name.string' => 'Tên thương hiệu phải là chuỗi.',
            'name.max' => 'Tên thương hiệu không được vượt quá 100 ký tự.',
            'name.unique' => 'Tên thương hiệu đã tồn tại.',
            'slug.string' => 'Slug phải là chuỗi.',
            'slug.max' => 'Slug không được vượt quá 100 ký tự.',
            'slug.unique' => 'Slug đã tồn tại.',
            'description.string' => 'Mô tả phải là chuỗi.',
            'image_path.image' => 'File phải là hình ảnh.',
            'image_path.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, webp.',
            'image_path.max' => 'Hình ảnh không được vượt quá 5MB.',
            'meta_title.string' => 'Tiêu đề Meta phải là chuỗi.',
            'meta_title.max' => 'Tiêu đề Meta không được vượt quá 255 ký tự.',
            'meta_description.string' => 'Mô tả Meta phải là chuỗi.',
            'meta_keywords.string' => 'Từ khóa Meta phải là chuỗi.',
            'meta_keywords.max' => 'Từ khóa Meta không được vượt quá 255 ký tự.',
            'status.required' => 'Trạng thái là bắt buộc.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ]);

        if ($validator->fails()) {
            Log::warning('BrandController@update validation failed', ['brand_id' => $brand->id, 'errors' => $validator->errors()]);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        
        // Tự động tạo slug nếu không có
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($request->name);
        }

        if ($request->hasFile('image_path')) {
            if ($brand->image_path) {
                Storage::disk('public')->delete($brand->image_path);
                Log::info('Old brand image deleted', ['brand_id' => $brand->id, 'old_image_path' => $brand->image_path]);
            }
            $data['image_path'] = $request->file('image_path')->store('brands', 'public');
            Log::info('Brand image updated', ['brand_id' => $brand->id, 'new_image_path' => $data['image_path']]);
        }

        $brand->update($data);
        Log::info('Brand updated', ['brand_id' => $brand->id]);

        return redirect()->route('admin.brands.index')->with('success', 'Thương hiệu đã được cập nhật thành công.');
    }

    public function destroy(Brand $brand)
    {
        Log::info('BrandController@destroy called', ['brand_id' => $brand->id]);
        $brand->update(['status' => 'deleted']);
        Log::info('Brand soft deleted', ['brand_id' => $brand->id]);
        return redirect()->route('admin.brands.index')->with('success', 'Thương hiệu đã được xóa thành công.');
    }
}