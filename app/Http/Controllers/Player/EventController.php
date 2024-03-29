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
use App\Events;
use Auth;

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

    public function tryManualGet(Request $request) {
        $loginUser = Auth::user();
        $data['player_dd'] = Player::where('created_by', $loginUser->id)->orderBy('player_name')->get()->toArray();
        $data['loginUser'] = $loginUser;
        return view('admin.player.events', $data);
    }

    public function tryManualPost(Request $request) {
        $loginUser = Auth::user();
        $this->validate($request, [
            'winner_code' => 'required|numeric|validate_player_code',
            'loser_code' => 'required|numeric|validate_player_code|same_player_code:'. $request['winner_code'],
            'amount' => 'required|numeric|min:0',
            'date_time' => 'required|date_format:d-m-Y h:i a',
            'comments' => 'max:200',
        ]);

        //insert
        $setData = [
            'winner_id' => $request['winner_code'],
            'looser_id' => $request['loser_code'],
            'amount' => $request['amount'],
            'date_time' => $request['date_time'],
        ];
        if (isset($request['comments']) && !empty($request['comments'])) {
            $setData['omments'] = $request['comments'];
        }
        $eventsId = Events::setData($setData);
        if ($eventsId) {
            
        }
        echo "<pre>"; print_r($request->all()); die;
        $data['loginUser'] = $loginUser;
        return view('admin.player.events', $data);
    }

    function callCorrentMethos() {

        echo "callCorrentMethos()"; die;
    }



    

}
