<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(){
        try{
            $user = User::with('roles')->orderBy('created_at','desc')->get();
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
                foreach($request->data['roles'] as $role){
                    $user->roles()->attach($role['id']);
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
                foreach($request->data['roles'] as $role){
                    $user->roles()->attach($role['id']);
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
}
