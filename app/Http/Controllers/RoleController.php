<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Exception;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(){
        try{
            $roles = Role::with('permissions')->orderBy('created_at','desc')->get();
            return response(
                [
                    'status' => 200,
                    'type' => 'success',
                    'message' => 'Roles loaded successfully',
                    'data' => compact('roles')
                ]
                );
        }
        catch(Exception $e){

        }
    }

    public function saveRoleData(Request $request){
        try{
            // update
            if(isset($request->data['id'])){
                $role = Role::find($request->data['id']);
                $role->name = $request->data['name'];
                $role->update();
                $saved=0;
                $role->permissions()->detach();
                foreach($request->data['permissions'] as $permission){
                    $role->permissions()->attach($permission['id']);
                }
            }
            // create
            else{
                $role = new Role();
                $role->name = $request->data['name'];
                $role->save();
                $saved = 1;
                foreach($request->data['permissions'] as $permission){
                    $role->permissions()->attach($permission['id']);
                }
            }

            return response(
                [
                    'status'=>200,
                    'type'=>'success',
                    'message' => 'Users '.($saved ? 'created' : 'updated').' successfully',
                ]
                );
        }
        catch(Exception $e){

        }
    }
}
