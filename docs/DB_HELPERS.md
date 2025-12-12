# Database Helpers, Views, Functions, Stored Procedures and Triggers

This document describes the database-side helpers introduced to improve performance and correctness in NexoraLabs: which views, SQL functions, stored procedures and triggers were created, where they are used in the application, how they are implemented, and why we use them.

> Location of implementation
- Migration with all DB objects: `database/migrations/2025_10_30_120000_create_db_sql_helpers.php`

## Summary of DB objects added

### Views
- `v_stock_levels` — quick projection of product id, name, quantity, quantity_alert and `is_low_stock` flag.
- `v_daily_sales_summary` — aggregated daily sales totals (total cents, orders_count, items_sold).
- `v_monthly_expenses_summary` — month-keyed total expenses.
- `v_return_rates` — per-product returns vs sold quantities and return rate.
- `v_product_sales_30d` — top product sales in last 30 days.
- `v_order_kpis` — singleton view reading the `orders_summary_cache` row for dashboard KPIs.

Why views: they centralize SQL aggregation and make reads extremely cheap for dashboard cards and list pages without repeating GROUP BY logic in application code.

### Functions (SQL functions)
- `fn_currency_to_cents(val)` and `fn_cents_to_currency(cents)` — canonical currency <-> cents helpers used by procedures and queries.
- `fn_product_stock(pid)` — returns current product stock.
- `fn_compute_order_total(oid)` — sums order detail totals.
- `fn_is_low_stock(pid)` — boolean-like function for low stock checks.
- `fn_total_orders()` / `fn_total_orders_amount()` — return single KPI values from orders summary cache.

Why functions: small, deterministic helpers let SQL use shared logic in views and procedures and keep the calculation logic close to the data.

### Stored Procedures
Group of set-based, atomic procedures (examples):
- `sp_adjust_stock_after_order(order_id)` — set-based update to decrement product quantities using aggregated `order_details` and insert `stock_audits` for the whole order.
- `sp_adjust_stock_after_return(return_id)` — set-based update to increment stock from `return_sale_items` and insert audits.
- `sp_record_expense(type, amount_decimal, expense_date, notes)` — insert expense (amount stored in cents) + update `expenses_summary`.
- `sp_get_top_selling_products(start_date, end_date, limit)` — returns leaderboard rows.
- `sp_get_order_kpis` / `sp_get_order_kpis_by_shop(shop_id)` — return aggregated KPI row(s) for dashboards / per-shop cards.
- `sp_get_return_kpis_by_shop(shop_id)` and `sp_get_expense_kpis_by_shop(shop_id)` — shop-specific KPI procedures for returns/expenses.
- `sp_resolve_order_totals(order_id)` — recompute and set order totals from details.
- `sp_rebuild_expenses_summary()` — rebuild `expenses_summary` from scratch.

Why procedures: heavy, write-side work (updates/inserts that affect many rows) should be done set-based in SQL — stored procedures provide atomic, fast operations and keep business-critical update logic centralized and DB-side for performance.

### Triggers
- Audit-only triggers (designed intentionally to avoid updating tables that the triggering statement uses):
  - `trg_order_details_after_insert` — inserts audit row for new order detail (product quantity update moved to `sp_adjust_stock_after_order`).
  - `trg_order_details_after_delete` — inserts audit row for deletions; full reconciliation applied through stored procedure.
  - `trg_return_sale_items_after_insert` — inserts audit row; actual stock increment must be applied by calling `sp_adjust_stock_after_return`.
  - `trg_expenses_after_insert` — updates `expenses_summary` incrementally.
  - `trg_products_after_update` — records manual product quantity changes to `stock_audits`.
  - `trg_orders_after_insert`, `trg_orders_after_update`, `trg_orders_after_delete` — refresh `orders_summary_cache` (singleton row) on changes to `orders`.

Why triggers: triggers provide immediate, fine-grained auditing of row-level changes. They are intentionally designed to be audit-only for write flows that need set-based updates (to avoid MySQL ERROR 1442: "trigger can't modify table being used by statement"). For set-based inventory updates we call stored procedures from the app after inserts, and rely on triggers for the audit trail and lightweight incremental summary updates where safe.


## How these are used in the application

### 1) KPI read flows (dashboard & shop pages)
- The application uses `App\Services\KpiService` (server-side service) to read values from views and stored procedures, e.g.:
  - `DB::select('SELECT * FROM v_order_kpis')` or `DB::select('CALL sp_get_order_kpis()')`.
  - `SELECT * FROM v_stock_levels WHERE is_low_stock = 1` to show low-stock lists.
- Blade templates and Livewire components now rely on the KpiService and the `safe_count()` helper to avoid iterating collections in views. Large aggregations are served by DB views/procs.

Benefits:
- Dashboard cards are simple DB reads (cheap), often selecting from `v_order_kpis` or `orders_summary_cache`.
- Per-shop KPIs are obtained via `sp_get_order_kpis_by_shop(shop_id)` which runs fast set-based aggregates.

