<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CustomeMiddlware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {        
        $action = $request->route()->getAction();
        $controller = class_basename($action['controller']);
        list($controller, $action) = explode('@', $controller);
        
        if ((Auth::check())) {
            $user = Auth::user();
            if ($user->rl == 1) {
                return $next($request);
            } else {
                if ($controller == 'UsersController') {
                    if ($request->route('user') != null ) {
                        if ($user->id == $request->route('user')->created_by) {
                            return $next($request);    
                        } else {
                            return redirect('/admin/users')->with('error','You din\'t have access.');        
                        }
                    }                    
                } else if ($controller == 'PlayerController') {
                    if (in_array($action, config('app.actions.player'))) {
                        $playerModel = Player::select('created_by')->find($request->route('id'));
                        if (isset($playerModel->created_by) && $playerModel->created_by != $user->id) {
                            return redirect()->route('player.list');
                        }
                    }
                }
                return $next($request);
            }
        }

        return redirect('login')->with('error','Session expired.');
    }
}
