<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TakerAnswer extends Model
{
    protected $fillable = ['user_id', 'answer_id', 'question_id'];

    public function answer()
    {
        return $this->belongsTo('App\Answer');
    }

    public function isCorrect()
    {
        if ($this->answer->correct === 1) {
            return true;
        }
    }
}
