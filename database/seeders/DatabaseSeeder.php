<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(CreatePermissionSeeder::class);
        $this->call(AddPWAIconFieldSettingSeeder::class);
        $this->call(SetIsDefaultSuperAdminSeeder::class);
        $this->call(AddDefaultSettingSeeder::class);
    }
}
