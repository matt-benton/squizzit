<?php

namespace App\Http\Controllers\API;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Quiz;

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

    private function retrieveQuizWithRelations($id)
    {
        return Auth::user()->quizzes()->where('id', $id)->with('questions.answers')->first();
    }
}
