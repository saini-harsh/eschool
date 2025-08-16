<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'name',
        'section_ids',
        'student_count',
        'institution_id',
        'admin_id',
        'status',
    ];

    protected $casts = [
        'section_ids' => 'array', // auto convert JSON to array
    ];

    // Optional: fetch sections directly
    public function sections()
    {
        return Section::whereIn('id', $this->section_ids ?? [])->get();
    }
}
