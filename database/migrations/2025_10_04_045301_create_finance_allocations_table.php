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
        Schema::create('finance_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_id')->constrained('finance_sources')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('target_category_id')->constrained('finance_categories')->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('percentage', 5, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_allocations');
    }
};
