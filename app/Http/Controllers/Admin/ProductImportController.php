<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\ProductsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class ProductImportController extends Controller
{
    public function showImportForm()
    {
        return view('admin.products.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(new ProductsImport, $request->file('excel_file')); // ❌ KHÔNG DÙNG QUEUE
            return redirect()->back()->with('success', 'Nhập sản phẩm thành công!');
        } catch (\Exception $e) {
            Log::error('Lỗi nhập sản phẩm: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi nhập sản phẩm: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        return response()->download(public_path('templates/products_import_template.xlsx'));
    }
}
