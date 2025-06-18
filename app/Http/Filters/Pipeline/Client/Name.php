<?php

namespace App\Http\Filters\Pipeline\Client;

use Illuminate\Database\Eloquent\Builder;

class Name
{
    public function handle(Builder $builder, \Closure $next)
    {
        if (request('name')) {
            $builder->where('name', 'like', '%' . request('name') . '%');
        }
        return $next($builder);
    }

}