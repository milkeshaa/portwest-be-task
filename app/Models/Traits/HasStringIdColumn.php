<?php

declare(strict_types=1);

namespace App\Models\Traits;

use Illuminate\Support\Str;

trait HasStringIdColumn 
{
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (true === empty($model->getKey())) {
                $model->{$model->getKeyName()} = Str::uuid()->toString();
            }
        });
    }
}