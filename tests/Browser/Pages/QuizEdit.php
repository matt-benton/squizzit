<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use App\Quiz;

class QuizEdit extends Page
{
    protected $quiz;

    public function __construct(Quiz $quiz)
    {
        $this->quiz = $quiz;
    }

    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return "/#/quizzes/{$this->quiz->id}";
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
      //  $browser->assertPathIs($this->url());
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@element' => '#selector',
        ];
    }

    public function addAnswerToQuestion(Browser $browser, $answerIndex, $answerText)
    {
        $browser->pause(1000)
                ->click('#add-answer-button')
                ->type("@answer-input-{$answerIndex}", $answerText);
    }
}
