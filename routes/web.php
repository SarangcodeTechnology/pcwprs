<?php

use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
use App\Models\CfData;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
Route::get('/addCfData',function() {
    $cfData =  CfData::all();

    foreach($cfData as $item){
        $tempCfData = new \App\Models\TempCfData();
        // fug_name
        $tempCfData->fug_name = $item->fug_name ?? '';
        // fug_code
        $tempCfData->fug_code = $item->fug_code ?? '';
        //cfid
        $tempCfData->cfid = $item->cfid ?? '';
        // province_id
        $tempCfData->province_id = $item->province ? \App\Models\Province::where('name',$item->province)->first()->id : 0;
        // district_id
        $tempCfData->district_id = $item->district ? \App\Models\District::where('name',$item->district)->first()->id : 0;
        // local_level_id
        $tempCfData->local_level_id = $item->llid ? \App\Models\LocalLevel::where('llid',$item->llid)->first()->id : 0;
        // physiography_id
        $tempCfData->physiography_id = $item->physiography ? \App\Models\Physiography::where('name',$item->physiography)->first()->id : 0;
        // x
        $tempCfData->x = $item->x ?? '';
        // y
        $tempCfData->y = $item->y ?? '';
        // subdivision_id
        $tempCfData->subdivision_id = $item->subdivision ? \App\Models\SubDivision::where('name',$item->subdivision)->first()->id : 0;
        // approval_date_bs
        $tempCfData->approval_date_bs = $item->approval_date_bs ?? '';
        // approval_date_ad
        $tempCfData->approval_date_ad = $item->approval_date_ad ?? '';
        // approval_fy
        $tempCfData->approval_fy = $item->approval_fy ?? '';
        // area_ha
        $tempCfData->area_ha = $item->area_ha ?? 0;
        // hh
        $tempCfData->hh = $item->hh ?? 0;
        // vegetation_type_id
        $tempCfData->vegetation_type_id = $item->vegetation_type ? \App\Models\VegetationType::where('code',$item->vegetation_type)->first()->id : 0;
        // forest_type_id
        $tempCfData->forest_type_id = $item->vegetation_type_code ?? 0;
        // foreast_condition_id
        $tempCfData->forest_condition_id = $item->forest_condition ? \App\Models\ForestCondition::where('code',$item->forest_condition)->first()->id : 0;

        // no_of_person_in_committee
        $tempCfData->no_of_person_in_committee = $item->no_of_person_in_committee ?? 0;

        // women_in_committee
        $tempCfData->women_in_committee = $item->women_in_committee ?? 0;

        // remarks
        $tempCfData->remarks = $item->remarks ?? '';

        // approval_revision_date_bs
        $tempCfData->approval_revision_date_bs = $item->approval_revision_date_bs ? json_encode([$item->approval_revision_date_bs]) : '[]';

        // approval_revision_date_ad
        $tempCfData->approval_revision_date_ad = $item->approval_revision_date_ad ? json_encode([$item->approval_revision_date_ad]) : '[]';

        $tempCfData->save();


    }


    return 'done';


});
*/

/*
Route::get('/importCfData',function() {
    $cfDataToBeImported =  CfData::select('local_level_name','llid','local_level','district')->orderBy('llid')->get();
     $cfDataToBeImported = $cfDataToBeImported->unique('llid');

    foreach($cfDataToBeImported as $item){
        $cfData = new \App\Models\LocalLevel();
        $cfData->llid = $item->llid ?? 0;
        $cfData->district_id = $item->district ? \App\Models\District::where('name',$item->district)->first()->id : 0;
        $cfData->name = $item->local_level_name ?? '';
        $cfData->type = $item->local_level ?? '';
        $cfData->save();
    }
    // foreach($uniqueDistrictProvince as $item){
    //     $district = new \App\Models\District();
    //     $district->name = $item->district;
    //     $district->province_id = explode('-',$item->province)[1];
    //     $district->save();
    // }

    // foreach($uniqueDistrictProvince as $item){
    //     $district = new \App\Models\Province();
    //     $district->name = $item->province;
    //     $district->save();
    // }

    // foreach($uniqueDistrictProvince as $item){
    //     $district = new \App\Models\ForestType();
    //     $district->name = $item->vegetation_type_scientific_name;
    //     $district->code = ($item->vegetation_type_code==null || $item->vegetation_type_code=='N/A') ?  0 : $item->vegetation_type_code;
    //     $district->save();
    // }


    // foreach($uniqueDistrictProvince as $item){
    //     $district = new \App\Models\Physiography();
    //     if($item->physiography!=null){
    //         $district->name = $item->physiography;
    //         $district->save();
    //     }

    // }


    return 'done';


});
*/

Route::get('test', [TestController::class, 'index']);

//Clear Cache
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    Artisan::call('route:clear');
    return "Cache is cleared";
});

//auth::routes();
Route::get('/{any}', '\App\Http\Controllers\SinglePageController@index')->where('any', '.*');
