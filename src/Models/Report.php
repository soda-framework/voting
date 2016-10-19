<?php

namespace Soda\Voting\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model{
    protected $table = "voting_reports";

    protected $fillable = [
        'name',
        'action',
        'last_run',
        'created_at',
        'updated_at'
    ];

}
