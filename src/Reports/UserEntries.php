<?php

namespace Soda\Voting\Reports;

use Illuminate\Http\Request;
use Soda\Voting\Models\User;
use Soda\Voting\Models\Vote;
use Zofe\Rapyd\Facades\DataGrid;
use Soda\Reports\Foundation\AbstractReporter;

/**
 * Class UserEntries.
 *
 * Generates a report of every entry/submission (sometimes of multiple votes) submitted by a user
 */
class UserEntries extends AbstractReporter
{
    public function query(Request $request)
    {
        $usersTable = (new User)->getTable();
        $votesTable = (new Vote)->getTable();

        $subQuery = Vote::select('user_id', DB::raw('DATE_FORMAT(created_at, \'%Y-%m-%d\') as date'))
            ->groupBy('user_id')
            ->groupBy('date');

        $query = DB::table(DB::raw('('.$subQuery->toSql().') votes'))
            ->select(
                "$usersTable.id",
                 "$usersTable.username",
                 "$usersTable.email",
                 "$usersTable.phone",
                 "$usersTable.dob",
                 DB::raw('count(*) as entries')
            )
            ->leftJoin('users', "$usersTable.id", '=', "$votesTable.user_id")
            ->groupBy('user_id')
            ->orderBy('entries', 'DESC')
            ->orderBy('id');

        return $query;
    }

    public function run(Request $request)
    {
        $grid = DataGrid::source($this->query($request));
        $grid->add('id', 'ID');
        $grid->add('username', 'Name');
        $grid->add('email', 'Email');
        $grid->add('phone', 'Phone');
        $grid->add('dob', 'DOB');
        $grid->add('entries', 'Entries');
        $grid->paginate(20)->getGrid($this->getGridView());

        return view($this->getView(), ['report' => $this->report, 'grid' => $grid]);
    }
}
