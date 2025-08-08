<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Banner::query();

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lọc theo tiêu đề
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Sắp xếp
        $sortBy = $request->get('sort_by', 'sort_order');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $banners = $query->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.banners._table_body', compact('banners'))->render(),
                'pagination' => view('admin.banners._pagination', compact('banners'))->render(),
            ]);
        }

        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.banners.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'link_url' => 'nullable|url|max:255',
            'content_position' => 'nullable|string|in:center,left,right,top-left,top-right,bottom-left,bottom-right',
            'text_align' => 'nullable|string|in:center,left,right',
            'title_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'subtitle_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'title_font_size' => 'nullable|string|max:50',
            'subtitle_font_size' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'nullable|integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        try {
            $data = $request->except('image');
            
            // Upload hình ảnh
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('banners', 'public');
                $data['image_path'] = $path;
            }

            Banner::create($data);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Banner đã được tạo thành công!'
                ]);
            }

            return redirect()->route('admin.banners.index')
                ->with('success', 'Banner đã được tạo thành công!');

        } catch (\Exception $e) {
            Log::error('Error creating banner: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi tạo banner!'
                ], 500);
            }

            return back()->withErrors('Có lỗi xảy ra khi tạo banner!')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Banner $banner)
    {
        return view('admin.banners.show', compact('banner'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'link_url' => 'nullable|url|max:255',
            'content_position' => 'nullable|string|in:center,left,right,top-left,top-right,bottom-left,bottom-right',
            'text_align' => 'nullable|string|in:center,left,right',
            'title_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'subtitle_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'title_font_size' => 'nullable|string|max:50',
            'subtitle_font_size' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'nullable|integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        try {
            $data = $request->except('image');
            
            // Upload hình ảnh mới nếu có
            if ($request->hasFile('image')) {
                // Xóa hình ảnh cũ
                if ($banner->image_path) {
                    Storage::disk('public')->delete($banner->image_path);
                }
                
                $path = $request->file('image')->store('banners', 'public');
                $data['image_path'] = $path;
            }

            $banner->update($data);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Banner đã được cập nhật thành công!'
                ]);
            }

            return redirect()->route('admin.banners.index')
                ->with('success', 'Banner đã được cập nhật thành công!');

        } catch (\Exception $e) {
            Log::error('Error updating banner: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi cập nhật banner!'
                ], 500);
            }

            return back()->withErrors('Có lỗi xảy ra khi cập nhật banner!')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner)
    {
        try {
            // Xóa hình ảnh
            if ($banner->image_path) {
                Storage::disk('public')->delete($banner->image_path);
            }

            $banner->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Banner đã được xóa thành công!'
                ]);
            }

            return redirect()->route('admin.banners.index')
                ->with('success', 'Banner đã được xóa thành công!');

        } catch (\Exception $e) {
            Log::error('Error deleting banner: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi xóa banner!'
                ], 500);
            }

            return back()->withErrors('Có lỗi xảy ra khi xóa banner!');
        }
    }

    /**
     * Cập nhật trạng thái banner
     */
    public function toggleStatus(Banner $banner)
    {
        try {
            $banner->update([
                'status' => $banner->status === 'active' ? 'inactive' : 'active'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Trạng thái banner đã được cập nhật!',
                'status' => $banner->status
            ]);

        } catch (\Exception $e) {
            Log::error('Error toggling banner status: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật trạng thái!'
            ], 500);
        }
    }

    /**
     * Cập nhật thứ tự sắp xếp
     */
    public function updateOrder(Request $request)
    {
        try {
            $orders = $request->input('orders', []);
            
            foreach ($orders as $order) {
                Banner::where('id', $order['id'])->update(['sort_order' => $order['order']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Thứ tự banner đã được cập nhật!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating banner order: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật thứ tự!'
            ], 500);
        }
    }
}
