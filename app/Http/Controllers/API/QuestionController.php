<?php

namespace App\Http\Controllers\API;

use App\Question;
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
        $question = new Question;
        $question->quiz_id = $request->quiz_id;
        $question->type = $request->type;
        $question->text = $request->text;
        $question->save();

        // ! might not need this
        // Get an empty collection for adding answers in the view
        // $question->answers = $question->answers;

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
        $question->type = $request->type;
        $question->text = $request->text;
        $question->save();

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
