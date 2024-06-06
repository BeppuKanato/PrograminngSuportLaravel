<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'expiry',
    ];

    protected $casts = [
        'ecprity' => 'datetime',
    ];
}
