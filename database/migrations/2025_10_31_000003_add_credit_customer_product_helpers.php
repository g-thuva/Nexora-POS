<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        // Create audit table for credit sales if not exists
        DB::unprepared(<<<'SQL'
        CREATE TABLE IF NOT EXISTS credit_sales_audit (
            id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
            credit_sale_id BIGINT NULL,
            shop_id BIGINT NULL,
            customer_id BIGINT NULL,
            total_cents BIGINT DEFAULT 0,
            due_cents BIGINT DEFAULT 0,
            created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        SQL
        );

        // v_credit_sales_summary: one row per credit sale with normalized columns
        DB::unprepared(<<<'SQL'
        DROP VIEW IF EXISTS v_credit_sales_summary;
        CREATE VIEW v_credit_sales_summary AS
        SELECT
            cs.id AS credit_sale_id,
            COALESCE(o.shop_id, NULL) AS shop_id,
            cs.customer_id,
            COALESCE(c.name, '') AS customer_name,
            COALESCE(cs.created_at, cs.sale_date, NOW()) AS sale_date,
            -- credit_sales stores amounts as integer cents in `total_amount` and `due_amount`
            COALESCE(cs.total_amount, 0) AS total_cents,
            COALESCE(cs.due_amount, 0) AS due_cents
        FROM credit_sales cs
        LEFT JOIN customers c ON c.id = cs.customer_id
        LEFT JOIN orders o ON o.id = cs.order_id;
        SQL
        );

        // v_customer_credit_summary: aggregated by customer
        DB::unprepared(<<<'SQL'
        DROP VIEW IF EXISTS v_customer_credit_summary;
        CREATE VIEW v_customer_credit_summary AS
        SELECT
            v.customer_id,
            v.customer_name,
            SUM(v.total_cents) AS total_credit_cents,
            SUM(v.due_cents) AS total_due_cents,
            MAX(v.sale_date) AS last_sale_date
        FROM v_credit_sales_summary v
        GROUP BY v.customer_id, v.customer_name;
        SQL
        );

        // v_product_credit_summary: aggregated by product (via credit_sale_items if available)
        DB::unprepared(<<<'SQL'
        DROP VIEW IF EXISTS v_product_credit_summary;
        CREATE VIEW v_product_credit_summary AS
        SELECT
            p.id AS product_id,
            p.name AS product_name,
            SUM(COALESCE(CASE WHEN cs.id IS NOT NULL THEN od.quantity ELSE 0 END, 0)) AS total_quantity_sold,
            SUM(COALESCE(CASE WHEN cs.id IS NOT NULL THEN od.total ELSE 0 END, 0)) AS total_amount
        FROM products p
        LEFT JOIN order_details od ON od.product_id = p.id
        LEFT JOIN orders o ON o.id = od.order_id
        LEFT JOIN credit_sales cs ON cs.order_id = o.id
        GROUP BY p.id, p.name;
        SQL
        );

        // helper function: customer total credit (returns cents)
        DB::unprepared(<<<'SQL'
        DROP FUNCTION IF EXISTS fn_customer_total_credit;
        CREATE FUNCTION fn_customer_total_credit(p_customer_id BIGINT) RETURNS BIGINT DETERMINISTIC
        BEGIN
            DECLARE v_total BIGINT DEFAULT 0;
            SELECT COALESCE(SUM(total_cents),0) INTO v_total FROM v_credit_sales_summary WHERE customer_id = p_customer_id;
            RETURN v_total;
        END;
        SQL
        );

        // helper function: product total sold (amount)
        DB::unprepared(<<<'SQL'
        DROP FUNCTION IF EXISTS fn_product_credit_total;
        CREATE FUNCTION fn_product_credit_total(p_product_id BIGINT) RETURNS DECIMAL(14,2) DETERMINISTIC
        BEGIN
            DECLARE v_total DECIMAL(14,2) DEFAULT 0;
            SELECT COALESCE(SUM(total_amount),0) INTO v_total FROM v_product_credit_summary WHERE product_id = p_product_id;
            RETURN v_total;
        END;
        SQL
        );

        // stored procedure: get credit sales report filtered by shop and date range
        DB::unprepared(<<<'SQL'
        DROP PROCEDURE IF EXISTS sp_get_credit_sales_report;
        CREATE PROCEDURE sp_get_credit_sales_report(
            IN p_shop_id BIGINT,
            IN p_start_date DATETIME,
            IN p_end_date DATETIME
        )
        BEGIN
            SELECT * FROM v_credit_sales_summary v
            WHERE (p_shop_id IS NULL OR v.shop_id = p_shop_id)
            AND (p_start_date IS NULL OR v.sale_date >= p_start_date)
            AND (p_end_date IS NULL OR v.sale_date <= p_end_date)
            ORDER BY v.sale_date DESC;
        END;
        SQL
        );

        // stored procedure: rebuild (simple validation/aggregation call) - safe and idempotent
        DB::unprepared(<<<'SQL'
        DROP PROCEDURE IF EXISTS sp_rebuild_credit_sales_summary;
        CREATE PROCEDURE sp_rebuild_credit_sales_summary()
        BEGIN
            -- This procedure intentionally performs a light aggregation to warm caches
            -- and can be extended later to populate summary tables if desired.
            SELECT customer_id, SUM(total_cents) AS total_credit_cents, SUM(due_cents) AS total_due_cents
            FROM v_credit_sales_summary
            GROUP BY customer_id;
        END;
        SQL
        );

        // Trigger: audit inserts on credit_sales
        DB::unprepared(<<<'SQL'
        DROP TRIGGER IF EXISTS trg_credit_sales_after_insert;
        CREATE TRIGGER trg_credit_sales_after_insert AFTER INSERT ON credit_sales
        FOR EACH ROW
        BEGIN
            INSERT INTO credit_sales_audit (credit_sale_id, shop_id, customer_id, total_cents, due_cents, created_at)
            VALUES (
                NEW.id,
                (SELECT o.shop_id FROM orders o WHERE o.id = NEW.order_id LIMIT 1),
                NEW.customer_id,
                COALESCE(NEW.total_amount, 0),
                COALESCE(NEW.due_amount, 0),
                NOW()
            );
        END;
        SQL
        );
    }

    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_credit_sales_after_insert');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_rebuild_credit_sales_summary');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_credit_sales_report');
        DB::unprepared('DROP FUNCTION IF EXISTS fn_product_credit_total');
        DB::unprepared('DROP FUNCTION IF EXISTS fn_customer_total_credit');
        DB::unprepared('DROP VIEW IF EXISTS v_product_credit_summary');
        DB::unprepared('DROP VIEW IF EXISTS v_customer_credit_summary');
        DB::unprepared('DROP VIEW IF EXISTS v_credit_sales_summary');
        DB::unprepared('DROP TABLE IF EXISTS credit_sales_audit');
    }
};
