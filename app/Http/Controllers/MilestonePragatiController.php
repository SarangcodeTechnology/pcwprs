<?php

namespace App\Http\Controllers;

use App\Models\Aayojana;
use App\Models\Kaaryalaya;
use App\Models\KriyakalapLakshya;
use App\Models\Mahina;
use App\Models\MilestoneLakshya;
use App\Models\MilestonePragati;
use App\Models\Submission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MilestonePragatiController extends Controller
{
   public function index(Request $request){
       try {
           $filterData = json_decode($request->filterData);
           $aayojanaID= $filterData->aayojana;
           $mahinaID = $filterData->mahina;
           $mahina = Mahina::find($mahinaID);
           $initial = $mahina->initial;
           $kaaryalayaID = $filterData->kaaryalaya;

           $milestonePragatiTaalika=MilestoneLakshya::where('aayojana_id',$aayojanaID)->where('kaaryalaya_id',$kaaryalayaID)->select('id','ikai','milestone_id','milestone_name','kharcha_sirsak','name','kriyakalap_code',$initial.'_lakshya_pariman')
           ->with(['milestonePragati' => function ($query) use ($mahinaID,$kaaryalayaID) {
               $query->where('mahina_id', $mahinaID)->where('kaaryalaya_id',$kaaryalayaID);
           }])->get();

           $editable = true;
           $submitted = Submission::where('kaaryalaya_id',$kaaryalayaID)->where('aayojana_id',$aayojanaID)->where('milestone',1)->where('mahina_id',$mahinaID)->where('submitted',1)->first() ? true : false;
           if($submitted){
               $editable = Submission::where('kaaryalaya_id',$kaaryalayaID)->where('aayojana_id',$aayojanaID)->where('milestone',1)->where('mahina_id',$mahinaID)->where('submitted',1)->where('editable',1)->first() ? true : false;
           }
           $requested = Submission::where('kaaryalaya_id',$kaaryalayaID)->where('aayojana_id',$aayojanaID)->where('milestone',1)->where('mahina_id',$mahinaID)->where('submitted',1)->where('requested',1)->first() ? true : false;

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
                   'text' =>'परिमाण',
                   'value' =>'pariman'
               ],
               [
                   'text' => $mahina->name . ' लक्ष परिमाण',
                   'value' => $mahina->initial . '_lakshya_pariman'
               ],
               [
                   'text' => 'प्रारम्भिक कार्यको शुरु प्रगति',
                   'value' => 'milestone_pragati.prarambhik_karya_suru_pragati'
               ],
               [
                   'text' => 'प्रारम्भिक कार्यको जारी प्रगति',
                   'value' => 'milestone_pragati.prarambhik_karya_jari_pragati'
               ],
               [
                   'text' => 'प्रारम्भिक कार्यको सम्पन्न प्रगति',
                   'value' => 'milestone_pragati.prarambhik_karya_sampanna_pragati'
               ],

               [
                   'text' => 'कार्यक्रम कार्यान्वयनको शुरु प्रगति',
                   'value' => 'milestone_pragati.karyakram_karyanayan_suru_pragati'
               ],
               [
                   'text' => 'कार्यक्रम कार्यान्वयनको जारी प्रगति',
                   'value' => 'milestone_pragati.karyakram_karyanayan_jari_pragati'
               ],
               [
                   'text' => 'कार्यक्रम कार्यान्वयनको सम्पन्न प्रगति',
                   'value' => 'milestone_pragati.karyakram_karyanayan_sampanna_pragati'
               ],
               [
                   'text'=> 'माईलस्टोन नं',
                   'value'=> 'milestone_id'
               ],
               [
                   'text'=> 'माईलस्टोन',
                   'value'=> 'milestone_name'
               ]
           ];
           return response(
               [
                   'status' => 200,
                   'type' => 'success',
                   'message' => 'Aayojana loaded successfully',
                   'data' => compact('requested','milestonePragatiTaalika','headers','submitted','editable')
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
    public function saveMilestonePragatiTaalika(Request $request){
        try {
            foreach ($request->items as $item) {
                $milestonePragati = $item['milestone_pragati'];
                if (isset($milestonePragati['id'])) {
                    unset($milestonePragati['mahina']);
                    MilestonePragati::find($milestonePragati['id'])->update($milestonePragati);
                } else {
                    MilestonePragati::create($milestonePragati);
                }
            }
            $submitted = false;
            $editable = true;
            if($request->submitted){
                // if row is already present of such data
                $submission = Submission::where('mahina_id',$request->filterData['mahina'])->where('aayojana_id',$request->filterData['aayojana'])->where('milestone',1)->where('kaaryalaya_id',$request->filterData['kaaryalaya'])->first();
                if($submission){
                    $submission->milestone = 1;
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
                    $submission->milestone = 1;
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
                    'message' => 'Milestone Pragati updated successfully',
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
                    if(!$component_id){
                        $items['chalu']['data_without_component'] = $component->values();
                        continue;
                    }
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
                $items['chalu']['components'] = isset($items['chalu']['components']) ? collect($items['chalu']['components'])->values() : [];

        // punjigat data
        $items['punjigat']['data'] = collect($myData)->where('kharcha_prakar','पुँजीगत');
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
                if(!$component_id){
                    $items['punjigat']['data_without_component'] = $component->values();
                    continue;
                }

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
            $items['punjigat']['components'] = isset($items['punjigat']['components']) ? collect($items['punjigat']['components'])->values() : [];


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

}
