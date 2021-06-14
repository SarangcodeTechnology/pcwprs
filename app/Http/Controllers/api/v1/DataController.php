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
use App\Models\User;
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
            $permissions = Permission::orderBy('name')->get();
            $aarthik_barsa = AarthikBarsa::orderBy('name')->with('aayojana')->get();
            $aayojana = Aayojana::orderBy('name')->get();
            $traimaasik = Traimaasik::all();
            $mahina = Mahina::orderBy('traimaasik_id')->get();
            $kaaryalaya = Kaaryalaya::all();
            $userPermissions = $this->permissions();
            $user  = Auth::user();
            $locked  = $user->kaaryalaya->locked;
            $formattedPermissions = $this->formattedPermissions();
            $dashboard_items = [
                1 => [
                    'title' => 'प्रयोगकर्ताहरू',
                    'subTitle' => 'कुल प्रयोगकर्ताहरूकाे संख्या',
                    'count' => $this->englishToNepali(number_format(User::all()->count())),
                    'lastEntry' => User::orderBy('created_at','desc')->first() ? User::orderBy('created_at','desc')->first()->created_at->diffForHumans() : '' ,
                ],
                2 => [
                    'title' => 'भूमिकाहरू',
                    'subTitle' => 'कुल भूमिकाहरूकाे संख्या',
                    'count' => $this->englishToNepali(number_format(Role::all()->count())),
                    'lastEntry' => Role::orderBy('created_at','desc')->first() ? Role::orderBy('created_at','desc')->first()->created_at->diffForHumans() : '' ,
                ],
                3 => [
                    'title' => 'अनुमतिहरू',
                    'subTitle' => 'कुल अनुमतिहरूकाे संख्या',
                    'count' => $this->englishToNepali(number_format(Permission::all()->count())),
                    'lastEntry' => Permission::orderBy('created_at','desc')->first() ? Permission::orderBy('created_at','desc')->first()->created_at->diffForHumans() : '' ,
                ],
                4 => [
                    'title' => 'आयोजनाहरु',
                    'subTitle' => 'कुल आयोजनाहरुको संख्या',
                    'count' => $this->englishToNepali(number_format(Aayojana::all()->count())),
                    'lastEntry' => Aayojana::orderBy('created_at','desc')->first() ? Aayojana::orderBy('created_at','desc')->first()->created_at->diffForHumans() : '' ,
                ],
                5 => [
                    'title' => 'कार्यलयहरु',
                    'subTitle' => 'कुल कार्यलयहरुहरुको संख्या',
                    'count' => $this->englishToNepali(number_format(Kaaryalaya::all()->count())),
                    'lastEntry' => Kaaryalaya::orderBy('created_at','desc')->first() ? Kaaryalaya::orderBy('created_at','desc')->first()->created_at->diffForHumans() : '' ,
                ]
            ];
            return response([
                'status' => 200,
                'type' => 'success',
                'message' => 'Resources loaded successfully',
                'data' => compact('locked','formattedPermissions','dashboard_items','userPermissions','kaaryalaya','roles','permissions','aarthik_barsa','aayojana','mahina','traimaasik')
            ]);
        } catch (Exception $e) {
            return response([
                'status' => $e->getCode(),
                'type' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
    private function englishToNepali($j){
        $find = array("1","2","3","4","5","6","7","8","9","0");
        $replace = array("१","२","३","४","५","६","७","८","९","०");

        // number lai array ma lageko
        $numarr = str_split($j,1);

        // numarr ko value lai nepali ma replace garna ko lagi, yesle array fyalxa
        $num = str_replace($find,$replace,$numarr);

        // yesle array linxa ani string return garxa
        return implode($num);
    }
    private function formattedPermissions(){
        $permissions = Permission::orderBy('name')->get();
        $formattedPermissions = [];
        foreach ($permissions as $item){
            $key = explode('-',$item->name)[0];
            $formattedPermissions[$key][] = $item;
        }
        return $formattedPermissions;
    }
    private function permissions(){
        $user = Auth::user();
        $permissions = [];
        $additionalPermissions =  collect($user->permissions);
        $roles = $user->roles;
        foreach($additionalPermissions as $permission){
            array_push($permissions, $permission->name);
        }
        foreach($roles as $role){
            foreach($role->permissions as $rolePermission){
                array_push($permissions, $rolePermission->name);
            }
        }

        return $permissions;
    }
}
