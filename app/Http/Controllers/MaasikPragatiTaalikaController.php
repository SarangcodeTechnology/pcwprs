<?php

namespace App\Http\Controllers;

use App\Models\KriyakalapLakshya;
use App\Models\KriyakalapMaasikPragati;
use App\Models\Mahina;
use App\Models\Submission;
use Illuminate\Http\Request;
use App\Models\Request as FormRequest;


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
            $editable = true;
            $submitted = Submission::where('kaaryalaya_id',$karyalayaID)->where('aayojana_id',$aayojanaID)->where('mahina_id',$mahinaID)->where('submitted',1)->first() ? true : false;
            if($submitted){
                $editable = Submission::where('kaaryalaya_id',$karyalayaID)->where('aayojana_id',$aayojanaID)->where('mahina_id',$mahinaID)->where('submitted',1)->where('editable',1)->first() ? true : false;
            }
            $requested = Submission::where('kaaryalaya_id',$karyalayaID)->where('aayojana_id',$aayojanaID)->where('mahina_id',$mahinaID)->where('submitted',1)->where('requested',1)->first() ? true : false;

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
                    'data' => compact('requested','maasikPragatiTaalika','headers','submitted','editable')
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
            $submitted = false;
            $editable = true;
            if($request->submitted){
                 // if row is already present of such data
                $submission = Submission::where('mahina_id',$request->filterData['mahina'])->where('aayojana_id',$request->filterData['aayojana'])->where('kaaryalaya_id',$request->filterData['kaaryalaya'])->first();
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
                    $submission->mahina_id = $request->filterData['mahina'];
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
}
