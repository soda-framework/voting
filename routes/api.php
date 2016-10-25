<?php

Route::group(['prefix' => config('soda.voting.api_path')], function(){
    Route::get('categories/{id?}', 'VotingController@getCategories')
        ->name('api.voting.get.categories');

    Route::get('votes', 'VotingController@getVotes')
        ->name('api.voting.get.votes');

    Route::get('nominees', 'VotingController@getNominees')
        ->name('api.voting.get.nominees');

    Route::post('votes', 'VotingController@postVote')
        ->name('api.voting.post.vote');
});