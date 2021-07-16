<?php

namespace App\Http\Controllers;

use App\Models\KriyakalapLakshya;
use App\Models\MilestoneLakshya;
use Illuminate\Http\Request;

class MilestoneLakshyaController extends Controller
{
    public function index(Request $request)
    {
        try {
            $filterData = json_decode($request->filterData);
            $kriyakalapLakshya = MilestoneLakshya
                ::where('aayojana_id',$filterData->aayojana)
                ->where('kaaryalaya_id',$filterData->kaaryalaya)
                //aayojana
                ->with('aayojana')
                ->orderBy('created_at', 'desc')
                ->get();
            return response(
                [
                    'status' => 200,
                    'type' => 'success',
                    'message' => 'Aayojana loaded successfully',
                    'data' => compact('kriyakalapLakshya')
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

    public function save(Request $request){
        try {
            if(count($request->deletedItems)>0){
                MilestoneLakshya::whereIn('id', $request->deletedItems)->delete();
            }
            foreach ($request->items as $item) {
                if (isset($item['id'])) {
                    $myData = $item;
                    unset($myData['aayojana']);
                    MilestoneLakshya::find($item['id'])->update($myData);
                } else {
                    MilestoneLakshya::create($item);
                }
            }

            return response(
                [
                    'status' => 200,
                    'type' => 'success',
                    'message' => 'Milestone Lakshya Updated successfully',
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

    public function upload(Request $request)
    {
        try {
            if($request->replace){
                $milstoneLakshya = MilestoneLakshya::where('aayojana_id',$request->aayojana)->where('kaaryalaya_id',$request->kaaryalaya);
                /**to be added after pragati is added
                foreach($milstoneLakshya->get() as $toBeDeletedPragati){
                    MilestonePragati::where('kriyakalap_lakshya_id',$toBeDeletedPragati->id)->delete();
                } **/
                $milstoneLakshya->delete();
            }
            $items = $this->convertCSV($request->file('csvData'));
            $aayojana_id = $request->aayojana;
            $aarthik_barsa = $request->aarthikBarsa;
            $kaaryalaya_id = $request->kaaryalaya;
            $user_id = $request->user;
            foreach ($items as $item) {
                $item['aayojana_id'] = $aayojana_id;
                $item['kaaryalaya_id'] = $kaaryalaya_id;
                $item['user_id'] = $user_id;
                MilestoneLakshya::create($item);
            }

            return response(
                [
                    'status' => 200,
                    'type' => 'success',
                    'message' => 'Milestone Lakshya Uploaded successfully',
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

    private function convertCSV($file){
        $key = [
                "kriyakalap_code",
                "name",
                "kharcha_sirsak",
                "ikai",
                "kharcha_prakar",
                "milestone_id",
                "milestone_name",
                "pariman",
                "shrawan_lakshya_pariman",
                "bhadra_lakshya_pariman",
                "ashoj_lakshya_pariman",
                "kaartik_lakshya_pariman",
                "mangsir_lakshya_pariman",
                "paush_lakshya_pariman",
                "magh_lakshya_pariman",
                "falgun_lakshya_pariman",
                "chaitra_lakshya_pariman",
                "baisakh_lakshya_pariman",
                "jestha_lakshya_pariman",
                "ashar_lakshya_pariman",
                "kaifiyat"
        ];
        $changeToEnglishArray = [
            "pariman",
            "shrawan_lakshya_pariman",
            "bhadra_lakshya_pariman",
            "ashoj_lakshya_pariman",
            "kaartik_lakshya_pariman",
            "mangsir_lakshya_pariman",
            "paush_lakshya_pariman",
            "magh_lakshya_pariman",
            "falgun_lakshya_pariman",
            "chaitra_lakshya_pariman",
            "baisakh_lakshya_pariman",
            "jestha_lakshya_pariman",
            "ashar_lakshya_pariman",
        ];
        $csv = file_get_contents($file);
        $array = array_map("str_getcsv", explode("\n", $csv));
        $key = $array[0];
        $arrayCount = -1;
        $val = '';
        foreach ($array as $item) {
            $arrayCount++;
            if(count($item)==1) continue;
            if($arrayCount==0) continue;
            $combinedArray = array_combine($key, $item);
            $data[] = $combinedArray;
        }
        $data = collect($data)->map(function ($item) use ($changeToEnglishArray){
            foreach ($changeToEnglishArray as $key){
                if(!$item[$key]) {
                    $item[$key] = NULL;
                }
                else {
                    $item[$key] = $this->nepaliToEnglish($item[$key]);
                }

            }
            return $item;
        });
        return $data;
    }
    private function nepaliToEnglish($j){
        $find = array("१","२","३","४","५","६","७","८","९","०");
        $replace = array("1","2","3","4","5","6","7","8","9","0");

        // number lai array ma lageko
        $numarr = mb_str_split($j,1);
        // numarr ko value lai nepali ma replace garna ko lagi, yesle array fyalxa
        $num = str_replace($find,$replace,$numarr);

        // yesle array linxa ani string return garxa
        return implode($num);
    }
}
