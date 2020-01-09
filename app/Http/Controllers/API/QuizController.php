<?php

namespace App\Http\Controllers\API;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Quiz;
use App\QuizInvite;
use App\TakerAnswer;
use Carbon\Carbon;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(['quizzes' => Auth::user()->quizzes]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $quiz = Quiz::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        Auth::user()->quizzes()->attach($quiz->id, ['role' => 'maker']);

        return response(['id' => $quiz->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response(['quiz' => $this->retrieveQuizWithRelations($id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Quiz $quiz)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);

        $quiz->name = $request->name;
        $quiz->description = $request->description;
        $quiz->save();

        return response(['quiz' => $this->retrieveQuizWithRelations($quiz->id)]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function destroy(Quiz $quiz)
    {
        //
    }

    public function addTaker(Request $request)
    {
        $request->validate([
            'quizInviteId' => 'required|numeric',
            'quizId' => 'required|numeric'
        ]);

        $quiz = Quiz::findOrFail($request->quizId);
        $quizInvite = QuizInvite::findOrFail($request->quizInviteId);
        
        // add the user to the quiz
        $quiz->users()->attach(Auth::user()->id, ['role' => 'taker']);

        // update the quiz invite
        $quizInvite->accepted = 1;
        $quizInvite->save();

        return response([
            'success' => true   
        ]);
    }

    public function getQuizForTaker($quizId)
    {
        $quiz = $this->retrieveQuizWithRelations($quizId);

        $takerAnswers = $quiz->takerAnswers;

        foreach ($quiz->questions as $question) {
            $question->takerAnswer = $takerAnswers->first(function ($ans) use ($question) {
                return $ans->user_id === Auth::user()->id && $ans->question_id === $question->id;
            });

            if ($question->takerAnswer === null) {
                $question->takerAnswer = new TakerAnswer;
                $question->takerAnswer->text = '';
            }
        }

        return response(['quiz' => $quiz]);
    }

    public function submit(Request $request)
    {
        $request->validate([
            'quizId' => 'required|numeric'
        ]);

        $now = Carbon::now();

        Auth::user()->quizzes()->updateExistingPivot($request->quizId, ['submitted_at' => $now->toDateTimeString()]);

        return response(['message' => 'success']);
    }

    private function retrieveQuizWithRelations($id)
    {
        return Auth::user()->quizzes()->where('id', $id)->with('questions.answers')->first();
    }
}
