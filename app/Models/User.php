<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'status',
        'photo',
        'phone',
        'address',
        'last_seen',
        'bio',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function courses() {
        return $this->hasMany(Course::class, 'instructor_id', 'id');
    }

    public function wishlistCourses(){
        return $this->belongsToMany(Course::class, 'wish_lists', 'user_id', 'course_id');
    }

    public function userOnline() {
        return Cache::has('user-id-online'. $this->id);
    }


    /**
     * Get the distinct permission group names.
     *
     * @return array The array of permission group names.
     */
    public static function get_permission_group_name() {

        // Fetch the distinct group names from the Permission model.
        // The group_name field is selected and the results are plucked into an array.
        // The plucked array is then converted to an array.
        return Permission::select('group_name')
            ->distinct()
            ->get()
            ->pluck('group_name')
            ->toArray();
    }
}
