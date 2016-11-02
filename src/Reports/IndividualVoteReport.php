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
        $filter->add('vote.nominee.name','Nominee Name', 'text');
        $filter->add('ip_address', 'IP Address', 'text');

        $filter->build();

        $grid = 'sup';
        return view('soda.voting::reports.votes.individual', compact('filter', 'grid'));
    }
}