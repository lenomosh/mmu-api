<?php


namespace App\Helpers;


use App\Models\Vote;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

trait HasVotes
{

    public function hasUpVoted(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->userHasUpVoted());
    }

    public function userHasUpVoted(): bool
    {
        if (!Auth::check()) {
            return false;
        }
        return $this->votes()->where(
                [
                    'user_id' => Auth::id(),
                    'is_upvote' => true,

                ])->count() > 0;
    }

    public function votes(): MorphMany
    {
        return $this->morphMany(Vote::class, 'votable');
    }

    public function hasDownVoted(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->userHasDownVoted()
        );
    }

    public function userHasDownVoted(): bool
    {
        if (!Auth::check()) {
            return false;
        }
        return $this->votes()->where(
                [
                    'user_id' => Auth::id(),
                    'is_upvote' => false
                ])->count() > 0;
    }

    public function upVotes(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->votes()->where('is_upvote', true)->count()
        );
    }

    public function downVotes(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->votes()->where('is_upvote', false)->count()
        );
    }

    public function upvote(): void
    {
        if ($this->userHasUpVoted()) {
            $this->deleteUserVote();
            return;
        }
        $this->deleteUserVote();

        $this->votes()->firstOrCreate(
            [
                'user_id' => Auth::id()
            ]
        );
    }

    private function deleteUserVote()
    {

        $params =
            [
                'user_id' => Auth::id()
            ];
        if ($votable_type = $this->votes()->where($params)->first()) {
            $votable_type->delete();
        }
    }

    public function downVote(): void
    {

        if ($this->userHasDownVoted()) {
            $this->deleteUserVote();
            return;
        }
        $this->deleteUserVote();
        $this->votes()->firstOrCreate(
            [
                'user_id' => Auth::id(),
                'is_upvote' => false,
            ]
        );
    }
}
