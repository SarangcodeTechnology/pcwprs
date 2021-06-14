<?php

namespace App\Http\Controllers;

use App\Models\Aayojana;
use App\Models\Kaaryalaya;
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
                ->select('id','ikai','component','component_id','milestone','kharcha_sirsak', 'name', 'kriyakalap_code', $initial . '_traimasik_lakshya_pariman', $initial . '_traimasik_lakshya_budget')
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
                    'text' => $mahina->traimaasik->name . ' परिमाण',
                    'value' => $mahina->traimaasik->initial . '_traimasik_lakshya_pariman'
                ],
                [
                    'text' => $mahina->traimaasik->name . ' बजेट',
                    'value' => $mahina->traimaasik->initial . '_traimasik_lakshya_budget'
                ],
                [
                    'text' => 'मासिक प्रगती परिमाण',
                    'value' => 'maasik_pragati.pariman'
                ],
                [
                    'text' => 'मासिक प्रगती खर्च',
                    'value' => 'maasik_pragati.kharcha'
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
        if($totalSum==0) return 0;
        return ($budget/$totalSum)*100;
    }

    private function getSpecificData($maasikPragati, $mahina, $totalBaarsikLakshyaBudget,$baarsik,$ardaBaarsik)
    {
        foreach ($maasikPragati as $item) {
            $item['total_till_now']['pariman'] = 0;
            $item['total_till_now']['kharcha'] = 0;
            $item['baarsik_lakshya_vaar'] = round($this->calculateVaar($item['baarsik_lakshya_budget'] ,$totalBaarsikLakshyaBudget),3);
            // if maasik_pragati is not null then calculate vaar else set all to 0
            if ($item['maasik_pragati']) {
                $item['maasik_pragati']['vaarit'] = $item['baarsik_lakshya_pariman']==0 ? 0 : round(($item['baarsik_lakshya_vaar']/$item['baarsik_lakshya_pariman'])*$item['maasik_pragati']['pariman'],3);
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

            $item['total_till_now']['pariman'] = round($item['total_till_now']['pariman'],3);
            $item['total_till_now']['kharcha'] = round($item['total_till_now']['kharcha'],3);
            $item['total_till_now']['vaarit'] = $item['baarsik_lakshya_pariman']==0 ? 0 : round(($item['baarsik_lakshya_vaar']/$item['baarsik_lakshya_pariman'])*$item['total_till_now']['pariman'],3);

            //round off pariman kharcha and vaarit

            if($baarsik||$ardaBaarsik) {
                $item['maasik_pragati']['pariman'] =  $item['total_till_now']['pariman'] ;
                $item['maasik_pragati']['kharcha'] =  $item['total_till_now']['kharcha'] ;
                $item['maasik_pragati']['vaarit'] =  $item['total_till_now']['vaarit'] ;
            }

            $item['vautik_pragati'] = round(($item['total_till_now']['pariman'] / $item['baarsik_lakshya_pariman'])*100,2);

            $myData[] = $item;
        }
        // chalu data
        $items['chalu']['data']= collect($myData)->where('kharcha_prakar','चालु')->values();
        $items['chalu']['totals']['baarsik_lakshya_vaar'] = round($items['chalu']['data']->sum('baarsik_lakshya_vaar'),3);
        $items['chalu']['totals']['baarsik_lakshya_budget'] = round($items['chalu']['data']->sum('baarsik_lakshya_budget'),3);
        $items['chalu']['totals']['maasik_pragati_vaarit'] = round($items['chalu']['data']->sum('maasik_pragati.vaarit'),3);
        $items['chalu']['totals']['maasik_pragati_kharcha'] = round($items['chalu']['data']->sum('maasik_pragati.kharcha'),3);
        $items['chalu']['totals']['total_till_now_vaarit'] = round($items['chalu']['data']->sum('total_till_now.vaarit'),3);
        $items['chalu']['totals']['total_till_now_kharcha'] = round($items['chalu']['data']->sum('total_till_now.kharcha'),3);
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
                    $items['chalu']['components'][$component_id]['totals']['baarsik_lakshya_vaar'] = round($items['chalu']['components'][$component_id]['items']->sum('baarsik_lakshya_vaar'),3);
                    $items['chalu']['components'][$component_id]['totals']['baarsik_lakshya_budget'] = round($items['chalu']['components'][$component_id]['items']->sum('baarsik_lakshya_budget'),3);
                    $items['chalu']['components'][$component_id]['totals']['maasik_pragati_vaarit'] = round($items['chalu']['components'][$component_id]['items']->sum('maasik_pragati.vaarit'),3);
                    $items['chalu']['components'][$component_id]['totals']['maasik_pragati_kharcha'] = round($items['chalu']['components'][$component_id]['items']->sum('maasik_pragati.kharcha'),3);
                    $items['chalu']['components'][$component_id]['totals']['total_till_now_vaarit'] = round($items['chalu']['components'][$component_id]['items']->sum('total_till_now.vaarit'),3);
                    $items['chalu']['components'][$component_id]['totals']['total_till_now_kharcha'] = round($items['chalu']['components'][$component_id]['items']->sum('total_till_now.kharcha'),3);
                }
                //converting object to array of component
                $items['chalu']['components'] = collect($items['chalu']['components'])->values();

        // punjigat data
        $items['punjigat']['data'] = collect($myData)->where('kharcha_prakar','पूँजीगत');
        $items['punjigat']['totals']['baarsik_lakshya_vaar'] = round($items['punjigat']['data']->sum('baarsik_lakshya_vaar'),3);
        $items['punjigat']['totals']['baarsik_lakshya_budget'] = round($items['punjigat']['data']->sum('baarsik_lakshya_budget'),3);
        $items['punjigat']['totals']['maasik_pragati_vaarit'] = round($items['punjigat']['data']->sum('maasik_pragati.vaarit'),3);
        $items['punjigat']['totals']['maasik_pragati_kharcha'] = round($items['punjigat']['data']->sum('maasik_pragati.kharcha'),3);
        $items['punjigat']['totals']['total_till_now_vaarit'] = round($items['punjigat']['data']->sum('total_till_now.vaarit'),3);
        $items['punjigat']['totals']['total_till_now_kharcha'] = round($items['punjigat']['data']->sum('total_till_now.kharcha'),3);

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
                $items['punjigat']['components'][$component_id]['totals']['baarsik_lakshya_vaar'] = round($items['punjigat']['components'][$component_id]['items']->sum('baarsik_lakshya_vaar'),3);
                $items['punjigat']['components'][$component_id]['totals']['baarsik_lakshya_budget'] = round($items['punjigat']['components'][$component_id]['items']->sum('baarsik_lakshya_budget'),3);
                $items['punjigat']['components'][$component_id]['totals']['maasik_pragati_vaarit'] = round($items['punjigat']['components'][$component_id]['items']->sum('maasik_pragati.vaarit'),3);
                $items['punjigat']['components'][$component_id]['totals']['maasik_pragati_kharcha'] = round($items['punjigat']['components'][$component_id]['items']->sum('maasik_pragati.kharcha'),3);
                $items['punjigat']['components'][$component_id]['totals']['total_till_now_vaarit'] = round($items['punjigat']['components'][$component_id]['items']->sum('total_till_now.vaarit'),3);
                $items['punjigat']['components'][$component_id]['totals']['total_till_now_kharcha'] = round($items['punjigat']['components'][$component_id]['items']->sum('total_till_now.kharcha'),3);
            }
            //converting object to array of component
            $items['punjigat']['components'] = collect($items['punjigat']['components'])->values();


            //totals of Data;
        $items['totals']['baarsik_lakshya_vaar'] = round(collect($myData)->sum('baarsik_lakshya_vaar'),3);
        $items['totals']['baarsik_lakshya_budget'] = round(collect($myData)->sum('baarsik_lakshya_budget'),3);
        $items['totals']['maasik_pragati_vaarit'] = round(collect($myData)->sum('maasik_pragati.vaarit'),3);
        $items['totals']['maasik_pragati_kharcha'] = round(collect($myData)->sum('maasik_pragati.kharcha'),3);
        $items['totals']['total_till_now_vaarit'] = round(collect($myData)->sum('total_till_now.vaarit'),3);
        $items['totals']['total_till_now_kharcha'] = round(collect($myData)->sum('total_till_now.kharcha'),3);


        $items['prdatibedan_awadi_ko_pragati']['vaarit'] = round($items['totals']['maasik_pragati_vaarit'] / $items['totals']['baarsik_lakshya_vaar'] , 3);
        $items['prdatibedan_awadi_ko_pragati']['vautik'] = round((collect($myData)->sum('vautik_pragati')) / (collect($myData)->count('vautik_pragati')), 3);


        $items['pratibedan_awadi_ko_kharcha']['punjigat'] = round($items['punjigat']['totals']['maasik_pragati_vaarit'],3);
        $items['pratibedan_awadi_ko_kharcha']['chalu'] = round($items['chalu']['totals']['maasik_pragati_vaarit'],3);
        $items['pratibedan_awadi_ko_kharcha']['total'] = round($items['pratibedan_awadi_ko_kharcha']['punjigat'] + $items['pratibedan_awadi_ko_kharcha']['chalu'],3);

        $items['pratibedan_awadi_ko_kharcha']['total_percent'] = $items['totals']['maasik_pragati_vaarit']==0 ? 0 : round(($items['pratibedan_awadi_ko_kharcha']['total'] / $items['totals']['maasik_pragati_vaarit']) * 100, 3);

        return $items;
    }

    public function report(Request $request){
        try {
            $filterData = json_decode($request->filterData);
            $aayojanaID= $filterData->aayojana;
            $mahinaID = $filterData->mahina;
            //barsa and arda barsa flag
            $baarsik = 0;
            $ardaBaarsik = 0;
            //setting for baarsik and arda baarsik
            if($mahinaID == 13){ $mahinaID = 12; $baarsik = 1; }
            if($mahinaID == 14){ $mahinaID = 6; $ardaBaarsik = 1; }
            $mahina = Mahina::find($mahinaID);
            $initial = $mahina->traimaasik->initial;
            $traimaasik = $mahina->traimaasik->name;
            $karyalayaIDs = $filterData->kaaryalaya;
            //initializing
            $maasikPragatiReports = [];
            foreach($karyalayaIDs as $karyalayaID) {
                // if that kaaryalaya has no lakshya then don't go through this loop
                if(!KriyakalapLakshya::where('aayojana_id', $aayojanaID)
                    ->where('kaaryalaya_id', $karyalayaID)->first()) continue;
                $maasikPragati = KriyakalapLakshya::where('aayojana_id', $aayojanaID)
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
                    ->with(['maasikPragati' => function ($query) use ($mahinaID) {
                        return $query->where('mahina_id', $mahinaID);
                    }])
                    ->with(['maasikPragatis' => function ($query) use ($mahinaID) {

                        return $query->where('mahina_id', '<=', $mahinaID);
                    }])
                    ->get();
                //for total baarsiklakshya budget we shouldn't filter
                $maasikPragatiUnfiltered = KriyakalapLakshya::where('aayojana_id', $aayojanaID)
                    ->where('kaaryalaya_id', $karyalayaID)
                    ->get();
                $totalBaarsikLakshyaBudget = $maasikPragatiUnfiltered->sum('baarsik_lakshya_budget');

                $maasikPragati = json_decode(json_encode($maasikPragati), true);
                $maasikPragatiReports[] = [
                    'baarsik' => $baarsik,
                    'ardaBaarsik' => $ardaBaarsik,
                    'kaaryalaya' => Kaaryalaya::find($karyalayaID),
                    'aayojana' => Aayojana::find($aayojanaID)->name,
                    'month' => $baarsik ? 'वार्षिक प्रगति' : ( $ardaBaarsik ? 'अर्द वार्षिक प्रगति' : Mahina::find($mahinaID)->name.' महिनाको प्रगति' ),
                    'items' => $this->getSpecificData($maasikPragati, $mahinaID, $totalBaarsikLakshyaBudget,$baarsik,$ardaBaarsik)
                ];

            }

            return response(
                [
                    'status' => 200,
                    'type' => 'success',
                    'message' => 'Aayojana loaded successfully',
                    'data' => compact('maasikPragatiReports')
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
