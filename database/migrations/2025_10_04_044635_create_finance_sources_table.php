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
        Schema::create('finance_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->foreignId('source_type_id')->constrained('finance_source_types')->onDelete('cascade')->onUpdate('cascade')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_sources');
    }
};
