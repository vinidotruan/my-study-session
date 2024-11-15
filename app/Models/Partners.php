<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Partners extends Model
{
    protected $fillable = ['partner_id', 'session_id'];

    public function session(): BelongsTo
    {
        return $this->belongsTo(StudySession::class, 'session_id', 'id');
    }
}
