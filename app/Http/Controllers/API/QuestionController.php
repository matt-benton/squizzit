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
        $question->type = $inputQuestion['type'];
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
        $question->type = $inputQuestion['type'];
        $question->text = $inputQuestion['text'];
        $question->save();

        // create or update answers
        foreach ($inputQuestion['answers'] as $answer) {
            if (array_key_exists('id', $answer)) {
                $ans = $question->answers()->where('id', $answer['id'])->first();
            } else {
                $ans = new Answer;
            }

            $ans->text = $answer['text'];

            if (array_key_exists('correct', $answer)) {
                $ans->correct = $answer['correct'];
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
}
