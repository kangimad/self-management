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
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('allocation_type', ['source', 'category'])->nullable();
            $table->foreignId('source_id')->nullable()->constrained('finance_sources')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('category_id')->nullable()->constrained('finance_categories')->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('percentage', 5, 2)->nullable();
            $table->foreignId('target_source_id')->nullable()->constrained('finance_sources')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('target_category_id')->nullable()->constrained('finance_categories')->onDelete('cascade')->onUpdate('cascade');
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
