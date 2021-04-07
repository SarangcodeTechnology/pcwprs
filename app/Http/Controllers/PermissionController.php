<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Exception;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(){
        try{
            $permissions = Permission::orderBy('created_at','desc')->get();
            return response(
                [
                    'status' => 200,
                    'type' => 'success',
                    'message' => 'Permissions loaded successfully',
                    'data' => compact('permissions')
                ]
                );
        }
        catch(Exception $e){

        }
    }
    public function savePermissionData(Request $request){
        try{
            // update
            if(isset($request->data['id'])){
                $permission = Permission::find($request->data['id']);
                $permission->name = $request->data['name'];
                $permission->update();
                $saved=0;
            }
            // create
            else{
                $permission = new Permission();
                $permission->name = $request->data['name'];
                $permission->save();
                $saved = 1;
            }

            return response(
                [
                    'status'=>200,
                    'type'=>'success',
                    'message' => 'Permissions '.($saved ? 'created' : 'updated').' successfully',
                ]
                );
        }
        catch(Exception $e){

        }
    }
}
