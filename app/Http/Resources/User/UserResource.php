<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Branch\BranchMinResource;
use App\Http\Resources\Branch\BranchMinWithCountryMinResource;
use App\Http\Resources\Company\CompanyMinResource;
use App\Http\Resources\Country\CountryMinResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin User */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $response = [
            'id' => $this->when(!empty($this->id),fn()=>$this->id),
            'name' => $this->when(!empty($this->name),fn()=>$this->name),
            'surname' => $this->when(!empty($this->surname),fn()=>$this->surname),
            'middleName' => $this->when(!empty($this->middleName),fn()=>$this->middleName),
            'phone' => $this->when(!empty($this->phone),fn()=>$this->phone),
            'email' => $this->when(!empty($this->email),fn()=>$this->email),
            'avatar' => $this->when(!empty($this->avatar),fn()=>$this->avatar),
            'branch_id' => $this->when(!empty($this->branch_id),fn()=>$this->branch_id),
            'comment'=>$this->when(!empty($this->comment),fn()=>$this->comment),
            'birthday'=>$this->when(!empty($this->birthday),fn()=>$this->birthday?->format('Y-m-d')),
            'created_at' => $this->when(!empty($this->created_at),fn()=>$this->created_at?->format('Y-m-d')),
            'deleted_at' => $this->when(!empty($this->deleted_at),fn()=>$this->deleted_at?->format('Y-m-d')),
        ];

        if ($this->relationLoaded('branch' )) {
            $response['branch'] = BranchMinResource::make($this->branch)->resolve();
        }

        if ($this->relationLoaded('branch') && $this->branch?->relationLoaded('company') ) {
            $response['company']= CompanyMinResource::make($this->branch->company)->resolve();
        }
        if ($this->branch?->company->relationLoaded('country')) {
            $response['country'] = CountryMinResource::make($this->branch->company->country)->resolve();
            $response['resolved_country_id'] = $this->branch?->company?->country?->id;
        }
        return $response;
    }
}
