<?php

namespace App\Http\Resources\Company;

use App\Http\Resources\Country\CountryMinResource;
use App\Models\Company\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Company */
class CompanyMinWithCountryMinResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $response =  [
            'id' => $this->id,
            'name' => $this->when(!empty($this->name),fn()=>$this->name),
            'phone' => $this->when(!empty($this->phone),fn()=>$this->phone),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'country_id' => $this->when(!empty($this->country_id),fn()=>$this->country_id),
        ];

        if ($this->relationLoaded('country' )) {
            $response['country'] = CountryMinResource::make($this->country)->resolve();
        }
        return $response;

    }
}
