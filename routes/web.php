<?php

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
Route::group(['middleware' => ['auth','throttle:60,1']], function () {

    Route::get('/', 'AnalysisController@shortShow');

    /**
     * 短期分析
     */
    Route::group(['prefix'=>'analysis'],function(){
        Route::get('/analysis-short', 'AnalysisController@shortShow')->name('analysis.short.show');
        Route::get('/analysis-valuation', 'AnalysisController@shortValuation')->name('analysis.short.valuation');
    });

    /**
     * 庫存
     */
    Route::group(['prefix'=>'warehouse'],function(){
        Route::get('/show', 'WarehouseController@show')->name('warehouse.show');
        Route::get('/record', 'WarehouseController@warehouse')->name('warehouse.record');
        Route::get('/deal-record', 'WarehouseController@dealRecord')->name('warehouse.deal.record');
        Route::post('/sold', 'WarehouseController@sold')->name('warehouse.sold');
        Route::post('/add', 'WarehouseController@add')->name('warehouse.add');
    });

});

Auth::routes();

Route::get('/facebook-login', 'SocialAuthController@facebookLogin')->name('facebook.login');
Route::get('/facebook-callback', 'SocialAuthController@facebookCallback');