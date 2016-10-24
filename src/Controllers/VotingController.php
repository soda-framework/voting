<?php

namespace Soda\Voting\Controllers;

use Illuminate\Http\Request;
use Hash;
use Soda\Cms\Http\Controllers\BaseController;
use Soda\Voting\Components\Helpers;
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
            $hash = Hash::make(implode($votes));
            $votes = Helpers::truncateVotes($votes);
            return response()->json(['votes' => $votes, 'hash' => $hash]);
        }else{
            throw new \Exception('Expecting a JSON array called votes in request');
        }
    }
}