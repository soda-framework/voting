<?php

namespace Soda\Voting\Reports;

use Illuminate\Http\Request;
use Soda\Voting\Models\User;
use Soda\Voting\Models\Vote;
use Zofe\Rapyd\Facades\DataGrid;
use Soda\Reports\Foundation\AbstractReporter;

/**
 * Class UniqueUsers.
 *
 * Generates a report of every unique user that has voted
 */
class UniqueUsers extends AbstractReporter
{
    public function query(Request $request)
    {
        $votesTable = (new Vote)->getTable();
        $usersTable = (new User)->getTable();

        $query = Vote::select(
                "$usersTable.username as name",
                "$usersTable.email",
                "$usersTable.phone",
                "$usersTable.dob"
            )
            ->leftJoin($usersTable, "$usersTable.id", '=', "$votesTable.user_id")
            ->groupBy("$usersTable.email");

        return $query;
    }

    public function run(Request $request)
    {
        $grid = DataGrid::source($this->query($request));
        $grid->add('name', 'Name');
        $grid->add('email', 'Email');
        $grid->add('phone', 'Phone');
        $grid->add('dob', 'DOB');

        $grid->paginate(20)->getGrid($this->getGridView());

        return view($this->getView(), ['report' => $this->report, 'grid' => $grid]);
    }
}
