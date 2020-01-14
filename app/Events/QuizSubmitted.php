<?php

namespace App\Events;

use App\Quiz;
use Illuminate\Queue\SerializesModels;

class QuizSubmitted
{
    use SerializesModels;

    public $quiz;

    /**
     * Create a new event instance.
     *
     * @param  \App\Quiz  $quiz
     * @return void
     */
    public function __construct(Quiz $quiz)
    {
        $this->quiz = $quiz;
    }
}