<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AttributeController extends Controller
{
    public function index()
    {
        $attributes = Attribute::with('attributeValues')->paginate(10);
        return view('admin.attributes.index', compact('attributes'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'name' => 'required|string|max:100|unique:attributes,name',
                'values' => 'nullable|string',
            ]);

            $attribute = Attribute::create([
                'name' => $request->name,
            ]);

            // Lưu các giá trị nếu có
            if ($request->filled('values')) {
                $values = array_filter(array_map('trim', explode(',', $request->values)));
                foreach ($values as $value) {
                    AttributeValue::create([
                        'attribute_id' => $attribute->id,
                        'value' => $value,
                    ]);
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Đã thêm thuộc tính: ' . $attribute->name);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi thêm thuộc tính: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi thêm thuộc tính: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $attribute = Attribute::with('attributeValues')->findOrFail($id);
        return view('admin.attributes.edit', compact('attribute'));
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'name' => 'required|string|max:100|unique:attributes,name,' . $id,
                'values' => 'nullable|string',
            ]);

            $attribute = Attribute::findOrFail($id);
            $attribute->update([
                'name' => $request->name,
            ]);

            // Xóa các giá trị cũ và thêm giá trị mới
            AttributeValue::where('attribute_id', $id)->delete();
            if ($request->filled('values')) {
                $values = array_filter(array_map('trim', explode(',', $request->values)));
                foreach ($values as $value) {
                    AttributeValue::create([
                        'attribute_id' => $attribute->id,
                        'value' => $value,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.attributes.index')->with('success', 'Đã cập nhật thuộc tính: ' . $attribute->name);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi cập nhật thuộc tính: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi cập nhật thuộc tính: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $attribute = Attribute::findOrFail($id);
            $attributeName = $attribute->name;

            // Xóa các giá trị liên quan
            AttributeValue::where('attribute_id', $id)->delete();
            $attribute->delete();

            DB::commit();

            return redirect()->route('admin.attributes.index')->with('success', 'Đã xóa thuộc tính: ' . $attributeName);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi xóa thuộc tính: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa thuộc tính: ' . $e->getMessage());
        }
    }
}
