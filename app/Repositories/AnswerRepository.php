<?php

namespace App\Repositories;

use App\Answer;
use App\Question;

class AnswerRepository
{
    public function saveManyAnswers($answers, Question $question)
    {
        $newAnswers = [];

        foreach ($answers as $answer)
        {
            $ans = new Answer;
            $ans->text = $answer['text'];

            if (array_key_exists('correct', $answer)) {
                $ans->correct = $answer['correct'];
            }

            array_push($newAnswers, $ans);
        }

        return $question->answers()->saveMany($newAnswers);
    }
}
