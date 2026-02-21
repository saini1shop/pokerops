# PokerOps Admin Application

PHP-based admin dashboard and landing page system for PokerOps.in

## Structure

```
├── config/                 # Configuration files
│   ├── app.php            # Application settings
│   └── database.php       # Database credentials
├── includes/              # Core PHP classes
│   ├── bootstrap.php      # Autoloader & initialization
│   ├── Database.php       # PDO connection
│   ├── Router.php         # Public page router
│   ├── AdminRouter.php    # Admin route handler
│   ├── Controllers/       # MVC Controllers
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   └── ...
│   └── Models/            # Data models
│       ├── User.php
│       └── Otp.php
├── views/                 # Templates
│   ├── admin/            # Admin UI
│   │   ├── auth/
│   │   ├── dashboard/
│   │   ├── layout.php
│   │   └── nav-links.php
│   └── public/           # Landing page templates
│       ├── blocks/       # Hero, form, offers, FAQ blocks
│       ├── landing-page.php
│       └── 404.php
├── public/               # Web root
│   ├── index.php        # Landing page router
│   ├── .htaccess        # URL rewrite rules
│   ├── admin/           # Admin entry
│   │   ├── index.php
│   │   └── .htaccess
│   └── api/             # API endpoints
│       └── landing/
│           └── signup.php
└── README.md
```

## Setup

### 1. Database
```bash
# Import schema
mysql -u pokerops_app -p pokerops < pokerops-schema-v0.3.sql

# Seed states
mysql -u pokerops_app -p pokerops < state_seed.sql
```

### 2. Web Server (Nginx)
```nginx
server {
    listen 80;
    server_name pokerops.in;
    root /var/www/pokerops.in/public;
    index index.php;

    # Admin routes
    location /admin {
        alias /var/www/pokerops.in/public/admin;
        try_files $uri $uri/ /admin/index.php?$query_string;
    }

    # Public landing pages
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### 3. Environment Variables (Production)
```bash
export DB_HOST=localhost
export DB_NAME=pokerops
export DB_USER=pokerops_app
export DB_PASS=your_secure_password
export APP_URL=https://pokerops.in
export APP_ENV=production
```

### 4. Permissions
```bash
chown -R www-data:www-data /var/www/pokerops.in
chmod 755 /var/www/pokerops.in/public/uploads
```

## Features

### Admin Panel (/admin)
- **OTP-only authentication** - No passwords, secure 6-digit codes
- **Dashboard** - Real-time stats: players, signups, check-ins, WhatsApp sent
- **Landing Pages** - Create/manage with block builder
- **Player CRM** - Search, view, manage player lifecycle
- **Campaigns** - Track attribution from Meta/Google ads
- **Venues & Check-ins** - Physical club operations
- **Tournaments** - Registration and management
- **Communities** - WhatsApp group tracking

### Public Pages (root)
- **Dynamic landing pages** - Slug-based routing (e.g., /punjab-welcome)
- **Block system** - Hero, form, offers, FAQ blocks
- **UTM tracking** - Automatic campaign attribution
- **Form submissions** → Player CRM + WhatsApp automation

## API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | /api/landing/signup | Landing page form submission |

## Security

- All passwords hashed (SHA-256 for OTPs)
- Prepared statements (PDO) - SQL injection protected
- Session-based auth with 8-hour timeout
- IP logging for consent evidence
- Input sanitization on all user data

## Next Steps

1. Configure WhatsApp provider (AiSensy/Interakt/Twilio)
2. Set up Meta Pixel + GTM tracking
3. Create default landing page templates
4. Add admin user to database
5. Test end-to-end flow: Landing page → Signup → Dashboard

## Development

Local PHP server:
```bash
cd public
php -S localhost:8000
```

Access admin at: `http://localhost:8000/admin`

---

Built with PHP 8.x, MySQL 8.0, Tailwind CSS
