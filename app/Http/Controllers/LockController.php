<?php

namespace App\Http\Controllers;

use App\Models\Kaaryalaya;
use App\Models\Lock;
use Illuminate\Http\Request;

class LockController extends Controller
{
    public function index(){
        try{
            $kaaryalaya = Kaaryalaya::with('locked')->orderBy('created_at','desc')->get();
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
    public function changeLock(Request $request){
        try{

            if($request->type=='lock'){

                $toBeLocked = Lock::where('kaaryalaya_id',$request->id)->first();

                if(!$toBeLocked){
                    $lock = new Lock();
                    $lock->kaaryalaya_id = $request->id;
                    $lock->save();
                }
                $message = 'Locked Successfully';
            }else{
                //unlock code
                $toBeLocked = Lock::where('kaaryalaya_id',$request->id)->first();
                if($toBeLocked){
                    $toBeLocked->delete();
                }
                $message = 'Unlocked Successfully';

            }
            return response(
                [
                    'status' => 200,
                    'type' => 'success',
                    'message' => $message,
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
