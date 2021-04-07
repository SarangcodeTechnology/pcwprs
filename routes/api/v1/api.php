<?php

use App\Http\Controllers\api\v1\DataController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
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
Route::middleware('auth:api')->get('users', [UserController::class,'index']);
Route::middleware('auth:api')->get('roles', [RoleController::class,'index']);
Route::middleware('auth:api')->post('save-user-data',[UserController::class,'saveUserData']);
Route::middleware('auth:api')->post('save-role-data',[RoleController::class,'saveRoleData']);

// permissions
Route::middleware('auth:api')->get('permissions', [PermissionController::class,'index']);
Route::middleware('auth:api')->post('save-permission-data',[PermissionController::class,'savePermissionData']);

