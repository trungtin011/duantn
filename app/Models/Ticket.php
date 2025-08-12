<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_code',
        'user_id',
        'assigned_to',
        'subject',
        'description',
        'priority',
        'status',
        'category',
        'attachment_path',
        'resolved_at',
        'closed_at'
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    // Relationship với User (người tạo ticket)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relationship với Admin được phân công
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Relationship với các phản hồi
    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class);
    }

    // Scope để lọc ticket theo status
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope để lọc ticket theo priority
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    // Scope để lọc ticket theo category
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Tạo mã ticket tự động
    public static function generateTicketCode(): string
    {
        do {
            $code = 'TKT-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 6));
        } while (self::where('ticket_code', $code)->exists());

        return $code;
    }

    // Kiểm tra xem ticket có thể được cập nhật không
    public function canBeUpdated(): bool
    {
        return !in_array($this->status, ['resolved', 'closed']);
    }

    // Đánh dấu ticket đã được giải quyết
    public function markAsResolved(): void
    {
        $this->update([
            'status' => 'resolved',
            'resolved_at' => now()
        ]);
    }

    // Đánh dấu ticket đã đóng
    public function markAsClosed(): void
    {
        $this->update([
            'status' => 'closed',
            'closed_at' => now()
        ]);
    }
}