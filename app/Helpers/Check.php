<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class Check {
    public static function permission($type){
        $user =  Auth::user();
        $permissions = [];
        $additionalPermissions =  collect($user->permissions);
        $roles = $user->roles;
        foreach($additionalPermissions as $permission){
            array_push($permissions, $permission->name);
        }
        foreach($roles as $role){
            foreach($role->permissions as $rolePermission){
                array_push($permissions, $rolePermission->name);
            }
        }

        return in_array($type,$permissions);
    }
}
