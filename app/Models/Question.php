<?php

namespace App\Models;

use App\Events\Question\QuestionCreatedEvent;
use App\Events\Question\QuestionDeletedEvent;
use App\Events\Question\QuestionUpdatedEvent;
use App\Helpers\HasVotes;
use App\Helpers\SlugAutomaticUpdate;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Question extends Model
{
    use HasFactory;
    use SlugAutomaticUpdate;
    use HasVotes;
    use HasUuids;

    protected $primaryKey = 'uuid';

    protected $fillable = ['body', 'title', 'slug', 'user_id', 'views'];

    protected $hidden = ['user_id'];
    public $appends = ['has_up_voted', 'has_down_voted', 'up_votes', 'down_votes', 'answers_count'];

    protected $dispatchesEvents = [
        'created' => QuestionCreatedEvent::class,
        'updated' => QuestionUpdatedEvent::class,
        'deleted' => QuestionDeletedEvent::class
    ];

    public function answersCount(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->answers()->count()
        );
    }

    public static function getTableName(): string
    {
        return with(new static)->getTable();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function title(): Attribute
    {
        return Attribute::make(
            get: fn($value) => "Make me understand " . $value
        );
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function answers(): HasMany
    {

        return $this->hasMany(Answer::class);
    }

    public function incrementViewCount(): void
    {
        if (!Auth::check()) {
            return;
        }
        $this->update([
            'views' => $this->views + 1
        ]);
        $this->save();
    }

}
