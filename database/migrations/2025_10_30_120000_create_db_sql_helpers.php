<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations: create views, stored procedures, functions and triggers
     * aimed at improving POS and inventory performance and correctness.
     */
    public function up(): void
    {
        $sql = <<<'SQL'
-- ==================================================
-- Helper tables
-- ==================================================
CREATE TABLE IF NOT EXISTS `stock_audits` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `change_amount` INT NOT NULL,
  `reason` VARCHAR(191) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX (`product_id`)
);

CREATE TABLE IF NOT EXISTS `expenses_summary` (
  `month_key` VARCHAR(7) NOT NULL PRIMARY KEY, -- YYYY-MM
  `total_amount` BIGINT NOT NULL DEFAULT 0
);

-- ==================================================
-- Views (4)
-- ==================================================
-- 1) v_stock_levels: quick view of product stock and low-stock flag
CREATE OR REPLACE VIEW v_stock_levels AS
SELECT p.id AS product_id, p.name AS product_name, p.quantity, p.quantity_alert,
       (p.quantity <= COALESCE(p.quantity_alert, 0)) AS is_low_stock
FROM products p;

-- 2) v_daily_sales_summary: daily totals (amounts stored in cents)
CREATE OR REPLACE VIEW v_daily_sales_summary AS
SELECT DATE(o.order_date) AS sale_date,
       SUM(o.total) AS total_cents,
       COUNT(*) AS orders_count,
       SUM(o.total_products) AS items_sold
FROM `orders` o
GROUP BY DATE(o.order_date);

-- 3) v_monthly_expenses_summary: month -> total expenses (cents)
CREATE OR REPLACE VIEW v_monthly_expenses_summary AS
SELECT DATE_FORMAT(expense_date, '%Y-%m') AS month_key,
       SUM(amount) AS total_cents
FROM expenses
GROUP BY month_key;

-- 4) v_return_rates: per-product returns vs sold quantities
CREATE OR REPLACE VIEW v_return_rates AS
SELECT p.id AS product_id,
       p.name AS product_name,
       COALESCE(r.total_returns, 0) AS total_returns,
       COALESCE(s.total_sold, 0) AS total_sold,
       CASE WHEN COALESCE(s.total_sold,0) = 0 THEN 0
            ELSE ROUND( COALESCE(r.total_returns,0) / s.total_sold, 4)
       END AS return_rate
FROM products p
LEFT JOIN (
  SELECT product_id, SUM(quantity) AS total_returns
  FROM return_sale_items
  GROUP BY product_id
) r ON r.product_id = p.id
LEFT JOIN (
  SELECT od.product_id, SUM(od.quantity) AS total_sold
  FROM order_details od
  JOIN orders o ON od.order_id = o.id
  GROUP BY od.product_id
) s ON s.product_id = p.id;

-- 5) v_product_sales_30d: product sales in the last 30 days for quick leaderboards
CREATE OR REPLACE VIEW v_product_sales_30d AS
SELECT p.id AS product_id, p.name AS product_name, IFNULL(SUM(od.quantity),0) AS total_sold_30d
FROM products p
LEFT JOIN order_details od ON od.product_id = p.id
LEFT JOIN orders o ON od.order_id = o.id AND o.order_date >= (CURRENT_DATE - INTERVAL 30 DAY)
GROUP BY p.id;

-- ==================================================
-- Functions (4)
-- ==================================================
-- 1) fn_currency_to_cents(decimal)
DROP FUNCTION IF EXISTS fn_currency_to_cents;
CREATE FUNCTION fn_currency_to_cents(val DECIMAL(13,2))
RETURNS BIGINT DETERMINISTIC
RETURN ROUND(val * 100);

-- 2) fn_cents_to_currency(int)
DROP FUNCTION IF EXISTS fn_cents_to_currency;
CREATE FUNCTION fn_cents_to_currency(cents BIGINT)
RETURNS DECIMAL(13,2) DETERMINISTIC
RETURN (cents / 100.00);

