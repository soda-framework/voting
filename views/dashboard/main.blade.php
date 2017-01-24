<?php
$usersCount = Cache::remember('users.count', 5, function () {
    return [
        'last_update' => \Carbon\Carbon::now(),
        'count' => number_format(\Soda\Voting\Models\User::count())
    ];
});
$votesCount = Cache::remember('votes.count', 5, function () {
    return [
        'last_update' => \Carbon\Carbon::now(),
        'count' => number_format(\Soda\Voting\Models\Vote::count())
    ];
});
?>
<style>
    .content-block, .display-3 {
        margin-top: 0;
    }
</style>
<div class="content-top">
    <h1>
        <span>Dashboard</span>
    </h1>
</div>
<div class="row">
    <div class="col-xs-12 col-md-3">
        <div class="content-block">
            <h2 class="display-3">
                <span>
                    {{$usersCount['count']}}
                </span>
            </h2>
            <small class="text-muted">Unique Users</small>
        </div>
    </div>
    <div class="col-xs-12 col-md-3">
        <div class="content-block">
            <h2 class="display-3">
                <span>
                    {{$votesCount['count']}}
                </span>
            </h2>
            <small class="text-muted">Raw votes</small>
        </div>
    </div>
</div>