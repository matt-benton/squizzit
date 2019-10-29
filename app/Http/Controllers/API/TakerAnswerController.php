<?php

namespace App\Http\Controllers\API;

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
            'answer_text' => 'required'
        ]);

        $takerAnswer = TakerAnswer::firstOrNew([
            'user_id' => Auth::user()->id,
            'question_id' => $request->question_id,
        ]);

        $takerAnswer->text = $request->answer_text;
        $takerAnswer->save();
    }
}
