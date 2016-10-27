<?php

namespace Soda\Voting\Components;


use Illuminate\Support\Collection;
use Soda\Voting\Models\Nominee;
use Hash;

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
        //Truncating each category collection to its max allowed size
        foreach ($categories as $category) {
            while ($category->count() > config('soda.votes.voting.max_votes_per_category')) {
                if (config('soda.votes.voting.replace_votes')) {
                    $category->shift(); //Collection array FIFO queue
                } else {
                    $category->pop();   //Typical stack
                }
            }
        }
        $categories = $categories->collapse();
        return $categories->toArray();
    }

    static public function hashVotes($votes){
        return hash('sha256', implode($votes) . env('APP_KEY'));
    }

    static public function verifyVotes($votes, $hash){
        $new = Helpers::hashVotes($votes);
        return $new === $hash;
    }
}
