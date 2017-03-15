@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('soda.home') }}">Home</a></li>
        <li><a href="{{ route('voting.reports') }}">Reports</a></li>
        <li class="active">Votes</li>
    </ol>
@stop

@section('head.cms')
    <title>Voting | Result</title>
@endsection

@include(soda_cms_view_path('partials.heading'), [
    'icon'        => 'fa fa-copy',
    'title'       => 'Reports',
])

@section('content')
    <div class="cotent-block">
        @foreach($categories as $category => $thing)
            <h3>{{ $category }}</h3>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th style="width: 5%;">Position</th>
                        <th style="width: 25%;">Nominee Name</th>
                        <th style="width: 25%;">Nominee Description</th>
                        <th style="width: 20%;">Number of votes</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($thing as $vote)
                        <tr>
                            <td style="width: 5%;">
                                {{ $loop->iteration }}
                            </td>
                            <td style="width: 25%;">
                                {{ $vote->name }}
                            </td>
                            <td style="width: 25%;">
                                {{ $vote->description }}
                            </td>
                            <td style="width: 20%;">
                                {{ $vote->votes_count }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
@endsection