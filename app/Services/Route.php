<?php
namespace App\Services;

class Route {
    private static $routes = [];
    private static $controllerNamespace = 'App\Controllers\\';
    private static $cachedRoutes = [];

    public static function add($uri, $controller, $action, $method = 'GET', $middleware = []) {
        if (!self::isValidUri($uri)) {
            throw new \InvalidArgumentException('Invalid URI pattern');
        }
        
        $allowedMethods = ['GET', 'POST', 'PUT', 'DELETE'];
        if (!in_array($method, $allowedMethods)) {
            throw new \InvalidArgumentException('Invalid HTTP method');
        }

        // Sanitize URI components if needed
        $uri = self::sanitizeUri($uri);
        
        self::$routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'action' => $action,
            'middleware' => $middleware
        ];
    }

    public static function get($uri, $controller, $action, $middleware = []) {
        self::add($uri, $controller, $action, 'GET', $middleware);
    }

    public static function post($uri, $controller, $action, $middleware = []) {
        self::add($uri, $controller, $action, 'POST', $middleware);
    }

    public static function handle() {
        $requestURI = $_SERVER['REQUEST_URI'];
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        // Check if the matched route is cached
        $cacheKey = $requestURI . '_' . $requestMethod;
        if (isset(self::$cachedRoutes[$cacheKey])) {
            $routeInfo = self::$cachedRoutes[$cacheKey];
        } else {
            // Match routes
            $routeInfo = self::matchRoute($requestURI, $requestMethod);
            // Cache the matched route
            self::$cachedRoutes[$cacheKey] = $routeInfo;
        }

        if ($routeInfo && isset($routeInfo['route'])) {
            // Handle middleware and execute controller action
            self::handleRoute($routeInfo['route'], $routeInfo['matches']);
        } else {
            http_response_code(404);
            echo '404 - Page not found';
        }
    }

    private static function matchRoute($requestURI, $requestMethod) {
        foreach (self::$routes as $route) {
            $pattern = '#^' . preg_replace('#\{([^/}]+)\}#', '([^/]+)', $route['uri']) . '$#';
            if (preg_match($pattern, $requestURI, $matches) && $route['method'] == $requestMethod) {
                array_shift($matches); // Remove the full match
                return ['route' => $route, 'matches' => $matches]; // Return both route and matches
            }
        }
        return null;
    }

    private static function handleRoute($route, $matches) {
        // Handle middleware
        foreach ($route['middleware'] as $middleware) {
            $middlewareInstance = new $middleware;
            $middlewareInstance->handle();
        }
    
        // Execute controller action with $matches as arguments
        $controllerClass = self::$controllerNamespace . $route['controller'];
        $action = $route['action'];
        $controller = new $controllerClass();
        call_user_func_array([$controller, $action], $matches);
    }

    private static function isValidUri($uri) {
        // Validate URI pattern using regular expression
        return preg_match('/^\/(?:[a-zA-Z0-9_-]+|\/\{[a-zA-Z0-9_-]+\})*(?:\/[a-zA-Z0-9_-]+)?(?:\/\{[a-zA-Z0-9_-]+\})?\/?$/', $uri);
    }

    private static function sanitizeUri($uri) {
        // Implement URI sanitization logic if needed
        // For example, you can use htmlspecialchars to escape special characters
        return htmlspecialchars($uri, ENT_QUOTES, 'UTF-8');
    }
}
?>
