<?php

use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $permission = Permission::create([
            'name' => 'manage_front_cms',
            'display_name' => 'Manage Front CMS',
            'guard_name' => 'web',
        ]);
        $role = Role::where('name', 'Admin')->first();
        $role->givePermissionTo($permission);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $role = Role::where('name', 'Admin')->first();
        $role->revokePermissionTo('manage_front_cms');
        Permission::where('name', 'manage_front_cms')->delete();
    }
};
