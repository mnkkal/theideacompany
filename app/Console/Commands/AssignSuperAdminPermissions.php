<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;


class AssignSuperAdminPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:assign-super-admin-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $superAdmin = Role::where('name', 'Admin')->first();
       
        if ($superAdmin === null) {
            $superAdmin = new Role();
            $superAdmin->id = 1;
            $superAdmin->name = 'Admin';
            $superAdmin->save();
        }
        if ($superAdmin) {
                    $permissions = Permission::all();
                    $superAdmin->syncPermissions($permissions);
                    $this->info(
                        'All Permissions assigned to Super Admin successfully.'
                    );
                    $user = User::where('id', 1)->first();
            if ($user === null) {
                        $user = User::first();
            }
                    $user->syncRoles("Admin");
            return true;
        } else {
            $this->error('Super admin user not found.');
            return false;
        }
        return false;
    }
}
