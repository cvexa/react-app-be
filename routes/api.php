<?php

use App\Http\Controllers\PropertyController;
use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);
Route::get('top-three', [PropertyController::class, 'topThree'])->name('top-three');
Route::get('property-types',[PropertyController::class, 'propertyTypes'])->name('get-property-types');
Route::fallback(function(){
    return response()->json(['message' => 'not found'], 404);
});

Route::middleware('auth:api')->group( function () {
   Route::get('test', function () {
      return 'ok';
   });

    Route::resource('properties', PropertyController::class);
    Route::post('logout', [RegisterController::class, 'logOut'])->name('logout');
});
