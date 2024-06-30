<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'subCategory_name',
        'subCategory_slug'
    ];




    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function courses() {
        return $this->hasMany(Course::class, 'sub_category_id', 'id');
    }
}
