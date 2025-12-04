# Used Car Marketplace - PHP MVC

เว็บไซต์ตลาดรถมือสองพัฒนาด้วย PHP แบบ Lightweight MVC

## Features

- 🚗 **ค้นหารถ** - ค้นหาและกรองรถด้วยเงื่อนไขต่างๆ (ยี่ห้อ, รุ่น, ปี, ราคา, เกียร์, เชื้อเพลิง, จังหวัด)
- 👤 **ระบบสมาชิก** - ลงทะเบียน, เข้าสู่ระบบ, ลืมรหัสผ่าน
- 📝 **ลงประกาศขายรถ** - สำหรับผู้ขาย พร้อมอัปโหลดรูปหลายไฟล์
- 💬 **ระบบสอบถาม** - ผู้ซื้อสามารถส่งข้อความถึงผู้ขาย
- 🔧 **Admin Panel** - จัดการผู้ใช้, ประกาศ, ยี่ห้อ/รุ่น, รายงาน
- 🌐 **REST API** - API สำหรับ Mobile App หรือ Third-party
- 🔒 **ความปลอดภัย** - CSRF Protection, PDO Prepared Statements, Password Hashing

## Requirements

- PHP >= 8.1
- MySQL/MariaDB
- Composer
- Apache/Nginx (or PHP built-in server)

## Installation

### 1. Clone หรือ Download โปรเจกต์

```bash
cd c:\xampp\htdocs\used-car
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Configure Environment

```bash
# Copy .env.example to .env
copy .env.example .env

# Edit .env file with your database credentials
```

แก้ไขไฟล์ `.env`:
```
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=used_car_db
DB_USERNAME=root
DB_PASSWORD=
APP_KEY=your-random-32-character-string
```

### 4. Create Database & Run Migrations

```bash
# Run migration (creates database and tables)
php migrations/migrate.php

# Seed sample data
php seeds/seed.php
```

### 5. Set Permissions (Linux/Mac)

```bash
chmod -R 755 storage/
chmod -R 755 public/
```

### 6. Start Development Server

```bash
# Using PHP built-in server
php -S localhost:8000 -t public

