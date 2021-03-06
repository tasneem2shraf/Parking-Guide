<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RectanglesController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'prefix' => 'auth',

], function () {

    Route::post('login', [App\Http\Controllers\AuthController::class, 'login']);
    Route::post('logout', [App\Http\Controllers\AuthController::class, 'logout']);
    Route::post('refresh', [App\Http\Controllers\AuthController::class, 'refresh']);
    Route::post('me', [App\Http\Controllers\AuthController::class, 'me']);
});

Route::post('register', [App\Http\Controllers\RegisterController::class, 'register']);

Route::group([], function () {
    Route::resource('garages', (App\Http\Controllers\GarageController::class));//coment
    Route::resource('reviews', (App\Http\Controllers\ReviewController::class));
    Route::resource('histories', (App\Http\Controllers\HistoryController::class));
    Route::resource('floorhistories', (App\Http\Controllers\Floor_HistoryController::class));
    Route::resource('rectangle',(RectanglesController::class));
});

//garage with comments + reviews +floors for any user
Route::get('garages/mine/{id}/', [App\Http\Controllers\GarageController::class,'show_one_garage']);

Route::get('owner/garages', [App\Http\Controllers\GarageController::class,'show_owner_garages']);
// for auth owners

// get garage with its active requests where statue = 10
Route::get('garage/{id}/requests', [App\Http\Controllers\GarageController::class,'get_garage_active_requests']);
//
Route::get('search/{name}', [App\Http\Controllers\GarageController::class,'search']);//USER SEARCH FOR GARAGES by name
Route::get('nearest_garage', [App\Http\Controllers\GarageController::class,'get_nearest_garage']);//user get nearest garage, by lat and long

//Search nearest
Route::get('search/{name}', [App\Http\Controllers\GarageController::class,'search']);//USER SEARCH FOR GARAGES by name
Route::post('nearest_garage', [App\Http\Controllers\GarageController::class,'get_nearest_garage']);//user get nearest garage, by lat and long

//comments
Route::post('comments/add/', [App\Http\Controllers\ConmmentController::class,'store']);
Route::put('comments/{id}/update/', [App\Http\Controllers\ConmmentController::class,'update']);
Route::get('comments/{id}/show/', [App\Http\Controllers\ConmmentController::class,'show']);// get a comment by auth
Route::delete('commentdestroy/{id}/', [App\Http\Controllers\ConmmentController::class,'destroy']);

//not auth
Route::get('commentindex/{garage_id}/', [App\Http\Controllers\ConmmentController::class,'index']);

//request
Route::post('request/add/', [App\Http\Controllers\RequestcarController::class,'store']);
Route::get('request/show/{id}/', [App\Http\Controllers\RequestcarController::class,'show']);
Route::put('request/update/{id}/', [App\Http\Controllers\RequestcarController::class,'update']);
Route::put('request/cancel/{id}/', [App\Http\Controllers\RequestcarController::class,'canceled']);
Route::delete('request/destroy/{id}/', [App\Http\Controllers\RequestcarController::class,'destroy']);
Route::get('request/index/{id}/', [App\Http\Controllers\RequestcarController::class,'index']);
Route::get('request/showLastActive', [App\Http\Controllers\RequestcarController::class,'get_last_active_request']);
Route::get('request/showAll', [App\Http\Controllers\RequestcarController::class,'all_user_requests']);

//camera
Route::post('camera', [App\Http\Controllers\CameraController::class,'store']);
Route::get('show_camera/{id}/', [App\Http\Controllers\CameraController::class,'show']);
Route::delete('camera_destroy/{id}/', [App\Http\Controllers\CameraController::class,'destroy']);


Route::get('floorCameras/{id}/', [App\Http\Controllers\CameraController::class,'show_all_floor_cameras']);


Route::post('cahgneAvail/{id}/', [App\Http\Controllers\RectanglesController::class,'changeRectanglesAvail']);
