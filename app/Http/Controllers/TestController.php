<?php

namespace App\Http\Controllers;

use App\Models\Aayojana;
use App\Models\CfData;
use App\Models\District;
use App\Models\KriyakalapLakshya;
use App\Models\KriyakalapMaasikPragati;
use App\Models\LocalLevel;
use App\Models\Mahina;
use App\Models\Permission;
use App\Models\Province;
use App\Models\Role;
use App\Models\Traimaasik;
use App\Models\User;
use Illuminate\Http\Request;
use PDF;


class TestController extends Controller
{
    private function calculateVaar($budget,$totalSum){
        return ($budget/$totalSum)*100;
    }


    private function getSpecificData($traimaasikPragati, $totalBaarsikLakshyaBudget)
    {
        foreach ($traimaasikPragati as $item) {
            $item['total_till_now']['pariman'] = 0;
            $item['total_till_now']['kharcha'] = 0;
            $item['baarsik_lakshya_vaar'] = round($this->calculateVaar($item['baarsik_lakshya_budget'] ,$totalBaarsikLakshyaBudget),3);

            // if maasik_pragati is not null then calculate vaar else set all to 0
            if ($item['traimaasik_pragati']) {
                $item['traimaasik_pragati']['vaarit'] = round(($item['baarsik_lakshya_vaar']/$item['baarsik_lakshya_pariman'])*$item['traimaasik_pragati']['pariman'],3);
            } else {
                $item['traimaasik_pragati']['pariman'] = 0;
                $item['traimaasik_pragati']['kharcha'] = 0;
                $item['traimaasik_pragati']['vaarit'] = 0;
            }
            foreach ($item['traimaasik_pragatis'] as $subitem) {
                $item['total_till_now']['pariman'] += $subitem['pariman'];
                $item['total_till_now']['kharcha'] += $subitem['kharcha'];

                //$item['vautik_pragati']['this_month'] =
                // unset($item['maasik_pragatis']);
            }
            $item['total_till_now']['vaarit'] = round(($item['baarsik_lakshya_vaar']/$item['baarsik_lakshya_pariman'])*$item['total_till_now']['pariman'],3);
            $item['vautik_pragati'] = round(($item['total_till_now']['pariman'] / $item['baarsik_lakshya_pariman'])*100,2);

            $myData[] = $item;
        }
        return $myData;
    }


    public function trial(Request $request)
    {
        //basic tasks

            $components =  KriyakalapLakshya::all()->pluck('component')->unique()->values();
            foreach(KriyakalapLakshya::all() as $item){
                $id = 3900;
                foreach($components as $component){
                    if($item->component == $component){
                        $item->component_id = $id;
                        $item->update();
                    }
                    $id++;
                }
            }
            return 'done';
             //for component ids


            $chalu =  collect(KriyakalapLakshya::where('kharcha_prakar', 'पूँजीगत')->get());
            $component_id_in_chalu = $chalu->pluck('component_id')->unique()->values();
            foreach($component_id_in_chalu as $component_id){
                $component =  $chalu->where('component_id',$component_id);
                $myChalu[$component_id]['name'] = $component->first()->component;
                $myChalu[$component_id]['items'] = $component->values();
            }
            return collect($myChalu)->values();

        // traimaasik pragrati report

    }

    public function postTrial(Request $request)
    {
        $key = [
            "kriyakalap_code",
            "name",
            "kharcha_sirsak",
            "kharcha_prakar",
            "component",
            "milestone",
            "ikai",
            "aayojana_kul_kriyakalap_pariman",
            "aayojana_kul_kriyakalap_laagat",
            "gata_aarthik_barsa_sammako_pariman",
            "gata_aarthik_barsa_sammako_laagat",
            "baarsik_lakshya_pariman",
            "baarsik_lakshya_budget",
            "pahilo_traimasik_lakshya_pariman",
            "pahilo_traimasik_lakshya_budget",
            "dosro_traimasik_lakshya_pariman",
            "dosro_traimasik_lakshya_budget",
            "tesro_traimasik_lakshya_pariman",
            "tesro_traimasik_lakshya_budget",
            "chautho_traimasik_lakshya_pariman",
            "chautho_traimasik_lakshya_budget",
            "kaifiyat"
        ];
        $csv = file_get_contents($request->myfile);
        $array = array_map("str_getcsv", explode("\n", $csv));
        $key = $array[0];
        $arrayCount = -1;
        $val = '';
        foreach ($array as $item) {
            $arrayCount++;
            if (count($item) == 1) continue;
            if ($arrayCount == 0) continue;
            $combinedArray = array_combine($key, $item);
            $data[] = $combinedArray;
        }
        return $data;
    }

    public function index()
    {
        return Aayojana::first();
        $roles = Role::with('permissions')->get()->take(3);


        $selectedRolePermissions = [];
        foreach ($roles as $role) {
            foreach ($role['permissions'] as $item) {
                array_push($selectedRolePermissions, $item);
            }
        }
        $selectedRolePermissions = array_unique($selectedRolePermissions, SORT_REGULAR);
        $selectedRolePermissionIDs = array_column($selectedRolePermissions, 'id');
        $additionalPermissions = Permission::whereNotIn('id', $selectedRolePermissionIDs)->get();
        return $selectedRolePermissions;
    }
}
