<?php

namespace App\Http\Filters\Pipeline\Client;

use Illuminate\Database\Eloquent\Builder;

class MiddleName
{
    public function handle(Builder $builder, \Closure $next)
    {
        if (request('middleName')) {
            $builder->where('middleName', 'like', '%' . request('middleName') . '%');
        }
        return $next($builder);
    }

}