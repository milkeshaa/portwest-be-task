<?php

declare(strict_types=1);

namespace App\Models\Traits;

trait HasNameColumnUcfirst
{
    public function getNameAttribute($value): string
    {
        return ucfirst($value);
    }
}