<?php

namespace App\Http\Resources\Country;

use App\Models\Country\Country;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Country */
class CountryMinResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->when(!empty($this->name),$this->name),
            'phone_regex' => $this->when(!empty($this->phone_regex),$this->phone_regex),
            'phone_mask' => $this->when(!empty($this->phone_mask),$this->phone_mask),
        ];
    }
}
