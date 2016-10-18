<?php
Route::group(['prefix' => config('soda.cms.path'), 'middleware' => 'soda.auth:soda'], function(){
    Route::group(['prefix' => 'voting'], function(){
        Route::group(['prefix' => 'categories'], function(){
            Route::get('index', 'CategoryController@anyIndex')
            ->name('voting.categories');

            Route::get('delete/{id}', 'CategoryController@getDelete')
                ->name('voting.categories.get.delete');

            Route::get('modify/{id?}', 'CategoryController@getModify')
                ->name('voting.categories.get.modify');

            Route::post('modify', 'CategoryController@postModify')
                ->name('voting.categories.post.modify');
        });

        Route::group(['prefix' => 'nominees'], function(){
            Route::get('index', 'NomineeController@anyIndex')
                ->name('voting.nominees');

            Route::get('delete/{id}', 'NomineeController@getDelete')
                ->name('voting.nominees.get.delete');

            Route::get('modify/{id?}', 'NomineeController@getModify')
                ->name('voting.nominees.get.modify');

            Route::post('modify', 'NomineeController@postModify')
                ->name('voting.nominees.post.modify');
        });

        Route::get('reports', '\Soda\Voting\Controllers\ReportController@anyIndex')
            ->name('voting.reports');
    });
});

