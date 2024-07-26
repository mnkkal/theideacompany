<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'subscription_plan_id',
        'user_id',
        'razorpay_order_id',
        'status'
    ];
}
