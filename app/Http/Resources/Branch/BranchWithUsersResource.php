<?php

namespace App\Http\Resources\Branch;

use App\Http\Resources\User\UserMinResource;
use App\Models\Branch\Branch;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Branch */
class BranchWithUsersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $response = [
            'id' => $this->id,
            'name' => $this->when(!empty($this->name), fn() => $this->name),
            'phone' => $this->when(!empty($this->phone), fn() => $this->phone),
            'description' => $this->when(!empty($this->description), fn() => $this->description),
            'contact' => $this->when(!empty($this->contact), fn() => $this->contact),
            'avatar' => $this->when(!empty($this->avatar), fn() => $this->avatar),
            'status' => $this->when(!empty($this->status), fn() => $this->status),
            'created_at' => $this->created_at?->format('Y-m-d H:i'),
            'users_count' => $this->when($this->users_count > 0, fn() => $this->users_count),
        ];

        // Добавляем users только если загружены
        if ($this->relationLoaded('users' )) {
            $response['users'] = UserMinResource::collection($this->users)->resolve();
        }
        return $response;
    }
}
