<?php

namespace Tests\Browser;

use App\Mail\InviteToQuiz;
use Tests\DuskTestCase;
use Tests\Browser\Pages\Login;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Pages\QuizEdit;
use Mail;

class QuizInviteTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @group tacos
     */
    public function testSendingQuizInvite()
    {
        $sender = factory(\App\User::class)->create();
        $recipient = factory(\App\User::class)->create();
        $quiz = factory(\App\Quiz::class)->create();
        $sender->quizzes()->attach($quiz->id, ['role' => 'maker']);

        $this->browse(function (Browser $browser) use ($sender, $recipient, $quiz) {
            $browser->visit(new Login($sender))
                    ->loginUser()
                    ->visit(new QuizEdit($quiz))
                    ->pause(1000)
                    ->click('#share-button')
                    ->waitFor('#invite-form')
                    ->type('email', $recipient->email)
                    ->pause(1000)
                    ->click('#invite-button')
                    ->waitForText("Your invite to {$recipient->email} has been sent!");
                
            $this->assertDatabaseHas('quiz_invites', [
                'email' => $recipient->email,
                'quiz_id' => $quiz->id
            ]);

            // login as the recipient and check for the invite
            $browser->visit(new Login($recipient))
                    ->loginUser()
                    ->visit('/#/invites')
                    ->waitForText($quiz->name)
                    ->assertSee($quiz->description);
        });
    }
}
