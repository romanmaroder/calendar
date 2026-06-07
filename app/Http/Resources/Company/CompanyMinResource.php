<?php

namespace App\Http\Resources\Company;

use App\Models\Company\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Company */
class CompanyMinResource extends JsonResource
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
            'created_at' => $this->created_at?->format('Y-m-d'),
        ];
    }
}
