<?php

namespace Soda\Voting\Reports;

use Illuminate\Http\Request;
use Soda\Reports\Foundation\AbstractReporter;
use Soda\Voting\Models\User;
use Soda\Voting\Models\Vote;
use Zofe\Rapyd\Facades\DataGrid;

class UniqueUsers extends AbstractReporter{
    public function query(Request $request){
        $votes = (new Vote())->getTable();
        $users = (new User())->getTable();
        $query = Vote::select("$users.username as name", "$users.email", "$users.phone", "$users.dob")
            ->leftJoin($users,"$users.id", '=', "$votes.user_id")
            ->groupBy("$users.email");
        return $query;
    }

    public function run(Request $request){
        $grid = DataGrid::source($this->query($request));
        $grid->add('name', 'Name');
        $grid->add('email', 'Email');
        $grid->add('phone', 'Phone');
        $grid->add('dob', 'DOB');

        $grid->paginate(20)->getGrid($this->getGridView());
        return view($this->getView(), ['report' => $this->report, 'grid' => $grid]);
    }
}