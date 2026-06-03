<?php

namespace App\Http\Resources;

use App\Models\Branch\Branch;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Branch */
class BranchResource extends JsonResource
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
            'description' => $this->description ?? null,
            'contact' => $this->contact,
            'avatar' => $this->when(!empty($this->avatar), fn() => $this->avatar),
            'status' => $this->status,
            'company_id' => $this->company_id,
            'created_at' => $this->created_at?->format('Y-m-d H:i'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i'),
            'deleted_at' => $this->when( !empty($this->deleted_at), fn()=> $this->deleted_at->format('Y-m-d H:i')),
            'users_count' => $this->when($this->users_count > 0, fn() => $this->users_count),
            'company' => $this->country_id ? [
                'country' => [
                    'id' => $this->country_id,
                    'name' => $this->country_name,
                    'phone_regex' => $this->phone_regex,
                    'phone_mask' => $this->phone_mask,
                ]
            ] : null,
            'users' => UserResource::collection($this->whenLoaded('users'))->resolve(),
        ];
    }
}
