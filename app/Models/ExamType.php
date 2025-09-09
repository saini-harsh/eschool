<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamType extends Model
{
    use HasFactory;

    protected $fillable = [
        'institution_id',
        'title',
        'code',
        'description',
        'status'
    ];

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }
}
