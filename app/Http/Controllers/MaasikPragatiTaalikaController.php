<?php

namespace App\Http\Controllers;

use App\Models\Aayojana;
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

    private function calculateVaar($budget,$totalSum){
        return ($budget/$totalSum)*100;
    }

    private function getSpecificData($maasikPragati, $mahina, $totalBaarsikLakshyaBudget)
    {
        foreach ($maasikPragati as $item) {
            $item['total_till_now']['pariman'] = 0;
            $item['total_till_now']['kharcha'] = 0;
            $item['baarsik_lakshya_vaar'] = round($this->calculateVaar($item['baarsik_lakshya_budget'] ,$totalBaarsikLakshyaBudget),3);

            // if maasik_pragati is not null then calculate vaar else set all to 0
            if ($item['maasik_pragati']) {
                $item['maasik_pragati']['vaarit'] = round(($item['baarsik_lakshya_vaar']/$item['baarsik_lakshya_pariman'])*$item['maasik_pragati']['pariman'],3);
            } else {
                $item['maasik_pragati']['pariman'] = 0;
                $item['maasik_pragati']['kharcha'] = 0;
                $item['maasik_pragati']['vaarit'] = 0;
            }

            // to calculate total till now datas
            foreach ($item['maasik_pragatis'] as $subitem) {
                $item['total_till_now']['pariman'] += $subitem['pariman'];
                $item['total_till_now']['kharcha'] += $subitem['kharcha'];

                //$item['vautik_pragati']['this_month'] =
                // unset($item['maasik_pragatis']);
            }
            $item['total_till_now']['vaarit'] = round(($item['baarsik_lakshya_vaar']/$item['baarsik_lakshya_pariman'])*$item['total_till_now']['pariman'],3);
            $item['vautik_pragati'] = round(($item['total_till_now']['pariman'] / $item['baarsik_lakshya_pariman'])*100,2);

            $myData[] = $item;
        }
        // chalu data
        $items['chalu']['data']= collect($myData)->where('kharcha_prakar','चालु');
        $items['chalu']['totals']['baarsik_lakshya_vaar'] = round($items['chalu']['data']->sum('baarsik_lakshya_vaar'),3);
        $items['chalu']['totals']['baarsik_lakshya_budget'] = round($items['chalu']['data']->sum('baarsik_lakshya_budget'),3);
        $items['chalu']['totals']['maasik_pragati_vaarit'] = round($items['chalu']['data']->sum('maasik_pragati.vaarit'),3);
        $items['chalu']['totals']['maasik_pragati_kharcha'] = round($items['chalu']['data']->sum('maasik_pragati.kharcha'),3);
        $items['chalu']['totals']['total_till_now_vaarit'] = round($items['chalu']['data']->sum('total_till_now.vaarit'),3);
        $items['chalu']['totals']['total_till_now_kharcha'] = round($items['chalu']['data']->sum('total_till_now.kharcha'),3);
        // punjigat data
        $items['punjigat']['data'] = collect($myData)->where('kharcha_prakar','पूँजीगत');
        $items['punjigat']['totals']['baarsik_lakshya_vaar'] = round($items['punjigat']['data']->sum('baarsik_lakshya_vaar'),3);
        $items['punjigat']['totals']['baarsik_lakshya_budget'] = round($items['punjigat']['data']->sum('baarsik_lakshya_budget'),3);
        $items['punjigat']['totals']['maasik_pragati_vaarit'] = round($items['punjigat']['data']->sum('maasik_pragati.vaarit'),3);
        $items['punjigat']['totals']['maasik_pragati_kharcha'] = round($items['punjigat']['data']->sum('maasik_pragati.kharcha'),3);
        $items['punjigat']['totals']['total_till_now_vaarit'] = round($items['punjigat']['data']->sum('total_till_now.vaarit'),3);
        $items['punjigat']['totals']['total_till_now_kharcha'] = round($items['punjigat']['data']->sum('total_till_now.kharcha'),3);

        $items['totals']['baarsik_lakshya_vaar'] = round(collect($myData)->sum('baarsik_lakshya_vaar'),3);
        $items['totals']['baarsik_lakshya_budget'] = round(collect($myData)->sum('baarsik_lakshya_budget'),3);
        $items['totals']['maasik_pragati_vaarit'] = round(collect($myData)->sum('maasik_pragati.vaarit'),3);
        $items['totals']['maasik_pragati_kharcha'] = round(collect($myData)->sum('maasik_pragati.kharcha'),3);
        $items['totals']['total_till_now_vaarit'] = round(collect($myData)->sum('total_till_now.vaarit'),3);
        $items['totals']['total_till_now_kharcha'] = round(collect($myData)->sum('total_till_now.kharcha'),3);
        return $items;
    }

    public function report(Request $request){
        try {
            $filterData = json_decode($request->filterData);
            $aayojanaID= $filterData->aayojana;
            $mahinaID = $filterData->mahina;
            $mahina = Mahina::find($mahinaID);
            $initial = $mahina->traimaasik->initial;
            $traimaasik = $mahina->traimaasik->name;
            $karyalayaID = $filterData->kaaryalaya;

            $maasikPragati= KriyakalapLakshya::where('aayojana_id', $aayojanaID)
                ->where('kaaryalaya_id',$karyalayaID)
                ->where(function ($query) use ($initial) {
                    return $query->where($initial . '_traimasik_lakshya_pariman', '>', 0)->orWhere($initial . '_traimasik_lakshya_budget', '>', 0);
                })
                ->with(['maasikPragati'=>function($query) use ($mahinaID){
                    return $query->where('mahina_id',$mahinaID);
                }])
                ->with(['maasikPragatis'=>function($query) use ($mahinaID){

                    return $query->where('mahina_id','<=',$mahinaID);
                }])
                ->get();

            //for total baarsiklakshya budget we shouldn't filter
            $maasikPragatiUnfiltered= KriyakalapLakshya::where('aayojana_id', $aayojanaID)
                ->where('kaaryalaya_id',$karyalayaID)
                ->get();
            $totalBaarsikLakshyaBudget = $maasikPragatiUnfiltered->sum('baarsik_lakshya_budget');

            $maasikPragati = json_decode(json_encode($maasikPragati),true);
            $maasikPragatiReport = [
                'aayojana' => Aayojana::find($aayojanaID)->name,
                'month' => Mahina::find($mahinaID)->name,
                'items' => $this->getSpecificData($maasikPragati,$mahinaID,$totalBaarsikLakshyaBudget)
            ];
            return response(
                [
                    'status' => 200,
                    'type' => 'success',
                    'message' => 'Aayojana loaded successfully',
                    'data' => compact('maasikPragatiReport')
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
                    unset($maasikPragati['mahina']);
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
