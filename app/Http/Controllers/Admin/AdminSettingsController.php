<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminSettingsController extends Controller
{
    
    public function index()
    {
        $settings = DB::table('settings')->first();
        $settings = [
            'site_title'    => $settings->site_title    ?? 'ZynoxMall',
            'tagline'       => $settings->tagline       ?? 'Chào mừng đến với ZynoxMall',
            'logo'          => $settings->logo          ?? null,
            'banner_image'  => $settings->banner_image  ?? null,
            'favicon'       => $settings->favicon       ?? null,
        ];
        return view('admin.settings.index', compact('settings'));
    }

    
    public function create()
    {
        $settings = DB::table('settings')->first();
        $settings = [
            'site_title'    => $settings->site_title    ?? 'ZynoxMall',
            'tagline'       => $settings->tagline       ?? 'Chào mừng đến với ZynoxMall',
            'logo'          => $settings->logo          ?? null,
            'banner_image'  => $settings->banner_image  ?? null,
            'favicon'       => $settings->favicon       ?? null,
        ];
        return view('admin.settings.create', compact('settings'));
    }

    /**
     * Cập nhật hoặc tạo mới cài đặt quản trị.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'site_title' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'banner_image' => 'nullable|image|max:2048',
            'favicon' => 'nullable|image|max:2048',
        ]);

        $data = $validatedData;

        // Xử lý upload logo
        if ($request->hasFile('logo')) {
            $settings = DB::table('settings')->first();
            if ($settings->logo ?? false) {
                Storage::disk('public')->delete($settings->logo);
            }
            $path = $request->file('logo')->store('logos', 'public');
            $data['logo'] = $path;
        }

        // Xử lý upload banner image
        if ($request->hasFile('banner_image')) {
            $settings = DB::table('settings')->first();
            if ($settings->banner_image ?? false) {
                Storage::disk('public')->delete($settings->banner_image);
            }
            $path = $request->file('banner_image')->store('banners', 'public');
            $data['banner_image'] = $path;
        }

        // Xử lý upload favicon
        if ($request->hasFile('favicon')) {
            $settings = DB::table('settings')->first();
            if ($settings->favicon ?? false) {
                Storage::disk('public')->delete($settings->favicon);
            }
            $path = $request->file('favicon')->store('favicons', 'public');
            $data['favicon'] = $path;
        }

        $this->saveSettings($data);

        Session::flash('success', 'Cài đặt đã được lưu thành công!');
        return redirect()->route('admin.settings.index');
    }

    /**
     * Lấy các cài đặt hiện tại (giả sử từ cơ sở dữ liệu).
     *
     * @return array
     */
    protected function getSettings()
    {
        $settings = DB::table('settings')->first();

        if ($settings) {
            return (array) $settings;
        }

        return [
            'site_title' => 'My Blog',
            'tagline' => 'My WordPress Blog',
            'logo' => null,
            'banner_image' => null,
            'favicon' => null,
        ];
    }

    /**
     * Lưu các cài đặt vào cơ sở dữ liệu.
     *
     * @param array $data
     * @return void
     */
    protected function saveSettings(array $data)
    {
        $exists = DB::table('settings')->exists();

        if ($exists) {
            DB::table('settings')->update($data);
        } else {
            DB::table('settings')->insert($data);
        }
    }

    /**
     * Xóa logo hiện tại.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyLogo()
    {
        $settings = DB::table('settings')->first();
        if ($settings->logo ?? false) {
            Storage::disk('public')->delete($settings->logo);
            DB::table('settings')->update(['logo' => null]);
            Session::flash('success', 'Logo đã được xóa thành công!');
        } else {
            Session::flash('error', 'Không tìm thấy logo để xóa!');
        }
        return redirect()->back();
    }

    /**
     * Xóa banner image hiện tại.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyBanner()
    {
        $settings = DB::table('settings')->first();
        if ($settings->banner_image ?? false) {
            Storage::disk('public')->delete($settings->banner_image);
            DB::table('settings')->update(['banner_image' => null]);
            Session::flash('success', 'Banner đã được xóa thành công!');
        } else {
            Session::flash('error', 'Không tìm thấy banner để xóa!');
        }
        return redirect()->back();
    }

    /**
     * Xóa favicon hiện tại.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyFavicon()
    {
        $settings = DB::table('settings')->first();
        if ($settings->favicon ?? false) {
            Storage::disk('public')->delete($settings->favicon);
            DB::table('settings')->update(['favicon' => null]);
            Session::flash('success', 'Favicon đã được xóa thành công!');
        } else {
            Session::flash('error', 'Không tìm thấy favicon để xóa!');
        }
        return redirect()->back();
    }
}
