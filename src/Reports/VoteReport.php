<?php

namespace Soda\Voting\Reports;

use Soda\Voting\Components\AbstractReport;
use Soda\Voting\Models\Category;
use Soda\Voting\Models\Nominee;
use Soda\Voting\Models\Vote;


class VoteReport extends AbstractReport{

    public function getData(){
        $nominees = Nominee::withCount('votes')->orderBy('votes_count', 'DESC')->get();
        $categories = Category::all()->keyBy('name');
        $categories->transform(function($category){
            $votes = Nominee::withCount('votes')->where('category_id', $category->id);
            return $votes->orderBy('votes_count', 'DESC')->get();
        });
        return $categories;
    }

    public function view(){
        $categories = $this->getData();
        return view('soda.voting::reports.votes.index', compact('categories'));
    }

    public function export(){
        $categories = $this->getData();
        $file_name = storage_path() . '/export_' . uniqid() . '.csv';
        $handle = fopen($file_name, 'w');
        foreach($categories as $category=>$votes){
            fputcsv($handle, [$category]);
            fputcsv($handle, ['Nominee Name', 'Nominee Description', 'Number of Votes']);
            foreach($votes as $vote){
                fputcsv($handle, [$vote->name, $vote->description, $vote->votes_count]);
            }
        }
        fclose($handle);
        return response()->download($file_name);
    }
}