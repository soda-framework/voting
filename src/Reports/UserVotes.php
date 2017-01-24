<?php
namespace Soda\Voting\Reports;

use Illuminate\Http\Request;
use Soda\Reports\Foundation\AbstractReporter;
use Soda\Voting\Models\Category;
use Soda\Voting\Models\Nominee;
use Soda\Voting\Models\User;
use Soda\Voting\Models\Vote;
use Zofe\Rapyd\Facades\DataGrid;

class UserVotes extends AbstractReporter{

    public function query(Request $request){
        $votes = (new Vote())->getTable();
        $nominees = (new Nominee())->getTable();
        $categories = (new Category())->getTable();
        $users = (new User())->getTable();
        $query = Vote::select("$users.username as name", "$votes.ip_address", "$users.email", "$users.phone",
            "$users.dob", "$nominees.name as nominee", "$categories.name as category", "$votes.created_at as voted_at")
            ->leftJoin($users, "$users.id", '=', "$votes.user_id")
            ->leftJoin($nominees, "$nominees.id", '=', "$votes.nominee_id")
            ->leftJoin($categories, "$categories.id", '=', "$nominees.category_id");
        return $query;
    }

    public function run(Request $request){
        $grid = DataGrid::source($this->query($request));
        $grid->add('name', 'Name');
        $grid->add('ip_address', 'IP Address');
        $grid->add('email', 'Email');
        $grid->add('phone', 'Phone');
        $grid->add('dob', 'DOB');
        $grid->add('nominee', 'Nominee');
        $grid->add('category', 'Category');
        $grid->add('voted_at', 'Voted At');
        $grid->paginate(20)->getGrid($this->getGridView());
        return view($this->getView(), ['report' => $this->report, 'grid' => $grid]);
    }
}