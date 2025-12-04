<?php
/**
 * Router - Handles URL routing and dispatching
 */

namespace App\Core;

class Router
{
    private array $routes = [];
    private array $namedRoutes = [];
    
    /**
     * Add a GET route
     */
    public function get(string $path, string $handler, ?string $name = null): self
    {
        return $this->addRoute('GET', $path, $handler, $name);
    }
    
    /**
     * Add a POST route
     */
    public function post(string $path, string $handler, ?string $name = null): self
    {
        return $this->addRoute('POST', $path, $handler, $name);
    }
    
    /**
     * Add a PUT route
     */
    public function put(string $path, string $handler, ?string $name = null): self
    {
        return $this->addRoute('PUT', $path, $handler, $name);
    }
    
    /**
     * Add a DELETE route
     */
    public function delete(string $path, string $handler, ?string $name = null): self
    {
        return $this->addRoute('DELETE', $path, $handler, $name);
    }
    
    /**
     * Add route to collection
     */
    private function addRoute(string $method, string $path, string $handler, ?string $name = null): self
    {
        // Convert path parameters to regex
        $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $path);
        $pattern = '#^' . $pattern . '$#';
        
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'pattern' => $pattern,
            'handler' => $handler,
            'name' => $name,
        ];
        
        if ($name) {
            $this->namedRoutes[$name] = $path;
        }
        
        return $this;
    }
    
    /**
     * Generate URL for named route
     */
    public static function url(string $name, array $params = []): string
    {
        global $router;
        
        if (!isset($router->namedRoutes[$name])) {
            return '#';
        }
        
        $path = $router->namedRoutes[$name];
        
        foreach ($params as $key => $value) {
            $path = str_replace('{' . $key . '}', (string)$value, $path);
        }
        
        return $path;
    }
    
    /**
     * Dispatch request to appropriate handler
     */
    public function dispatch(string $uri, string $method): void
    {
        // Remove query string from URI
        $uri = strtok($uri, '?');
        
        // Remove trailing slash except for root
        if ($uri !== '/' && str_ends_with($uri, '/')) {
            $uri = rtrim($uri, '/');
        }
        
        // Handle method override for PUT/DELETE
        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
        }
        
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            
            if (preg_match($route['pattern'], $uri, $matches)) {
                // Extract named parameters
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                
                // Parse handler
                [$controllerName, $methodName] = explode('@', $route['handler']);
                $controllerClass = "App\\Controllers\\{$controllerName}";
                
                if (!class_exists($controllerClass)) {
                    $this->notFound("Controller {$controllerName} not found");
                    return;
                }
                
                $controller = new $controllerClass();
                
                if (!method_exists($controller, $methodName)) {
                    $this->notFound("Method {$methodName} not found in {$controllerName}");
                    return;
                }
                
                // Call controller method with parameters
                call_user_func_array([$controller, $methodName], $params);
                return;
            }
        }
        
        // No route matched
        $this->notFound();
    }
    
    /**
     * Handle 404 Not Found
     */
    private function notFound(string $message = 'Page not found'): void
    {
        http_response_code(404);
        
        if ($this->isApiRequest()) {
            header('Content-Type: application/json');
            echo json_encode(['error' => $message]);
        } else {
            include BASE_PATH . '/app/Views/errors/404.php';
        }
    }
    
    /**
     * Check if request is API request
     */
    private function isApiRequest(): bool
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        return str_starts_with($uri, '/api/');
    }
}