# Or configure Apache/Nginx to point to public/ folder
```

### 7. Access the Application

- **Frontend**: http://localhost:8000
- **Admin Panel**: http://localhost:8000/admin

## Test Accounts

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@usedcar.test | password123 |
| Seller | seller1@usedcar.test | password123 |
| Buyer | buyer1@usedcar.test | password123 |

## Project Structure

```
used-car/
├── app/
│   ├── Controllers/        # Controller classes
│   │   ├── Admin/          # Admin controllers
│   │   └── Api/            # API controllers
│   ├── Core/               # Core framework classes
│   │   ├── Auth.php        # Authentication
│   │   ├── BaseController.php
│   │   ├── CSRF.php        # CSRF protection
│   │   ├── Database.php    # PDO wrapper
│   │   ├── ErrorHandler.php
│   │   ├── RateLimiter.php
│   │   ├── Router.php      # URL routing
│   │   └── Session.php
│   ├── Helpers/            # Helper functions
│   ├── Models/             # Model classes
│   ├── Views/              # View templates
│   │   ├── admin/
│   │   ├── auth/
│   │   ├── cars/
│   │   ├── dashboard/
│   │   ├── errors/
│   │   ├── layouts/
│   │   ├── partials/
│   │   └── seller/
│   └── routes.php          # Route definitions
├── config/
│   └── config.php          # Application config
├── migrations/             # Database migrations
├── public/                 # Web root
│   ├── assets/             # CSS, JS, images
│   └── index.php           # Entry point
├── seeds/                  # Sample data
├── storage/
│   ├── logs/               # Application logs
│   ├── rate_limits/        # Rate limit data
│   └── uploads/            # Uploaded files
│       └── cars/           # Car images
├── tests/                  # Unit tests
├── .env.example
├── composer.json
└── README.md
```

## Routes

### Public Routes
| Method | URL | Controller@Method | Description |
|--------|-----|-------------------|-------------|
| GET | / | HomeController@index | Homepage |
| GET | /cars | CarController@index | Car listings |
| GET | /cars/{slug} | CarController@show | Car detail |
| GET | /search | CarController@search | AJAX search |

### Auth Routes
| Method | URL | Controller@Method | Description |
|--------|-----|-------------------|-------------|
| GET | /login | AuthController@showLogin | Login form |
| POST | /login | AuthController@login | Process login |
| GET | /register | AuthController@showRegister | Register form |
| POST | /register | AuthController@register | Process registration |
| POST | /logout | AuthController@logout | Logout |
| GET | /forgot-password | AuthController@showForgotPassword | Reset request |
| POST | /forgot-password | AuthController@sendResetLink | Send reset email |
| GET | /reset-password/{token} | AuthController@showResetForm | Reset form |
| POST | /reset-password | AuthController@resetPassword | Process reset |

### User Routes (Authenticated)
| Method | URL | Controller@Method | Description |
|--------|-----|-------------------|-------------|
| GET | /dashboard | DashboardController@index | User dashboard |
| GET | /profile | ProfileController@show | View profile |
| GET | /profile/edit | ProfileController@edit | Edit profile form |
| POST | /profile/update | ProfileController@update | Update profile |

### Seller Routes
| Method | URL | Controller@Method | Description |
|--------|-----|-------------------|-------------|
| GET | /my-cars | SellerController@index | My listings |
| GET | /my-cars/create | SellerController@create | Create form |
| POST | /my-cars | SellerController@store | Store listing |
| GET | /my-cars/{id}/edit | SellerController@edit | Edit form |
| POST | /my-cars/{id} | SellerController@update | Update listing |
| POST | /my-cars/{id}/delete | SellerController@destroy | Delete listing |

### Admin Routes
| Method | URL | Controller@Method | Description |
|--------|-----|-------------------|-------------|
| GET | /admin | Admin\DashboardController@index | Admin dashboard |
| GET | /admin/users | Admin\UserController@index | Manage users |
| GET | /admin/cars | Admin\CarController@index | Manage cars |
| GET | /admin/brands | Admin\BrandController@index | Manage brands |
| GET | /admin/models | Admin\ModelController@index | Manage models |
| GET | /admin/reports | Admin\ReportController@index | Reports |
| GET | /admin/reports/export | Admin\ReportController@export | CSV export |

### API Routes
| Method | URL | Description |
|--------|-----|-------------|
| GET | /api/cars | Get car listings |
| GET | /api/cars/{id} | Get car detail |
| GET | /api/brands | Get brands |
| GET | /api/models | Get models |
| POST | /api/auth/login | Login (get token) |
| POST | /api/cars | Create car (auth required) |
| PUT | /api/cars/{id} | Update car (auth required) |
| DELETE | /api/cars/{id} | Delete car (auth required) |

## API Authentication

```bash
# Login to get token
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"seller1@usedcar.test","password":"password123"}'

# Use token for authenticated requests
curl -X GET http://localhost:8000/api/cars \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

## Security Considerations

1. **CSRF Protection** - All forms include CSRF token
2. **SQL Injection** - PDO prepared statements used everywhere
3. **XSS Prevention** - Output escaped with `htmlspecialchars()`
4. **Password Security** - `password_hash()` with bcrypt
5. **Rate Limiting** - IP-based request limiting
6. **File Upload** - MIME type validation, sanitized filenames
7. **Session Security** - Regenerated on login

### Additional Hardening Recommendations

- Enable HTTPS in production
- Set secure cookie flags
- Add Content Security Policy headers
- Implement 2FA for admin accounts
- Regular security audits
- Keep dependencies updated

## Running Tests

```bash
# Run all tests
./vendor/bin/phpunit

# Run specific test
./vendor/bin/phpunit tests/Unit/CarTest.php
```

## License

MIT License

## Credits

Built with:
- PHP 8.1+
- Bootstrap 5
- PDO (MySQL)
- Composer
