<?php

namespace Soda\Voting\Reports;

use Soda\Voting\Components\AbstractReport;
use Soda\Voting\Models\Category;
use Soda\Voting\Models\Nominee;
use Soda\Voting\Models\Vote;
use Zofe\Rapyd\DataFilter\DataFilter;
use Zofe\Rapyd\DataGrid\DataGrid;
use Carbon\Carbon;

class IndividualVoteReport extends AbstractReport{
    public function export(){
        $votes = Vote::with('user', 'nominee', 'nominee.category')->get();
        $file_path = storage_path() . '/individual_report_' . uniqid() . '.csv';
        $handle = fopen($file_path, 'w');
        fputcsv($handle, ['Nominee Name', 'Nominee Category','User Name', 'User Email', 'IP Address', 'Time']);
        foreach($votes as $vote){
            fputcsv($handle, [$vote->nominee->name, $vote->nominee->category->name, $vote->user->username, $vote->user->email, $vote->ip_address, $vote->created_at]);
        }
        fclose($handle);
        return response()->download($file_path);
    }

    public function view(){
        $filter = DataFilter::source(Vote::with('user', 'nominee', 'nominee.category'));
        $filter->add('nominee.name','Nominee Name', 'text');
        $filter->add('user.username', 'User Name', 'text');
        $filter->add('ip_address', 'IP Address', 'text');
        $filter->submit('Search');
        $filter->reset('Clear');
        $filter->build();

        $grid = DataGrid::source($filter);
        $grid->add('id', 'ID', true);
        $grid->add('nominee.name', 'Nominee Name', true);
        $grid->add('nominee.category.name', 'Nominee Category');
        $grid->add('user.username','User Name', true);
        $grid->add('user.email', 'User email', true);
        $grid->add('ip_address', 'IP Address');
        $grid->add('created_at', 'Time', true)->cell(function($value){
            return Carbon::parse($value)->timezone('Australia/Sydney')->toDayDateTimeString();
        });

        $grid->paginate(20);

        return view('soda.votes.voting::reports.votes.individual', compact('filter', 'grid'));
    }
}
