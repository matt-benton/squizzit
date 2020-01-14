<?php

namespace Tests\Unit;

use App\Events\QuizSubmitted;
use App\TakerAnswer;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GradeQuizTest extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();

        $this->taker = factory(\App\User::class)->create();
        $this->quiz = factory(\App\Quiz::class)->create();
        $this->taker->quizzes()->attach($this->quiz->id, ['role' => 'taker']);
        $questions = $this->quiz->questions()->saveMany([
            factory(\App\Question::class)->make(),
            factory(\App\Question::class)->make(),
            factory(\App\Question::class)->make()
        ]);

        foreach ($questions as $question) {
            $question->answers()->saveMany([
                factory(\App\Answer::class)->make(),
                factory(\App\Answer::class)->make([
                    'correct' => 1,
                ]),
                factory(\App\Answer::class)->make(),
                factory(\App\Answer::class)->make()
            ]);
        }

        $this->takerAnswer1 = new TakerAnswer;
        $this->takerAnswer1->user_id = $this->taker->id;
        $this->takerAnswer1->question_id = $questions[0]->id;
        $this->takerAnswer1->answer_id = $questions[0]->answers[1]->id; // should be a correct answer
        $this->takerAnswer1->save();

        $this->takerAnswer2 = new TakerAnswer;
        $this->takerAnswer2->user_id = $this->taker->id;
        $this->takerAnswer2->question_id = $questions[1]->id;
        $this->takerAnswer2->answer_id = $questions[1]->answers[1]->id; // correct
        $this->takerAnswer2->save();

        $this->takerAnswer3 = new TakerAnswer;
        $this->takerAnswer3->user_id = $this->taker->id;
        $this->takerAnswer3->question_id = $questions[2]->id;
        $this->takerAnswer3->answer_id = $questions[2]->answers[3]->id; // incorrect answer
        $this->takerAnswer3->save();
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testQuizIsGradedCorrectly()
    {
        // First, make sure answers are not marked correct or incorrect
        $this->assertDatabaseHas('taker_answers', [
            'user_id' => $this->taker->id,
            'question_id' => $this->takerAnswer1->question_id,
            'answer_id' => $this->takerAnswer1->answer_id,
            'is_correct' => null,
        ]);

        $this->assertDatabaseHas('taker_answers', [
            'user_id' => $this->taker->id,
            'question_id' => $this->takerAnswer1->question_id,
            'answer_id' => $this->takerAnswer1->answer_id,
            'is_correct' => null,
        ]);

        $this->assertDatabaseHas('taker_answers', [
            'user_id' => $this->taker->id,
            'question_id' => $this->takerAnswer1->question_id,
            'answer_id' => $this->takerAnswer1->answer_id,
            'is_correct' => null,
        ]);

        event(new QuizSubmitted($this->quiz));

        /**
         * For this test to pass, the first two takerAnswers should be marked as
         * correct and the last one incorrect.
         */
        $this->assertDatabaseHas('taker_answers', [
            'user_id' => $this->taker->id,
            'question_id' => $this->takerAnswer1->question_id,
            'answer_id' => $this->takerAnswer1->answer_id,
            'is_correct' => 1,
        ]);

        $this->assertDatabaseHas('taker_answers', [
            'user_id' => $this->taker->id,
            'question_id' => $this->takerAnswer2->question_id,
            'answer_id' => $this->takerAnswer2->answer_id,
            'is_correct' => 1,
        ]);

        $this->assertDatabaseHas('taker_answers', [
            'user_id' => $this->taker->id,
            'question_id' => $this->takerAnswer3->question_id,
            'answer_id' => $this->takerAnswer3->answer_id,
            'is_correct' => 0,
        ]);
    }
}
