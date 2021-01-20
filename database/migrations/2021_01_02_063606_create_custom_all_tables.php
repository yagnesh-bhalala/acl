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
        Schema::dropIfExists('player');
        DB::statement("CREATE TABLE `player` (`id` bigint(20) NOT NULL AUTO_INCREMENT,`user_id` bigint(20) NOT NULL,`player_name` varchar(55) NOT NULL,`player_code` varchar(55) NOT NULL,`opening_balance` float DEFAULT NULL,`player_commision_percentage` float DEFAULT NULL,`third_party_code` varchar(25) DEFAULT NULL,`third_party_percentage` float DEFAULT NULL,`is_flat_commision` tinyint(2) NOT NULL DEFAULT 0,`reached_limit` int(11) DEFAULT NULL,`self_player` int(11) DEFAULT NULL,`created_by` bigint(20) NOT NULL,`created_at` timestamp NULL DEFAULT NULL,`updated_at` timestamp NULL DEFAULT NULL,PRIMARY KEY (`id`), KEY `user_id` (`user_id`) ) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8");
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
