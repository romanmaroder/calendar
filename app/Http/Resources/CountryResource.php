<?php

namespace App\Http\Resources;

use App\Models\Country\Country;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Country */
class CountryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'iso_code' => $this->iso_code,
            'phone_code' => $this->phone_code,
            'phone_regex' => $this->phone_regex,
            'phone_mask' => $this->phone_mask,
            'currency' => $this->currency,
            'active' => $this->active,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
