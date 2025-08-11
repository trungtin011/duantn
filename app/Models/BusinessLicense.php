<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusinessLicense extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'business_license_number',
        'tax_number',
        'business_ID',
        'business_name',
        'business_license_date',
        'expiry_date',
        'status',
        'rejection_reason',
        'verified_by',
        'verified_at',
        'license_file_path',
        'is_active'
    ];

    protected $casts = [
        'business_license_date' => 'date',
        'expiry_date' => 'date',
        'verified_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function sellers(): HasMany
    {
        return $this->hasMany(Seller::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'shop_orderID', 'id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now());
    }

    // Methods
    public function isActive()
    {
        return $this->is_active && $this->status === 'approved' && $this->expiry_date > now();
    }

    public function isExpired()
    {
        return $this->expiry_date <= now();
    }

    public function approve($verifiedBy)
    {
        $this->update([
            'status' => 'approved',
            'verified_by' => $verifiedBy,
            'verified_at' => now(),
            'is_active' => true
        ]);
    }

    public function reject($reason)
    {
        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'is_active' => false
        ]);
    }

    public function deactivate()
    {
        $this->update(['is_active' => false]);
    }
}
