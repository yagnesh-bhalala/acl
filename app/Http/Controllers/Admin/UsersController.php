<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUsersRequest;
use App\Http\Requests\Admin\UpdateUsersRequest;
use Auth;

class UsersController extends Controller
{
    /**
     * Display a listing of User.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $loginUser = Auth::user();
        $permission = $this->getPermition();
        // echo '<pre>'; print_r($loginUser);die;
        if (! Gate::allows($permission)) {
            return abort(401);
        }
        $users = User::where('created_by', $loginUser->id)->get();

        if ($loginUser->rl == 1) {
            return view('admin.users.superadmin_index', compact('users'));
        } else if ($loginUser->rl == 2) {
            return view('admin.users.admin_index', compact('users'));
        } else if ($loginUser->rl == 3) {
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
        $permission = $this->getPermition();
        if (! Gate::allows($permission)) {
            return abort(401);
        }
        // $roles = Role::get()->pluck('name', 'name');

        if ($loginUser->rl == 1) { // author create superadmin
            return view('admin.users.superadmin_create');
        } else if ($loginUser->rl == 2) { // superadmin create admin
            return view('admin.users.admin_create');
        } else if ($loginUser->rl == 3) { // admin create end-user
            return view('admin.users.user_create');
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
        
        $requestArray = $request->all();
        $requestArray['created_by'] = $loginUser->id;
        $requestArray['rl'] = $loginUser->rl+1;
        $requestArray['pwd'] = $requestArray['password'];
        
        $status = 0;
        if (isset($requestArray['status']) && $requestArray['status'] == 'on') {
                $status = 1;
        }
        $requestArray['status'] = $status;
        // echo '<pre>';print_r($requestArray);die;
        $user = User::create($requestArray);
        $permission = $this->getPermition($loginUser->rl+1);
        $user->assignRole([$permission]);

        return redirect()->route('admin.users.index');
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
            return view('admin.users.user_edit', compact('user'));
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
        $status = 0;
        if (isset($requestArray['status']) && $requestArray['status'] == 'on') {
                $status = 1;
        }
        $requestArray['status'] = $status;
        if (isset($requestArray['password']) && trim($requestArray['password']) != '') {
            $requestArray['pwd'] = $requestArray['password'];
        }
        // echo '<pre>';print_r($requestArray);die;
        $user->update($requestArray);
        $permission = $this->getPermition($loginUser->rl+1);        
        $user->syncRoles([$permission]);

        return redirect()->route('admin.users.index');
    }

    public function show(User $user) {
        $loginUser = Auth::user();
        $permission = $this->getPermition($loginUser->rl);
        if (! Gate::allows($permission)) {
            return abort(401);
        }

        // $user->load('roles');

        // return view('admin.users.show', compact('user'));
        return view('admin.users.superadmin_show', compact('user'));
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

        $user->delete();

        return redirect()->route('admin.users.index');
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

    public function getPermition() {
        $loginUser = Auth::user();
        if ($loginUser->rl == 1) {
            $permission = 'users_manage';
        } else if ($loginUser->rl == 2) {
            $permission = 'superadmin';
        } else {
            $permission = 'admin';    
        }
        
        return $permission;
    }

}
