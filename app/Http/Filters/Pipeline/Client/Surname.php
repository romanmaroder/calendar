<?php

namespace App\Http\Filters\Pipeline\Client;

use Illuminate\Database\Eloquent\Builder;

class Surname
{
    public function handle(Builder $builder, \Closure $next)
    {
        if (request('surname')) {
            $builder->where('surname', 'like', '%' . request('surname') . '%');
        }
        return $next($builder);
    }

}