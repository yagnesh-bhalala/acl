<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Validator;
use App\Player;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('validate_third_party_code', function($attr, $value, $param) {
            // echo "<pre><br>";
            // print_r($attr); 
            // echo "<br>";print_r($value); 
            // echo "<br>";print_r($param); 
            // die;
            $plrMdl = Player::where(['id' => $value, 'created_by' => $param[0]])->first();
            // $value  is commision_2_player id
            // $param[0])  is player_id
            if (!$value) {
                true;
            }
            if ($plrMdl) {
                return true;
            }

            return false;
        }, "Invalidate third party player code.");
        Schema::defaultStringLength(191);

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        

    }
}
