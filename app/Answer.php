<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    public function takerAnswers()
    {
        return $this->hasMany('App\TakerAnswer');
    }

    public function takerAnswer()
    {
        return $this->takerAnswers()->where('user_id', Auth::user()->id)->first();
    }
}
