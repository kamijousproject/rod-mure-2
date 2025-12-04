<?php
/**
 * Application Routes
 * Define all application routes here
 */

/** @var \App\Core\Router $router */

// Make router available globally for URL generation
$GLOBALS['router'] = $router;

// ============================================
// PUBLIC ROUTES (Guests)
// ============================================

// Home
$router->get('/', 'HomeController@index', 'home');

// Car Listings
$router->get('/cars', 'CarController@index', 'cars.index');
$router->get('/cars/{slug}', 'CarController@show', 'cars.show');

// Search (AJAX)
$router->get('/search', 'CarController@search', 'cars.search');

// ============================================
// AUTHENTICATION ROUTES
// ============================================

// Login
$router->get('/login', 'AuthController@showLogin', 'login');
$router->post('/login', 'AuthController@login', 'login.post');

// Register
$router->get('/register', 'AuthController@showRegister', 'register');
$router->post('/register', 'AuthController@register', 'register.post');

// Logout
$router->post('/logout', 'AuthController@logout', 'logout');

// Password Reset
$router->get('/forgot-password', 'AuthController@showForgotPassword', 'password.request');
$router->post('/forgot-password', 'AuthController@sendResetLink', 'password.email');
$router->get('/reset-password/{token}', 'AuthController@showResetForm', 'password.reset');
$router->post('/reset-password', 'AuthController@resetPassword', 'password.update');

// ============================================
// USER ROUTES (Authenticated)
// ============================================

// Dashboard
$router->get('/dashboard', 'DashboardController@index', 'dashboard');

// Profile
$router->get('/profile', 'ProfileController@show', 'profile');
$router->get('/profile/edit', 'ProfileController@edit', 'profile.edit');
$router->post('/profile/update', 'ProfileController@update', 'profile.update');
$router->post('/profile/password', 'ProfileController@updatePassword', 'profile.password');

// ============================================
// SELLER ROUTES
// ============================================

// My Listings
$router->get('/my-cars', 'SellerController@index', 'seller.cars');
$router->get('/my-cars/create', 'SellerController@create', 'seller.cars.create');
$router->post('/my-cars', 'SellerController@store', 'seller.cars.store');
$router->get('/my-cars/{id}/edit', 'SellerController@edit', 'seller.cars.edit');
$router->post('/my-cars/{id}', 'SellerController@update', 'seller.cars.update');
$router->post('/my-cars/{id}/delete', 'SellerController@destroy', 'seller.cars.destroy');
$router->post('/my-cars/{id}/status', 'SellerController@updateStatus', 'seller.cars.status');

// Image Upload
$router->post('/upload/image', 'UploadController@image', 'upload.image');
$router->post('/upload/delete', 'UploadController@delete', 'upload.delete');

// ============================================
// MESSAGING ROUTES
// ============================================

// Inquiries
$router->get('/inquiries', 'InquiryController@index', 'inquiries');
$router->get('/inquiries/{id}', 'InquiryController@show', 'inquiries.show');
$router->post('/inquiries', 'InquiryController@store', 'inquiries.store');
$router->post('/inquiries/{id}/reply', 'InquiryController@reply', 'inquiries.reply');

// ============================================
// ADMIN ROUTES
// ============================================

// Admin Dashboard
$router->get('/admin', 'Admin\DashboardController@index', 'admin.dashboard');

// Admin Users
$router->get('/admin/users', 'Admin\UserController@index', 'admin.users');
$router->get('/admin/users/{id}', 'Admin\UserController@show', 'admin.users.show');
$router->post('/admin/users/{id}/status', 'Admin\UserController@updateStatus', 'admin.users.status');
$router->post('/admin/users/{id}/role', 'Admin\UserController@updateRole', 'admin.users.role');

// Admin Cars
$router->get('/admin/cars', 'Admin\CarController@index', 'admin.cars');
$router->get('/admin/cars/{id}', 'Admin\CarController@show', 'admin.cars.show');
$router->post('/admin/cars/{id}/approve', 'Admin\CarController@approve', 'admin.cars.approve');
$router->post('/admin/cars/{id}/reject', 'Admin\CarController@reject', 'admin.cars.reject');
$router->post('/admin/cars/{id}/feature', 'Admin\CarController@feature', 'admin.cars.feature');
$router->post('/admin/cars/{id}/delete', 'Admin\CarController@destroy', 'admin.cars.delete');

// Admin Brands & Models
$router->get('/admin/brands', 'Admin\BrandController@index', 'admin.brands');
$router->post('/admin/brands', 'Admin\BrandController@store', 'admin.brands.store');
$router->post('/admin/brands/{id}', 'Admin\BrandController@update', 'admin.brands.update');
$router->post('/admin/brands/{id}/delete', 'Admin\BrandController@destroy', 'admin.brands.delete');

$router->get('/admin/models', 'Admin\ModelController@index', 'admin.models');
$router->post('/admin/models', 'Admin\ModelController@store', 'admin.models.store');
$router->post('/admin/models/{id}', 'Admin\ModelController@update', 'admin.models.update');
$router->post('/admin/models/{id}/delete', 'Admin\ModelController@destroy', 'admin.models.delete');

// Admin Reports
$router->get('/admin/reports', 'Admin\ReportController@index', 'admin.reports');
$router->get('/admin/reports/export', 'Admin\ReportController@export', 'admin.reports.export');

// ============================================
// API ROUTES
// ============================================

// Public API
$router->get('/api/cars', 'Api\CarController@index', 'api.cars');
$router->get('/api/cars/{id}', 'Api\CarController@show', 'api.cars.show');
$router->get('/api/brands', 'Api\BrandController@index', 'api.brands');
$router->get('/api/models', 'Api\ModelController@index', 'api.models');

// Authenticated API
$router->post('/api/auth/login', 'Api\AuthController@login', 'api.auth.login');
$router->post('/api/cars', 'Api\CarController@store', 'api.cars.store');
$router->put('/api/cars/{id}', 'Api\CarController@update', 'api.cars.update');
$router->delete('/api/cars/{id}', 'Api\CarController@destroy', 'api.cars.destroy');
