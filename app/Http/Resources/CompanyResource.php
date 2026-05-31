<?php

namespace App\Http\Resources;

use App\Models\Company\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Company */
class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'description' => $this->description,
            'contact' => $this->contact,
            'info' => $this->info,
            'avatar' => $this->when(!empty($this->avatar), fn() => $this->avatar),
            'country_id' => $this->country_id,
            'country' => $this->whenLoaded('country', function () {
                return [
                    'id' => $this->country->id,
                    'name' => $this->country->name,
                    'phone_regex' => $this->country->phone_regex,
                    'phone_mask' => $this->country->phone_mask,
                    'phone_code' => $this->when(!empty($this->country->phone_code),fn()=>$this->country->phone_code),
                    'code' => $this->when(!empty($this->country->code),fn()=>$this->country->code),
                    'iso_code' => $this->when(!empty($this->country->iso_code),fn()=>$this->country->iso_code),
                    'currency' => $this->when(!empty($this->country->currency),fn()=>$this->country->currency),
                ];
            }),
            'branches_count' => $this->when($this->branches_count > 0, fn() => $this->branches_count),
            'branches' =>$this->whenLoaded('branches', function () {
               return  $this->branches->map(function ($branches) {
                        return [
                            'id' => $branches->id,
                            'name' => $branches->name,
                            'contact' => $branches->contact,
                            'phone' => $branches->phone,
                            'created_at' => $branches->created_at?->format('Y-m-d'),
                        ];
                    });
            }),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            //'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
