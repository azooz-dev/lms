<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course_Lecture extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'course_section',
        'lecture_title',
        'content',
        'video',
        'url',
    ];
}
