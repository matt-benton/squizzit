<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Tests\Browser\Pages\Login;
use Tests\Browser\Pages\QuizCreate;
use Tests\Browser\Pages\QuizEdit;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class QuizTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testCreatingAQuiz()
    {
        $user = factory(\App\User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit(new Login($user))
                    ->loginUser()
                    ->visit(new QuizCreate)
                    ->createQuiz()
                    ->assertSee('This is a quiz that was made by an automated test.');

            $this->assertDatabaseHas('quizzes', [
                'name' => 'My Test Quiz',
                'description' => 'This is a quiz that was made by an automated test.'
            ]);
        });
    }

    public function testAddingQuestionToQuiz()
    {
        $user = factory(\App\User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit(new Login($user))
                    ->loginUser()
                    ->visit(new QuizCreate)
                    ->createQuiz()
                    ->click('#new-question-button')
                    ->select('question_type', 'short_answer')
                    // the save question button should not be visible if the textarea is empty
                    ->assertMissing('#save-question-button')
                    ->type('question_text', 'What is your name?')
                    ->click('#save-question-button')
                    ->assertMissing('#question-editor-container')
                    ->pause(1000)
                    ->assertSeeIn('#question-nav-list', 'What is your name?');
        });
    }

    public function testAddingAnswerToQuestion()
    {
        $user = factory(\App\User::class)->create();
        $quiz = factory(\App\Quiz::class)->create();
        $user->quizzes()->attach($quiz->id, ['role' => 'maker']);
        $question = $quiz->questions()->save(factory(\App\Question::class)->make());

        $this->browse(function (Browser $browser) use ($user, $quiz, $question) {
            $browser->visit(new Login($user))
                    ->loginUser()
                    ->visit(new QuizEdit($quiz))
                    ->addAnswerToQuestion()
                    ->click('#save-question-button')
                    ->assertMissing('#question-editor-container')
                    ->click('@question-link-1')
                    ->assertValue('@answer-input-0', 'Answer 1');

            $this->assertDatabaseHas('answers', [
                'question_id' => $question->id,
                'text' => 'Answer 1'
            ]);
        });
    }

    public function testRemovingAnswerFromQuestion()
    {
        $user = factory(\App\User::class)->create();
        $quiz = factory(\App\Quiz::class)->create();
        $user->quizzes()->attach($quiz->id, ['role' => 'maker']);
        $question = $quiz->questions()->save(factory(\App\Question::class)->make());
        $answer = $question->answers()->save(factory(\App\Answer::class)->make());

        $this->browse(function (Browser $browser) use ($user, $quiz, $question, $answer) {
            $browser->visit(new Login($user))
                    ->loginUser()
                    ->visit(new QuizEdit($quiz))
                    ->pause(1000)
                    ->click('@question-link-1')
                    ->waitFor('@answer-input-0')
                    ->click('@remove-answer-button-0')
                    ->waitUntilMissing('@answer-input-0')
                    ->click('#save-question-button')
                    ->click('@question-link-1')
                    ->waitFor('#question-editor-container')
                    ->assertMissing('@answer-input-0');

            $this->assertDatabaseMissing('answers', [
                'question_id' => $question->id,
                'text' => $answer->text
            ]);
        });
    }

    public function testEditingAnswer()
    {
        // todo
    }
}
