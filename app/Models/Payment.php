<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'cash_delivery',
        'total_amount',
        'invoice_number',
        'payment_type',
        'status',
    ];


    public function orders() {
        return $this->hasMany(Order::class);
    }
}
