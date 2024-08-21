<?php

declare(strict_types=1);

namespace App\Jobs;

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
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class ProcessSingleDataEntryJob implements ShouldQueue
{
    use Queueable;
    use Dispatchable;
    use InteractsWithQueue;

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

    /**
     * Create a new job instance.
     */
    public function __construct(
        private int|string $dataKey,
        private array $data,
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (false === $this->isDataValid()) {
            Log::error(sprintf('Row %s is invalid. It is ignored. Please check logs', $this->dataKey));
            info(sprintf('Row %s data:', $this->dataKey));
            info(json_encode($this->data));
            return;
        }

        if (true === ProductVariant::query()->where('id', '=', $this->data['id'])->exists()) {
            Log::warning(sprintf('Product variant %s already exists. Skipping', $this->data['id']));
            return;
        }

        [
            'sku_id' => $skuId,
            'product_id' => $productId,
            'colour_id' => $colourId,
            'size_id' => $sizeId,
        ] = $this->getRelationsIds();
        ProductVariantFactory::new()->create([
            'id' => $this->data['id'] ?? Str::uuid()->toString(),
            'product_id' => $productId,
            'sku_id' => $skuId,
            'size_id' => $sizeId,
            'colour_id' => $colourId,
            'on_sale' => $this->data['on_sale'],
            'box_qty' => $this->data['box_qty'],
            'width' => $this->data['width'],
            'height' => $this->data['height'],
            'length' => $this->data['length'],
            'upcoming_update_date' => $this->data['updated_at'] ? Carbon::parse($this->data['updated_at'])->format('m/d/Y') : null,
        ]);
    }

    public function failed(Throwable $e): void
    {
        Log::error($e->getMessage());
        Log::error($e->getTraceAsString());
    }

    private function isDataValid(): bool
    {
        return true === $this->allKeysExist() 
            && true === $this->valuesAreOfCorrectDataType()
            && true === $this->requiredDataExists();
    }

    private function allKeysExist(): bool
    {
        foreach (self::REQUIRED_DATA_KEYS as $key) {
            if (false === array_key_exists($key, $this->data)) {
                return false;
            }
        }
        return true;
    }

    private function valuesAreOfCorrectDataType(): bool
    {
        foreach (self::DATA_TYPE_PER_KEY as $key => $type) {
            $originalType = gettype($this->data[$key]);
            if (true === in_array($originalType, self::NUMERIC_TYPES)) {
                $originalType = 'numeric';
            }
            if ($type !== $originalType) {
                return false;
            }
        }
        return true;
    }

    private function requiredDataExists(): bool
    {
        foreach (self::REQUIRED_DATA_KEYS as $key) {
            if (true === empty($this->data[$key]) && false === $this->allowedToBeEmpty($key)) {
                return false;
            }
        }
        return true;
    }

    private function allowedToBeEmpty(string $key): bool
    {
        return true === in_array(self::DATA_TYPE_PER_KEY[$key], ['boolean', 'integer', 'double']);
    }

    private function getRelationsIds(): array
    {
        $categoryId = Category::query()->where('name', '=', strtolower($this->data['parent_category']))->value('id');
        if (true === empty($categoryId)) {
            $categoryId = CategoryFactory::new()->createQuietly([
                'name' => strtolower($this->data['parent_category']),
            ])->getKey();
        }
        
        $productId = Product::query()
            ->where('name', '=', $this->data['product_name'])
            ->where('category_id', '=', $categoryId)
            ->value('id');
        if (true === empty($productId)) {
            $productId = ProductFactory::new()->createQuietly([
                'name' => $this->data['product_name'],
                'category_id' => $categoryId,
                'description' => $this->data['description'] ?? null,
            ])->getKey();
        }

        $skuId = Sku::query()->where('name', '=', strtoupper($this->data['SKU']))->value('id');
        if (true === empty($skuId)) {
            $skuId = SkuFactory::new()->create([
                'name' => strtoupper($this->data['SKU']),
            ])->getKey();
        }

        $colourId = Colour::query()->where('name', '=', strtolower($this->data['colours']))->value('id');
        if (true === empty($colourId)) {
            $colourId = ColourFactory::new()->createQuietly([
                'name' => strtolower($this->data['colours']),
            ])->getKey();
        }

        $sizeId = Size::query()->where('name','=', strtoupper($this->data['size']))->value('id');
        if (true === empty($sizeId)) {
            $sizeId = SizeFactory::new()->createQuietly([
                'name' => strtoupper($this->data['size']),
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
