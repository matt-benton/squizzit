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
                    ->createQuiz();

            $this->assertDatabaseHas('quizzes', [
                'name' => 'My Test Quiz',
                'description' => 'This is a quiz that was made by an automated test.'
            ]);
        });
    }

    public function testEditingQuizMainInfo()
    {
        $user = factory(\App\User::class)->create();
        $quiz = factory(\App\Quiz::class)->create();
        $user->quizzes()->attach($quiz->id, ['role' => 'maker']);

        $this->browse(function (Browser $browser) use ($user, $quiz) {
            $nameInputText = 'My Edited Quiz Name';
            $descriptionInputText = 'This quiz has been edited by an automated test.';

            $browser->visit(new Login($user))
                    ->loginUser()
                    ->visit(new QuizEdit($quiz))
                    ->waitFor('#quiz-edit-button')
                    ->click('#quiz-edit-button')
                    ->waitFor('#quiz-name-input')
                    ->type('#quiz-name-input', $nameInputText)
                    ->type('#quiz-description-input', $descriptionInputText)
                    ->click('#quiz-save-button')
                    ->pause(1000);

            $this->assertDatabaseMissing('quizzes', [
                'name' => $quiz->name,
                'description' => $quiz->description
            ]);

            $this->assertDatabaseHas('quizzes', [
                'name' => $nameInputText,
                'description' => $descriptionInputText
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
                    // the save question button should not be visible if the textarea is empty
                    ->type('new_question', 'What is your name?')
                    ->click('#add-question-button')
                    ->waitFor('#question-0-editor')
                    // the save question button should still be invisible until we add two answers
                    ->assertDontSeeIn('#question-0-editor', '#save-question-button')
                    ->click('#add-answer-button')
                    ->waitFor('@question-1-answer-input-0')
                    ->type('@question-1-answer-input-0', 'Mark')
                    // still need to add one more answer before the button is visible
                    ->assertDontSeeIn('#question-0-editor', '#save-question-button')
                    ->click('#add-answer-button')
                    ->waitFor('@question-1-answer-input-1')
                    ->type('@question-1-answer-input-1', 'Jerry')
                    ->click('#save-question-button')
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
                    ->waitFor("@question-link-{$question->id}")
                    ->click("@question-link-{$question->id}")
                    ->addAnswerToQuestion(0, 'Answer 1', $question->id)
                    ->addAnswerToQuestion(1, 'Answer 2', $question->id)
                    ->waitFor('#save-question-button')
                    ->click('#save-question-button')
                    ->assertMissing('#question-editor-container')
                    ->click('@question-link-1')
                    ->assertValue('@question-1-answer-input-0', 'Answer 1');

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
        $answers = $question->answers()->saveMany(factory(\App\Answer::class, 3)->make());

        $this->browse(function (Browser $browser) use ($user, $quiz, $question, $answers) {
            $browser->visit(new Login($user))
                    ->loginUser()
                    ->visit(new QuizEdit($quiz))
                    ->pause(1000)
                    ->click('@question-link-1')
                    ->waitFor('@question-1-answer-input-0')
                    ->click('@question-1-answer-0-delete-button')
                    ->waitUntilMissing('@question-1-answer-input-2') // We have 3 answers, if we remove one then the third answer "element" will be missing
                    ->click('#save-question-button')
                    ->waitForText('Save successful!')
                    ->assertMissing('@question-1-answer-input-2');

            $this->assertDatabaseMissing('answers', [
                'question_id' => $question->id,
                'text' => $answers[0]->text
            ]);
        });
    }

    public function testEditingAnswer()
    {
        $user = factory(\App\User::class)->create();
        $quiz = factory(\App\Quiz::class)->create();
        $user->quizzes()->attach($quiz->id, ['role' => 'maker']);
        $question = $quiz->questions()->save(factory(\App\Question::class)->make());
        $answers = $question->answers()->saveMany(factory(\App\Answer::class, 2)->make());

        $this->browse(function (Browser $browser) use ($user, $quiz, $question, $answers) {
            $browser->visit(new Login($user))
                    ->loginUser()
                    ->visit(new QuizEdit($quiz))
                    ->waitFor('@question-link-1')
                    ->click('@question-link-1')
                    ->waitFor('@question-1-answer-input-0')
                    ->type('@question-1-answer-input-0', 'This answer has been edited.')
                    ->click('@question-1-answer-0-correct-button')
                    ->click('#save-question-button')
                    ->waitForText('Save successful!');

            $this->assertDatabaseHas('answers', [
                'question_id' => $question->id,
                'text' => 'This answer has been edited.',
                'correct' => 1
            ]);

            $this->assertDatabaseMissing('answers', [
                'question_id' => $question->id,
                'text' => $answers[0]->text,
                'correct' => 0
            ]);    
        });
    }
}