-- 3) fn_product_stock(pid)
DROP FUNCTION IF EXISTS fn_product_stock;
CREATE FUNCTION fn_product_stock(pid BIGINT)
RETURNS INT DETERMINISTIC
RETURN (SELECT IFNULL(quantity,0) FROM products WHERE id = pid LIMIT 1);

-- 4) fn_compute_order_total(oid) -> total cents from order details
DROP FUNCTION IF EXISTS fn_compute_order_total;
CREATE FUNCTION fn_compute_order_total(oid BIGINT)
RETURNS BIGINT DETERMINISTIC
RETURN (
  SELECT IFNULL(SUM(total),0) FROM order_details WHERE order_id = oid
);

-- 5) fn_is_low_stock(pid) -> 1 if stock <= alert threshold, else 0
DROP FUNCTION IF EXISTS fn_is_low_stock;
CREATE FUNCTION fn_is_low_stock(pid BIGINT)
RETURNS TINYINT DETERMINISTIC
RETURN (
  SELECT IF((quantity <= COALESCE(quantity_alert,0)), 1, 0) FROM products WHERE id = pid LIMIT 1
);

-- ==================================================
-- Stored Procedures (4)
-- ==================================================
-- 1) sp_adjust_stock_after_return(IN return_id)
DROP PROCEDURE IF EXISTS sp_adjust_stock_after_return;
CREATE PROCEDURE sp_adjust_stock_after_return(IN p_return_id BIGINT)
BEGIN
  -- Add returned quantities back to products in a set-based update
  UPDATE products p
  JOIN (
    SELECT product_id, SUM(quantity) AS qty
    FROM return_sale_items
    WHERE return_sale_id = p_return_id
    GROUP BY product_id
  ) r ON r.product_id = p.id
  SET p.quantity = p.quantity + r.qty;

  -- Insert audit records
  INSERT INTO stock_audits (product_id, change_amount, reason, created_at)
  SELECT product_id, SUM(quantity) AS change_amount, CONCAT('Return #', p_return_id), NOW()
  FROM return_sale_items
  WHERE return_sale_id = p_return_id
  GROUP BY product_id;
END;

-- 2) sp_record_expense(type, amount_decimal, expense_date, notes)
DROP PROCEDURE IF EXISTS sp_record_expense;
CREATE PROCEDURE sp_record_expense(
  IN p_type VARCHAR(191),
  IN p_amount DECIMAL(13,2),
  IN p_expense_date DATE,
  IN p_notes TEXT
)
BEGIN
  INSERT INTO expenses (`type`, `amount`, `expense_date`, `notes`, `created_at`, `updated_at`)
  VALUES (p_type, ROUND(p_amount * 100), p_expense_date, p_notes, NOW(), NOW());

  -- Update summary table (month key)
  SET @m = DATE_FORMAT(p_expense_date, '%Y-%m');
  INSERT INTO expenses_summary (month_key, total_amount)
    VALUES (@m, ROUND(p_amount * 100))
    ON DUPLICATE KEY UPDATE total_amount = total_amount + ROUND(p_amount * 100);
END;

-- 3) sp_get_top_selling_products(start_date, end_date, limit)
DROP PROCEDURE IF EXISTS sp_get_top_selling_products;
CREATE PROCEDURE sp_get_top_selling_products(
  IN p_start DATE,
  IN p_end DATE,
  IN p_limit INT
)
BEGIN
  SELECT od.product_id, p.name AS product_name, SUM(od.quantity) AS total_sold
  FROM order_details od
  JOIN orders o ON od.order_id = o.id
  JOIN products p ON p.id = od.product_id
  WHERE o.order_date BETWEEN p_start AND p_end
  GROUP BY od.product_id
  ORDER BY total_sold DESC
  LIMIT p_limit;
END;

