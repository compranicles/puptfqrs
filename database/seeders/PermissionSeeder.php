<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Authentication\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* ************************RESEARCH********************************** */

        Permission::create(['name' => 'view all faculty research']);

        Permission::create(['name' => 'manage faculty research registration']);


        Permission::create(['name' => 'manage faculty research completion']);


        Permission::create(['name' => 'manage faculty research presentation']);

        Permission::create(['name' => 'manage faculty research publication']);

        Permission::create(['name' => 'manage faculty research copyright']);

        Permission::create(['name' => 'manage faculty research utilization']);

        Permission::create(['name' => 'manage faculty research citation']);

        Permission::create(['name' => 'defer research']);

        /* ************************Users********************************** */
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'edit user role']);
        Permission::create(['name' => 'delete user record']);

    }
}