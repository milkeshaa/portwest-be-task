<?php

declare(strict_types=1);

namespace Database\Seeders;

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
    private const REQUIRED_DATA_KEYS = [
        'id',
        'product_name',
        'parent_category',
        'on_sale',
        'colours',
        'size',
        'box_qty',
        'width',
        'length',
        'height',
        'SKU',
        'variant',
    ];

    private const DATA_TYPE_PER_KEY = [
        'id' => 'string',
        'product_name' => 'string',
        'parent_category' => 'string',
        'on_sale' => 'boolean',
        'colours' => 'string',
        'size' => 'string',
        'box_qty' => 'numeric',
        'width' => 'numeric',
        'length' => 'numeric',
        'height' => 'numeric',
        'SKU' => 'string',
        'variant' => 'string',
    ];

    private const NUMERIC_TYPES = [
        'float',
        'double',
        'integer',
    ];

    private Response $response;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Start seeding of API call results');
        try {
            $this->response = Http::get(url('/product-data'));
        } catch (Throwable $e) {
            info($e->getMessage());
            info($e->getTraceAsString());
            $this->command->error('Request was not sent, please check logs');
            return;
        }

        if (false === $this->response->successful()) {
            $this->command->error('API call failed, please check logs for the details');
            info($this->response->getStatusCode());
            info($this->response->body());
        }

        $this->command->info('Data retrieved, starting the insertion process');
        /*
            here we can create a "background" jobs chain for this insertion and insert each record separately in a single job
            and at the end informing admin by email or somehow that the data was inserted

            but for the simplicity I'll process this flow sync here
        */
        foreach ($this->getDataGenerator() as $index => $data) {
            if (false === $this->isDataValid($data)) {
                $this->command->error(sprintf('Row %s is invalid. It is ignored. Please check logs', $index));
                info(sprintf('Row %s data:', $index));
                info(json_encode($data));
                continue;
            }

            if (true === ProductVariant::query()->where('id', '=', $data['id'])->exists()) {
                $this->command->warn(sprintf('Product variant %s already exists. Skipping', $data['id']));
                continue;
            }

            [
                'sku_id' => $skuId,
                'product_id' => $productId,
                'colour_id' => $colourId,
                'size_id' => $sizeId,
            ] = $this->getRelationsIds($data);
            ProductVariantFactory::new()->create([
                'id' => $data['id'] ?? Str::uuid()->toString(),
                'product_id' => $productId,
                'sku_id' => $skuId,
                'size_id' => $sizeId,
                'colour_id' => $colourId,
                'on_sale' => $data['on_sale'],
                'box_qty' => $data['box_qty'],
                'width' => $data['width'],
                'height' => $data['height'],
                'length' => $data['length'],
                'upcoming_update_date' => $data['updated_at'] ? Carbon::parse($data['updated_at'])->format('m/d/Y') : null,
            ]);
        }
        $this->command->info('Insertion process is finished');
    }

    private function getDataGenerator(): \Generator
    {
        foreach ($this->response->json() as $key => $value) {
            yield $key => $value;
        }
    }

    private function isDataValid(array $data): bool
    {
        return true === $this->allKeysExist($data) 
            && true === $this->valuesAreOfCorrectDataType($data)
            && true === $this->requiredDataExists($data);
    }

    private function allKeysExist(array $data): bool
    {
        foreach (self::REQUIRED_DATA_KEYS as $key) {
            if (false === array_key_exists($key, $data)) {
                return false;
            }
        }
        return true;
    }

    private function valuesAreOfCorrectDataType(array $data): bool
    {
        foreach (self::DATA_TYPE_PER_KEY as $key => $type) {
            $originalType = gettype($data[$key]);
            if (true === in_array($originalType, self::NUMERIC_TYPES)) {
                $originalType = 'numeric';
            }
            if ($type !== $originalType) {
                return false;
            }
        }
        return true;
    }

    private function requiredDataExists(array $data): bool
    {
        foreach (self::REQUIRED_DATA_KEYS as $key) {
            if (true === empty($data[$key]) && false === $this->allowedToBeEmpty($key)) {
                return false;
            }
        }
        return true;
    }

    private function allowedToBeEmpty(string $key): bool
    {
        return true === in_array(self::DATA_TYPE_PER_KEY[$key], ['boolean', 'integer', 'double']);
    }

    private function getRelationsIds(array $data): array
    {
        $categoryId = Category::query()->where('name', '=', strtolower($data['parent_category']))->value('id');
        if (true === empty($categoryId)) {
            $categoryId = CategoryFactory::new()->createQuietly([
                'name' => strtolower($data['parent_category']),
            ])->getKey();
        }
        
        $productId = Product::query()
            ->where('name', '=', $data['product_name'])
            ->where('category_id', '=', $categoryId)
            ->value('id');
        if (true === empty($productId)) {
            $productId = ProductFactory::new()->createQuietly([
                'name' => $data['product_name'],
                'category_id' => $categoryId,
                'description' => $data['description'] ?? null,
            ])->getKey();
        }

        $skuId = Sku::query()->where('name', '=', strtoupper($data['SKU']))->value('id');
        if (true === empty($skuId)) {
            $skuId = SkuFactory::new()->create([
                'name' => strtoupper($data['SKU']),
            ])->getKey();
        }

        $colourId = Colour::query()->where('name', '=', strtolower($data['colours']))->value('id');
        if (true === empty($colourId)) {
            $colourId = ColourFactory::new()->createQuietly([
                'name' => strtolower($data['colours']),
            ])->getKey();
        }

        $sizeId = Size::query()->where('name','=', strtoupper($data['size']))->value('id');
        if (true === empty($sizeId)) {
            $sizeId = SizeFactory::new()->createQuietly([
                'name' => strtoupper($data['size']),
            ])->getKey();
        }

        return [
            'product_id' => $productId,
            'sku_id' => $skuId,
            'colour_id' => $colourId,
            'size_id' => $sizeId,
        ];
    }
}
