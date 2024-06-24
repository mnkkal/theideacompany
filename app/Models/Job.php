<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;
    /**
     * @var string
     */
    protected $table = 'jobs';
    protected $fillable = [
        'title', 'description', 'company', 'location', 'experience', 'notice_period', 'current_job', 'resume'
    ];
}
