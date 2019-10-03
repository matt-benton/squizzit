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
        'quizzes' => 'API\QuizController'
    ]);

    Route::post('quizzes/{quizId}/invite', 'API\QuizController@sendInvite');
});

Route::post('/register', 'API\UserController@store');
Route::post('/login', 'API\UserController@login');
