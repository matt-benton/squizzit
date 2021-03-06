<?php

namespace App\Http\Controllers\API;

use App\Events\QuizSubmitted;
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
        $quizzes = Auth::user()->quizzes;

        foreach ($quizzes as $quiz) {
            $quiz->numQuestions = $quiz->questions()->count();
        }

        return response(['quizzes' => $quizzes]);
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

        $quiz = Quiz::findOrFail($request->quizId);

        $now = Carbon::now();

        Auth::user()->quizzes()->updateExistingPivot($quiz->id, ['submitted_at' => $now->toDateTimeString()]);

        event(new QuizSubmitted($quiz));

        return response(['message' => 'success']);
    }

    public function isSubmitted($quizId)
    {
        $result = 0;

        if (Auth::user()->hasSubmittedQuiz($quizId)) {
            $result = 1;
        }

        return response($result);
    }

    public function getResults($id)
    {
        $quiz = $this->retrieveQuizWithRelations($id);
        $takerAnswers = $quiz->takerAnswers()->where('user_id', Auth::user()->id)->get();
        $score = $quiz->getTakerScore(Auth::user());

        return response([
            'quiz' => $quiz,
            'takerAnswers' => $takerAnswers,
            'score' => $score,
        ]);
    }

    public function getUserRole($id)
    {
        $quiz = Quiz::find($id);

        if (!$quiz) {
            return response(['role' => 'none']);
        }

        $user = $quiz->users()->where('id', Auth::user()->id)->first();

        // it's possible a user might not be associated with a quiz at all
        if (!$user) {
            $role = 'none';
        } else {
            $role = $user->pivot->role;
        }

        return response(['role' => $role]);
    }

    private function retrieveQuizWithRelations($id)
    {
        return Auth::user()->quizzes()->where('id', $id)->with('questions.answers')->first();
    }
}
