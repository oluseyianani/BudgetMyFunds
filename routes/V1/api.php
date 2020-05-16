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


Route::prefix('v1')->middleware('cors')->group(function () {
    Route::post('/auth/register', 'Auth\RegisterController@register');
    Route::get('email/verify/{id}', 'VerificationApiController@verify')->name('verificationapi.verify');
    Route::get('email/resend', 'VerificationApiController@resend')->name('verificationapi.resend');
    Route::post('/auth/email-login', 'Auth\LoginController@login');
    Route::post('auth/mobile-login', 'Auth\LoginController@mobileLogin');
    Route::post('auth/registration-code', 'Auth\RegisterController@getCode');
    Route::post('auth/validate-code', 'Auth\RegisterController@validateCode');

    Route::middleware('auth:api', 'verified')->prefix('category')->group(function () {
        Route::get('', 'BudgetCategoryController@index');
        Route::post('', 'BudgetCategoryController@store');
        Route::get('/{id}', 'BudgetCategoryController@show');
        Route::put('/{id}', 'BudgetCategoryController@update');
        Route::delete('/{id}', 'BudgetCategoryController@destroy');

        Route::post('/{id}/subcategory', 'BudgetSubCategoryController@store');
        Route::put('/{id}/subcategory/{subCategoryId}', 'BudgetSubCategoryController@update');
        Route::delete('/{id}/subcategory/{subCategoryId}', 'BudgetSubCategoryController@destroy');

        Route::post('/{id}/usercategory', 'BudgetUserCategoryController@store');
        Route::put('/{id}/usercategory/{userCategoryId}', 'BudgetUserCategoryController@update');
        Route::delete('/{id}/usercategory/{userCategoryId}', 'BudgetUserCategoryController@destroy');
    });

    Route::middleware('auth:api')->group(function () {
        Route::get('role', 'RoleController@index');
        Route::get('role/{id}', 'RoleController@show');
        Route::post('role', 'RoleController@store');
        Route::put('role/{id}', 'RoleController@update');

        Route::get('goal/category', 'GoalCategoryController@index');
        Route::get('goal/category/{id}', 'GoalCategoryController@show');
        Route::post('goal/category', 'GoalCategoryController@store');
        Route::put('goal/category/{id}', 'GoalCategoryController@update');
        Route::delete('goal/category/{id}', 'GoalCategoryController@destroy');
    });

    Route::middleware('auth:api', 'verified')->group(function () {
        Route::post('profile', 'BudgetUserProfileController@store');
        Route::get('profile/{userId}', 'BudgetUserProfileController@show');

        Route::get('goal', 'GoalController@index');
        Route::get('goal/{id}', 'GoalController@show');
        Route::post('goal', 'GoalController@store');
        Route::put('goal/{id}', 'GoalController@update');
        Route::delete('goal/{id}', 'GoalController@destroy');
        Route::post('goal/{id}/complete', 'GoalController@complete');

        Route::post('goal/{id}/tracking', 'GoalTrackingController@store');
        Route::put('goal/{id}/tracking/{trackingId}', 'GoalTrackingController@update');
        Route::delete('goal/{id}/tracking/{trackingId}', 'GoalTrackingController@destroy');

        Route::get('budget', 'BudgetController@index');
        Route::get('budget/{id}', 'BudgetController@show');
        Route::post('budget', 'BudgetController@store');
        Route::put('budget/{id}', 'BudgetController@update');
        Route::delete('budget/{id}', 'BudgetController@destroy');

        Route::get('budget/{id}/tracking', 'BudgetTrackingController@index');
        Route::get('budget/{id}/tracking/{trackingId}', 'BudgetTrackingController@show');
        Route::post('budget/{id}/tracking', 'BudgetTrackingController@store');
        Route::put('budget/{id}/tracking/{trackingId}', 'BudgetTrackingController@update');
        Route::delete('budget/{id}/tracking/{trackingId}', 'BudgetTrackingController@destroy');

        Route::get('income', 'MonthlyIncomeController@index');
        Route::get('income/{id}', 'MonthlyIncomeController@show');
        Route::post('income', 'MonthlyIncomeController@store');
        Route::put('income/{id}', 'MonthlyIncomeController@update');
        Route::delete('income/{id}', 'MonthlyIncomeController@destroy');
    });
});
