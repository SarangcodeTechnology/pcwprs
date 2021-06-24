<?php

namespace App\Http\Controllers;

use App\Helpers\Check;
use App\Models\KriyakalapLakshya;
use Exception;
use Illuminate\Http\Request;

class KriyakalapLakshyaController extends Controller
{
    public function index(Request $request)
    {
        try {
            $filterData = json_decode($request->filterData);
            $kriyakalapLakshya = KriyakalapLakshya
                ::where('aayojana_id',$filterData->aayojana)
                ->where('kaaryalaya_id',$filterData->kaaryalaya)
                //aayojana
                ->with('aayojana')
                ->orderBy('created_at', 'desc')
                ->get();
            return response(
                [
                    'status' => 200,
                    'type' => 'success',
                    'message' => 'Aayojana loaded successfully',
                    'data' => compact('kriyakalapLakshya')
                ]
            );
        } catch (Exception $e) {
            return response([
                'status' => $e->getCode(),
                'type' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function saveKriyakalaplakshya(Request $request){
        try {
            KriyakalapLakshya::whereIn('id', $request->data['deletedItems'])->delete();
            foreach ($request->data['items'] as $item) {
                if (isset($item['id'])) {
                    $myData = $item;
                    unset($myData['aayojana']);
                    KriyakalapLakshya::find($item['id'])->update($myData);
                } else {
                    KriyakalapLakshya::create($item);
                }
            }

            return response(
                [
                    'status' => 200,
                    'type' => 'success',
                    'message' => 'Kriyakalap Lakshya Updated successfully',
                ]
            );

        } catch (Exception $e) {
            return response([
                'status' => $e->getCode(),
                'type' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function uploadKriyakalapLakshya(Request $request)
    {
        try {
//          KriyakalapLakshya::whereIn('id', $request->data['deletedItems'])->delete();
            if($request->replace){
                KriyakalapLakshya::where('aayojana_id',$request->aayojana)->where('kaaryalaya_id',$request->kaaryalaya)->delete();
            }
            $items = $this->convertCSV($request->file('csvData'));
            $aayojana_id = $request->aayojana;
            $aarthik_barsa = $request->aarthikBarsa;
            $kaaryalaya_id = $request->kaaryalaya;
            $user_id = $request->user;
            foreach ($items as $item) {
                    $item['aayojana_id'] = $aayojana_id;
                    $item['kaaryalaya_id'] = $kaaryalaya_id;
                    $item['user_id'] = $user_id;
                    KriyakalapLakshya::create($item);
            }

            return response(
                [
                    'status' => 200,
                    'type' => 'success',
                    'message' => 'Kriyakalap Lakshya Uploaded successfully',
                ]
            );

        } catch (Exception $e) {
            return response([
                'status' => $e->getCode(),
                'type' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    private function convertCSV($file){
        $key = [
            "kriyakalap_code",
            "name",
            "kharcha_sirsak",
            "kharcha_prakar",
            "component",
            "component_id",
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
        $changeToEnglishArray = [
            "milestone",
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
            "chautho_traimasik_lakshya_budget"
            ];
        $csv = file_get_contents($file);
        $array = array_map("str_getcsv", explode("\n", $csv));
        $key = $array[0];
        $arrayCount = -1;
        $val = '';
        foreach ($array as $item) {
            $arrayCount++;
            if(count($item)==1) continue;
            if($arrayCount==0) continue;
            $combinedArray = array_combine($key, $item);
            $data[] = $combinedArray;
        }
        $data = collect($data)->map(function ($item) use ($changeToEnglishArray){
            foreach ($changeToEnglishArray as $key){
                    if(!$item[$key]) {
                        $item[$key] = NULL;
                    }
                    else {
                        $item[$key] = $this->nepaliToEnglish($item[$key]);
                    }

            }
           return $item;
        });
        return $data;
    }
    private function nepaliToEnglish($j){
        $find = array("१","२","३","४","५","६","७","८","९","०");
        $replace = array("1","2","3","4","5","6","7","8","9","0");

        // number lai array ma lageko
        $numarr = mb_str_split($j,1);
        // numarr ko value lai nepali ma replace garna ko lagi, yesle array fyalxa
        $num = str_replace($find,$replace,$numarr);

        // yesle array linxa ani string return garxa
        return implode($num);
    }
}
