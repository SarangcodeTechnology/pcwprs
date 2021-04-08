<?php

namespace App\Http\Controllers;

use App\Helpers\CollectionHelper;
use App\Models\CfData;
use App\Models\Province;
use App\Models\LocalLevel;
use App\Models\District;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Module\Permission as ModulePermission;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(){

        $roles = Role::with('permissions')->get()->take(3);
        $selectedRolePermissions = [];
        foreach($roles as $role){
            foreach($role['permissions'] as $item){
                array_push($selectedRolePermissions,$item);
            }
        }
        $selectedRolePermissions = array_unique($selectedRolePermissions,SORT_REGULAR);
        $selectedRolePermissionIDs = array_column($selectedRolePermissions, 'id');
        $additionalPermissions = Permission::whereNotIn('id',$selectedRolePermissionIDs)->get();
        return $selectedRolePermissions;
    }
}
