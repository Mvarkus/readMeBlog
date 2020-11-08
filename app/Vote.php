<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    protected $dates = ['allowed_to_vote_from'];

    public function post()
    {
        return $this->belognsTo('App\Post');
    }

    public function user()
    {
        return $this->belognsTo('App\User');
    }
}
