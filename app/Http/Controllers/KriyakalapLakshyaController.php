<?php

namespace App\Http\Controllers;

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
//            KriyakalapLakshya::whereIn('id', $request->data['deletedItems'])->delete();
            if($request->replace){
                KriyakalapLakshya::where('aayojana_id',$request->aayojana)->delete();
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
        $csv = file_get_contents($file);
        $array = array_map("str_getcsv", explode("\n", $csv));
        $arrayCount = 0;
        $val = '';
        foreach ($array as $item) {
            $dots = explode('.', $item[0]);
            if ($item[1] == "" && $item[2] == "" && $item[3] == "" && $item[4] == "" && $item[5] == "" && $item[6] == "" && $item[7] == "" && $item[8] == "" && $item[9] == "" && $item[10] == "") {
                $val = $item[0];
            }
            if (isset($dots[3])) {
                $combinedArray = array_combine($key,$item);
                $combinedArray['kharcha_prakar'] = $val;
                $data[] = $combinedArray;
                $arrayCount++;
            }
        }
        return $data;
    }
}
