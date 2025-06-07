<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use Illuminate\Http\Request;

class AttributeController extends Controller
{

    public function index()
    {
        $attributes = Attribute::all();
        return view('admin.attributes.index', compact('attributes'));
    }

    /**
     * Thêm một thuộc tính đơn lẻ
     */
    public function store(Request $request)
    {
        // Validate dữ liệu nhập vào
        $request->validate([
            'name' => 'required|string|max:100'
        ]);

        // Tạo mới thuộc tính
        $attribute = Attribute::create([
            'name' => $request->name,
        ]);

        // Chuyển hướng về trang trước kèm thông báo thành công
        return redirect()->back()->with('success', 'Đã thêm thuộc tính: ' . $attribute->name);
    }

    /**
     * Thêm danh sách các thuộc tính.
     * Dữ liệu đầu vào: chuỗi cách nhau bằng dấu phẩy,
     * ví dụ "Màu sắc, Kích thước, Chất liệu"
     */
    public function storeList(Request $request)
    {
        $request->validate([
            'attributes_list' => 'required|string'
        ]);

        // Tách chuỗi thành mảng tên (loại bỏ khoảng trắng thừa)
        $names = array_filter(array_map('trim', explode(',', $request->attributes_list)));
        $inserted_ids = [];

        foreach ($names as $name) {
            // Nếu muốn tránh trùng lặp, bạn có thể kiểm tra trước khi tạo
            $attribute = Attribute::create(['name' => $name]);
            $inserted_ids[] = $attribute->id;
        }

        return redirect()->back()->with('success', 'Đã thêm các thuộc tính với IDs: ' . implode(', ', $inserted_ids));
    }
}
