<?php

namespace App\Http\Controllers;

use App\Models\Mahina;
use App\Models\MilestoneLakshya;
use App\Models\Submission;
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
           $traimaasik = $mahina->traimaasik->name;
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
}
