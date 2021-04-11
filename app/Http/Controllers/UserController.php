<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(){
        try{
            $user = User::with('roles','permissions')->orderBy('created_at','desc')->get();
            return response(
                [
                    'status' => 200,
                    'type' => 'success',
                    'message' => 'Users loaded successfully',
                    'data' => compact('user')
                ]
                );
        }
        catch(Exception $e){

        }
    }

    public function saveUserData(Request $request){
        try{
            // update
            if(isset($request->data['id'])){
                $user = User::find($request->data['id']);
                $user->name = $request->data['name'];
                $user->email = $request->data['email'];
                if(isset($request->data['password'])){
                    $user->password = Hash::make($request->data['password']);
                }
                $user->update();
                $user->roles()->detach();
                if(count($request->data['roles'])>0){
                    foreach($request->data['roles'] as $role){
                        $user->roles()->attach($role['id']);
                    }
                }
                $user->permissions()->detach();
                if(count($request->data['permissions'])>0){
                    foreach($request->data['permissions'] as $permission){
                        $user->permissions()->attach($permission['id']);
                    }
                }
                $saved = 0;
            }
            // save
            else{
                $user = new User();
                $user->name = $request->data['name'];
                $user->email = $request->data['email'];
                $user->password = Hash::make($request->data['password']);
                $user->save();
                if(count($request->data['roles'])>0){
                    foreach($request->data['roles'] as $role){
                        $user->roles()->attach($role['id']);
                    }
                }
                if(count($request->data['permissions'])>0){
                    foreach($request->data['permissions'] as $permission){
                        $user->permissions()->attach($permission['id']);
                    }
                }
                $saved = 1;
            }

            return response(
                [
                    'status' => 200,
                    'type' => 'success',
                    'message' => 'Users '.($saved ? 'created' : 'updated').' successfully',
                ]
                );
        }
        catch(Exception $e){
            return response([
                'status' => $e->getCode(),
                'type' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function permissionsDataForUser(Request $request){
        try{
            if($request->roles){
                // getting role id
                $roleID = array_column($request->roles,'id');
                $roles = Role::whereIn('id',$roleID)->with('permissions')->get();

                $selectedRolePermissionsIDs = [];
                foreach($roles as $role){
                    foreach($role['permissions'] as $item){
                        array_push($selectedRolePermissionsIDs,$item['id']);
                    }
                }


                // getting unique permissions
                $selectedRolePermissionsIDs = array_unique($selectedRolePermissionsIDs);
                $selectedRolePermissions = Permission::whereIn('id',$selectedRolePermissionsIDs)->get();


                // getting additional permissions using whereNotIn
                $additionalPermissions = Permission::whereNotIn('id',$selectedRolePermissionsIDs)->get();

                // selected additional permissions from frontend
                $selectedPermissions = $request->permissions ?? [];
                $selectedPermissionsIDs = array_column($selectedPermissions, 'id');

                // getting final sellected additional permissions for frontend
                $finalSelectedPermissions = Permission::whereIn('id',$selectedPermissionsIDs)->whereNotIn('id',$selectedRolePermissionsIDs)->get();




                return response(
                    [
                        'status' => 200,
                        'type' => 'success',
                        'message' => 'Users loaded successfully',
                        'data' => compact('selectedRolePermissions','additionalPermissions','finalSelectedPermissions')
                    ]
                );
            }
            else{
                $additionalPermissions = Permission::all();
                $finalSelectedPermissions = $request->permissions;
                return response(

                    [
                    'status' => 200,
                    'type' => 'success',
                    'message' => 'it is okay',
                    'data' => compact('additionalPermissions','finalSelectedPermissions')
                     ]
                );
            }

        }
        catch(Exception $e){
            return response([
                'status' => $e->getCode(),
                'type' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
