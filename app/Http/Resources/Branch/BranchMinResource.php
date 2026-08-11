<?php

namespace App\Http\Resources\Branch;

use App\Http\Resources\Company\CompanyMinResource;
use App\Http\Resources\Company\CompanyResource;
use App\Http\Resources\Country\CountryMinResource;
use App\Http\Resources\Country\CountryResource;
use App\Http\Resources\User\UserResource;
use App\Models\Branch\Branch;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Branch
 *
 * For edit view
 * */
class BranchMinResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->when(!empty($this->id),fn()=>$this->id),
            'name' => $this->when(!empty($this->name), fn() => $this->name),
            'phone' => $this->when(!empty($this->phone), fn() => $this->phone),
            'description' => $this->when(!empty($this->description), fn() => $this->description),
            'contact' => $this->when(!empty($this->contact), fn() => $this->contact),
            'avatar' => $this->when(!empty($this->avatar), fn() => $this->avatar),
            'status' => $this->when(!empty($this->status), fn() => $this->status),
            'company_id' => $this->when(!empty($this->company_id), fn() => $this->company_id),
            'deleted_at' => $this->deleted_at ?? ''
        ];
    }
}
