<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyCheckin extends Model
{
    protected $fillable = ['user_id', 'checkin_date', 'points'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
