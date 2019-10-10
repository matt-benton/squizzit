<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\InviteToQuiz;
use App\QuizInvite;
use Mail;
use Auth;

class QuizInviteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get invites that have not been accepted or declined
        $quizInvites = QuizInvite::where('email', Auth::user()->email)
            ->where('accepted', null)
            ->with('quiz')->get();

        return response(['quizInvites' => $quizInvites]);
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
            'email' => 'required|email',
            'quiz_id' => 'required|numeric'
        ]);

        $email = $request->email;
        $quizId = $request->quiz_id;

        $quiz = Auth::user()->quizzes()->where('id', $quizId)->first();

        QuizInvite::create([
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
