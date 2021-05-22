<?php

namespace App\Http\Controllers;

use App\Models\Kaaryalaya;
use Illuminate\Http\Request;

class KaaryalayaController extends Controller
{
    public function index(){
        try{
            $kaaryalaya = Kaaryalaya::orderBy('created_at','desc')->get();
            return response(
                [
                    'status' => 200,
                    'type' => 'success',
                    'message' => 'Kaaryalaya loaded successfully',
                    'data' => compact('kaaryalaya')
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
    public function saveKaaryalayaData(Request $request){
        try{
            // update
            if(isset($request->data['id'])){
                $permission = Kaaryalaya::find($request->data['id']);
                $permission->name = $request->data['name'];
                $permission->update();
                $saved=0;
            }
            // create
            else{
                $permission = new Kaaryalaya();
                $permission->name = $request->data['name'];
                $permission->save();
                $saved = 1;
            }

            return response(
                [
                    'status'=>200,
                    'type'=>'success',
                    'message' => 'Kaaryalaya '.($saved ? 'created' : 'updated').' successfully',
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
