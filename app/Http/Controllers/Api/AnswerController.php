<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Answer\CreateAnswerRequest;
use App\Http\Requests\Answer\UpdateAnswerRequest;
use App\Jobs\Answer\CreateAnswerJob;
use App\Jobs\Answer\DeleteAnswerJob;
use App\Jobs\Answer\UpdateAnswerJob;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json(['data' => Answer::query()->paginate(10)]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Question $question
     * @param CreateAnswerRequest $request
     * @return void
     */
    public function store(Question $question, CreateAnswerRequest $request)
    {
        CreateAnswerJob::dispatch(answer: $request->validated('answer'), question: $question, user: Auth::user());

    }

    /**
     * Display the specified resource.
     *
     * @param Answer $answer
     * @return JsonResponse
     */
    public function show(Answer $answer)
    {
        return response()->json(['data' => $answer]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Answer $answer
     * @param UpdateAnswerRequest $request
     * @return void
     */
    public function update(Answer $answer, UpdateAnswerRequest $request)
    {
        UpdateAnswerJob::dispatch(answer: $answer, updatedAnswer: $request->validated('answer'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Answer $answer
     * @return void
     */
    public function destroy(Answer $answer)
    {
        DeleteAnswerJob::dispatch($answer);
    }
}
