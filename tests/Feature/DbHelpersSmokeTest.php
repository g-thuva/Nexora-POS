<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DbHelpersSmokeTest extends TestCase
{
    /**
     * Quick smoke test to ensure DB helpers (views & stored procedures) are present and callable.
     * This avoids performing write-side operations in CI but verifies that the read-side helpers work.
     *
     * @return void
     */
    public function test_db_helpers_readables()
    {
        // These DB helpers rely on MySQL objects (views / stored procs). Skip if test DB is not MySQL.
        if (config('database.default') !== 'mysql') {
            $this->markTestSkipped('MySQL connection is required for DB helpers smoke test. Current connection: ' . config('database.default'));
        }

        // Check that the v_order_kpis view returns either an object or null without throwing
        $kpis = DB::table('v_order_kpis')->first();
        $this->assertTrue(is_object($kpis) || is_null($kpis));

        // Call read-side stored procedure and assert it does not throw and returns array
        $rows = [];
        try {
            $rows = DB::select('CALL sp_get_order_kpis()');
        } catch (\Exception $e) {
            $this->fail('Calling sp_get_order_kpis() threw: ' . $e->getMessage());
        }

        $this->assertIsArray($rows);

        // Check stock levels view
        $stock = DB::table('v_stock_levels')->limit(1)->get();
        $this->assertIsIterable($stock);

        // Call top selling proc with reasonable params (start, end, limit)
        try {
            $top = DB::select('CALL sp_get_top_selling_products(?, ?, ?)', [now()->subDays(30)->toDateString(), now()->toDateString(), 5]);
        } catch (\Exception $e) {
            $this->fail('Calling sp_get_top_selling_products threw: ' . $e->getMessage());
        }

        $this->assertIsArray($top);
    }
}
