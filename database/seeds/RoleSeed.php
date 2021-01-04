<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create(['name' => 'users_manage']);
        $role->givePermissionTo('users_manage');

        $role = Role::create(['name' => 'superadmin']);
        $role->givePermissionTo('superadmin');

        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo('admin');

        $role = Role::create(['name' => 'user']);
        $role->givePermissionTo('user');
    }
}
