<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->group(function () {
    Route::apiResources([
        'answers' => 'API\AnswerController',
        'questions' => 'API\QuestionController',
        'quizzes' => 'API\QuizController',
        'quiz_invites' => 'API\QuizInviteController'
    ]);

    Route::post('/taker_answers/save', 'API\TakerAnswerController@save');

    Route::get('/quizzes/{id}/take', 'API\QuizController@getQuizForTaker');
    Route::post('/quizzes/{id}/submit', 'API\QuizController@submit');
    Route::post('/quizzes/join', 'API\QuizController@addTaker');
    Route::get('/quizzes/{id}/is_submitted', 'API\QuizController@isSubmitted');
    Route::get('/quizzes/{id}/results', 'API\QuizController@getResults');
    Route::get('/quizzes/{id}/user_role', 'API\QuizController@getUserRole');
    Route::get('/quiz_invites/{id}/decline', 'API\QuizInviteController@decline');
});

Route::post('/register', 'API\UserController@store');
Route::post('/login', 'API\UserController@login');
Route::post('/email_is_unique', 'API\UserController@emailIsUnique');
