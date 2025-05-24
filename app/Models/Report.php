<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    protected $fillable = [
        'reporter_id',
        'product_id',
        'shop_id',
        'order_id',
        'user_id',
        'report_type',
        'report_content',
        'evidence',
        'priority',
        'status',
        'resolution',
        'resolution_note',
        'assigned_to',
        'resolved_by',
        'resolved_at',
        'due_date',
        'is_anonymous'
    ];

    protected $casts = [
        'evidence' => 'array',
        'resolved_at' => 'datetime',
        'due_date' => 'datetime',
        'is_anonymous' => 'boolean'
    ];

    // Relationships
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function reportedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function resolvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeUnderReview($query)
    {
        return $query->where('status', 'under_review');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
            ->whereNotIn('status', ['resolved', 'rejected']);
    }

    // Methods
    public function isOverdue()
    {
        return $this->due_date && $this->due_date->isPast() &&
            !in_array($this->status, ['resolved', 'rejected']);
    }

    public function canBeAssigned()
    {
        return in_array($this->status, ['pending', 'under_review']);
    }

    public function canBeResolved()
    {
        return in_array($this->status, ['under_review', 'processing']);
    }
} 