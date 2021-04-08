<?php

namespace App\Http\Controllers\api\v1;

use App\Helpers\CollectionHelper;
use App\Http\Controllers\Controller;
use App\Models\CfData;
use App\Models\ForestCondition;
use App\Models\ForestType;
use App\Models\Permission;
use App\Models\Physiography;
use App\Models\Province;
use App\Models\Role;
use App\Models\SubDivision;
use App\Models\VegetationType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataController extends Controller
{
    public function CFData(Request $request)
    {
        try {
            if ($request->search) {
                $search = $request->search;
                $cfData = CfData::with(['district', 'province', 'localLevel', 'physiography', 'subdivision', 'vegetationType', 'forestType', 'forestCondition'])->get();
                $cfData = CollectionHelper::paginate($cfData->filter(function ($item) use ($search) {
                    return false !== stripos($item, $search);
                })->values(), $request->totalItems);
            } else {
                $cfData = CfData::with(['district', 'province', 'localLevel', 'physiography', 'subdivision', 'vegetationType', 'forestType', 'forestCondition'])->orderBy('created_at','desc')->paginate($request->totalItems);
            }
            return response([
                'status' => 200,
                'type' => 'success',
                'message' => 'Data loaded successfully.',
                'data' => [
                    'cfData' => $cfData,
                    'totalCfData' => $cfData['total'],
                ]
            ]);
        } catch (Exception $e) {
            return response([
                'status' => $e->getCode(),
                'type' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function search(Request $request)
    {
        try {
            $cfData = CfData::with(['district', 'province', 'localLevel', 'physiography', 'subdivision', 'vegetationType', 'forestType', 'forestCondition'])->get();
            $paginatedData = CollectionHelper::paginate($cfData->where('district.name', 'Makwanpur'), $request->totalItems);
            return response([
                'status' => 200,
                'type' => 'success',
                'message' => 'Data loaded successfully.',
                'data' => [
                    'cfData' => $paginatedData,
                    'totalCfData' => $paginatedData['total'],
                ]
            ]);
        } catch (Exception $e) {
            return response([
                'status' => $e->getCode(),
                'type' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function saveCfData(Request $request){
        try {
            CfData::create($request->data);
            return response([
                'status' => 200,
                'type' => 'success',
                'message' => 'Data loaded successfully.'
            ]);
        } catch (Exception $e) {
            return response([
                'status' => $e->getCode(),
                'type' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function deleteCfData(Request $request){
        try {
            CfData::destroy($request->id);
            return response([
                'status' => 200,
                'type' => 'success',
                'message' => 'Data Deleted Successfully'
            ]);
        } catch (Exception $e) {
            return response([
                'status' => $e->getCode(),
                'type' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function loadResources(Request $request)
    {
        try {
            $roles = Role::with('permissions')->get();
            $permissions = Permission::all();
            return response([
                'status' => 200,
                'type' => 'success',
                'message' => 'Resources loaded successfully',
                'data' => compact('roles','permissions')
            ]);
        } catch (Exception $e) {
            return response([
                'status' => $e->getCode(),
                'type' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
