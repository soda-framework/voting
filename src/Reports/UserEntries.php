<?php

namespace Soda\Voting\Reports;

use Illuminate\Http\Request;
use Soda\Voting\Models\User;
use Soda\Voting\Models\Vote;
use Zofe\Rapyd\Facades\DataGrid;
use Illuminate\Support\Facades\DB;
use Soda\Reports\Foundation\AbstractReporter;
use Soda\Voting\Reports\Traits\DisplaysUserFields;

/**
 * Class UserEntries.
 *
 * Generates a report of every entry/submission (sometimes of multiple votes) submitted by a user
 */
class UserEntries extends AbstractReporter
{
    use DisplaysUserFields;

    public function query(Request $request)
    {
        $this->disableStrictMode();

        $votesTable = (new Vote)->getTable();
        $usersTable = (new User)->getTable();

        $subQuery = Vote::select(
                "$votesTable.user_id",
                "$votesTable.created_at",
                DB::raw('count(*) as votes')
            )
            ->groupBy('user_id')
            ->groupBy('created_at');

        $fields = array_merge($this->gatherUserFields($usersTable), [
            DB::raw('count(*) as entries'),
            DB::raw('sum(votes) as votes'),
        ]);

        $query = DB::table(DB::raw('('.$subQuery->toSql().') votes'))
            ->select($fields)
            ->leftJoin($usersTable, "$usersTable.id", '=', 'votes.user_id')
            ->groupBy('votes.user_id')
            ->orderBy('entries', 'DESC')
            ->orderBy('id');

        return $query;
    }

    public function run(Request $request)
    {
        $grid = DataGrid::source($this->query($request));
        $grid = $this->addUserFieldsToGrid($grid);
        $grid->add('entries', 'Entries');
        $grid->add('votes', 'Total Votes');
        $grid->paginate(20)->getGrid($this->getGridView());

        return view($this->getView(), ['report' => $this->report, 'grid' => $grid]);
    }
}
