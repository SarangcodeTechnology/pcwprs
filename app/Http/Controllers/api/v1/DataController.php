<?php

namespace App\Http\Controllers\api\v1;

use App\Helpers\CollectionHelper;
use App\Http\Controllers\AarthikBarsaController;
use App\Http\Controllers\Controller;
use App\Models\AarthikBarsa;
use App\Models\Aayojana;
use App\Models\CfData;
use App\Models\ForestCondition;
use App\Models\ForestType;
use App\Models\Kaaryalaya;
use App\Models\Mahina;
use App\Models\Permission;
use App\Models\Physiography;
use App\Models\Province;
use App\Models\Role;
use App\Models\SubDivision;
use App\Models\Traimaasik;
use App\Models\VegetationType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataController extends Controller
{
    public function loadResources(Request $request)
    {
        try {
            $roles = Role::with('permissions')->get();
            $permissions = Permission::all();
            $aarthik_barsa = AarthikBarsa::orderBy('name')->with('aayojana')->get();
            $aayojana = Aayojana::orderBy('name')->get();
            $traimaasik = Traimaasik::all();
            $mahina = Mahina::orderBy('traimaasik_id')->get();
            $kaaryalaya = Kaaryalaya::all();
            return response([
                'status' => 200,
                'type' => 'success',
                'message' => 'Resources loaded successfully',
                'data' => compact('kaaryalaya','roles','permissions','aarthik_barsa','aayojana','mahina','traimaasik')
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
