<?php
/**
 * Verify Database Cleanup - Check if stored procedures and views are removed
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Database Cleanup Verification ===" . PHP_EOL . PHP_EOL;

// Check stored procedures
echo "Checking for stored procedures..." . PHP_EOL;
$procedures = DB::select("SHOW PROCEDURE STATUS WHERE Db = DATABASE()");
echo "Found: " . count($procedures) . " stored procedures" . PHP_EOL;
if (count($procedures) > 0) {
    echo "WARNING: Stored procedures still exist!" . PHP_EOL;
    foreach ($procedures as $proc) {
        echo "  - {$proc->Name}" . PHP_EOL;
    }
} else {
    echo "✓ All stored procedures removed successfully!" . PHP_EOL;
}
echo PHP_EOL;

// Check views
echo "Checking for views..." . PHP_EOL;
$views = DB::select("SHOW FULL TABLES WHERE Table_type = 'VIEW'");
echo "Found: " . count($views) . " views" . PHP_EOL;
if (count($views) > 0) {
    echo "WARNING: Views still exist!" . PHP_EOL;
    foreach ($views as $view) {
        $viewName = array_values((array)$view)[0];
        echo "  - {$viewName}" . PHP_EOL;
    }
} else {
    echo "✓ All views removed successfully!" . PHP_EOL;
}
echo PHP_EOL;

// Check summary tables
echo "Checking for summary tables..." . PHP_EOL;
$tables = ['product_metrics', 'credit_summary'];
foreach ($tables as $table) {
    try {
        DB::select("SELECT 1 FROM {$table} LIMIT 1");
        echo "WARNING: Table '{$table}' still exists!" . PHP_EOL;
    } catch (\Exception $e) {
        echo "✓ Table '{$table}' removed successfully!" . PHP_EOL;
    }
}
echo PHP_EOL;

echo "=== Verification Complete ===" . PHP_EOL;
