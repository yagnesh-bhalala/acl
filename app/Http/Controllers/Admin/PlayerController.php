<?php

namespace App\Http\Controllers\Admin;

// use Spatie\Permission\Models\Role;
// use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
// use App\Http\Requests\Admin\StoreRolesRequest;
// use App\Http\Requests\Admin\UpdateRolesRequest;
use App\Player;

class PlayerController extends Controller
{
    /**
     * Display a listing of Role.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        
    }

    /**
     * Show the form for creating new Role.
     *
     * @return \Illuminate\Http\Response
     */
    public function createPlayerFromAdminPanel($request, $user, $loginUser, $create) {
        if ($create) {
            $player = new Player;
            $player->user_id = $user->id;
            $player->player_name = $user->name;
            $player->player_code = strtoupper($user->username);
            $player->opening_balance = $request['opening_balance'];
            $player->created_by = $loginUser->id;
            $player->save();
        } else {
            $player = Player::where('user_id', $user->id)->first();
            if ($player) {
                $player->player_name = $user->name;
                $player->player_code = strtoupper($user->username);
                $player->created_by = $loginUser->id;
                $player->save();
            }
        }
    }

    function callCorrentMethos() {

        echo "callCorrentMethos()"; die;
    }

    

}
