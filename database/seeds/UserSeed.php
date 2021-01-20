<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $user = User::create([
            'name' => 'Author',
            'email' => 'author@admin.com',
            'username' => 'barotjee',
            'password' => bcrypt('barotjee'),
            'created_by' => 0,
            'rl' => 1, //Author
            'start_access_date' => null,
            'end_access_date' => null,
        ],);
        $user->assignRole('users_manage');

        // -------------------------------------------
        $user2 = User::create([
            'name' => 'Superadmin',
            'email' => 'superadmin@gmail.com',
            'username' => 'superadmin',
            'password' => bcrypt('password'),
            'created_by' => 1,
            'rl' => 2, //Superadmin
            'start_access_date' => date('Y-m-d'),
            'end_access_date' => date("Y-m-d",strtotime("1 year")),
        ]);
        $user2->assignRole('superadmin');
        // -------------------------------------------

        

    }
}
