@extends(soda_cms_view_path('layouts.inner'))

@section('content-heading-button')
    @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#category-form'])
@endsection

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
                {!! app('soda.form')->{$field['type']}([
                    'name'        => $field['label'],
                    'field_name'  => $field_name,
                ])->setModel($nominee) !!}
            @endforeach

            {!! app('soda.form')->dropdown([
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
