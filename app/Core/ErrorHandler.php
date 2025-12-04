<?php
/**
 * ErrorHandler - Application error handling and logging
 */

namespace App\Core;

class ErrorHandler
{
    private static bool $debug = false;
    private static string $logPath = '';
    
    /**
     * Register error handlers
     */
    public static function register(bool $debug = false): void
    {
        self::$debug = $debug;
        self::$logPath = BASE_PATH . '/storage/logs/error.log';
        
        // Ensure log directory exists
        $logDir = dirname(self::$logPath);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
        register_shutdown_function([self::class, 'handleShutdown']);
    }
    
    /**
     * Handle PHP errors
     */
    public static function handleError(int $level, string $message, string $file, int $line): bool
    {
        if (!(error_reporting() & $level)) {
            return false;
        }
        
        $error = sprintf('[%s] Error: %s in %s on line %d', date('Y-m-d H:i:s'), $message, $file, $line);
        self::log($error);
        
        if (self::$debug) {
            echo "<pre>$error</pre>";
        }
        
        return true;
    }
    
    /**
     * Handle uncaught exceptions
     */
    public static function handleException(\Throwable $exception): void
    {
        $error = sprintf(
            '[%s] Exception: %s in %s on line %d\nStack trace:\n%s',
            date('Y-m-d H:i:s'),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $exception->getTraceAsString()
        );
        
        self::log($error);
        
        http_response_code(500);
        
        if (self::$debug) {
            echo "<pre>$error</pre>";
        } else {
            include BASE_PATH . '/app/Views/errors/500.php';
        }
    }
    
    /**
     * Handle fatal errors on shutdown
     */
    public static function handleShutdown(): void
    {
        $error = error_get_last();
        
        if ($error !== null && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE])) {
            self::handleError($error['type'], $error['message'], $error['file'], $error['line']);
        }
    }
    
    /**
     * Log message to file
     */
    public static function log(string $message): void
    {
        $logPath = self::$logPath ?: BASE_PATH . '/storage/logs/error.log';
        
        $logDir = dirname($logPath);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        file_put_contents($logPath, $message . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}
