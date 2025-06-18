<?php

namespace App\Http\Filters\Pipeline\Client;

use Illuminate\Database\Eloquent\Builder;

class Email
{
    public function handle(Builder $builder, \Closure $next)
    {
        if (request('email')) {
            $builder->where('email', 'like', '%' . request('email') . '%');
        }
        return $next($builder);
    }

}