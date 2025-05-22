<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'position',
        'status',
        'salary',
        'shop_id',
        'hired_date'
    ];

    protected $casts = [
        'salary' => 'decimal:2',
        'hired_date' => 'date'
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByPosition($query, $position)
    {
        return $query->where('position', $position);
    }

    // Methods
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isOnLeave()
    {
        return $this->status === 'on_leave';
    }

    public function isManager()
    {
        return $this->position === 'manager';
    }

    public function isAdmin()
    {
        return $this->position === 'admin';
    }

    public function isStaff()
    {
        return $this->position === 'staff';
    }
} 