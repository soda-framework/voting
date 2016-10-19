<?php

namespace Soda\Voting\Controllers;

use Soda\Cms\Http\Controllers\BaseController;
use Soda\Voting\Components\AbstractReport;
use Soda\Voting\Models\Report;
use Zofe\Rapyd\DataFilter\DataFilter;
use Zofe\Rapyd\DataGrid\DataGrid;

class ReportController extends BaseController {

    public function anyIndex(){
        $filter = DataFilter::source(new Report);
        $filter->add('name', 'Name', 'text');
        $filter->submit('Search');
        $filter->reset('Clear');
        $filter->build();

        $grid = DataGrid::source($filter);
        $grid->add('id', 'ID', true);
        $grid->add('name', 'Name', true);
        $grid->add('last_run|strtotime|date[d/m/Y g:i a]', 'Last time run', true);
        $grid->add('{{ $id }}', 'Action')->cell(function($value){
            $content = '<a href="' . route('voting.reports.get.run', $value) . '" class="btn btn-warning">Run</a>';
            return $content;
        });
        $grid->paginate(20);
        return view('soda.voting::reports.index', compact('filter', 'grid'));
    }


    public function getRun($id){
        $report = Report::find($id);
        $handler = app($report->class_path);     //Resolves the users class name
        if($handler instanceof AbstractReport){
            $report->last_run = Carbon::now()->toDateTimeString();
            $report->save();
            return $handler->generateReport();   //Runs the handler method of said class
        }else{
            return back()->with('danger', 'Does not implement Abstract Report interface');
        }
    }
}
