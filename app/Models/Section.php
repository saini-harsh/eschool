<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'institution_id',
        'class_id',
        'name',
        'description',
        'status',
    ];

    protected $with = ['institution', 'class'];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function student(){
        return $this->hasMany(Student::class, 'section_id');
    }

    public function institution(){
        return $this->belongsTo(Institution::class, 'institution_id');
    }

    public function schoolClass(){
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function class(){
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
}