-- 4) sp_get_order_kpis_by_shop(shop_id)
DROP PROCEDURE IF EXISTS sp_get_order_kpis_by_shop;
CREATE PROCEDURE sp_get_order_kpis_by_shop(IN p_shop_id BIGINT)
BEGIN
  SELECT COUNT(*) AS total_orders,
         IFNULL(SUM(total),0) AS total_amount,
         SUM(CASE WHEN order_status = 1 THEN 1 ELSE 0 END) AS completed_count,
         SUM(CASE WHEN order_status = 0 THEN 1 ELSE 0 END) AS pending_count,
         SUM(CASE WHEN order_status = 2 THEN 1 ELSE 0 END) AS cancelled_count,
         NOW() AS updated_at
  FROM orders
  WHERE shop_id = p_shop_id;
END;

-- 5) sp_get_return_kpis_by_shop(shop_id)
DROP PROCEDURE IF EXISTS sp_get_return_kpis_by_shop;
CREATE PROCEDURE sp_get_return_kpis_by_shop(IN p_shop_id BIGINT)
BEGIN
  SELECT IFNULL(SUM(rs.total),0) AS total_returns,
         IFNULL(SUM(CASE WHEN rs.return_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN rs.total ELSE 0 END),0) AS last_30_days_total,
         IFNULL(SUM(rsi.quantity),0) AS items_returned
  FROM return_sales rs
  LEFT JOIN return_sale_items rsi ON rsi.return_sale_id = rs.id
  WHERE rs.shop_id = p_shop_id;
END;

-- 6) sp_get_expense_kpis_by_shop(shop_id)
DROP PROCEDURE IF EXISTS sp_get_expense_kpis_by_shop;
CREATE PROCEDURE sp_get_expense_kpis_by_shop(IN p_shop_id BIGINT)
BEGIN
  SELECT IFNULL(SUM(amount),0) AS total_expenses,
         IFNULL(SUM(CASE WHEN expense_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN amount ELSE 0 END),0) AS last_30_days_expenses,
         COUNT(DISTINCT type) AS types_count
  FROM expenses
  WHERE shop_id = p_shop_id;
END;

-- 4) sp_resolve_order_totals(order_id) -> recalculates and updates order.total and sub_total from details
DROP PROCEDURE IF EXISTS sp_resolve_order_totals;
CREATE PROCEDURE sp_resolve_order_totals(IN p_order_id BIGINT)
BEGIN
  DECLARE v_sub BIGINT DEFAULT 0;
  SELECT IFNULL(SUM(total),0) INTO v_sub FROM order_details WHERE order_id = p_order_id;
  UPDATE orders SET sub_total = v_sub, total = v_sub WHERE id = p_order_id;
END;

-- 5) sp_rebuild_expenses_summary() -> recompute expenses_summary from scratch
DROP PROCEDURE IF EXISTS sp_rebuild_expenses_summary;
CREATE PROCEDURE sp_rebuild_expenses_summary()
BEGIN
  DELETE FROM expenses_summary;
  INSERT INTO expenses_summary (month_key, total_amount)
    SELECT DATE_FORMAT(expense_date, '%Y-%m') AS month_key, SUM(amount) AS total_amount
    FROM expenses
    GROUP BY month_key;
END;

-- 6) sp_adjust_stock_after_order(order_id)
-- Performs a set-based update to decrement product quantities according to
-- order_details for the given order, and inserts corresponding stock_audits.
DROP PROCEDURE IF EXISTS sp_adjust_stock_after_order;
CREATE PROCEDURE sp_adjust_stock_after_order(IN p_order_id BIGINT)
BEGIN
  -- update product quantities in a set-based manner
  UPDATE products p
  JOIN (
    SELECT product_id, SUM(quantity) AS qty
    FROM order_details
    WHERE order_id = p_order_id
    GROUP BY product_id
  ) od ON od.product_id = p.id
  SET p.quantity = p.quantity - od.qty;

  -- insert the aggregated audit records
  INSERT INTO stock_audits (product_id, change_amount, reason, created_at)
  SELECT product_id, -SUM(quantity) AS change_amount, CONCAT('Order #', p_order_id), NOW()
  FROM order_details
  WHERE order_id = p_order_id
  GROUP BY product_id;
END;

