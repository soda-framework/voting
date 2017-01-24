<?php

namespace Soda\Voting\Reports;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Soda\Reports\Foundation\AbstractReporter;
use Soda\Voting\Models\Category;
use Soda\Voting\Models\Nominee;
use Soda\Voting\Models\Vote;
use Zofe\Rapyd\Facades\DataGrid;

class RawVoteReport extends AbstractReporter{
    public function query(Request $request){
        $votes = (new Vote())->getTable();
        $nominees = (new Nominee())->getTable();
        $categories = (new Category())->getTable();
        $query = Vote::select("$nominees.id as id", "$nominees.name as name", "$categories.name as category", DB::raw("count($votes.nominee_id) as votes"))
            ->leftJoin($nominees, "$nominees.id", '=', "$votes.nominee_id")
            ->leftJoin($categories, "$nominees.category_id", '=', "$categories.id");
        if($request->has('categoryId')) $query = $query->where("$categories.id", $request->input('categoryId'));
        $query->groupBy("$votes.nominee_id")->orderBy('category')->orderBy('votes', 'DESC');
        return $query;
    }


    public function run(Request $request) {
        $grid = DataGrid::source($this->query($request));
        $grid->add('name', 'Name');
        $grid->add('category', 'Category');
        $grid->add('votes', 'Votes');

        $grid->paginate(20)->getGrid($this->getGridView());

        return view($this->getView(), ['report' => $this->report, 'grid' => $grid]);
    }
}