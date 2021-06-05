<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\indexController;
use App\Http\Controllers\PollController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\OrgController;
use App\Models\Poll;
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

Route::get('/', [indexController::class, 'index']);
Route::prefix('front/polls')->group(function () {
Route::get('/', 'PollController@index')->name('front.polls');
Route::get('/create','PollController@create')->name('front.polls.create');
Route::post('/store','PollController@store')->name('front.polls.store');
Route::get('/edit/{poll}','PollController@edit')->name('front.polls.edit');
Route::post('/update/{poll}','PollController@update')->name('front.polls.update');
Route::get('/destroy/{poll}','PollController@destroy')->name('front.polls.destroy');
});

Route::prefix('front/rates')->group(function () {
    Route::get('/', 'RateController@index')->name('front.rates');
    Route::get('/create','RateController@create')->name('front.rates.create');
    Route::post('/store','RateController@store')->name('front.rates.store');
    Route::get('/edit/{rate}','RateController@edit')->name('front.rates.edit');
    Route::post('/update/{rate}','RateController@update')->name('front.rates.update');
    Route::get('/destroy/{rate}','RateController@destroy')->name('front.rates.destroy');
    });

    Route::prefix('front/systems')->group(function () {
        Route::get('/', 'SystemController@index')->name('front.systems');
        Route::get('/create','SystemController@create')->name('front.systems.create');
        Route::post('/store','SystemController@store')->name('front.systems.store');
        Route::get('/edit/{system}','SystemController@edit')->name('front.systems.edit');
        Route::post('/update/{system}','SystemController@update')->name('front.systems.update');
        Route::get('/destroy/{system}','SystemController@destroy')->name('front.systems.destroy');
        });

    Route::prefix('front/orgs')->group(function () {
            Route::get('/', 'OrgController@index')->name('front.orgs');
            Route::get('/create','OrgController@create')->name('front.orgs.create');
            Route::post('/store','OrgController@store')->name('front.orgs.store');
            Route::get('/edit/{org}','OrgController@edit')->name('front.orgs.edit');
            Route::post('/update/{org}','OrgController@update')->name('front.orgs.update');
            Route::get('/destroy/{org}','OrgController@destroy')->name('front.orgs.destroy');
            });
