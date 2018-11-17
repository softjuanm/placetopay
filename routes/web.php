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

// Index route
Route::get('/', function () {
    return view('index');
})->name('home');

// Payment routes group
Route::group(['as' => 'payment::', 'prefix' => 'payment'], function () {

    Route::get('/', array(
        'as' => 'index',
        'uses' => 'Payments@index'
    ));
    
    Route::post('/process', array(
        'as' => 'process',
        'uses' => 'Payments@process'
    ));
    
    Route::get('/result', array(
        'as' => 'result',
        'uses' => 'Payments@processResult'
    ));
    
    Route::get('/resume', array(
        'as' => 'resume',
        'uses' => 'Payments@resume'
    ));
    

});