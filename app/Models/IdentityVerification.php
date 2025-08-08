<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdentityVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'userID',
        'shop_id',
        'full_name',
        'identity_number',
        'birth_date',
        'nationality',
        'gender',
        'hometown',
        'residence',
        'identity_type',
        'identity_card_date',
        'identity_card_place',
        'identity_card_image',
        'identity_card_holding_image',
        'status',
        'rejection_reason',
        'verified_by',
        'verified_at'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'identity_card_date' => 'date',
        'verified_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
} 