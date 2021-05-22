<?php

namespace App\Http\Controllers;

use App\Models\Aayojana;
use Illuminate\Http\Request;
use Exception;

class AayojanaController extends Controller
{
    public function index(Request $request){
        try{
            $filterData = json_decode($request->filterData);
            $aayojana = Aayojana::
            //aarthik Barsa
            when(!empty($filterData->aarthik_barsa), function($query) use ($filterData){
                $query->whereIn('aarthik_barsa_id',$filterData->aarthik_barsa);
            })->with('aarthikBarsa')->orderBy('created_at','desc')->get();
            return response(
                [
                    'status' => 200,
                    'type' => 'success',
                    'message' => 'Aayojana loaded successfully',
                    'data' => compact('aayojana')
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
    public function saveAayojana(Request $request){
        try{
            // update
            if(isset($request->data['id'])){
                $myData= $request->data;
                unset($myData['aarthik_barsa']);
                Aayojana::find($request->data['id'])->update($myData);
                $saved=0;
            }
            // create
            else{
                Aayojana::create($request->data);
                $saved = 1;
            }

            return response(
                [
                    'status'=>200,
                    'type'=>'success',
                    'message' => 'Aayojana '.($saved ? 'created' : 'updated').' successfully',
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
