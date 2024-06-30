<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo',
        'email_site',
        'address_site',
        'phone_site',
        'facebook',
        'twitter',
        'instagram',
        'linkedin',
        'copyright'
    ];
}
