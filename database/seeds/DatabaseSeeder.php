<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::statement('TRUNCATE `migrations`;TRUNCATE `password_resets`;TRUNCATE `permissions`;TRUNCATE `player`;TRUNCATE `roles`;TRUNCATE `role_has_permissions`;TRUNCATE `users`;TRUNCATE `model_has_permissions`;TRUNCATE `model_has_roles`;');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->call(PermissionSeed::class);
        $this->call(RoleSeed::class);
        $this->call(UserSeed::class);
    }
}
