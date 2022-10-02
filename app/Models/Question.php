<?php

namespace App\Models;

use App\Events\Question\QuestionCreatedEvent;
use App\Events\Question\QuestionDeletedEvent;
use App\Events\Question\QuestionUpdatedEvent;
use App\Helpers\SlugAutomaticUpdate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;
    use SlugAutomaticUpdate;

    protected $fillable = ['body', 'title', 'slug', 'user_id'];

    protected $dispatchesEvents = [
        'created' => QuestionCreatedEvent::class,
        'updated' => QuestionUpdatedEvent::class,
        'deleted' => QuestionDeletedEvent::class
    ];

    public static function getTableName(): string
    {
        return with(new static)->getTable();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function answers(): HasMany
    {

        return $this->hasMany(Answer::class);
    }
}
