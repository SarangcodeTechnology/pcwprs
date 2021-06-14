<?php

namespace App\Http\Controllers;

use App\Models\Aayojana;
use App\Models\Kaaryalaya;
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
                ->select('id','ikai','component','component_id','milestone','kharcha_sirsak', 'name', 'kriyakalap_code', $initial . '_traimasik_lakshya_pariman', $initial . '_traimasik_lakshya_budget')
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
                [
                    'text' => 'कृयाकलाप कोड',
                    'value' => 'name_with_kriyakalap_code'
                ],
                [
                    'text' => 'खर्च शिर्षक',
                    'value' => 'kharcha_sirsak'
                ],
                [
                    'text' => 'इकाई',
                    'value' => 'ikai'
                ],
                [
                    'text' => $traimaasik->name . ' परिमाण',
                    'value' => $traimaasik->initial . '_traimasik_lakshya_pariman'
                ],
                [
                    'text' => $traimaasik->name . ' बजेट',
                    'value' => $traimaasik->initial . '_traimasik_lakshya_budget'
                ],
                [
                    'text' => $traimaasik->name . ' प्रगती परिमाण',
                    'value' => 'traimaasik_pragati.pariman'
                ],
                [
                    'text' => $traimaasik->name . ' प्रगती खर्च',
                    'value' => 'traimaasik_pragati.kharcha'
                ],
                [
                    'text' => 'कम्पोनेन्ट',
                    'value' => 'component'
                ],
                [
                    'text' => 'कम्पोनेन्ट आईडी',
                    'value' => 'component_id'
                ],
                [
                    'text'=> 'माईलस्टोन',
                    'value'=> 'milestone'
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
        if($totalSum==0) return 0;
        return ($budget / $totalSum) * 100;
    }


    private function getSpecificData($traimaasikPragati, $totalBaarsikLakshyaBudget, $initial,$baarsik,$ardaBaarsik)
    {
        foreach ($traimaasikPragati as $item) {
            $item['total_till_now']['pariman'] = 0;
            $item['total_till_now']['kharcha'] = 0;
            $item['baarsik_lakshya_vaar'] = round($this->calculateVaar($item['baarsik_lakshya_budget'], $totalBaarsikLakshyaBudget), 3);
            $item[$initial . '_traimasik_lakshya_vaar'] = round($this->calculateVaar($item[$initial . '_traimasik_lakshya_budget'], $totalBaarsikLakshyaBudget), 3);
            // if traimaasik_pragati is not null then calculate vaar else set all to 0
            if ($item['traimaasik_pragati']) {
                $item['traimaasik_pragati']['vaarit'] = $item[$initial . '_traimasik_lakshya_pariman']==0 ? 0 : round(($item[$initial . '_traimasik_lakshya_vaar'] / $item[$initial . '_traimasik_lakshya_pariman']) * $item['traimaasik_pragati']['pariman'], 3);
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
            $item['total_till_now']['vaarit'] = $item['baarsik_lakshya_pariman']==0 ? 0 : round(($item['baarsik_lakshya_vaar'] / $item['baarsik_lakshya_pariman']) * $item['total_till_now']['pariman'], 3);
            $item['total_till_now']['pariman'] = round($item['total_till_now']['pariman'], 3);
            $item['total_till_now']['kharcha'] = round($item['total_till_now']['kharcha'], 3);
            if($baarsik || $ardaBaarsik){
                // change all that traimaasik vaarit pariman and kharcha to total_till_now
                $item['traimaasik_pragati']['vaarit'] = $item['total_till_now']['vaarit'];
                $item['traimaasik_pragati']['pariman'] = $item['total_till_now']['pariman'];
                $item['traimaasik_pragati']['kharcha'] = $item['total_till_now']['kharcha'];
                // change all that traimaasik lakshya to baarsik lakshya
                $item[$initial . '_traimasik_lakshya_vaar'] = $item['baarsik_lakshya_vaar'];
                $item[$initial . '_traimasik_lakshya_pariman'] = $item['baarsik_lakshya_pariman'];
                $item[$initial . '_traimasik_lakshya_budget'] = $item['baarsik_lakshya_budget'];
            }

            $item['vautik_pragati'][$initial . '_traimasik'] = $item[$initial . '_traimasik_lakshya_vaar']==0 ? 0 : ( round(($item['traimaasik_pragati']['vaarit'] / $item[$initial . '_traimasik_lakshya_vaar']) * 100, 2) < 100 ? round(($item['traimaasik_pragati']['vaarit'] / $item[$initial . '_traimasik_lakshya_vaar']) * 100, 2) : 100 );
            $item['vautik_pragati']['total_till_now'] = round(($item['total_till_now']['pariman'] / $item['baarsik_lakshya_pariman']) * 100, 2) < 100 ? round(($item['total_till_now']['pariman'] / $item['baarsik_lakshya_pariman']) * 100, 2) : 100;

            $myData[] = $item;
        }

        // chalu data
        $items['chalu']['data'] = collect($myData)->where('kharcha_prakar', 'चालु')->values();
        $items['chalu']['totals']['baarsik_lakshya_vaar'] = round($items['chalu']['data']->sum('baarsik_lakshya_vaar'), 3);
        $items['chalu']['totals']['baarsik_lakshya_budget'] = round($items['chalu']['data']->sum('baarsik_lakshya_budget'), 3);
        $items['chalu']['totals'][$initial . '_traimasik_lakshya_vaar'] = round($items['chalu']['data']->sum($initial . '_traimasik_lakshya_vaar'), 3);
        $items['chalu']['totals'][$initial . '_traimasik_lakshya_budget'] = round($items['chalu']['data']->sum($initial . '_traimasik_lakshya_budget'), 3);
        $items['chalu']['totals'][$initial . '_traimasik_pragati_vaarit'] = round($items['chalu']['data']->sum('traimaasik_pragati.vaarit'), 3);
        $items['chalu']['totals'][$initial . '_traimasik_pragati_kharcha'] = round($items['chalu']['data']->sum('traimaasik_pragati.kharcha'), 3);
        $items['chalu']['totals']['total_till_now_vaarit'] = round($items['chalu']['data']->sum('total_till_now.vaarit'), 3);
        $items['chalu']['totals']['total_till_now_kharcha'] = round($items['chalu']['data']->sum('total_till_now.kharcha'), 3);

            //for component sub category
                //getting component id of specific chalu data.
                $component_id_in_chalu = collect($items['chalu']['data'])->pluck('component_id')->unique()->values();
                foreach($component_id_in_chalu as $component_id){
                    $component =  $items['chalu']['data']->where('component_id',$component_id);
                    $items['chalu']['components'][$component_id]['name'] = $component->first()['component'];
                    $items['chalu']['components'][$component_id]['id'] = $component->first()['component_id'];
                    // component items
                    $items['chalu']['components'][$component_id]['items'] = $component->values();

                    // component totals
                    $items['chalu']['components'][$component_id]['totals']['baarsik_lakshya_vaar'] = round($items['chalu']['components'][$component_id]['items']->sum('baarsik_lakshya_vaar'), 3);
                    $items['chalu']['components'][$component_id]['totals']['baarsik_lakshya_budget'] = round($items['chalu']['components'][$component_id]['items']->sum('baarsik_lakshya_budget'), 3);
                    $items['chalu']['components'][$component_id]['totals'][$initial . '_traimasik_lakshya_vaar'] = round($items['chalu']['components'][$component_id]['items']->sum($initial . '_traimasik_lakshya_vaar'), 3);
                    $items['chalu']['components'][$component_id]['totals'][$initial . '_traimasik_lakshya_budget'] = round($items['chalu']['components'][$component_id]['items']->sum($initial . '_traimasik_lakshya_budget'), 3);
                    $items['chalu']['components'][$component_id]['totals'][$initial . '_traimasik_pragati_vaarit'] = round($items['chalu']['components'][$component_id]['items']->sum('traimaasik_pragati.vaarit'), 3);
                    $items['chalu']['components'][$component_id]['totals'][$initial . '_traimasik_pragati_kharcha'] = round($items['chalu']['components'][$component_id]['items']->sum('traimaasik_pragati.kharcha'), 3);
                    $items['chalu']['components'][$component_id]['totals']['total_till_now_vaarit'] = round($items['chalu']['components'][$component_id]['items']->sum('total_till_now.vaarit'), 3);
                    $items['chalu']['components'][$component_id]['totals']['total_till_now_kharcha'] = round($items['chalu']['components'][$component_id]['items']->sum('total_till_now.kharcha'), 3);
                }
                //converting object to array of component
                $items['chalu']['components'] = collect($items['chalu']['components'])->values();


        // punjigat data
        $items['punjigat']['data'] = collect($myData)->where('kharcha_prakar', 'पूँजीगत')->values();
        $items['punjigat']['totals']['baarsik_lakshya_vaar'] = round($items['punjigat']['data']->sum('baarsik_lakshya_vaar'), 3);
        $items['punjigat']['totals']['baarsik_lakshya_budget'] = round($items['punjigat']['data']->sum('baarsik_lakshya_budget'), 3);
        $items['punjigat']['totals'][$initial . '_traimasik_lakshya_vaar'] = round($items['punjigat']['data']->sum($initial . '_traimasik_lakshya_vaar'), 3);
        $items['punjigat']['totals'][$initial . '_traimasik_lakshya_budget'] = round($items['punjigat']['data']->sum($initial . '_traimasik_lakshya_budget'), 3);
        $items['punjigat']['totals'][$initial . '_traimasik_pragati_vaarit'] = round($items['punjigat']['data']->sum('traimaasik_pragati.vaarit'), 3);
        $items['punjigat']['totals'][$initial . '_traimasik_pragati_kharcha'] = round($items['punjigat']['data']->sum('traimaasik_pragati.kharcha'), 3);
        $items['punjigat']['totals']['total_till_now_vaarit'] = round($items['punjigat']['data']->sum('total_till_now.vaarit'), 3);
        $items['punjigat']['totals']['total_till_now_kharcha'] = round($items['punjigat']['data']->sum('total_till_now.kharcha'), 3);

            //for component sub category
            //getting component id of specific punjigat data.
            $component_id_in_punjigat = collect($items['punjigat']['data'])->pluck('component_id')->unique()->values();
            foreach($component_id_in_punjigat as $component_id){
                $component =  $items['punjigat']['data']->where('component_id',$component_id);
                $items['punjigat']['components'][$component_id]['name'] = $component->first()['component'];
                $items['punjigat']['components'][$component_id]['id'] = $component->first()['component_id'];
                // component items
                $items['punjigat']['components'][$component_id]['items'] = $component->values();

                // component totals
                $items['punjigat']['components'][$component_id]['totals']['baarsik_lakshya_vaar'] = round($items['punjigat']['components'][$component_id]['items']->sum('baarsik_lakshya_vaar'), 3);
                $items['punjigat']['components'][$component_id]['totals']['baarsik_lakshya_budget'] = round($items['punjigat']['components'][$component_id]['items']->sum('baarsik_lakshya_budget'), 3);
                $items['punjigat']['components'][$component_id]['totals'][$initial . '_traimasik_lakshya_vaar'] = round($items['punjigat']['components'][$component_id]['items']->sum($initial . '_traimasik_lakshya_vaar'), 3);
                $items['punjigat']['components'][$component_id]['totals'][$initial . '_traimasik_lakshya_budget'] = round($items['punjigat']['components'][$component_id]['items']->sum($initial . '_traimasik_lakshya_budget'), 3);
                $items['punjigat']['components'][$component_id]['totals'][$initial . '_traimasik_pragati_vaarit'] = round($items['punjigat']['components'][$component_id]['items']->sum('traimaasik_pragati.vaarit'), 3);
                $items['punjigat']['components'][$component_id]['totals'][$initial . '_traimasik_pragati_kharcha'] = round($items['punjigat']['components'][$component_id]['items']->sum('traimaasik_pragati.kharcha'), 3);
                $items['punjigat']['components'][$component_id]['totals']['total_till_now_vaarit'] = round($items['punjigat']['components'][$component_id]['items']->sum('total_till_now.vaarit'), 3);
                $items['punjigat']['components'][$component_id]['totals']['total_till_now_kharcha'] = round($items['punjigat']['components'][$component_id]['items']->sum('total_till_now.kharcha'), 3);
            }
            //converting object to array of component
            $items['punjigat']['components'] = collect($items['punjigat']['components'])->values();

        $items['totals']['baarsik_lakshya_vaar'] = round(collect($myData)->sum('baarsik_lakshya_vaar'), 3);
        $items['totals']['baarsik_lakshya_budget'] = round(collect($myData)->sum('baarsik_lakshya_budget'), 3);
        $items['totals'][$initial . '_traimasik_lakshya_vaar'] = round(collect($myData)->sum($initial . '_traimasik_lakshya_vaar'), 3);
        $items['totals'][$initial . '_traimasik_lakshya_budget'] = round(collect($myData)->sum($initial . '_traimasik_lakshya_budget'), 3);
        $items['totals'][$initial . '_traimasik_pragati_vaarit'] = round(collect($myData)->sum('traimaasik_pragati.vaarit'), 3);
        $items['totals'][$initial . '_traimasik_pragati_kharcha'] = round(collect($myData)->sum('traimaasik_pragati.kharcha'), 3);
        $items['totals']['total_till_now_vaarit'] = round(collect($myData)->sum('total_till_now.vaarit'), 3);
        $items['totals']['total_till_now_kharcha'] = round(collect($myData)->sum('total_till_now.kharcha'), 3);


        $items['prdatibedan_awadi_ko_pragati']['vaarit'] = $items['totals'][$initial . '_traimasik_lakshya_vaar']==0 ? 0 : round($items['totals'][$initial . '_traimasik_pragati_vaarit'] / $items['totals'][$initial . '_traimasik_lakshya_vaar'], 3);
        $items['prdatibedan_awadi_ko_pragati']['vautik'] = (collect($myData)->count('vautik_pragati.' . $initial . '_traimasik'))==0 ? 0 : round((collect($myData)->sum('vautik_pragati.' . $initial . '_traimasik')) / (collect($myData)->count('vautik_pragati.' . $initial . '_traimasik')), 3);


        $items['pratibedan_awadi_ko_kharcha']['punjigat'] = round($items['punjigat']['totals'][$initial . '_traimasik_pragati_kharcha'],3);
        $items['pratibedan_awadi_ko_kharcha']['chalu'] = round($items['chalu']['totals'][$initial . '_traimasik_pragati_kharcha'],3);
        $items['pratibedan_awadi_ko_kharcha']['total'] = round($items['pratibedan_awadi_ko_kharcha']['punjigat'] + $items['pratibedan_awadi_ko_kharcha']['chalu'],3);
        $items['pratibedan_awadi_ko_kharcha']['total_percent'] = $items['totals'][$initial . '_traimasik_lakshya_budget']==0 ? 0 : round(($items['pratibedan_awadi_ko_kharcha']['total'] / $items['totals'][$initial . '_traimasik_lakshya_budget']) * 100, 3);
        return $items;
    }

    public function report(Request $request)
    {
        try {
            $filterData = json_decode($request->filterData);
            $aayojanaID = $filterData->aayojana;
            $traimaasikID = $filterData->traimaasik;

            //checking baarsik or not if baarsik set traimaasik to 4 as total_till_now will be baarsik
            $baarsik = 0;
            $ardaBaarsik = 0;
            if($traimaasikID==5){ $baarsik=1; $traimaasikID=4; }
            if($traimaasikID==6){ $ardaBaarsik=1; $traimaasikID=2; }
            $traimaasik = Traimaasik::find($traimaasikID);
            $initial = $traimaasik->initial;
            $karyalayaIDs = $filterData->kaaryalaya;
            foreach($karyalayaIDs as $karyalayaID){
                // if that kaaryalaya has no lakshya then don't go through this loop
                if(!KriyakalapLakshya::where('aayojana_id', $aayojanaID)
                    ->where('kaaryalaya_id', $karyalayaID)->first()) continue;
                $traimaasikPragati = KriyakalapLakshya::where('aayojana_id', $aayojanaID)
                    ->where('kaaryalaya_id', $karyalayaID)
                    //ardabaarsik
                    ->when($ardaBaarsik,function($query){
                            return $query->where('pahilo_traimasik_lakshya_pariman','>',0)->orWhere('pahilo_traimasik_lakshya_budget','>',0)->orWhere('dosro_traimasik_lakshya_pariman','>',0)->orWhere('dosro_traimasik_lakshya_budget','>',0);
                    })
                    //baarsik or Arda Baarsik
                    ->when((!$baarsik && !$ardaBaarsik),function($myQuery) use ($initial){
                        return $myQuery->where(function ($query) use ($initial) {
                            return $query->where($initial . '_traimasik_lakshya_pariman', '>', 0)->orWhere($initial . '_traimasik_lakshya_budget', '>', 0);
                        });
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
                $totalBaarsikLakshyaBudget = $traimaasikPragatiUnfiltered->sum('baarsik_lakshya_budget');

                $traimaasikPragati = json_decode(json_encode($traimaasikPragati), true);


                $traimaasikPragatiReports[] =
                    [
                        'ardaBaarsik' => $ardaBaarsik,
                        'baarsik' => $baarsik,
                        'kaaryalaya' => Kaaryalaya::find($karyalayaID),
                        'aayojana' => Aayojana::find($aayojanaID)->name,
                        'trimester' => $baarsik ? 'वार्षिक' :  ( $ardaBaarsik ? 'अर्द वार्षिक' : Traimaasik::find($traimaasikID)->name ),
                        'initial' => $initial,
                        'items' => $this->getSpecificData($traimaasikPragati, $totalBaarsikLakshyaBudget, $initial,$baarsik,$ardaBaarsik)
                    ];
            }



            return response(
                [
                    'status' => 200,
                    'type' => 'success',
                    'message' => 'Aayojana loaded successfully',
                    'data' => compact('traimaasikPragatiReports')
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

    //filterable reports

    private function getSpecificDataFilterable($traimaasikPragati, $totalBaarsikLakshyaBudget, $initial)
    {
        //initializing extra 6 sums for frontend table (remaining are summed by frontend)
        $sum['traimaasik_pragati_pariman'] = 0;
        $sum['traimaasik_pragati_kharcha'] = 0;
        $sum['traimaasik_pragati_vaarit'] = 0;
        $sum['total_till_now_pariman'] = 0;
        $sum['total_till_now_kharcha'] = 0;
        $sum['total_till_now_vaarit'] = 0;
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
            }
            $item['total_till_now']['vaarit'] = round(($item['baarsik_lakshya_vaar'] / $item['baarsik_lakshya_pariman']) * $item['total_till_now']['pariman'], 3);
            $item['total_till_now']['pariman'] = round($item['total_till_now']['pariman'], 3);
            $item['total_till_now']['kharcha'] = round($item['total_till_now']['kharcha'], 3);
            $item['vautik_pragati'][$initial . '_traimasik'] = round(($item['traimaasik_pragati']['vaarit'] / $item[$initial . '_traimasik_lakshya_vaar']) * 100, 2) < 100 ? round(($item['traimaasik_pragati']['vaarit'] / $item[$initial . '_traimasik_lakshya_vaar']) * 100, 2) : 100;
            $item['vautik_pragati']['total_till_now'] = round(($item['total_till_now']['pariman'] / $item['baarsik_lakshya_pariman']) * 100, 2) < 100 ? round(($item['total_till_now']['pariman'] / $item['baarsik_lakshya_pariman']) * 100, 2) : 100;

            //sum of traimaasik_pragatis & total_till_now
            $sum['traimaasik_pragati_pariman'] += $item['traimaasik_pragati']['pariman'];
            $sum['traimaasik_pragati_kharcha'] += $item['traimaasik_pragati']['kharcha'];
            $sum['traimaasik_pragati_vaarit'] += $item['traimaasik_pragati']['vaarit'];

            $sum['total_till_now_pariman'] += $item['total_till_now']['pariman'];
            $sum['total_till_now_kharcha'] += $item['total_till_now']['kharcha'];
            $sum['total_till_now_vaarit'] += $item['total_till_now']['vaarit'];

            $myData['items'][] = $item;
        }
        //rounding off sum array
        $sum['traimaasik_pragati_pariman'] = round($sum['traimaasik_pragati_pariman'], 3);
        $sum['traimaasik_pragati_kharcha'] = round($sum['traimaasik_pragati_kharcha'], 3);
        $sum['traimaasik_pragati_vaarit'] = round($sum['traimaasik_pragati_vaarit'], 3);
        $sum['total_till_now_pariman'] = round($sum['total_till_now_pariman'], 3);
        $sum['total_till_now_kharcha'] = round($sum['total_till_now_kharcha'], 3);
        $sum['total_till_now_vaarit'] = round($sum['total_till_now_vaarit'], 3);

        $myData['sum'] = $sum;
        return $myData;
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
                ->when(!empty($filterData->kharchaPrakar), function ($query) use ($filterData) {
                    return $query->whereIn('kharcha_prakar', $filterData->kharchaPrakar);
                })
                ->where('kaaryalaya_id', $karyalayaID)
                ->where(function ($query) use ($initial) {
                    return $query->where($initial . '_traimasik_lakshya_pariman', '>', 0)->where($initial . '_traimasik_lakshya_budget', '>', 0);
                })
                ->with(['aayojana' => function ($query) {
                    return $query->with('aarthikBarsa');
                }, 'traimaasikPragati' => function ($query) use ($traimaasikID) {
                    return $query->where('traimaasik_id', $traimaasikID);
                }, 'traimaasikPragatis' => function ($query) use ($traimaasikID) {

                    return $query->where('traimaasik_id', '<=', $traimaasikID);
                }])
                ->get();
            $traimaasikPragatiUnfiltered = KriyakalapLakshya::where('aayojana_id', $aayojanaID)
                ->where('kaaryalaya_id', $karyalayaID)
                ->get();
            $totalBaarsikLakshyaBudget = $traimaasikPragatiUnfiltered->sum('baarsik_lakshya_budget');
            $traimaasikPragati = json_decode(json_encode($traimaasikPragati), true);
            $headers = [
                [
                    'text' => 'कृयाकलाप कोड',
                    'value' => 'name_with_kriyakalap_code'
                ],
                [
                    'text' => 'आर्थिक वर्ष',
                    'value' => 'aayojana.aarthik_barsa.name'
                ],
                [
                    'text' => 'आयोजना',
                    'value' => 'aayojana.name'
                ],
                [
                    'text' => 'खर्च शिर्षक',
                    'value' => 'kharcha_sirsak'
                ],
                [
                    'text' => 'इकाई',
                    'value' => 'ikai'
                ],
                [
                    'text' => 'बार्षिक लक्ष परिमाण',
                    'value' => 'baarsik_lakshya_pariman'
                ],
                [
                    'text' => 'बार्षिक लक्ष भार',
                    'value' => 'baarsik_lakshya_vaar'
                ],
                [
                    'text' => 'बार्षिक लक्ष बजेट',
                    'value' => 'baarsik_lakshya_budget'
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

            $finalData = $this->getSpecificDataFilterable($traimaasikPragati, $totalBaarsikLakshyaBudget, $initial);
            $traimaasikPragatiReport = [
                'trimester' => Traimaasik::find($traimaasikID)->name,
                'headers' => $headers,
                'initial' => $initial,
                'items' => $finalData['items'],
                'sum' => $finalData['sum']
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
