# Database Exports

This directory contains database exports for NexoraLabs application.

## Files

### 1. `nexoralabs_full_YYYY-MM-DD_HHMMSS.sql` (WITH DATA)
- **Size**: ~0.13 MB
- **Contents**: Complete database with all structure and data
- **Use Case**: 
  - Full backup with all existing data
  - Migration to another server with data
  - Development database with realistic data
- **Import**: 
  ```bash
  mysql -u root -p nexoralabs < nexoralabs_full_YYYY-MM-DD_HHMMSS.sql
  ```

### 2. `nexoralabs_schema_only_YYYY-MM-DD_HHMMSS.sql` (SCHEMA ONLY)
- **Size**: ~0.06 MB
- **Contents**: Database structure only (no data)
- **Use Case**: 
  - Fresh installation setup
  - Development environment without sample data
  - Template for new instances
  - Schema documentation
- **Import**: 
  ```bash
  mysql -u root -p nexoralabs < nexoralabs_schema_only_YYYY-MM-DD_HHMMSS.sql
  ```

## How to Use

### Import Full Database (WITH DATA)
```bash
# Create database first
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS nexoralabs;"

# Import
mysql -u root -p nexoralabs < nexoralabs_full_2026-01-16_210557.sql
```

### Import Schema Only (WITHOUT DATA)
```bash
# Create database first
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS nexoralabs_new;"

# Import
mysql -u root -p nexoralabs_new < nexoralabs_schema_only_2026-01-16_210601.sql
```

### Using Laravel Artisan
If you prefer using Laravel:
```bash
# Clear current database
php artisan migrate:fresh

# Seed with seeder files (if available)
php artisan db:seed
```

## Tables Included

The exports include all tables:
- users
- shops
- categories
- products
- customers
- orders
- order_details
- payments
- credit_sales
- credit_payments
- return_sales
- return_sale_items
- units
- warranties
- expenses
- jobs
- job_types
- subscription_plans
- shop_subscriptions
- And more...

## Database Credentials

Default credentials (for local development):
- **Host**: 127.0.0.1 or localhost
- **Port**: 3306
- **User**: root
- **Password**: (empty)
- **Database**: nexoralabs

## Restore/Import Steps

### Option 1: Command Line
```bash
# With password prompt
mysql -u root -p < nexoralabs_full_2026-01-16_210557.sql

# Without password (if no password set)
mysql -u root < nexoralabs_full_2026-01-16_210557.sql

# Specify database
mysql -u root nexoralabs < nexoralabs_full_2026-01-16_210557.sql
```

### Option 2: Using MySQL Workbench
1. Open MySQL Workbench
2. Connect to your MySQL server
3. Go to **File** → **Open SQL Script**
4. Select the SQL file
5. Execute (Cmd+Enter or Ctrl+Enter)

### Option 3: Using phpMyAdmin
1. Log in to phpMyAdmin
2. Click on **Import** tab
3. Select the SQL file
4. Click **Go**

## Backup Best Practices

1. **Regular Backups**: Create backups periodically
2. **Version Control**: Keep dated backups
3. **Test Restores**: Verify backups work before relying on them
4. **Offsite Storage**: Keep copies in secure locations
5. **Document Changes**: Note what data was in each backup

## Export New Database

To create fresh exports anytime:

```bash
# Full export with data
mysqldump -u root -p nexoralabs > nexoralabs_full_$(date +%Y-%m-%d_%H%M%S).sql

# Schema only
mysqldump -u root -p --no-data nexoralabs > nexoralabs_schema_$(date +%Y-%m-%d_%H%M%S).sql
```

## File Locations

All exports are stored in: `database_exports/`

## Support

For issues or questions regarding database:
- Check Laravel logs: `storage/logs/laravel.log`
- Check MySQL error logs
- Review environment configuration: `.env`

---

**Created**: January 16, 2026  
**Application**: NexoraLabs  
**Database**: nexoralabs (MySQL)
