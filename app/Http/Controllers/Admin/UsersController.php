<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Player;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUsersRequest;
use App\Http\Requests\Admin\UpdateUsersRequest;
use Auth;
use App\Http\Controllers\Admin\PlayerController;

class UsersController extends Controller
{
    protected $playerController;
    public function __construct(PlayerController $playerController) {
        $this->playerController = $playerController;
    }
    /**
     * Display a listing of User.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {        
        $loginUser = Auth::user();
        $permission = $this->getPermition($loginUser->rl);
        // echo '<pre>'; print_r($permission);die;
        if (! Gate::allows($permission)) {
            return abort(401);
        }
        $users = User::where('created_by', $loginUser->id)->get();

        if ($loginUser->rl == 1) {
            return view('admin.users.superadmin_index', compact('users'));
        } else if ($loginUser->rl == 2) {
            return view('admin.users.admin_index', compact('users'));
        } else if ($loginUser->rl == 3) {
            $users = User::select('users.*','p.opening_balance','p.self_player')
            ->where('users.created_by', $loginUser->id)
            ->leftjoin('player as p', 'users.id', '=', 'p.user_id')
            ->get();
            // echo "<pre>";print_r($users);die;
            return view('admin.users.user_index', compact('users'));
        } else if ($loginUser->rl == 4) {
            die('Test User logged in!!!');
            return view('admin.users.index', compact('users'));
        }

    }

    /**
     * Show the form for creating new User.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $loginUser = Auth::user();
        $permission = $this->getPermition($loginUser->rl);
        if (! Gate::allows($permission)) {
            return abort(401);
        }
        // $roles = Role::get()->pluck('name', 'name');

        if ($loginUser->rl == 1) { // author create superadmin
            return view('admin.users.superadmin_create');
        } else if ($loginUser->rl == 2) { // superadmin create admin
            return view('admin.users.admin_create');
        } else if ($loginUser->rl == 3) { // admin create end-user
            $player_detail = Player::select('id','player_code','player_name')
                ->where('created_by', $loginUser->id)
                ->get()->toArray();
            
            return view('admin.users.user_create', compact('loginUser', 'player_detail'));
        }         
    }

    /**
     * Store a newly created User in storage.
     *
     * @param  \App\Http\Requests\StoreUsersRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUsersRequest $request) {        
        $loginUser = Auth::user();
        $permission = $this->getPermition($loginUser->rl);
        if (! Gate::allows($permission)) {
            return abort(401);
        }
        
        if ($loginUser->rl == 3) {
            $this->validate($request, [
                'opening_balance' => 'required|numeric|min:0',
                'player_commision_percentage' => 'between:1,20',
                // 'third_party_percentage' => 'nullable|between:0.5,'. $request['player_commision_percentage'],
            ]);
            if (trim($request['third_party_code']) != '') {
                $this->validate(request(), [
                    'third_party_code' => 'validate_third_party_code:' . $loginUser->id,
                    'third_party_percentage' => 'required|between:0.5,'. $request['player_commision_percentage'],
                ]);
            }
        }

        $requestArray = $request->all();
        $requestArray['created_by'] = $loginUser->id;
        $requestArray['rl'] = $loginUser->rl+1;
        $requestArray['pwd'] = $requestArray['password'];
        
        $status = 0;
        if (isset($requestArray['status']) && $requestArray['status'] == 'on') {
            $status = 1;
        }
        $is_flat_commision = 0;
        if (isset($requestArray['is_flat_commision']) && $requestArray['is_flat_commision'] == 'on') {
            $is_flat_commision = 1;
        }
        $requestArray['status'] = $status;
        $requestArray['is_flat_commision'] = $is_flat_commision;
        // echo '<pre>';print_r($requestArray);die;
        $user = User::create($requestArray);
        $permission = $this->getPermition($loginUser->rl+1);
        $user->assignRole([$permission]);
        if ($loginUser->rl == 2) { //superadmin create admin
            $adminid = $user->id;
            $selfPlayer = $user->replicate();
            $selfPlayer->name = 'self_' . $user->name;
            $selfPlayer->username = 'self_' . $user->username . rand(1111,9999);
            $selfPlayer->created_by = $adminid;
            $selfPlayer->save();
            // echo "<pre>";print_r((array)$selfPlayer);die;
            $selfPlayer->assignRole(['user']);
            $user->id = $selfPlayer->id;
            $user->name = $selfPlayer->name;
            $user->username = $selfPlayer->username;
            $requestArray['opening_balance'] = 0;
            $requestArray['player_commision_percentage'] = 0;
            $requestArray['third_party_code'] = '';
            $requestArray['is_flat_commision'] = 0;
            $requestArray['self_player'] = $adminid;
            $loginUser->id = $adminid;
            $this->playerController->createPlayerFromAdminPanel($requestArray, $user, $loginUser, 1);
        }
        if ($loginUser->rl == 3) {// admin create user-player
            $this->playerController->createPlayerFromAdminPanel($requestArray, $user, $loginUser, 1);
        }

        return redirect()->route('admin.users.index')->with('success', 'saved!');
    }

    /**
     * Show the form for editing User.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user) {        
        $loginUser = Auth::user();
        $permission = $this->getPermition($loginUser->rl);        
        if (! Gate::allows($permission)) {
            return abort(401);
        }
        // $roles = Role::get()->pluck('name', 'name');

        if ($loginUser->rl == 1) { // author edit superadmin
            return view('admin.users.superadmin_edit', compact('user'));
        } else if ($loginUser->rl == 2) { // superadmin edit admin
            return view('admin.users.admin_edit', compact('user'));
        } else if ($loginUser->rl == 3) { // admin edit end-user
            $user->player_model = Player::where(['user_id' => $user->id, 'created_by' => $loginUser->id])->first();
            $player_detail = Player::select('id','player_code','player_name')
                ->where('created_by', $loginUser->id)
                ->get()->toArray();
            // echo "<pre>"; print_r($user); die();
            return view('admin.users.user_edit', compact('user', 'loginUser', 'player_detail'));
        }
        // return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update User in storage.
     *
     * @param  \App\Http\Requests\UpdateUsersRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUsersRequest $request, User $user) {
        $loginUser = Auth::user();
        $permission = $this->getPermition($loginUser->rl);        
        if (! Gate::allows($permission)) {
            return abort(401);
        }

        $requestArray = $request->all();
            // echo "<pre>"; print_r($request->all()); die();

        if ($loginUser->rl == 3) {
            $this->validate($request, [                
                'player_commision_percentage' => 'between:1,20',
            ]);
            if (trim($request['third_party_code']) != '') {
                $this->validate(request(), [
                    'third_party_code' => 'validate_third_party_code:' . $loginUser->id,
                    'third_party_percentage' => 'required|between:0.5,'. $request['player_commision_percentage'],
                ]);
            }
        }

        $status = 0;
        if (isset($requestArray['status']) && $requestArray['status'] == 'on') {
            $status = 1;
        }
        $requestArray['status'] = $status;

        $is_flat_commision = 0;
        if (isset($requestArray['is_flat_commision']) && $requestArray['is_flat_commision'] == 'on') {
            $is_flat_commision = 1;
        }
        $requestArray['is_flat_commision'] = $is_flat_commision;

        if ($user->status != $requestArray['status']) { // status update All
            $this->updateAllChildUserStatus($user, $loginUser->rl, $status);
        }        
        if (isset($requestArray['password']) && trim($requestArray['password']) != '') {
            $requestArray['pwd'] = $requestArray['password'];
        }
        // echo '<pre>';print_r($requestArray);die;
        $user->update($requestArray);
        $permission = $this->getPermition($loginUser->rl+1);        
        $user->syncRoles([$permission]);

        if ($loginUser->rl == 3) {
            $this->playerController->createPlayerFromAdminPanel($requestArray,$user, $loginUser, 0);
        }

        return redirect()->route('admin.users.index')->with('success', 'Updated!');
    }

    public function show(User $user) {
        $loginUser = Auth::user();
        $permission = $this->getPermition($loginUser->rl);
        if (! Gate::allows($permission)) {
            return abort(401);
        }
        // $user->load('roles');
        // return view('admin.users.show', compact('user'));
        if ($loginUser->rl == 1) { // author view superadmin            
            return view('admin.users.superadmin_show', compact('user'));
        } else if ($loginUser->rl == 2) { // superadmin view admin
            return view('admin.users.admin_show', compact('user'));
        } else if ($loginUser->rl == 3) { // admin view end-user
            $player = Player::where('user_id', $user->id)->first();
            // print_r($player->opening_balance);die;
            return view('admin.users.user_show', compact('user', 'player'));
        }
        return view('admin.users.user_show', compact('user'));
    }

    /**
     * Remove User from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user) {
        $loginUser = Auth::user();
        $permission = $this->getPermition($loginUser->rl);
        if (! Gate::allows($permission)) {
            return abort(401);
        }
        $player = Player::where(['user_id' => $user->id, 'created_by' => $loginUser->id])->first();
        if ($player) {
            $player->delete();
            $user->delete();
            return redirect()->route('admin.users.index')->with('success', 'Deleted!');
        }

        return redirect()->route('admin.users.index')->with('error', 'Something went wrong!');
    }

    /**
     * Delete all selected User at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request) {
        $loginUser = Auth::user();
        $permission = $this->getPermition($loginUser->rl);
        if (! Gate::allows($permission)) {
            return abort(401);
        }

        User::whereIn('id', request('ids'))->delete();

        return response()->noContent();
    }

    public function getPermition($perRl) {
        if ($perRl == 1) {
            $permission = 'users_manage';
        } else if ($perRl == 2) {
            $permission = 'superadmin';
        } else if ($perRl == 3) {
            $permission = 'admin';
        } else {
            $permission = 'user';
        }
        
        return $permission;
    }

    public function updateAllChildUserStatus($user, $loginUserRl, $status) {
        $userId = [];
        if ($loginUserRl == 1) { // call when author hint
            $adminGet = User::select('id')->where('created_by', $user->id)->get();            
            foreach ($adminGet as $k => $val) {            
                $userId[] = $val->id;            
                $playerGet = User::where('created_by', $val->id)->get();
                foreach ($playerGet as $k2 => $val2) {
                    $userId[] = $val2->id;
                }
            }
        } else if ($loginUserRl == 2) { // for call when superadmin  hint
            $playerGet = User::where('created_by', $user->id)->get();
            foreach ($playerGet as $k2 => $val2) {
                $userId[] = $val2->id;
            }
        }

        if (!empty($userId)) {
            User::whereIn('id', $userId)->update(['status' => $status]);    
        }
    }

}
