<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\PostAPIController;

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




Route::middleware('auth.basic')->group(function () {
    // Routes that require Basic Authentication
    Route::post('/getuser/costing','Api\PostAPIController@index');
    // Add more secure routes as needed

});
   