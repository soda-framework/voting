@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('soda.home') }}">Home</a></li>
        <li><a href="{{ route('voting.categories') }}">Categories</a></li>
        <li class="active">Edit</li>
    </ol>
@stop

@section('head.cms')
    <title>Voting | Categories | Edit</title>
@endsection

@section('content-heading-button')
    @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#category-form'])
@endsection

@include(soda_cms_view_path('partials.heading'), [
    'icon'        => 'fa fa-sitemap',
    'title'       => 'Editing Category ' . @$category->name,
])
@section('content')
    <div class="content-block">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="post" action="{{ route('voting.categories.post.modify') }}" id="category-form">
            <input type="hidden" name="id" value="{{ @$category->id }}" />
            {!! csrf_field() !!}
            {!! SodaForm::text([
                'name'        => 'Category Name',
                'field_name'  => 'name',
            ])->setModel($category) !!}

            {!! SodaForm::textarea([
                'name'        => 'Category Description',
                'field_name'  => 'description',
            ])->setModel($category) !!}
        </form>
    </div>

    <div class="content-bottom">
        @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#category-form'])
    </div>
@endsection
