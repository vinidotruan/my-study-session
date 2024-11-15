<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudySession extends Model
{
    protected $fillable = ['user_id', 'minutes', 'rest_minutes', 'counter', 'name', 'uri'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function partners(): HasMany
    {
        return $this->hasMany(Partners::class);
    }
}
