<?php

namespace Soda\Voting\Controllers;

use Soda\Cms\Http\Controllers\BaseController;
use Soda\Voting\Models\Category;


class VotingController extends BaseController {
    public function getCategories(){
        $nominees = Category::with('nominees')->get();
        return response()->json($nominees);
    }

    public function getVotes(){

    }

    public function postVote(){

    }
}