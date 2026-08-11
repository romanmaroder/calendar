<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    //TODO Удалить вывод запрещающего разрешения
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect(route('login'));
        }

        // Проверяем, что у пользователя есть ВСЕ указанные разрешения
       // \Log::info('Проверяемые разрешения:', $permissions);
        //\Log::info('Разрешения пользователя:', $user->getPermissionsViaRoles()->pluck('name')->toArray());
        //\Log::info('Роли пользователя:', $user->roles()->pluck('name')->toArray());
        foreach ($permissions as $permission) {
            if (!$user->hasPermissionTo($permission)) {
                abort(403, 'Недостаточно прав ' . $permission . '  ' . $user->surname );
            }
        }
        return $next($request);
    }

}