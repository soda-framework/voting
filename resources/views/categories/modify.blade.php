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
        <form method="post" action="{{ route('voting.categories.post.modify') }}" id="category-form">
            <input type="hidden" name="id" value="{{ @$category->id }}" />
            {!! csrf_field() !!}
            {!! app('soda.form')->text([
                'name'        => 'Category Name',
                'field_name'  => 'name',
            ])->setModel($category) !!}

            {!! app('soda.form')->textarea([
                'name'        => 'Category Description',
                'field_name'  => 'description',
            ])->setModel($category) !!}
            
            {!! app('soda.form')->fancy_upload([
                'name'        => 'Category Image',
                'field_name'  => 'image',
            ])->setModel($category) !!}
        </form>
    </div>

    <div class="content-bottom">
        @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#category-form'])
    </div>
@endsection
