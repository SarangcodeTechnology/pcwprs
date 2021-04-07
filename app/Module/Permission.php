<?php

namespace App\Module;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission
{
    public static function get($id){
        $user = User::find($id);
        $additionalPermission = $user->permissions;
        $rolePermission = [];
        foreach($user->roles as $item){
            foreach($item->permissions as $item2){
                array_push($rolePermission,$item2);
            }
        }
        $permissions = [
            'additional_permissions' => $additionalPermission,
            'role_permissions' => $rolePermission
        ];
        return $permissions;
    }

}
