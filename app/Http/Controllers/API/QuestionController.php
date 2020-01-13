<?php

namespace App\Http\Controllers\API;

use App\Question;
use App\Answer;
use App\Http\Controllers\Controller;
use App\Repositories\AnswerRepository;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function __construct(AnswerRepository $answer)
    {
        $this->answer = $answer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputQuestion = $request->question;

        $question = new Question;
        $question->quiz_id = $inputQuestion['quiz_id'];
        $question->text = $inputQuestion['text'];
        $question->save();

        $question->answers = $this->answer->saveManyAnswers($inputQuestion['answers'], $question);

        return response(['question' => $question]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question)
    {
        $inputQuestion = $request->question;
        $question->text = $inputQuestion['text'];
        $question->save();

        // delete answers if they are missing from request data
        $answersFromDatabase = $question->answers;
        $inputAnswers = collect($inputQuestion['answers']);

        $this->removeAnswers($answersFromDatabase, $inputAnswers);

        // create or update answers
        foreach ($inputAnswers as $inputAnswer) {
            if (array_key_exists('id', $inputAnswer)) {
                $ans = $answersFromDatabase->where('id', $inputAnswer['id'])->first();
            } else {
                $ans = new Answer;
            }

            $ans->text = $inputAnswer['text'];
            
            if (array_key_exists('correct', $inputAnswer)) {
                $ans->correct = $inputAnswer['correct'];
            }

            $question->answers()->save($ans);
        }

        $question->answers = $question->answers;

        return response(['question' => $question]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        $question->delete();

        return response('success');
    }

    private function removeAnswers($answersFromDatabase, $answersFromInput)
    {
        foreach ($answersFromDatabase as $dbAnswer) {
            // if $inputAnswers does not contain an answer with the same id then delete the $dbAnswer from the database
            if (!$answersFromInput->contains('id', $dbAnswer->id)) {
                $dbAnswer->delete();
            }
        }
    }
}
