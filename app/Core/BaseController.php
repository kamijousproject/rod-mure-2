<?php
/**
 * BaseController - Parent controller with common functionality
 */

namespace App\Core;

abstract class BaseController
{
    protected array $config;
    
    public function __construct()
    {
        $this->config = require BASE_PATH . '/config/config.php';
    }
    
    /**
     * Render a view with data
     */
    protected function view(string $view, array $data = []): void
    {
        // Add common data
        $data['config'] = $this->config;
        $data['auth'] = Auth::user();
        $data['csrf_token'] = CSRF::generate();
        $data['flash'] = Session::getFlash();
        
        // Extract data to variables
        extract($data);
        
        // Build view path
        $viewPath = BASE_PATH . '/app/Views/' . str_replace('.', '/', $view) . '.php';
        
        if (!file_exists($viewPath)) {
            throw new \RuntimeException("View not found: {$view}");
        }
        
        // Start output buffering for content
        ob_start();
        include $viewPath;
        $content = ob_get_clean();
        
        // Check if layout is needed
        $layoutPath = BASE_PATH . '/app/Views/layouts/main.php';
        if (file_exists($layoutPath) && !isset($noLayout)) {
            include $layoutPath;
        } else {
            echo $content;
        }
    }
    
    /**
     * Render JSON response
     */
    protected function json(mixed $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Redirect to URL
     */
    protected function redirect(string $url, int $status = 302): void
    {
        // If URL starts with /, add base path
        if (str_starts_with($url, '/')) {
            $url = url($url);
        }
        http_response_code($status);
        header("Location: {$url}");
        exit;
    }
    
    /**
     * Redirect back to previous page
     */
    protected function back(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->redirect($referer);
    }
    
    /**
     * Get input from request
     */
    protected function input(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }
    
    /**
     * Get all input
     */
    protected function all(): array
    {
        return array_merge($_GET, $_POST);
    }
    
    /**
     * Validate input data
     */
    protected function validate(array $data, array $rules): array
    {
        $errors = [];
        
        foreach ($rules as $field => $ruleString) {
            $fieldRules = explode('|', $ruleString);
            $value = $data[$field] ?? null;
            
            foreach ($fieldRules as $rule) {
                $params = [];
                if (str_contains($rule, ':')) {
                    [$rule, $paramString] = explode(':', $rule, 2);
                    $params = explode(',', $paramString);
                }
                
                $error = $this->checkRule($field, $value, $rule, $params, $data);
                if ($error) {
                    $errors[$field][] = $error;
                }
            }
        }
        
        return $errors;
    }
    
    /**
     * Check single validation rule
     */
    private function checkRule(string $field, mixed $value, string $rule, array $params, array $data): ?string
    {
        $label = ucfirst(str_replace('_', ' ', $field));
        
        return match ($rule) {
            'required' => empty($value) && $value !== '0' ? "{$label} is required" : null,
            'email' => !empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL) ? "{$label} must be a valid email" : null,
            'min' => strlen((string)$value) < (int)$params[0] ? "{$label} must be at least {$params[0]} characters" : null,
            'max' => strlen((string)$value) > (int)$params[0] ? "{$label} must not exceed {$params[0]} characters" : null,
            'numeric' => !is_numeric($value) && !empty($value) ? "{$label} must be a number" : null,
            'integer' => !filter_var($value, FILTER_VALIDATE_INT) && !empty($value) ? "{$label} must be an integer" : null,
            'confirmed' => ($value !== ($data[$field . '_confirmation'] ?? null)) ? "{$label} confirmation does not match" : null,
            'unique' => $this->checkUnique($field, $value, $params) ? "{$label} already exists" : null,
            'in' => !in_array($value, $params) ? "{$label} must be one of: " . implode(', ', $params) : null,
            default => null,
        };
    }
    
    /**
     * Check if value is unique in database
     */
    private function checkUnique(string $field, mixed $value, array $params): bool
    {
        if (empty($value)) {
            return false;
        }
        
        $table = $params[0] ?? $field . 's';
        $column = $params[1] ?? $field;
        $exceptId = $params[2] ?? null;
        
        $sql = "SELECT COUNT(*) as count FROM {$table} WHERE {$column} = ?";
        $sqlParams = [$value];
        
        if ($exceptId) {
            $sql .= " AND id != ?";
            $sqlParams[] = $exceptId;
        }
        
        $result = Database::fetch($sql, $sqlParams);
        return ($result['count'] ?? 0) > 0;
    }
    
    /**
     * Require authentication
     */
    protected function requireAuth(): void
    {
        if (!Auth::check()) {
            Session::setFlash('error', 'Please login to continue');
            $this->redirect('/login');
        }
    }
    
    /**
     * Require specific role
     */
    protected function requireRole(string|array $roles): void
    {
        $this->requireAuth();
        
        $roles = is_array($roles) ? $roles : [$roles];
        $user = Auth::user();
        
        if (!in_array($user['role'], $roles)) {
            Session::setFlash('error', 'You do not have permission to access this page');
            $this->redirect('/');
        }
    }
    
    /**
     * Require admin role
     */
    protected function requireAdmin(): void
    {
        $this->requireRole('admin');
    }
}
