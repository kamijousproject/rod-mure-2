<?php
/**
 * RateLimiter - Simple IP-based rate limiting
 */

namespace App\Core;

class RateLimiter
{
    private int $maxRequests;
    private int $period;
    private string $storagePath;
    
    public function __construct(int $maxRequests = 60, int $period = 60)
    {
        $this->maxRequests = $maxRequests;
        $this->period = $period;
        $this->storagePath = BASE_PATH . '/storage/rate_limits/';
        
        if (!is_dir($this->storagePath)) {
            mkdir($this->storagePath, 0755, true);
        }
    }
    
    /**
     * Check if IP is rate limited
     */
    public function check(string $ip): bool
    {
        $file = $this->storagePath . md5($ip) . '.json';
        $now = time();
        
        // Load existing data
        if (file_exists($file)) {
            $data = json_decode(file_get_contents($file), true);
            
            // Clean old entries
            $data['requests'] = array_filter(
                $data['requests'] ?? [],
                fn($timestamp) => ($now - $timestamp) < $this->period
            );
        } else {
            $data = ['requests' => []];
        }
        
        // Check limit
        if (count($data['requests']) >= $this->maxRequests) {
            return false;
        }
        
        // Add current request
        $data['requests'][] = $now;
        
        // Save
        file_put_contents($file, json_encode($data), LOCK_EX);
        
        return true;
    }
    
    /**
     * Get remaining requests for IP
     */
    public function remaining(string $ip): int
    {
        $file = $this->storagePath . md5($ip) . '.json';
        
        if (!file_exists($file)) {
            return $this->maxRequests;
        }
        
        $data = json_decode(file_get_contents($file), true);
        $now = time();
        
        $validRequests = array_filter(
            $data['requests'] ?? [],
            fn($timestamp) => ($now - $timestamp) < $this->period
        );
        
        return max(0, $this->maxRequests - count($validRequests));
    }
    
    /**
     * Clear rate limit data for IP
     */
    public function clear(string $ip): void
    {
        $file = $this->storagePath . md5($ip) . '.json';
        
        if (file_exists($file)) {
            unlink($file);
        }
    }
}
