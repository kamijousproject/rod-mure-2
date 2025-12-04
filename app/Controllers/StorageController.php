<?php
/**
 * StorageController - Serve files from storage directory
 */

namespace App\Controllers;

use App\Core\BaseController;

class StorageController extends BaseController
{
    /**
     * MIME types mapping
     */
    private const MIME_TYPES = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
        'svg' => 'image/svg+xml',
        'pdf' => 'application/pdf',
        'mp4' => 'video/mp4',
        'mp3' => 'audio/mpeg',
    ];
    
    /**
     * Serve a file from storage
     */
    public function serve(string $path): void
    {
        // Sanitize path - prevent directory traversal
        $path = str_replace(['..', '\\'], ['', '/'], $path);
        
        // Build full file path
        $filePath = BASE_PATH . '/storage/uploads/' . $path;
        
        // Check if file exists
        if (!file_exists($filePath) || !is_file($filePath)) {
            http_response_code(404);
            echo 'File not found';
            exit;
        }
        
        // Get file extension and MIME type
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mimeType = self::MIME_TYPES[$extension] ?? 'application/octet-stream';
        
        // Get file size and modification time
        $fileSize = filesize($filePath);
        $lastModified = filemtime($filePath);
        
        // Set caching headers
        $etag = md5_file($filePath);
        
        // Check if browser has cached version
        if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] === $etag) {
            http_response_code(304);
            exit;
        }
        
        // Set headers
        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . $fileSize);
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $lastModified) . ' GMT');
        header('ETag: ' . $etag);
        header('Cache-Control: public, max-age=31536000'); // 1 year
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
        
        // Output file
        readfile($filePath);
        exit;
    }
}
