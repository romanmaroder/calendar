<?php

namespace App\Http\Filters\Pipeline\Client;

use Illuminate\Database\Eloquent\Builder;

class Id
{
    public function handle(Builder $builder, \Closure $next)
    {
        if (request('id')) {
            $builder->where('id', request('id'));
        }
        return $next($builder);
    }

}