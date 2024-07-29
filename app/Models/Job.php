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
    protected $table = 'job_post';
    protected $fillable = [
        'job_post',
        'email',
        'company_name',
        'job_type',
        'doj',
        'apply_by',
        'salary',
        'hiring_from',
        'about_company',
        'about_job',
        'who_can_apply',
        'skill_required',
        'add_perks_of_job',
    ];

    protected $casts = [
        'job_type' => 'array',
    ];
}
