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

        // New migration for payments table
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->decimal('amount', 15, 2);
            $table->text('note')->nullable();
            $table->timestamps();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
        });

        // Pivot table for payment-invoice relationship
        Schema::create('invoice_payment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_id');
            $table->unsignedBigInteger('invoice_id');
            $table->decimal('amount_applied', 15, 2);
            $table->timestamps();
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
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
