<?php

namespace Soda\Voting\Controllers;

use Illuminate\Http\Request;
use Hash;
use Soda\Cms\Http\Controllers\BaseController;
use Soda\Voting\Models\Category;
use Soda\Voting\Models\Nominee;


class VotingController extends BaseController {
    public function getCategories($id = null){
        $categories = Category::with('nominees');
        if(is_null($id)){
            $categories = $categories->get();
        }else{
            $categories = $categories->where('id', $id)->first();
        }
        return response()->json($categories);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function postVote(Request $request){
        if($request->has('votes')){
            $votes = json_decode($request->input('votes'));
            $categories = Nominee::whereIn('id', $votes)->get()->groupBy('category_id');
            $categories = $categories->map(function($category, $key){
                return $category->pluck('id')->toArray();
            });
            foreach($categories as $category){
                if(count($category) > config('soda.votes.max_votes_per_category') && config('soda.votes.replace_votes')){
                    //Need to remove the first occurring vote for this category
                    for($i = 0; $i < count($votes); $i++){
                        if(in_array($votes[$i], $category)){
                            unset($votes[$i]);
                            break;
                        }
                    }
                }else if(count($category) > config('soda.votes.max_votes_per_category')){
                    //remove the last occurring entry in the votes array
                    for($i = (count($votes) -1); $i >= 0; $i--){
                        if(in_array($votes[$i], $category)){
                            unset($votes[$i]);
                            break;
                        }
                    }
                }
            }
            $hash = Hash::make(implode($votes));
            return response()->json(['votes' => $votes, 'hash' => $hash]);
        }else{
            throw new \Exception('Expecting a JSON array called votes in request');
        }
    }
}