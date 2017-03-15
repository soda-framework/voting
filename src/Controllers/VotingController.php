<?php

namespace Soda\Voting\Controllers;

use Hash;
use Illuminate\Http\Request;
use Soda\Voting\Models\Nominee;
use Soda\Voting\Models\Category;
use Soda\Voting\Components\Helpers;
use Soda\Cms\Http\Controllers\BaseController;

class VotingController extends BaseController
{
    /**
     * @param null $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategories($id = null)
    {
        $categories = Category::with(['nominees' => function ($q) {
            // get first field
            $fields = reset(config('soda.votes.voting.fields.nominee'));
            $first_field = key($fields);
            $first_field = $first_field ? $first_field : 'name';

            return $q->orderBy($first_field, 'ASC');
        }]);
        if (is_null($id)) {
            $categories = $categories->get();
        } else {
            $categories = $categories->where('id', $id)->first();
            $categories = [$categories];
        }

        return response()->json($categories);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNominees(Request $request)
    {
        if ($request->has('nominees')) {
            $requestNominees = json_decode($request->input('nominees'));
            if (! is_array($requestNominees)) {
                $requestNominees = [$requestNominees];
            }
            $nominees = Nominee::whereIn('id', $requestNominees)->get();

            return response()->json($nominees);
        } else {
            abort(500, 'Expecting a JSON array called nominees in request');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postVote(Request $request)
    {
        if ($request->has('votes')) {
            $votes = json_decode($request->input('votes'));
            if ($request->has('ranked')) {
                $votes = Helpers::truncateVotes($votes, $request->input('ranked'));
            } else {
                $votes = Helpers::truncateVotes($votes);
            }

            $hash = Helpers::hashVotes($votes);

            return response()->json(['votes' => $votes, 'hash' => $hash]);
        } else {
            abort(500, 'Expecting a JSON array called votes in request');
        }
    }
}
