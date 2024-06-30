<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'category_id',
        'title',
        'slug',
        'image',
        'description',
    ];

    public function tags(){
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function blog_category(){
        return $this->belongsTo(BlogCategory::class, 'category_id', 'id');
    }

    public function admin() {
        return $this->belongsTo(User::class, 'admin_id', 'id');
    }

    public function comments() {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }
}
