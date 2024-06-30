<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'user_id',
        'rating',
        'message',
        'instructor_id',
        'status',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function reviewsAvg() {
        $reviews = $this->where('status', 1)->avg('rating');

        return $reviews;
    }

    public function course() {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
