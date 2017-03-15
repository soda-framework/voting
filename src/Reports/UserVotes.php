<?php

namespace Soda\Voting\Reports;

use Illuminate\Http\Request;
use Soda\Voting\Models\User;
use Soda\Voting\Models\Vote;
use Soda\Voting\Models\Nominee;
use Soda\Voting\Models\Category;
use Soda\Voting\Reports\Traits\DisplaysUserFields;
use Zofe\Rapyd\Facades\DataGrid;
use Soda\Reports\Foundation\AbstractReporter;

/**
 * Class UserVotes.
 *
 * Generates a report of every singular vote submitted by user
 */
class UserVotes extends AbstractReporter
{
    use DisplaysUserFields;

    public function query(Request $request)
    {
        $votesTable = (new Vote)->getTable();
        $nomineesTable = (new Nominee)->getTable();
        $categoriesTable = (new Category)->getTable();
        $usersTable = (new User)->getTable();

        $query = Vote::select(array_merge($this->gatherUserFields($usersTable), [
                "$nomineesTable.name as nominee",
                "$categoriesTable.name as category",
                "$votesTable.created_at as voted_at"
            ]))
            ->leftJoin($usersTable, "$usersTable.id", '=', "$votesTable.user_id")
            ->leftJoin($nomineesTable, "$nomineesTable.id", '=', "$votesTable.nominee_id")
            ->leftJoin($categoriesTable, "$categoriesTable.id", '=', "$nomineesTable.category_id");

        return $query;
    }

    public function run(Request $request)
    {
        $grid = DataGrid::source($this->query($request));
        $grid = $this->addUserFieldsToGrid($grid);
        $grid->add('nominee', 'Nominee');
        $grid->add('category', 'Category');
        $grid->add('voted_at', 'Voted At');
        $grid->paginate(20)->getGrid($this->getGridView());

        return view($this->getView(), ['report' => $this->report, 'grid' => $grid]);
    }
}
