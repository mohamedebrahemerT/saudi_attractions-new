<?php

namespace App\Http\Middleware;

use App\Permission;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserRoles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $route_name =$request->route()->getName();
        $permission = Permission::where('name', $route_name)->first();
        $user = \Auth::user();
        if($permission != null && $permission->roles_permission->contains($user->user_role_id)){
            return $next($request);
        }
        return redirect('/home');
    }
}
