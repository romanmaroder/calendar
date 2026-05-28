<?php

namespace App\Http\Resources;

use App\Models\Company\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Company*/
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
                ];
            }),
            'branches' => BranchResource::collection($this->whenLoaded('branches'))->resolve(),
            'users' => UserResource::collection($this->whenLoaded('users')),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            //'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
