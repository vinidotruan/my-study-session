<?php

namespace App\Jobs;

use App\Events\EndSession;
use App\Models\StudySession;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FinalizeSession implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly User $user, public StudySession $studySession)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        EndSession::dispatch($this->user, $this->studySession);
    }
}
