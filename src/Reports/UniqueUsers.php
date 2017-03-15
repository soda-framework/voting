<?php

namespace Soda\Voting\Reports;

use Illuminate\Http\Request;
use Soda\Voting\Models\User;
use Soda\Voting\Models\Vote;
use Zofe\Rapyd\Facades\DataGrid;
use Soda\Reports\Foundation\AbstractReporter;
use Soda\Voting\Reports\Traits\DisplaysUserFields;

/**
 * Class UniqueUsers.
 *
 * Generates a report of every unique user that has voted
 */
class UniqueUsers extends AbstractReporter
{
    use DisplaysUserFields;

    public function query(Request $request)
    {
        $votesTable = (new Vote)->getTable();
        $usersTable = (new User)->getTable();

        $query = Vote::select($this->gatherUserFields($usersTable))
            ->leftJoin($usersTable, "$usersTable.id", '=', "$votesTable.user_id")
            ->groupBy("$usersTable.email");

        return $query;
    }

    public function run(Request $request)
    {
        $grid = DataGrid::source($this->query($request));
        $grid = $this->addUserFieldsToGrid($grid);

        $grid->paginate(20)->getGrid($this->getGridView());

        return view($this->getView(), ['report' => $this->report, 'grid' => $grid]);
    }
}
