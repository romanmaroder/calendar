<?php

namespace App\Traits;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;




trait HasControllerRoutes
{
    /**
     * Получить список маршрутов, принадлежащих текущему контроллеру.
     *
     * Возвращает массив вида
     * [
     *  item1=>[
     *      uri=>[
     *         'index' => 'item1.index'
     *         'store' => 'item1.store'
     *      ]
     *  ],
     * item2=>[
     *       uri=>[
     *          'index' => 'item2.index'
     *          'store' => 'item2.store'
     *       ]
     *   ]
     * ]
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

    public function getRoutesByController(string $controllerClass): array
    {
        $controllerRoutes = [];

        foreach (Route::getRoutes() as $route) {
            $action = $route->getAction();
            if (
                isset($action['controller']) &&
                str_contains($action['controller'], $controllerClass)
            ) {
                $controllerRoutes[] = [
                    //'method' => implode(', ', $route->methods()),
                    //'name'   => $route->getName() ?? 'unnamed',
                    //Str::after($action['controller'], '@')   => $route->getName() ?? 'unnamed',
                    //Str::after($action['controller'], '@') => $route->getName(),
                    //'action' => $action['uses'],
                    'uri'    => [Str::after($action['uses'], '@') =>$route->getName() ??  $route->uri()],
                ];
            }
        }
        return [Str::lower(Str::before(class_basename($controllerClass), 'Controller')) => array_merge_recursive(
            ... $controllerRoutes
        )];
    }


    /**
     * Объединяет маршруты контроллеров в один массив
     * @param ...$routes
     * @return array
     */
    public function routeMerge(...$routes): array
    {
         return array_merge(...$routes);
    }

    private function fetchControllerRoutes(): array
    {
        $controllerRoutes = [];

        foreach (Route::getRoutes() as $route) {
            $action = $route->getAction();

            // Проверяем, что маршрут ведёт к текущему контроллеру
            if (isset($action['controller']) && str_contains($action['controller'], static::class)) {

                $controllerRoutes[] = [
                    //'method' => $route->methods(),
                    //'name' => $route->getName(),
                    //'uri' => $route->uri(),
                    //Str::after($action['controller'], '@') => $route->getName(),
                    //'action' => $action['uses'] ,
                    'uri'    => [Str::after($action['uses'], '@') =>$route->getName() ?? $route->uri()],
                ];
            }
        }

        return [
            Str::lower(Str::before(class_basename(static::class), 'Controller')) => array_merge_recursive(
                ...$controllerRoutes
            )
        ];
    }

}