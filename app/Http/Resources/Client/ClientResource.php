<?php

namespace App\Http\Resources\Client;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/** @mixin Client */
class ClientResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->when(!empty($this->id), fn() => $this->id),
            'name' => $this->when(!empty($this->name), fn() => $this->name),
            'surname' => $this->when(!empty($this->surname), fn() => $this->surname),
            'middleName' => $this->when(!empty($this->middleName), fn() => $this->middleName),
            'phone' => $this->when(!empty($this->phone), fn() => $this->phone),
            'comment' => $this->when(!empty($this->comment), fn() => $this->comment),
            'blacklist' => $this->when(!empty($this->blacklist), fn() => $this->blacklist),
            'prepayment' => $this->when(!empty($this->prepayment),fn()=> $this->prepayment),
            'discount' => $this->when(!empty($this->discount),fn()=> $this->discount),
            'records' => $this->when(!empty($this->records),fn()=> $this->records),
            'total' => $this->when(!empty($this->total),fn()=> $this->total),
            'source' => $this->when(!empty($this->source),fn()=> $this->source),
            'avatar' => $this->when(!empty($this->avatar), fn() => $this->avatar),
            'email' => $this->when(!empty($this->email),fn()=> $this->email),
            'birthday' => $this->when(!empty($this->birthday), fn() => $this->birthday?->format('Y-m-d')),
            'created_at' => $this->when(!empty($this->created_at),fn()=> $this->created_at->format('Y-m-d')),
            //'deleted_at' => $this->when(!empty($this->deleted_at),fn()=> $this->deleted_at->format('Y-m-d')),
            'deleted_at' => $this->deleted_at,
        ];
    }
}
