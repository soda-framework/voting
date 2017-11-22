<?php

namespace Soda\Voting\Controllers;

use Illuminate\Http\Request;
use Soda\Voting\Models\Category;
use Zofe\Rapyd\DataGrid\DataGrid;
use Zofe\Rapyd\DataFilter\DataFilter;
use Soda\Cms\Http\Controllers\BaseController;

class CategoryController extends BaseController
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            app('soda.interface')->setHeading('Categories')->setHeadingIcon('fa fa-sitemap');
            app('soda.interface')->breadcrumbs()->addLink(route('soda.home'), ucfirst(trans('soda::terminology.home')));

            return $next($request);
        });
    }

    public function anyIndex()
    {
        $filter = DataFilter::source(new Category());
        $filter->add('name', 'Name', 'text');
        $filter->submit('Search');
        $filter->reset('Clear');
        $filter->build();

        $grid = DataGrid::source($filter);
        $grid->add('id', 'ID', true);
        $grid->add('name', 'Name', true);
        $grid->add('description', 'Description');
        $grid->add('image', 'Image');
        $grid->add('created_at|strtotime|date[d/m/Y]', 'Created At', true);
        $grid->add('{{ $id }}', 'Options')->cell(function ($id) {
            $content = '<a href="'.route('voting.categories.get.delete', $id).'" class="btn btn-danger">Delete</a>';
            $content .= ' <a href=" '.route('voting.categories.get.modify', $id).'" class="btn btn-warning">Edit</a>';

            return $content;
        });

        $grid->paginate(20);

        return view('soda.voting::categories.index', compact('filter', 'grid'));
    }

    public function getDelete($id)
    {
        Category::destroy($id);

        return redirect()->route('voting.categories')->with('success', 'Successfully deleted category');
    }

    public function getModify($id = null)
    {
        $category = $id ? Category::find($id) : new Category;

        app('soda.interface')->setHeading($category->exists ? $category->name : 'New Category');
        app('soda.interface')->breadcrumbs()->addLink(route('voting.categories'), 'Categories');

        return view('soda.voting::categories.modify', compact('category'));
    }

    public function postModify(Request $request)
    {
        $this->validate($request, [
            'name'          => 'required|max:128',
            'description'   => 'max:255',
        ]);
        $category = ($request->has('id')) ? Category::find($request->input('id')) : new Category;
        //dd($request, $category);
        $category->name = $request->input('name');
        $category->description = $request->input('description');
        $category->save();

        return redirect()->route('voting.categories.get.modify', ['category' => $category])->with('success', 'Successfully updated category');
    }
}
