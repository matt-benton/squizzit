<?php

namespace App\Listeners;

use App\Events\QuizSubmitted;

class GradeQuiz
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\QuizSubmitted  $event
     * @return void
     */
    public function handle(QuizSubmitted $event)
    {
        // get taker answers for this quiz
        $takerAnswers = $event->quiz->takerAnswers;
        $correctAnswers = $event->quiz->getCorrectAnswers();

        /**
         * An answer is correct if we can find an item in $correctAnswers
         * with a matching answer id and question id
         */
        foreach ($takerAnswers as $takerAnswer) {
            $correctAnswer = $correctAnswers->first(function ($answer) use ($takerAnswer) {
                return $answer->question_id === $takerAnswer->question_id 
                    && $answer->id === $takerAnswer->answer_id;
            });

            if ($correctAnswer) {
                $takerAnswer->is_correct = 1;
            } else {
                $takerAnswer->is_correct = 0;
            }

            $takerAnswer->save();
        }
    }
}