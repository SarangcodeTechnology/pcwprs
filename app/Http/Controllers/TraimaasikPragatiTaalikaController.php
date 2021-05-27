<?php

namespace App\Http\Controllers;

use App\Models\KriyakalapLakshya;
use App\Models\KriyakalapMaasikPragati;
use App\Models\KriyakalapTraimaasikPragati;
use App\Models\Submission;
use App\Models\Traimaasik;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class TraimaasikPragatiTaalikaController extends Controller
{
    public function index(Request $request){
        try {
            $filterData = json_decode($request->filterData);
            $aayojanaID= $filterData->aayojana;
            $traimaasikID = $filterData->traimaasik;
            $traimaasik = Traimaasik::find($traimaasikID);
            $initial = $traimaasik->initial;
            $karyalayaID = $filterData->kaaryalaya;

            $traiMaasikPragatiTaalika = KriyakalapLakshya::where('aayojana_id', $aayojanaID)
                ->where(function ($query) use ($initial) {
                    return $query->where($initial . '_traimasik_lakshya_pariman', '>', 0)->orWhere($initial . '_traimasik_lakshya_budget', '>', 0);
                })
                ->where('kaaryalaya_id',$karyalayaID)
                ->select('id', 'name', 'kriyakalap_code', $initial . '_traimasik_lakshya_pariman', $initial . '_traimasik_lakshya_budget')
                ->with(['traimaasikPragati' => function ($query) use ($traimaasikID,$karyalayaID) {
                    $query->where('traimaasik_id', $traimaasikID)->where('kaaryalaya_id',$karyalayaID);
                }])
                ->get();
            $editable = true;
            $submitted = Submission::where('kaaryalaya_id',$karyalayaID)->where('aayojana_id',$aayojanaID)->where('traimaasik_id',$traimaasikID)->where('submitted',1)->first() ? true : false;
            if($submitted){
                $editable = Submission::where('kaaryalaya_id',$karyalayaID)->where('aayojana_id',$aayojanaID)->where('traimaasik_id',$traimaasikID)->where('submitted',1)->where('editable',1)->first() ? true : false;
            }
            $requested = Submission::where('kaaryalaya_id',$karyalayaID)->where('aayojana_id',$aayojanaID)->where('traimaasik_id',$traimaasikID)->where('submitted',1)->where('requested',1)->first() ? true : false;

            $headers = [
                0 => [
                    'text' => 'कृयाकलाप कोड',
                    'value' => 'name_with_kriyakalap_code'
                ],
                1 => [
                    'text' => $traimaasik->name . ' परिमाण',
                    'value' => $traimaasik->initial . '_traimasik_lakshya_pariman'
                ],
                2 => [
                    'text' => $traimaasik->name . ' बजेट',
                    'value' => $traimaasik->initial . '_traimasik_lakshya_budget'
                ],
                3 => [
                    'text' => $traimaasik->name.' प्रगती परिमाण',
                    'value' => 'traimaasik_pragati.pariman'
                ],
                4 => [
                    'text' => $traimaasik->name.' प्रगती खर्च',
                    'value' => 'traimaasik_pragati.kharcha'
                ]
            ];
            return response(
                [
                    'status' => 200,
                    'type' => 'success',
                    'message' => 'Aayojana loaded successfully',
                    'data' => compact('traiMaasikPragatiTaalika','headers','editable','submitted','requested')
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

    public function saveTraimaasikPragatiTaalika(Request $request){
        try {
            foreach ($request->items as $item) {
                $traiMaasikPragati = $item['traimaasik_pragati'];
                if (isset($traiMaasikPragati['id'])) {
                    KriyakalapTraimaasikPragati::find($traiMaasikPragati['id'])->update($traiMaasikPragati);
                } else {
                    KriyakalapTraimaasikPragati::create($traiMaasikPragati);
                }
            }
            $submitted = false;
            $editable = true;
            if($request->submitted){
                // if row is already present of such data
                $submission = Submission::where('traimaasik_id',$request->filterData['traimaasik'])->where('aayojana_id',$request->filterData['aayojana'])->where('kaaryalaya_id',$request->filterData['kaaryalaya'])->first();
                if($submission){
                    $submission->submitted = 1;
                    $submission->editable = 0;
                    $submission->update();
                }
                // if row is not present of such data, create one
                else{
                    $submission = new Submission();
                    $submission->submitted_by = $request->filterData['user'];
                    $submission->aayojana_id = $request->filterData['aayojana'];
                    $submission->traimaasik_id = $request->filterData['traimaasik'];
                    $submission->kaaryalaya_id = $request->filterData['kaaryalaya'];
                    $submission->submitted = 1;
                    $submission->editable = 0;
                    $submission->save();
                }
                $editable = false;
                $submitted = true;
            }

            return response(
                [
                    'status' => 200,
                    'type' => 'success',
                    'data' => compact('submitted','editable'),
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

    public function importFromMaasikPragati(Request $request){
        try {

            $summations = $this->getSum(json_decode($request->filterData));

            return response(
                [
                    'status' => 200,
                    'type' => 'success',
                    'data' =>compact('summations'),
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

    private function getSum($filterData){
        $aayojanaID= $filterData->aayojana;
        $userID = $filterData->user;
        $traimaasikID = $filterData->traimaasik;
        $karyalayaID = $filterData->kaaryalaya;
        $traimaasik = Traimaasik::find($traimaasikID);
        $mahina =  $traimaasik->mahina->pluck('id');

        $items = KriyakalapMaasikPragati::whereIn('mahina_id',$mahina)->where('kaaryalaya_id',$karyalayaID)->orderBy('kriyakalap_lakshya_id')->get();

        $data = [];
        $kriyakalap_lakshya_id = 0;
        $nextCount = -1;
        foreach($items as $item){
            if($item->kriyakalap_lakshya_id != $kriyakalap_lakshya_id){
                $kriyakalap_lakshya_id = $item->kriyakalap_lakshya_id;
                $nextCount++;
                $data[$nextCount]['id'] = $kriyakalap_lakshya_id;
                $data[$nextCount]['traimaasik_pragati']['pariman'] = 0;
                $data[$nextCount]['traimaasik_pragati']['kharcha'] = 0;
                $data[$nextCount]['traimaasik_pragati']['kaaryalaya_id'] = $karyalayaID;
                $data[$nextCount]['traimaasik_pragati']['kriyakalap_lakshya_id'] = $kriyakalap_lakshya_id;
                $data[$nextCount]['traimaasik_pragati']['traimaasik_id'] = $traimaasikID;
                $data[$nextCount]['traimaasik_pragati']['user_id'] = $userID;

            }
            $data[$nextCount]['traimaasik_pragati']['pariman']+=$item->pariman;
            $data[$nextCount]['traimaasik_pragati']['kharcha']+=$item->kharcha;
        }
        return $data;
    }
}
