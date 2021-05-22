<?php

namespace App\Http\Controllers;

use App\Models\AarthikBarsa;
use Exception;
use Illuminate\Http\Request;

class AarthikBarsaController extends Controller
{
    public function index(){
        try{
            $aarthikBarsa = AarthikBarsa::orderBy('created_at','desc')->get();
            return response(
                [
                    'status' => 200,
                    'type' => 'success',
                    'message' => 'Aarthik Barsa loaded successfully',
                    'data' => compact('aarthikBarsa')
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
    public function saveAarthikBarsa(Request $request){
        try{
            // update
            if(isset($request->data['id'])){
                $aarthikBarsa = AarthikBarsa::find($request->data['id']);
                $aarthikBarsa->name = $request->data['name'];
                $aarthikBarsa->update();
                $saved=0;
            }
            // create
            else{
                $aarthikBarsa = new AarthikBarsa();
                $aarthikBarsa->name = $request->data['name'];
                $aarthikBarsa->save();
                $saved = 1;
            }

            return response(
                [
                    'status'=>200,
                    'type'=>'success',
                    'message' => 'Aarthik Barsa '.($saved ? 'created' : 'updated').' successfully',
                ]
                );
        }
        catch(Exception $e){

        }
    }
}
