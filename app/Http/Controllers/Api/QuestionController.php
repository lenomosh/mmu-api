<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Questions\CreateQuestionRequest;
use App\Http\Requests\Questions\UpdateQuestionRequest;
use App\Jobs\CreateQuestionJob;
use App\Jobs\DeleteQuestionJob;
use App\Jobs\UpdateQuestionJob;
use App\Models\Question;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use function response;

class QuestionController extends Controller
{


    public function upvote(Question $question)
    {
        $question->upvote();
        return $question->userHasUpVoted();
    }

    public function downVote(Question $question)
    {
        $question->downVote();
        return $question->userHasDownVoted();
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json(['data' => Question::query()->with('author')->orderBy('views', 'desc')->paginate(5)]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateQuestionRequest $request
     * @return JsonResponse
     */
    public function store(CreateQuestionRequest $request)
    {
        CreateQuestionJob::dispatch(title: $request->validated('title'), body: $request->validated('body'), user: Auth::user());
        return response()->json();

    }

    /**
     * Display the specified resource.
     *
     * @param Question $question
     * @return JsonResponse
     */
    public function show(Question $question)
    {
        $question->load('author');
        $question->setRelation('answers', $question->answers()->with('author')->orderBy('updated_at', 'desc')->paginate(1));
        $question->incrementViewCount();
        return response()->json(['data' => $question]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateQuestionRequest $request
     * @param Question $question
     * @return JsonResponse
     */
    public function update(UpdateQuestionRequest $request, Question $question)
    {
        UpdateQuestionJob::dispatch(data: $request->validated(), question: $question);

        return response()->json();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Question $question
     * @return void
     */
    public function destroy(Question $question)
    {
        DeleteQuestionJob::dispatch(question: $question);
    }
}
