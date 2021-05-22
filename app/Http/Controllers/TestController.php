<?php

namespace App\Http\Controllers;

use App\Helpers\CollectionHelper;
use App\Models\Aayojana;
use App\Models\CfData;
use App\Models\KriyakalapLakshya;
use App\Models\Mahina;
use App\Models\Province;
use App\Models\LocalLevel;
use App\Models\District;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Traimaasik;
use App\Models\User;
use App\Module\Permission as ModulePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TestController extends Controller
{
    public function trial()
    {
        $traimaasik = Traimaasik::find(1);
        $mahina =  $traimaasik->mahina->pluck('id');
        
        return User::first()->kriyakalapMaasikPragati;
        $inital = "pahilo";
        $mahina = 1;
        $mahinaModel = Mahina::find($mahina);
       return $data = KriyakalapLakshya::where('aayojana_id', 4)
            ->where(function ($query) use ($inital) {
                return $query->where($inital . '_traimasik_lakshya_pariman', '>', 0)->orWhere($inital . '_traimasik_lakshya_budget', '>', 0);
            })
            ->select('id', 'name', 'kriyakalap_code', $inital . '_traimasik_lakshya_pariman', $inital . '_traimasik_lakshya_budget')->with(['maasikPragati' => function ($query) use ($mahina) {
                $query->where('mahina_id', $mahina);
            }])
            ->take(5)
            ->get();
        return $header = [
            0 => [
                'name' => 'कृयाकलाप कोड',
                'value' => 'name_with_kriyakalap_code'
            ],
            1 => [
                'name' => $mahinaModel->traimaasik->name . ' परिमाण',
                'value' => $mahinaModel->traimaasik->initial . '_traimasik_lakshya_pariman'
            ],
            2 => [
                'name' => $mahinaModel->traimaasik->name . ' बजेट',
                'value' => $mahinaModel->traimaasik->initial . '_traimasik_lakshya_budget'
            ],
            3 => [
                'name' => 'मासिक प्रगती परिमाण',
                'value' => 'maasik_pragati.pariman'
            ],
            4 => [
                'name' => 'मासिक प्रगती खर्च',
                'value' => 'maasik_pragati.kharcha'
            ]
        ];
        $array = [
            0 => [
                'month' => 'बैशाख',
                'trimester' => 4,
            ],
            1 => [
                'month' => 'जेठ',
                'trimester' => 4,
            ],
            2 => [
                'month' => 'असार',
                'trimester' => 4,
            ],
            3 => [
                'month' => 'श्रावण',
                'trimester' => 1,
            ],
            4 => [
                'month' => 'भदौ',
                'trimester' => 1,
            ],
            5 => [
                'month' => 'आश्विन',
                'trimester' => 1,
            ],
            6 => [
                'month' => 'कार्तिक',
                'trimester' => 2,
            ],
            7 => [
                'month' => 'मंसिर',
                'trimester' => 2,
            ],
            8 => [
                'month' => 'पुष',
                'trimester' => 2,
            ],
            9 => [
                'month' => 'माघ',
                'trimester' => 3,
            ],
            10 => [
                'month' => 'फाल्गुन',
                'trimester' => 3,
            ],
            11 => [
                'month' => 'चैत्र',
                'trimester' => 3,
            ],

        ];
        foreach ($array as $item) {
            $mahina = new Mahina();
            $mahina->name = $item['month'];
            $mahina->traimaasik_id = $item['trimester'];
            $mahina->save();
        }
        $traimasik = [
            'पहिलो त्रैमासिक',
            'दोश्रो त्रैमासिक',
            'तेश्रो त्रैमासिक',
            'चौधो त्रैमासिक',
        ];
        foreach ($traimasik as $item) {
            $traima = new Traimaasik();
            $traima->name = $item;
            $traima->save();
        }
        return done;

    }

    public function postTrial(Request $request)
    {
        $key = [
            "kriyakalap_code",
            "name",
            "kharcha_sirsak",
            "ikai",
            "aayojana_kul_kriyakalap_pariman",
            "aayojana_kul_kriyakalap_vaar",
            "aayojana_kul_kriyakalap_laagat",
            "gata_aarthik_barsa_sammako_pariman",
            "gata_aarthik_barsa_sammako_vaar",
            "gata_aarthik_barsa_sammako_laagat",
            "baarsik_lakshya_pariman",
            "baarsik_lakshya_vaar",
            "baarsik_lakshya_budget",
            "pahilo_traimasik_lakshya_pariman",
            "pahilo_traimasik_lakshya_vaar",
            "pahilo_traimasik_lakshya_budget",
            "dosro_traimasik_lakshya_pariman",
            "dosro_traimasik_lakshya_vaar",
            "dosro_traimasik_lakshya_budget",
            "tesro_traimasik_lakshya_pariman",
            "tesro_traimasik_lakshya_vaar",
            "tesro_traimasik_lakshya_budget",
            "chautho_traimasik_lakshya_pariman",
            "chautho_traimasik_lakshya_vaar",
            "chautho_traimasik_lakshya_budget",
            "kaifiyat"
        ];
        $csv = file_get_contents($request->myfile);
        $array = array_map("str_getcsv", explode("\n", $csv));
        $arrayCount = 0;
        $val = '';
        foreach ($array as $item) {
            $dots = explode('.', $item[0]);
            if ($item[1] == "" && $item[2] == "" && $item[3] == "" && $item[4] == "" && $item[5] == "" && $item[6] == "" && $item[7] == "" && $item[8] == "" && $item[9] == "" && $item[10] == "") {
                $val = $item[0];
            }
            if (isset($dots[3])) {
                $combinedArray = array_combine($key, $item);
                $combinedArray['kharcha_prakar'] = $val;
                $data[] = $combinedArray;
                $arrayCount++;
            }
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
