<?php
Route::group(['prefix' => config('soda.cms.path'), 'middleware' => 'soda.auth:soda'], function(){
    Route::group(['prefix' => 'voting'], function(){
        Route::group(['prefix' => 'categories'], function(){
            Route::get('/', 'CategoryController@anyIndex')
            ->name('voting.categories');

            Route::get('delete/{id}', 'CategoryController@getDelete')
                ->name('voting.categories.get.delete');

            Route::get('modify/{id?}', 'CategoryController@getModify')
                ->name('voting.categories.get.modify');

            Route::post('modify', 'CategoryController@postModify')
                ->name('voting.categories.post.modify');
        });

        Route::group(['prefix' => 'nominees'], function(){
            Route::get('/', 'NomineeController@anyIndex')
                ->name('voting.nominees');

            Route::get('delete/{id}', 'NomineeController@getDelete')
                ->name('voting.nominees.get.delete');

            Route::get('modify/{id?}', 'NomineeController@getModify')
                ->name('voting.nominees.get.modify');

            Route::post('modify', 'NomineeController@postModify')
                ->name('voting.nominees.post.modify');
        });

        Route::group(['prefix' => 'reports'], function(){
            Route::get('/', 'ReportController@anyIndex')
                ->name('voting.reports');

            Route::get('run/{id}', 'ReportController@getRun')
                ->name('voting.reports.get.run');
        });
    });
});

