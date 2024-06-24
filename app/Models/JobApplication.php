<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;
    protected $table = 'job_applications';
    protected $fillable = [
        'job_id', 'name', 'email', 'phone', 'experience', 'notice_period', 'current_job', 'resume'
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
