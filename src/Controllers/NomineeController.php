<?php

namespace Soda\Voting\Controllers;

use Soda\Cms\Http\Controllers\BaseController;
use Soda\Voting\Models\Category;
use Soda\Voting\Models\Nominee;
use Zofe\Rapyd\DataFilter\DataFilter;
use Zofe\Rapyd\DataGrid\DataGrid;
use Illuminate\Http\Request;

class NomineeController extends BaseController{
    public function anyIndex() {
        $filter = DataFilter::source((new Nominee)->with('category'));

        foreach (config('soda.votes.voting.fields.nominee') as $field_name => $field) {
            if( @$field['filter']['enabled'] === true ){
                $filter->add($field_name, @$field['label'], @$field['filter']['type']);
            }
        }

        $filter->add('category_id', 'Category', 'select')->options(
            ['-1' => 'All Categories'] + Category::all()->pluck('name', 'id')->toArray())
                ->insertValue(-1)->scope('hasCategory');
        $filter->submit('Search');
        $filter->reset('Clear');
        $filter->build();

        $grid = DataGrid::source($filter);
        $grid->add('id', 'ID', true);

        foreach (config('soda.votes.voting.fields.nominee') as $field_name => $field) {
            if( @$field['grid']['enabled'] === true ){

                if( @$field['type'] == 'fancy_upload' ){
                    $grid->add($field_name, @$field['label'])->cell(function($image){
                        $content = '<img class="voting_nominee__image" src="' . $image . '" alt="Nominee image" style="max-width: 100px;"/>';
                        return $content;
                    });
                }
                else{
                    $grid->add($field_name, @$field['label'], @$field['grid']['sortable']);
                }

            }
        }

        $grid->add('category.name', 'Category', true);

        $grid->add('{{ $id }}', 'options')->cell(function($id){
            $content = '<a href="' . route('voting.nominees.get.delete', $id) . '" class="btn btn-danger">Delete</a>';
            $content .= ' <a href=" ' . route('voting.nominees.get.modify', $id) . '" class="btn btn-warning">Edit</a>';
            return $content;
        });
        $grid->paginate(10);
        return view('soda.voting::nominees.index', compact('filter', 'grid'));
    }


    public function getDelete($id){
        Nominee::destroy($id);
        return back()->with('success', 'Successfully deleted nominee');
    }

    public function getModify($id = null){
        $nominee = null;
        if (!is_null($id)){
            $nominee = Nominee::firstOrNew(['id' => $id]);
        }
        return view('soda.voting::nominees.modify', compact('nominee'));
    }

    public function postModify(Request $request){

        // rules
        $rules = [];
        foreach (config('soda.votes.voting.fields.nominee') as $field_name => $field) {
            if( @$field['rules'] ){
                $rules[$field_name] = $field['rules'];
            }
        }

        $this->validate($request, $rules);
        $nominee = ($request->has('id'))? Nominee::find($request->id) : new Nominee;

        foreach (config('soda.votes.voting.fields.nominee') as $field_name => $field) {
            $nominee->{$field_name} = $request->input($field_name);
        }

        $nominee->category_id = $request->input('category_id');
        $nominee->save();

        return redirect()->route('voting.nominees.get.modify', $nominee->id)->with('success', 'Successfully edited');
    }
}
