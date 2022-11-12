<?php

namespace App\Models;

use App\Events\Answer\AnswerCreatedEvent;
use App\Events\Answer\AnswerDeletedEvent;
use App\Events\Answer\AnswerUpdatedEvent;
use App\Helpers\HasVotes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Answer extends Model
{
    use HasFactory;
    use HasVotes;
    use HasUuids;

    protected $primaryKey = 'uuid';

    protected $fillable = ['answer', 'user_id'];
    protected $hidden = ['user_id', 'question_uuid'];
    public $appends = ['has_up_voted', 'has_down_voted', 'up_votes', 'down_votes'];


    protected $dispatchesEvents = [
        'created' => AnswerCreatedEvent::class,
        'updated' => AnswerUpdatedEvent::class,
        'deleted' => AnswerDeletedEvent::class
    ];

    public function answer(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
            $wrapped_a_elements = preg_replace_callback("~[a-z]+://\S+~", fn($match) => "<a href='" . implode('', $match) . "'>" . implode('', $match) . "</a>", $value);
            return str_replace('<p></p>', '', preg_replace("/^(.*)\n/mi", '<p>$1</p>', $wrapped_a_elements));
        }
        );
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function incrementViewCount(): bool
    {
        $this->views++;
        return $this->save();
    }

    public function votes(): MorphMany
    {
        return $this->morphMany(Vote::class, 'votable');
    }
}
