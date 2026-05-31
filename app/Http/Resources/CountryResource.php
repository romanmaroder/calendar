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
            'code' => $this->when(!empty($this->code),$this->code),
            'name' => $this->when(!empty($this->name),$this->name),
            'iso_code' => $this->when(!empty($this->iso_code),$this->iso_code),
            'phone_code' =>$this->when(!empty($this->phone_code),$this->phone_code),
            'phone_regex' => $this->when(!empty($this->phone_regex),$this->phone_regex),
            'phone_mask' => $this->when(!empty($this->phone_mask),$this->phone_mask),
            'currency' => $this->when(!empty($this->currency),$this->currency),
            'active' => $this->when(!empty($this->active),$this->active),
            'created_at' =>$this->when(!empty($this->created_at),$this->created_at?->format('Y-m-d H:i:s')),
            'updated_at' =>$this->when(!empty($this->updated_at),$this->updated_at?->format('Y-m-d H:i:s')),
        ];
    }
}
