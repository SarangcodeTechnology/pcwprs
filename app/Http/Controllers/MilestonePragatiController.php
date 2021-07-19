<?php

namespace App\Http\Controllers;

use App\Models\Mahina;
use App\Models\MilestoneLakshya;
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


           $milestonePragatiTaalika=MilestoneLakshya::where('aayojana_id',$aayojanaID)->where('kaaryalaya_id',$karyalayaID)->select('id','ikai','milestone_id','milestone_name',$initial.'_lakshy')


           $maasikPragatiTaalika = KriyakalapLakshya::where('aayojana_id', $aayojanaID)
//                ->where(function ($query) use ($initial) {
//                    return $query->where($initial . '_traimasik_lakshya_pariman', '>', 0)->orWhere($initial . '_traimasik_lakshya_budget', '>', 0);
//                })
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
}