-- ==================================================
-- Triggers (4)
-- ==================================================
-- 1) trg_order_details_after_insert: decrement product stock and audit
DROP TRIGGER IF EXISTS trg_order_details_after_insert;
CREATE TRIGGER trg_order_details_after_insert
AFTER INSERT ON order_details
FOR EACH ROW
BEGIN
  -- only insert audit record here; product quantity will be adjusted by
  -- sp_adjust_stock_after_order(order_id) to avoid table-use conflicts
  INSERT INTO stock_audits (product_id, change_amount, reason, created_at)
    VALUES (NEW.product_id, -NEW.quantity, CONCAT('Order #', NEW.order_id), NOW());
END;

-- 5) trg_order_details_after_delete: when an order detail is deleted, restore product stock
DROP TRIGGER IF EXISTS trg_order_details_after_delete;
CREATE TRIGGER trg_order_details_after_delete
AFTER DELETE ON order_details
FOR EACH ROW
BEGIN
  -- record the audit for the deleted detail; actual product quantity
  -- restoration should be applied via sp_adjust_stock_after_order(order_id)
  INSERT INTO stock_audits (product_id, change_amount, reason, created_at)
    VALUES (OLD.product_id, OLD.quantity, CONCAT('Order detail deleted from Order #', OLD.order_id), NOW());
END;

-- ==================================================
-- Orders KPI cache, view, functions, procedure and triggers
-- (used by dashboard KPI cards: Total Orders, Completed, Pending, Cancelled)
-- ==================================================

