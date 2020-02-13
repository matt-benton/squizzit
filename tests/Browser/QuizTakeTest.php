<?php

namespace Tests\Browser;

use App\Events\QuizSubmitted;
use App\TakerAnswer;
use Tests\Browser\Pages\Login;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Carbon\Carbon;

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
                    ->radio("question-1-answers", $questions[0]->answers[rand(0, 3)]->id)
                    ->assertSee($questions[1]->text)
                    ->radio("question-2-answers", $questions[1]->answers[rand(0, 3)]->id)
                    ->assertSee($questions[2]->text)
                    ->radio("question-3-answers", $questions[2]->answers[rand(0, 3)]->id)
                    ->click("#submit-quiz-button")
                    ->waitForText('Your Score');

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

    public function testGetQuizResults()
    {
        $taker = factory(\App\User::class)->create();
        $quiz = factory(\App\Quiz::class)->create();
        $taker->quizzes()->attach($quiz->id, ['role' => 'taker']);
        $questions = $quiz->questions()->saveMany([
            factory(\App\Question::class)->make(),
            factory(\App\Question::class)->make(),
            factory(\App\Question::class)->make(),
            factory(\App\Question::class)->make(),
        ]);

        foreach ($questions as $key=>$question) {
            $answers = $question->answers()->saveMany([
                factory(\App\Answer::class)->make(),
                factory(\App\Answer::class)->make([
                    'correct' => 1,
                ]),
                factory(\App\Answer::class)->make(),
                factory(\App\Answer::class)->make()
            ]);

            // make the first answer incorrect but all the rest correct
            if ($key === 0) {
                TakerAnswer::create([
                    'user_id' => $taker->id,
                    'question_id' => $question->id,
                    'answer_id' => $answers[3]->id,
                ]);
            } else {
                TakerAnswer::create([
                    'user_id' => $taker->id,
                    'question_id' => $question->id,
                    'answer_id' => $answers[1]->id,
                ]);
            }
        }

        $now = Carbon::now();

        $taker->quizzes()->updateExistingPivot($quiz->id, ['submitted_at' => $now->toDateTimeString()]);

        event(new QuizSubmitted($quiz));

        $this->browse(function (Browser $browser) use ($taker, $quiz, $questions) {
            $browser->visit(new Login($taker))
                    ->loginUser()
                    ->visit("/#/quizzes/")
                    ->waitFor($quiz->title)
                    ->click('.card')
                    ->waitForText($questions[0]->text)
                    ->waitForText('Your Score: 75')
                    ->assertSee('You answered 3 out of 4 questions correctly.')
                    ->assertVisible('#correct-icon')
                    ->assertVisible('#incorrect-icon')
                    ->assertVisible('.text-green-500')
                    ->assertVisible('.text-red-500')
                    ->assertVisible('.font-medium');
        });
    }
}
