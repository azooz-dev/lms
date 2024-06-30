<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'sub_category_id',
        'instructor_id',
        'image',
        'name',
        'title',
        'slug',
        'description',
        'video_link',
        'course_level',
        'duration',
        'language',
        'resources',
        'certificate',
        'selling_price',
        'discount_price',
        'prerequisites',
        'best_seller',
        'featured',
        'highest_rated',
        'status',
    ];

    public function instructor() {
        return $this->belongsTo(User::class, 'instructor_id', 'id');
    }

    public function category() {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function subCategory() {
        return $this->belongsTo(SubCategory::class, 'sub_category_id', 'id');
    }

    public function goals() {
        return $this->hasMany(Course_goal::class);
    }

    public function sections() {
        return $this->hasMany(Course_Section::class);
    }

    public function lectures() {
        return $this->hasMany(Course_Lecture::class, 'course_id', 'id');
    }

    public function orders() {
        return $this->hasMany(Order::class, 'course_id');
    }


    public function questions() {
        return $this->hasMany(Question::class, 'course_id');
    }


    public function reviews() {
        return $this->hasMany(Review::class, 'course_id', 'id')->where('status', '1');
    }


    /**
     * Calculates the distribution of ratings for the course
     *
     * @return array An array containing the rating and its corresponding percentage
     *              distribution for the course.
     */
    public function calculateRatingDistribution() {
        // Get all the reviews for the course and group them by rating
        $reviews = Review::where('course_id', $this->id)
            ->where('status', '1')
            // Select the rating and the count of each rating
            ->select(DB::raw("rating, COUNT(*) as count"))
            // Group the results by rating and order them by count in descending order
            ->groupBy('rating')
            ->orderByRaw('COUNT(*) DESC')
            // Get the results as a collection of objects
            ->get();

        // Calculate the total number of reviews
        $totalReviews = $reviews->sum('count');

        // Calculate the percentage of each rating and store it in an array
        $distribution = [];
        for($i = 5; $i >= 1; $i--) {
            $ratingCount = $reviews->where('rating', $i)->first();
            $count = $ratingCount ? $ratingCount->count : 0;
            // Calculate the percentage of each rating
            $percentage = $totalReviews > 0 ? ( $count / $totalReviews) * 100 : 0;
            // Store the rating and its percentage in an array
            $distribution[] = [
                'rating' => $i,
                'percentage' => round($percentage),
            ];

        }

        // Return the distribution array
        return $distribution;
    }
    

    
    // Add a new method to calculate the average review score
    public function averageReviewScore() {
        return $this->hasOne(Review::class, 'course_id', 'id')
                    ->select(DB::raw("avg(rating) as average_score"))
                    ->where('status', '1')
                    ->limit(1);
    }

}
