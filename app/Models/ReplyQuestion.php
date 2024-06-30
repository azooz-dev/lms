<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReplyQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'user_id',
        'reply',
        'course_id',
        'instructor_id',
        'read_status'
    ];


    public function question() {
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }

    public function instructor() {
        return $this->belongsTo(User::class, 'instructor_id', 'id');
    }

}
