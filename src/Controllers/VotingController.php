<?php

namespace Soda\Voting\Controllers;

use Illuminate\Http\Request;
use Hash;
use Soda\Cms\Http\Controllers\BaseController;
use Soda\Voting\Components\Helpers;
use Soda\Voting\Models\Category;
use Soda\Voting\Models\Nominee;


class VotingController extends BaseController {


    /**
     * @param null $id
     * @return \Illuminate\Http\JsonResponse
     */
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNominees(Request $request){
        if($request->has('nominees')){
            $requestNominees = json_decode($request->input('nominees'));
            if(!is_array($requestNominees)) $requestNominees = [$requestNominees];
            $nominees = Nominee::whereIn('id', $requestNominees)->get();
            return response()->json($nominees);
        }else{
            abort(500, 'Expecting a JSON array called nominees in request');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postVote(Request $request){
        if($request->has('votes')){
            $votes = json_decode($request->input('votes'));
            $votes = Helpers::truncateVotes($votes);
            $hash = Helpers::hashVotes($votes);
            return response()->json(['votes' => $votes, 'hash' => $hash]);
        }else{
            abort(500, 'Expecting a JSON array called votes in request');
        }
    }
}