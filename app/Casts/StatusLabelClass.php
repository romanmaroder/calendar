<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class StatusLabelClass implements CastsAttributes
{

    /**
     * @inheritDoc
     */
    public function get(Model $model, string $key, mixed $value, array $attributes)
    {
        return $value ? 'active' : 'disabled';
    }

    /**
     * @inheritDoc
     */
    public function set(Model $model, string $key, mixed $value, array $attributes)
    {
        return (bool)$value;
    }
}