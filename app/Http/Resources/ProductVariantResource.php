<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
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
            'name' => $this->name,
            'colour' => $this->whenLoaded('colour', fn () => $this->colour->name),
            'size' => $this->whenLoaded('size', fn () => $this->size->name),
            $this->mergeWhen($request->user(), [
                'box_qty' => $this->box_qty,
                'width' => $this->width,
                'height' => $this-> height,
                'length' => $this->length,
            ]),
        ];
    }
}
