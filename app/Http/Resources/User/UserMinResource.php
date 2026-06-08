<?php

namespace App\Http\Resources\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin User
 *
 * For view show
 * */
class UserMinResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->when(!empty($this->id), fn() => $this->id),
            'name' => $this->when(!empty($this->name), fn() => $this->name),
            'surname' => $this->when(!empty($this->surname), fn() => $this->surname),
            'phone' => $this->when(!empty($this->phone), fn() => $this->phone),
        ];
    }
}
