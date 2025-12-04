<?php
/**
 * UploadController - Handles file uploads
 */

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Auth;
use App\Core\CSRF;

class UploadController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
    }
    
    /**
     * Upload image
     */
    public function image(): void
    {
        CSRF::check();
        
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $this->json(['success' => false, 'message' => 'ไม่พบไฟล์หรือเกิดข้อผิดพลาด'], 400);
        }
        
        $file = $_FILES['image'];
        $config = $this->config['upload'];
        
        // Validate file size
        if ($file['size'] > $config['max_size']) {
            $this->json([
                'success' => false, 
                'message' => 'ไฟล์มีขนาดใหญ่เกินไป (สูงสุด ' . ($config['max_size'] / 1024 / 1024) . 'MB)'
            ], 400);
        }
        
        // Validate MIME type
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);
        $allowedMimes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
        ];
        
        if (!isset($allowedMimes[$mimeType])) {
            $this->json([
                'success' => false, 
                'message' => 'ประเภทไฟล์ไม่ถูกต้อง (รองรับ: JPG, PNG, GIF, WebP)'
            ], 400);
        }
        
        // Generate unique filename
        $extension = $allowedMimes[$mimeType];
        $filename = 'cars/' . unique_filename($extension);
        $uploadPath = BASE_PATH . '/storage/uploads/' . $filename;
        
        // Create directory if not exists
        $dir = dirname($uploadPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        // Move file
        if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
            $this->json(['success' => false, 'message' => 'ไม่สามารถอัปโหลดไฟล์ได้'], 500);
        }
        
        // Resize image if needed (optional - basic implementation)
        $this->resizeImage($uploadPath, 1200, 800);
        
        $this->json([
            'success' => true,
            'message' => 'อัปโหลดสำเร็จ',
            'data' => [
                'path' => $filename,
                'url' => upload_url($filename),
            ],
        ]);
    }
    
    /**
     * Delete uploaded image
     */
    public function delete(): void
    {
        CSRF::check();
        
        $path = $this->input('path');
        
        if (empty($path)) {
            $this->json(['success' => false, 'message' => 'ไม่ระบุไฟล์'], 400);
        }
        
        // Security: ensure path doesn't contain traversal
        if (str_contains($path, '..')) {
            $this->json(['success' => false, 'message' => 'ไม่อนุญาต'], 403);
        }
        
        $fullPath = BASE_PATH . '/storage/uploads/' . $path;
        
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
        
        $this->json(['success' => true, 'message' => 'ลบไฟล์สำเร็จ']);
    }
    
    /**
     * Resize image if larger than max dimensions
     */
    private function resizeImage(string $path, int $maxWidth, int $maxHeight): void
    {
        $info = getimagesize($path);
        
        if (!$info) {
            return;
        }
        
        [$width, $height] = $info;
        $mime = $info['mime'];
        
        // Check if resize needed
        if ($width <= $maxWidth && $height <= $maxHeight) {
            return;
        }
        
        // Calculate new dimensions
        $ratio = min($maxWidth / $width, $maxHeight / $height);
        $newWidth = (int)($width * $ratio);
        $newHeight = (int)($height * $ratio);
        
        // Create image resource
        $source = match ($mime) {
            'image/jpeg' => imagecreatefromjpeg($path),
            'image/png' => imagecreatefrompng($path),
            'image/gif' => imagecreatefromgif($path),
            'image/webp' => imagecreatefromwebp($path),
            default => null,
        };
        
        if (!$source) {
            return;
        }
        
        // Create resized image
        $destination = imagecreatetruecolor($newWidth, $newHeight);
        
        // Preserve transparency for PNG and GIF
        if ($mime === 'image/png' || $mime === 'image/gif') {
            imagealphablending($destination, false);
            imagesavealpha($destination, true);
            $transparent = imagecolorallocatealpha($destination, 255, 255, 255, 127);
            imagefilledrectangle($destination, 0, 0, $newWidth, $newHeight, $transparent);
        }
        
        // Resize
        imagecopyresampled($destination, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        
        // Save
        match ($mime) {
            'image/jpeg' => imagejpeg($destination, $path, 85),
            'image/png' => imagepng($destination, $path, 8),
            'image/gif' => imagegif($destination, $path),
            'image/webp' => imagewebp($destination, $path, 85),
            default => null,
        };
        
        // Cleanup
        imagedestroy($source);
        imagedestroy($destination);
    }
}
