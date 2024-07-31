<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PitchUserLikesDislike extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'pitch_id', 'liked', 'disliked'];
}
