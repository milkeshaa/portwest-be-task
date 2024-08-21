<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Jobs\FetchDataJob;
use App\Models\Category;
use App\Models\Colour;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Size;
use App\Models\Sku;
use Database\Factories\CategoryFactory;
use Database\Factories\ColourFactory;
use Database\Factories\ProductFactory;
use Database\Factories\ProductVariantFactory;
use Database\Factories\SizeFactory;
use Database\Factories\SkuFactory;
use Illuminate\Database\Seeder;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class RestoreDataFromApiSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding will be processed in the backround.');
        $this->command->info('In real-world scenario I would implement something like semaphor to "prevent" multiple calls.');
        $this->command->info('In real-world scenario I would implement the functionality of infromin the user who started the import once it\'s done or failed.');
        $this->command->info('But for the simplicity I did not handle these features in this task. All the errors are simply logged in the laravel.log file.');
        
        FetchDataJob::dispatch();
        $this->command->info('Job is dispatched, please follow the info in the laravel.log file');
    }
}
