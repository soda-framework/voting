@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('soda.home') }}">Home</a></li>
        <li class="active">Nominees</li>
    </ol>
@stop

@section('head.cms')
    <title>Voting | Nominees</title>
@endsection

@section('content-heading-button')
    @include(soda_cms_view_path('partials.buttons.create'), ['url' => route('voting.nominees.get.modify')])
@endsection

@include(soda_cms_view_path('partials.heading'), [
    'icon'        => 'fa fa-users',
    'title'       => 'Nominees',
])

@section('content')
    <div class="content-top">
        {!! $filter !!}
    </div>

    <div class="content-block">
        {!! $grid !!}
    </div>
@endsection
