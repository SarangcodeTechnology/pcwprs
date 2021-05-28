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


    public function trial()
    {
//        return view('test');

        $aayojanaID= 1;
        $traimaasikID = 1;
        $karyalayaID = 1;

        $traimaasikPragati= KriyakalapLakshya::where('aayojana_id', $aayojanaID)
            ->where('kaaryalaya_id',$karyalayaID)
//                ->select('id', 'name', 'kriyakalap_code')
            ->with(['traimaasikPragati'=>function($query) use ($traimaasikID){
                return $query->where('traimaasik_id',$traimaasikID);
            }])
            ->with(['traimaasikPragatis'=>function($query) use ($traimaasikID){

                return $query->where('traimaasik_id','<=',$traimaasikID);
            }])
            ->get();
         $totalBaarsikLakshyaBudget = $traimaasikPragati->sum('baarsik_lakshya_budget');

        $traimaasikPragati = json_decode(json_encode($traimaasikPragati),true);
        return $this->getSpecificData($traimaasikPragati,$totalBaarsikLakshyaBudget);

        // share data to view
        $path = public_path() . '/pdf';
        view()->share('data', $data);
        $pdf = PDF::loadView('pdf_view', $data);
        $pdf->save($path . '/my_pdf_name.pdf', 'utf8mb4_unicode_ci');
        return response()->download($path . '/my_pdf_name.pdf');


        $traimaasik = Traimaasik::find(1);
        $mahina = $traimaasik->mahina->pluck('id');
        $kaaryalaya_id = 1;
        $items = KriyakalapMaasikPragati::whereIn('mahina_id', $mahina)->where('kaaryalaya_id', $kaaryalaya_id)->orderBy('kriyakalap_lakshya_id')->get();
        $data = [];
        $kriyakalap_lakshya_id = 0;
        $nextCount = -1;
        foreach ($items as $item) {
            if ($item->kriyakalap_lakshya_id != $kriyakalap_lakshya_id) {
                $kriyakalap_lakshya_id = $item->kriyakalap_lakshya_id;
                $nextCount++;
                $data[$nextCount]['id'] = $kriyakalap_lakshya_id;
                $data[$nextCount]['traimaasik_pragati']['pariman'] = 0;
                $data[$nextCount]['traimaasik_pragati']['kharcha'] = 0;
                $data[$nextCount]['traimaasik_pragati']['kaaryalaya_id'] = $kaaryalaya_id;
                $data[$nextCount]['traimaasik_pragati']['kriyakalap_lakshya_id'] = $kriyakalap_lakshya_id;

            }
            $data[$nextCount]['traimaasik_pragati']['pariman'] += $item->pariman;
            $data[$nextCount]['traimaasik_pragati']['kharcha'] += $item->kharcha;
        }
        return $data;
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
