<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Throwable;

class HandleImportCompletionJob implements ShouldQueue
{
    use Queueable;
    use Dispatchable;
    use InteractsWithQueue;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        info('Data import was completed, please check the DB and perform further checks.');
    }

    public function failed(Throwable $e): void
    {
        app('log')->error($e->getMessage());
        app('log')->error($e->getTraceAsString());
    }
}
