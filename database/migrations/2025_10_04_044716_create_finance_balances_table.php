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
            $table->foreignId('source_id')->constrained('finance_sources')->onDelete('cascade')->onUpdate('cascade');
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
