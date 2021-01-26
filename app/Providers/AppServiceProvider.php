<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Validator;
use App\Player;
use Auth;

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
            $plrMdl = Player::where(['id' => $value, 'created_by' => $param[0]])->first();
            if (!$value) {
                true;
            }
            if ($plrMdl) {
                return true;
            }
            return false;
        }, "Invalidate third party player code.");

        Validator::extend('validate_player_code', function($attr, $value, $param) {
            $loginUser = Auth::user();
            $plrMdl = Player::where(['id' => $value, 'created_by' => $loginUser->id])->first();
            // print_r($plrMdl);die;
            if (empty($plrMdl)) {
                return false;
            }
            return true;
        }, "Invalidate player code.");

        Validator::extend('same_player_code', function($attr, $value, $param) {
            $loginUser = Auth::user();
            if ($value == $param[0]) {
                return false;
            }
            return true;
        }, "Winner and loser code should not be sama.");

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
