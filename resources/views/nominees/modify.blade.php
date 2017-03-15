@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('soda.home') }}">Home</a></li>
        <li><a href="{{ route('voting.nominees') }}">Nominees</a></li>
        <li class="active">Edit</li>
    </ol>
@stop

@section('head.cms')
    <title>Voting | Nominees | Edit</title>
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

            @foreach(config('soda.votes.voting.fields.nominee') as $field_name => $field)
                {!! SodaForm::{$field['type']}([
                    'name'        => $field['label'],
                    'field_name'  => $field_name,
                ])->setModel($nominee) !!}
            @endforeach

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
