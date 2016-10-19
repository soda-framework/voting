<?php

Route::group(['prefix' => config('soda.voting.api_path')], function(){
    Route::get('categories', 'VotingController@getCategories')
        ->name('api.voting.get.categories');

    Route::get('votes', 'VotingController@getVotes')
        ->name('api.voting.get.votes');

    Route::post('votes{id}', 'VotingController@postVote')
        ->name('api.voting.post.vote');
});