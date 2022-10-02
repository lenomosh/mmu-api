<?php

namespace App\Models;

use App\Events\Answer\AnswerCreatedEvent;
use App\Events\Answer\AnswerDeletedEvent;
use App\Events\Answer\AnswerUpdatedEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = ['answer', 'user_id'];

    protected $dispatchesEvents = [
        'created' => AnswerCreatedEvent::class,
        'updated' => AnswerUpdatedEvent::class,
        'deleted' => AnswerDeletedEvent::class
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
