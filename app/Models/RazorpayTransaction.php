<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RazorpayTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature',
        'name',
        'email',
        'contact',
        'address',
        'amount'
    ];
}
