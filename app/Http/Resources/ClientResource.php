<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'avatar'=>$this->avatar,
            'surname' =>$this->surname,
            'middleName' =>$this->middleName,
            'phone' =>$this->phone,
            'comment' =>$this->comment,
            'blacklist' =>$this->blacklist,
            'prepayment' =>$this->prepayment,
            'discount' =>$this->discount,
            'records'=>$this->records,
            'total'=>$this->total,
            'source'=>$this->source,
            'email' =>$this->email,
        ];
    }
}
