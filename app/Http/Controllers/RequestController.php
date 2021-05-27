<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function index(Request $request){
        try{
            $requests = Submission::where('requested',1)->with(['kaaryalaya','aayojana','requestedBy','mahina','traimaasik'])->get();
            return response(
                [
                    'status' => 200,
                    'type' => 'success',
                    'data' => compact('requests'),
                    'message' => 'Request sent successfully',
                ]
            );
        }catch (Exception $e) {
            return response([
                'status' => $e->getCode(),
                'type' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function editRequest(Request $request){
        try {
            // if row is already present of such data
            $requested = false;
            if(isset($request->filterData['mahina'])){
                $formRequest = Submission::where('mahina_id',$request->filterData['mahina'])->where('aayojana_id',$request->filterData['aayojana'])->where('kaaryalaya_id',$request->filterData['kaaryalaya'])->first();
            }
            if(isset($request->filterData['traimaasik'])){
                $formRequest = Submission::where('traimaasik_id',$request->filterData['traimaasik'])->where('aayojana_id',$request->filterData['aayojana'])->where('kaaryalaya_id',$request->filterData['kaaryalaya'])->first();
            }
            if($formRequest){
                $formRequest->requested_by = $request->filterData['user'];
                $formRequest->requested = 1;
                $formRequest->update();
                $requested = true;
            }
            return response(
                [
                    'status' => 200,
                    'type' => 'success',
                    'data' => compact('requested'),
                    'message' => 'Request sent successfully',
                ]
            );
        }catch (Exception $e) {
            return response([
                'status' => $e->getCode(),
                'type' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function approveRequest(Request $request){
        try {
            if(isset($request->mahina_id)){
                $formRequest = Submission::where('mahina_id',$request->mahina_id)->where('aayojana_id',$request->aayojana_id)->where('kaaryalaya_id',$request->kaaryalaya_id)->first();

            }
            else{
                $formRequest = Submission::where('traimaasik_id',$request->traimaasik_id)->where('aayojana_id',$request->aayojana_id)->where('kaaryalaya_id',$request->kaaryalaya_id)->first();

            }
            $formRequest->editable = 1;
            $formRequest->requested = 0;
            $formRequest->update();
            return response(
                [
                    'status' => 200,
                    'type' => 'success',
                    'message' => 'Request sent successfully',
                ]
            );
        }
        catch (Exception $e) {
            return response([
                'status' => $e->getCode(),
                'type' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
