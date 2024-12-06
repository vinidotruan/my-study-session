<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaitingRoom extends Model
{
    protected $fillable = [
        'session_id',
        'waiters->id',
        'waiters->display_name',
        'waiters->profile_image_url'
    ];

    protected $casts = ['waiters' => 'array'];

    public function session(): BelongsTo
    {
        return $this->belongsTo(StudySession::class);
    }
}
