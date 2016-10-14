<?php

Route::group(['prefix' => config('soda.cms.path')], function () {
  Route::get('voting/categories', function(){
    return "This is the categories page";
  })->name('voting.categories');

  Route::get('voting/nominees', function(){
    return('This is the nomninees page');
  })->name('voting.nominees');

  Route::get('voting/reports', function(){
    return('This is the reports page');
  })->name('voting.reports');
});
