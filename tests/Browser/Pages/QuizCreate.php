<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class QuizCreate extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/#/quizzes/create';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        // NOTE: This is not working with the hashed urls
        // $browser->assertPathIs($this->url());
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

    public function createQuiz(Browser $browser)
    {
        $browser->type('name', 'My Test Quiz')
                ->type('description', 'This is a quiz that was made by an automated test.')
                ->click('@save-quiz-button')
                ->pause(1000)
                ->assertSeeIn('#quiz-heading', 'My Test Quiz')
                ->assertSeeIn(
                    '#quiz-heading',
                    'This is a quiz that was made by an automated test.'
                );
    }
}
