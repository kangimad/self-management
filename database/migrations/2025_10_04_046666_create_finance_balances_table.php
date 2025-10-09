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
        Schema::create('finance_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('source_id')->nullable()->constrained('finance_sources')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('transaction_id')->nullable()->constrained('finance_transactions')->cascadeOnDelete()->cascadeOnUpdate();
            $table->decimal('previous_balance', 15, 2)->default(0);
            $table->decimal('change_amount', 15, 2)->default(0);
            $table->decimal('current_balance', 15, 2)->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_balances');
    }
};
