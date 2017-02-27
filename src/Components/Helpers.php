<?php

namespace Soda\Voting\Components;


use Illuminate\Support\Collection;
use Soda\Voting\Models\Category;
use Soda\Voting\Models\Nominee;
use Hash;

class Helpers
{
    static public function truncateVotes($votes, $ranked= false)
    {
        $categories = Nominee::whereIn('id', $votes)->get()->groupBy('category_id');

        //Sorting values into order found in the votes array
        $categories->transform(function ($category) use ($votes,$ranked) {
            $category = $category->pluck('id')->toArray();

            if( $ranked ){
                $new_category = array_fill(0, config('soda.votes.voting.max_votes_per_category'), null); // create to suit length of votes
                foreach ($votes as $key=>$vote) {
                    if (in_array($vote, $category)) $new_category[$key] = $vote; // preserve rank in current category
                }
                $new_category = collect($new_category);
            }
            else{
                $new_category = New Collection();
                foreach ($votes as $vote) {
                    if (in_array($vote, $category)) $new_category->push($vote);
                }
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
    
    /**
     * Checks whether every ctagory has been given a vote
     *
     * @param $votes
     *
     * @return bool
     */
    static public function allCategoriesVoted($votes){
        $categories = Category::all()->pluck('id');
        $voted_categories = Nominee::whereIn('id', $votes)->get()->pluck('category_id');

        return $categories->diff($voted_categories)->count() <= 0;
    }
}
