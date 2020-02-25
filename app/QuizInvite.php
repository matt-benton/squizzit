<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class QuizInvite extends Model
{
    protected $fillable = ['email', 'quiz_id'];

    public function quiz()
    {
        return $this->belongsTo('App\Quiz');
    }
}
