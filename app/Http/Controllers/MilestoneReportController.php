<?php

namespace App\Http\Controllers;

use App\Models\Aayojana;
use App\Models\Kaaryalaya;
use App\Models\Mahina;
use App\Models\MilestonePragati;
use App\Models\MilestoneReport;
use App\Models\Submission;
use App\Models\Traimaasik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MilestoneReportController extends Controller
{
    public function saveMilestoneData(Request $request)
    {
        try {
            foreach ($request->items['milestonePragatiReports'] as $item) {
                foreach ($item['items'] as $secondItem) {
                    $milestoneReport = $secondItem['milestone_report'];
                    $milestoneReport['milestone_pragati_id'] = $secondItem['id'];
                    if (isset($milestoneReport['id'])) {
                        MilestoneReport::find($milestoneReport['id'])->update($milestoneReport);
                    } else {
                        MilestoneReport::create($milestoneReport);
                    }
                }
            }
            return response(
                [
                    'status' => 200,
                    'type' => 'success',
                    'message' => 'Milestone report saved successfully',
                    'data' => null
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

    public function report(Request $request)
    {
        try {
            $filterData = json_decode($request->filterData);
            $aayojanaID = $filterData->aayojana;
            $mahinaID = $filterData->mahina;
            $milestoneLakshyaIDs = Aayojana::find($aayojanaID)->milestoneLakshya->pluck('id')->toArray();
            $mahina = Mahina::find($mahinaID)->name;
            $kaaryalayaIDs = $filterData->kaaryalaya;
            //initializing
            $milestonePragatiReports = [];

            $totalMahinaArray = range(1, $mahinaID);
            /** getting data according to kaaryalaya */
            foreach ($kaaryalayaIDs as $kaaryalayaID) {
                /** checking if that kaaryalaya submitted or not and if not submitted no need to go further continue the loop
                 *  if user's kaaryalaya is same as kaaryalayaID then yes they can access it even without submitting data i.e, only saving data */
//                if (Auth::user()->kaaryalaya_id != $kaaryalayaID) {
//                    if (!Submission::where('mahina_id', $mahinaID)->where('milestone', 1)->where('kaaryalaya_id', $kaaryalayaID)->where('submitted', 1)->first()) continue;
//                }

                $milestonePragatis = MilestonePragati::whereIn('milestone_lakshya_id', $milestoneLakshyaIDs)->where('kaaryalaya_id', $kaaryalayaID)->where('mahina_id', $mahinaID)->with(['kaaryalaya', 'milestoneLakshya', 'milestoneReportInitial'])->get();
                //data with summations

                $milestonePragatis = $milestonePragatis->map(function ($item, $milestone_samayaawadhi_values) use ($totalMahinaArray) {
                    /* this will give all data upto that month */
                    $totalData = MilestonePragati::where('milestone_lakshya_id', $item['milestone_lakshya_id'])->where('kaaryalaya_id', $item['kaaryalaya_id'])->whereIn('mahina_id', $totalMahinaArray)->with('mahina');
                    $item["total_prarambhik_karya_suru_pragati"] = $totalData->sum('prarambhik_karya_suru_pragati');
                    $item["total_prarambhik_karya_jari_pragati"] = $totalData->sum('prarambhik_karya_jari_pragati');
                    $item["total_prarambhik_karya_sampanna_pragati"] = $totalData->sum('prarambhik_karya_sampanna_pragati');
                    $item["total_karyakram_karyanayan_suru_pragati"] = $totalData->sum('karyakram_karyanayan_suru_pragati');
                    $item["total_karyakram_karyanayan_jari_pragati"] = $totalData->sum('karyakram_karyanayan_jari_pragati');
                    $item["total_karyakram_karyanayan_sampanna_pragati"] = $totalData->sum('karyakram_karyanayan_sampanna_pragati');
                    $samayavadhiSum = Traimaasik::with('mahina')->get()->map(function ($traimaasikItem) use ($item) {
                        $traimaasikItem['prarambhik_karya_suru_samayavadhi'] = MilestonePragati::where('milestone_lakshya_id', $item['milestone_lakshya_id'])->whereIn('mahina_id', $traimaasikItem->mahina->pluck('id'))->sum('prarambhik_karya_suru_pragati');
                        $traimaasikItem['prarambhik_karya_jari_samayavadhi'] = MilestonePragati::where('milestone_lakshya_id', $item['milestone_lakshya_id'])->whereIn('mahina_id', $traimaasikItem->mahina->pluck('id'))->sum('prarambhik_karya_jari_pragati');
                        $traimaasikItem['prarambhik_karya_sampanna_samayavadhi'] = MilestonePragati::where('milestone_lakshya_id', $item['milestone_lakshya_id'])->whereIn('mahina_id', $traimaasikItem->mahina->pluck('id'))->sum('prarambhik_karya_sampanna_pragati');
                        $traimaasikItem['karyakram_karyanayan_suru_samayavadhi'] = MilestonePragati::where('milestone_lakshya_id', $item['milestone_lakshya_id'])->whereIn('mahina_id', $traimaasikItem->mahina->pluck('id'))->sum('karyakram_karyanayan_suru_pragati');
                        $traimaasikItem['karyakram_karyanayan_jari_samayavadhi'] = MilestonePragati::where('milestone_lakshya_id', $item['milestone_lakshya_id'])->whereIn('mahina_id', $traimaasikItem->mahina->pluck('id'))->sum('karyakram_karyanayan_jari_pragati');
                        $traimaasikItem['karyakram_karyanayan_sampanna_samayavadhi'] = MilestonePragati::where('milestone_lakshya_id', $item['milestone_lakshya_id'])->whereIn('mahina_id', $traimaasikItem->mahina->pluck('id'))->sum('karyakram_karyanayan_sampanna_pragati');
                        unset($traimaasikItem['id'], $traimaasikItem['name'], $traimaasikItem['initial']);
                        return $traimaasikItem;
                    });
                    $temp['prarambhik_karya_suru_samayavadhi'] = '';
                    $temp['prarambhik_karya_jari_samayavadhi'] = '';
                    $temp['prarambhik_karya_sampanna_samayavadhi'] = '';
                    $temp['karyakram_karyanayan_suru_samayavadhi'] = '';
                    $temp['karyakram_karyanayan_jari_samayavadhi'] = '';
                    $temp['karyakram_karyanayan_sampanna_samayavadhi'] = '';
                    foreach ($samayavadhiSum as $samayavadhiSumItem) {
                        $temp['prarambhik_karya_suru_samayavadhi'] = $temp['prarambhik_karya_suru_samayavadhi'] . $samayavadhiSumItem['mahina'][2]['name'] . '(' . $samayavadhiSumItem['prarambhik_karya_suru_samayavadhi'] . ')';
                        $temp['prarambhik_karya_jari_samayavadhi'] = $temp['prarambhik_karya_suru_samayavadhi'] . $samayavadhiSumItem['mahina'][2]['name'] . '(' . $samayavadhiSumItem['prarambhik_karya_jari_samayavadhi'] . ')';
                        $temp['prarambhik_karya_sampanna_samayavadhi'] = $temp['prarambhik_karya_suru_samayavadhi'] . $samayavadhiSumItem['mahina'][2]['name'] . '(' . $samayavadhiSumItem['prarambhik_karya_sampanna_samayavadhi'] . ')';
                        $temp['karyakram_karyanayan_suru_samayavadhi'] = $temp['prarambhik_karya_suru_samayavadhi'] . $samayavadhiSumItem['mahina'][2]['name'] . '(' . $samayavadhiSumItem['karyakram_karyanayan_suru_samayavadhi'] . ')';
                        $temp['karyakram_karyanayan_jari_samayavadhi'] = $temp['prarambhik_karya_suru_samayavadhi'] . $samayavadhiSumItem['mahina'][2]['name'] . '(' . $samayavadhiSumItem['karyakram_karyanayan_jari_samayavadhi'] . ')';
                        $temp['karyakram_karyanayan_sampanna_samayavadhi'] = $temp['prarambhik_karya_suru_samayavadhi'] . $samayavadhiSumItem['mahina'][2]['name'] . '(' . $samayavadhiSumItem['karyakram_karyanayan_sampanna_samayavadhi'] . ')';

                    }
//                    $item['samayavadhiSum'] = $temp;
                    $samayavadhiSum = $temp;
                    $mileStoneReportTemp = [
                        'prarambhik_karya_suru_milestone' => '',
                        'prarambhik_karya_jari_milestone' => '',
                        'prarambhik_karya_suru_reason' => '',
                        'prarambhik_karya_jari_reason' => '',
                        'prarambhik_karya_sampanna_milestone' => '',
                        'prarambhik_karya_sampanna_reason' => '',
                        'karyakram_karyanayan_suru_milestone' => '',
                        'karyakram_karyanayan_suru_reason' => '',
                        'karyakram_karyanayan_jari_reason' => '',
                        'karyakram_karyanayan_jari_milestone' => '',
                        'karyakram_karyanayan_sampanna_milestone' => '',
                        'karyakram_karyanayan_sampanna_reason' => ''];


                    $item["milestone_report"] = $item->milestoneReportInitial ?? json_decode(json_encode(array_merge($samayavadhiSum, $mileStoneReportTemp)));
                    return $item;
                });


                if (count($milestonePragatis) > 0) {
                    $milestonePragatiReports[] = [
                        'kaaryalaya' => Kaaryalaya::find($kaaryalayaID),
                        'aayojana' => Aayojana::find($aayojanaID)->name,
                        'items' => $milestonePragatis
                    ];
                }
                /** Required Data
                 *  [
                 *      {
                 *          'karyalaya_name': 'Kaaryalaya Name',
                 *          'niti_bundha_no': '1234',
                 *          'budget_baktabyako_bundha_no' : '12342',
                 *          'kriyakalap':'विरुवा उत्पादन/खरिद',
                 *       }
                 *  ]
                 */
            }

            /** if milestonePragatiReports is empty then return data is not available */
//            if (count($milestonePragatiReports) > 0) {
                return response(
                    [
                        'status' => 200,
                        'type' => 'success',
                        'message' => 'Milestone Report loaded successfully',
                        'data' => compact('milestonePragatiReports', 'mahina')
                    ]
                );
//            }
//            return response(
//                [
//                    'status' => 200,
//                    'type' => 'success',
//                    'message' => 'No Data Available',
//                    'data' => null
//                ]
//            );

        } catch (Exception $e) {
            return response([
                'status' => $e->getCode(),
                'type' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
