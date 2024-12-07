<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StudySession extends Model
{
    protected $fillable = ['user_id', 'minutes', 'rest_minutes', 'on_going', 'name', 'uri'];
    protected $with = ['waitingRoom'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function partners(): HasMany
    {
        return $this->hasMany(Partners::class, 'session_id', 'id');
    }

    public function waitingRoom(): HasOne
    {
        return $this->hasOne(WaitingRoom::class, 'session_id');
    }
}
