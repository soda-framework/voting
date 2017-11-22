@extends(soda_cms_view_path('layouts.inner'))

@section('content-heading-button')
    @include(soda_cms_view_path('partials.buttons.create'), ['url' => route('voting.categories.get.modify')])
@endsection

@section('content')
    <div class="content-top">
        {!! $filter !!}
    </div>

    <div class="content-block">
        {!! $grid  !!}
    </div>
@endsection
