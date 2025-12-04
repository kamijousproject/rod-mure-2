<?php
/**
 * API AuthController - Authentication API
 */

namespace App\Controllers\Api;

use App\Core\BaseController;
use App\Core\Auth;
use App\Models\User;

class AuthController extends BaseController
{
    /**
     * Login and get token
     * POST /api/auth/login
     */
    public function login(): void
    {
        $input = $this->getJsonInput();
        
        $email = $input['email'] ?? '';
        $password = $input['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            $this->json([
                'success' => false,
                'error' => 'Email and password are required',
            ], 400);
        }
        
        if (!Auth::attempt($email, $password)) {
            $this->json([
                'success' => false,
                'error' => 'Invalid credentials',
            ], 401);
        }
        
        $user = Auth::user();
        $token = $this->generateToken($user['id']);
        
        $this->json([
            'success' => true,
            'data' => [
                'token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => 86400, // 24 hours
                'user' => [
                    'id' => (int)$user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                ],
            ],
        ]);
    }
    
    /**
     * Generate JWT-like token
     */
    private function generateToken(int $userId): string
    {
        $header = base64_encode(json_encode(['typ' => 'JWT', 'alg' => 'HS256']));
        
        $payload = base64_encode(json_encode([
            'user_id' => $userId,
            'iat' => time(),
            'exp' => time() + 86400, // 24 hours
        ]));
        
        $signature = hash_hmac('sha256', "$header.$payload", config('app.key'));
        
        return "$header.$payload.$signature";
    }
    
    /**
     * Get JSON input
     */
    private function getJsonInput(): array
    {
        $input = file_get_contents('php://input');
        return json_decode($input, true) ?? [];
    }
}
