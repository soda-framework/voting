<?php

namespace Soda\Voting\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model{
    protected $table = 'voting_categories';

    protected $fillable = [
        'name',
        'description',
        'created_at',
        'updated_at',
    ];

    public function nominees(){
        return $this->hasMany(Nominee::class);
    }
}
