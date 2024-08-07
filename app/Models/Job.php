<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'created_by'
    ];

    protected $casts = [
        'job_type' => 'array',
    ];
    public function User(): HasOne
    {
        return $this->hasOne(User::class, 'created_by');
    }
}
