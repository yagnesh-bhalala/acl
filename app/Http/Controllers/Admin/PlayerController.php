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
            // print_r($user);die;
            // print_r($player);die;
            $player->user_id = $user->id;
            $player->player_name = $user->name;
            $player->player_code = strtoupper($user->username);
            $player->opening_balance = $request['opening_balance'];
            // player_commision_percentage
            // third_party_code
            // third_party_percentage
            $player->player_commision_percentage = $request['player_commision_percentage'];
            if (trim($request['third_party_code']) != '') {
                $player->third_party_code = ($request['third_party_code']);                
                $player->third_party_percentage = $request['third_party_percentage'];
                
            } else {
                $player->third_party_code = null;
                $player->third_party_percentage = null;
            }
            $player->is_flat_commision = $request['is_flat_commision'];
            if (isset($request['self_player'])) {
                $player->self_player = $request['self_player'];
            }
            $player->created_by = $loginUser->id;
            $player->save();
        } else {
            $player = Player::where('user_id', $user->id)->first();
            if ($player) {
                $player->player_name = $user->name;
                $player->player_code = strtoupper($user->username);
                
                $player->player_commision_percentage = $request['player_commision_percentage'];
                if (trim($request['third_party_code']) != '') {
                    $player->third_party_code = ($request['third_party_code']);                
                    $player->third_party_percentage = $request['third_party_percentage'];
                    
                } else {
                    $player->third_party_code = null;
                    $player->third_party_percentage = null;
                }
                $player->is_flat_commision = $request['is_flat_commision'];
                // $player->created_by = $loginUser->id;
                $player->save();
            }
        }
    }

    function callCorrentMethos() {

        echo "callCorrentMethos()"; die;
    }

    

}
