<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE TABLE `laravel_acl`.`player` ( `id` BIGINT NOT NULL , `user_id` BIGINT NOT NULL , `player_name` VARCHAR(55) NULL DEFAULT NULL , `player_code` VARCHAR(55) NOT NULL , `created_by` BIGINT NOT NULL , `created_at` TIMESTAMP NULL DEFAULT NULL , `updated_at` TIMESTAMP NULL DEFAULT NULL ) ENGINE = InnoDB;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('player');
    }
}
