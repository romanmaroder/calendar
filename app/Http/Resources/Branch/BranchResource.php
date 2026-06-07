<?php

namespace App\Http\Resources\Branch;

use App\Http\Resources\Company\CompanyMinResource;
use App\Http\Resources\Country\CountryMinResource;
use App\Http\Resources\User\UserMinResource;
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
        $response = [
            'id' => $this->id,
            'name' => $this->when(!empty($this->name), fn() => $this->name),
            'phone' => $this->when(!empty($this->phone), fn() => $this->phone),
            'description' => $this->when(!empty($this->description), fn() => $this->description),
            'contact' => $this->when(!empty($this->contact), fn() => $this->contact),
            'avatar' => $this->when(!empty($this->avatar), fn() => $this->avatar),
            'status' => $this->when(!empty($this->status), fn() => $this->status),
            'company_id' => $this->when(!empty($this->company_id), fn() => $this->company_id),
            'created_at' => $this->when(!empty($this->created_at),fn()=>$this->created_at?->format('Y-m-d')),
            'deleted_at' => $this->when(!empty($this->deleted_at), fn() => $this->deleted_at->format('Y-m-d')),
            'users_count' => $this->when($this->users_count > 0, fn() => $this->users_count),
            'country_id' => $this->country_id,
        ];

        // Добавляем users только если загружены
        if ($this->relationLoaded('users' ) && $this->users_count > 0) {
            $response['users'] = UserMinResource::collection($this->users)->resolve();
        }
        if ($this->relationLoaded('company')) {
            $response['company'] =CompanyMinResource::make($this->company)->resolve();
        }
        // Добавляем country ТОЛЬКО если загружены и company, и country внутри него
        // Это предотвращает Lazy Loading и гарантирует отсутствие ключа, если данных нет
        if ($this->relationLoaded('company') && $this->company?->relationLoaded('country')) {
            $response['country'] = CountryMinResource::make($this->company->country)->resolve();
        }
        return $response;
    }
}
