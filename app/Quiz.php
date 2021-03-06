<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = ['name', 'description'];

    public function users()
    {
        return $this->belongsToMany('App\User')->withPivot('role');
    }

    public function questions()
    {
        return $this->hasMany('App\Question');
    }

    public function answers()
    {
        return $this->hasManyThrough('App\Answer', 'App\Question');
    }

    public function takerAnswers()
    {
        return $this->hasManyThrough('App\TakerAnswer', 'App\Question');
    }

    public function getCorrectAnswers()
    {
        return $this->answers()->where('correct', 1)->get();
    }

    // ! needs unit test
    public function getTakerScore($taker)
    {
        $takerAnswers = $this->takerAnswers()->where('user_id', $taker->id)->get();
        $correctTakerAnswers = $takerAnswers->filter(function ($takerAnswer) {
            return $takerAnswer->is_correct === 1;
        });

        return round(count($correctTakerAnswers) / count($takerAnswers) * 100);
    }
}
