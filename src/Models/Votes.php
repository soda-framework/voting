<?php

namespace Soda\Voting\Models;

use Illuminate\Database\Eloquent\Model;

class Votes extends Model{

    protected $table = 'voting_votes';


    public function User(){
        return $this->hasOne(User::class);
    }

}