### 2) Write flows (orders, returns, expenses)
- Pattern used in controllers / Livewire components:
  1. Perform the base DML (insert order row and associated `order_details` via bulk insert).
  2. Immediately call the corresponding stored procedure to apply set-based updates and audits, e.g.:
     ```php
     DB::statement('CALL sp_adjust_stock_after_order(?)', [$order->id]);
     ```
  3. Optionally call `sp_resolve_order_totals($orderId)` or `sp_rebuild_expenses_summary()` when fixing historical data.

- For returns: insert `return_sales` and `return_sale_items`, then call `sp_adjust_stock_after_return(return_id)`.
- For expenses: call `sp_record_expense(type, amount, date, notes)` to insert expense and update the `expenses_summary` table.

Why this pattern:
- Triggers were converted to audit-only where necessary to avoid trigger-table conflicts (MySQL ERROR 1442). Doing set-based updates in stored procedures avoids row-by-row operations and is much faster and safer for bulk changes.
- Stored procs keep update logic close to the data and reduce application complexity; they run inside the database and are optimized for set operations.

### 3) Audit & Historical Data
- `stock_audits` table stores changes to product quantities from orders, returns, and manual updates.
- Triggers write audit records on row modifications; procedures insert aggregated audit entries (one row per product per order/return) to keep the audit table compact and meaningful.


## Implementation notes and important considerations

- Migration file: `database/migrations/2025_10_30_120000_create_db_sql_helpers.php` runs DB::unprepared() to create views, functions, procedures and triggers. This file must be executed on the target database before expecting KPIs to be available.

- Backups: Always backup your DB before running the migration on production. Stored procedures and triggers can alter data semantics.

- Calling procs from Laravel:
  - Read proc that returns rows: `DB::select('CALL sp_get_top_selling_products(?, ?, ?)', [$start, $end, $limit])`
  - Write proc: `DB::statement('CALL sp_adjust_stock_after_order(?)', [$orderId])`

- Permissions: the DB user used by the app must have EXECUTE privileges for stored procedures and CREATE/ALTER privileges when migrating DB artifacts.

- Triggers vs Procedures: triggers are kept for auditing and safe incremental updates; heavy state changes are handled by stored procs called by the application. This design avoids MySQL's limitation where a trigger cannot modify certain tables safely when they are part of the triggering statement.

- Idempotency & Rebuilds: we included `sp_rebuild_expenses_summary()` and similar utilities so you can recompute summary tables if you need to repair or rebuild cache rows.

- Testing & Smoke tests:
  1. Run migrations so views/procs/triggers exist.
  2. Seed the admin user (already included in `database/seeders/AdminUserSeeder.php`).
  3. Create an order (web UI or tinker), run `CALL sp_adjust_stock_after_order(order_id)` and verify `products.quantity` and `stock_audits` updated.
  4. Create a return, call `CALL sp_adjust_stock_after_return(return_id)` and verify audit/stock updated.
  5. Call `CALL sp_record_expense(...)` and verify `expenses_summary` updated.


## Why this approach improves performance
- Offloading aggregation and set logic to the database reduces PHP memory/CPU usage. The DB is optimized for GROUP BY, JOINs and set-based updates, so KPI queries and bulk updates complete faster.
- Many dashboard widgets that previously performed collection-level PHP operations now read from optimized views or cached summary tables (`orders_summary_cache`) — this drastically reduces page render time.
- Stored procedures perform set-updates atomically and efficiently; they also centralize update rules so different app entrypoints (web, API, batch jobs) use the same logic.


## Where in code to look
- Migration: `database/migrations/2025_10_30_120000_create_db_sql_helpers.php`
- Seeder: `database/seeders/AdminUserSeeder.php`
- Helper (view-safe count): `app/helpers.php` (function `safe_count()`)
- Service that reads KPIs: `app/Services/KpiService.php` (used by controllers and Livewire components)
- Example controller patterns: search for `CALL sp_adjust_stock_after_order` or `DB::statement('CALL` in `app/Http/Controllers` and Livewire components.
- Views updated to use safe_count and KpiService: `resources/views/*` (multiple files; see recent commits or diffs).


## Next steps & recommendations
- Run integration smoke tests (order/return/expense flows) in a dev environment.
- Move any remaining direct DB COUNT queries to KpiService or a dedicated controller method if you want to centralize performance-sensitive queries.
- Add documentation for developers describing the write-flow pattern (bulk insert -> CALL stored procedure -> optional KPI refresh) so contributors follow the pattern.
- Consider monitoring slow queries (enable slow query log) and add indexes for fields used in the views/procedures if needed (e.g., `orders.order_date`, `order_details.product_id`).


If you'd like, I can also:
- Add example Laravel wrapper methods in `App\Services\KpiService` for calling common procedures and returning typed results.
- Add a small PHPUnit/feature test that runs the smoke test flows using the test database (fast, self-cleaning).

---
Generated on 2025-10-30
