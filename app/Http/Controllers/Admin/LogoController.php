<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Logo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LogoController extends Controller
{
    public function index()
    {
        $logos = Logo::latest()->get();
        return view('admin.logo.index', compact('logos'));
    }

    public function create()
    {
        return view('admin.logo.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'required|image',
        ]);

        // Nếu chọn logo active thì tắt logo khác
        if ($request->status === 'active') {
            Logo::where('status', 'active')->update(['status' => 'inactive']);
        }

        $path = $request->file('image')->store('logos', 'public');

        Logo::create([
            'name' => $request->name,
            'image_path' => $path,
            'status' => $request->status ?? 'inactive',
        ]);

        return redirect()->route('logo.index')->with('success', 'Thêm logo thành công');
    }

    public function destroy(Logo $logo)
    {
        Storage::disk('public')->delete($logo->image_path);
        $logo->delete();

        return redirect()->back()->with('success', 'Xoá logo thành công');
    }
}
