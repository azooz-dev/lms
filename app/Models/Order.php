<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'user_id',
        'instructor_id',
        'course_id',
        'course_title',
        'course_image',
        'course_price',
    ];


    public function course() {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function instructor() {
        return $this->belongsTo(User::class, 'instructor_id', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function payment() {
        return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }
}
