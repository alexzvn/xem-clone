<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    const UP_VOTE = 1;

    const DOWN_VOTE = -1;

    use HasFactory;

    protected $fillable = ['type'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUpVoted(Builder $builder)
    {
        return $builder->whereType(static::UP_VOTE);
    }

    public function scopeDownVoted(Builder $builder)
    {
        return $builder->whereType(static::DOWN_VOTE);
    }
}
