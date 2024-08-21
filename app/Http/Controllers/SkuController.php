<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\IndexSkusRequest;
use App\Http\Resources\SkuCollection;
use App\Models\Sku;

class SkuController extends Controller
{
    public function index(IndexSkusRequest $request): SkuCollection
    {
        $query = Sku::query()->with([
            'variants',
            'product.category',
        ])->whereHas('variants', function ($query) {
            return $query->isAvailable();
        });
        if (false === empty($request->filter)) {
            $query->where('name', 'ilike', "%$request->filter%");
        }

        $items = $query
            ->orderBy('name', $request->sort)
            ->paginate(perPage: $request->per_page, page: $request->page);

        return new SkuCollection($items);
    }
}
