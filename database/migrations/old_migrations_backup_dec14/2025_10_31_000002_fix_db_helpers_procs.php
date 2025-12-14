<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $sql = <<<'SQL'
-- Redefine sp_rebuild_product_metrics with correct aliases
DROP PROCEDURE IF EXISTS sp_rebuild_product_metrics;
CREATE PROCEDURE sp_rebuild_product_metrics()
BEGIN
  DELETE FROM product_metrics;
  INSERT INTO product_metrics (product_id, total_sold, total_returns, last_sold_date)
    SELECT p.id AS product_id,
           IFNULL(s.total_sold,0) AS total_sold,
           IFNULL(r.total_returns,0) AS total_returns,
           GREATEST(IFNULL(s.order_date,'1970-01-01'), IFNULL(r.return_date,'1970-01-01')) AS last_sold_date
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
    ) r ON r.product_id = p.id;
END;

-- Redefine sp_rebuild_credit_summary using actual credit_sales columns
DROP PROCEDURE IF EXISTS sp_rebuild_credit_summary;
CREATE PROCEDURE sp_rebuild_credit_summary()
BEGIN
  DELETE FROM credit_summary;
  INSERT INTO credit_summary (customer_id, total_credit, outstanding_amount, overdue_count, last_payment_date)
    SELECT cs.customer_id,
           IFNULL(SUM(cs.total_amount),0) AS total_credit,
           IFNULL(SUM(cs.due_amount),0) AS outstanding_amount,
           IFNULL(SUM(CASE WHEN cs.due_date < CURDATE() AND (cs.due_amount) > 0 THEN 1 ELSE 0 END),0) AS overdue_count,
           IFNULL(MAX(cs.updated_at), NULL) AS last_payment_date
    FROM credit_sales cs
    GROUP BY cs.customer_id;
END;
SQL;

        DB::unprepared($sql);
    }

    public function down(): void
    {
        $drop = <<<'SQL'
DROP PROCEDURE IF EXISTS sp_rebuild_product_metrics;
DROP PROCEDURE IF EXISTS sp_rebuild_credit_summary;
SQL;
        DB::unprepared($drop);
    }
};
