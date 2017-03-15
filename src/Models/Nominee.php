<?php

namespace Soda\Voting\Models;

use Illuminate\Database\Eloquent\Model;

class Nominee extends Model
{
    protected $table = 'voting_nominees';

    protected $fillable = [
        'name',
        'description',
        'details',
        'image',
        'category_id',
        'created_at',
        'updated_at',
    ];

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getVoteCountAttribute()
    {
        return $this->getRelation('votes');
    }

    public function scopeHasCategory($q, $category_id)
    {
        if ($category_id == -1) {
            return $q;
        }

        return $q->where('category_id', $category_id);
    }
}
