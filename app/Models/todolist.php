<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class todolist extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'activity',
        'user_id',
        'user_profile',
        'user_name'
    ];
}
