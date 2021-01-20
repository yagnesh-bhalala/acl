<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('username');
            $table->string('email')->nullable();
            $table->string('password');
            $table->date('start_access_date')->nullable();
            $table->date('end_access_date')->nullable();
            $table->string('remember_token')->nullable();
            $table->integer('rl')->comment('1-author | 2-superadmin | 3-admin | 4-end user');
            $table->string('phone')->nullable();
            $table->string('pack')->nullable();
            $table->string('pwd')->nullable();
            $table->tinyInteger('status')->default(1);            
            $table->integer('created_by')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
