<?php

use App\Http\Controllers\AarthikBarsaController;
use App\Http\Controllers\AayojanaController;
use App\Http\Controllers\api\v1\DataController;
use App\Http\Controllers\api\v1\DeleteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KaaryalayaController;
use App\Http\Controllers\KriyakalapLakshyaController;
use App\Http\Controllers\LockController;
use App\Http\Controllers\MaasikPragatiTaalikaController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TraimaasikPragatiTaalikaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [AuthController::class,'login']);
Route::post('register', [AuthController::class,'register']);
Route::middleware('auth:api')->post('logout', [AuthController::class,'logout']);
Route::middleware('auth:api')->get('load-resources',[DataController::class,'loadResources']);
// users
Route::middleware('auth:api')->get('users', [UserController::class,'index']);
Route::middleware('auth:api')->post('save-user-data',[UserController::class,'saveUserData']);
Route::middleware('auth:api')->post('permissions-data-for-user', [UserController::class,'permissionsDataForUser']);

Route::middleware('auth:api')->get('roles', [RoleController::class,'index']);
Route::middleware('auth:api')->post('save-role-data',[RoleController::class,'saveRoleData']);

// permissions
Route::middleware('auth:api')->get('permissions', [PermissionController::class,'index']);
Route::middleware('auth:api')->post('save-permission-data',[PermissionController::class,'savePermissionData']);

// kaaryalaya
Route::middleware('auth:api')->get('kaaryalaya', [KaaryalayaController::class,'index']);
Route::middleware('auth:api')->post('save-kaaryalaya-data',[KaaryalayaController::class,'saveKaaryalayaData']);


// aarthik-barsa
Route::middleware('auth:api')->get('aarthik-barsa', [AarthikBarsaController::class,'index']);
Route::middleware('auth:api')->post('save-aarthik-barsa',[AarthikBarsaController::class,'saveAarthikBarsa']);

// aayojana
Route::middleware('auth:api')->get('aayojana', [AayojanaController::class,'index']);
Route::middleware('auth:api')->post('save-aayojana',[AayojanaController::class,'saveAayojana']);

// kriyakalap-lakshya
Route::middleware('auth:api')->get('kriyakalap-lakshya', [KriyakalapLakshyaController::class,'index']);
Route::middleware('auth:api')->post('save-kriyakalap-lakshya',[KriyakalapLakshyaController::class,'saveKriyakalapLakshya']);
Route::middleware('auth:api')->post('upload-kriyakalap-lakshya',[KriyakalapLakshyaController::class,'uploadKriyakalapLakshya']);

// maasik pragati talika
Route::middleware('auth:api')->get('maasik-pragati-taalika', [MaasikPragatiTaalikaController::class,'index']);
Route::middleware('auth:api')->get('maasik-pragati-taalika-report', [MaasikPragatiTaalikaController::class,'report']);
Route::middleware('auth:api')->post('save-maasik-pragati-taalika',[MaasikPragatiTaalikaController::class,'saveMaasikPragatiTaalika']);
Route::middleware('auth:api')->get('maasik-pragati-report-filterable', [MaasikPragatiTaalikaController::class,'filterableReport']);

// traimaasik pragati talika
Route::middleware('auth:api')->get('traimaasik-pragati-taalika', [TraimaasikPragatiTaalikaController::class,'index']);
Route::middleware('auth:api')->get('import-from-maasik-pragati', [TraimaasikPragatiTaalikaController::class,'importFromMaasikPragati']);
Route::middleware('auth:api')->post('save-traimaasik-pragati-taalika',[TraimaasikPragatiTaalikaController::class,'saveTraimaasikPragatiTaalika']);
Route::middleware('auth:api')->get('traimaasik-pragati-taalika-report', [TraimaasikPragatiTaalikaController::class,'report']);
Route::middleware('auth:api')->get('traimaasik-pragati-report-filterable', [TraimaasikPragatiTaalikaController::class,'filterableReport']);

//edit request
Route::middleware('auth:api')->post('edit-request',[RequestController::class,'editRequest']);
Route::middleware('auth:api')->get('edit-requests',[RequestController::class,'index']);
Route::middleware('auth:api')->post('approve-request',[RequestController::class,'approveRequest']);

//Delete Data from single controller
Route::middleware('auth:api')->post('delete-data',[DeleteController::class,'delete']);


Route::middleware('auth:api')->get('locks',[LockController::class,'index']);
Route::middleware('auth:api')->post('change-lock',[LockController::class,'changeLock']);
