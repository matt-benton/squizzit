<?php

namespace App\Observers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\QuizInvite;

class QuizInviteObserver
{
    /**
     * Handle the quiz invite "created" event.
     *
     * @param  \App\QuizInvite  $quizInvite
     * @return void
     */
    public function created(QuizInvite $quizInvite)
    {
        $quizInvite->setCreatedAt(Carbon::now()->timestamp);
        $quizInvite->setToken(Str::random(60));
    }
}
