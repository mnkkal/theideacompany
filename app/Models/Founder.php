<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Founder extends Model
{
    use HasFactory;
    protected $fillable = [
        'investor_id',
        'name',
        'qualification',
        'experience_summary',
        'key_skills',
    ];
}
