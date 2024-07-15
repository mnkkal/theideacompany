<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    use HasFactory;
    protected $table = 'user_subscriptions';

    protected $fillable = [
        'user_id',
        'subscription_plan_id',
        'transaction_id',
        'plan_amount',
        'plan_frequency',
        'starts_at',
        'ends_at',
        'trial_ends_at',
        'sms_limit',
        'status',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'trial_ends_at' => 'datetime',
    ];
}
