<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('session.{id}', function ($user, $id) {
    return true;
});
