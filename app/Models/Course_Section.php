<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course_Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'section_title',
    ];


    public function lectures() {
        return $this->hasMany(Course_Lecture::class, 'section_id', 'id');
    }
}
