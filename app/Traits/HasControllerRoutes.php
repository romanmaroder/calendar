<?php

namespace App\Traits;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

trait HasControllerRoutes
{
    /**
     * Получить список маршрутов, принадлежащих текущему контроллеру.
     *
     * @return array
     */
    public function getControllerRoutes(): array
    {
        return cache()->remember(
            'controller_routes_' . static::class,
            60, // 1 минута
            fn() => $this->fetchControllerRoutes()
        );

    }

    public function getControllerRouteNames(): array
    {
        return array_column($this->getControllerRoutes(), 'name');
    }

    public function getRoutesByMethod(string $httpMethod): array
    {
        $routes = $this->getControllerRoutes();
        return array_filter($routes, fn($route) => in_array($httpMethod, $route['method']));
    }

    public function getControllerUris(): array
    {
        return array_column($this->getControllerRoutes(), 'uri');
    }

    private function fetchControllerRoutes(): array { $routes = Route::getRoutes();
        $controllerRoutes = [];

        foreach ($routes as $route) {
            $action = $route->getAction();

            // Проверяем, что маршрут ведёт к текущему контроллеру
            if (isset($action['controller']) &&
                str_contains($action['controller'], static::class)) {
                $controllerRoutes[] = [
                    //'uri' => $route->uri(),
                    //'method' => $route->methods(),
                    //'name' => $route->getName(),
                    //'action' => $action['controller'],
                    Str::after($action['controller'], '@') => $route->getName(),
                ];
            }
        }

        return array_merge(...$controllerRoutes); }

}