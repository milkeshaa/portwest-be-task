<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SkuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'SKU' => $this->name,
            'product' => new ProductResource($this->whenLoaded('product')),
            'variants' => ProductVariantResource::collection($this->whenLoaded('variants')),
        ];
    }
}
