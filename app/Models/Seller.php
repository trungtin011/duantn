<?php

namespace App\Models;

use App\Enums\CustomerRanking;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Seller extends Model
{
    use HasFactory;

    protected $fillable = [
        'userID',
        'status',
        'identity_card',
        'identity_card_date',
        'identity_card_place',
        'bank_account',
        'bank_name',
        'bank_account_name',
        'business_license_id'
    ];

    protected $casts = [
        'identity_card_date' => 'date'
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userID');
    }

    public function businessLicense(): BelongsTo
    {
        return $this->belongsTo(BusinessLicense::class, 'business_license_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }

    public function scopeBanned($query)
    {
        return $query->where('status', 'banned');
    }

    // Methods
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isSuspended()
    {
        return $this->status === 'suspended';
    }

    public function isBanned()
    {
        return $this->status === 'banned';
    }

    public function suspend()
    {
        $this->update(['status' => 'suspended']);
    }

    public function ban()
    {
        $this->update(['status' => 'banned']);
    }

    public function activate()
    {
        $this->update(['status' => 'active']);
    }
} 