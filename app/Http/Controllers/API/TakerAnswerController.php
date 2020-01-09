<?php

namespace App\Http\Controllers\API;

use App\Question;
use App\TakerAnswer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class TakerAnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function save(Request $request)
    {
        $request->validate([
            'answer_text' => 'required',
            'question_id' => 'required|numeric',
        ]);

        $question = Question::findOrFail($request->question_id);

        if (Auth::user()->hasSubmittedQuiz($question->quiz_id)) {
            return response(['message' => 'Quiz has been submitted and answers are locked.'], 500);
        }

        $takerAnswer = TakerAnswer::firstOrNew([
            'user_id' => Auth::user()->id,
            'question_id' => $request->question_id,
        ]);

        $takerAnswer->text = $request->answer_text;
        $takerAnswer->save();
    }
}
