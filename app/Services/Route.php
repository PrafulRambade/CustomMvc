<?php
namespace App\Services;

class Route {
    private static $routes = [];
    private static $controllerNamespace = 'App\Controllers\\';

    public static function add($uri, $controller, $action, $method='GET', $middleware=[]) {
        self::$routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'action' => $action,
            'middleware' => $middleware
        ];
    }

    public static function get($uri, $controller, $action, $middleware=[]) {
        self::add($uri, $controller, $action, 'GET', $middleware);
    }

    public static function post($uri, $controller, $action, $middleware=[]) {
        self::add($uri, $controller, $action, 'POST', $middleware);
    }

    public static function handle() {
        $requestURI = $_SERVER['REQUEST_URI'];
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        foreach(self::$routes as $route) {
            $pattern = preg_replace('#/{([^/]+)}#', '/([^/]+)', $route['uri']);
            $pattern = str_replace('/', '\/', $pattern);
            if (preg_match('#^' . $pattern . '$#', $requestURI, $matches) && $route['method'] == $requestMethod) {
                array_shift($matches); // Remove the first match (the full match)
                
                // handle middleware 
                foreach($route['middleware'] as $middleware){
                    $middlewareClass = new $middleware;
                    $middlewareClass->handle();
                }

                $controllerClass = self::$controllerNamespace . $route['controller'];
                $action = $route['action'];

                $controller = new $controllerClass();
                call_user_func_array([$controller, $action], $matches);
                return;
            }
        }
        echo '404 - page not found';
    }
}
