<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuestionVoteRequest;
use App\Http\Requests\UpdateQuestionVoteRequest;
use App\Models\Vote;
use Illuminate\Http\Response;

class QuestionVoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreQuestionVoteRequest $request
     * @return Response
     */
    public function store(StoreQuestionVoteRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Vote $questionVote
     * @return Response
     */
    public function show(Vote $questionVote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Vote $questionVote
     * @return Response
     */
    public function edit(Vote $questionVote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateQuestionVoteRequest $request
     * @param Vote $questionVote
     * @return Response
     */
    public function update(UpdateQuestionVoteRequest $request, Vote $questionVote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Vote $questionVote
     * @return Response
     */
    public function destroy(Vote $questionVote)
    {
        //
    }
}
