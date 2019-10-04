<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QuizInviteController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'quiz_id' => 'required|numeric'
        ]);

        $email = $request->email;
        $quizId = $request->quiz_id;

        $quiz = Auth::user()->quizzes()->where('id', $quizId)->first();

        $quizInvite = QuizInvite::create([
            'email' => $email,
            'quiz_id' => $quiz->id
        ]);
        
        Mail::to($email)->queue(new InviteToQuiz);

        return response(['message' => "Your invite to {$email} has been sent!"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
}
