<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable  = [
        'category_name',
        'category_slug',
        'image',
    ];


    public function subCategories() {
        return $this->hasMany(SubCategory::class);
    }

    public function courses() {
        return $this->hasMany(Course::class, 'category_id', 'id');
    }
}
