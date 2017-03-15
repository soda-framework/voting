<?php

namespace Soda\Voting\Reports;

use Illuminate\Http\Request;
use Soda\Voting\Models\User;
use Soda\Voting\Models\Vote;
use Soda\Voting\Models\Nominee;
use Soda\Voting\Models\Category;
use Zofe\Rapyd\Facades\DataGrid;
use Soda\Reports\Foundation\AbstractReporter;
use Soda\Voting\Reports\Traits\DisplaysUserFields;
use Soda\Voting\Reports\Traits\DisplaysNomineeFields;

/**
 * Class UserVotes.
 *
 * Generates a report of every singular vote submitted by user
 */
class UserVotes extends AbstractReporter
{
    use DisplaysUserFields, DisplaysNomineeFields;

    public function query(Request $request)
    {
        $this->disableStrictMode();
        
        $votesTable = (new Vote)->getTable();
        $nomineesTable = (new Nominee)->getTable();
        $categoriesTable = (new Category)->getTable();
        $usersTable = (new User)->getTable();

        $fields = array_merge($this->gatherUserFields($usersTable), $this->gatherNomineeFields($nomineesTable), [
            "$categoriesTable.name as category",
            "$votesTable.created_at as voted_at",
        ]);

        $query = Vote::select($fields)
            ->leftJoin($usersTable, "$usersTable.id", '=', "$votesTable.user_id")
            ->leftJoin($nomineesTable, "$nomineesTable.id", '=', "$votesTable.nominee_id")
            ->leftJoin($categoriesTable, "$categoriesTable.id", '=', "$nomineesTable.category_id");

        return $query;
    }

    public function run(Request $request)
    {
        $grid = DataGrid::source($this->query($request));
        $grid = $this->addUserFieldsToGrid($grid);
        $grid = $this->addNomineeFieldsToGrid($grid);
        $grid->add('category', 'Category');
        $grid->add('voted_at', 'Voted At');
        $grid->paginate(20)->getGrid($this->getGridView());

        return view($this->getView(), ['report' => $this->report, 'grid' => $grid]);
    }
}
