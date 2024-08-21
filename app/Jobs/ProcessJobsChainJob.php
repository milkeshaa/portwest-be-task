<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessJobsChainJob implements ShouldQueue
{
    use Queueable;
    use Dispatchable;
    use InteractsWithQueue;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }

    public function failed(Throwable $e): void
    {
        Log::error($e->getMessage());
        Log::error($e->getTraceAsString());
    }
}
