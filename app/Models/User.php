<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function hasVoted(Post $post) : bool
    {
        return !!$this->vote()->where('post_id', $post->id)->count();
    }

    public function voteTo(Post $post, int $type = Vote::UP_VOTE)
    {
        return $post->vote()->save(
            $this->vote()->create(compact('type'))
        );
    }

    public function unVoteTo(Post $post)
    {
        return $this->vote()->where('post_id', $post->id)->delete();
    }

    public function vote()
    {
        return $this->hasMany(Vote::class);
    }
}
