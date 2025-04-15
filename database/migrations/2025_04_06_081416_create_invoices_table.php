<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id(); // BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT
            $table->unsignedBigInteger('client_id'); // Foreign key to clients table
            $table->date('invoice_date'); // Invoice date
            $table->string('po_no')->nullable(); // Purchase Order No
            $table->string('invoice_no')->unique(); // Invoice No, must be unique
            $table->text('description')->nullable(); // Optional description
            $table->decimal('amount', 10, 2); // Invoice amount
            $table->date('due_date'); // Payment due date
            $table->enum('status', ['pending', 'paid', 'overdue'])->default('pending'); // Invoice status
            $table->timestamps(); // created_at & updated_at

            // Foreign key constraint
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');

            // Indexes
            $table->index('client_id');
            $table->index('invoice_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
