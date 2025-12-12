<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $sql = <<<'SQL'
-- Customer helpers
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

CREATE OR REPLACE VIEW v_customer_stats AS
SELECT c.id AS customer_id, c.name AS customer_name,
       COALESCE(cs.total_orders,0) AS total_orders,
       COALESCE(cs.total_spent,0) AS total_spent,
       cs.last_order_date
FROM customers c
LEFT JOIN customer_summary cs ON cs.customer_id = c.id;

DROP FUNCTION IF EXISTS fn_customer_total_spent;
CREATE FUNCTION fn_customer_total_spent(cust_id BIGINT)
RETURNS BIGINT DETERMINISTIC
RETURN (SELECT IFNULL(SUM(total),0) FROM orders WHERE customer_id = cust_id);

DROP PROCEDURE IF EXISTS sp_rebuild_customer_summary;
CREATE PROCEDURE sp_rebuild_customer_summary()
BEGIN
  DELETE FROM customer_summary;
  INSERT INTO customer_summary (customer_id, total_orders, total_spent, last_order_date)
    SELECT o.customer_id, COUNT(*), IFNULL(SUM(o.total),0), MAX(o.order_date)
    FROM orders o
    GROUP BY o.customer_id;
END;

DROP TRIGGER IF EXISTS trg_orders_customer_audit_after_insert;
CREATE TRIGGER trg_orders_customer_audit_after_insert
AFTER INSERT ON orders
FOR EACH ROW
BEGIN
  INSERT INTO customer_audits (customer_id, change_amount, reason, created_at)
    VALUES (NEW.customer_id, NEW.total, CONCAT('Order #', NEW.id), NOW());
END;

-- Product helpers
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

-- Credit sales helpers
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

CREATE TABLE IF NOT EXISTS credit_audits (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  credit_id BIGINT UNSIGNED DEFAULT NULL,
  customer_id BIGINT UNSIGNED DEFAULT NULL,
  amount BIGINT DEFAULT 0,
  reason VARCHAR(191) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX (customer_id)
);

DROP TRIGGER IF EXISTS trg_credit_payments_after_insert;
CREATE TRIGGER trg_credit_payments_after_insert
AFTER INSERT ON credit_payments
FOR EACH ROW
BEGIN
  -- credit_payments table doesn't store customer_id directly; resolve via credit_sales
  INSERT INTO credit_audits (credit_id, customer_id, amount, reason, created_at)
    SELECT NEW.credit_sale_id, cs.customer_id, NEW.payment_amount, CONCAT('Payment for credit ', NEW.credit_sale_id), NOW()
    FROM credit_sales cs
    WHERE cs.id = NEW.credit_sale_id;
END;
SQL;

        DB::unprepared($sql);
    }

    public function down(): void
    {
        $drop = <<<'SQL'
DROP TRIGGER IF EXISTS trg_credit_payments_after_insert;
DROP TABLE IF EXISTS credit_audits;
DROP PROCEDURE IF EXISTS sp_rebuild_credit_summary;
DROP FUNCTION IF EXISTS fn_customer_credit_total;
DROP VIEW IF EXISTS v_credit_sales_summary;
DROP TABLE IF EXISTS credit_summary;

DROP TRIGGER IF EXISTS trg_return_sale_items_product_audit_after_insert;
DROP TRIGGER IF EXISTS trg_order_details_product_audit_after_insert;
DROP PROCEDURE IF EXISTS sp_rebuild_product_metrics;
DROP FUNCTION IF EXISTS fn_product_total_returns;
DROP FUNCTION IF EXISTS fn_product_total_sold;
DROP VIEW IF EXISTS v_product_metrics;
DROP TABLE IF EXISTS product_metrics;
DROP TABLE IF EXISTS product_audits;

DROP TRIGGER IF EXISTS trg_orders_customer_audit_after_insert;
DROP PROCEDURE IF EXISTS sp_rebuild_customer_summary;
DROP FUNCTION IF EXISTS fn_customer_total_spent;
DROP VIEW IF EXISTS v_customer_stats;
DROP TABLE IF EXISTS customer_summary;
DROP TABLE IF EXISTS customer_audits;
SQL;

        DB::unprepared($drop);
    }
};
