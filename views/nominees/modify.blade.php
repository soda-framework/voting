@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('soda.home') }}">Home</a></li>
        <li><a href="{{ route('voting.nominees') }}">Nominees</a></li>
        <li class="active">Edit</li>
    </ol>
@stop

@section('head.cms')
    <title>Voting | Nominees</title>
@endsection

@section('content-heading-button')
    @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#category-form'])
@endsection

@include(soda_cms_view_path('partials.heading'), [
    'icon'        => 'fa fa-user',
    'title'       => 'Editing Nominee - ' . @$nominee->name,
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
        <form method="post" action="{{ route('voting.nominees.post.modify') }}" id="category-form" enctype="multipart/form-data">
            <input type="hidden" name="id" value="{{ @$nominee->id }}" />
            {!! csrf_field() !!}
            {!! SodaForm::text([
                'name'        => 'Nominee Name',
                'field_name'  => 'name',
            ])->setModel($nominee) !!}

            {!! SodaForm::textarea([
                'name'        => 'Nominee Description',
                'field_name'  => 'description',
            ])->setModel($nominee) !!}

            {!! SodaForm::fancyupload([
                'name'          => 'Nominee Image',
                'field_name'    => 'image'
            ])->setModel($nominee) !!}

            {!! SodaForm::tinymce([
                'name'          => 'Nominee Details',
                'field_name'    => 'details'
            ])->setModel($nominee) !!}

            {!! SodaForm::dropdown([
                'name'  => 'Nominee Category',
                'field_name'    => 'category_id',
                'field_params'       => [
                    'options'   => \Soda\Voting\Models\Category::pluck('name', 'id'),
                ]
            ])->setModel($nominee) !!}
        </form>
    </div>

    <div class="content-bottom">
        @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#category-form'])
    </div>
@endsection
