<?php

use App\Http\Controllers\PropertyController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
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
Route::post('login', [RegisterController::class, 'login'])->name('login');
Route::get('top-three', [PropertyController::class, 'topThree'])->name('top-three');
Route::get('property-types',[PropertyController::class, 'propertyTypes'])->name('get-property-types');
Route::fallback(function(){
    return response()->json(['message' => 'not found'], 404);
});
Route::get('featured-property', [PropertyController::class, 'featuredProperty'])->name('featured-property');
Route::get('best-deal-by-type/{type}', [PropertyController::class, 'bestDealByType'])->name('best-property-by-type');
Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show');
Route::get('/properties-by-type', [PropertyController::class, 'getByType'])->name('properties-by-type');

Route::middleware('auth:api')->group( function () {
   Route::get('test', function () {
      return 'ok';
   });

    Route::resource('properties', PropertyController::class)->except('index','show');
    Route::post('logout', [RegisterController::class, 'logOut'])->name('logout');
    Route::resource('users', UserController::class);

});
