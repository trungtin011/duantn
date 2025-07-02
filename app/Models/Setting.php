<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings'; // Xác định bảng
    protected $guarded = []; // Cho phép mass assignment
}
