# NexoraLabs POS System - Developer Setup Guide

## Prerequisites

### Required Software
- **PHP 8.2.12** or higher
- **MySQL 5.7** or higher (XAMPP recommended for Windows)
- **Composer** (PHP dependency manager)
- **Node.js & NPM** (for frontend assets)
- **ImageMagick 7.1.2-9** (for PDF preview generation)
- **Ghostscript 10.06.0** (required by ImageMagick for PDF processing)

## Installation Steps

### 1. Clone the Repository
```bash
git clone <repository-url>
cd NexoraLabs
```

### 2. Install PHP Dependencies
```bash
composer install
```

### 3. Install Node Dependencies
```bash
npm install
```

### 4. Environment Configuration

Copy the example environment file:
```bash
cp .env.example .env
```

Update the `.env` file with your database credentials:
```env
APP_NAME=NexoraLabs
APP_ENV=local
APP_KEY=base64:HYNGN/z6PkbRH5Fevy/c2kAkw9+ogW4gtFHG8NK5G0c=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nexoralabs
DB_USERNAME=root
DB_PASSWORD=
```

Generate application key if needed:
```bash
php artisan key:generate
```

### 5. Database Setup

Create the database:
```bash
# Using MySQL command line
mysql -u root -e "CREATE DATABASE nexoralabs CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

Run migrations:
```bash
php artisan migrate
```

*Optional: If you have a database backup:*
```bash
mysql -u root nexoralabs < DB/nexoralabs_export.sql
```

### 6. Storage Setup

Create symbolic link for storage:
```bash
php artisan storage:link
```

Ensure proper permissions:
```bash
# On Linux/Mac
chmod -R 775 storage bootstrap/cache
chmod -R 775 public/letterheads

# On Windows (run as Administrator in PowerShell)
icacls storage /grant Everyone:(OI)(CI)F /T
icacls bootstrap\cache /grant Everyone:(OI)(CI)F /T
icacls public\letterheads /grant Everyone:(OI)(CI)F /T
```

### 7. ImageMagick & Ghostscript Setup (Critical for PDF Features)

#### Windows Installation:

**ImageMagick:**
1. Download ImageMagick 7.1.2-9 Q16-HDRI x64 from: https://imagemagick.org/script/download.php
2. Run installer and check "Add to PATH" option
3. Verify installation:
```powershell
magick -version
```

**Ghostscript:**
1. Download Ghostscript 10.06.0 from: https://www.ghostscript.com/download/gsdnld.html
2. Install to default location: `C:\Program Files\gs\gs10.06.0`
3. Add to PATH: `C:\Program Files\gs\gs10.06.0\bin`
4. Verify installation:
```powershell
gswin64c -version
```

#### Linux Installation:
```bash
sudo apt-get update
sudo apt-get install imagemagick ghostscript
```

#### Mac Installation:
```bash
brew install imagemagick ghostscript
```

### 8. Build Frontend Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 9. Start Development Server

```bash
php artisan serve
```

Access the application at: `http://127.0.0.1:8000`

## Key Features Configuration

### Letterhead System
- Navigate to Settings > Letterhead Configuration
- Upload letterhead PDF or image
- Configure element positions using drag-and-drop canvas
- Test print to verify positioning

### Invoice Numbering
- Default format: `PREFIX00001` (5-digit padding, no hyphen)
- Configure prefix in shop settings
- Starting number can be set during configuration
- Examples: `INV00001`, `APFIN00090`

### PDF Generation
- Uses DomPDF for invoice generation
- Letterhead overlay support with ImageMagick preview
- Positioned elements system for custom layouts
- Warranty terms automatically included

## Database Structure

### Key Tables:
- `orders` - Sales orders with invoice numbers
- `order_details` - Line items for each order
- `payments` - Payment records
- `credit_sales` - Credit sale tracking
- `customers` - Customer information
- `products` - Product catalog
- `shops` - Multi-shop support

### Important Fields:
- All monetary values stored as integers (cents) in database
- Accessors convert to decimal for display
- `discount_amount` and `service_charges` in orders table
- `created_by` used instead of `user_id` for order tracking

## Common Commands

### Clear Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### Database Operations
```bash
# Fresh migration
php artisan migrate:fresh

# Seed database
php artisan db:seed

# Create backup
mysqldump -u root nexoralabs > backup.sql
```

### Queue Workers (if using)
```bash
php artisan queue:work
```

## Testing

Run tests:
```bash
php artisan test
```

## Troubleshooting

### PDF Preview Not Working
- Verify ImageMagick installed: `magick -version`
- Verify Ghostscript installed: `gswin64c -version` (Windows) or `gs -version` (Linux/Mac)
- Check PHP can execute ImageMagick: create test file `test_pdf_config.php`
- Ensure `letterheads/` directory is writable

### Invoice Numbers Not Generating
- Check `starting_number` in letterhead config
- Verify `invoice_no` format in OrderStoreRequest
- Clear orders table if testing: `TRUNCATE TABLE orders;`

### Discount/Service Charges Not Showing
- Ensure fields in Order model `$fillable` array
- Check database columns exist: `discount_amount`, `service_charges`
- Values stored as integers (cents), displayed as decimals

### Serial Number/Warranty Text Not Visible
- Check PDF generation view: `resources/views/orders/pdf-bill.blade.php`
- Color should be `#2c3e50` (dark blue-gray)
- Font weight should be `500` (medium)

## Development Guidelines

### Code Structure
- Controllers: `app/Http/Controllers/`
- Models: `app/Models/`
- Views: `resources/views/`
- Routes: `routes/web.php`
- Migrations: `database/migrations/`

### Naming Conventions
- Controllers: PascalCase with `Controller` suffix
- Models: Singular PascalCase
- Database tables: Plural snake_case
- Variables: camelCase
- Routes: kebab-case

### Best Practices
- Always use Eloquent ORM for database operations
- Use Request classes for validation
- Store monetary values as integers (cents)
- Use accessors/mutators for data transformation
- Log important operations
- Keep controllers thin, logic in services

## Production Deployment

1. Set environment to production:
```env
APP_ENV=production
APP_DEBUG=false
```

2. Optimize application:
```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

3. Build production assets:
```bash
npm run build
```

4. Set proper permissions:
```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

5. Configure web server (Apache/Nginx)
6. Set up SSL certificate
7. Configure scheduled tasks (cron):
```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

## Support & Documentation

- Technical Changelog: `TECHNICAL_CHANGELOG.md`
- PDF Features: `PDF_PRINT_COMPLETE.md`
- Performance Notes: `PERFORMANCE.md`
- Database Helpers: `docs/DB_HELPERS.md`

## Security Notes

- Never commit `.env` file
- Keep `APP_KEY` secret and unique
- Use strong database passwords
- Enable CSRF protection (default in Laravel)
- Validate all user inputs
- Use prepared statements (Eloquent handles this)
- Keep dependencies updated: `composer update`

## License & Credits

NexoraLabs POS System
Developed with Laravel 10.x
