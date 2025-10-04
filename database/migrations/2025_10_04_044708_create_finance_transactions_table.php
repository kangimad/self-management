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
        Schema::create('finance_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_type_id')->constrained('finance_transaction_types')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('category_id')->constrained('finance_categories')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('source_id')->constrained('finance_sources')->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('amount', 15, 2);
            $table->date('date');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_transactions');
    }
};
