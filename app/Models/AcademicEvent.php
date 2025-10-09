<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'role',
        'institution_id',
        'location',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'description',
        'category',
        'color',
        'url',
        'file_path',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
        'status' => 'boolean',
    ];

    public function institution()
    {
        return $this->belongsTo(Institution::class, 'institution_id');
    }
}
