<?php

namespace App\Http\Controllers;

use App\Models\KriyakalapLakshya;
use App\Models\KriyakalapMaasikPragati;
use App\Models\Mahina;
use Illuminate\Http\Request;

class MaasikPragatiTaalikaController extends Controller
{
    public function index(Request $request){
        try {
            $filterData = json_decode($request->filterData);
            $aayojanaID= $filterData->aayojana;
            $mahinaID = $filterData->mahina;
            $mahina = Mahina::find($mahinaID);
            $initial = $mahina->traimaasik->initial;
            $traimaasik = $mahina->traimaasik->name;
            $karyalayaID = $filterData->kaaryalaya;

            $maasikPragatiTaalika = KriyakalapLakshya::where('aayojana_id', $aayojanaID)
                ->where(function ($query) use ($initial) {
                    return $query->where($initial . '_traimasik_lakshya_pariman', '>', 0)->orWhere($initial . '_traimasik_lakshya_budget', '>', 0);
                })
                ->where('kaaryalaya_id',$karyalayaID)
                ->select('id', 'name', 'kriyakalap_code', $initial . '_traimasik_lakshya_pariman', $initial . '_traimasik_lakshya_budget')
                ->with(['maasikPragati' => function ($query) use ($mahinaID,$karyalayaID) {
                    $query->where('mahina_id', $mahinaID)->where('kaaryalaya_id',$karyalayaID);
                }])
                ->get();
            $headers = [
                0 => [
                    'text' => 'कृयाकलाप कोड',
                    'value' => 'name_with_kriyakalap_code'
                ],
                1 => [
                    'text' => $mahina->traimaasik->name . ' परिमाण',
                    'value' => $mahina->traimaasik->initial . '_traimasik_lakshya_pariman'
                ],
                2 => [
                    'text' => $mahina->traimaasik->name . ' बजेट',
                    'value' => $mahina->traimaasik->initial . '_traimasik_lakshya_budget'
                ],
                3 => [
                    'text' => 'मासिक प्रगती परिमाण',
                    'value' => 'maasik_pragati.pariman'
                ],
                4 => [
                    'text' => 'मासिक प्रगती खर्च',
                    'value' => 'maasik_pragati.kharcha'
                ]
            ];
            return response(
                [
                    'status' => 200,
                    'type' => 'success',
                    'message' => 'Aayojana loaded successfully',
                    'data' => compact('maasikPragatiTaalika','headers')
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

    public function saveMaasikPragatiTaalika(Request $request){
        try {
            foreach ($request->items as $item) {
                $maasikPragati = $item['maasik_pragati'];
                if (isset($maasikPragati['id'])) {
                    KriyakalapMaasikPragati::find($maasikPragati['id'])->update($maasikPragati);
                } else {
                    KriyakalapMaasikPragati::create($maasikPragati);
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
}
