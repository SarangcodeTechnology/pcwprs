<?php

namespace App\Http\Middleware;

use App\Helpers\Check;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(Check::permission($request['permissionType'])){
            return $next($request);
        }else{
            return response(
                [
                    'status' => 401,
                    'type' => 'error',
                    'message' => 'Current user doesnt have the necessary permission.',
                ]
            );
        }
    }
}
