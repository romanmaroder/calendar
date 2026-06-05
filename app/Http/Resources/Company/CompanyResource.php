<?php

namespace App\Http\Resources\Company;

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
            'name' => $this->when(!empty($this->name),fn()=>$this->name),
            'phone' => $this->when(!empty($this->phone),fn()=>$this->phone),
            'description' => $this->when(!empty($this->description),fn()=>$this->description),
            'contact' => $this->when(!empty($this->contact),fn()=>$this->contact),
            'info' => $this->when(!empty($this->info),fn()=>$this->info),
            'avatar' => $this->when(!empty($this->avatar), fn() => $this->avatar),
            'country_id' => $this->when(!empty($this->country_id), fn() => $this->country_id),
            'country' => $this->whenLoaded('country', function () {
                return [
                    'id' => $this->country->id,
                    'code' => $this->when(!empty($this->country->code),fn()=>$this->country->code),
                    'currency' => $this->when(!empty($this->country->currency),fn()=>$this->country->currency),
                    'phone_code' => $this->when(!empty($this->country->phone_code),fn()=>$this->country->phone_code),
                    'iso_code' => $this->when(!empty($this->country->iso_code),fn()=>$this->country->iso_code),
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
        ];
    }
}
