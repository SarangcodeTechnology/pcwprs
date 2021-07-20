<?php

namespace App\Http\Controllers;

use App\Models\MilestonePragati;
use App\Models\Mahina;
use App\Models\MilestoneLakshya;
use App\Models\Submission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class MilestonePragatiController extends Controller
{
   public function index(Request $request){
       try {
           $filterData = json_decode($request->filterData);
           $aayojanaID= $filterData->aayojana;
           $mahinaID = $filterData->mahina;
           $mahina = Mahina::find($mahinaID);
           $initial = $mahina->initial;
           $karyalayaID = $filterData->kaaryalaya;


           $milestonePragatiTaalika=MilestoneLakshya::where('aayojana_id',$aayojanaID)->where('kaaryalaya_id',$karyalayaID)->select('id','ikai','milestone_id','milestone_name','kharcha_sirsak','name','kriyakalap_code',$initial.'_lakshya_pariman')
           ->with(['milestonePragati' => function ($query) use ($mahinaID,$karyalayaID) {
               $query->where('mahina_id', $mahinaID)->where('kaaryalaya_id',$karyalayaID);
           }])->get();

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
}
