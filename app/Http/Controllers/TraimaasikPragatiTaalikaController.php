<?php

namespace App\Http\Controllers;

use App\Models\Aayojana;
use App\Models\KriyakalapLakshya;
use App\Models\KriyakalapMaasikPragati;
use App\Models\KriyakalapTraimaasikPragati;
use App\Models\Submission;
use App\Models\Traimaasik;
use Illuminate\Http\Request;

class TraimaasikPragatiTaalikaController extends Controller
{
    public function index(Request $request)
    {
        try {
            $filterData = json_decode($request->filterData);
            $aayojanaID = $filterData->aayojana;
            $traimaasikID = $filterData->traimaasik;
            $traimaasik = Traimaasik::find($traimaasikID);
            $initial = $traimaasik->initial;
            $karyalayaID = $filterData->kaaryalaya;

            $traiMaasikPragatiTaalika = KriyakalapLakshya::where('aayojana_id', $aayojanaID)
                ->where(function ($query) use ($initial) {
                    return $query->where($initial . '_traimasik_lakshya_pariman', '>', 0)->orWhere($initial . '_traimasik_lakshya_budget', '>', 0);
                })
                ->where('kaaryalaya_id', $karyalayaID)
                ->select('id', 'name', 'kriyakalap_code', $initial . '_traimasik_lakshya_pariman', $initial . '_traimasik_lakshya_budget')
                ->with(['traimaasikPragati' => function ($query) use ($traimaasikID, $karyalayaID) {
                    $query->where('traimaasik_id', $traimaasikID)->where('kaaryalaya_id', $karyalayaID);
                }])
                ->get();
            $editable = true;
            $submitted = Submission::where('kaaryalaya_id', $karyalayaID)->where('aayojana_id', $aayojanaID)->where('traimaasik_id', $traimaasikID)->where('submitted', 1)->first() ? true : false;
            if ($submitted) {
                $editable = Submission::where('kaaryalaya_id', $karyalayaID)->where('aayojana_id', $aayojanaID)->where('traimaasik_id', $traimaasikID)->where('submitted', 1)->where('editable', 1)->first() ? true : false;
            }
            $requested = Submission::where('kaaryalaya_id', $karyalayaID)->where('aayojana_id', $aayojanaID)->where('traimaasik_id', $traimaasikID)->where('submitted', 1)->where('requested', 1)->first() ? true : false;

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
                    'text' => $traimaasik->name . ' प्रगती परिमाण',
                    'value' => 'traimaasik_pragati.pariman'
                ],
                4 => [
                    'text' => $traimaasik->name . ' प्रगती खर्च',
                    'value' => 'traimaasik_pragati.kharcha'
                ]
            ];
            return response(
                [
                    'status' => 200,
                    'type' => 'success',
                    'message' => 'Aayojana loaded successfully',
                    'data' => compact('traiMaasikPragatiTaalika', 'headers', 'editable', 'submitted', 'requested')
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


    private function calculateVaar($budget, $totalSum)
    {
        return ($budget / $totalSum) * 100;
    }

    private function getSpecificDataFilterable($traimaasikPragati, $totalBaarsikLakshyaBudget, $initial)
    {
        foreach ($traimaasikPragati as $item) {
            $item['total_till_now']['pariman'] = 0;
            $item['total_till_now']['kharcha'] = 0;
            $item['baarsik_lakshya_vaar'] = round($this->calculateVaar($item['baarsik_lakshya_budget'], $totalBaarsikLakshyaBudget), 3);
            $item[$initial . '_traimasik_lakshya_vaar'] = round($this->calculateVaar($item[$initial . '_traimasik_lakshya_budget'], $totalBaarsikLakshyaBudget), 3);
            // if traimaasik_pragati is not null then calculate vaar else set all to 0
            if ($item['traimaasik_pragati']) {
                $item['traimaasik_pragati']['vaarit'] = round(($item[$initial . '_traimasik_lakshya_vaar'] / $item[$initial . '_traimasik_lakshya_pariman']) * $item['traimaasik_pragati']['pariman'], 3);
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
            $item['total_till_now']['vaarit'] = round(($item['baarsik_lakshya_vaar'] / $item['baarsik_lakshya_pariman']) * $item['total_till_now']['pariman'], 3);
            $item['total_till_now']['pariman'] = round($item['total_till_now']['pariman'], 3);
            $item['total_till_now']['kharcha'] = round($item['total_till_now']['kharcha'], 3);
            $item['vautik_pragati'][$initial . '_traimasik'] = round(($item['traimaasik_pragati']['vaarit'] / $item[$initial . '_traimasik_lakshya_vaar']) * 100, 2) < 100 ? round(($item['traimaasik_pragati']['vaarit'] / $item[$initial . '_traimasik_lakshya_vaar']) * 100, 2) : 100;
            $item['vautik_pragati']['total_till_now'] = round(($item['total_till_now']['pariman'] / $item['baarsik_lakshya_pariman']) * 100, 2) < 100 ? round(($item['total_till_now']['pariman'] / $item['baarsik_lakshya_pariman']) * 100, 2) : 100;

            $myData[] = $item;
        }
        return $myData;
    }

    private function getSpecificData($traimaasikPragati, $totalBaarsikLakshyaBudget, $initial)
    {
        foreach ($traimaasikPragati as $item) {
            $item['total_till_now']['pariman'] = 0;
            $item['total_till_now']['kharcha'] = 0;
            $item['baarsik_lakshya_vaar'] = round($this->calculateVaar($item['baarsik_lakshya_budget'], $totalBaarsikLakshyaBudget), 3);
            $item[$initial . '_traimasik_lakshya_vaar'] = round($this->calculateVaar($item[$initial . '_traimasik_lakshya_budget'], $totalBaarsikLakshyaBudget), 3);
            // if traimaasik_pragati is not null then calculate vaar else set all to 0
            if ($item['traimaasik_pragati']) {
                $item['traimaasik_pragati']['vaarit'] = round(($item[$initial . '_traimasik_lakshya_vaar'] / $item[$initial . '_traimasik_lakshya_pariman']) * $item['traimaasik_pragati']['pariman'], 3);
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
            $item['total_till_now']['vaarit'] = round(($item['baarsik_lakshya_vaar'] / $item['baarsik_lakshya_pariman']) * $item['total_till_now']['pariman'], 3);
            $item['total_till_now']['pariman'] = round($item['total_till_now']['pariman'], 3);
            $item['total_till_now']['kharcha'] = round($item['total_till_now']['kharcha'], 3);
            $item['vautik_pragati'][$initial . '_traimasik'] = round(($item['traimaasik_pragati']['vaarit'] / $item[$initial . '_traimasik_lakshya_vaar']) * 100, 2) < 100 ? round(($item['traimaasik_pragati']['vaarit'] / $item[$initial . '_traimasik_lakshya_vaar']) * 100, 2) : 100;
            $item['vautik_pragati']['total_till_now'] = round(($item['total_till_now']['pariman'] / $item['baarsik_lakshya_pariman']) * 100, 2) < 100 ? round(($item['total_till_now']['pariman'] / $item['baarsik_lakshya_pariman']) * 100, 2) : 100;

            $myData[] = $item;
        }
        // chalu data
        $items['chalu']['data'] = collect($myData)->where('kharcha_prakar', 'चालु');
        $items['chalu']['totals']['baarsik_lakshya_vaar'] = round($items['chalu']['data']->sum('baarsik_lakshya_vaar'), 3);
        $items['chalu']['totals']['baarsik_lakshya_budget'] = round($items['chalu']['data']->sum('baarsik_lakshya_budget'), 3);
        $items['chalu']['totals'][$initial . '_traimasik_lakshya_vaar'] = round($items['chalu']['data']->sum($initial . '_traimasik_lakshya_vaar'), 3);
        $items['chalu']['totals'][$initial . '_traimasik_lakshya_budget'] = round($items['chalu']['data']->sum($initial . '_traimasik_lakshya_budget'), 3);
        $items['chalu']['totals'][$initial . '_traimasik_pragati_vaarit'] = round($items['chalu']['data']->sum('traimaasik_pragati.vaarit'), 3);
        $items['chalu']['totals'][$initial . '_traimasik_pragati_kharcha'] = round($items['chalu']['data']->sum('traimaasik_pragati.kharcha'), 3);
        $items['chalu']['totals']['total_till_now_vaarit'] = round($items['chalu']['data']->sum('total_till_now.vaarit'), 3);
        $items['chalu']['totals']['total_till_now_kharcha'] = round($items['chalu']['data']->sum('total_till_now.kharcha'), 3);
        // punjigat data
        $items['punjigat']['data'] = collect($myData)->where('kharcha_prakar', 'पूँजीगत');
        $items['punjigat']['totals']['baarsik_lakshya_vaar'] = round($items['punjigat']['data']->sum('baarsik_lakshya_vaar'), 3);
        $items['punjigat']['totals']['baarsik_lakshya_budget'] = round($items['punjigat']['data']->sum('baarsik_lakshya_budget'), 3);
        $items['punjigat']['totals'][$initial . '_traimasik_lakshya_vaar'] = round($items['punjigat']['data']->sum($initial . '_traimasik_lakshya_vaar'), 3);
        $items['punjigat']['totals'][$initial . '_traimasik_lakshya_budget'] = round($items['punjigat']['data']->sum($initial . '_traimasik_lakshya_budget'), 3);
        $items['punjigat']['totals'][$initial . '_traimasik_pragati_vaarit'] = round($items['punjigat']['data']->sum('traimaasik_pragati.vaarit'), 3);
        $items['punjigat']['totals'][$initial . '_traimasik_pragati_kharcha'] = round($items['punjigat']['data']->sum('traimaasik_pragati.kharcha'), 3);
        $items['punjigat']['totals']['total_till_now_vaarit'] = round($items['punjigat']['data']->sum('total_till_now.vaarit'), 3);
        $items['punjigat']['totals']['total_till_now_kharcha'] = round($items['punjigat']['data']->sum('total_till_now.kharcha'), 3);

        $items['totals']['baarsik_lakshya_vaar'] = round(collect($myData)->sum('baarsik_lakshya_vaar'), 3);
        $items['totals']['baarsik_lakshya_budget'] = round(collect($myData)->sum('baarsik_lakshya_budget'), 3);
        $items['totals'][$initial . '_traimasik_lakshya_vaar'] = round(collect($myData)->sum($initial . '_traimasik_lakshya_vaar'), 3);
        $items['totals'][$initial . '_traimasik_lakshya_budget'] = round(collect($myData)->sum($initial . '_traimasik_lakshya_budget'), 3);
        $items['totals'][$initial . '_traimasik_pragati_vaarit'] = round(collect($myData)->sum('traimaasik_pragati.vaarit'), 3);
        $items['totals'][$initial . '_traimasik_pragati_kharcha'] = round(collect($myData)->sum('traimaasik_pragati.kharcha'), 3);
        $items['totals']['total_till_now_vaarit'] = round(collect($myData)->sum('total_till_now.vaarit'), 3);
        $items['totals']['total_till_now_kharcha'] = round(collect($myData)->sum('total_till_now.kharcha'), 3);


        $items['prdatibedan_awadi_ko_pragati']['vaarit'] = round($items['totals'][$initial . '_traimasik_pragati_vaarit'] / $items['totals'][$initial . '_traimasik_lakshya_vaar'], 3);
        $items['prdatibedan_awadi_ko_pragati']['vautik'] = round((collect($myData)->sum('vautik_pragati.' . $initial . '_traimasik')) / (collect($myData)->count('vautik_pragati.' . $initial . '_traimasik')), 3);


        $items['pratibedan_awadi_ko_kharcha']['punjigat'] = $items['punjigat']['totals'][$initial . '_traimasik_pragati_kharcha'];
        $items['pratibedan_awadi_ko_kharcha']['chalu'] = $items['chalu']['totals'][$initial . '_traimasik_pragati_kharcha'];
        $items['pratibedan_awadi_ko_kharcha']['total'] = $items['pratibedan_awadi_ko_kharcha']['punjigat'] + $items['pratibedan_awadi_ko_kharcha']['chalu'];
        $items['pratibedan_awadi_ko_kharcha']['total_percent'] = round(($items['pratibedan_awadi_ko_kharcha']['total'] / $items['totals'][$initial . '_traimasik_lakshya_budget']) * 100, 3);
        return $items;
    }

    public function report(Request $request)
    {
        try {
            $filterData = json_decode($request->filterData);
            $aayojanaID = $filterData->aayojana;
            $traimaasikID = $filterData->traimaasik;
            $traimaasik = Traimaasik::find($traimaasikID);
            $initial = $traimaasik->initial;
            $karyalayaID = $filterData->kaaryalaya;

            $traimaasikPragati = KriyakalapLakshya::where('aayojana_id', $aayojanaID)
                ->where('kaaryalaya_id', $karyalayaID)
                ->where(function ($query) use ($initial) {
                    return $query->where($initial . '_traimasik_lakshya_pariman', '>', 0)->where($initial . '_traimasik_lakshya_budget', '>', 0);
                })
                ->with(['traimaasikPragati' => function ($query) use ($traimaasikID) {
                    return $query->where('traimaasik_id', $traimaasikID);
                }])
                ->with(['traimaasikPragatis' => function ($query) use ($traimaasikID) {

                    return $query->where('traimaasik_id', '<=', $traimaasikID);
                }])
                ->get();
            //for sum
            $traimaasikPragatiUnfiltered = KriyakalapLakshya::where('aayojana_id', $aayojanaID)
                ->where('kaaryalaya_id', $karyalayaID)
                ->get();
            $totalBaarsikLakshyaBudget = $traimaasikPragati->sum('baarsik_lakshya_budget');

            $traimaasikPragati = json_decode(json_encode($traimaasikPragati), true);
            $traimaasikPragatiReport =
                [
                    'aayojana' => Aayojana::find($aayojanaID)->name,
                    'trimester' => Traimaasik::find($traimaasikID)->name,
                    'initial' => $initial,
                    'items' => $this->getSpecificData($traimaasikPragati, $totalBaarsikLakshyaBudget, $initial)];
            return response(
                [
                    'status' => 200,
                    'type' => 'success',
                    'message' => 'Aayojana loaded successfully',
                    'data' => compact('traimaasikPragatiReport')
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

    public function saveTraimaasikPragatiTaalika(Request $request)
    {
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
            if ($request->submitted) {
                // if row is already present of such data
                $submission = Submission::where('traimaasik_id', $request->filterData['traimaasik'])->where('aayojana_id', $request->filterData['aayojana'])->where('kaaryalaya_id', $request->filterData['kaaryalaya'])->first();
                if ($submission) {
                    $submission->submitted = 1;
                    $submission->editable = 0;
                    $submission->update();
                } // if row is not present of such data, create one
                else {
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
                    'data' => compact('submitted', 'editable'),
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

    public function importFromMaasikPragati(Request $request)
    {
        try {

            $summations = $this->getSum(json_decode($request->filterData));

            return response(
                [
                    'status' => 200,
                    'type' => 'success',
                    'data' => compact('summations'),
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

    private function getSum($filterData)
    {
        $aayojanaID = $filterData->aayojana;
        $userID = $filterData->user;
        $traimaasikID = $filterData->traimaasik;
        $karyalayaID = $filterData->kaaryalaya;
        $traimaasik = Traimaasik::find($traimaasikID);
        $mahina = $traimaasik->mahina->pluck('id');

        $items = KriyakalapMaasikPragati::whereIn('mahina_id', $mahina)->where('kaaryalaya_id', $karyalayaID)->orderBy('kriyakalap_lakshya_id')->get();

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
                $data[$nextCount]['traimaasik_pragati']['kaaryalaya_id'] = $karyalayaID;
                $data[$nextCount]['traimaasik_pragati']['kriyakalap_lakshya_id'] = $kriyakalap_lakshya_id;
                $data[$nextCount]['traimaasik_pragati']['traimaasik_id'] = $traimaasikID;
                $data[$nextCount]['traimaasik_pragati']['user_id'] = $userID;

            }
            $data[$nextCount]['traimaasik_pragati']['pariman'] += $item->pariman;
            $data[$nextCount]['traimaasik_pragati']['kharcha'] += $item->kharcha;
        }
        return $data;
    }

    public function filterableReport(Request $request)
    {
        try {
            $filterData = json_decode($request->filterData);
            $aayojanaID = $filterData->aayojana;
            $traimaasikID = $filterData->traimaasik;
            $traimaasik = Traimaasik::find($traimaasikID);
            $initial = $traimaasik->initial;
            $karyalayaID = $filterData->kaaryalaya;
            $traimaasikPragati = KriyakalapLakshya::
            when(!empty($aayojanaID), function ($query) use ($aayojanaID) {
                $query->whereIn('aayojana_id', $aayojanaID);
            })
                ->when(!empty($filterData->kharchaPrakar),function($query) use ($filterData){
                    return $query->whereIn('kharcha_prakar',$filterData->kharchaPrakar);
                })
                ->where('kaaryalaya_id', $karyalayaID)
                ->where(function ($query) use ($initial) {
                    return $query->where($initial . '_traimasik_lakshya_pariman', '>', 0)->where($initial . '_traimasik_lakshya_budget', '>', 0);
                })
                ->with(['aayojana' => function($query){ return $query->with('aarthikBarsa'); },'traimaasikPragati' => function ($query) use ($traimaasikID) {
                    return $query->where('traimaasik_id', $traimaasikID);
                },'traimaasikPragatis' => function ($query) use ($traimaasikID) {

                    return $query->where('traimaasik_id', '<=', $traimaasikID);
                }])
                ->get();
            $totalBaarsikLakshyaBudget = $traimaasikPragati->sum('baarsik_lakshya_budget');
            $traimaasikPragati = json_decode(json_encode($traimaasikPragati), true);
            $headers = [
                 [
                    'text' => 'कृयाकलाप कोड',
                    'value' => 'name_with_kriyakalap_code'
                ],
                [
                    'text' => 'आर्थिक वर्ष',
                    'value'=> 'aayojana.aarthik_barsa.name'
                ],
                 [
                    'text' => 'आयोजना',
                    'value' => 'aayojana.name'
                ],
                [
                    'text'=>'खर्च शिर्षक',
                    'value'=>'kharcha_sirsak'
                ],
                 [
                    'text' => 'इकाई',
                    'value' => 'ikai'
                ],
                [
                    'text'=>'बार्षिक लक्ष परिमाण',
                    'value'=> 'baarsik_lakshya_pariman'
                ],
                [
                    'text'=>'बार्षिक लक्ष भार',
                    'value'=> 'baarsik_lakshya_vaar'
                ],
                [
                    'text'=>'बार्षिक लक्ष बजेट',
                    'value'=> 'baarsik_lakshya_budget'
                ],

                 [
                    'text' => $traimaasik->name . ' लक्षको परिमाण',
                    'value' => $traimaasik->initial . '_traimasik_lakshya_pariman'
                ],
                [
                    'text' => $traimaasik->name . ' लक्षको भार',
                    'value' => $traimaasik->initial . '_traimasik_lakshya_vaar'
                ],
                 [
                    'text' => $traimaasik->name . ' लक्षको बजेट',
                    'value' => $traimaasik->initial . '_traimasik_lakshya_budget'
                ],
                 [
                    'text' => $traimaasik->name . ' प्रगती परिमाण',
                    'value' => 'traimaasik_pragati.pariman'
                ],
                [
                    'text' => $traimaasik->name . ' प्रगती भारित',
                    'value' => 'traimaasik_pragati.vaarit'
                ],
                 [
                    'text' => $traimaasik->name . ' प्रगती खर्च',
                    'value' => 'traimaasik_pragati.kharcha'
                ],
                [
                    'text' => 'प्रतिवेदन अवधिसम्मको यस आ.व.को प्रगति परिमान',
                    'value' => 'total_till_now.pariman'
                ],
                [
                    'text' => 'प्रतिवेदन अवधिसम्मको यस आ.व.को प्रगति भारित',
                    'value' => 'total_till_now.vaarit'
                ],
                [
                    'text' => 'प्रतिवेदन अवधिसम्मको यस आ.व.को प्रगति खर्च',
                    'value' => 'total_till_now.kharcha'
                ],
                [
                    'text' => 'खर्च प्रकार',
                    'value' => 'kharcha_prakar'
                ],

            ];
            $traimaasikPragatiReport = [
                                        'headers' => $headers,
                                        'initial' => $initial,
                                        'items' => $this->getSpecificDataFilterable($traimaasikPragati, $totalBaarsikLakshyaBudget, $initial),
                                        ];
            return response(
                [
                    'status' => 200,
                    'type' => 'success',
                    'message' => 'Aayojana loaded successfully',
                    'data' => compact('traimaasikPragatiReport')
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
