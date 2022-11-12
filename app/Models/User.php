<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'picture',
        'google_id',
        'first_name',
        'last_name'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email',
        'google_id',
        'email_verified_at',
        'google_id',
        'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['name'];

    public function name(): Attribute
    {
        return Attribute::get(fn($value, $attributes) => $attributes['first_name'] . ' ' . $attributes['last_name']);
    }

    public function downVote()
    {
        Vote::query()->create(
            [
                'user_id' => Auth::id(),
                'question_id' => $this->id,
                'is_upvote' => false
            ]
        );
    }

    public function upVote()
    {
        Vote::query()->create(
            [
                'user_id' => Auth::id(),
                'question_id' => $this->id
            ]
        );
    }

    public static function getTableName(): string
    {
        return with(new static)->getTable();
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }
}
