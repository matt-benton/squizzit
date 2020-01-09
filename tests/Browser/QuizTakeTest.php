<?php

namespace Tests\Browser;

use Tests\Browser\Pages\Login;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class QuizTakeTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test answering questions and submitting quiz
     *
     * @return void
     */
    public function testTakeQuiz()
    {
        $taker = factory(\App\User::class)->create();
        $quiz = factory(\App\Quiz::class)->create();
        $taker->quizzes()->attach($quiz->id, ['role' => 'taker']);
        $questions = $quiz->questions()->saveMany([
            factory(\App\Question::class)->make(),
            factory(\App\Question::class)->make(),
            factory(\App\Question::class)->make()
        ]);

        foreach ($questions as $question) {
            $question->answers()->saveMany([
                factory(\App\Answer::class)->make(),
                factory(\App\Answer::class)->make(),
                factory(\App\Answer::class)->make(),
                factory(\App\Answer::class)->make()
            ]);
        }

        $this->browse(function (Browser $browser) use ($taker, $quiz, $questions) {
            $browser->visit(new Login($taker))
                    ->loginUser()
                    ->visit("/#/quizzes/{$quiz->id}/take")
                    ->waitFor('#quiz-title')
                    ->assertSee($questions[0]->text)
                    ->radio("question-1-answers", $questions[0]->answers[rand(0, 3)]->text)
                    ->assertSee($questions[1]->text)
                    ->radio("question-2-answers", $questions[1]->answers[rand(0, 3)]->text)
                    ->assertSee($questions[2]->text)
                    ->radio("question-3-answers", $questions[2]->answers[rand(0, 3)]->text)
                    ->click("#submit-quiz-button")
                    ->waitForText('my results!');

            $this->assertDatabaseHas('taker_answers', [
                'user_id' => $taker->id,
                'question_id' => $questions[0]->id,
            ]);

            $this->assertDatabaseHas('taker_answers', [
                'user_id' => $taker->id,
                'question_id' => $questions[1]->id,
            ]);

            $this->assertDatabaseHas('taker_answers', [
                'user_id' => $taker->id,
                'question_id' => $questions[2]->id,
            ]);

            /**
             * ! I can't check the db for submitted_at because it is a datetime
             */
        });
    }
}
