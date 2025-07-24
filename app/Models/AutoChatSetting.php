<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoChatSetting extends Model
{
    use HasFactory;

    protected $table = 'auto_chat_settings';

    protected $fillable = [
        'user_id',
        'auto_reply_enabled',
        'auto_reply_offtime_enabled',
    ];
}
