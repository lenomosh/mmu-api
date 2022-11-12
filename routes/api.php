<?php

use App\Http\Controllers\Api\AnswerController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\QuestionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['prefix' => 'auth', 'middleware' => ['guest']], function () {
    Route::post('/login/google', [AuthController::class, 'loginWithGoogle']);
});
Route::group(['middleware' => ['auth:api']], function () {

    Route::apiResource('question', QuestionController::class);
    Route::post('question/{question}/answer', [AnswerController::class, 'store']);
    Route::post('question/{question}/upvote', [QuestionController::class, 'upvote']);
    Route::post('question/{question}/down-vote', [QuestionController::class, 'downVote']);

    Route::post('answer/{answer}/down-vote', [AnswerController::class, 'downVote']);
    Route::post('answer/{answer}/upvote', [AnswerController::class, 'upvote']);

    Route::apiResource('answer', AnswerController::class)->only(['destroy', 'update']);
});
