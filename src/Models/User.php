<?php

namespace Soda\Voting\Models;

use Soda\Cms\Models\User as SodaUser;

class User extends SodaUser{
    protected $fillable = [
        'username',
        'email',
        'password',
        'remember_token',
        'application_id',
        'phone',
        'dob',
    ];

    public function Votes(){
        return $this->hasMany(Vote::class);
    }

}
