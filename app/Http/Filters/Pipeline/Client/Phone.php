<?php

namespace App\Http\Filters\Pipeline\Client;

use Illuminate\Database\Eloquent\Builder;

class Phone
{
    public function handle(Builder $builder, \Closure $next)
    {
        if (request('phone')) {
            $builder->where('phone', request('phone'));
        }
        return $next($builder);
    }

}