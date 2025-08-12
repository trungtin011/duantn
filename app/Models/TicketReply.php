<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
        'attachment_path',
        'is_internal'
    ];

    protected $casts = [
        'is_internal' => 'boolean',
    ];

    // Relationship với Ticket
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    // Relationship với User (người trả lời)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scope để lọc phản hồi nội bộ
    public function scopeInternal($query)
    {
        return $query->where('is_internal', true);
    }

    // Scope để lọc phản hồi công khai
    public function scopePublic($query)
    {
        return $query->where('is_internal', false);
    }
}