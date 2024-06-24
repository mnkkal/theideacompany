<?php
/**
 * Company: InfyOm Technologies, Copyright 2019, All Rights Reserved.
 */

namespace App\Queries;

use App\Models\Job;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class UserDataTable.
 */
class JobDataTable
{
    public function get(array $input = [])
    {
        $jobs = Job::All();
        dd($jobs);
        return $jobs;
    }
}