CREATE TABLE IF NOT EXISTS orders_summary_cache (
  id TINYINT NOT NULL PRIMARY KEY DEFAULT 1,
  total_orders BIGINT NOT NULL DEFAULT 0,
  total_amount BIGINT NOT NULL DEFAULT 0,
  completed_count BIGINT NOT NULL DEFAULT 0,
  pending_count BIGINT NOT NULL DEFAULT 0,
  cancelled_count BIGINT NOT NULL DEFAULT 0,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- initialize singleton row
INSERT IGNORE INTO orders_summary_cache (id, total_orders, total_amount, completed_count, pending_count, cancelled_count)
  SELECT 1, COUNT(*), IFNULL(SUM(total),0),
         SUM(CASE WHEN order_status = 1 THEN 1 ELSE 0 END),
         SUM(CASE WHEN order_status = 0 THEN 1 ELSE 0 END),
         SUM(CASE WHEN order_status = 2 THEN 1 ELSE 0 END)
  FROM orders;

-- View to read KPIs easily
CREATE OR REPLACE VIEW v_order_kpis AS
SELECT total_orders, total_amount, completed_count, pending_count, cancelled_count, updated_at
FROM orders_summary_cache WHERE id = 1;

-- Function to get one KPI value (example: total orders)
DROP FUNCTION IF EXISTS fn_total_orders;
CREATE FUNCTION fn_total_orders()
RETURNS BIGINT DETERMINISTIC
RETURN (SELECT total_orders FROM orders_summary_cache WHERE id = 1 LIMIT 1);

DROP FUNCTION IF EXISTS fn_total_orders_amount;
CREATE FUNCTION fn_total_orders_amount()
RETURNS BIGINT DETERMINISTIC
RETURN (SELECT total_amount FROM orders_summary_cache WHERE id = 1 LIMIT 1);

-- Stored procedure to return KPI row (useful for apps that prefer CALL)
DROP PROCEDURE IF EXISTS sp_get_order_kpis;
CREATE PROCEDURE sp_get_order_kpis()
BEGIN
  SELECT total_orders, total_amount, completed_count, pending_count, cancelled_count, updated_at
  FROM orders_summary_cache WHERE id = 1;
END;

-- Triggers that refresh the cache whenever orders table changes
DROP TRIGGER IF EXISTS trg_orders_after_insert;
CREATE TRIGGER trg_orders_after_insert
AFTER INSERT ON orders
FOR EACH ROW
BEGIN
  INSERT INTO orders_summary_cache (id, total_orders, total_amount, completed_count, pending_count, cancelled_count, updated_at)
    SELECT 1, COUNT(*), IFNULL(SUM(total),0),
           SUM(CASE WHEN order_status = 1 THEN 1 ELSE 0 END),
           SUM(CASE WHEN order_status = 0 THEN 1 ELSE 0 END),
           SUM(CASE WHEN order_status = 2 THEN 1 ELSE 0 END), NOW()
    FROM orders
    ON DUPLICATE KEY UPDATE
      total_orders = VALUES(total_orders),
      total_amount = VALUES(total_amount),
      completed_count = VALUES(completed_count),
      pending_count = VALUES(pending_count),
      cancelled_count = VALUES(cancelled_count),
      updated_at = NOW();
END;

DROP TRIGGER IF EXISTS trg_orders_after_update;
CREATE TRIGGER trg_orders_after_update
AFTER UPDATE ON orders
FOR EACH ROW
BEGIN
  -- recompute full counts (keeps correctness if status changes)
  INSERT INTO orders_summary_cache (id, total_orders, total_amount, completed_count, pending_count, cancelled_count, updated_at)
    SELECT 1, COUNT(*), IFNULL(SUM(total),0),
           SUM(CASE WHEN order_status = 1 THEN 1 ELSE 0 END),
           SUM(CASE WHEN order_status = 0 THEN 1 ELSE 0 END),
           SUM(CASE WHEN order_status = 2 THEN 1 ELSE 0 END), NOW()
    FROM orders
    ON DUPLICATE KEY UPDATE
      total_orders = VALUES(total_orders),
      total_amount = VALUES(total_amount),
      completed_count = VALUES(completed_count),
      pending_count = VALUES(pending_count),
      cancelled_count = VALUES(cancelled_count),
      updated_at = NOW();
END;

DROP TRIGGER IF EXISTS trg_orders_after_delete;
CREATE TRIGGER trg_orders_after_delete
AFTER DELETE ON orders
FOR EACH ROW
BEGIN
  INSERT INTO orders_summary_cache (id, total_orders, total_amount, completed_count, pending_count, cancelled_count, updated_at)
    SELECT 1, COUNT(*), IFNULL(SUM(total),0),
           SUM(CASE WHEN order_status = 1 THEN 1 ELSE 0 END),
           SUM(CASE WHEN order_status = 0 THEN 1 ELSE 0 END),
           SUM(CASE WHEN order_status = 2 THEN 1 ELSE 0 END), NOW()
    FROM orders
    ON DUPLICATE KEY UPDATE
      total_orders = VALUES(total_orders),
      total_amount = VALUES(total_amount),
      completed_count = VALUES(completed_count),
      pending_count = VALUES(pending_count),
      cancelled_count = VALUES(cancelled_count),
      updated_at = NOW();
END;

-- 2) trg_return_sale_items_after_insert: restore stock and audit
DROP TRIGGER IF EXISTS trg_return_sale_items_after_insert;
CREATE TRIGGER trg_return_sale_items_after_insert
AFTER INSERT ON return_sale_items
FOR EACH ROW
BEGIN
  -- record audit only; actual stock increase should be applied by
  -- calling sp_adjust_stock_after_return(p_return_id) to avoid trigger conflicts
  INSERT INTO stock_audits (product_id, change_amount, reason, created_at)
    VALUES (NEW.product_id, NEW.quantity, CONCAT('Return #', NEW.return_sale_id), NOW());
END;

-- 3) trg_expenses_after_insert: keep expenses_summary in sync
DROP TRIGGER IF EXISTS trg_expenses_after_insert;
CREATE TRIGGER trg_expenses_after_insert
AFTER INSERT ON expenses
FOR EACH ROW
BEGIN
  SET @mkey = DATE_FORMAT(NEW.expense_date, '%Y-%m');
  INSERT INTO expenses_summary (month_key, total_amount)
    VALUES (@mkey, NEW.amount)
    ON DUPLICATE KEY UPDATE total_amount = total_amount + NEW.amount;
END;

