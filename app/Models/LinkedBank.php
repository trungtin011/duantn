<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LinkedBank extends Model {
    protected $fillable = ['seller_id', 'bank_id', 'account_number', 'account_name'];

    public function bank() {
        return $this->belongsTo(Bank::class);
    }
}
