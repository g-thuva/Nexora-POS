<?php

namespace App\Console\Commands;

use App\Enums\CreditStatus;
use App\Models\CreditSale;
use Illuminate\Console\Command;

class FixCreditSaleStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'credit-sales:fix-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix credit sale status for records with zero due amount';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fixing credit sale statuses...');

        // Find all credit sales with zero or negative due amount but not marked as PAID
        $salesToFix = CreditSale::where('due_amount', '<=', 0)
            ->where('status', '!=', CreditStatus::PAID->value)
            ->get();

        if ($salesToFix->isEmpty()) {
            $this->info('No records found that need fixing.');
            return 0;
        }

        $count = $salesToFix->count();
        $this->info("Found {$count} record(s) to fix.");

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        foreach ($salesToFix as $sale) {
            $sale->status = CreditStatus::PAID;
            $sale->due_amount = 0; // Ensure it's exactly 0
            $sale->save();
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("✓ Successfully updated {$count} credit sale record(s) to PAID status.");

        return 0;
    }
}
