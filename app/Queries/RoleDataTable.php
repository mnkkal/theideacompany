<?php

namespace App\Queries;

use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class RoleDataTable
 */
class RoleDataTable
{
    public function get(): Builder
    {
        return Role::with('permissions');
    }
}
