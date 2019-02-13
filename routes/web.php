<?php

Route::get('/{any?}', 'AppController@serve')->where('any', '.*');

Route::group(['prefix' => 'polls'], function () {
    Route::post('create', 'PollController@create');
    Route::post('get/{id}', 'PollController@get');
    Route::post('vote/{id}', 'PollController@vote');

    Route::group(['prefix' => 'manage'], function () {
    	Route::post('/{token}', 'PollController@manage');
    	Route::post('/{token}/{status}', 'PollController@managestatus');
    });
});