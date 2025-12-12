<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RebuildDbSummaries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nexora:rebuild-summaries {--only=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuild product/customer/credit summaries and metrics by calling stored procedures.';

    public function handle()
    {
        $only = $this->option('only');

        $this->info('Starting rebuild of DB summaries...');

        $map = [
            'products' => 'sp_rebuild_product_metrics',
            'customers' => 'sp_rebuild_customer_summary',
            'credits' => 'sp_rebuild_credit_summary',
        ];

        if ($only) {
            if (!isset($map[$only])) {
                $this->error('Unknown target: ' . $only);
                return 1;
            }
            $procs = [ $map[$only] ];
        } else {
            $procs = array_values($map);
        }

        foreach ($procs as $proc) {
            try {
                $this->line('Calling ' . $proc . '...');
                DB::statement('CALL ' . $proc . '()');
                $this->info('OK: ' . $proc);
            } catch (\Exception $e) {
                $this->error('Failed to call ' . $proc . ': ' . $e->getMessage());
            }
        }

        $this->info('Rebuild complete.');
        return 0;
    }
}
