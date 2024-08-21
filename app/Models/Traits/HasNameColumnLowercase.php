<?php

declare(strict_types=1);

namespace App\Models\Traits;

trait HasNameColumnLowercase
{
    public function setNameAttribute($value): void
    {
        $this->attributes['name'] = strtolower($value);
    }
}