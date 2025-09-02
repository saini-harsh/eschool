<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_no',
        'capacity',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
