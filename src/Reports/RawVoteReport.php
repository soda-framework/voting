<?php

namespace Soda\Voting\Reports;

use Illuminate\Http\Request;
use Soda\Voting\Models\Vote;
use Soda\Voting\Models\Nominee;
use Soda\Voting\Models\Category;
use Zofe\Rapyd\Facades\DataGrid;
use Illuminate\Support\Facades\DB;
use Soda\Reports\Foundation\AbstractReporter;

/**
 * @deprecated
 *
 * Class RawVoteReport.
 *
 * Generates a report of votes per nominee, listing total votes
 */
class RawVoteReport extends AbstractReporter
{
    public function query(Request $request)
    {
        $votesTable = (new Vote)->getTable();
        $nomineesTable = (new Nominee)->getTable();
        $categoriesTable = (new Category)->getTable();

        $query = Vote::select(
                "$nomineesTable.id as id",
                "$nomineesTable.name as name",
                "$categoriesTable.name as category",
                DB::raw("count($votesTable.nominee_id) as votes")
            )
            ->leftJoin($nomineesTable, "$nomineesTable.id", '=', "$votesTable.nominee_id")
            ->leftJoin($categoriesTable, "$nomineesTable.category_id", '=', "$categoriesTable.id");

        if ($request->has('categoryId')) {
            $query = $query->where("$categoriesTable.id", $request->input('categoryId'));
        }

        return $query->groupBy("$votesTable.nominee_id")
            ->orderBy('category')
            ->orderBy('votes', 'DESC');
    }

    public function run(Request $request)
    {
        $grid = DataGrid::source($this->query($request));
        $grid->add('name', 'Name');
        $grid->add('category', 'Category');
        $grid->add('votes', 'Votes');

        $grid->paginate(20)->getGrid($this->getGridView());

        return view($this->getView(), ['report' => $this->report, 'grid' => $grid]);
    }
}
