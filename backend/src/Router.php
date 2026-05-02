<?php

class Router
{
    private array $routes = [];

    public function add(string $method, string $pattern, callable $handler): void
    {
        $this->routes[] = compact('method', 'pattern', 'handler');
    }

    public function dispatch(string $method, string $uri): void
    {
        foreach ($this->routes as $route) {
            $pattern = preg_replace('#:id#', '([^/]+)', $route['pattern']);
            $pattern = '#^' . $pattern . '$#';

            if ($method === $route['method'] && preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                call_user_func_array($route['handler'], $matches);
                return;
            }
        }

        jsonResponse(['message' => 'Маршрут не найден'], 404);
    }
}