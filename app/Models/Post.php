<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    const IMAGE = 1;

    const VIDEO = 2;

    protected $fillable = [
        'title'
    ];

    protected $casts = [
        'payload' => 'json'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vote()
    {
        return $this->hasMany(Vote::class);
    }
}
