<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    protected $fillable = [
        'coupon_name',
        'coupon_discount',
        'coupon_validity',
        'instructor_id',
        'course_id',
    ];


    public function course() {
        return $this->belongsTo(Course::class);
    }
}
