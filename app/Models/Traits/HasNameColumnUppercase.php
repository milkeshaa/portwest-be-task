<?php

declare(strict_types=1);

namespace App\Models\Traits;

trait HasNameColumnUppercase
{
    public function setNameAttribute($value): void
    {
        $this->attributes['name'] = strtoupper($value);
    }

    public function getNameAttribute($value): string
    {
        return strtoupper($value);
    }
}