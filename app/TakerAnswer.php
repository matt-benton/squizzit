<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TakerAnswer extends Model
{
    protected $fillable = ['user_id', 'question_id'];
}
