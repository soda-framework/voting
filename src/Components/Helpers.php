<?php

namespace Soda\Voting\Components;


use Illuminate\Support\Collection;
use Soda\Voting\Models\Nominee;

class Helpers
{
    static public function truncateVotes($votes)
    {
        $categories = Nominee::whereIn('id', $votes)->get()->groupBy('category_id');
        //Sorting values into order found in the votes array
        $categories->transform(function ($category) use ($votes) {
            $category = $category->pluck('id')->toArray();
            $new_category = New Collection();
            foreach ($votes as $vote) {
                if (in_array($vote, $category)) $new_category->push($vote);
            }
            return $new_category;
        });
        foreach ($categories as $category) {
            while ($category->count() > config('soda.votes.voting.max_votes_per_category')) {
                if (config('soda.votes.voting.replace_votes')) {
                    $category->shift();
                } else {
                    $category->pop();
                }
            }
        }
        $categories = $categories->collapse();
        return $categories->toArray();
    }
}