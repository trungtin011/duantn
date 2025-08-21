<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'userID',
        'receiver_name',
        'receiver_phone',
        'address',
        'province',
        'district',
        'ward',
        'zip_code', // Giữ lại để tương thích với database nhưng sẽ luôn null
        'address_type',
        'note',
        'is_default'
    ];

    protected $casts = [
        'is_default' => 'boolean'
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userID');
    }

    // Methods
    public function getFullAddressAttribute()
    {
        return "{$this->address}, {$this->ward}, {$this->district}, {$this->province}";
    }

    public function setAsDefault()
    {
        // Remove default status from other addresses
        $this->user->addresses()->update(['is_default' => false]);

        // Set this address as default
        $this->update(['is_default' => true]);
    }
    public function getAddressTypeLabel()
{
    return match ($this->address_type) {
        'home' => 'Nhà riêng',
        'office' => 'Công ty',
        'other' => 'Khác',
        default => 'Không xác định',
    };
}

}
