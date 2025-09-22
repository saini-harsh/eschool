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

    // Override the accessor to ensure proper array conversion
    public function getSectionIdsAttribute($value)
    {
        if (is_string($value)) {
            // Handle double-encoded JSON strings
            $decoded = json_decode($value, true);
            if (is_string($decoded)) {
                // If the decoded value is still a string, decode it again
                $decoded = json_decode($decoded, true);
            }
            return is_array($decoded) ? $decoded : [];
        }
        return is_array($value) ? $value : [];
    }

    // Optional: fetch sections directly
    public function sections()
    {
        return Section::whereIn('id', $this->section_ids ?? [])->get();
    }

    public function sec()
    {
        return $this->hasMany(Section::class);
    }

    public function assignClassTeachers()
    {
        return $this->hasMany(AssignClassTeacher::class, 'class_id');
    }
}
