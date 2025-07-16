<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateInvoiceStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-invoice-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();

        // Mark overdue
        \App\Models\Invoice::where('status', 'pending')
            ->where('due_date', '<', $now->format('Y-m-d'))
            ->update(['status' => 'overdue']);

        // Revert overdue to pending if due_date is now in the future
        \App\Models\Invoice::where('status', 'overdue')
            ->where('due_date', '>=', $now->format('Y-m-d'))
            ->update(['status' => 'pending']);
    }
}
