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
        return $user = Role::with('permissions')->get();
        return $roles = User::find(8)->roles;
        $permissions = ModulePermission::get(9);
        return $permissions;
    }
}
