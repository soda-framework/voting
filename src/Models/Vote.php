<?php

namespace Soda\Voting\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model{

    protected $table = 'voting_votes';
    protected $fillable = [
      'user_id',
      'nominee_id',
      'ip_address',
      'created_at',
      'updated_at'
    ];
    
    public function User(){
        return $this->belongsTo(User::class);
    }

    public function Nominee(){
        return $this->belongsTo(Nominee::class);
    }
}
