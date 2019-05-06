<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Tests\Browser\Pages\Login;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Str;
use App\User;

class QuizTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Login)
                    ->loginUser()
                    ->visit('/#/quizzes/create')
                    ->type('name', 'My Test Quiz')
                    ->type('description', 'This is a quiz that was made from an automated test.')
                    ->click('@save-quiz-button')
                    ->waitForText('My Test Quiz')
                    ->assertSee('This is a quiz that was made from an automated test.');
        });
    }
}
