<?php

namespace Soda\Voting\Reports;

use Soda\Voting\Components\AbstractReport;
use Soda\Voting\Models\Category;
use Soda\Voting\Models\Nominee;
use Soda\Voting\Models\Vote;
use Zofe\Rapyd\DataFilter\DataFilter;
use Zofe\Rapyd\DataGrid\DataGrid;

class IndividualVoteReport extends AbstractReport{
    public function export(){
        return 'Not yet implemented';
    }

    public function view(){
        $filter = DataFilter::source(Vote::with('user', 'nominee'));
        $filter->add('nominee.name','Nominee Name', 'text');
        $filter->add('user.username', 'User Name', 'text');
        $filter->add('ip_address', 'IP Address', 'text');
        $filter->submit('Search');
        $filter->reset('Clear');
        $filter->build();

        $grid = DataGrid::source($filter);
        $grid->add('id', 'ID', true);
        $grid->add('nominee.name', 'Nominee Name', true);
        $grid->add('user.username','User Name', true);
        $grid->add('user.email', 'User email', true);
        $grid->add('ip_address', 'IP Address');

        $grid->paginate(20);

        return view('soda.voting::reports.votes.individual', compact('filter', 'grid'));
    }
}