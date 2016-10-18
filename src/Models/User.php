<?php

namespace Soda\Voting\Models;

use Soda\Cms\Models\User as SodaUser;

class User extends SodaUser{

    public function Votes(){
        return $this->hasMany(Votes::class);
    }

}
