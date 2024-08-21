<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Client\Response;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class FetchDataJob implements ShouldQueue
{
    use Queueable;
    use Dispatchable;
    use InteractsWithQueue;

    private Response $response;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $this->response = Http::get(url('/product-data'));
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            throw $e;
        }

        if (false === $this->response->successful()) {
            Log::error('API call failed, please check logs for the details');
            info($this->response->getStatusCode());
            info($this->response->body());
            return;
        }
        
        ProcessJobsChainJob::withChain($this->prepareJobsChain())->dispatch();
    }

    public function failed(Throwable $e): void
    {
        Log::error($e->getMessage());
        Log::error($e->getTraceAsString());
    }

    private function prepareJobsChain(): array
    {
        $jobs = [];
        foreach ($this->getDataGenerator() as $index => $data) {
            $jobs[] = new ProcessSingleDataEntryJob(dataKey: $index, data: $data);
        }
        $jobs[] = new HandleImportCompletionJob();
        return $jobs;
    }

    private function getDataGenerator(): \Generator
    {
        foreach ($this->response->json() as $key => $value) {
            yield $key => $value;
        }
    }
}