-- 4) trg_products_after_update: track quantity changes
DROP TRIGGER IF EXISTS trg_products_after_update;
CREATE TRIGGER trg_products_after_update
AFTER UPDATE ON products
FOR EACH ROW
BEGIN
  IF OLD.quantity <> NEW.quantity THEN
    INSERT INTO stock_audits (product_id, change_amount, reason, created_at)
      VALUES (NEW.id, NEW.quantity - OLD.quantity, CONCAT('Manual update or proc on product ', NEW.id), NOW());
  END IF;
END;

-- ==================================================
-- Customer helpers: audits, summary, view, functions and proc
-- ==================================================
CREATE TABLE IF NOT EXISTS customer_audits (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  customer_id BIGINT UNSIGNED NOT NULL,
  change_amount BIGINT NOT NULL,
  reason VARCHAR(191) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX (customer_id)
);

CREATE TABLE IF NOT EXISTS customer_summary (
  customer_id BIGINT UNSIGNED NOT NULL PRIMARY KEY,
  total_orders BIGINT NOT NULL DEFAULT 0,
  total_spent BIGINT NOT NULL DEFAULT 0,
  last_order_date DATETIME DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- view for customer stats
CREATE OR REPLACE VIEW v_customer_stats AS
SELECT c.id AS customer_id, c.name AS customer_name,
       COALESCE(cs.total_orders,0) AS total_orders,
       COALESCE(cs.total_spent,0) AS total_spent,
       cs.last_order_date
FROM customers c
LEFT JOIN customer_summary cs ON cs.customer_id = c.id;

-- function: total spent by customer
DROP FUNCTION IF EXISTS fn_customer_total_spent;
CREATE FUNCTION fn_customer_total_spent(cust_id BIGINT)
RETURNS BIGINT DETERMINISTIC
RETURN (SELECT IFNULL(SUM(total),0) FROM orders WHERE customer_id = cust_id);

-- procedure to rebuild customer_summary
DROP PROCEDURE IF EXISTS sp_rebuild_customer_summary;
CREATE PROCEDURE sp_rebuild_customer_summary()
BEGIN
  DELETE FROM customer_summary;
  INSERT INTO customer_summary (customer_id, total_orders, total_spent, last_order_date)
    SELECT o.customer_id, COUNT(*), IFNULL(SUM(o.total),0), MAX(o.order_date)
    FROM orders o
    GROUP BY o.customer_id;
END;

-- trigger: audit orders into customer_audits
DROP TRIGGER IF EXISTS trg_orders_customer_audit_after_insert;
CREATE TRIGGER trg_orders_customer_audit_after_insert
AFTER INSERT ON orders
FOR EACH ROW
BEGIN
  INSERT INTO customer_audits (customer_id, change_amount, reason, created_at)
    VALUES (NEW.customer_id, NEW.total, CONCAT('Order #', NEW.id), NOW());
END;

-- ==================================================
-- Product helpers: audits, metrics table, view, functions and proc
-- ==================================================
CREATE TABLE IF NOT EXISTS product_audits (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  product_id BIGINT UNSIGNED NOT NULL,
  change_amount BIGINT NOT NULL,
  reason VARCHAR(191) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX (product_id)
);

CREATE TABLE IF NOT EXISTS product_metrics (
  product_id BIGINT UNSIGNED NOT NULL PRIMARY KEY,
  total_sold BIGINT NOT NULL DEFAULT 0,
  total_returns BIGINT NOT NULL DEFAULT 0,
  last_sold_date DATETIME DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE OR REPLACE VIEW v_product_metrics AS
SELECT p.id AS product_id, p.name AS product_name,
       COALESCE(pm.total_sold,0) AS total_sold,
       COALESCE(pm.total_returns,0) AS total_returns,
       p.quantity AS stock, p.quantity_alert,
       (p.quantity <= COALESCE(p.quantity_alert,0)) AS is_low_stock
FROM products p
LEFT JOIN product_metrics pm ON pm.product_id = p.id;

DROP FUNCTION IF EXISTS fn_product_total_sold;
CREATE FUNCTION fn_product_total_sold(pid BIGINT)
RETURNS BIGINT DETERMINISTIC
RETURN (SELECT IFNULL(SUM(quantity),0) FROM order_details WHERE product_id = pid);

DROP FUNCTION IF EXISTS fn_product_total_returns;
CREATE FUNCTION fn_product_total_returns(pid BIGINT)
RETURNS BIGINT DETERMINISTIC
RETURN (SELECT IFNULL(SUM(quantity),0) FROM return_sale_items WHERE product_id = pid);

DROP PROCEDURE IF EXISTS sp_rebuild_product_metrics;
CREATE PROCEDURE sp_rebuild_product_metrics()
BEGIN
  DELETE FROM product_metrics;
  INSERT INTO product_metrics (product_id, total_sold, total_returns, last_sold_date)
    SELECT p.id AS product_id,
           IFNULL(s.total_sold,0) AS total_sold,
           IFNULL(r.total_returns,0) AS total_returns,
           GREATEST(IFNULL(MAX(o.order_date), '1970-01-01'), IFNULL(MAX(rs.return_date), '1970-01-01')) AS last_sold_date
    FROM products p
    LEFT JOIN (
      SELECT od.product_id, SUM(od.quantity) AS total_sold, MAX(o.order_date) AS order_date
      FROM order_details od
      JOIN orders o ON od.order_id = o.id
      GROUP BY od.product_id
    ) s ON s.product_id = p.id
    LEFT JOIN (
      SELECT rsi.product_id, SUM(rsi.quantity) AS total_returns, MAX(rs.return_date) AS return_date
      FROM return_sale_items rsi
      JOIN return_sales rs ON rsi.return_sale_id = rs.id
      GROUP BY rsi.product_id
    ) r ON r.product_id = p.id
    GROUP BY p.id;
END;

-- triggers for product audits from order_details and return items
DROP TRIGGER IF EXISTS trg_order_details_product_audit_after_insert;
CREATE TRIGGER trg_order_details_product_audit_after_insert
AFTER INSERT ON order_details
FOR EACH ROW
BEGIN
  INSERT INTO product_audits (product_id, change_amount, reason, created_at)
    VALUES (NEW.product_id, -NEW.quantity, CONCAT('Order #', NEW.order_id), NOW());
END;

DROP TRIGGER IF EXISTS trg_return_sale_items_product_audit_after_insert;
CREATE TRIGGER trg_return_sale_items_product_audit_after_insert
AFTER INSERT ON return_sale_items
FOR EACH ROW
BEGIN
  INSERT INTO product_audits (product_id, change_amount, reason, created_at)
    VALUES (NEW.product_id, NEW.quantity, CONCAT('Return #', NEW.return_sale_id), NOW());
END;

-- ==================================================
-- Credit sales helpers: summary table, view, functions and proc
-- ==================================================
CREATE TABLE IF NOT EXISTS credit_summary (
  customer_id BIGINT UNSIGNED NOT NULL PRIMARY KEY,
  total_credit BIGINT NOT NULL DEFAULT 0,
  outstanding_amount BIGINT NOT NULL DEFAULT 0,
  overdue_count BIGINT NOT NULL DEFAULT 0,
  last_payment_date DATETIME DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE OR REPLACE VIEW v_credit_sales_summary AS
SELECT c.id AS customer_id, c.name AS customer_name,
       COALESCE(cs.total_credit,0) AS total_credit,
       COALESCE(cs.outstanding_amount,0) AS outstanding_amount,
       COALESCE(cs.overdue_count,0) AS overdue_count,
       cs.last_payment_date
FROM customers c
LEFT JOIN credit_summary cs ON cs.customer_id = c.id;

DROP FUNCTION IF EXISTS fn_customer_credit_total;
CREATE FUNCTION fn_customer_credit_total(cust_id BIGINT)
RETURNS BIGINT DETERMINISTIC
RETURN (SELECT IFNULL(SUM(total),0) FROM credit_sales WHERE customer_id = cust_id);

DROP PROCEDURE IF EXISTS sp_rebuild_credit_summary;
CREATE PROCEDURE sp_rebuild_credit_summary()
BEGIN
  DELETE FROM credit_summary;
  INSERT INTO credit_summary (customer_id, total_credit, outstanding_amount, overdue_count, last_payment_date)
    SELECT cs.customer_id,
           IFNULL(SUM(cs.total),0) AS total_credit,
           IFNULL(SUM(cs.outstanding),0) AS outstanding_amount,
           IFNULL(SUM(CASE WHEN cs.due_date < CURDATE() AND cs.outstanding > 0 THEN 1 ELSE 0 END),0) AS overdue_count,
           IFNULL(MAX(cs.updated_at), NULL) AS last_payment_date
    FROM credit_sales cs
    GROUP BY cs.customer_id;
END;

-- trigger to audit credit payments (if credit_payments table exists)
DROP TABLE IF EXISTS credit_payments_audit_temp;
CREATE TABLE IF NOT EXISTS credit_audits (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  credit_id BIGINT UNSIGNED DEFAULT NULL,
  customer_id BIGINT UNSIGNED DEFAULT NULL,
  amount BIGINT DEFAULT 0,
  reason VARCHAR(191) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX (customer_id)
);

-- It's safer to only insert audit rows here; heavy rebuilding should be done by calling sp_rebuild_credit_summary manually or via a cron/job.
DROP TRIGGER IF EXISTS trg_credit_payments_after_insert;
CREATE TRIGGER trg_credit_payments_after_insert
AFTER INSERT ON credit_payments
FOR EACH ROW
BEGIN
  -- credit_payments uses `credit_sale_id` and `payment_amount`;
  -- resolve customer_id from credit_sales for audit entry.
  INSERT INTO credit_audits (credit_id, customer_id, amount, reason, created_at)
    VALUES (
      NEW.credit_sale_id,
      (SELECT customer_id FROM credit_sales WHERE id = NEW.credit_sale_id LIMIT 1),
      NEW.payment_amount,
      CONCAT('Payment for credit ', NEW.credit_sale_id),
      NOW()
    );
END;

SQL;

  // Temporarily disable ONLY_FULL_GROUP_BY for this session to allow
  // legacy-style GROUP BY queries in views/procedures to be created.
  DB::unprepared("SET @OLD_SQL_MODE = @@SESSION.sql_mode;");
  DB::unprepared("SET SESSION sql_mode = REPLACE(@@SESSION.sql_mode, 'ONLY_FULL_GROUP_BY', '');");
  DB::unprepared($sql);
  // restore previous sql_mode
  DB::unprepared("SET SESSION sql_mode = @OLD_SQL_MODE;");
    }

    public function down(): void
    {
        $drop = <<<'SQL'
-- Drop triggers
DROP TRIGGER IF EXISTS trg_products_after_update;
DROP TRIGGER IF EXISTS trg_expenses_after_insert;
DROP TRIGGER IF EXISTS trg_return_sale_items_after_insert;
DROP TRIGGER IF EXISTS trg_order_details_after_insert;
DROP TRIGGER IF EXISTS trg_order_details_after_delete;

-- Drop procedures
DROP PROCEDURE IF EXISTS sp_resolve_order_totals;
DROP PROCEDURE IF EXISTS sp_get_top_selling_products;
DROP PROCEDURE IF EXISTS sp_record_expense;
DROP PROCEDURE IF EXISTS sp_adjust_stock_after_return;
DROP PROCEDURE IF EXISTS sp_adjust_stock_after_order;

-- Drop functions
DROP FUNCTION IF EXISTS fn_compute_order_total;
DROP FUNCTION IF EXISTS fn_product_stock;
DROP FUNCTION IF EXISTS fn_cents_to_currency;
DROP FUNCTION IF EXISTS fn_currency_to_cents;

-- Drop views
DROP VIEW IF EXISTS v_return_rates;
DROP VIEW IF EXISTS v_monthly_expenses_summary;
DROP VIEW IF EXISTS v_daily_sales_summary;
DROP VIEW IF EXISTS v_stock_levels;

-- Drop helper tables
DROP TABLE IF EXISTS expenses_summary;
DROP TABLE IF EXISTS stock_audits;
SQL;

        DB::unprepared($drop);
    }
};
