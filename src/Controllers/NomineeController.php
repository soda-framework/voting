<?php

namespace Soda\Voting\Controllers;

use Soda\Cms\Http\Controllers\BaseController;
use Soda\Voting\Models\Category;
use Soda\Voting\Models\Nominee;
use Zofe\Rapyd\DataFilter\DataFilter;
use Zofe\Rapyd\DataGrid\DataGrid;
use Illuminate\Http\Request;

class NomineeController extends BaseController{
    public function anyIndex(){
        $filter = DataFilter::source((new Nominee)->with('category'));
        $filter->add('name', 'Name', 'text');
        $filter->add('description', 'Description', 'text');
        $filter->add('category_id', 'Category', 'select')->options(
            ['-1' => 'All Categories'] + Category::all()->pluck('name', 'id')->toArray())
                ->insertValue(-1)->scope('hasCategory');
        $filter->submit('Search');
        $filter->reset('Clear');
        $filter->build();

        $grid = DataGrid::source($filter);
        $grid->add('image', 'Image')->cell(function($image){
            $content = '<img class="voting_nominee__image" src="' . $image . '" alt="Nominee image" style="max-width: 100px;"/>';
            return $content;
        });
        $grid->add('id', 'ID', true);
        $grid->add('name', 'Name', true);
        $grid->add('description', 'Description');
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
        $this->validate($request, [
            'name'          => 'required|max:128',
            'description'   => 'required|max:255',
            'details'       => 'required|max:1000',
            'category_id'   => 'required|integer'
        ]);
        $nominee = ($request->has('id'))? Nominee::find($request->id) : new Nominee;
        $nominee->name = $request->input('name');
        $nominee->description = $request->input('description');
        $nominee->details = $request->input('details');
        $nominee->category_id = $request->input('category_id');
        $nominee->save();

        return redirect()->route('voting.nominees.get.modify', $nominee->id)->with('success', 'Successfully edited ' . $nominee->name);
    }
}
