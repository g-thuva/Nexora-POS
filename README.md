# NexoraLabs

A Laravel-based multi-user inventory and POS system with reports, KPIs, and DB-side helpers (views, functions, stored procedures, triggers) to improve read performance for reporting pages.

This repository contains the application, database migrations, and helper utilities used during development. The project includes recently added finance reports for Returns, Expenses and Credit Sales, plus customer/product credit summaries.

---

## Quick highlights
- Laravel (PHP) application with Blade views, Artisan commands and scheduled tasks
- MySQL-compatible DB artifacts (views, functions, stored procedures and lightweight triggers) used to speed up heavy read operations
- Finance reports: Returns, Expenses, Credit Sales, Customer Credit Summary, Product Credit Summary
- KPI surface: server-computed small KPIs exposed via a view composer (nav badges) to avoid per-page heavy queries

---

## Getting started (developer)

Prerequisites
- PHP 8.x
- Composer
- Node.js (for frontend assets)
- MySQL (or Docker setup below)

1. Clone the repo

```bash
git clone <repo-url> NexoraLabs
cd NexoraLabs
```

2. Install dependencies

```bash
composer install
npm install
```

3. Environment

Copy .env and configure DB credentials

```bash
cp .env.example .env
php artisan key:generate
# edit .env to point to your MySQL instance
```

4. Run migrations (and optional seeders)

If you want a fresh DB for development:

```bash
php artisan migrate --seed
```

Notes:
- The project includes several migrations that create DB views, stored procedures, functions and triggers. Those are idempotent and safe to run on a dev database but require the DB user to have privileges to create routines and triggers.

5. Build assets and run the server

```bash
npm run dev
php artisan serve
```

Visit http://127.0.0.1:8000 and sign in with seeded admin credentials (if seeder used).

---

## New finance/reporting features (developer overview)

What was added recently:
- `v_credit_sales_summary`, `v_customer_credit_summary`, `v_product_credit_summary` (DB views)
- `sp_get_credit_sales_report`, `sp_rebuild_credit_sales_summary` (stored procedures)
- `fn_customer_total_credit`, `fn_product_credit_total` (helper functions)
- `trg_credit_sales_after_insert` (lightweight audit trigger)

These are created in migration `database/migrations/2025_10_31_000003_add_credit_customer_product_helpers.php` and are designed to work with the existing schema (credit sales are linked to orders; product aggregates use order_details when credit_sale_items do not exist).

Application wiring:
- Pages and APIs implemented in `app/Http/Controllers/FinanceReportController.php`
- Blade views under `resources/views/reports/finance/` (credit_sales, customers, products, returns, expenses)
- Routes registered under `routes/web.php` in the `reports.sales.finance.*` group
- `App\\Providers\\AppServiceProvider` composes a small set of KPIs into the navbar using caching to avoid repeated heavy DB calls

There is an artisan command `nexora:rebuild-summaries` (in `app/Console/Commands`) that invokes the heavier rebuild stored procedures — scheduled in `app/Console/Kernel.php` and usable for manual refresh.

---

## Testing & verification

- Quick syntax checks: `php -l` on PHP files you edit
- Run migrations locally and then run the rebuild command to exercise stored procedures:

```bash
php artisan migrate
php artisan nexora:rebuild-summaries
```

- The read-only report pages can be exercised in a browser (Reports -> Finance -> Credit Sales / Customers / Products).

### Notes about test environments
- The repository includes a PHPUnit test harness; some tests touching DB helpers assume a MySQL-backed test DB. If CI uses SQLite by default, those tests will skip or should be run against a MySQL instance.

---

## Troubleshooting

- If migrations fail due to missing privileges to create functions/procedures/triggers, run migrations on a DB user with appropriate privileges or comment out routine creation temporarily during local iteration.
- If a view or stored procedure references a column that doesn't exist in your schema, check the project's SQL dump in `DB/` for the authoritative schema used during development.

---

## Contributing

Contributions are welcome. Preferred workflow:

1. Create a topic branch
2. Add tests for new behavior where appropriate
3. Open a pull request with a focused change

---

## License

MIT

---

If you'd like, I can also:
- Add a short development checklist for setting up a MySQL-backed PHPUnit environment
- Add an `ADMIN.md` describing admin-only operations (manual rebuild, tailoring migrations for production)

---

Happy hacking — you can ask me to add targeted README sections if you'd like more detail in any area.
# 🚀 NexoraLabs Multi-User Inventory System

A complete Laravel-based inventory management system with **multi-user support**, **role-based access control**, and comprehensive business features. Perfect for testing multi-tenant architecture!

## 🌟 What's New - Multi-User Features
- ✅ **Complete Data Isolation**: Each user sees only their own data
- ✅ **Role-Based Access**: Admin vs Regular user permissions  
- ✅ **Composite Unique Constraints**: Users can have same product names/emails
- ✅ **Automatic User Scoping**: No cross-user data leakage
- ✅ **Admin-Only User Management**: Secure user administration


### Test Accounts Ready
- **Admin:** admin@admin.com / password (can manage users)


## 🗂️ Database Design

The system is structured using a clear and efficient database schema:

## 🌟 Key Features

-   **POS (Point of Sale)**
-   **Orders**
    -   Pending Orders
    -   Complete Orders
    -   Pending Payments
-   **Products Management**
-   **Customer Records**
-   **Supplier Management**

## 🚀 Quick Start

Follow these steps to set up the project locally:

1. **Clone the repository:**

    ```bash
    git clone https://github.com/kirupan10/nexora
    ```

2. **Navigate to the project folder:**

    ```bash
    cd nexora
    ```

3. **Install PHP dependencies:**

    ```bash
    composer install
    ```

4. **Copy `.env` configuration:**

    ```bash
    cp .env.example .env
    ```

5. **Generate application key:**

    ```bash
    php artisan key:generate
    ```

6. **Configure the database in the `.env` file** with your local credentials.

7. **Run database migrations and seed sample data:**

    ```bash
    php artisan migrate:fresh --seed
    ```

8. **Link storage for media files:**

    ```bash
    php artisan storage:link
    ```

9. **Install JavaScript and CSS dependencies:**

    ```bash
    npm install && npm run dev
    ```

10. **Start the Laravel development server:**

    ```bash
    php artisan serve
    ```

11. **Login using the default admin credentials:**

    - **Email:** `admin@admin.com`
    - **Password:** `123456`


## 💡 Contributing

Have ideas to improve the system? Feel free to:

-   Submit a **Pull Request (PR)**
-   Create an **Issue** for feature requests or bugs

## 📄 License

Licensed under the [MIT License](LICENSE).
