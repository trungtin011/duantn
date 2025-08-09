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

    public function editEmails()
    {
        $settings = $this->getSettings();
        // Lấy giá trị thực tế từ config/env
        $settings['mail_from_address'] = old('mail_from_address', env('MAIL_FROM_ADDRESS', config('mail.from.address')));
        $settings['mail_from_name'] = old('mail_from_name', env('MAIL_FROM_NAME', config('mail.from.name')));
        $settings['mail_reply_to'] = old('mail_reply_to', env('MAIL_REPLY_TO', ''));
        $settings['mail_driver'] = old('mail_driver', env('MAIL_MAILER', config('mail.default')));
        $settings['mail_host'] = old('mail_host', env('MAIL_HOST', config('mail.mailers.smtp.host')));
        $settings['mail_port'] = old('mail_port', env('MAIL_PORT', config('mail.mailers.smtp.port')));
        $settings['mail_username'] = old('mail_username', env('MAIL_USERNAME', config('mail.mailers.smtp.username')));
        $settings['mail_password'] = old('mail_password', env('MAIL_PASSWORD', config('mail.mailers.smtp.password')));
        $settings['mail_encryption'] = old('mail_encryption', env('MAIL_ENCRYPTION', config('mail.mailers.smtp.encryption')));
        return view('admin.settings.emails', compact('settings'));
    }

    public function updateEmails(Request $request)
    {
        $validated = $request->validate([
            'mail_from_address' => 'required|email|max:255',
            'mail_from_name' => 'required|string|max:255',
            'mail_reply_to' => 'nullable|email|max:255',
            'mail_driver' => 'required|in:smtp,sendmail,mailgun,ses',
            'mail_host' => 'required|string|max:255',
            'mail_port' => 'required|numeric',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|in:tls,ssl,null',
        ]);
        // Lưu vào DB (bảng settings)
        $data = [
            'mail_from_address' => $validated['mail_from_address'],
            'mail_from_name' => $validated['mail_from_name'],
            'mail_reply_to' => $validated['mail_reply_to'] ?? null,
            'mail_driver' => $validated['mail_driver'],
            'mail_host' => $validated['mail_host'],
            'mail_port' => $validated['mail_port'],
            'mail_username' => $validated['mail_username'],
            'mail_password' => $validated['mail_password'],
            'mail_encryption' => $validated['mail_encryption'],
        ];
        $exists = \DB::table('settings')->exists();
        if ($exists) {
            \DB::table('settings')->update($data);
        } else {
            \DB::table('settings')->insert($data);
        }
        // Đồng bộ vào file .env
        $this->setEnv([
            'MAIL_FROM_ADDRESS' => $validated['mail_from_address'],
            'MAIL_FROM_NAME' => '"' . $validated['mail_from_name'] . '"',
            'MAIL_REPLY_TO' => $validated['mail_reply_to'] ?? '',
            'MAIL_MAILER' => $validated['mail_driver'],
            'MAIL_HOST' => $validated['mail_host'],
            'MAIL_PORT' => $validated['mail_port'],
            'MAIL_USERNAME' => $validated['mail_username'],
            'MAIL_PASSWORD' => $validated['mail_password'],
            'MAIL_ENCRYPTION' => $validated['mail_encryption'],
        ]);
        // Reload config cache để giá trị mới có hiệu lực ngay
        \Artisan::call('config:clear');
        \Artisan::call('config:cache');
        \Session::flash('success', 'Cập nhật cài đặt email thành công!');
        return redirect()->route('admin.settings.emails');
    }

    /**
     * Ghi các key vào file .env
     */
    protected function setEnv(array $data)
    {
        $envPath = base_path('.env');
        $env = file_exists($envPath) ? file_get_contents($envPath) : '';
        foreach ($data as $key => $value) {
            $pattern = "/^{$key}=.*$/m";
            $line = $key . '=' . $value;
            if (preg_match($pattern, $env)) {
                $env = preg_replace($pattern, $line, $env);
            } else {
                $env .= "\n" . $line;
            }
        }
        file_put_contents($envPath, $env);
    }
}
