<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizInvite extends Model
{
    public $timestamps = false;
    protected $fillable = ['email', 'quiz_id'];
}
