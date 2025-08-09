<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image_path',
        'image_width',
        'image_height',
        'image_size',
        'image_position',
        'image_object_fit',
        'image_object_position',
        'image_parallax',
        'image_scale',
        'link_url',
        'content_position',
        'text_align',
        'title_color',
        'subtitle_color',
        'title_font_size',
        'subtitle_font_size',
        'status',
        'sort_order',
        'start_date',
        'end_date',
        'responsive_settings',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'responsive_settings' => 'array',
    ];

    // Scope để lấy banner active
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope để lấy banner hiện tại (trong khoảng thời gian)
    public function scopeCurrent($query)
    {
        return $query->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            });
    }

    // Lấy URL hình ảnh
    public function getImageUrlAttribute()
    {
        return Storage::url($this->image_path);
    }

    // Lấy thông tin kích thước hình ảnh
    public function getImageDimensionsAttribute()
    {
        if ($this->image_width && $this->image_height) {
            return [
                'width' => $this->image_width,
                'height' => $this->image_height,
                'size' => $this->image_size,
                'aspect_ratio' => round($this->image_width / $this->image_height, 2)
            ];
        }
        return null;
    }

    // Kiểm tra banner có đang hoạt động không
    public function isActive()
    {
        if ($this->status !== 'active') {
            return false;
        }

        $now = now();
        
        if ($this->start_date && $now < $this->start_date) {
            return false;
        }

        if ($this->end_date && $now > $this->end_date) {
            return false;
        }

        return true;
    }
}
