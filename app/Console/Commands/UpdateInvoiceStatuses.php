<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Invoice;
use Illuminate\Console\Command;

class UpdateInvoiceStatuses extends Command
{
    protected $signature = 'invoices:update-status';
    protected $description = 'Update invoice statuses based on due dates';

    public function handle()
    {
        $now = Carbon::now();

        // Update overdue invoices
        $updated = Invoice::where('status', 'pending')
            ->where('due_date', '<', $now->format('Y-m-d'))
            ->update(['status' => 'overdue']);

        // Reset overdue invoices that have been paid
        $resetPaid = Invoice::where('status', 'overdue')
            ->where('status', 'paid')
            ->update(['status' => 'paid']);

        $this->info("Updated {$updated} invoices to overdue status");
        $this->info("Reset {$resetPaid} paid invoices status");

        return Command::SUCCESS;
    }
} 