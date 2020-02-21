<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('v1/user', function (Request $request) {
    return $request->user();
});


Route::prefix('v1')->group(function() {
    Route::post('/auth/register', 'Auth\RegisterController@register');
    Route::get('email/verify/{id}', 'VerificationApiController@verify')->name('verificationapi.verify');
    Route::get('email/resend', 'VerificationApiController@resend')->name('verificationapi.resend');
    Route::post('/auth/login', 'Auth\LoginController@login');

    Route::middleware('auth:api','verified')->prefix('category')->group(function() {
        Route::get('', 'BudgetCategoryController@index');
        Route::post('', 'BudgetCategoryController@store');
        Route::get('/{id}', 'BudgetCategoryController@show');
        Route::put('/{id}', 'BudgetCategoryController@update');
        Route::delete('/{id}', 'BudgetCategoryController@destroy');
    });
});
