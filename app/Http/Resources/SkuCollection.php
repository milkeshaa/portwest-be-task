<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Sku;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SkuCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => SkuResource::collection($this->collection),
        ];
    }
}
