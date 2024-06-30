<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'instructor_id',
        'user_id',
        'reply_id',
        'subject',
        'question',
        'read_status',
    ];

    public function course() {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function instructor() {
        return $this->belongsTo(User::class, 'instructor_id', 'id');
    }

    public function replies(){
        return $this->hasMany(ReplyQuestion::class, 'question_id');
    }
}
